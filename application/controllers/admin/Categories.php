<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Categories extends CI_Controller
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
		$admin = $this->get_admin();
		$edit_category_id = (int) $this->input->get('edit_category');

		$data = array(
			'title' => 'Categories',
			'page_type' => 'dashboard',
			'admin' => $admin,
			'active_page' => 'categories',
			'categories' => $this->Category_model->get_all_categories_with_questions(),
			'edit_category' => $edit_category_id > 0 ? $this->Category_model->get_category($edit_category_id) : NULL,
			'total_categories' => $this->Category_model->count_all_categories(),
			'total_questions' => $this->Category_model->count_all_questions()
		);

		$this->load->view('admin/includes/header', $data);
		$this->load->view('admin/categories_view', $data);
		$this->load->view('admin/includes/footer', $data);
	}

	public function create()
	{
		$this->get_admin();

		$this->form_validation->set_rules('name', 'Category name', 'required|trim|min_length[2]|max_length[120]');

		if ($this->form_validation->run() === FALSE) {
			$this->set_validation_error_flashdata();
			redirect('admin/categories');
		}

		$name = $this->input->post('name', TRUE);

		if ($this->Category_model->category_name_exists($name)) {
			$this->session->set_flashdata('error', 'This category name already exists.');
			redirect('admin/categories');
		}

		$created = $this->Category_model->create_category(array(
			'name' => $name,
			// 'description' => NULL
		));

		if (!$created) {
			$this->session->set_flashdata('error', 'Database tables for categories are missing. Please run the SQL query first.');
			redirect('admin/categories');
		}

		$this->session->set_flashdata('success', 'Category added successfully.');
		redirect('admin/categories');
	}

	public function update($id)
	{
		$this->get_admin();
		$id = (int) $id;
		$category = $this->Category_model->get_category($id);

		if (!$category) {
			$this->session->set_flashdata('error', 'Category not found.');
			redirect('admin/categories');
		}

		$this->form_validation->set_rules('name', 'Category name', 'required|trim|min_length[2]|max_length[120]');

		if ($this->form_validation->run() === FALSE) {
			$this->set_validation_error_flashdata();
			redirect('admin/categories?edit_category=' . $id);
		}

		$name = $this->input->post('name', TRUE);

		if ($this->Category_model->category_name_exists($name, $id)) {
			$this->session->set_flashdata('error', 'This category name already exists.');
			redirect('admin/categories?edit_category=' . $id);
		}

		$updated = $this->Category_model->update_category($id, array(
			'name' => $name,
			// 'description' => NULL
		));

		if (!$updated) {
			$this->session->set_flashdata('error', 'Category could not be updated. Please confirm the category table exists.');
			redirect('admin/categories?edit_category=' . $id);
		}

		$this->session->set_flashdata('success', 'Category updated successfully.');
		redirect('admin/categories');
	}

	public function delete($id)
	{
		$this->get_admin();
		$id = (int) $id;
		$category = $this->Category_model->get_category($id);

		if (!$category) {
			$this->session->set_flashdata('error', 'Category not found.');
			redirect('admin/categories');
		}

		if (!$this->Category_model->delete_category($id)) {
			$this->session->set_flashdata('error', 'Category could not be deleted. Please confirm the category table exists.');
			redirect('admin/categories');
		}

		$this->session->set_flashdata('success', 'Category deleted successfully.');
		redirect('admin/categories');
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

	private function set_validation_error_flashdata()
	{
		$error_message = trim(strip_tags(validation_errors(' ', ' ')));
		$this->session->set_flashdata('error', $error_message !== '' ? $error_message : 'Please check the form fields and try again.');
	}
}
