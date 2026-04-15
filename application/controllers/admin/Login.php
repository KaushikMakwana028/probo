<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
	}

	public function index()
	{
		if ($this->session->userdata('admin_logged_in') === TRUE) {
			redirect('admin/dashboard');
		}

		$data = array(
			'title' => 'Admin Login',
			'page_type' => 'auth'
		);
		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/login_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function authenticate()
	{
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|numeric|min_length[10]|max_length[15]');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]');

		if ($this->form_validation->run() === FALSE) {
			$this->index();
			return;
		}

		$user = $this->User_model->get_admin_by_mobile($this->input->post('mobile', TRUE));
		$password = $this->input->post('password', TRUE);

		if (!$user || !password_verify($password, $user->password)) {
			$this->session->set_flashdata('error', 'Invalid admin mobile or password.');
			redirect('admin/login');
		}

		$this->session->set_userdata(array(
			'admin_id' => $user->id,
			'admin_name' => $user->name,
			'admin_mobile' => $user->mobile,
			'admin_email' => $user->email,
			'admin_logged_in' => TRUE
		));

		redirect('admin/dashboard');
	}

	public function logout()
	{
		$this->session->unset_userdata(array(
			'admin_id',
			'admin_name',
			'admin_mobile',
			'admin_email',
			'admin_logged_in'
		));
		redirect('admin/login');
	}
}
