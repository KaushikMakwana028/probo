<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');

		if (!$this->session->userdata('admin_id')) {
			redirect('admin/login');
		}
	}

	public function index()
	{
		$admin = $this->User_model->get_by_id($this->session->userdata('admin_id'));

		if (!$admin || (int) $admin->role !== 1) {
			$this->session->unset_userdata(array('admin_id', 'admin_name', 'admin_mobile', 'admin_email'));
			redirect('admin/login');
		}

		$data = array(
			'title' => 'Admin Profile',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'active_page' => 'profile'
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/profile_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function update()
	{
		$admin_id = $this->session->userdata('admin_id');
		$admin = $this->User_model->get_by_id($admin_id);

		if (!$admin || (int) $admin->role !== 1) {
			$this->session->unset_userdata(array('admin_id', 'admin_name', 'admin_mobile', 'admin_email'));
			redirect('admin/login');
		}

		$this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[3]');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|numeric|min_length[10]|max_length[15]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('address', 'Address', 'trim');

		if ($this->form_validation->run() === FALSE) {
			$this->index();
			return;
		}

		$mobile = $this->input->post('mobile', TRUE);
		$email = $this->input->post('email', TRUE);

		if ($this->User_model->mobile_exists($mobile, $admin_id)) {
			$this->session->set_flashdata('error', 'This mobile number is already used by another user.');
			redirect('admin/profile');
		}

		if ($this->User_model->email_exists($email, $admin_id)) {
			$this->session->set_flashdata('error', 'This email address is already used by another user.');
			redirect('admin/profile');
		}

		$profile_data = array(
			'name' => $this->input->post('name', TRUE),
			'mobile' => $mobile,
			'email' => $email,
			'address' => $this->input->post('address', TRUE)
		);

		$uploaded_image = $this->handle_profile_upload($admin);

		if ($uploaded_image === FALSE) {
			return;
		}

		if ($uploaded_image !== NULL) {
			$profile_data['profile_image'] = $uploaded_image;
		}

		$this->User_model->update($admin_id, $profile_data);
		$this->session->set_userdata(array(
			'admin_name' => $profile_data['name'],
			'admin_mobile' => $profile_data['mobile'],
			'admin_email' => $profile_data['email']
		));

		$this->session->set_flashdata('success', 'Admin profile updated successfully.');
		redirect('admin/profile');
	}

	public function change_password()
	{
		$admin_id = $this->session->userdata('admin_id');
		$admin = $this->User_model->get_by_id($admin_id);

		if (!$admin || (int) $admin->role !== 1) {
			$this->session->unset_userdata(array('admin_id', 'admin_name', 'admin_mobile', 'admin_email'));
			redirect('admin/login');
		}

		$this->form_validation->set_rules('new_password', 'New Password', 'required|trim|min_length[6]');

		if ($this->form_validation->run() === FALSE) {
			$this->index();
			return;
		}

		$this->User_model->update($admin_id, array(
			'password' => password_hash($this->input->post('new_password', TRUE), PASSWORD_DEFAULT)
		));

		$this->session->set_flashdata('success', 'Admin password changed successfully.');
		redirect('admin/profile');
	}

	private function handle_profile_upload($admin)
	{
		if (empty($_FILES['profile_image_file']['name'])) {
			return NULL;
		}

		$upload_path = FCPATH.'uploads/profile/';

		if (!is_dir($upload_path)) {
			mkdir($upload_path, 0777, TRUE);
		}

		$config = array(
			'upload_path' => $upload_path,
			'allowed_types' => 'jpg|jpeg|png|gif|webp',
			'max_size' => 2048,
			'encrypt_name' => TRUE
		);

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('profile_image_file')) {
			$this->session->set_flashdata('error', $this->upload->display_errors('', ''));
			redirect('admin/profile');
			return FALSE;
		}

		$upload_data = $this->upload->data();
		$new_image = 'uploads/profile/'.$upload_data['file_name'];

		if (!empty($admin->profile_image) && strpos($admin->profile_image, 'uploads/profile/') === 0) {
			$old_file = FCPATH.$admin->profile_image;

			if (is_file($old_file)) {
				@unlink($old_file);
			}
		}

		return $new_image;
	}
}
