<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Questions extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Category_model');
		$this->load->model('Wallet_model');

		if (!$this->session->userdata('admin_logged_in')) {
			redirect('admin/login');
		}
	}

	public function index()
	{
		redirect('admin/questions/add');
	}

	public function add()
	{
		$admin = $this->get_admin();

		$data = array(
			'title' => 'Add Questions',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'active_page' => 'questions_add',
			'categories' => $this->Category_model->get_all_categories(),
			'total_questions' => $this->Category_model->count_all_questions()
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/questions_add_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function view()
	{
		$admin = $this->get_admin();
		$selected_category_id = (int) $this->input->get('category_id');
		$selected_category = $selected_category_id > 0 ? $this->Category_model->get_category_with_questions($selected_category_id) : NULL;

		if ($selected_category && !empty($selected_category->questions)) {
			foreach ($selected_category->questions as $question_item) {
				$trade_breakdown = $this->Category_model->get_question_trade_breakdown((int) $question_item->id);
				$question_item->trade_totals = $trade_breakdown;
				$question_item->user_counts = array(
					'yes_users' => isset($trade_breakdown['yes_users']) ? (int) $trade_breakdown['yes_users'] : 0,
					'no_users' => isset($trade_breakdown['no_users']) ? (int) $trade_breakdown['no_users'] : 0
				);

				$real_users = $this->Category_model->get_total_users_by_question($question_item->id);
				$admin_users = (int) (isset($question_item->admin_extra_users) ? $question_item->admin_extra_users : 0);

				$question_item->real_users = $real_users;
				$question_item->admin_users = $admin_users;
				$question_item->total_users = $real_users + $admin_users;
			}
		}

		$data = array(
			'title' => 'View Questions',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'active_page' => 'questions_view',
			'categories' => $this->Category_model->get_all_categories(),
			'selected_category' => $selected_category,
			'total_questions' => $this->Category_model->count_all_questions()
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/questions_list_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function completed()
	{
		$this->render_questions_listing_page('completed');
	}

	public function detail($id)
	{
		$admin = $this->get_admin();
		$context = $this->build_question_detail_context((int) $id);

		if (!$context) {
			$this->session->set_flashdata('error', 'Question details could not be loaded.');
			redirect('admin/questions/view');
		}

		$data = array(
			'title' => 'Question Details',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'active_page' => $this->get_question_listing_active_page($context['selected_question']),
			'categories' => $this->Category_model->get_all_categories(),
			'selected_category' => $context['selected_category'],
			'selected_question' => $context['selected_question'],
			'list_page_url' => $this->get_question_listing_url($context['selected_question']),
			'total_questions' => $this->Category_model->count_all_questions()
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/question_detail_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function detail_users($id)
	{
		$admin = $this->get_admin();
		$context = $this->build_question_detail_context((int) $id);

		if (!$context) {
			$this->session->set_flashdata('error', 'Question details could not be loaded.');
			redirect('admin/questions/view');
		}

		$data = array(
			'title' => 'Question User Details',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'active_page' => $this->get_question_listing_active_page($context['selected_question']),
			'categories' => $this->Category_model->get_all_categories(),
			'selected_category' => $context['selected_category'],
			'selected_question' => $context['selected_question'],
			'list_page_url' => $this->get_question_listing_url($context['selected_question']),
			'total_questions' => $this->Category_model->count_all_questions()
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/question_user_details_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function detail_users_data($id)
	{
		$this->get_admin();
		$context = $this->build_question_detail_context((int) $id);

		if (!$context) {
			$this->output
				->set_content_type('application/json')
				->set_status_header(404)
				->set_output(json_encode(array(
					'status' => 'error',
					'message' => 'Question not found.'
				)));
			return;
		}

		// 🔥 GET FILTER INPUTS
		$from = $this->input->get('from', TRUE);
		$to   = $this->input->get('to', TRUE);

		// 🔥 CONVERT TO MYSQL DATETIME FORMAT
		$from_sql = !empty($from) ? date('Y-m-d H:i:s', strtotime($from)) : '';
		$to_sql   = !empty($to)   ? date('Y-m-d H:i:s', strtotime($to)) : '';

		// 🔥 FETCH DATA FROM MODEL
		$report = $this->Category_model->get_question_user_trade_report((int) $id, array(
			'search' => $this->input->get('search', TRUE),
			'answer' => $this->input->get('answer', TRUE),
			'result' => $this->input->get('result', TRUE),
			'page' => $this->input->get('page', TRUE),
			'per_page' => $this->input->get('per_page', TRUE),
			'from' => $from_sql,
			'to'   => $to_sql
		));

		// 🔥 FORMAT RESPONSE
		$rows = array();
		foreach ($report['rows'] as $row) {
			$rows[] = array(
				'id' => (int) $row->id,
				'user_id' => (int) $row->user_id,
				'name' => (string) $row->name,
				'mobile' => (string) $row->mobile,
				'email' => (string) $row->email,
				'answer' => strtolower((string) $row->answer),
				'quantity' => (int) $row->quantity,
				'stake_amount' => (float) $row->stake_amount,
				'payout_amount' => (float) $row->payout_amount,
				'win_amount' => (float) $row->payout_amount,
				'result_status' => (string) $row->result_status,
				'price' => (float) $row->price,
				'created_at' => $row->created_at
			);
		}

		// 🔥 OUTPUT JSON
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'status' => 'ok',
				'rows' => $rows,
				'total' => (int) $report['total'],
				'page' => (int) $report['page'],
				'per_page' => (int) $report['per_page']
			)));
	}

	public function create()
	{
		$this->get_admin();
		$this->form_validation->set_rules('category_id', 'Category', 'required|integer');
		$this->form_validation->set_rules('question_count', 'Question count', 'required|integer|greater_than[0]|less_than_equal_to[100]');

		if ($this->form_validation->run() === FALSE) {
			$this->set_validation_error_flashdata();
			redirect('admin/questions/add');
		}

		$category_id = (int) $this->input->post('category_id');
		$question_count = (int) $this->input->post('question_count');
		$category = $this->Category_model->get_category($category_id);

		if (!$category) {
			$this->session->set_flashdata('error', 'Selected category does not exist.');
			redirect('admin/questions/add');
		}

		$questions_input = $this->input->post('questions');
		$yes_prices = $this->input->post('yes_prices');
		$no_prices = $this->input->post('no_prices');
		$yes_multipliers = $this->input->post('yes_multipliers');
		$no_multipliers = $this->input->post('no_multipliers');
		$start_time = trim((string) $this->input->post('start_time', TRUE));
		$end_time = trim((string) $this->input->post('end_time', TRUE));
		$start_time_sql = $this->normalize_datetime_input($start_time);
		$end_time_sql = $this->normalize_datetime_input($end_time);
		$status = strtolower(trim((string) $this->input->post('status', TRUE)));
		$batch = array();

		if (!$this->is_valid_question_status($status)) {
			$this->session->set_flashdata('error', 'Please choose a valid market status.');
			redirect('admin/questions/add');
		}

		if (($start_time !== '' && $start_time_sql === NULL) || ($end_time !== '' && $end_time_sql === NULL)) {
			$this->session->set_flashdata('error', 'Please enter valid start and end time values.');
			redirect('admin/questions/add');
		}

		if (!$this->validate_question_window($start_time_sql, $end_time_sql)) {
			$this->session->set_flashdata('error', 'Start time must be earlier than end time.');
			redirect('admin/questions/add');
		}

		for ($i = 0; $i < $question_count; $i++) {
			$question_text = $this->normalize_question_text(isset($questions_input[$i]) ? $questions_input[$i] : '');
			$yes_price = $this->normalize_market_price(isset($yes_prices[$i]) ? $yes_prices[$i] : 0);
			$no_price = $this->normalize_market_price(isset($no_prices[$i]) ? $no_prices[$i] : 0);
			$yes_multiplier = 1.25;
			$no_multiplier = 1.25;

			if ($question_text === '') {
				$this->session->set_flashdata('error', 'Please fill all question textboxes before saving.');
				redirect('admin/questions/add?category_id=' . $category_id . '&question_count=' . $question_count);
			}

			if (!$this->is_valid_price_pair($yes_price, $no_price)) {
				$this->session->set_flashdata('error', 'YES and NO prices must both be at least Rs 0.50.');
				redirect('admin/questions/add?category_id=' . $category_id . '&question_count=' . $question_count);
			}

			$batch[] = array(
				'category_id' => $category_id,
				'question' => $question_text,
				'yes_price' => $yes_price,
				'base_yes_price' => $yes_price,
				'no_price' => $no_price,
				'base_no_price' => $no_price,
				'yes_multiplier' => $yes_multiplier,
				'no_multiplier' => $no_multiplier,
				'start_time' => $start_time_sql,
				'end_time' => $end_time_sql,
				'status' => $status
			);
		}

		$created = $this->Category_model->create_questions_batch($batch);

		if (!$created) {
			$this->session->set_flashdata('error', 'Questions could not be added. Please confirm the question table exists.');
			redirect('admin/questions/add');
		}

		$created_questions = array_slice(array_reverse($this->Category_model->get_questions_by_category($category_id)), 0, count($batch));

		foreach ($created_questions as $created_question) {
			$this->Category_model->record_price_history((int) $created_question->id, (float) $created_question->yes_price, (float) $created_question->no_price);
		}

		$this->session->set_flashdata('success', 'Questions added successfully.');
		redirect('admin/questions/view?category_id=' . $category_id);
	}

	public function edit($id)
	{
		$admin = $this->get_admin();
		$question = $this->Category_model->get_question((int) $id);

		if (!$question) {
			$this->session->set_flashdata('error', 'Question not found.');
			redirect('admin/questions/view');
		}

		$question->trade_totals = $this->Category_model->get_question_trade_breakdown((int) $id);

		$data = array(
			'title' => 'Edit Question',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'active_page' => $this->get_question_listing_active_page($question),
			'question' => $question,
			'categories' => $this->Category_model->get_all_categories(),
			'list_page_url' => $this->get_question_listing_url($question)
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/question_edit_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function update($id)
	{
		$this->get_admin();
		$id = (int) $id;
		$question = $this->Category_model->get_question($id);

		if (!$question) {
			$this->session->set_flashdata('error', 'Question not found.');
			redirect('admin/questions/view');
		}

		$this->form_validation->set_rules('category_id', 'Category', 'required|integer');
		$this->form_validation->set_rules('question', 'Question', 'required|trim');
		$this->form_validation->set_rules('yes_price', 'Yes price', 'required|numeric');
		$this->form_validation->set_rules('no_price', 'No price', 'required|numeric');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[draft,open,closed]');
		$this->form_validation->set_rules('admin_yes_quantity', 'Yes quantity', 'required|integer|greater_than_equal_to[0]');
		$this->form_validation->set_rules('admin_no_quantity', 'No quantity', 'required|integer|greater_than_equal_to[0]');

		if ($this->form_validation->run() === FALSE) {
			$this->set_validation_error_flashdata();
			redirect('admin/questions/edit/' . $id);
		}

		$category_id = (int) $this->input->post('category_id');
		$category = $this->Category_model->get_category($category_id);
		$question_text = $this->normalize_question_text($this->input->post('question', TRUE));
		$yes_price = $this->normalize_market_price($this->input->post('yes_price', TRUE));
		$no_price = $this->normalize_market_price($this->input->post('no_price', TRUE));
		$yes_multiplier = 1.25;
		$no_multiplier = 1.25;
		$entered_yes_quantity = (int) $this->input->post('admin_yes_quantity', TRUE);
		$entered_no_quantity = (int) $this->input->post('admin_no_quantity', TRUE);
		$status = strtolower(trim((string) $this->input->post('status', TRUE)));
		$start_time = trim((string) $this->input->post('start_time', TRUE));
		$end_time = trim((string) $this->input->post('end_time', TRUE));
		$start_time_sql = $this->normalize_datetime_input($start_time);
		$end_time_sql = $this->normalize_datetime_input($end_time);
		$trade_breakdown = $this->Category_model->get_question_trade_breakdown($id);
		$actual_yes_quantity = isset($trade_breakdown['actual_yes_quantity']) ? (int) $trade_breakdown['actual_yes_quantity'] : 0;
		$actual_no_quantity = isset($trade_breakdown['actual_no_quantity']) ? (int) $trade_breakdown['actual_no_quantity'] : 0;
		$total_actual_quantity = $actual_yes_quantity + $actual_no_quantity;

		if (!$category) {
			$this->session->set_flashdata('error', 'Selected category not found.');
			redirect('admin/questions/edit/' . $id);
		}

		if (!$this->is_valid_price_pair($yes_price, $no_price)) {
			$this->session->set_flashdata('error', 'YES and NO prices must both be at least Rs 0.50.');
			redirect('admin/questions/edit/' . $id);
		}

		if (($start_time !== '' && $start_time_sql === NULL) || ($end_time !== '' && $end_time_sql === NULL)) {
			$this->session->set_flashdata('error', 'Please enter valid start and end time values.');
			redirect('admin/questions/edit/' . $id);
		}

		if (!$this->validate_question_window($start_time_sql, $end_time_sql)) {
			$this->session->set_flashdata('error', 'Start time must be earlier than end time.');
			redirect('admin/questions/edit/' . $id);
		}

		if ($entered_yes_quantity < $actual_yes_quantity || $entered_no_quantity < $actual_no_quantity) {
			$this->session->set_flashdata('error', 'Yes/No quantity cannot be less than the current live trade quantity.');
			redirect('admin/questions/edit/' . $id);
		}

		$update_data = array(
			'category_id' => $category_id,
			'question' => $question_text,
			'base_yes_price' => $yes_price,
			'base_no_price' => $no_price,
			'yes_multiplier' => $yes_multiplier,
			'no_multiplier' => $no_multiplier,
			'answer_key' => '',
			'start_time' => $start_time_sql,
			'end_time' => $end_time_sql,
			'status' => $status,
			'result_declared_at' => NULL
		);

		if ($total_actual_quantity <= 0) {
			$update_data['yes_price'] = $yes_price;
			$update_data['no_price'] = $no_price;
		}

		if ($this->db->field_exists('admin_yes_quantity', 'category_questions')) {
			$update_data['admin_yes_quantity'] = max(0, $entered_yes_quantity - $actual_yes_quantity);
		}

		if ($this->db->field_exists('admin_no_quantity', 'category_questions')) {
			$update_data['admin_no_quantity'] = max(0, $entered_no_quantity - $actual_no_quantity);
		}

		$current_question_text = trim((string) ($question->question ?? ''));
		$current_status = strtolower(trim((string) ($question->status ?? '')));
		$current_start_time = isset($question->start_time) ? (string) $question->start_time : NULL;
		$current_end_time = isset($question->end_time) ? (string) $question->end_time : NULL;
		$current_extra_yes_quantity = isset($question->admin_yes_quantity) ? (int) $question->admin_yes_quantity : 0;
		$current_extra_no_quantity = isset($question->admin_no_quantity) ? (int) $question->admin_no_quantity : 0;
		$new_extra_yes_quantity = isset($update_data['admin_yes_quantity']) ? (int) $update_data['admin_yes_quantity'] : $current_extra_yes_quantity;
		$new_extra_no_quantity = isset($update_data['admin_no_quantity']) ? (int) $update_data['admin_no_quantity'] : $current_extra_no_quantity;

		$question_changed = (
			(int) ($question->category_id ?? 0) !== $category_id ||
			$current_question_text !== $question_text ||
			abs((float) (($question->base_yes_price ?? $question->yes_price ?? 0)) - $yes_price) > 0.0001 ||
			abs((float) (($question->base_no_price ?? $question->no_price ?? 0)) - $no_price) > 0.0001 ||
			abs((float) ($question->yes_multiplier ?? 0) - $yes_multiplier) > 0.0001 ||
			abs((float) ($question->no_multiplier ?? 0) - $no_multiplier) > 0.0001 ||
			$current_status !== $status ||
			(string) $current_start_time !== (string) $start_time_sql ||
			(string) $current_end_time !== (string) $end_time_sql ||
			$current_extra_yes_quantity !== $new_extra_yes_quantity ||
			$current_extra_no_quantity !== $new_extra_no_quantity
		);

		if ($question_changed) {
			$update_data['last_trade_edit_at'] = date('Y-m-d H:i:s');
		}

		$updated = $this->Category_model->update_question($id, $update_data);

		if (!$updated) {
			$this->session->set_flashdata('error', 'Question could not be updated.');
			redirect('admin/questions/edit/' . $id);
		}

		if ($total_actual_quantity > 0) {
			$this->Category_model->rebalance_question_prices($id);
		} else {
			$this->Category_model->record_price_history($id, $yes_price, $no_price);
		}

		$this->session->set_flashdata('success', 'Question updated successfully. Please save the answer key again from View Questions.');
		redirect('admin/questions/detail/' . $id);
	}

	public function save_answer_keys()
	{
		$this->get_admin();
		$category_id = (int) $this->input->post('category_id');
		$selected_question_id = (int) $this->input->post('question_id');
		$category = $this->Category_model->get_category_with_questions($category_id);

		if (!$category || empty($category->questions)) {
			$this->session->set_flashdata('error', 'Selected category has no questions.');
			redirect('admin/questions/view');
		}

		$answer_keys = $this->input->post('answer_keys');
		if ($selected_question_id <= 0 && is_array($answer_keys)) {
			$answer_key_ids = array_keys($answer_keys);
			$selected_question_id = !empty($answer_key_ids) ? (int) $answer_key_ids[0] : 0;
		}

		if (!is_array($answer_keys) || !$this->Category_model->save_answer_keys($category_id, $answer_keys)) {
			$this->session->set_flashdata('error', 'Please select at least one valid Yes or No answer key.');
			redirect($selected_question_id > 0 ? 'admin/questions/detail/' . $selected_question_id : 'admin/questions/view?category_id=' . $category_id);
		}

		$resolved_any = FALSE;

		foreach ((array) $answer_keys as $question_id => $answer_key) {
			$question_id = (int) $question_id;
			$answer_key = strtolower(trim((string) $answer_key));

			if (!in_array($answer_key, array('yes', 'no'), TRUE)) {
				continue;
			}

			$question = $this->Category_model->get_question($question_id);

			if (!$question || (int) $question->category_id !== $category_id) {
				continue;
			}

			$this->Category_model->update_question($question_id, array(
				'status' => 'resolved',
				'result_declared_at' => date('Y-m-d H:i:s')
			));
			$this->settle_question_market($question_id, $answer_key);
			$resolved_any = TRUE;
		}

		$this->session->set_flashdata('success', $resolved_any ? 'Answer key saved successfully and winning amount credited to user wallets.' : 'Answer key saved successfully.');
		redirect('admin/questions/completed?category_id=' . $category_id);
	}

	public function delete($id)
	{
		$this->get_admin();
		$id = (int) $id;
		$question = $this->Category_model->get_question($id);

		if (!$question) {
			$this->session->set_flashdata('error', 'Question not found.');
			redirect('admin/questions/view');
		}

		if (!$this->Category_model->delete_question($id)) {
			$this->session->set_flashdata('error', 'Question could not be deleted. Please confirm the question table exists.');
			redirect($this->get_question_listing_url($question));
		}

		$this->session->set_flashdata('success', 'Question deleted successfully.');
		redirect($this->get_question_listing_url($question));
	}

	public function live_stats($category_id = 0)
	{
		$this->get_admin();
		$category = $this->Category_model->get_category((int) $category_id);

		if (!$category) {
			$this->output
				->set_content_type('application/json')
				->set_status_header(404)
				->set_output(json_encode(array(
					'status' => 'error',
					'message' => 'Category not found.'
				)));
			return;
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'status' => 'ok',
				'questions' => $this->Category_model->get_live_questions_snapshot_by_category((int) $category_id),
				'generated_at' => date('Y-m-d H:i:s')
			)));
	}

	private function get_admin()
	{
		$admin = $this->User_model->get_by_id($this->session->userdata('admin_id'));

		if (!$admin || (int) $admin->role !== 1) {
			$this->session->unset_userdata(array(
				'admin_id',
				'admin_name',
				'admin_mobile',
				'admin_email',
				'admin_logged_in'   // 🔥 VERY IMPORTANT
			));
			redirect('admin/login');
		}

		return $admin;
	}

	private function render_questions_listing_page($mode = 'active')
	{
		$admin = $this->get_admin();
		$list_mode = $mode === 'completed' ? 'completed' : 'active';
		$questions_payload = $this->build_question_listing_payload($list_mode);
		$stats = $this->build_question_listing_stats($questions_payload);

		$data = array(
			'title' => $list_mode === 'completed' ? 'Completed Questions' : 'View Questions',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'active_page' => $list_mode === 'completed' ? 'questions_completed' : 'questions_view',
			'categories' => $this->Category_model->get_all_categories(),
			'total_questions' => $this->Category_model->count_all_questions(),
			'list_mode' => $list_mode,
			'initial_category_id' => (int) $this->input->get('category_id'),
			'questions_payload' => $questions_payload,
			'listing_stats' => $stats
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view($list_mode === 'completed' ? 'admin/completed_questions_list_view' : 'admin/questions_list_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	private function build_question_listing_payload($mode = 'active')
	{
		$questions = $this->Category_model->get_all_questions();
		$payload = array();
		$now_ts = time();

		foreach ($questions as $question_item) {
			$answer_key = strtolower(trim((string) $question_item->answer_key));
			$item_saved = in_array($answer_key, array('yes', 'no'), TRUE);

			if ($mode === 'completed' && !$item_saved) {
				continue;
			}

			if ($mode !== 'completed' && $item_saved) {
				continue;
			}

			$real_users = $this->Category_model->get_total_users_by_question((int) $question_item->id);
			$admin_extra = (int) (isset($question_item->admin_extra_users) ? $question_item->admin_extra_users : 0);
			$total_joined_users = $real_users + $admin_extra;

			$start_ts = (!empty($question_item->start_time) && $question_item->start_time !== '0000-00-00 00:00:00') ? strtotime($question_item->start_time) : FALSE;
			$end_ts = (!empty($question_item->end_time) && $question_item->end_time !== '0000-00-00 00:00:00') ? strtotime($question_item->end_time) : FALSE;
			$timing_class = 'live';
			$timing_label = 'Live';
			$sort_weight = 1;
			$status = strtolower((string) $question_item->status);

			if ($start_ts && $now_ts < $start_ts) {
				$timing_class = 'upcoming';
				$timing_label = 'Upcoming';
				$sort_weight = 2;
			} elseif ($end_ts && $now_ts > $end_ts) {
				$timing_class = 'ended';
				$timing_label = 'Ended';
				$sort_weight = 3;
			} elseif ($status !== 'open') {
				$timing_class = 'ended';
				$timing_label = ucfirst($status);
				$sort_weight = 3;
			}

			$payload[] = array(
				'id' => (int) $question_item->id,
				'category_id' => (int) $question_item->category_id,
				'category_name' => (string) $question_item->category_name,
				'question' => (string) $question_item->question,
				'yes_price' => number_format((float) $question_item->yes_price, 2),
				'no_price' => number_format((float) $question_item->no_price, 2),
				'start_time' => !empty($question_item->start_time) ? (string) $question_item->start_time : 'Not set',
				'end_time' => !empty($question_item->end_time) ? (string) $question_item->end_time : 'Not set',
				'item_saved' => $item_saved,
				'answer_key' => $answer_key,
				'real_users' => $real_users,
				'timing_class' => $timing_class,
				'timing_label' => $timing_label,
				'sort_weight' => $sort_weight,
				'status' => $status,
				'result_declared_at' => !empty($question_item->result_declared_at) ? (string) $question_item->result_declared_at : '',
				'detail_url' => site_url('admin/questions/detail/' . (int) $question_item->id)
			);
		}

		usort($payload, function ($a, $b) use ($mode) {
			if ($mode === 'completed') {
				$time_a = !empty($a['result_declared_at']) ? strtotime($a['result_declared_at']) : 0;
				$time_b = !empty($b['result_declared_at']) ? strtotime($b['result_declared_at']) : 0;

				if ($time_a === $time_b) {
					return $b['id'] <=> $a['id'];
				}

				return $time_b <=> $time_a;
			}

			if ($a['sort_weight'] === $b['sort_weight']) {
				return $b['id'] <=> $a['id'];
			}

			return $a['sort_weight'] <=> $b['sort_weight'];
		});

		return $payload;
	}

	private function build_question_listing_stats($questions_payload)
	{
		$category_ids = array();
		$saved_total = 0;

		foreach ($questions_payload as $question_item) {
			$category_ids[(int) $question_item['category_id']] = TRUE;
			if (!empty($question_item['item_saved'])) {
				$saved_total++;
			}
		}

		return array(
			'page_total' => count($questions_payload),
			'category_total' => count($category_ids),
			'saved_total' => $saved_total
		);
	}

	private function get_question_listing_active_page($question)
	{
		$answer_key = strtolower(trim((string) (isset($question->answer_key) ? $question->answer_key : '')));
		return in_array($answer_key, array('yes', 'no'), TRUE) ? 'questions_completed' : 'questions_view';
	}

	private function get_question_listing_url($question)
	{
		$category_id = isset($question->category_id) ? (int) $question->category_id : 0;
		$answer_key = strtolower(trim((string) (isset($question->answer_key) ? $question->answer_key : '')));
		$path = in_array($answer_key, array('yes', 'no'), TRUE) ? 'admin/questions/completed' : 'admin/questions/view';
		return $path . ($category_id > 0 ? '?category_id=' . $category_id : '');
	}

	private function build_question_detail_context($id)
	{
		$id = (int) $id;
		$question = $this->Category_model->get_question($id);

		if (!$question) {
			return NULL;
		}

		$selected_category = $this->Category_model->get_category_with_questions((int) $question->category_id);
		$selected_question = NULL;

		if ($selected_category && !empty($selected_category->questions)) {
			foreach ($selected_category->questions as $question_item) {
				$trade_breakdown = $this->Category_model->get_question_trade_breakdown((int) $question_item->id);
				$question_item->trade_totals = $trade_breakdown;
				$question_item->user_counts = array(
					'yes_users' => isset($trade_breakdown['yes_users']) ? (int) $trade_breakdown['yes_users'] : 0,
					'no_users' => isset($trade_breakdown['no_users']) ? (int) $trade_breakdown['no_users'] : 0
				);

				$real_users = $this->Category_model->get_total_users_by_question($question_item->id);
				$admin_users = (int) (isset($question_item->admin_extra_users) ? $question_item->admin_extra_users : 0);
				$question_item->real_users = $real_users;
				$question_item->admin_users = $admin_users;
				$question_item->total_users = $real_users + $admin_users;

				if ((int) $question_item->id === $id) {
					$selected_question = $question_item;
				}
			}
		}

		if (!$selected_question) {
			return NULL;
		}

		return array(
			'selected_category' => $selected_category,
			'selected_question' => $selected_question
		);
	}

	private function set_validation_error_flashdata()
	{
		$error_message = trim(strip_tags(validation_errors(' ', ' ')));
		$this->session->set_flashdata('error', $error_message !== '' ? $error_message : 'Please check the form fields and try again.');
	}

	private function is_valid_price_pair($yes_price, $no_price)
	{
		return (float) $yes_price >= 0.5 && (float) $no_price >= 0.5;
	}

	private function normalize_question_text($value)
	{
		$value = trim((string) $value);
		return preg_replace('/\s+/', ' ', $value);
	}

	private function normalize_market_price($value)
	{
		return round(max(0, (float) $value), 2);
	}

	private function validate_question_window($start_time, $end_time)
	{
		if (empty($start_time) || empty($end_time)) {
			return TRUE;
		}

		return strtotime($start_time) < strtotime($end_time);
	}

	private function normalize_datetime_input($value)
	{
		$value = trim((string) $value);

		if ($value === '') {
			return NULL;
		}

		$formats = array(
			'Y-m-d\TH:i',
			'Y-m-d\TH:i:s',
			'Y-m-d H:i',
			'Y-m-d H:i:s',
			'd-m-Y H:i',
			'd/m/Y H:i'
		);

		foreach ($formats as $format) {
			$date = DateTime::createFromFormat($format, $value);

			if ($date instanceof DateTime) {
				return $date->format('Y-m-d H:i:s');
			}
		}

		$timestamp = strtotime($value);
		return $timestamp ? date('Y-m-d H:i:s', $timestamp) : NULL;
	}

	private function is_valid_question_status($status)
	{
		return in_array($status, array('draft', 'open', 'closed', 'resolved'), TRUE);
	}

	private function settle_question_market($question_id, $answer_key)
	{
		$unsettled_answers = $this->Category_model->get_unsettled_answers_by_question((int) $question_id);

		if (empty($unsettled_answers)) {
			return;
		}

		$question = $this->Category_model->get_question((int) $question_id);

		$settled_at = date('Y-m-d H:i:s');

		foreach ($unsettled_answers as $answer) {
			$is_winner = strtolower((string) $answer->answer) === strtolower((string) $answer_key);
			$market_total = (float) $question->yes_price + (float) $question->no_price;
			$current_payout = round($market_total * (int) $answer->quantity, 2);
			$payout_amount = $is_winner ? $current_payout : 0.00;

			$this->Category_model->mark_answer_settlement((int) $answer->id, $payout_amount, $settled_at, array(
				'settlement_type' => 'result',
				'sell_price' => NULL,
				'sell_multiplier' => NULL,
				'sell_profit' => NULL
			));

			if ($payout_amount > 0) {
				$this->User_model->adjust_wallet_balance((int) $answer->user_id, $payout_amount);
				$this->Wallet_model->add_transaction(array(
					'user_id' => (int) $answer->user_id,
					'source_type' => 'question_result',
					'source_id' => (int) $question_id,
					'type' => 'credit',
					'amount' => $payout_amount,
					'description' => 'Winning payout for resolved question #' . (int) $question_id
				));
				$this->User_model->add_notification(array(
					'user_id' => (int) $answer->user_id,
					'title' => 'Market won',
					'message' => 'Question **' . $question->question . '** resolved in your favor. Rs ' . number_format($payout_amount, 2) . ' was credited to your wallet.',
					'type' => 'result'
				));
			} else {
				$this->User_model->add_notification(array(
					'user_id' => (int) $answer->user_id,
					'title' => 'Market resolved',
					'message' => 'Question **' . $question->question . '** has been resolved. This trade did not earn a payout.',
					'type' => 'result'
				));
			}
		}
	}

	public function get_questions_by_category($category_id)
	{
		$questions = $this->db
			->where('category_id', $category_id)
			->get('category_questions')
			->result();

		echo json_encode($questions);
	}

	private function get_question_multiplier($question, $answer)
	{
		if (!$question) {
			return 1.25;
		}
		$yes_price = (float) $question->yes_price;
		$no_price = (float) $question->no_price;
		$selected_price = strtolower(trim((string) $answer)) === 'no' ? $no_price : $yes_price;

		if ($selected_price > 0) {
			return round(($yes_price + $no_price) / $selected_price, 4);
		}
		return 1.25;
	}
}
