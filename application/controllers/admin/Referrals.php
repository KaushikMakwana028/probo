<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referrals extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');

		if (!$this->session->userdata('admin_logged_in')) {
			redirect('admin/login');
		}
	}

	public function add()
	{
		$admin = $this->get_admin();

		$data = array(
			'title' => 'Referral Settings',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'settings' => $this->User_model->get_referral_settings(),
			'active_page' => 'referrals_add'
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/referral_add_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function save()
	{
		$this->get_admin();
		$this->form_validation->set_rules('new_user_bonus', 'New user bonus', 'required|numeric');
		$this->form_validation->set_rules('referrer_bonus', 'Referrer bonus', 'required|numeric');

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('error', trim(strip_tags(validation_errors(' ', ' '))));
			redirect('admin/referrals/add');
		}

		$new_user_bonus = round((float) $this->input->post('new_user_bonus', TRUE), 2);
		$referrer_bonus = round((float) $this->input->post('referrer_bonus', TRUE), 2);

		if ($new_user_bonus < 0 || $referrer_bonus < 0) {
			$this->session->set_flashdata('error', 'Referral amounts must be zero or greater.');
			redirect('admin/referrals/add');
		}

		if (!$this->User_model->save_referral_settings($new_user_bonus, $referrer_bonus)) {
			$this->session->set_flashdata('error', 'Referral settings could not be saved. Please run the referral SQL update first.');
			redirect('admin/referrals/add');
		}

		$this->session->set_flashdata('success', 'Referral settings updated successfully.');
		redirect('admin/referrals/add');
	}

	public function list()
	{
		$admin = $this->get_admin();

		$data = array(
			'title' => 'Referral List',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'settings' => $this->User_model->get_referral_settings(),
			'referral_rewards' => $this->User_model->get_referral_rewards(),
			'active_page' => 'referrals_list'
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/referral_list_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	private function get_admin()
	{
		$admin = $this->User_model->get_by_id($this->session->userdata('admin_id'));

		if (!$admin || (int) $admin->role !== 1) {
			$this->session->unset_userdata(array(
				'admin_id',
				'admin_name',
				'admin_mobile',
				'admin_email',
				'admin_logged_in'   // 🔥 VERY IMPORTANT
			));
			redirect('admin/login');
		}

		return $admin;
	}
}
