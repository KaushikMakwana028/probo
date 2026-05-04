<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Questions extends CI_Controller
{
	private $trade_rate_limit_seconds = 5;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Category_model');
		$this->load->model('Wallet_model');

		if (!$this->session->userdata('user_id')) {
			redirect('login');
		}
	}

	public function index()
	{
		$user = $this->User_model->get_by_id($this->session->userdata('user_id'));

		if (!$user) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$selected_category_id = (int) $this->input->get('category_id');
		$selected_category = $selected_category_id > 0 ? $this->Category_model->get_category_with_questions($selected_category_id) : NULL;

		if ($selected_category) {
			$selected_category->questions = $this->Category_model->get_visible_questions_by_category_for_user($selected_category->id, $user->id);
			$selected_category->questions = $this->sort_questions_for_user_display($selected_category->questions, $this->Category_model->get_user_answers_by_category($user->id, $selected_category->id));
		}

		$categories = $this->Category_model->get_all_categories_with_visible_question_counts_for_user($user->id);
		$category_payload = $this->build_category_payload($user, $selected_category);

		// ✅ ADD THIS BLOCK
		if ($selected_category && !empty($selected_category->questions)) {

			foreach ($selected_category->questions as $q) {

				// real users
				$real_users = $this->Category_model->get_total_users_by_question($q->id);

				// admin users
				$admin_users = (int) (isset($q->admin_extra_users) ? $q->admin_extra_users : 0);

				// final total
				$q->total_users = $real_users + $admin_users;
			}
		}

		$data = array(
			'title' => 'Questions',
			'page_type' => 'dashboard',
			'user' => $user,
			'categories' => $categories,
			'selected_category' => $selected_category,
			'user_answers' => $category_payload['user_answers'],
			'answered_count' => $category_payload['answered_count'],
			'correct_count' => $category_payload['correct_count'],
			'wrong_count' => $category_payload['wrong_count'],
			'question_list_html' => $category_payload['html'],
			'active_page' => 'questions'
		);

		$this->load->view('includes/header', $data);
		$this->load->view('questions_view', $data);
		$this->load->view('includes/footer', $data);
	}

	public function category_data($category_id = 0)
	{
		$user = $this->User_model->get_by_id($this->session->userdata('user_id'));

		if (!$user) {
			$this->output
				->set_content_type('application/json')
				->set_status_header(401)
				->set_output(json_encode(array(
					'status' => 'error',
					'message' => 'Session expired.'
				)));
			return;
		}

		$selected_category = $this->Category_model->get_category_with_questions((int) $category_id);

		if ($selected_category) {
			$selected_category->questions = $this->Category_model->get_visible_questions_by_category_for_user($selected_category->id, $user->id);
			$selected_category->questions = $this->sort_questions_for_user_display($selected_category->questions, $this->Category_model->get_user_answers_by_category($user->id, $selected_category->id));
		}
		$payload = $this->build_category_payload($user, $selected_category);

		if ($selected_category && !empty($selected_category->questions)) {

			foreach ($selected_category->questions as $q) {

				$real_users = $this->Category_model->get_total_users_by_question($q->id);
				$admin_users = (int) (isset($q->admin_extra_users) ? $q->admin_extra_users : 0);

				$q->total_users = $real_users + $admin_users;
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'status' => 'ok',
				'category_id' => $selected_category ? (int) $selected_category->id : 0,
				'answered_count' => $payload['answered_count'],
				'total_questions' => $payload['total_questions'],
				'correct_count' => $payload['correct_count'],
				'wrong_count' => $payload['wrong_count'],
				'html' => $payload['html']
			)));
	}

	public function answer($question_id = 0)
	{
		$user = $this->User_model->get_by_id($this->session->userdata('user_id'));

		if (!$user) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$question = $this->Category_model->get_question((int) $question_id);

		if (!$question) {
			$this->session->set_flashdata('error', 'Selected question was not found.');
			redirect('questions');
		}

		$selected_category = $this->Category_model->get_category_with_questions((int) $question->category_id);

		if (!$selected_category) {
			$this->session->set_flashdata('error', 'Selected category was not found.');
			redirect('questions');
		}

		$selected_category->questions = $this->Category_model->get_visible_questions_by_category_for_user((int) $selected_category->id, (int) $user->id);
		$selected_category->questions = $this->sort_questions_for_user_display($selected_category->questions, $this->Category_model->get_user_answers_by_category($user->id, (int) $selected_category->id));
		$is_visible_question = FALSE;

		foreach ($selected_category->questions as $question_item) {
			if ((int) $question_item->id === (int) $question->id) {
				$is_visible_question = TRUE;
				break;
			}
		}

		if (!$is_visible_question) {
			$this->session->set_flashdata('error', 'This question is no longer available for you.');
			redirect('questions?category_id=' . (int) $selected_category->id);
		}

		$user_answers = $this->Category_model->get_user_answers_by_category($user->id, (int) $selected_category->id);
		$selected_answer_state = isset($user_answers[(int) $question->id]) ? $user_answers[(int) $question->id] : NULL;
		$price_history = $this->Category_model->get_question_price_history((int) $question->id, 20);
		$trade_breakdown = $this->Category_model->get_question_trade_breakdown((int) $question->id);
		$sell_trade_summary = $this->build_sell_trade_summary($question, $selected_answer_state);
		$answered_count = 0;
		$correct_count = 0;
		$wrong_count = 0;

		foreach ($selected_category->questions as $question_item) {
			$current_question_id = (int) $question_item->id;

			if (isset($user_answers[$current_question_id])) {
				$answered_count++;

				if (strtolower((string) $user_answers[$current_question_id]->answer) === strtolower((string) $question_item->answer_key)) {
					$correct_count++;
				} else {
					$wrong_count++;
				}
			}
		}
		// ✅ TOTAL USERS CALCULATION
		$real_users = $this->Category_model->get_total_users_by_question($question->id);
		$admin_users = (int) ($question->admin_extra_users ?? 0);
		$total_users = $real_users + $admin_users;

		$data = array(
			'title' => 'Answer Question',
			'page_type' => 'dashboard',
			'user' => $user,
			'categories' => $this->Category_model->get_all_categories_with_visible_question_counts_for_user($user->id),
			'selected_category' => $selected_category,
			'selected_question' => $question,
			'selected_answer_state' => $selected_answer_state,
			'sell_trade_summary' => $sell_trade_summary,
			'price_history' => $price_history,
			'trade_breakdown' => $trade_breakdown,
			'market_is_open' => $this->is_question_open_for_trade($question),
			'user_answers' => $user_answers,
			'answered_count' => $answered_count,
			'correct_count' => $correct_count,
			'wrong_count' => $wrong_count,
			'total_users' => $total_users,
			'active_page' => 'questions'
		);

		$this->load->view('includes/header', $data);
		$this->load->view('question_answer_view', $data);
		$this->load->view('includes/footer', $data);
	}
	public function view()
	{
		// Load model if not already
		$this->load->model('Category_model');

		$category_id = (int) $this->input->get('category_id');

		$selected_category = null;

		if ($category_id > 0) {
			$selected_category = $this->Category_model->get_category_with_questions($category_id);
		}

		$data = array(
			'categories' => $this->Category_model->get_all_categories(),
			'selected_category' => $selected_category,
			'total_questions' => $this->Category_model->count_all_questions()
		);

		$this->load->view('admin/questions_add_view', $data);
	}
	public function save_answers()
	{
		$user = $this->User_model->get_by_id($this->session->userdata('user_id'));

		if (!$user) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$category_id = (int) $this->input->post('category_id');
		$question_id = (int) $this->input->post('question_id');
		$category = $this->Category_model->get_category_with_questions($category_id);
		$user_answers = $this->Category_model->get_user_answers_by_category($user->id, $category_id);

		if (!$category) {
			$this->session->set_flashdata('error', 'Selected category not found.');
			redirect('questions');
		}

		if (empty($category->questions)) {
			$this->session->set_flashdata('error', 'No questions are available for this category.');
			redirect('questions?category_id=' . $category_id);
		}

		$selected_question = NULL;

		foreach ($category->questions as $question_item) {
			if ((int) $question_item->id === $question_id) {
				$selected_question = $question_item;
				break;
			}
		}

		if (!$selected_question) {
			$this->session->set_flashdata('error', 'Selected question was not found in this category.');
			redirect('questions?category_id=' . $category_id);
		}

		if (isset($user_answers[$question_id])) {
			$this->session->set_flashdata('error', 'This answer is already submitted and cannot be changed.');
			redirect('questions/answer/' . $question_id);
		}

		$selected_answer = strtolower(trim((string) $this->input->post('answer')));
		$selected_price = (float) $this->input->post('price');
		$selected_quantity = (int) $this->input->post('quantity');

		if (!in_array($selected_answer, array('yes', 'no'), TRUE)) {
			$this->session->set_flashdata('error', 'Please choose only Yes or No before submitting.');
			redirect('questions/answer/' . $question_id);
		}

		$market_total = max(1.0, (float) $selected_question->yes_price + (float) $selected_question->no_price);
		$max_price = max(0.5, $market_total - 0.5);

		if ($selected_price < 0.5 || $selected_price > $max_price || $selected_quantity < 1 || $selected_quantity > 1000) {
			$this->session->set_flashdata('error', 'Price must stay between Rs 0.50 and Rs ' . number_format($max_price, 2) . ', and quantity must stay between 1 and 1000.');
			redirect('questions/answer/' . $question_id);
		}

		if (!$this->is_question_open_for_trade($selected_question)) {
			$this->session->set_flashdata('error', 'This market is not open for trading right now.');
			redirect('questions/answer/' . $question_id);
		}

		if (!$this->passes_trade_rate_limit()) {
			$this->session->set_flashdata('error', 'Please wait a few seconds before placing another trade.');
			redirect('questions/answer/' . $question_id);
		}

		$stake_amount = round($selected_price * $selected_quantity, 2);
		$multiplier = $this->get_question_multiplier($selected_question, $selected_answer);
		$winning_amount = round($stake_amount * $multiplier, 2);

		if ($stake_amount <= 0) {
			$this->session->set_flashdata('error', 'Trade amount is invalid.');
			redirect('questions/answer/' . $question_id);
		}

		if ((float) $user->wallet_balance < $stake_amount) {
			$this->session->set_flashdata('error', 'Insufficient wallet balance for this trade.');
			redirect('questions/answer/' . $question_id);
		}

		$this->User_model->adjust_wallet_balance($user->id, -1 * $stake_amount);
		$question_label = trim(isset($selected_question->question) ? (string) $selected_question->question : '');
		$question_label = $question_label !== '' ? $question_label : ('Question #' . $question_id);

		$this->Wallet_model->add_transaction(array(
			'user_id' => (int) $user->id,
			'source_type' => 'trade_entry',
			'source_id' => $question_id,
			'type' => 'debit',
			'amount' => $stake_amount,
			'description' => 'Trade placed on ' . $question_label . ' (' . strtoupper($selected_answer) . ')'
		));
		$this->User_model->add_notification(array(
			'user_id' => (int) $user->id,
			'title' => 'Trade placed successfully',
			'message' => 'Your ' . strtoupper($selected_answer) . ' trade on **' . $question_label . '** was submitted for Rs ' . number_format($stake_amount, 2) . '.',
			'type' => 'trade'
		));

		$this->Category_model->save_user_answer(
			$user->id,
			$question_id,
			$selected_answer,
			$selected_price,
			$selected_quantity,
			$winning_amount,
			$stake_amount,
			NULL,
			array(
				'entry_payout_amount' => $winning_amount
			)
		);
		$this->Category_model->rebalance_question_prices($question_id);
		$this->session->set_userdata('last_trade_at', time());

		$this->session->set_flashdata('success', 'Trade placed successfully. The trade amount was deducted from your wallet. Result payout will be added after admin declares the result.');
		redirect('questions/answer/' . $question_id);
	}

	public function sell_trade()
	{
		$user = $this->User_model->get_by_id($this->session->userdata('user_id'));

		if (!$user) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$question_id = (int) $this->input->post('question_id');
		$question = $this->Category_model->get_question($question_id);

		if (!$question) {
			$this->session->set_flashdata('error', 'Selected question was not found.');
			redirect('questions');
		}

		$answer_state = $this->Category_model->get_user_answer((int) $user->id, $question_id);

		if (!$answer_state) {
			$this->session->set_flashdata('error', 'No trade was found for this question.');
			redirect('questions/answer/' . $question_id);
		}

		$sell_summary = $this->build_sell_trade_summary($question, $answer_state);

		if (empty($sell_summary['can_sell'])) {
			$this->session->set_flashdata('error', isset($sell_summary['message']) ? $sell_summary['message'] : 'This trade cannot be sold right now.');
			redirect('questions/answer/' . $question_id);
		}

		$settled_at = date('Y-m-d H:i:s');
		$this->db->trans_start();

		$settled = $this->Category_model->mark_answer_settlement((int) $answer_state->id, (float) $sell_summary['exit_amount'], $settled_at, array(
			'settlement_type' => 'sell',
			'sell_price' => (float) $sell_summary['current_price'],
			'sell_multiplier' => (float) $sell_summary['current_multiplier'],
			'sell_profit' => (float) $sell_summary['net_profit']
		));

		if (!$settled || $this->db->affected_rows() < 1) {
			$this->db->trans_complete();
			$this->session->set_flashdata('error', 'This trade was already settled or sold. Please refresh the question page.');
			redirect('questions/answer/' . $question_id);
		}

		$this->User_model->adjust_wallet_balance((int) $user->id, (float) $sell_summary['exit_amount']);

		$question_label = trim(isset($question->question) ? (string) $question->question : '');
		$question_label = $question_label !== '' ? $question_label : ('Question #' . $question_id);

		$this->Wallet_model->add_transaction(array(
			'user_id' => (int) $user->id,
			'source_type' => 'trade_sell',
			'source_id' => $question_id,
			'type' => 'credit',
			'amount' => (float) $sell_summary['exit_amount'],
			'description' => 'Trade sold on ' . $question_label . ' (' . strtoupper((string) $answer_state->answer) . ')'
		));

		$this->User_model->add_notification(array(
			'user_id' => (int) $user->id,
			'title' => 'Trade sold successfully',
			'message' => 'You sold your ' . strtoupper((string) $answer_state->answer) . ' trade on **' . $question_label . '** and Rs ' . number_format((float) $sell_summary['exit_amount'], 2) . ' was credited to your wallet.',
			'type' => 'trade'
		));

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'We could not complete the sell trade request right now. Please try again.');
			redirect('questions/answer/' . $question_id);
		}

		$this->session->set_flashdata('success', 'Trade sold successfully. Rs ' . number_format((float) $sell_summary['exit_amount'], 2) . ' was added to your wallet.');
		redirect('questions/answer/' . $question_id);
	}

	private function build_category_payload($user, $selected_category)
	{
		$user_answers = $selected_category ? $this->Category_model->get_user_answers_by_category($user->id, $selected_category->id) : array();
		$answered_count = 0;
		$correct_count = 0;
		$wrong_count = 0;
		$total_questions = $selected_category && !empty($selected_category->questions) ? count($selected_category->questions) : 0;

		if ($selected_category && !empty($selected_category->questions)) {
			$selected_category->questions = $this->sort_questions_for_user_display($selected_category->questions, $user_answers);
		}

		if ($selected_category && !empty($selected_category->questions)) {
			foreach ($selected_category->questions as $question_item) {

				$question_id = (int) $question_item->id;

				// ✅ FIXED VARIABLE
				$counts = $this->Category_model->get_question_user_counts($question_item->id);

				// ✅ ASSIGN TO CORRECT OBJECT
				$question_item->total_users = (int) $counts + (int) (isset($question_item->admin_extra_users) ? $question_item->admin_extra_users : 0);

				if (isset($user_answers[$question_id])) {
					$answered_count++;

					if (strtolower((string) $user_answers[$question_id]->answer) === strtolower((string) $question_item->answer_key)) {
						$correct_count++;
					} else {
						$wrong_count++;
					}
				}
			}
		}

		$html = $this->load->view('partials/questions_category_content', array(
			'selected_category' => $selected_category,
			'user_answers' => $user_answers,
			'answered_count' => $answered_count,
			'correct_count' => $correct_count,
			'wrong_count' => $wrong_count,
			'total_questions' => $total_questions
		), TRUE);

		return array(
			'user_answers' => $user_answers,
			'answered_count' => $answered_count,
			'correct_count' => $correct_count,
			'wrong_count' => $wrong_count,
			'total_questions' => $total_questions,
			'html' => $html
		);
	}

	private function sort_questions_for_user_display($questions, $user_answers = array())
	{
		if (empty($questions)) {
			return array();
		}

		$user_answers = is_array($user_answers) ? $user_answers : array();
		$indexed_questions = array_values($questions);

		usort($indexed_questions, function ($left, $right) use ($user_answers) {
			$left_bucket = $this->get_user_question_display_bucket($left, isset($user_answers[(int) $left->id]) ? $user_answers[(int) $left->id] : NULL);
			$right_bucket = $this->get_user_question_display_bucket($right, isset($user_answers[(int) $right->id]) ? $user_answers[(int) $right->id] : NULL);

			if ($left_bucket !== $right_bucket) {
				return $left_bucket - $right_bucket;
			}

			$left_start = $this->parse_question_timestamp(isset($left->start_time) ? $left->start_time : NULL);
			$right_start = $this->parse_question_timestamp(isset($right->start_time) ? $right->start_time : NULL);

			if ($left_start !== FALSE && $right_start !== FALSE && $left_start !== $right_start) {
				return $left_start < $right_start ? -1 : 1;
			}

			if ((int) $left->id === (int) $right->id) {
				return 0;
			}

			return ((int) $left->id < (int) $right->id) ? -1 : 1;
		});

		return $indexed_questions;
	}

	private function get_user_question_display_bucket($question, $answer_state = NULL)
	{
		if ($answer_state && !empty($answer_state->settled_at)) {
			return 4; // completed
		}

		if ($answer_state) {
			return 2; // review
		}

		return $this->is_question_open_for_trade($question) ? 1 : 3; // open, then draft/not-open
	}

	private function is_question_open_for_trade($question)
	{
		$status = strtolower(trim((string) $question->status));
		$now_ts = time();
		$start_ts = $this->parse_question_timestamp(isset($question->start_time) ? $question->start_time : NULL);
		$end_ts = $this->parse_question_timestamp(isset($question->end_time) ? $question->end_time : NULL);

		// Keep market availability aligned with the configured time window.
		// A question should trade until end_time unless it is clearly not tradable.
		if (in_array($status, array('draft', 'resolved'), TRUE)) {
			return FALSE;
		}

		// 'closed' status blocks trading regardless of time window
		if ($status === 'closed') {
			return FALSE;
		}

		if ($start_ts !== FALSE && $now_ts < $start_ts) {
			return FALSE;
		}

		if ($end_ts !== FALSE && $now_ts > $end_ts) {
			return FALSE;
		}

		return TRUE;
	}

	private function parse_question_timestamp($value)
	{
		$value = trim((string) $value);

		if ($value === '' || $value === '0000-00-00 00:00:00') {
			return FALSE;
		}

		return strtotime($value);
	}

	private function passes_trade_rate_limit()
	{
		$last_trade_at = (int) $this->session->userdata('last_trade_at');

		if ($last_trade_at > 0 && (time() - $last_trade_at) < $this->trade_rate_limit_seconds) {
			return FALSE;
		}

		return TRUE;
	}

	private function build_sell_trade_summary($question, $answer_state)
	{
		$summary = array(
			'can_sell'           => FALSE,
			'message'            => 'This trade cannot be sold right now.',
			'current_price'      => 0.0,
			'current_multiplier' => 0.0,
			'exit_amount'        => 0.0,
			'entry_amount'       => 0.0,
			'booked_return'      => 0.0,
			'net_profit'         => 0.0
		);

		if (!$question || !$answer_state) {
			$summary['message'] = 'No active trade found for this question.';
			return $summary;
		}

		if (!empty($answer_state->settled_at)) {
			$summary['message'] = 'This trade is already settled.';
			return $summary;
		}

		if (!$this->is_question_open_for_trade($question)) {
			$summary['message'] = 'Trade selling is available only while the market is open.';
			return $summary;
		}

		$answer_side     = strtolower((string) $answer_state->answer);
		$current_price   = $answer_side === 'no'
			? (float) $question->no_price
			: (float) $question->yes_price;
		$current_quantity    = max(1, (int) $answer_state->quantity);
		$entry_price         = isset($answer_state->price) ? (float) $answer_state->price : 0.0;
		$stake_amount        = isset($answer_state->stake_amount)
			? (float) $answer_state->stake_amount
			: 0.0;

		// The payout locked at entry time
		$booked_return = (isset($answer_state->entry_payout_amount) && (float) $answer_state->entry_payout_amount > 0)
			? (float) $answer_state->entry_payout_amount
			: ((float) $answer_state->payout_amount > 0
				? (float) $answer_state->payout_amount
				: $stake_amount);

		$entry_multiplier = 0.0;
		$entry_notional = $entry_price * $current_quantity;
		if ($entry_notional > 0 && $booked_return > 0) {
			$entry_multiplier = round($booked_return / $entry_notional, 4);
		} elseif ($stake_amount > 0 && $booked_return > 0) {
			$entry_multiplier = round($booked_return / $stake_amount, 4);
		}
		if ($entry_multiplier <= 0) {
			$entry_multiplier = $this->get_question_multiplier($question, $answer_side);
		}

		$current_multiplier = $this->get_question_multiplier($question, $answer_side);
		$stored_peak_price = isset($answer_state->peak_sell_price) ? (float) $answer_state->peak_sell_price : 0.0;
		$stored_peak_multiplier = isset($answer_state->peak_sell_multiplier) ? (float) $answer_state->peak_sell_multiplier : 0.0;
		$peak_price = max($stored_peak_price, $current_price, $entry_price);
		$peak_multiplier = max($stored_peak_multiplier, $current_multiplier, $entry_multiplier);

		// Persist higher admin/live peaks so sell stays available even if reduced later.
		if (
			isset($answer_state->id) &&
			(
				$peak_price > $stored_peak_price + 0.0001 ||
				$peak_multiplier > $stored_peak_multiplier + 0.0001
			)
		) {
			$this->db->where('id', (int) $answer_state->id);
			$this->db->where('settled_at IS NULL', NULL, FALSE);
			$this->db->update('user_question_answers', array(
				'peak_sell_price' => round($peak_price, 2),
				'peak_sell_multiplier' => round($peak_multiplier, 2)
			));
		}

		$effective_price = $peak_price;
		$effective_multiplier = $peak_multiplier;
		$exit_amount = round($effective_price * $current_quantity * $effective_multiplier, 2);

		// Net profit in sell box should compare current return vs locked payout.
		$net_profit = round($exit_amount - $booked_return, 2);

		$summary['current_price']      = $effective_price;
		$summary['current_multiplier'] = $effective_multiplier;
		$summary['exit_amount']        = $exit_amount;
		$summary['entry_amount']       = $stake_amount;
		$summary['booked_return']      = $booked_return;
		$summary['net_profit']         = $net_profit;

		// ✅ CORRECT unlock condition: user must be in profit vs what they paid
		$is_higher_than_entry = ($effective_price > $entry_price + 0.0001) || ($effective_multiplier > $entry_multiplier + 0.0001);

		if (!$is_higher_than_entry) {
			$summary['message'] = 'Sell option will unlock once edited price or multiplier moves above your original trade values.';
			return $summary;
		}

		$summary['can_sell'] = TRUE;
		$summary['message']  = 'You can sell this trade now based on the highest edited price/multiplier above your original trade values.';
		return $summary;
	}

	private function get_question_multiplier($question, $answer)
	{
		$answer_key = strtolower(trim((string) $answer));
		$fallback = 1.25;

		if ($question && isset($question->multiplier) && (float) $question->multiplier > 0) {
			$fallback = (float) $question->multiplier;
		}

		if (!$question) {
			return $fallback;
		}

		if ($answer_key === 'no' && isset($question->no_multiplier) && (float) $question->no_multiplier > 0) {
			return (float) $question->no_multiplier;
		}

		if ($answer_key === 'yes' && isset($question->yes_multiplier) && (float) $question->yes_multiplier > 0) {
			return (float) $question->yes_multiplier;
		}

		return $fallback;
	}
}
