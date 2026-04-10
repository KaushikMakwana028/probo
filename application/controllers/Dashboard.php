<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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

		$referral_code = $this->User_model->ensure_referral_code($user->id);
		$referral_summary = $this->User_model->get_referral_summary($user->id);
		$notifications = $this->User_model->get_notifications_by_user($user->id, 5);
		$unread_notifications = $this->User_model->count_unread_notifications($user->id);
		$user_trade_count = $this->Category_model->count_user_attempted_answers($user->id);

		$data = array(
			'title' => 'Dashboard',
			'page_type' => 'dashboard',
			'user' => $user,
			'referral_code' => $referral_code,
			'referral_summary' => $referral_summary,
			'notifications' => $notifications,
			'unread_notifications' => $unread_notifications,
			'user_trade_count' => $user_trade_count,
			'active_page' => 'dashboard'
		);

		$this->load->view('includes/header', $data);
		$this->load->view('dashboard_view', $data);
		$this->load->view('includes/footer', $data);
	}

	public function mark_notifications_read()
	{
		$user = $this->User_model->get_by_id($this->session->userdata('user_id'));

		if (!$user) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$this->User_model->mark_all_notifications_read($user->id);
		$this->session->set_flashdata('success', 'Notifications marked as read.');
		redirect('dashboard');
	}
}
