<?php
defined('BASEPATH') or exit('No direct script access allowed');

class History extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->load->model('User_model');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $user    = $this->User_model->get_by_id($user_id);

        $this->db->select('
            ua.*,
            q.question,
            q.category_id,
            q.answer_key,
            c.name as category_name
        ');
        $this->db->from('user_question_answers ua');
        $this->db->join('category_questions q', 'q.id = ua.question_id');
        $this->db->join('categories c', 'c.id = q.category_id');
        $this->db->where('ua.user_id', $user_id);
        $this->db->order_by('ua.id', 'DESC');

        $history = $this->db->get()->result();

        // Compute result + winning_amount on each row
        foreach ($history as $row) {
            $answer     = strtolower($row->answer ?? '');
            $key        = strtolower($row->answer_key ?? '');
            $is_settled = !empty($row->answer_key);

            $row->result = 'pending';
            if ($is_settled && $key !== '') {
                $row->result = ($answer === $key) ? 'win' : 'lose';
            }

            $row->winning_amount = ($row->result === 'win')
                ? (float)($row->payout_amount ?? 0)
                : 0.0;
        }

        $data = [
            'title'       => 'History',
            'page_type'   => 'dashboard',
            'user'        => $user,
            'history'     => $history,
            'active_page' => 'history',
        ];

        $this->load->view('includes/header', $data);
        $this->load->view('history_view', $data);
        $this->load->view('includes/footer', $data);
    }
}
