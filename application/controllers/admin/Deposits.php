<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Deposits extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Check admin authentication
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }

        // Load required models and libraries
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'file']);
    }

    /**
     * Get common data for all views
     */
    private function get_common_data()
    {
        return [
            'admin' => $this->db->get_where('users', [
                'id' => $this->session->userdata('admin_id')
            ])->row(),
            'page_type' => 'dashboard',
        ];
    }

    /**
     * Payment Settings Page
     */
    public function settings()
    {
        try {
            $settings = $this->db->get('payment_settings')->row();

            $data = $this->get_common_data();
            $data['settings'] = $settings ?? (object)[];
            $data['active_page'] = 'deposits_settings';
            $data['title'] = 'Payment Settings';

            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/deposit_settings_view', $data);
            $this->load->view('admin/includes/footer');
        } catch (Exception $e) {
            log_message('error', 'Deposit Settings Error: ' . $e->getMessage());
            show_error('An error occurred while loading payment settings.');
        }
    }

    /**
     * Save Payment Settings
     */
    public function save_settings()
    {
        // Set validation rules
        $this->form_validation->set_rules('upi_id', 'UPI ID', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|max_length[100]');
        $this->form_validation->set_rules('account_number', 'Account Number', 'trim|max_length[50]');
        $this->form_validation->set_rules('ifsc', 'IFSC Code', 'trim|max_length[20]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/deposits/settings');
            return;
        }

        try {
            // Prepare data
            $data = [
                'upi_id' => $this->input->post('upi_id', TRUE),
                'bank_name' => $this->input->post('bank_name', TRUE),
                'account_number' => $this->input->post('account_number', TRUE),
                'ifsc' => strtoupper($this->input->post('ifsc', TRUE)),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Handle QR Code Upload
            if (!empty($_FILES['qr_image']['name'])) {
                $upload_result = $this->upload_qr_image();

                if ($upload_result['status']) {
                    // Delete old QR image if exists
                    $old_settings = $this->db->get('payment_settings')->row();
                    if ($old_settings && !empty($old_settings->qr_image)) {
                        $old_file = './uploads/qr/' . $old_settings->qr_image;
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }
                    $data['qr_image'] = $upload_result['file_name'];
                } else {
                    $this->session->set_flashdata('error', $upload_result['error']);
                    redirect('admin/deposits/settings');
                    return;
                }
            }

            // Update or Insert
            if ($this->db->count_all('payment_settings') > 0) {
                $this->db->update('payment_settings', $data);
                $message = 'Payment settings updated successfully!';
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert('payment_settings', $data);
                $message = 'Payment settings saved successfully!';
            }

            $this->session->set_flashdata('success', $message);
        } catch (Exception $e) {
            log_message('error', 'Save Settings Error: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Failed to save settings. Please try again.');
        }

        redirect('admin/deposits/settings');
    }

    /**
     * Upload QR Image
     */
    private function upload_qr_image()
    {
        // Create upload directory if not exists
        $upload_path = './uploads/qr/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('qr_image')) {
            $upload_data = $this->upload->data();
            return [
                'status' => true,
                'file_name' => $upload_data['file_name']
            ];
        } else {
            return [
                'status' => false,
                'error' => $this->upload->display_errors('', '')
            ];
        }
    }

    /**
     * Deposit Requests List
     */
    public function requests()
    {
        try {
            $status_filter = $this->input->get('status', TRUE);

            $this->db->select('deposit_requests.*, users.name, users.email');
            $this->db->from('deposit_requests');
            $this->db->join('users', 'users.id = deposit_requests.user_id', 'left');

            // Apply status filter if provided
            if ($status_filter && in_array($status_filter, ['pending', 'approved', 'rejected'])) {
                $this->db->where('deposit_requests.status', $status_filter);
            }

            // ✅ KEY FIX: pending first, then approved, then rejected, newest within each group
            $this->db->order_by("FIELD(deposit_requests.status, 'pending', 'approved', 'rejected')");
            $this->db->order_by('deposit_requests.created_at', 'DESC');

            $requests = $this->db->get()->result();

            $stats = $this->get_deposit_statistics();

            $data = $this->get_common_data();
            $data['requests']      = $requests ?? [];
            $data['stats']         = $stats;
            $data['active_page']   = 'deposits_requests';
            $data['title']         = 'Deposit Requests';
            $data['status_filter'] = $status_filter;

            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/deposit_requests_view', $data);
            $this->load->view('admin/includes/footer');
        } catch (Exception $e) {
            log_message('error', 'Deposit Requests Error: ' . $e->getMessage());
            show_error('An error occurred while loading deposit requests.');
        }
    }
    /**
     * Get deposit statistics
     */
    private function get_deposit_statistics()
    {
        $stats = [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'total_amount' => 0,
            'approved_amount' => 0,
        ];

        try {
            $stats['total'] = $this->db->count_all('deposit_requests');

            $stats['pending'] = $this->db->where('status', 'pending')
                ->count_all_results('deposit_requests');

            $stats['approved'] = $this->db->where('status', 'approved')
                ->count_all_results('deposit_requests');

            $stats['rejected'] = $this->db->where('status', 'rejected')
                ->count_all_results('deposit_requests');

            $total_amount = $this->db->select_sum('amount')
                ->get('deposit_requests')->row();
            $stats['total_amount'] = $total_amount->amount ?? 0;

            $approved_amount = $this->db->select_sum('amount')
                ->where('status', 'approved')
                ->get('deposit_requests')->row();
            $stats['approved_amount'] = $approved_amount->amount ?? 0;
        } catch (Exception $e) {
            log_message('error', 'Statistics Error: ' . $e->getMessage());
        }

        return $stats;
    }

    /**
     * Approve Deposit Request
     */
    public function approve($id)
    {
        // Validate ID
        if (!$id || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid request ID.');
            redirect('admin/deposits/requests');
            return;
        }

        try {
            // Start DB transaction
            $this->db->trans_start();

            // Get deposit request
            $req = $this->db->get_where('deposit_requests', ['id' => $id])->row();

            if (!$req) {
                $this->session->set_flashdata('error', 'Deposit request not found.');
                redirect('admin/deposits/requests');
                return;
            }

            // Prevent duplicate approval
            if ($req->status == 'approved') {
                $this->session->set_flashdata('error', 'Already approved.');
                redirect('admin/deposits/requests');
                return;
            }

            if ($req->status != 'pending') {
                $this->session->set_flashdata('error', 'Already processed.');
                redirect('admin/deposits/requests');
                return;
            }

            // ================================
            // 1️⃣ UPDATE USER WALLET (REAL MONEY)
            // ================================
            $this->db->set('wallet_balance', 'wallet_balance + ' . (float)$req->amount, FALSE);
            $this->db->where('id', $req->user_id);
            $this->db->update('users');

            // ================================
            // 2️⃣ INSERT INTO wallet_transactions (FOR UI)
            // ================================
            $this->db->insert('wallet_transactions', [
                'user_id'     => $req->user_id,
                'source_type' => 'deposit',
                'source_id'   => $req->id,
                'type'        => 'credit',
                'amount'      => $req->amount,
                'description' => 'Wallet deposit approved',
                'created_at'  => date('Y-m-d H:i:s')
            ]);

            // ================================
            // 3️⃣ INSERT INTO transaction_logs (FOR ADMIN / LOG)
            // ================================
            $this->db->insert('transaction_logs', [
                'user_id'      => $req->user_id,
                'type'         => 'deposit_approved',
                'amount'       => $req->amount,
                'source_type'  => 'deposit',
                'reference_id' => $req->id,
                'admin_id'     => $this->session->userdata('admin_id'),
                'created_at'   => date('Y-m-d H:i:s')
            ]);

            // ================================
            // 4️⃣ UPDATE REQUEST STATUS
            // ================================
            $this->db->update('deposit_requests', [
                'status'      => 'approved',
                'approved_at' => date('Y-m-d H:i:s'),
                'approved_by' => $this->session->userdata('admin_id')
            ], ['id' => $id]);

            // ================================
            // 5️⃣ ADD USER NOTIFICATION
            // ================================
            $this->User_model->add_notification([
                'user_id'    => (int) $req->user_id,
                'title'      => 'Deposit approved',
                'message'    => 'Your deposit of ₹' . number_format((float) $req->amount, 2) . ' has been approved and added to your wallet.',
                'type'       => 'wallet',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // ================================
            // 6️⃣ UPDATE SESSION (IF SAME USER)
            // ================================
            $updatedUser = $this->db->get_where('users', ['id' => $req->user_id])->row();
            // Update session if the approved user is currently logged in
            if ($this->session->userdata('user_id') == $req->user_id) {
                $this->session->set_userdata('wallet_balance', $updatedUser->wallet_balance);
            }

            // Complete transaction
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }

            // Success message
            $this->session->set_flashdata(
                'success',
                'Deposit approved! ₹' . number_format($req->amount, 2) . ' added to wallet.'
            );
        } catch (Exception $e) {
            log_message('error', 'Approve Deposit Error: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Something went wrong!');
        }

        redirect('admin/deposits/requests');
    }

    /**
     * Reject Deposit Request
     */
    public function reject($id)
    {
        if (!$id || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid request ID.');
            redirect('admin/deposits/requests');
            return;
        }

        try {
            $req = $this->db->get_where('deposit_requests', ['id' => $id])->row();

            if (!$req) {
                $this->session->set_flashdata('error', 'Deposit request not found.');
                redirect('admin/deposits/requests');
                return;
            }

            if ($req->status != 'pending') {
                $this->session->set_flashdata('error', 'This request has already been processed.');
                redirect('admin/deposits/requests');
                return;
            }

            $this->db->update('deposit_requests', [
                'status' => 'rejected',
                'rejected_at' => date('Y-m-d H:i:s'),
                'rejected_by' => $this->session->userdata('admin_id')
            ], ['id' => $id]);

            // Log the transaction
            $this->log_transaction($req->user_id, 'deposit_rejected', $req->amount, $id);

            $this->session->set_flashdata('success', 'Deposit request rejected.');

            // Optional: Send notification to user
            // $this->send_rejection_notification($req->user_id, $req->amount);

        } catch (Exception $e) {
            log_message('error', 'Reject Deposit Error: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Failed to reject deposit request. Please try again.');
        }

        redirect('admin/deposits/requests');
    }

    /**
     * View Deposit Request Details
     */
    public function view($id)
    {
        if (!$id || !is_numeric($id)) {
            show_404();
            return;
        }

        try {
            $this->db->select('deposit_requests.*, users.name, users.email, users.phone');
            $this->db->from('deposit_requests');
            $this->db->join('users', 'users.id = deposit_requests.user_id', 'left');
            $this->db->where('deposit_requests.id', $id);
            $request = $this->db->get()->row();

            if (!$request) {
                show_404();
                return;
            }

            $data = $this->get_common_data();
            $data['request'] = $request;
            $data['active_page'] = 'deposits_requests';
            $data['title'] = 'Deposit Request Details';

            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/deposit_detail_view', $data);
            $this->load->view('admin/includes/footer');
        } catch (Exception $e) {
            log_message('error', 'View Deposit Error: ' . $e->getMessage());
            show_error('An error occurred while loading deposit details.');
        }
    }

    /**
     * Log transaction activity
     */
    private function log_transaction($user_id, $type, $amount, $reference_id)
    {
        $this->db->insert('transaction_logs', [
            'user_id'      => $user_id,
            'type'         => $type, // FIXED ✅
            'amount'       => $amount,
            'source_type'  => 'deposit',
            'reference_id' => $reference_id,
            'admin_id'     => $this->session->userdata('admin_id'),
            'created_at'   => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Delete QR Code Image
     */
    public function delete_qr()
    {
        try {
            $settings = $this->db->get('payment_settings')->row();

            if ($settings && !empty($settings->qr_image)) {
                $file_path = './uploads/qr/' . $settings->qr_image;

                if (file_exists($file_path)) {
                    unlink($file_path);
                }

                $this->db->update('payment_settings', ['qr_image' => NULL]);
                $this->session->set_flashdata('success', 'QR code deleted successfully!');
            } else {
                $this->session->set_flashdata('error', 'No QR code found to delete.');
            }
        } catch (Exception $e) {
            log_message('error', 'Delete QR Error: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Failed to delete QR code.');
        }

        redirect('admin/deposits/settings');
    }
}
