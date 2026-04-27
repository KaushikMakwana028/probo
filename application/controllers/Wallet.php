<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wallet extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Wallet_model');

		if (!$this->session->userdata('user_id')) {
			redirect('login');
		}
	}

	public function index()
	{
		$user = $this->get_user();
		$settings = $this->db->get('payment_settings')->row();
		$history_filter = strtolower(trim((string) $this->input->get('history')));
		$allowed_filters = array('all', 'winnings', 'withdrawals', 'deposits', 'trades', 'refunds');
		$withdraw_min_amount = 5000;

		if (
			$settings &&
			$this->db->field_exists('withdraw_min_amount', 'payment_settings') &&
			isset($settings->withdraw_min_amount) &&
			(float) $settings->withdraw_min_amount > 0
		) {
			$withdraw_min_amount = (float) $settings->withdraw_min_amount;
		}

		if (!in_array($history_filter, $allowed_filters, TRUE)) {
			$history_filter = 'all';
		}

		$transactions = $this->Wallet_model->get_transactions_by_user($user->id);
		$withdrawals = $this->Wallet_model->get_withdrawals_by_user($user->id);
		$bank_details_saved = !empty($user->bank_account_holder_name) && !empty($user->bank_name) && !empty($user->bank_account_number) && !empty($user->bank_ifsc_code);

		$data = array(
			'title' => 'Wallet',
			'page_type' => 'dashboard',
			'user' => $user,
			'transactions' => $transactions,
			'withdrawals' => $withdrawals,
			'total_winnings' => $this->Wallet_model->get_total_credited_by_user($user->id),
			'total_withdrawn' => $this->Wallet_model->get_total_debited_by_user($user->id),
			'total_deposited' => $this->Wallet_model->get_total_deposited_by_user($user->id),
			'history_filter' => $history_filter,
			'bank_details_saved' => $bank_details_saved,
			'withdraw_min_amount' => $withdraw_min_amount,
			'edit_bank_details' => $this->input->get('edit_bank') == '1' || !$bank_details_saved,
			'active_page' => 'wallet'
		);

		$this->load->view('includes/header', $data);
		$this->load->view('wallet_view', $data);
		$this->load->view('includes/footer', $data);
	}

	public function request_withdrawal()
	{
		$user = $this->get_user();
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

		$this->form_validation->set_rules('amount', 'Amount', 'required|numeric');

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('error', trim(strip_tags(validation_errors(' ', ' '))));
			redirect('wallet');
		}

		$amount = round((float) $this->input->post('amount', TRUE), 2);

		if ($amount <= 0) {
			$this->session->set_flashdata('error', 'Withdrawal amount must be greater than zero.');
			redirect('wallet');
		}

		if ($amount < $withdraw_min_amount) {
			$this->session->set_flashdata('error', 'Minimum withdrawal amount is Rs ' . number_format($withdraw_min_amount, 2) . '.');
			redirect('wallet');
		}

		if ($amount > (float) $user->wallet_balance) {
			$this->session->set_flashdata('error', 'Withdrawal amount cannot exceed your wallet balance.');
			redirect('wallet');
		}

		if ($this->Wallet_model->has_pending_withdrawal($user->id)) {
			$this->session->set_flashdata('error', 'You already have a pending withdrawal request.');
			redirect('wallet');
		}

		if (empty($user->bank_account_holder_name) || empty($user->bank_name) || empty($user->bank_account_number) || empty($user->bank_ifsc_code)) {
			$this->session->set_flashdata('error', 'Please save your bank details first.');
			redirect('wallet?edit_bank=1');
		}

		$this->Wallet_model->create_withdrawal_request(array(
			'user_id' => (int) $user->id,
			'amount' => $amount,
			'account_holder_name' => $user->bank_account_holder_name,
			'bank_name' => $user->bank_name,
			'account_number' => $user->bank_account_number,
			'ifsc_code' => strtoupper($user->bank_ifsc_code),
			'status' => 'pending'
		));
		$this->User_model->add_notification(array(
			'user_id' => (int) $user->id,
			'title' => 'Withdrawal requested',
			'message' => 'Your withdrawal request for Rs ' . number_format($amount, 2) . ' has been sent to admin.',
			'type' => 'withdrawal'
		));

		$this->session->set_flashdata('success', 'Withdrawal request sent to admin for approval.');
		redirect('wallet');
	}

	public function add_balance()
	{
		$user = $this->get_user(); // use get_user() for consistency + null check

		$settings = $this->db->get('payment_settings')->row();

		// Always sync session wallet balance with DB value
		$this->session->set_userdata('wallet_balance', $user->wallet_balance);

		$data = [
			'title' => 'Add Balance',
			'user' => $user,
			'settings' => $settings,
			'page_type' => 'dashboard'
		];

		$this->load->view('includes/header', $data);
		$this->load->view('add_balance_view', $data);
		$this->load->view('includes/footer');
	}

	public function request_deposit()
	{
		$user = $this->get_user();

		$amount = (float)$this->input->post('amount');

		if ($amount <= 0) {
			$this->session->set_flashdata('error', 'Invalid amount');
			redirect('wallet/add_balance');
		}

		// ============================
		// UPLOAD RECEIPT
		// ============================
		if (empty($_FILES['receipt']['name'])) {
			$this->session->set_flashdata('error', 'Receipt is required');
			redirect('wallet/add_balance');
		}

		$upload_path = './uploads/receipts/';
		if (!is_dir($upload_path)) {
			mkdir($upload_path, 0755, true);
		}

		$config['upload_path']   = $upload_path;
		$config['allowed_types'] = 'jpg|jpeg|png|pdf';
		$config['max_size']      = 4096;
		$config['encrypt_name']  = TRUE;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('receipt')) {
			$this->session->set_flashdata('error', $this->upload->display_errors());
			redirect('wallet/add_balance');
		}

		$upload_data = $this->upload->data();
		$receipt_file = $upload_data['file_name'];

		// ============================
		// SAVE REQUEST
		// ============================
		$this->db->insert('deposit_requests', [
			'user_id'  => $user->id,
			'amount'   => $amount,
			'receipt'  => $receipt_file,
			'status'   => 'pending',
			'created_at' => date('Y-m-d H:i:s')
		]);

		$this->session->set_flashdata('success', 'Deposit request submitted successfully');
		redirect('wallet/add_balance');
	}
	
	public function save_bank_details()
	{
		$user = $this->get_user();

		$this->form_validation->set_rules('bank_account_holder_name', 'Account holder name', 'required|trim');
		$this->form_validation->set_rules('bank_name', 'Bank name', 'required|trim');
		$this->form_validation->set_rules('bank_account_number', 'Account number', 'required|trim');
		$this->form_validation->set_rules('bank_ifsc_code', 'IFSC code', 'required|trim');

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('error', trim(strip_tags(validation_errors(' ', ' '))));
			redirect('wallet?edit_bank=1');
		}

		$this->User_model->update($user->id, array(
			'bank_account_holder_name' => $this->input->post('bank_account_holder_name', TRUE),
			'bank_name' => $this->input->post('bank_name', TRUE),
			'bank_account_number' => $this->input->post('bank_account_number', TRUE),
			'bank_ifsc_code' => strtoupper($this->input->post('bank_ifsc_code', TRUE))
		));

		$this->session->set_flashdata('success', 'Bank details saved successfully.');
		redirect('wallet');
	}

	private function get_user()
	{
		$user = $this->User_model->get_by_id($this->session->userdata('user_id'));

		if (!$user) {
			$this->session->sess_destroy();
			redirect('login');
		}

		return $user;
	}
}
