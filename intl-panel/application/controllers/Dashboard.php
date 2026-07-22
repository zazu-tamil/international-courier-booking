<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        $this->load->model('Shipment_model');
        $this->load->model('Customer_model');
        $this->load->model('Master_model');
    }

    public function index() {
        $role_id = $this->session->userdata('role_id');
        $data['page_title'] = 'Dashboard';

        if ($role_id == 4) {
            // Customer Dashboard
            $customer_id = $this->session->userdata('customer_id');
            $data['customer'] = $this->Customer_model->get_customers($customer_id);
            $data['wallet_balance'] = $this->Customer_model->get_wallet_balance($customer_id);
            
            // Counts
            $this->db->where('customer_id', $customer_id);
            $this->db->where('deleted_at IS NULL');
            $data['total_shipments'] = $this->db->count_all_results('shipment_master');

            $this->db->where('customer_id', $customer_id);
            $this->db->where('verification_status', 'Pending');
            $this->db->where('deleted_at IS NULL');
            $data['pending_verifications'] = $this->db->count_all_results('shipment_master');

            // Recent shipments
            $this->db->select('shipment_master.*, countries.country_name as destination_country');
            $this->db->from('shipment_master');
            $this->db->join('countries', 'countries.id = shipment_master.destination_country_id');
            $this->db->where('shipment_master.customer_id', $customer_id);
            $this->db->where('shipment_master.deleted_at IS NULL');
            $this->db->order_by('shipment_master.id', 'DESC');
            $this->db->limit(5);
            $data['recent_shipments'] = $this->db->get()->result();

            // Recent wallet transactions
            $this->db->limit(5);
            $this->db->order_by('id', 'DESC');
            $data['wallet_transactions'] = $this->db->get_where('customer_wallet_transactions', array('customer_id' => $customer_id))->result();

            $data['view_path'] = 'dashboard/customer';
        } else {
            // Admin/Staff Dashboard
            // Today's Bookings
            if ($role_id == 3) {
                $this->db->where('created_by', $this->session->userdata('user_id'));
            } elseif ($role_id == 2) {
                $this->db->where('branch_id', $this->session->userdata('branch_id'));
            }
            $this->db->where('booking_date', date('Y-m-d'));
            $this->db->where('deleted_at IS NULL');
            $data['today_bookings'] = $this->db->count_all_results('shipment_master');

            // Today's Revenue
            $this->db->select_sum('estimated_charges');
            if ($role_id == 3) {
                $this->db->where('created_by', $this->session->userdata('user_id'));
            } elseif ($role_id == 2) {
                $this->db->where('branch_id', $this->session->userdata('branch_id'));
            }
            $this->db->where('booking_date', date('Y-m-d'));
            $this->db->where('deleted_at IS NULL');
            $rev_q = $this->db->get('shipment_master')->row();
            $data['today_revenue'] = $rev_q ? (float)$rev_q->estimated_charges : 0.00;

            // Pending Pickups
            if ($role_id == 3) {
                $this->db->where("shipment_id IN (SELECT id FROM shipment_master WHERE deleted_at IS NULL AND created_by = " . intval($this->session->userdata('user_id')) . ")");
            } elseif ($role_id == 2) {
                $this->db->where("shipment_id IN (SELECT id FROM shipment_master WHERE deleted_at IS NULL AND branch_id = " . intval($this->session->userdata('branch_id')) . ")");
            } else {
                $this->db->where("shipment_id IN (SELECT id FROM shipment_master WHERE deleted_at IS NULL)");
            }
            $this->db->where('status', 'Requested');
            $data['pending_pickups'] = $this->db->count_all_results('pickup_requests');

            // Pending Customer Verification
            if ($role_id == 3) {
                $this->db->where('created_by', $this->session->userdata('user_id'));
            } elseif ($role_id == 2) {
                $this->db->where('branch_id', $this->session->userdata('branch_id'));
            }
            $this->db->where('verification_status', 'Pending');
            $this->db->where('deleted_at IS NULL');
            $data['pending_verification'] = $this->db->count_all_results('shipment_master');

            // Detailed verification status counts
            if ($role_id == 3) {
                $this->db->where('created_by', $this->session->userdata('user_id'));
            } elseif ($role_id == 2) {
                $this->db->where('branch_id', $this->session->userdata('branch_id'));
            }
            $this->db->where('declaration_status', 'Pending')->where('deleted_at IS NULL');
            $data['pending_declaration'] = $this->db->count_all_results('shipment_master');

            if ($role_id == 3) {
                $this->db->where('created_by', $this->session->userdata('user_id'));
            } elseif ($role_id == 2) {
                $this->db->where('branch_id', $this->session->userdata('branch_id'));
            }
            $this->db->where('terms_status', 'Pending')->where('deleted_at IS NULL');
            $data['pending_terms'] = $this->db->count_all_results('shipment_master');

            if ($role_id == 3) {
                $this->db->where('created_by', $this->session->userdata('user_id'));
            } elseif ($role_id == 2) {
                $this->db->where('branch_id', $this->session->userdata('branch_id'));
            }
            $this->db->where('otp_verification_status', 'Pending')->where('deleted_at IS NULL');
            $data['pending_otp'] = $this->db->count_all_results('shipment_master');

            // Ready For Dispatch
            if ($role_id == 3) {
                $this->db->where('created_by', $this->session->userdata('user_id'));
            } elseif ($role_id == 2) {
                $this->db->where('branch_id', $this->session->userdata('branch_id'));
            }
            $this->db->where('status', 'Ready For Dispatch')->where('deleted_at IS NULL');
            $data['ready_for_dispatch'] = $this->db->count_all_results('shipment_master');

            // In Transit
            if ($role_id == 3) {
                $this->db->where('created_by', $this->session->userdata('user_id'));
            } elseif ($role_id == 2) {
                $this->db->where('branch_id', $this->session->userdata('branch_id'));
            }
            $this->db->where_not_in('status', array('Booking Created', 'Verification Pending', 'Ready For Dispatch', 'Delivered', 'Returned', 'Cancelled'));
            $this->db->where('deleted_at IS NULL');
            $data['in_transit'] = $this->db->count_all_results('shipment_master');

            // Delivered
            if ($role_id == 3) {
                $this->db->where('created_by', $this->session->userdata('user_id'));
            } elseif ($role_id == 2) {
                $this->db->where('branch_id', $this->session->userdata('branch_id'));
            }
            $this->db->where('status', 'Delivered')->where('deleted_at IS NULL');
            $data['delivered'] = $this->db->count_all_results('shipment_master');

            // Active Customers
            if ($role_id == 3) {
                $this->db->where("id IN (SELECT DISTINCT customer_id FROM shipment_master WHERE deleted_at IS NULL AND created_by = " . intval($this->session->userdata('user_id')) . ")");
            } elseif ($role_id == 2) {
                $this->db->where("id IN (SELECT DISTINCT customer_id FROM shipment_master WHERE deleted_at IS NULL AND branch_id = " . intval($this->session->userdata('branch_id')) . ")");
            }
            $this->db->where('status', 'Active')->where('deleted_at IS NULL');
            $data['active_customers'] = $this->db->count_all_results('customers');

            // Active Franchises
            $this->db->where('status', 'Active')->where('deleted_at IS NULL');
            if ($role_id == 3 || $role_id == 2) {
                $data['active_franchises'] = 0;
            } else {
                $data['active_franchises'] = $this->db->count_all_results('franchises');
            }

            // Pending KYC & Total bookings
            if ($role_id == 3 || $role_id == 2) {
                if ($role_id == 3) {
                    $this->db->where('created_by', $this->session->userdata('user_id'));
                } else {
                    $this->db->where('branch_id', $this->session->userdata('branch_id'));
                }
                $this->db->where('deleted_at IS NULL');
                $data['total_bookings'] = $this->db->count_all_results('shipment_master');
                $data['pending_kyc'] = 0;
            } else {
                $this->db->where('status', 'pending');
                $this->db->where("customer_id IN (SELECT id FROM customers WHERE deleted_at IS NULL)");
                $data['pending_kyc'] = $this->db->count_all_results('customer_kyc');
                
                $this->db->where('deleted_at IS NULL');
                $data['total_bookings'] = $this->db->count_all_results('shipment_master');
            }

            // Delayed / Idle Shipments
            $idle_sql = "
                SELECT 
                    SUM(CASE WHEN idle_days <= 1 THEN 1 ELSE 0 END) as idle_green,
                    SUM(CASE WHEN idle_days >= 2 AND idle_days <= 3 THEN 1 ELSE 0 END) as idle_yellow,
                    SUM(CASE WHEN idle_days > 3 THEN 1 ELSE 0 END) as idle_red
                FROM (
                    SELECT DATEDIFF(CURRENT_DATE(), DATE(COALESCE(st.date_time, sm.booking_date))) as idle_days
                    FROM shipment_master sm
                    LEFT JOIN (
                        SELECT t1.shipment_id, t1.date_time
                        FROM shipment_tracking t1
                        INNER JOIN (
                            SELECT shipment_id, MAX(date_time) as max_date
                            FROM shipment_tracking
                            GROUP BY shipment_id
                        ) t2 ON t1.shipment_id = t2.shipment_id AND t1.date_time = t2.max_date
                        GROUP BY t1.shipment_id
                    ) st ON sm.id = st.shipment_id
                    WHERE sm.status NOT IN ('Delivered', 'Cancelled')
                    AND sm.deleted_at IS NULL
            ";

            if ($role_id == 3) {
                $idle_sql .= " AND sm.created_by = " . intval($this->session->userdata('user_id'));
            } elseif ($role_id == 2) {
                $idle_sql .= " AND sm.branch_id = " . intval($this->session->userdata('branch_id'));
            }

            $idle_sql .= " ) as counts";

            $idle_query = $this->db->query($idle_sql)->row();
            $data['idle_green'] = $idle_query ? (int)$idle_query->idle_green : 0;
            $data['idle_yellow'] = $idle_query ? (int)$idle_query->idle_yellow : 0;
            $data['idle_red'] = $idle_query ? (int)$idle_query->idle_red : 0;

            $data['view_path'] = 'dashboard/admin';

            // CHARTS COMPILING

            // 1. Monthly Revenue (last 6 months)
            $months = array();
            $revenues = array();
            for ($i = 5; $i >= 0; $i--) {
                $m = date('Y-m', strtotime("-$i months"));
                $months[] = date('M Y', strtotime("-$i months"));
                
                $this->db->select_sum('estimated_charges');
                $this->db->like('booking_date', $m, 'after');
                if ($role_id == 3) {
                    $this->db->where('created_by', $this->session->userdata('user_id'));
                } elseif ($role_id == 2) {
                    $this->db->where('branch_id', $this->session->userdata('branch_id'));
                }
                $this->db->where('deleted_at IS NULL');
                $q = $this->db->get('shipment_master')->row();
                $revenues[] = $q ? (float)$q->estimated_charges : 0.00;
            }
            $data['chart_months'] = json_encode($months);
            $data['chart_revenues'] = json_encode($revenues);

            // 2. Country Wise Shipments (group destination country)
            $this->db->select('countries.country_name, COUNT(shipment_master.id) as count');
            $this->db->from('shipment_master');
            $this->db->join('countries', 'countries.id = shipment_master.destination_country_id');
            if ($role_id == 3) {
                $this->db->where('shipment_master.created_by', $this->session->userdata('user_id'));
            } elseif ($role_id == 2) {
                $this->db->where('shipment_master.branch_id', $this->session->userdata('branch_id'));
            }
            $this->db->where('shipment_master.deleted_at IS NULL');
            $this->db->group_by('shipment_master.destination_country_id');
            $this->db->limit(5);
            $countries_q = $this->db->get()->result();
            
            $country_labels = array();
            $country_counts = array();
            foreach ($countries_q as $row) {
                $country_labels[] = $row->country_name;
                $country_counts[] = (int)$row->count;
            }
            $data['chart_countries'] = json_encode($country_labels);
            $data['chart_country_counts'] = json_encode($country_counts);

            // 3. Courier Partner Performance
            $this->db->select('courier_partners.partner_name, COUNT(shipment_master.id) as count');
            $this->db->from('shipment_master');
            $this->db->join('courier_partners', 'courier_partners.id = shipment_master.courier_partner_id');
            if ($role_id == 3) {
                $this->db->where('shipment_master.created_by', $this->session->userdata('user_id'));
            } elseif ($role_id == 2) {
                $this->db->where('shipment_master.branch_id', $this->session->userdata('branch_id'));
            }
            $this->db->where('shipment_master.deleted_at IS NULL');
            $this->db->group_by('shipment_master.courier_partner_id');
            $couriers_q = $this->db->get()->result();

            $courier_labels = array();
            $courier_counts = array();
            foreach ($couriers_q as $row) {
                $courier_labels[] = $row->partner_name;
                $courier_counts[] = (int)$row->count;
            }
            $data['chart_couriers'] = json_encode($courier_labels);
            $data['chart_courier_counts'] = json_encode($courier_counts);

            // 4. Delivery Success Rate (Delivered vs Exception / Out for Delivery / etc.)
            if ($role_id == 3) {
                $this->db->where('created_by', $this->session->userdata('user_id'));
            } elseif ($role_id == 2) {
                $this->db->where('branch_id', $this->session->userdata('branch_id'));
            }
            $this->db->where('deleted_at IS NULL');
            $total_all = $this->db->count_all_results('shipment_master');
            $success_rate = $total_all > 0 ? round(($data['delivered'] / $total_all) * 100, 1) : 100;
            $data['delivery_success_rate'] = $success_rate;

            $data['view_path'] = 'dashboard/admin';
        }
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function test_mail()
    {
        $this->load->library('email');

        $config['protocol'] = 'smtp'; 
        $config['smtp_host'] = 'smtp.hostinger.com';
        $config['smtp_user'] = 'noreply@couriersyndicate.co.in'; 
        $config['smtp_pass'] = '7/FjSy^rhU';
        $config['smtp_port'] = 465;
        $config['smtp_crypto'] = 'ssl';
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['crlf'] = "\r\n";
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);
        $this->email->from('noreply@couriersyndicate.co.in', 'Courier Syndicate'); 
        $this->email->to('selvanramesh@gmail.com');
        $this->email->reply_to("noreply@couriersyndicate.co.in", "Admin - Courier Syndicate");
        $this->email->subject('Test Email - Hostinger SMTP');
        $this->email->message('Hello World! This test mail using Hostinger SMTP');

        if ($this->email->send()) {
            echo 'Email sent successfully';
        } else {
            echo 'Email sending failed';
            echo $this->email->print_debugger();
        }
    }     
    public function ajax_delayed_shipments() {
        //if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        $type = $this->input->post('type');
        $role_id = $this->session->userdata('role_id');

        $sql = "
            SELECT sm.id as shipment_id, sm.awb_number, dest.country_name as destination_country, 
                   c.name as customer_name, sm.status as master_status,
                   st.status as tracking_status, st.date_time as last_update_date,
                   DATEDIFF(CURRENT_DATE(), DATE(COALESCE(st.date_time, sm.booking_date))) as idle_days
            FROM shipment_master sm
            LEFT JOIN customers c ON sm.customer_id = c.id
            LEFT JOIN countries dest ON sm.destination_country_id = dest.id
            LEFT JOIN (
                SELECT t1.shipment_id, t1.date_time, t1.status
                FROM shipment_tracking t1
                INNER JOIN (
                    SELECT shipment_id, MAX(date_time) as max_date
                    FROM shipment_tracking
                    GROUP BY shipment_id
                ) t2 ON t1.shipment_id = t2.shipment_id AND t1.date_time = t2.max_date
                GROUP BY t1.shipment_id
            ) st ON sm.id = st.shipment_id
            WHERE sm.status NOT IN ('Delivered', 'Cancelled')
            AND sm.deleted_at IS NULL
        ";

        if ($role_id == 3) {
            $sql .= " AND sm.created_by = " . intval($this->session->userdata('user_id'));
        } elseif ($role_id == 2) {
            $sql .= " AND sm.branch_id = " . intval($this->session->userdata('branch_id'));
        }

        if ($type == 'green') {
            $sql .= " HAVING idle_days <= 1 ";
        } elseif ($type == 'yellow') {
            $sql .= " HAVING idle_days >= 2 AND idle_days <= 3 ";
        } elseif ($type == 'red') {
            $sql .= " HAVING idle_days > 3 ";
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid type']);
            return;
        }
        
        $sql .= " ORDER BY idle_days DESC, sm.booking_date DESC LIMIT 50";

        $query = $this->db->query($sql);
        $result = $query->result_array();

        echo json_encode(['status' => 'success', 'data' => $result]);
    }
}
