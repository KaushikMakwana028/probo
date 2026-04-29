<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Withdrawals extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Wallet_model');

		if (!$this->session->userdata('admin_logged_in')) {
			redirect('admin/login');
		}
	}

	public function index()
	{
		$admin = $this->get_admin();
		$withdrawals = $this->Wallet_model->get_all_withdrawals();
		$pending_count = 0;
		$approved_count = 0;
		$total_requested = 0.0;

		foreach ($withdrawals as $withdrawal) {
			$total_requested += (float) $withdrawal->amount;

			if ($withdrawal->status === 'pending') {
				$pending_count++;
			} elseif ($withdrawal->status === 'approved') {
				$approved_count++;
			}
		}

		$data = array(
			'title' => 'Withdrawal Requests',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'withdrawals' => $withdrawals,
			'pending_count' => $pending_count,
			'approved_count' => $approved_count,
			'total_requested' => $total_requested,
			'active_page' => 'withdrawals_requests'
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/withdrawals_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function settings()
	{
		$admin = $this->get_admin();
		$settings = $this->db->get('payment_settings')->row();
		$withdraw_min_amount = 5000;

		if (
			$settings &&
			$this->db->field_exists('withdraw_min_amount', 'payment_settings') &&
			isset($settings->withdraw_min_amount) &&
			(float) $settings->withdraw_min_amount > 0
		) {
			$withdraw_min_amount = (float) $settings->withdraw_min_amount;
		}

		$data = array(
			'title' => 'Set Withdraw Amount',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'withdraw_min_amount' => $withdraw_min_amount,
			'active_page' => 'withdrawals_settings'
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/withdrawal_settings_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function save_settings()
	{
		$this->get_admin();
		$this->form_validation->set_rules('withdraw_min_amount', 'Withdraw amount', 'required|numeric|greater_than[0]');

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('error', trim(strip_tags(validation_errors(' ', ' '))));
			redirect('admin/withdrawals/settings');
		}

		if (!$this->db->field_exists('withdraw_min_amount', 'payment_settings')) {
			$this->session->set_flashdata('error', 'Please run the withdrawal settings SQL update first.');
			redirect('admin/withdrawals/settings');
		}

		$amount = round((float) $this->input->post('withdraw_min_amount', TRUE), 2);
		$data = array(
			'withdraw_min_amount' => $amount,
			'updated_at' => date('Y-m-d H:i:s')
		);

		if ($this->db->count_all('payment_settings') > 0) {
			$this->db->update('payment_settings', $data);
		} else {
			$data['upi_id'] = '';
			$data['bank_name'] = '';
			$data['account_number'] = '';
			$data['ifsc'] = '';
			$data['created_at'] = date('Y-m-d H:i:s');
			$this->db->insert('payment_settings', $data);
		}

		$this->session->set_flashdata('success', 'Withdraw amount updated successfully.');
		redirect('admin/withdrawals/settings');
	}

	public function approve($id)
	{
		$this->get_admin();
		$withdrawal = $this->Wallet_model->get_withdrawal((int) $id);
		$admin_note = trim((string) $this->input->post('admin_note', TRUE));

		if (!$withdrawal || $withdrawal->status !== 'pending') {
			$this->session->set_flashdata('error', 'Withdrawal request not found or already processed.');
			redirect('admin/withdrawals');
		}

		$user = $this->User_model->get_by_id((int) $withdrawal->user_id);

		if (!$user || (float) $user->wallet_balance < (float) $withdrawal->amount) {
			$this->Wallet_model->update_withdrawal((int) $withdrawal->id, array(
				'status' => 'rejected',
				'admin_note' => $admin_note !== '' ? $admin_note : 'Insufficient wallet balance at approval time.'
			));
			$this->session->set_flashdata('error', 'Wallet balance was insufficient, so the request was rejected.');
			redirect('admin/withdrawals');
		}

		$this->User_model->adjust_wallet_balance((int) $withdrawal->user_id, -1 * (float) $withdrawal->amount);
		$this->Wallet_model->add_transaction(array(
			'user_id' => (int) $withdrawal->user_id,
			'source_type' => 'withdrawal',
			'source_id' => (int) $withdrawal->id,
			'type' => 'debit',
			'amount' => (float) $withdrawal->amount,
			'description' => 'Withdrawal approved for bank transfer'
		));
		$this->Wallet_model->update_withdrawal((int) $withdrawal->id, array(
			'status' => 'approved',
			'admin_note' => $admin_note !== '' ? $admin_note : 'Approved and marked for bank transfer.'
		));
		$this->User_model->add_notification(array(
			'user_id' => (int) $withdrawal->user_id,
			'title' => 'Withdrawal approved',
			'message' => 'Your withdrawal request for Rs ' . number_format((float) $withdrawal->amount, 2) . ' was approved.' . ($admin_note !== '' ? ' Remark: ' . $admin_note : ''),
			'type' => 'withdrawal'
		));

		$this->session->set_flashdata('success', 'Withdrawal approved. Amount has been deducted from the wallet for bank payout.');
		redirect('admin/withdrawals');
	}

	public function reject($id)
	{
		$this->get_admin();
		$withdrawal = $this->Wallet_model->get_withdrawal((int) $id);
		$admin_note = trim((string) $this->input->post('admin_note', TRUE));

		if (!$withdrawal || $withdrawal->status !== 'pending') {
			$this->session->set_flashdata('error', 'Withdrawal request not found or already processed.');
			redirect('admin/withdrawals');
		}

		$this->Wallet_model->update_withdrawal((int) $withdrawal->id, array(
			'status' => 'rejected',
			'admin_note' => $admin_note !== '' ? $admin_note : 'Rejected by admin.'
		));
		$this->User_model->add_notification(array(
			'user_id' => (int) $withdrawal->user_id,
			'title' => 'Withdrawal rejected',
			'message' => 'Your withdrawal request for Rs ' . number_format((float) $withdrawal->amount, 2) . ' was rejected.' . ($admin_note !== '' ? ' Remark: ' . $admin_note : ''),
			'type' => 'withdrawal'
		));

		$this->session->set_flashdata('success', 'Withdrawal request rejected.');
		redirect('admin/withdrawals');
	}

	private function get_admin()
	{
		$admin_id = (int) $this->session->userdata('admin_id');

		if ($admin_id <= 0) {
			redirect('admin/login');
		}

		$admin = $this->User_model->get_admin_by_id($admin_id);

		if (!$admin) {
			redirect('admin/login');
		}

		return $admin;
	}
}


