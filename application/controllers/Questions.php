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
		}
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
			'categories' => $this->Category_model->get_all_categories(),
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
			'categories' => $this->Category_model->get_all_categories(),
			'selected_category' => $selected_category,
			'selected_question' => $question,
			'selected_answer_state' => $selected_answer_state,
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
		$multiplier = isset($selected_question->multiplier) ? (float)$selected_question->multiplier : 1.25;

		$winning_amount = round($selected_price * $selected_quantity * $multiplier, 2);

		if ($stake_amount <= 0) {
			$this->session->set_flashdata('error', 'Trade amount is invalid.');
			redirect('questions/answer/' . $question_id);
		}

		if ((float) $user->wallet_balance < $stake_amount) {
			$this->session->set_flashdata('error', 'Insufficient wallet balance for this trade.');
			redirect('questions/answer/' . $question_id);
		}

		$this->User_model->adjust_wallet_balance($user->id, -1 * $stake_amount);
		$this->Wallet_model->add_transaction(array(
			'user_id' => (int) $user->id,
			'source_type' => 'trade_entry',
			'source_id' => $question_id,
			'type' => 'debit',
			'amount' => $stake_amount,
			'description' => 'Trade placed on question #' . $question_id . ' (' . strtoupper($selected_answer) . ')'
		));
		$this->User_model->add_notification(array(
			'user_id' => (int) $user->id,
			'title' => 'Trade placed successfully',
			'message' => 'Your ' . strtoupper($selected_answer) . ' trade on question #' . $question_id . ' was submitted for Rs ' . number_format($stake_amount, 2) . '.',
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
			NULL
		);
		$this->Category_model->rebalance_question_prices($question_id);
		$this->session->set_userdata('last_trade_at', time());

		$this->session->set_flashdata('success', 'Trade placed successfully. The trade amount was deducted from your wallet. Result payout will be added after admin declares the result.');
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

	private function is_question_open_for_trade($question)
	{
		$status = strtolower(trim((string) $question->status));
		$now_ts = time();
		$start_ts = $this->parse_question_timestamp(isset($question->start_time) ? $question->start_time : NULL);
		$end_ts = $this->parse_question_timestamp(isset($question->end_time) ? $question->end_time : NULL);

		if ($status !== 'open') {
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

		$formats = array(
			'Y-m-d H:i:s',
			'Y-m-d H:i',
			'Y-m-d\TH:i',
			'd-m-Y H:i',
			'd/m/Y H:i'
		);

		foreach ($formats as $format) {
			$date = DateTime::createFromFormat($format, $value);
			if ($date instanceof DateTime) {
				return $date->getTimestamp();
			}
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
}
