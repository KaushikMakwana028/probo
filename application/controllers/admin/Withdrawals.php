<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Withdrawals extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Wallet_model');

		if (!$this->session->userdata('admin_id')) {
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
			'active_page' => 'withdrawals'
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/withdrawals_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function approve($id)
	{
		$this->get_admin();
		$withdrawal = $this->Wallet_model->get_withdrawal((int) $id);

		if (!$withdrawal || $withdrawal->status !== 'pending') {
			$this->session->set_flashdata('error', 'Withdrawal request not found or already processed.');
			redirect('admin/withdrawals');
		}

		$user = $this->User_model->get_by_id((int) $withdrawal->user_id);

		if (!$user || (float) $user->wallet_balance < (float) $withdrawal->amount) {
			$this->Wallet_model->update_withdrawal((int) $withdrawal->id, array(
				'status' => 'rejected',
				'admin_note' => 'Insufficient wallet balance at approval time.'
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
			'admin_note' => 'Approved and marked for bank transfer.'
		));
		$this->User_model->add_notification(array(
			'user_id' => (int) $withdrawal->user_id,
			'title' => 'Withdrawal approved',
			'message' => 'Your withdrawal request for Rs ' . number_format((float) $withdrawal->amount, 2) . ' was approved.',
			'type' => 'withdrawal'
		));

		$this->session->set_flashdata('success', 'Withdrawal approved. Amount has been deducted from the wallet for bank payout.');
		redirect('admin/withdrawals');
	}

	public function reject($id)
	{
		$this->get_admin();
		$withdrawal = $this->Wallet_model->get_withdrawal((int) $id);

		if (!$withdrawal || $withdrawal->status !== 'pending') {
			$this->session->set_flashdata('error', 'Withdrawal request not found or already processed.');
			redirect('admin/withdrawals');
		}

		$this->Wallet_model->update_withdrawal((int) $withdrawal->id, array(
			'status' => 'rejected',
			'admin_note' => 'Rejected by admin.'
		));
		$this->User_model->add_notification(array(
			'user_id' => (int) $withdrawal->user_id,
			'title' => 'Withdrawal rejected',
			'message' => 'Your withdrawal request for Rs ' . number_format((float) $withdrawal->amount, 2) . ' was rejected.',
			'type' => 'withdrawal'
		));

		$this->session->set_flashdata('success', 'Withdrawal request rejected.');
		redirect('admin/withdrawals');
	}

	private function get_admin()
	{
		$admin = $this->User_model->get_by_id($this->session->userdata('admin_id'));

		if (!$admin || (int) $admin->role !== 1) {
			$this->session->unset_userdata(array('admin_id', 'admin_name', 'admin_mobile', 'admin_email'));
			redirect('admin/login');
		}

		return $admin;
	}
}
