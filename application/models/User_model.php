<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	protected $table = 'users';
	protected $answers_table = 'user_question_answers';
	protected $notifications_table = 'notifications';
	protected $wallet_transactions_table = 'wallet_transactions';
	protected $referral_settings_table = 'referral_settings';
	protected $referral_rewards_table = 'referral_rewards';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('General_model');
	}

	public function get_by_id($id)
	{
		return $this->General_model->getrow($this->table, array('id' => $id));
	}

	public function get_by_mobile($mobile)
	{
		return $this->General_model->getrow($this->table, array('mobile' => $mobile));
	}

	public function get_by_email($email)
	{
		return $this->General_model->getrow($this->table, array('email' => $email));
	}

	public function get_by_login_identity($identity)
	{
		$identity = trim((string) $identity);

		if ($identity === '') {
			return NULL;
		}

		if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
			return $this->get_by_email($identity);
		}

		return $this->get_by_mobile($identity);
	}

	public function get_admin_by_email($email)
	{
		return $this->General_model->getrow($this->table, array('email' => $email, 'role' => 1));
	}

	public function get_admin_by_mobile($mobile)
	{
		return $this->General_model->getrow($this->table, array('mobile' => $mobile, 'role' => 1));
	}

	public function get_by_reset_code($email, $code)
	{
		$this->db->where('email', $email);
		$this->db->where('reset_token', $code);
		$this->db->where('reset_token_expiry >=', date('Y-m-d H:i:s'));
		return $this->db->get($this->table)->row();
	}

	public function insert($data)
	{
		return $this->General_model->insert($this->table, $data);
	}

	public function update($id, $data)
	{
		return $this->General_model->update($this->table, $data, array('id' => $id));
	}

	public function delete($id)
	{
		return $this->General_model->delete($this->table, array('id' => $id));
	}

	public function mobile_exists($mobile, $exclude_id = NULL)
	{
		$this->db->where('mobile', $mobile);

		if ($exclude_id !== NULL) {
			$this->db->where('id !=', $exclude_id);
		}

		return $this->db->count_all_results($this->table) > 0;
	}

	public function email_exists($email, $exclude_id = NULL)
	{
		$this->db->where('email', $email);

		if ($exclude_id !== NULL) {
			$this->db->where('id !=', $exclude_id);
		}

		return $this->db->count_all_results($this->table) > 0;
	}

	public function get_all_users()
	{
		return $this->General_model->getall($this->table, array(), 'id', 'DESC');
	}

	public function get_regular_users()
	{
		return $this->General_model->getall($this->table, array('role' => 0), 'id', 'DESC');
	}

	public function get_by_referral_code($referral_code)
	{
		$referral_code = strtoupper(trim((string) $referral_code));

		if ($referral_code === '') {
			return NULL;
		}

		return $this->General_model->getrow($this->table, array('referral_code' => $referral_code));
	}

	public function generate_referral_code($name = '')
	{
		$prefix = strtoupper(preg_replace('/[^A-Z0-9]/', '', substr((string) $name, 0, 4)));

		if ($prefix === '') {
			$prefix = 'PROB';
		}

		do {
			$code = $prefix . random_int(1000, 9999);
		} while ($this->get_by_referral_code($code));

		return $code;
	}

	public function ensure_referral_code($user_id)
	{
		$user = $this->get_by_id($user_id);

		if (!$user) {
			return NULL;
		}

		$current_code = isset($user->referral_code) ? strtoupper(trim((string) $user->referral_code)) : '';

		if ($current_code !== '') {
			return $current_code;
		}

		$code = $this->generate_referral_code($user->name);
		$this->update($user_id, array('referral_code' => $code));
		return $code;
	}

	public function count_all_users()
	{
		return (int) $this->db->count_all($this->table);
	}

	public function count_users_by_role($role)
	{
		$this->db->where('role', (int) $role);
		return (int) $this->db->count_all_results($this->table);
	}

	public function get_leaderboard($limit = 10, $winners_only = FALSE)
	{
		$limit = max(1, (int) $limit);

		if (!$this->db->table_exists($this->table) || !$this->db->table_exists($this->answers_table)) {
			return array();
		}

		$this->db->select("
			u.id,
			u.name,
			u.email,
			u.mobile,
			u.profile_image,
			u.wallet_balance,
			COUNT(a.id) AS total_trades,
			SUM(CASE WHEN a.settled_at IS NOT NULL AND a.payout_amount > 0 THEN 1 ELSE 0 END) AS total_wins,
			SUM(CASE WHEN a.settled_at IS NOT NULL THEN a.payout_amount ELSE 0 END) AS total_payout
		", FALSE);
		$this->db->from($this->table . ' u');
		$this->db->join($this->answers_table . ' a', 'a.user_id = u.id', 'left');
		$this->db->where('u.role', 0);
		$this->db->group_by('u.id');
		if ($winners_only) {
			$this->db->having('SUM(CASE WHEN a.settled_at IS NOT NULL AND a.payout_amount > 0 THEN 1 ELSE 0 END) >', 0, FALSE);
		}
		$this->db->order_by('total_payout', 'DESC');
		$this->db->order_by('total_wins', 'DESC');
		$this->db->order_by('u.wallet_balance', 'DESC');
		$this->db->limit($limit);
		return $this->db->get()->result();
	}

	public function get_referral_summary($user_id)
	{
		$user_id = (int) $user_id;

		$this->db->where('referred_by_user_id', $user_id);
		$referral_count = (int) $this->db->count_all_results($this->table);

		if (!$this->db->table_exists($this->wallet_transactions_table)) {
			return array(
				'referral_count' => $referral_count,
				'bonus_amount' => 0.0
			);
		}

		$this->db->select_sum('amount');
		$this->db->where('user_id', $user_id);
		$this->db->where('source_type', 'referral_bonus');
		$row = $this->db->get($this->wallet_transactions_table)->row();
		$bonus_amount = $row && $row->amount !== NULL ? (float) $row->amount : 0.0;

		return array(
			'referral_count' => $referral_count,
			'bonus_amount' => $bonus_amount
		);
	}

	public function get_referral_settings()
	{
		$defaults = array(
			'new_user_bonus' => 25.00,
			'referrer_bonus' => 25.00
		);

		if (!$this->db->table_exists($this->referral_settings_table)) {
			return $defaults;
		}

		$row = $this->db->order_by('id', 'ASC')->limit(1)->get($this->referral_settings_table)->row();

		if (!$row) {
			return $defaults;
		}

		return array(
			'new_user_bonus' => isset($row->new_user_bonus) ? (float) $row->new_user_bonus : $defaults['new_user_bonus'],
			'referrer_bonus' => isset($row->referrer_bonus) ? (float) $row->referrer_bonus : $defaults['referrer_bonus']
		);
	}

	public function save_referral_settings($new_user_bonus, $referrer_bonus)
	{
		if (!$this->db->table_exists($this->referral_settings_table)) {
			return FALSE;
		}

		$data = array(
			'new_user_bonus' => round((float) $new_user_bonus, 2),
			'referrer_bonus' => round((float) $referrer_bonus, 2)
		);

		$row = $this->db->order_by('id', 'ASC')->limit(1)->get($this->referral_settings_table)->row();

		if ($row) {
			$this->db->where('id', (int) $row->id);
			return $this->db->update($this->referral_settings_table, $data);
		}

		return $this->db->insert($this->referral_settings_table, $data);
	}

	public function create_referral_reward($data)
	{
		if (!$this->db->table_exists($this->referral_rewards_table)) {
			return FALSE;
		}

		$this->db->insert($this->referral_rewards_table, $data);
		return $this->db->insert_id();
	}

	public function get_referral_rewards()
	{
		if (
			!$this->db->table_exists($this->referral_rewards_table) ||
			!$this->db->table_exists($this->table)
		) {
			return array();
		}

		$this->db->select('
			rr.*,
			referrer.name AS referrer_name,
			referrer.email AS referrer_email,
			referrer.mobile AS referrer_mobile,
			referred.name AS referred_name,
			referred.email AS referred_email,
			referred.mobile AS referred_mobile
		');
		$this->db->from($this->referral_rewards_table . ' rr');
		$this->db->join($this->table . ' referrer', 'referrer.id = rr.referrer_user_id', 'left');
		$this->db->join($this->table . ' referred', 'referred.id = rr.referred_user_id', 'left');
		$this->db->order_by('rr.id', 'DESC');
		return $this->db->get()->result();
	}

	public function add_notification($data)
	{
		if (!$this->db->table_exists($this->notifications_table)) {
			return FALSE;
		}

		if (!isset($data['is_read'])) {
			$data['is_read'] = 0;
		}

		$this->db->insert($this->notifications_table, $data);
		return $this->db->insert_id();
	}

	public function get_notifications_by_user($user_id, $limit = 10)
	{
		if (!$this->db->table_exists($this->notifications_table)) {
			return array();
		}

		$this->db->where('user_id', (int) $user_id);
		$this->db->order_by('id', 'DESC');
		if ($limit !== NULL) {
			$this->db->limit(max(1, (int) $limit));
		}
		return $this->db->get($this->notifications_table)->result();
	}

	public function count_unread_notifications($user_id)
	{
		if (!$this->db->table_exists($this->notifications_table)) {
			return 0;
		}

		$this->db->where('user_id', (int) $user_id);
		$this->db->where('is_read', 0);
		return (int) $this->db->count_all_results($this->notifications_table);
	}

	public function mark_all_notifications_read($user_id)
	{
		if (!$this->db->table_exists($this->notifications_table)) {
			return FALSE;
		}

		$this->db->where('user_id', (int) $user_id);
		return $this->db->update($this->notifications_table, array('is_read' => 1));
	}

	public function mark_notification_read($user_id, $notification_id)
	{
		if (!$this->db->table_exists($this->notifications_table)) {
			return FALSE;
		}

		$this->db->where('user_id', (int) $user_id);
		$this->db->where('id', (int) $notification_id);
		$this->db->where('is_read', 0);
		return $this->db->update($this->notifications_table, array('is_read' => 1));
	}

	public function adjust_wallet_balance($user_id, $amount)
	{
		$user = $this->get_by_id($user_id);

		if (!$user) {
			return FALSE;
		}

		$new_balance = max(0, ((float) $user->wallet_balance) + (float) $amount);
		return $this->update($user_id, array('wallet_balance' => $new_balance));
	}
}
