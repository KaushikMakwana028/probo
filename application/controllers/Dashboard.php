<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

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
		$categories = $this->Category_model->get_all_categories();
		$referral_settings = $this->User_model->get_referral_settings();

		$data = array(
			'title' => 'Dashboard',
			'page_type' => 'dashboard',
			'user' => $user,
			'categories' => $categories,
			'referral_code' => $referral_code,
			'referral_summary' => $referral_summary,
			'notifications' => $notifications,
			'unread_notifications' => $unread_notifications,
			'user_trade_count' => $user_trade_count,
			'referral_settings' => $referral_settings,
			'active_page' => 'dashboard'
		);

		$this->load->view('includes/header', $data);
		$this->load->view('dashboard_view', $data);
		$this->load->view('includes/footer', $data);
	}

	public function referral()
	{
		$user = $this->User_model->get_by_id($this->session->userdata('user_id'));

		if (!$user) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$referral_code = $this->User_model->ensure_referral_code($user->id);
		$referral_summary = $this->User_model->get_referral_summary($user->id);
		$referral_settings = $this->User_model->get_referral_settings();

		$notifications = $this->User_model->get_notifications_by_user($user->id, 5);
		$unread_notifications = $this->User_model->count_unread_notifications($user->id);
		$register_url = site_url('login/register');

		$share_message = 'Join PROBO and use my referral code ' . $referral_code .
			'. Earn ₹' . number_format((float)$referral_settings['referrer_bonus'], 2) .
			' on signup! Sign up here: ' . $register_url;

		$data = array(
			'title' => 'Referral Program',
			'page_type' => 'dashboard',
			'user' => $user,
			'referral_code' => $referral_code,
			'referral_summary' => $referral_summary,
			'referral_settings' => $referral_settings,
			'notifications' => $notifications,
			'unread_notifications' => $unread_notifications,
			'share_message' => $share_message,
			'register_url' => $register_url,
			'active_page' => 'referral'
		);

		$this->load->view('includes/header', $data);
		$this->load->view('referral_view', $data);
		$this->load->view('includes/footer', $data);
	}

	public function notifications()
	{
		$user = $this->User_model->get_by_id($this->session->userdata('user_id'));

		if (!$user) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$notifications = $this->User_model->get_notifications_by_user($user->id, NULL);
		$unread_notifications = $this->User_model->count_unread_notifications($user->id);

		$data = array(
			'title' => 'Notifications',
			'page_type' => 'dashboard',
			'user' => $user,
			'notifications' => $notifications,
			'unread_notifications' => $unread_notifications,
			'active_page' => 'notifications'
		);

		$this->load->view('includes/header', $data);
		$this->load->view('notifications_view', $data);
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

		$redirect_to = trim((string) $this->input->get('redirect_to', TRUE));
		if ($redirect_to !== '') {
			redirect($redirect_to);
		}

		redirect('dashboard/notifications');
	}

	public function mark_notification_read()
	{
		$user = $this->User_model->get_by_id($this->session->userdata('user_id'));

		if (!$user) {
			$this->output
				->set_status_header(401)
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'success' => false,
					'message' => 'Unauthorized'
				)));
			return;
		}

		$notification_id = (int) $this->input->post('notification_id');
		if ($notification_id <= 0) {
			$this->output
				->set_status_header(400)
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'success' => false,
					'message' => 'Invalid notification id'
				)));
			return;
		}

		$updated = $this->User_model->mark_notification_read($user->id, $notification_id);
		$unread_notifications = $this->User_model->count_unread_notifications($user->id);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => (bool) $updated,
				'unread_notifications' => $unread_notifications
			)));
	}
}
