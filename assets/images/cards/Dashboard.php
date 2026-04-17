<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Dashboard extends CI_Controller
{



    public function __construct()
    {



        parent::__construct();



        $this->load->library('form_validation');

        $this->load->library('session');
        $this->load->library('subscription_guard');
        $this->load->model('Dashboard_model', 'dashboard_model');
        $this->load->model('Plan_model');

        $this->load->model('general_model');
        if (!$this->session->userdata('admin')) {
            redirect(site_url('admin/login'));
        }

        $admin = $this->session->userdata('admin');

        if ((int) ($admin['role'] ?? 0) !== 2) {
            redirect(site_url('super-admin/dashboard'));
        }

        $this->subscription_guard->enforce((int) $admin['id']);


    }



    public function index()
    {
        $store_id = $this->session->userdata('admin')['id'];
        $this->data['upcoming_emi'] = $this->dashboard_model->get_upcoming_emi($store_id);


        $this->data['upcoming_services'] = $this->dashboard_model->get_upcoming_services($store_id);
        $this->data['upcoming_amc_expiry'] = $this->dashboard_model->get_upcoming_amc_expiry($store_id);
        $this->data['latest_complaints'] = $this->dashboard_model->get_latest_complaints($store_id, 3);
        $this->data['customers'] = $this->general_model->getCount('customers', array('store_id' => $store_id));
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');

        $this->data['thismonth_customers'] = $this->general_model->getCount(
            'customers',
            array('store_id' => $store_id),
            null,
            'created_at',
            array('start' => $start_date, 'end' => $end_date)
        );
        $this->data['thismonth_amc'] = $this->general_model->getCount(
            'amc_customer',
            array('store_id' => $store_id),
            null,
            'created_at',
            array('start' => $start_date, 'end' => $end_date)
        );
        $this->data['total_amc'] = $this->general_model->getCount('amc_customer', array('store_id' => $store_id));


        $this->data['order'] = $this->general_model->getCount('orders', array('store_id' => $store_id));
        $this->data['thismonth_orders'] = $this->general_model->getCount(
            'orders',
            array('store_id' => $store_id),
            null,
            'created_at',
            array('start' => $start_date, 'end' => $end_date)
        );



        $this->data['complaints'] = $this->general_model->getCount('complaint', array('store_id' => $store_id, 'status' => '1'));
        $this->data['thismonth_complaints'] = $this->general_model->getCount(
            'complaint',
            array('store_id' => $store_id, 'status' => 1),
            null,
            'created_at',
            array('start' => $start_date, 'end' => $end_date)
        );
        $this->data['thismonth_services'] = $this->general_model->getServiceCountThisMonth($store_id);
        $service_counts = $this->general_model->getServiceStatusCountsThisMonth($store_id);
        $this->data['thismonth_pending_services'] = $service_counts['pending_services'];
        $this->data['thismonth_fulfilled_services'] = $service_counts['fulfilled_services'];
        $emi_counts = $this->general_model->getEmiStatusCountsThisMonth($store_id);
        $this->data['thismonth_emi_customers'] = $emi_counts['emi_customers'];
        $this->data['thismonth_pending_emis'] = $emi_counts['pending_emis'];
        $this->data['thismonth_paid_emis'] = $emi_counts['paid_emis'];
        $current_year = date('Y');

        $salesSummary = $this->dashboard_model->get_sales_summary($store_id);
        $monthlySalesSummary = $this->dashboard_model->get_sales_summary($store_id, date('Y-m-01'), date('Y-m-t'));
        $inventorySummary = $this->dashboard_model->get_inventory_summary($store_id);
        $monthlyInventorySummary = $this->dashboard_model->get_inventory_summary($store_id, date('Y-m-01'), date('Y-m-t'));
        $monthlyMetrics = $this->dashboard_model->get_monthly_sales_metrics($store_id, $current_year);

        $monthly_orders = [];
        $monthly_revenue = [];
        $monthly_profit = [];

        for ($month = 1; $month <= 12; $month++) {
            $yearMonth = $current_year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
            $monthly_orders[$yearMonth] = (int) ($monthlyMetrics[$yearMonth]['orders'] ?? 0);
            $monthly_revenue[$yearMonth] = (float) ($monthlyMetrics[$yearMonth]['sales'] ?? 0);
            $monthly_profit[$yearMonth] = (float) ($monthlyMetrics[$yearMonth]['profit'] ?? 0);
        }

        $this->data['total_sales'] = (float) ($salesSummary['total_sales'] ?? 0);
        $this->data['thismonth_sales'] = (float) ($monthlySalesSummary['total_sales'] ?? 0);
        $this->data['total_profit'] = (float) ($salesSummary['total_profit'] ?? 0);
        $this->data['thismonth_profit'] = (float) ($monthlySalesSummary['total_profit'] ?? 0);
        $this->data['active_products'] = (int) ($inventorySummary['total_products'] ?? 0);
        $this->data['low_stock_items'] = (int) ($inventorySummary['low_stock_items'] ?? 0);
        $this->data['total_purchase'] = (float) ($inventorySummary['total_purchase'] ?? 0);
        $this->data['thismonth_purchase'] = (float) ($monthlyInventorySummary['total_purchase'] ?? 0);
        $this->data['monthly_orders'] = $monthly_orders;
        $this->data['monthly_revenue'] = $monthly_revenue;
        $this->data['monthly_profit'] = $monthly_profit;
        $this->data['plan_summary'] = $this->Plan_model->get_plan_summary($store_id);

        $this->load->view('admin/header');

        $this->load->view('admin/dashboard_view', $this->data);

        $this->load->view('admin/footer', $this->data);

    }

}
