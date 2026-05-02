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
		$listing = $this->User_model->get_referral_rewards_listing(array(
			'page' => 1,
			'per_page' => 10
		));

		$data = array(
			'title' => 'Referral List',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'settings' => $this->User_model->get_referral_settings(),
			'referral_rewards' => $listing['rows'],
			'referral_listing' => $listing,
			'active_page' => 'referrals_list'
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/referral_list_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function list_data()
	{
		$this->get_admin();

		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$listing = $this->User_model->get_referral_rewards_listing(array(
			'page' => (int) $this->input->get('page'),
			'per_page' => (int) $this->input->get('per_page'),
			'search' => $this->input->get('search', TRUE),
			'date_from' => $this->input->get('date_from', TRUE),
			'date_to' => $this->input->get('date_to', TRUE)
		));

		$start = $listing['filtered_total'] > 0 ? (($listing['page'] - 1) * $listing['per_page']) + 1 : 0;
		$end = min($listing['filtered_total'], $listing['page'] * $listing['per_page']);
		$rows = array();

		foreach ($listing['rows'] as $index => $reward) {
			$rows[] = array(
				'position' => $start + $index,
				'referred_name' => (string) $reward->referred_name,
				'referred_email' => (string) $reward->referred_email,
				'referred_mobile' => (string) $reward->referred_mobile,
				'referrer_name' => (string) $reward->referrer_name,
				'referrer_email' => (string) $reward->referrer_email,
				'referrer_mobile' => (string) $reward->referrer_mobile,
				'referral_code' => (string) $reward->referral_code,
				'referred_bonus' => number_format((float) $reward->referred_bonus, 2),
				'referrer_bonus' => number_format((float) $reward->referrer_bonus, 2),
				'created_at' => !empty($reward->created_at) ? (string) $reward->created_at : '-'
			);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'status' => 'success',
				'rows' => $rows,
				'pagination' => array(
					'total' => $listing['total'],
					'filtered_total' => $listing['filtered_total'],
					'page' => $listing['page'],
					'per_page' => $listing['per_page'],
					'total_pages' => $listing['total_pages'],
					'start' => $start,
					'end' => $end
				)
			)));
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


