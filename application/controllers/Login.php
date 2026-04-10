<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Wallet_model');
		$this->load->library('email');
	}

	public function index()
	{
		if ($this->session->userdata('user_id')) {
			redirect('dashboard');
		}

		$data = array(
			'title' => 'Login',
			'page_type' => 'auth'
		);

		$this->load->view('includes/header', $data);
		$this->load->view('login_view', $data);
		$this->load->view('includes/footer', $data);
	}

	public function register()
	{
		if ($this->session->userdata('user_id')) {
			redirect('dashboard');
		}

		$data = array(
			'title' => 'Register',
			'page_type' => 'auth'
		);

		$this->load->view('includes/header', $data);
		$this->load->view('register_view', $data);
		$this->load->view('includes/footer', $data);
	}

	public function forgot_password()
	{
		if ($this->session->userdata('user_id')) {
			redirect('dashboard');
		}

		$data = array(
			'title' => 'Forgot Password',
			'page_type' => 'auth'
		);

		$this->load->view('forgot_password_view', $data);
	}

	public function authenticate()
	{
		$this->form_validation->set_rules('identity', 'Mobile or Email', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]');

		if ($this->form_validation->run() === FALSE) {
			$this->index();
			return;
		}

		$identity = $this->input->post('identity', TRUE);
		$password = $this->input->post('password', TRUE);
		$user = $this->User_model->get_by_login_identity($identity);

		if (!$user || !password_verify($password, $user->password)) {
			$this->session->set_flashdata('error', 'Invalid mobile/email or password.');
			redirect('login');
		}

		$this->session->set_userdata(array(
			'user_id' => $user->id,
			'user_name' => $user->name,
			'user_mobile' => $user->mobile,
			'user_email' => $user->email,
			'is_logged_in' => TRUE
		));

		redirect('dashboard');
	}

	public function store_register()
	{
		$this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[3]');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|numeric|min_length[10]|max_length[15]|is_unique[users.mobile]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]');

		if ($this->form_validation->run() === FALSE) {
			$this->register();
			return;
		}

		$referral_code = strtoupper(trim((string) $this->input->post('referral_code', TRUE)));
		$referrer = NULL;

		if ($referral_code !== '') {
			$referrer = $this->User_model->get_by_referral_code($referral_code);

			if (!$referrer || (int) $referrer->role !== 0) {
				$this->session->set_flashdata('error', 'Referral code is invalid.');
				redirect('login/register');
			}
		}

		$user_data = array(
			'name' => $this->input->post('name', TRUE),
			'mobile' => $this->input->post('mobile', TRUE),
			'email' => $this->input->post('email', TRUE),
			'role' => 0,
			'address' => '',
			'profile_image' => NULL,
			'password' => password_hash($this->input->post('password', TRUE), PASSWORD_DEFAULT),
			'referral_code' => $this->User_model->generate_referral_code($this->input->post('name', TRUE)),
			'referred_by_user_id' => $referrer ? (int) $referrer->id : NULL
		);

		$user_id = $this->User_model->insert($user_data);

		if ($referrer && $user_id) {
			$referral_settings = $this->User_model->get_referral_settings();
			$referrer_bonus = max(0, (float) $referral_settings['referrer_bonus']);
			$new_user_bonus = max(0, (float) $referral_settings['new_user_bonus']);

			if ($referrer_bonus > 0) {
				$this->User_model->adjust_wallet_balance((int) $referrer->id, $referrer_bonus);
			}

			if ($new_user_bonus > 0) {
				$this->User_model->adjust_wallet_balance((int) $user_id, $new_user_bonus);
			}

			if ($referrer_bonus > 0) {
				$this->Wallet_model->add_transaction(array(
					'user_id' => (int) $referrer->id,
					'source_type' => 'referral_bonus',
					'source_id' => (int) $user_id,
					'type' => 'credit',
					'amount' => $referrer_bonus,
					'description' => 'Referral bonus for inviting ' . $user_data['name']
				));
			}

			if ($new_user_bonus > 0) {
				$this->Wallet_model->add_transaction(array(
					'user_id' => (int) $user_id,
					'source_type' => 'referral_bonus',
					'source_id' => (int) $referrer->id,
					'type' => 'credit',
					'amount' => $new_user_bonus,
					'description' => 'Referral welcome bonus from code ' . $referral_code
				));
			}

			$this->User_model->create_referral_reward(array(
				'referrer_user_id' => (int) $referrer->id,
				'referred_user_id' => (int) $user_id,
				'referrer_bonus' => $referrer_bonus,
				'referred_bonus' => $new_user_bonus,
				'referral_code' => $referral_code
			));

			$this->User_model->add_notification(array(
				'user_id' => (int) $referrer->id,
				'title' => 'Referral bonus credited',
				'message' => $user_data['name'] . ' joined with your referral code. Rs ' . number_format($referrer_bonus, 2) . ' was added to your wallet.',
				'type' => 'referral'
			));

			$this->User_model->add_notification(array(
				'user_id' => (int) $user_id,
				'title' => 'Referral welcome bonus',
				'message' => 'Your registration bonus of Rs ' . number_format($new_user_bonus, 2) . ' was added to your wallet.',
				'type' => 'wallet'
			));
		}

		$this->session->set_userdata(array(
			'user_id' => $user_id,
			'user_name' => $user_data['name'],
			'user_mobile' => $user_data['mobile'],
			'user_email' => $user_data['email'],
			'is_logged_in' => TRUE
		));

		$this->session->set_flashdata('success', $referrer ? 'Registration completed successfully. Referral bonus added to wallet.' : 'Registration completed successfully.');
		redirect('dashboard');
	}

	public function send_reset_code()
	{
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

		if ($this->form_validation->run() === FALSE) {
			$this->forgot_password();
			return;
		}

		$email = $this->input->post('email', TRUE);
		$user = $this->User_model->get_by_email($email);

		if (!$user) {
			$this->session->set_flashdata('error', 'Email address not found.');
			redirect('login/forgot-password');
		}

		$code = (string) random_int(100000, 999999);
		$expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

		$this->User_model->update($user->id, array(
			'reset_token' => $code,
			'reset_token_expiry' => $expiry
		));

		$this->email->from('trilexadvisories@gmail.com', 'PROBO');
		$this->email->to($user->email);
		$this->email->subject('Your Password Reset Code');
		$this->email->message("Hello ".$user->name.",\r\n\r\nYour password reset code is: ".$code."\r\n\r\nEnter this code in the reset form. This code will expire in 1 hour.");

		if (!$this->email->send()) {
			$this->session->set_flashdata('error', 'Reset code email could not be sent. Please check SMTP settings.');
			redirect('login/forgot-password');
		}

		$this->session->set_userdata('reset_email', $user->email);
		$this->session->set_flashdata('success', 'Reset code sent to your email.');
		redirect('login/verify-reset-code');
	}

	public function verify_reset_code()
	{
		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
			$this->form_validation->set_rules('reset_code', 'Reset Code', 'required|trim|numeric|exact_length[6]');

			if ($this->form_validation->run() === FALSE) {
				$data = array(
					'title' => 'Verify Reset Code',
					'page_type' => 'auth',
					'reset_email' => $this->input->post('email', TRUE)
				);
				$this->load->view('verify_reset_code_view', $data);
				return;
			}

			$email = $this->input->post('email', TRUE);
			$code = $this->input->post('reset_code', TRUE);
			$user = $this->User_model->get_by_reset_code($email, $code);

			if (!$user) {
				$this->session->set_flashdata('error', 'Invalid or expired reset code.');
				redirect('login/verify-reset-code');
			}

			$this->session->set_userdata('reset_user_id', $user->id);
			$this->session->set_userdata('reset_email', $user->email);
			redirect('login/reset-password');
		}

		$data = array(
			'title' => 'Verify Reset Code',
			'page_type' => 'auth',
			'reset_email' => $this->session->userdata('reset_email')
		);

		$this->load->view('verify_reset_code_view', $data);
	}

	public function reset_password()
	{
		$reset_user_id = $this->session->userdata('reset_user_id');

		if (!$reset_user_id) {
			$this->session->set_flashdata('error', 'Please verify your reset code first.');
			redirect('login/forgot-password');
		}

		$data = array(
			'title' => 'Reset Password',
			'page_type' => 'auth'
		);

		$this->load->view('reset_password_view', $data);
	}

	public function update_password()
	{
		$reset_user_id = $this->session->userdata('reset_user_id');

		if (!$reset_user_id) {
			$this->session->set_flashdata('error', 'Please verify your reset code first.');
			redirect('login/forgot-password');
		}

		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|matches[password]');

		if ($this->form_validation->run() === FALSE) {
			$this->reset_password();
			return;
		}

		$this->User_model->update($reset_user_id, array(
			'password' => password_hash($this->input->post('password', TRUE), PASSWORD_DEFAULT),
			'reset_token' => NULL,
			'reset_token_expiry' => NULL
		));

		$this->session->unset_userdata('reset_user_id');
		$this->session->unset_userdata('reset_email');
		$this->session->set_flashdata('success', 'Password reset successfully. Please login.');
		redirect('login');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}
