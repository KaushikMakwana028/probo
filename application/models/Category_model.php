<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends CI_Model
{

	protected $category_table = 'categories';
	protected $question_table = 'category_questions';
	protected $answer_table = 'user_question_answers';
	protected $price_history_table = 'question_price_history';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_all_categories()
	{
		if (!$this->db->table_exists($this->category_table)) {
			return array();
		}

		if (!$this->db->table_exists($this->question_table)) {
			$this->db->order_by('name', 'ASC');
			return $this->db->get($this->category_table)->result();
		}

		$this->db->select('c.*, COUNT(q.id) AS question_count');
		$this->db->from($this->category_table . ' c');
		$this->db->join($this->question_table . ' q', 'q.category_id = c.id', 'left');
		$this->db->group_by('c.id');
		$this->db->order_by('c.name', 'ASC');
		return $this->db->get()->result();
	}

	public function get_all_categories_with_visible_question_counts_for_user($user_id)
	{
		$categories = $this->get_all_categories();

		if (empty($categories)) {
			return array();
		}

		foreach ($categories as $category) {
			$visible_questions = $this->get_visible_questions_by_category_for_user((int) $category->id, (int) $user_id);
			$category->question_count = count($visible_questions);
		}

		return $categories;
	}

	public function get_all_categories_with_questions()
	{
		$categories = $this->get_all_categories();

		if (empty($categories)) {
			return array();
		}

		$questions = $this->get_all_questions();
		$questions_by_category = array();

		foreach ($questions as $question) {
			if (!isset($questions_by_category[$question->category_id])) {
				$questions_by_category[$question->category_id] = array();
			}

			$questions_by_category[$question->category_id][] = $question;
		}

		foreach ($categories as $category) {
			$category->questions = isset($questions_by_category[$category->id]) ? $questions_by_category[$category->id] : array();
		}

		return $categories;
	}

	public function get_category_with_questions($category_id)
	{
		$category = $this->get_category($category_id);

		if (!$category) {
			return NULL;
		}

		$category->questions = $this->get_questions_by_category($category_id);
		return $category;
	}

	public function get_category($id)
	{
		if (!$this->db->table_exists($this->category_table)) {
			return NULL;
		}

		return $this->db->get_where($this->category_table, array('id' => (int) $id))->row();
	}

	public function category_name_exists($name, $exclude_id = NULL)
	{
		if (!$this->db->table_exists($this->category_table)) {
			return FALSE;
		}

		$this->db->where('LOWER(name)', strtolower(trim($name)));

		if ($exclude_id !== NULL) {
			$this->db->where('id !=', (int) $exclude_id);
		}

		return $this->db->count_all_results($this->category_table) > 0;
	}

	public function create_category($data)
	{
		if (!$this->db->table_exists($this->category_table)) {
			return FALSE;
		}

		$this->db->insert($this->category_table, $data);
		return $this->db->insert_id();
	}

	public function update_category($id, $data)
	{
		if (!$this->db->table_exists($this->category_table)) {
			return FALSE;
		}

		$this->db->where('id', (int) $id);
		return $this->db->update($this->category_table, $data);
	}

	public function delete_category($id)
	{
		if (!$this->db->table_exists($this->category_table)) {
			return FALSE;
		}

		if ($this->db->table_exists($this->question_table)) {
			$this->db->where('category_id', (int) $id);
			$this->db->delete($this->question_table);
		}

		$this->db->where('id', (int) $id);
		return $this->db->delete($this->category_table);
	}

	public function get_all_questions()
	{
		$this->ensure_question_trade_edit_column();

		if (!$this->db->table_exists($this->question_table) || !$this->db->table_exists($this->category_table)) {
			return array();
		}

		$this->db->select('q.*, c.name AS category_name');
		$this->db->from($this->question_table . ' q');
		$this->db->join($this->category_table . ' c', 'c.id = q.category_id');
		$this->db->order_by('q.id', 'DESC');
		$questions = $this->db->get()->result();

		foreach ($questions as $question) {
			$this->hydrate_question_market_fields($question);
		}

		return $questions;
	}

	public function get_questions_by_category($category_id)
	{
		$this->ensure_question_trade_edit_column();

		if (!$this->db->table_exists($this->question_table) || !$this->db->table_exists($this->category_table)) {
			return array();
		}

		$this->db->select('q.*, c.name AS category_name');
		$this->db->from($this->question_table . ' q');
		$this->db->join($this->category_table . ' c', 'c.id = q.category_id');
		$this->db->where('q.category_id', (int) $category_id);
		$this->db->order_by('q.id', 'ASC');
		$questions = $this->db->get()->result();

		foreach ($questions as $question) {
			$this->hydrate_question_market_fields($question);
		}

		return $questions;
	}

	public function get_visible_questions_by_category_for_user($category_id, $user_id)
	{
		$questions = $this->get_questions_by_category($category_id);

		if (empty($questions)) {
			return array();
		}

		$user_answers = $this->get_user_answers_by_category($user_id, $category_id);
		$visible_questions = array();
		$now_ts = time();

		foreach ($questions as $question) {
			$question_id = (int) $question->id;
			$answer_state = isset($user_answers[$question_id]) ? $user_answers[$question_id] : NULL;
			$end_ts = $this->to_timestamp(isset($question->end_time) ? $question->end_time : NULL);

			if ($end_ts === FALSE) {
				$visible_questions[] = $question;
				continue;
			}

			if ($now_ts <= $end_ts) {
				$visible_questions[] = $question;
				continue;
			}

			if ($answer_state && $now_ts <= strtotime('+24 hours', $end_ts)) {
				$visible_questions[] = $question;
			}
		}

		return $visible_questions;
	}

	public function get_question($id)
	{
		$this->ensure_question_trade_edit_column();

		if (!$this->db->table_exists($this->question_table) || !$this->db->table_exists($this->category_table)) {
			return NULL;
		}

		$this->db->select('q.*, c.name AS category_name');
		$this->db->from($this->question_table . ' q');
		$this->db->join($this->category_table . ' c', 'c.id = q.category_id');
		$this->db->where('q.id', (int) $id);
		$question = $this->db->get()->row();

		if ($question) {
			$this->hydrate_question_market_fields($question);
		}

		return $question;
	}

	public function create_question($data)
	{
		$this->ensure_question_trade_edit_column();

		if (!$this->db->table_exists($this->question_table)) {
			return FALSE;
		}

		$this->db->insert($this->question_table, $data);
		return $this->db->insert_id();
	}

	public function create_questions_batch($data)
	{
		$this->ensure_question_trade_edit_column();

		if (!$this->db->table_exists($this->question_table)) {
			return FALSE;
		}

		if (empty($data)) {
			return FALSE;
		}

		return $this->db->insert_batch($this->question_table, $data);
	}

	public function update_question($id, $data)
	{
		$this->ensure_question_trade_edit_column();

		if (!$this->db->table_exists($this->question_table)) {
			return FALSE;
		}

		$this->db->where('id', (int) $id);
		return $this->db->update($this->question_table, $data);
	}

	public function save_answer_keys($category_id, $answer_keys)
	{
		if (!$this->db->table_exists($this->question_table)) {
			return FALSE;
		}

		$questions = $this->get_questions_by_category($category_id);

		if (empty($questions)) {
			return FALSE;
		}
		$question_ids = array();
		$updated_any = FALSE;

		foreach ($questions as $question) {
			$question_ids[] = (int) $question->id;
		}

		foreach ((array) $answer_keys as $question_id => $answer_key) {
			$question_id = (int) $question_id;
			$answer_key = strtolower(trim((string) $answer_key));

			if (!in_array($question_id, $question_ids, TRUE)) {
				continue;
			}

			if (!in_array($answer_key, array('yes', 'no'), TRUE)) {
				continue;
			}

			$this->db->where('id', $question_id);
			$this->db->update($this->question_table, array(
				'answer_key' => $answer_key
			));
			$updated_any = TRUE;
		}

		return $updated_any;
	}

	public function delete_question($id)
	{
		if (!$this->db->table_exists($this->question_table)) {
			return FALSE;
		}

		$this->db->where('id', (int) $id);
		return $this->db->delete($this->question_table);
	}

	public function count_all_categories()
	{
		if (!$this->db->table_exists($this->category_table)) {
			return 0;
		}

		return (int) $this->db->count_all($this->category_table);
	}

	public function count_all_questions()
	{
		if (!$this->db->table_exists($this->question_table)) {
			return 0;
		}

		return (int) $this->db->count_all($this->question_table);
	}

	public function get_user_answers_by_category($user_id, $category_id)
	{
		$this->ensure_answer_trade_exit_columns();

		if (
			!$this->db->table_exists($this->answer_table) ||
			!$this->db->table_exists($this->question_table)
		) {
			return array();
		}

		$this->db->select('a.question_id, a.answer, a.price, a.quantity, a.payout_amount, a.stake_amount, a.entry_payout_amount, a.created_at, a.settled_at, a.settlement_type, a.sell_price, a.sell_multiplier, a.sell_profit, a.peak_sell_price, a.peak_sell_multiplier');
		$this->db->from($this->answer_table . ' a');
		$this->db->join($this->question_table . ' q', 'q.id = a.question_id');
		$this->db->where('a.user_id', (int) $user_id);
		$this->db->where('q.category_id', (int) $category_id);
		$rows = $this->db->get()->result();
		$answers = array();

		foreach ($rows as $row) {
			$answers[(int) $row->question_id] = (object) array(
				'answer' => $row->answer,
				'price' => isset($row->price) ? (float) $row->price : 0,
				'quantity' => isset($row->quantity) ? (int) $row->quantity : 1,
				'payout_amount' => isset($row->payout_amount) ? (float) $row->payout_amount : 0,
				'entry_payout_amount' => isset($row->entry_payout_amount) ? (float) $row->entry_payout_amount : (isset($row->payout_amount) ? (float) $row->payout_amount : 0),
				'stake_amount' => isset($row->stake_amount) ? (float) $row->stake_amount : 0,
				'created_at' => isset($row->created_at) ? $row->created_at : NULL,
				'settled_at' => isset($row->settled_at) ? $row->settled_at : NULL,
				'settlement_type' => isset($row->settlement_type) ? (string) $row->settlement_type : '',
				'sell_price' => isset($row->sell_price) ? (float) $row->sell_price : 0,
				'sell_multiplier' => isset($row->sell_multiplier) ? (float) $row->sell_multiplier : 0,
				'sell_profit' => isset($row->sell_profit) ? (float) $row->sell_profit : 0,
				'peak_sell_price' => isset($row->peak_sell_price) ? (float) $row->peak_sell_price : 0,
				'peak_sell_multiplier' => isset($row->peak_sell_multiplier) ? (float) $row->peak_sell_multiplier : 0
			);
		}

		return $answers;
	}

	public function get_user_answer($user_id, $question_id)
	{
		$this->ensure_answer_trade_exit_columns();

		if (!$this->db->table_exists($this->answer_table)) {
			return NULL;
		}

		$this->db->where('user_id', (int) $user_id);
		$this->db->where('question_id', (int) $question_id);
		return $this->db->get($this->answer_table)->row();
	}

	public function save_user_answer($user_id, $question_id, $answer, $price = 0, $quantity = 1, $payout_amount = 0, $stake_amount = 0, $settled_at = NULL, $extra_data = array())
	{
		$this->ensure_answer_trade_exit_columns();

		if (!$this->db->table_exists($this->answer_table)) {
			return FALSE;
		}

		$this->db->where('user_id', (int) $user_id);
		$this->db->where('question_id', (int) $question_id);
		$existing = $this->db->get($this->answer_table)->row();

		$data = array(
			'user_id' => (int) $user_id,
			'question_id' => (int) $question_id,
			'answer' => $answer,
			'price' => (float) $price,
			'quantity' => max(1, (int) $quantity),
			'payout_amount' => max(0, (float) $payout_amount),
			'entry_payout_amount' => max(0, (float) (($extra_data['entry_payout_amount'] ?? $payout_amount))),
			'stake_amount' => max(0, (float) $stake_amount),
			'settled_at' => $settled_at,
			'settlement_type' => isset($extra_data['settlement_type']) ? $extra_data['settlement_type'] : NULL,
			'sell_price' => isset($extra_data['sell_price']) ? (float) $extra_data['sell_price'] : NULL,
			'sell_multiplier' => isset($extra_data['sell_multiplier']) ? (float) $extra_data['sell_multiplier'] : NULL,
			'sell_profit' => isset($extra_data['sell_profit']) ? (float) $extra_data['sell_profit'] : NULL,
			'peak_sell_price' => isset($extra_data['peak_sell_price']) ? (float) $extra_data['peak_sell_price'] : NULL,
			'peak_sell_multiplier' => isset($extra_data['peak_sell_multiplier']) ? (float) $extra_data['peak_sell_multiplier'] : NULL
		);

		if ($existing) {
			$this->db->where('id', (int) $existing->id);
			return $this->db->update($this->answer_table, $data);
		}

		return $this->db->insert($this->answer_table, $data);
	}

	public function count_user_attempted_answers($user_id)
	{
		if (!$this->db->table_exists($this->answer_table)) {
			return 0;
		}

		$this->db->where('user_id', (int) $user_id);
		return (int) $this->db->count_all_results($this->answer_table);
	}

	public function get_question_trade_totals($question_id)
	{
		if (!$this->db->table_exists($this->answer_table)) {
			return array('yes_quantity' => 0, 'no_quantity' => 0);
		}

		$this->db->select('answer, SUM(quantity) AS total_quantity');
		$this->db->from($this->answer_table);
		$this->db->where('question_id', (int) $question_id);
		$this->db->group_by('answer');
		$rows = $this->db->get()->result();
		$totals = array('yes_quantity' => 0, 'no_quantity' => 0);

		foreach ($rows as $row) {
			$key = strtolower((string) $row->answer) === 'no' ? 'no_quantity' : 'yes_quantity';
			$totals[$key] = (int) $row->total_quantity;
		}

		return $totals;
	}

	public function get_question_trade_breakdown($question_id)
	{
		$question_id = (int) $question_id;
		$totals = $this->get_question_trade_totals($question_id);
		$display_yes_quantity = (int) $totals['yes_quantity'];
		$display_no_quantity = (int) $totals['no_quantity'];
		$admin_yes_quantity = 0;
		$admin_no_quantity = 0;

		if ($this->db->table_exists($this->question_table)) {
			$question = $this->db
				->select('admin_yes_quantity, admin_no_quantity')
				->where('id', $question_id)
				->get($this->question_table)
				->row();

			if ($question) {
				if (isset($question->admin_yes_quantity) && (int) $question->admin_yes_quantity >= 0) {
					$admin_yes_quantity = (int) $question->admin_yes_quantity;
				}
				if (isset($question->admin_no_quantity) && (int) $question->admin_no_quantity >= 0) {
					$admin_no_quantity = (int) $question->admin_no_quantity;
				}
			}
		}

		$display_yes_quantity += $admin_yes_quantity;
		$display_no_quantity += $admin_no_quantity;

		if (!$this->db->table_exists($this->answer_table)) {
			return array(
				'yes_quantity' => $display_yes_quantity,
				'no_quantity' => $display_no_quantity,
				'actual_yes_quantity' => (int) $totals['yes_quantity'],
				'actual_no_quantity' => (int) $totals['no_quantity'],
				'admin_yes_quantity' => $admin_yes_quantity,
				'admin_no_quantity' => $admin_no_quantity,
				'yes_users' => 0,
				'no_users' => 0,
				'total_users' => 0
			);
		}

		$this->db->select('answer, COUNT(*) AS total_users');
		$this->db->from($this->answer_table);
		$this->db->where('question_id', $question_id);
		$this->db->group_by('answer');
		$rows = $this->db->get()->result();
		$users = array('yes_users' => 0, 'no_users' => 0);

		foreach ($rows as $row) {
			$key = strtolower((string) $row->answer) === 'no' ? 'no_users' : 'yes_users';
			$users[$key] = (int) $row->total_users;
		}

		return array(
			'yes_quantity' => $display_yes_quantity,
			'no_quantity' => $display_no_quantity,
			'actual_yes_quantity' => (int) $totals['yes_quantity'],
			'actual_no_quantity' => (int) $totals['no_quantity'],
			'admin_yes_quantity' => $admin_yes_quantity,
			'admin_no_quantity' => $admin_no_quantity,
			'yes_users' => (int) $users['yes_users'],
			'no_users' => (int) $users['no_users'],
			'total_users' => (int) $users['yes_users'] + (int) $users['no_users']
		);
	}

	public function rebalance_question_prices($question_id)
	{
		$question = $this->get_question((int) $question_id);

		if (!$question) {
			return FALSE;
		}

		$base_yes_price = isset($question->base_yes_price) && (float) $question->base_yes_price > 0
			? (float) $question->base_yes_price
			: (float) $question->yes_price;
		$base_no_price = isset($question->base_no_price) && (float) $question->base_no_price > 0
			? (float) $question->base_no_price
			: (float) $question->no_price;

		$updated = $this->update_question((int) $question_id, array(
			'yes_price' => round($base_yes_price, 2),
			'no_price' => round($base_no_price, 2)
		));

		if ($updated) {
			$this->record_price_history((int) $question_id, round($base_yes_price, 2), round($base_no_price, 2));
		}

		return $updated;
	}

	public function record_price_history($question_id, $yes_price, $no_price)
	{
		if (!$this->db->table_exists($this->price_history_table)) {
			return FALSE;
		}

		return $this->db->insert($this->price_history_table, array(
			'question_id' => (int) $question_id,
			'yes_price' => (float) $yes_price,
			'no_price' => (float) $no_price
		));
	}

	public function get_question_price_history($question_id, $limit = 24)
	{
		if (!$this->db->table_exists($this->price_history_table)) {
			$question = $this->get_question($question_id);

			if (!$question) {
				return array();
			}

			return array((object) array(
				'yes_price' => (float) $question->yes_price,
				'no_price' => (float) $question->no_price,
				'created_at' => date('Y-m-d H:i:s')
			));
		}

		$this->db->where('question_id', (int) $question_id);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(max(1, (int) $limit));
		$rows = $this->db->get($this->price_history_table)->result();

		if (empty($rows)) {
			$question = $this->get_question($question_id);

			if (!$question) {
				return array();
			}

			return array((object) array(
				'yes_price' => (float) $question->yes_price,
				'no_price' => (float) $question->no_price,
				'created_at' => date('Y-m-d H:i:s')
			));
		}

		return array_reverse($rows);
	}

	public function get_live_questions_snapshot_by_category($category_id)
	{
		$questions = $this->get_questions_by_category($category_id);
		$snapshot = array();

		foreach ($questions as $question) {
			$breakdown = $this->get_question_trade_breakdown((int) $question->id);
			$history = $this->get_question_price_history((int) $question->id, 12);
			$history_points = array();

			foreach ($history as $point) {
				$history_points[] = array(
					'yes_price' => isset($point->yes_price) ? (float) $point->yes_price : 0,
					'no_price' => isset($point->no_price) ? (float) $point->no_price : 0,
					'created_at' => isset($point->created_at) ? $point->created_at : ''
				);
			}

			$snapshot[] = array(
				'id' => (int) $question->id,
				'status' => (string) $question->status,
				'yes_price' => (float) $question->yes_price,
				'no_price' => (float) $question->no_price,
				'market_total' => round((float) $question->yes_price + (float) $question->no_price, 2),
				'trade_totals' => $breakdown,
				'price_history' => $history_points,
				'real_users' => (int) $breakdown['total_users']
			);
		}

		return $snapshot;
	}

	public function get_unsettled_answers_by_question($question_id)
	{
		$this->ensure_answer_trade_exit_columns();

		if (!$this->db->table_exists($this->answer_table)) {
			return array();
		}

		$this->db->where('question_id', (int) $question_id);
		$this->db->where('settled_at IS NULL', NULL, FALSE);
		return $this->db->get($this->answer_table)->result();
	}

	public function mark_answer_settlement($answer_id, $payout_amount, $settled_at, $extra_data = array())
	{
		$this->ensure_answer_trade_exit_columns();

		$data = array(
			'payout_amount' => max(0, (float) $payout_amount),
			'settled_at' => $settled_at
		);

		foreach (array('settlement_type', 'sell_price', 'sell_multiplier', 'sell_profit') as $key) {
			if (array_key_exists($key, $extra_data)) {
				$data[$key] = $extra_data[$key];
			}
		}

		$this->db->where('id', (int) $answer_id);
		$this->db->where('settled_at IS NULL', NULL, FALSE);
		return $this->db->update($this->answer_table, $data);
	}

	private function to_timestamp($value)
	{
		$value = trim((string) $value);

		if ($value === '' || $value === '0000-00-00 00:00:00') {
			return FALSE;
		}

		$timestamp = strtotime($value);
		return $timestamp ?: FALSE;
	}

	public function get_total_users_by_question($question_id)
	{
		if (!$this->db->table_exists('user_question_answers')) {
			return 0;
		}

		return (int) $this->db
			->where('question_id', (int) $question_id)
			->count_all_results('user_question_answers');
	}

	public function get_question_user_counts($question_id)
	{
		if (!$this->db->table_exists('user_question_answers')) {
			return 0;
		}

		return (int) $this->db
			->where('question_id', (int)$question_id)
			->count_all_results('user_question_answers');
	}

	public function get_question_user_trade_report($question_id, $filters = array())
	{
		$this->ensure_answer_trade_exit_columns();

		if (
			!$this->db->table_exists($this->answer_table) ||
			!$this->db->table_exists($this->question_table) ||
			!$this->db->table_exists('users')
		) {
			return array('rows' => array(), 'total' => 0);
		}

		$question_id = (int) $question_id;
		$search = strtolower(trim((string) ($filters['search'] ?? '')));
		$answer_filter = strtolower(trim((string) ($filters['answer'] ?? 'all')));
		$result_filter = strtolower(trim((string) ($filters['result'] ?? 'all')));
		$from = trim((string) ($filters['from'] ?? ''));
		$to   = trim((string) ($filters['to'] ?? ''));
		$page = max(1, (int) ($filters['page'] ?? 1));
		$per_page = max(1, min(100, (int) ($filters['per_page'] ?? 10)));
		$offset = ($page - 1) * $per_page;

		$base_sql = "
        FROM {$this->answer_table} a
        INNER JOIN {$this->question_table} q ON q.id = a.question_id
        INNER JOIN users u ON u.id = a.user_id
        WHERE a.question_id = ?
    ";
		$params = array($question_id);

		// 🔍 SEARCH
		if ($search !== '') {
			$base_sql .= " AND (LOWER(u.name) LIKE ? OR LOWER(u.mobile) LIKE ? OR LOWER(u.email) LIKE ?)";
			$term = '%' . $search . '%';
			$params[] = $term;
			$params[] = $term;
			$params[] = $term;
		}

		// 🎯 ANSWER FILTER
		if (in_array($answer_filter, array('yes', 'no'), TRUE)) {
			$base_sql .= " AND LOWER(a.answer) = ?";
			$params[] = $answer_filter;
		}

		// 🧠 RESULT FILTER
		if ($result_filter === 'win') {
			$base_sql .= " AND a.settled_at IS NOT NULL AND LOWER(a.answer) = LOWER(COALESCE(q.answer_key, ''))";
		} elseif ($result_filter === 'lose') {
			$base_sql .= " AND a.settled_at IS NOT NULL AND LOWER(a.answer) <> LOWER(COALESCE(q.answer_key, '')) AND COALESCE(q.answer_key, '') <> ''";
		} elseif ($result_filter === 'pending') {
			$base_sql .= " AND a.settled_at IS NULL";
		}

		// 🔥 TIME FILTER (FIXED → created_at)
		if ($from !== '') {
			$base_sql .= " AND a.created_at >= ?";
			$params[] = $from;
		}

		if ($to !== '') {
			$base_sql .= " AND a.created_at <= ?";
			$params[] = $to;
		}

		// 📊 COUNT
		$count_sql = "SELECT COUNT(*) AS total " . $base_sql;
		$count_row = $this->db->query($count_sql, $params)->row();
		$total = (int) ($count_row->total ?? 0);

		// 📋 LIST
		$list_sql = "
        SELECT
            a.id,
            a.user_id,
            a.answer,
            a.price,
            a.quantity,
            a.stake_amount,
            a.payout_amount,
            a.created_at,
            u.name,
            u.mobile,
            u.email,
            q.answer_key,
            CASE
                WHEN a.settled_at IS NOT NULL AND LOWER(a.answer) = LOWER(COALESCE(q.answer_key, '')) THEN 1
                WHEN a.settled_at IS NOT NULL AND LOWER(a.answer) <> LOWER(COALESCE(q.answer_key, '')) AND COALESCE(q.answer_key, '') <> '' THEN 2
                ELSE 3
            END AS result_priority
        " . $base_sql . "
        ORDER BY result_priority ASC, a.created_at ASC
        LIMIT ? OFFSET ?
    ";

		$list_params = $params;
		$list_params[] = $per_page;
		$list_params[] = $offset;

		$rows = $this->db->query($list_sql, $list_params)->result();

		// 🎯 RESULT STATUS
		foreach ($rows as $row) {
			$answer = strtolower($row->answer ?? '');
			$key = strtolower($row->answer_key ?? '');
			$is_settled = !empty($row->answer_key);

			$row->result_status = 'pending';

			if ($is_settled && $key !== '') {
				$row->result_status = ($answer === $key) ? 'win' : 'lose';
			}

			$row->win_amount = ($row->result_status === 'win') ? (float) $row->payout_amount : 0.0;
		}

		return array(
			'rows' => $rows,
			'total' => $total,
			'page' => $page,
			'per_page' => $per_page
		);
	}

	private function ensure_answer_trade_exit_columns()
	{
		if (!$this->db->table_exists($this->answer_table)) {
			return;
		}

		$column_queries = array(
			'entry_payout_amount' => "ALTER TABLE `{$this->answer_table}` ADD COLUMN `entry_payout_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00 AFTER `payout_amount`",
			'settlement_type' => "ALTER TABLE `{$this->answer_table}` ADD COLUMN `settlement_type` VARCHAR(20) NULL AFTER `settled_at`",
			'sell_price' => "ALTER TABLE `{$this->answer_table}` ADD COLUMN `sell_price` DECIMAL(10,2) NULL AFTER `settlement_type`",
			'sell_multiplier' => "ALTER TABLE `{$this->answer_table}` ADD COLUMN `sell_multiplier` DECIMAL(10,2) NULL AFTER `sell_price`",
			'sell_profit' => "ALTER TABLE `{$this->answer_table}` ADD COLUMN `sell_profit` DECIMAL(12,2) NULL AFTER `sell_multiplier`",
			'peak_sell_price' => "ALTER TABLE `{$this->answer_table}` ADD COLUMN `peak_sell_price` DECIMAL(10,2) NULL AFTER `sell_profit`",
			'peak_sell_multiplier' => "ALTER TABLE `{$this->answer_table}` ADD COLUMN `peak_sell_multiplier` DECIMAL(10,2) NULL AFTER `peak_sell_price`"
		);

		foreach ($column_queries as $column => $sql) {
			if (!$this->db->field_exists($column, $this->answer_table)) {
				$this->db->query($sql);
			}
		}
	}

	private function ensure_question_trade_edit_column()
	{
		if (!$this->db->table_exists($this->question_table)) {
			return;
		}

		if (!$this->db->field_exists('base_yes_price', $this->question_table)) {
			$this->db->query("ALTER TABLE `{$this->question_table}` ADD COLUMN `base_yes_price` DECIMAL(10,2) NOT NULL DEFAULT 10.00 AFTER `yes_price`");
			$this->db->query("UPDATE `{$this->question_table}` SET `base_yes_price` = `yes_price` WHERE `base_yes_price` = 10.00 AND `yes_price` > 0");
		}

		if (!$this->db->field_exists('base_no_price', $this->question_table)) {
			$this->db->query("ALTER TABLE `{$this->question_table}` ADD COLUMN `base_no_price` DECIMAL(10,2) NOT NULL DEFAULT 10.00 AFTER `no_price`");
			$this->db->query("UPDATE `{$this->question_table}` SET `base_no_price` = `no_price` WHERE `base_no_price` = 10.00 AND `no_price` > 0");
		}

		if (!$this->db->field_exists('last_trade_edit_at', $this->question_table)) {
			$this->db->query("ALTER TABLE `{$this->question_table}` ADD COLUMN `last_trade_edit_at` DATETIME NULL AFTER `result_declared_at`");
		}
	}

	private function hydrate_question_market_fields(&$question)
	{
		if (!$question || !isset($question->id)) {
			return;
		}

		$totals = $this->get_question_trade_totals((int) $question->id);
		$actual_yes_quantity = (int) $totals['yes_quantity'];
		$actual_no_quantity = (int) $totals['no_quantity'];
		list($base_yes_price, $base_no_price) = $this->resolve_base_prices_for_question($question, $actual_yes_quantity, $actual_no_quantity);
		$market_total = max(1.0, round($base_yes_price + $base_no_price, 2));
		$live_yes_price = round($base_yes_price, 2);
		$live_no_price = round($base_no_price, 2);

		$question->base_yes_price = $base_yes_price;
		$question->base_no_price = $base_no_price;
		$question->live_yes_price = $live_yes_price;
		$question->live_no_price = $live_no_price;
		$question->market_total = $market_total;
		$question->yes_price = $live_yes_price;
		$question->no_price = $live_no_price;
	}

	private function resolve_base_prices_for_question($question, $actual_yes_quantity = 0, $actual_no_quantity = 0)
	{
		$base_yes_price = isset($question->base_yes_price) && (float) $question->base_yes_price > 0
			? (float) $question->base_yes_price
			: 0.0;
		$base_no_price = isset($question->base_no_price) && (float) $question->base_no_price > 0
			? (float) $question->base_no_price
			: 0.0;

		$can_use_history_recovery = $this->db->table_exists($this->price_history_table);
		$should_try_history = $can_use_history_recovery && (
			$base_yes_price <= 0 ||
			$base_no_price <= 0 ||
			(
				($actual_yes_quantity + $actual_no_quantity) > 0 &&
				empty($question->last_trade_edit_at) &&
				abs($base_yes_price - (float) ($question->yes_price ?? 0)) < 0.0001 &&
				abs($base_no_price - (float) ($question->no_price ?? 0)) < 0.0001
			)
		);

		if ($should_try_history) {
			$history_row = $this->db
				->select('yes_price, no_price')
				->where('question_id', (int) $question->id)
				->order_by('id', 'ASC')
				->limit(1)
				->get($this->price_history_table)
				->row();

			if ($history_row && (float) $history_row->yes_price > 0 && (float) $history_row->no_price > 0) {
				$base_yes_price = (float) $history_row->yes_price;
				$base_no_price = (float) $history_row->no_price;

				if ($this->db->field_exists('base_yes_price', $this->question_table) && $this->db->field_exists('base_no_price', $this->question_table)) {
					$this->db->where('id', (int) $question->id);
					$this->db->update($this->question_table, array(
						'base_yes_price' => round($base_yes_price, 2),
						'base_no_price' => round($base_no_price, 2)
					));
				}
			}
		}

		// Legacy recovery:
		// some old records copied the then-live price into base price fields.
		// If the base still matches a skewed stored live value on an old traded question,
		// reset the base to an even split so edit shows the admin/default market again.
		if (
			($actual_yes_quantity + $actual_no_quantity) > 0 &&
			empty($question->last_trade_edit_at) &&
			$base_yes_price > 0 &&
			$base_no_price > 0 &&
			abs($base_yes_price - (float) ($question->yes_price ?? 0)) < 0.0001 &&
			abs($base_no_price - (float) ($question->no_price ?? 0)) < 0.0001 &&
			abs($base_yes_price - $base_no_price) >= 5
		) {
			$market_total = $this->get_prediction_market_total();
			$base_yes_price = round($market_total / 2, 2);
			$base_no_price = round($market_total - $base_yes_price, 2);

			if ($this->db->field_exists('base_yes_price', $this->question_table) && $this->db->field_exists('base_no_price', $this->question_table)) {
				$this->db->where('id', (int) $question->id);
				$this->db->update($this->question_table, array(
					'base_yes_price' => $base_yes_price,
					'base_no_price' => $base_no_price
				));
			}
		}

		if ($base_yes_price <= 0 || $base_no_price <= 0) {
			$base_yes_price = max(0.5, (float) ($question->yes_price ?? 10));
			$base_no_price = max(0.5, (float) ($question->no_price ?? 10));
		}

		return array(round($base_yes_price, 2), round($base_no_price, 2));
	}

	private function get_prediction_market_total()
	{
		return 20.0;
	}
}
