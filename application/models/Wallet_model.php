<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet_model extends CI_Model {

	protected $transactions_table = 'wallet_transactions';
	protected $withdrawals_table = 'withdrawal_requests';

	public function __construct()
	{
		parent::__construct();
	}

	public function add_transaction($data)
	{
		$this->db->insert($this->transactions_table, $data);
		return $this->db->insert_id();
	}

	public function get_transactions_by_user($user_id, $source_type = '', $limit = NULL)
	{
		$this->db->where('user_id', (int) $user_id);

		$source_type = trim((string) $source_type);

		if ($source_type !== '' && $source_type !== 'all') {
			$source_map = array(
				'winnings' => 'question_result',
				'withdrawals' => 'withdrawal',
				'deposits' => 'deposit',
				'trades' => 'trade_entry'
			);

			if (isset($source_map[$source_type])) {
				$this->db->where('source_type', $source_map[$source_type]);
			}
		}

		$this->db->order_by('id', 'DESC');

		if ($limit !== NULL) {
			$this->db->limit(max(1, (int) $limit));
		}

		return $this->db->get($this->transactions_table)->result();
	}

	public function get_total_credited_by_user($user_id)
	{
		$this->db->select_sum('amount');
		$this->db->where('user_id', (int) $user_id);
		$this->db->where('type', 'credit');
		$row = $this->db->get($this->transactions_table)->row();
		return $row && $row->amount !== NULL ? (float) $row->amount : 0.0;
	}

	public function get_total_debited_by_user($user_id)
	{
		$this->db->select_sum('amount');
		$this->db->where('user_id', (int) $user_id);
		$this->db->where('type', 'debit');
		$row = $this->db->get($this->transactions_table)->row();
		return $row && $row->amount !== NULL ? (float) $row->amount : 0.0;
	}

	public function get_total_withdrawn_by_user($user_id)
	{
		if (!$this->db->table_exists($this->withdrawals_table)) {
			return 0.0;
		}

		$this->db->select_sum('amount');
		$this->db->where('user_id', (int) $user_id);
		$this->db->where('status', 'approved');
		$row = $this->db->get($this->withdrawals_table)->row();
		return $row && $row->amount !== NULL ? (float) $row->amount : 0.0;
	}

	public function create_withdrawal_request($data)
	{
		$this->db->insert($this->withdrawals_table, $data);
		return $this->db->insert_id();
	}

	public function get_withdrawals_by_user($user_id)
	{
		$this->db->where('user_id', (int) $user_id);
		$this->db->order_by('id', 'DESC');
		return $this->db->get($this->withdrawals_table)->result();
	}

	public function get_all_withdrawals()
	{
		$this->db->select('w.*, u.name AS user_name, u.email AS user_email, u.mobile AS user_mobile');
		$this->db->from($this->withdrawals_table.' w');
		$this->db->join('users u', 'u.id = w.user_id', 'left');
		$this->db->order_by("FIELD(w.status, 'pending', 'approved', 'rejected')", '', FALSE);
		$this->db->order_by('w.id', 'DESC');
		return $this->db->get()->result();
	}

	public function get_withdrawal($id)
	{
		$this->db->where('id', (int) $id);
		return $this->db->get($this->withdrawals_table)->row();
	}

	public function update_withdrawal($id, $data)
	{
		$this->db->where('id', (int) $id);
		return $this->db->update($this->withdrawals_table, $data);
	}

	public function has_pending_withdrawal($user_id)
	{
		$this->db->where('user_id', (int) $user_id);
		$this->db->where('status', 'pending');
		return $this->db->count_all_results($this->withdrawals_table) > 0;
	}
}
