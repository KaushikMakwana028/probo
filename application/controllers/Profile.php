<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');

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

		$data = array(
			'title' => 'My Profile',
			'page_type' => 'dashboard',
			'user' => $user,
			'active_page' => 'profile'
		);

		$this->load->view('includes/header', $data);
		$this->load->view('profile_view', $data);
		$this->load->view('includes/footer', $data);
	}

	public function update()
	{
		$user_id = $this->session->userdata('user_id');
		$user = $this->User_model->get_by_id($user_id);

		if (!$user) {
			$this->session->sess_destroy();
			redirect('login');
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

		if ($this->User_model->mobile_exists($mobile, $user_id)) {
			$this->session->set_flashdata('error', 'This mobile number is already used by another user.');
			redirect('profile');
		}

		if ($this->User_model->email_exists($email, $user_id)) {
			$this->session->set_flashdata('error', 'This email address is already used by another user.');
			redirect('profile');
		}

		$profile_data = array(
			'name' => $this->input->post('name', TRUE),
			'mobile' => $mobile,
			'email' => $email,
			'address' => $this->input->post('address', TRUE)
		);

		$uploaded_image = $this->handle_profile_upload($user);

		if ($uploaded_image === FALSE) {
			return;
		}

		if ($uploaded_image !== NULL) {
			$profile_data['profile_image'] = $uploaded_image;
		}

		$this->User_model->update($user_id, $profile_data);
		$this->refresh_session($profile_data);

		$this->session->set_flashdata('success', 'Profile updated successfully.');
		redirect('profile');
	}

	public function change_password()
	{
		$user_id = $this->session->userdata('user_id');
		$user = $this->User_model->get_by_id($user_id);

		if (!$user) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$this->form_validation->set_rules('new_password', 'New Password', 'required|trim|min_length[6]');

		if ($this->form_validation->run() === FALSE) {
			$this->index();
			return;
		}

		$new_password = $this->input->post('new_password', TRUE);

		$this->User_model->update($user_id, array(
			'password' => password_hash($new_password, PASSWORD_DEFAULT)
		));

		$this->session->set_flashdata('success', 'Password changed successfully.');
		redirect('profile');
	}

	private function refresh_session($profile_data)
	{
		$this->session->set_userdata(array(
			'user_name' => $profile_data['name'],
			'user_mobile' => $profile_data['mobile'],
			'user_email' => $profile_data['email']
		));
	}

	private function handle_profile_upload($user)
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
			redirect('profile');
			return FALSE;
		}

		$upload_data = $this->upload->data();
		$new_image = 'uploads/profile/'.$upload_data['file_name'];

		if (!empty($user->profile_image) && strpos($user->profile_image, 'uploads/profile/') === 0) {
			$old_file = FCPATH.$user->profile_image;

			if (is_file($old_file)) {
				@unlink($old_file);
			}
		}

		return $new_image;
	}
}
