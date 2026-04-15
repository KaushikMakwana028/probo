<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Category_model');

		if (!$this->session->userdata('admin_logged_in')) {
			redirect('admin/login');
		}
	}

	public function index()
	{
		if (!$this->session->userdata('admin_id')) {
			redirect('admin/login');
		}
		$admin = $this->User_model->get_by_id($this->session->userdata('admin_id'));

		if (!$admin || (int) $admin->role !== 1) {
			$this->session->unset_userdata(array('admin_id', 'admin_name', 'admin_mobile', 'admin_email', 'admin_logged_in'));
			redirect('admin/login');
		}

		$total_users = $this->User_model->count_all_users();
		$total_admins = $this->User_model->count_users_by_role(1);
		$total_members = $this->User_model->count_users_by_role(0);
		$winner_leaderboard = $this->User_model->get_leaderboard(10, TRUE);

		$data = array(
			'title' => 'Admin Dashboard',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'total_users' => $total_users,
			'total_admins' => $total_admins,
			'total_members' => $total_members,
			'total_categories' => $this->Category_model->count_all_categories(),
			'total_questions' => $this->Category_model->count_all_questions(),
			'latest_users' => array_slice($this->User_model->get_regular_users(), 0, 5),
			'winner_leaderboard' => $winner_leaderboard,
			'active_page' => 'dashboard'
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/dashboard_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}
}
