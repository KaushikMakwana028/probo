<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Category_model');
		$this->load->model('Wallet_model');

		if (!$this->session->userdata('admin_logged_in')) {
			redirect('admin/login');
		}
	}

	public function index()
	{
		$admin = $this->get_admin();
		$users = $this->User_model->get_regular_users();

		$data = array(
			'title' => 'Users',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'users' => $users,
			'total_users' => count($users),
			'active_page' => 'users'
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/users_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function view($id)
	{
		$admin = $this->get_admin();
		$user = $this->get_regular_user_or_redirect((int) $id);

		$data = array(
			'title' => 'User Details',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'user' => $user,
			'attempted_answers' => $this->Category_model->count_user_attempted_answers($user->id),
			'total_winnings' => $this->Wallet_model->get_total_credited_by_user($user->id),
			'total_withdraw' => $this->Wallet_model->get_total_withdrawn_by_user($user->id),
			'active_page' => 'users'
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/user_details_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function edit($id)
	{
		$admin = $this->get_admin();
		$user = $this->get_regular_user_or_redirect((int) $id);

		$data = array(
			'title' => 'Edit User',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'user' => $user,
			'active_page' => 'users'
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/user_edit_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function update($id)
	{
		$this->get_admin();
		$id = (int) $id;
		$user = $this->get_regular_user_or_redirect($id);

		$this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[3]');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|numeric|min_length[10]|max_length[15]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('address', 'Address', 'trim');

		if ($this->form_validation->run() === FALSE) {
			$this->set_validation_error_flashdata();
			redirect('admin/users/edit/' . $id);
		}

		$mobile = $this->input->post('mobile', TRUE);
		$email = $this->input->post('email', TRUE);

		if ($this->User_model->mobile_exists($mobile, $id)) {
			$this->session->set_flashdata('error', 'This mobile number is already used by another user.');
			redirect('admin/users/edit/' . $id);
		}

		if ($this->User_model->email_exists($email, $id)) {
			$this->session->set_flashdata('error', 'This email address is already used by another user.');
			redirect('admin/users/edit/' . $id);
		}

		$update_data = array(
			'name' => $this->input->post('name', TRUE),
			'mobile' => $mobile,
			'email' => $email,
			'address' => $this->input->post('address', TRUE)
		);

		$password = trim((string) $this->input->post('password', TRUE));

		if ($password !== '') {
			if (strlen($password) < 6) {
				$this->session->set_flashdata('error', 'Password must be at least 6 characters long.');
				redirect('admin/users/edit/' . $id);
			}

			$update_data['password'] = password_hash($password, PASSWORD_DEFAULT);
		}

		$this->User_model->update($id, $update_data);
		$this->session->set_flashdata('success', 'User updated successfully.');
		redirect('admin/users');
	}

	public function delete($id)
	{
		$this->get_admin();
		$id = (int) $id;
		$user = $this->get_regular_user_or_redirect($id);

		if (!$this->User_model->delete($id)) {
			$this->session->set_flashdata('error', 'User could not be deleted. Please try again.');
			redirect('admin/users');
		}

		$this->session->set_flashdata('success', 'User deleted successfully.');
		redirect('admin/users');
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

	private function get_regular_user_or_redirect($id)
	{
		$user = $this->User_model->get_by_id($id);

		if (!$user || (int) $user->role !== 0) {
			$this->session->set_flashdata('error', 'User not found.');
			redirect('admin/users');
		}

		return $user;
	}

	private function set_validation_error_flashdata()
	{
		$error_message = trim(strip_tags(validation_errors(' ', ' ')));
		$this->session->set_flashdata('error', $error_message !== '' ? $error_message : 'Please check the form fields and try again.');
	}

	public function add_users_to_question()
	{
		$admin = $this->get_admin();

		$data = array(
			'title' => 'Add Users',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'active_page' => 'users_add',
			'categories' => $this->Category_model->get_all_categories()
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/add_users_question', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function save_users_to_question()
	{
		$this->get_admin();
		$question_id = (int)$this->input->post('question_id');
		$count = (int)$this->input->post('user_count');

		if ($question_id <= 0 || $count <= 0) {
			$this->session->set_flashdata('error', 'Please select a valid question and enter a user count greater than zero.');
			redirect('admin/users/add_users_to_question');
		}

		$question = $this->Category_model->get_question($question_id);

		if (!$question) {
			$this->session->set_flashdata('error', 'Selected question was not found.');
			redirect('admin/users/add_users_to_question');
		}

		$this->db->set('admin_extra_users', 'COALESCE(admin_extra_users,0) + ' . $count, FALSE);
		$this->db->where('id', $question_id);
		$updated = $this->db->update('category_questions');

		if (!$updated) {
			$this->session->set_flashdata('error', 'Users could not be added right now. Please try again.');
			redirect('admin/users/add_users_to_question');
		}

		$this->session->set_flashdata('success', $count . ' users added successfully to "' . $question->question . '".');

		redirect('admin/users/add_users_to_question');
	}
}


