<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

 
	public function index()
	{
		 
	}
    
    public function attendance_report()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        if($this->session->userdata('cr_tracking_upd_rights') != 1) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'reports.inc'; 
        $data['submit_flg'] = false;
        
        if(isset($_POST['srch_from_date'])) {
            $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
            $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date'); 
        } else {
            $data['srch_from_date'] = date('Y-m-01');
            $data['srch_to_date'] = date('Y-m-d'); 
        }
        
        if(isset($_POST['srch_user_id'])) {
            $data['srch_user_id'] = $srch_user_id = $this->input->post('srch_user_id');  
        }  else {
            $data['srch_user_id'] = $srch_user_id = '';  
        }
       
       if(!empty($srch_from_date) && !empty($srch_to_date) ){
        
        $where = " DATE_FORMAT(a.in_time,'%Y-%m-%d') between '" . $srch_from_date . "' and  '". $srch_to_date ."'";  
        $data['submit_flg'] = true;
         
       } 
       
       if(!empty($srch_user_id) ){ 
        $where .= " and a.user_id = '". $srch_user_id ."' "; 
        $data['submit_flg'] = true;
         
       }     
         
       
            $sql = "
                select 
                a.user_id,
                b.first_name as u_name ,
                a.franchise_id,
                ifnull(d.franchise_type_name,'Admin') as f_type
                from crit_attendance_log_info as a  
                left join crit_user_info as b on b.user_id = a.user_id
                left join crit_franchise_info as c on c.franchise_id = a.franchise_id
                left join crit_franchise_type_info as d on d.franchise_type_id = c.franchise_type_id
                where a.`status` != 'Delete'
                group by a.user_id
                order by b.first_name asc           
        ";  
        
        $query = $this->db->query($sql);
        
         $data['user_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['user_opt'][$row['f_type']][$row['user_id']] = $row['u_name'];     
        }
        
        if($data['submit_flg']) {
        
        
        
        $sql = "
                select 
                a.user_id,
                b.first_name as u_name ,
                a.in_time,
                a.out_time,
                TIMEDIFF(a.out_time,a.in_time) as tot_hr,
                a.`status`
                from crit_attendance_log_info as a  
                left join crit_user_info as b on b.user_id = a.user_id
                where a.`status` != 'Delete'
                and $where
                order by b.first_name , a.in_time  asc                          
                          
        "; 
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][$row['u_name']][] = $row;     
        } 
        
        }
        
        $this->load->view('page/reports/attendance_report',$data); 
	}  
    
    public function customer_wise_booking_report()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'reports.inc'; 
        $data['submit_flg'] = false;
        
        if(isset($_POST['srch_from_date'])) {
           $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date'); 
           $data['srch_customer_id'] = $srch_customer_id = $this->input->post('srch_customer_id'); 
           $this->session->set_userdata('srch_from_date', $this->input->post('srch_from_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date'));
           $this->session->set_userdata('srch_customer_id', $this->input->post('srch_customer_id'));
       }
       elseif($this->session->userdata('srch_from_date')){
           $data['srch_from_date'] = $srch_from_date = $this->session->userdata('srch_from_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ;
           $data['srch_customer_id'] = $srch_customer_id = $this->session->userdata('srch_customer_id') ;
       } else {
        $data['srch_from_date'] = date('Y-m-d');
        $data['srch_to_date'] = date('Y-m-d');
        $data['srch_customer_id'] = '';
       }
       
       if(!empty($srch_from_date) && !empty($srch_to_date) && !empty($srch_customer_id) ){
        
        $where = " a.booking_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'";
        $where .= " and a.consignor_id = '". $srch_customer_id ."' ";
        
        $data['submit_flg'] = true;
         
       }    
         
       if($this->session->userdata('cr_is_admin') == '11'){
             
            $sql = "
                select 
                a.customer_id,                
                a.company ,
                a.contact_person,
                a.customer_code            
                from crit_customer_info as a  
                where status = 'Active' and a.franchise_id = '". $this->session->userdata('cr_franchise_id') ."'
                order by a.company , a.contact_person asc                 
            "; 
                    
        } else {
            $sql = "
                select 
                a.customer_id,                
                a.company ,
                a.contact_person,
                a.customer_code            
                from crit_customer_info as a  
                where status = 'Active' 
                order by a.company , a.contact_person asc                 
        "; 
        }  
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['customer_opt'][$row['customer_id']] = $row['customer_code'] . ':' . $row['company']. ' - ' . $row['contact_person']  ;     
        }
        
        if($data['submit_flg']) {
        
        $this->load->library('pagination'); 
        
        $this->db->where('a.status != ', 'Delete'); 
        $this->db->where($where); 
        $this->db->from('crit_booking_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        /* 	
        $config['base_url'] = trim(site_url('customer-booking-report/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   */
        
        $sql = "
                select  
                a.awbno,
                a.booking_date,
                a.booking_time,
                a.origin_pincode,
                a.origin_state_code,
                a.origin_city_code,
                a.dest_pincode,
                a.dest_state_code,
                a.dest_city_code,
                a.customer_ref_no,
                a.no_of_pieces,
                a.chargable_weight,
                a.grand_total,
                a.`status`
                from crit_booking_info as a
                where a.`status` != 'Delete' and
                $where      
                order by a.booking_date asc , a.booking_time asc                           
                          
        ";
        
        // limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."     
        
        $query = $this->db->query($sql);
        
        //$data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
       // $data['pagination'] = $this->pagination->create_links();
        }
        
        $this->load->view('page/reports/customer_wise_booking_report',$data); 
	}  
    
    public function franchise_wise_booking_report()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'reports.inc'; 
        $data['submit_flg'] = false;
        
       /*if(isset($_POST['srch_from_date'])) {
           $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date'); 
           $data['srch_franchise_id'] = $srch_franchise_id = $this->input->post('srch_franchise_id'); 
           $this->session->set_userdata('srch_from_date', $this->input->post('srch_from_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date'));
           $this->session->set_userdata('srch_franchise_id', $this->input->post('srch_franchise_id'));
       }
       elseif($this->session->userdata('srch_from_date')){
           $data['srch_from_date'] = $srch_from_date = $this->session->userdata('srch_from_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ;
           $data['srch_franchise_id'] = $srch_franchise_id = $this->session->userdata('srch_franchise_id') ;
       } else {
        $data['srch_from_date'] = date('Y-m-d');
        $data['srch_to_date'] = date('Y-m-d');
        $data['srch_franchise_id'] = $this->session->userdata('cr_franchise_id');
       } */
       
       if(isset($_POST['srch_from_date'])) {
            $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
            $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date'); 
       } else {
            $data['srch_from_date'] = $srch_from_date =  date('Y-m-d');
            $data['srch_to_date'] = $srch_to_date = date('Y-m-d'); 
       }
       if(isset($_POST['srch_franchise_id'])) {
            $data['srch_franchise_id'] = $srch_franchise_id = $this->input->post('srch_franchise_id');  
       } else {
            if($this->session->userdata('cr_is_admin') == '1' or $this->session->userdata('cr_is_admin') == '111')            
                $data['srch_franchise_id'] = '';
            else  
                $data['srch_franchise_id'] = $srch_franchise_id =  $this->session->userdata('cr_franchise_id'); 
       }
       
       if(isset($_POST['srch_pay_mode'])) {
            $data['srch_pay_mode'] = $srch_pay_mode = $this->input->post('srch_pay_mode');  
       } else { 
            $data['srch_pay_mode'] = $srch_pay_mode = $this->session->userdata('srch_pay_mode'); 
       }
       
       if(isset($_POST['srch_awb_no'])) {
            $data['srch_awb_no'] = $srch_awb_no = $this->input->post('srch_awb_no');  
       } else { 
            $data['srch_awb_no'] = $srch_awb_no = $this->session->userdata('srch_awb_no'); 
       }
       
       if($this->session->userdata('cr_is_admin') == '11'){
        $data['srch_franchise_id'] = $srch_franchise_id =  $this->session->userdata('cr_franchise_id');         
       }
       
       $where = " 1=1 ";
       
       if(!empty($srch_from_date) && !empty($srch_to_date) && empty($srch_awb_no) ){
        $where .= " and a.booking_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'"; 
        $data['submit_flg'] = true; 
       }  
       
       if(!empty($srch_franchise_id) && empty($srch_awb_no) ){
         //$where .= " and a.created_by in ( select a.user_id from crit_user_info as a  where a.franchise_id =  '". $srch_franchise_id ."') ";
         $where .= "and b.franchise_id = '". $srch_franchise_id ."'";   
         $data['submit_flg'] = true; 
       }  
       
       if(!empty($srch_pay_mode) && empty($srch_awb_no)){
         $where .= "and a.payment_mode = '". $srch_pay_mode ."'";   
         $data['submit_flg'] = true; 
       } 
       
       if(!empty($srch_awb_no)){
         $where .= "and a.awbno = '". $srch_awb_no ."'";   
         $data['submit_flg'] = true; 
       } 
       
       
       if($this->session->userdata('cr_is_admin') == '111')  {
        $where .= " and a.origin_state_code = '". $this->session->userdata('cr_pstate') ."'";  
       }
       
       
       
        
        if($this->session->userdata('cr_is_admin') == '1') { 
            $sql = "
                    select 
                    c.franchise_type_name,
                    a.franchise_id,
                    a.contact_person,
                    a.state_code,
                    a.city_code,
                    b.city_name as city
                    from crit_franchise_info as a
                    left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                    left join crit_city_info as b on b.city_code = a.city_code
                    where a.`status` = 'Active'   
                    order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        } elseif($this->session->userdata('cr_is_admin') == '111')  {
            $sql = "
                    select 
                    c.franchise_type_name,
                    a.franchise_id,
                    a.contact_person,
                    a.state_code,
                    a.city_code,
                    b.city_name as city
                    from crit_franchise_info as a
                    left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                    left join crit_city_info as b on b.city_code = a.city_code
                    where a.`status` = 'Active' and a.state_code = '". $this->session->userdata('cr_pstate') ."'   
                    order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        } else {
            $sql = "
                    select 
                    c.franchise_type_name,
                    a.franchise_id,
                    a.contact_person,
                    a.state_code,
                    a.city_code,
                    b.city_name as city
                    from crit_franchise_info as a
                    left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                    left join crit_city_info as b on b.city_code = a.city_code
                    where a.`status` = 'Active' and a.franchise_id = '". $this->session->userdata('cr_franchise_id') ."'   
                    order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        }
        
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['franchise_opt'][$row['franchise_type_name']][$row['franchise_id']] =  $row['state_code'] . ' - ' . $row['city'] .' [ ' . $row['contact_person']. ' ]';     
        }
        
        if($data['submit_flg']) {
        
        /*$this->load->library('pagination'); 
        
        $this->db->where('a.status != ', 'Delete'); 
        $this->db->where($where); 
        $this->db->from('crit_booking_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('franchise-booking-report'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   */
        
        $this->db->query("SET SQL_BIG_SELECTS=1");
        
        $sql = "
                select  
                a.booking_id,
                d.hub_branch_name as branch,
                c.contact_person as franchise,
                a.awbno,
                a.booking_date,
                a.booking_time,
                a.origin_pincode,
                a.origin_state_code,
                a.origin_city_code,
                e.city_name as origin_city,
                a.dest_pincode,
                a.dest_state_code,
                a.dest_city_code,
                f.city_name as dest_city,
                a.customer_ref_no,
                a.no_of_pieces,
                a.chargable_weight,
                a.grand_total,
                a.`status`,
                a.sender_company,
                a.sender_name,
                a.sender_mobile,
                a.sender_address,
                a.receiver_company,
                a.receiver_name,
                a.receiver_mobile,
                a.receiver_address,
                a.payment_mode,
                a.cod,
                a.to_pay as FOD,
                a.cod_amount,
                a.cl_no,
                g.co_loader_name
                from crit_booking_info as a
                left join crit_user_info as b on b.user_id = a.created_by
                left join crit_franchise_info as c on c.franchise_id = b.franchise_id
                left join crit_hub_branch_info as d on d.hub_branch_code = c.branch_code  and d.status= 'Active'
                left join crit_city_info as e on e.city_code = a.origin_city_code and e.status= 'Active'
                left join crit_city_info as f on f.city_code = a.dest_city_code and f.status= 'Active'
                left join crit_co_loader_info as g on g.co_loader_id = a.co_loader_id and g.status= 'Active'
                where a.`status` != 'Delete' and
                $where   
                group by a.awbno
                order by d.hub_branch_name asc, c.contact_person , a.booking_date asc , a.booking_time asc                         
                              
        ";
        
        // limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ." 
        
        $query = $this->db->query($sql);
        
        //$data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][$row['branch'] . ' [ ' . $row['franchise'] . ' ]'][] = $row;     
        }
        
        $data['total_records'] = $query->num_rows();  
        
       // $data['pagination'] = $this->pagination->create_links();
        }
        
        $this->load->view('page/reports/franchise_wise_booking_report',$data); 
	}
    
    public function franchise_wise_ndr_report()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'reports.inc'; 
        $data['submit_flg'] = false;
        
       /*if(isset($_POST['srch_from_date'])) {
           $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date'); 
           $data['srch_franchise_id'] = $srch_franchise_id = $this->input->post('srch_franchise_id'); 
           $this->session->set_userdata('srch_from_date', $this->input->post('srch_from_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date'));
           $this->session->set_userdata('srch_franchise_id', $this->input->post('srch_franchise_id'));
       }
       elseif($this->session->userdata('srch_from_date')){
           $data['srch_from_date'] = $srch_from_date = $this->session->userdata('srch_from_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ;
           $data['srch_franchise_id'] = $srch_franchise_id = $this->session->userdata('srch_franchise_id') ;
       } else {
        $data['srch_from_date'] = date('Y-m-d');
        $data['srch_to_date'] = date('Y-m-d');
        $data['srch_franchise_id'] = $this->session->userdata('cr_franchise_id');
       } */
       
       if(isset($_POST['srch_from_date'])) {
            $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
            $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date'); 
       } else {
            $data['srch_from_date'] = $srch_from_date =  date('Y-m-d');
            $data['srch_to_date'] = $srch_to_date = date('Y-m-d'); 
       }
       if(isset($_POST['srch_franchise_id'])) {
            $data['srch_franchise_id'] = $srch_franchise_id = $this->input->post('srch_franchise_id');  
       } else {
            if($this->session->userdata('cr_is_admin') == '1' or $this->session->userdata('cr_is_admin') == '111')            
                $data['srch_franchise_id'] = $srch_franchise_id = '';
            else  
                $data['srch_franchise_id'] = $srch_franchise_id = $this->session->userdata('cr_franchise_id'); 
       }
       
       if($this->session->userdata('cr_is_admin') == '11'){
        $data['srch_franchise_id'] = $srch_franchise_id =  $this->session->userdata('cr_franchise_id');         
       }
       
       if(!empty($srch_from_date) && !empty($srch_to_date) ){
        $where = " a.booking_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'"; 
        $data['submit_flg'] = true; 
       }  
       
       if(!empty($srch_franchise_id) ){
         $where .= " and a.created_by in ( select a.user_id from crit_user_info as a  where a.franchise_id =  '". $srch_franchise_id ."') ";
         $data['submit_flg'] = true; 
       }   
       
       if($this->session->userdata('cr_is_admin') == '111')  {
        $where .= " and a.origin_state_code = '". $this->session->userdata('cr_pstate') ."'";  
       }
       
        
        if($this->session->userdata('cr_is_admin') == '1') { 
            $sql = "
                    select 
                    c.franchise_type_name,
                    a.franchise_id,
                    a.contact_person,
                    a.state_code,
                    a.city_code,
                    b.city_name as city
                    from crit_franchise_info as a
                    left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                    left join crit_city_info as b on b.city_code = a.city_code
                    where a.`status` = 'Active'   
                    order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        } elseif($this->session->userdata('cr_is_admin') == '111')  {
            $sql = "
                    select 
                    c.franchise_type_name,
                    a.franchise_id,
                    a.contact_person,
                    a.state_code,
                    a.city_code,
                    b.city_name as city
                    from crit_franchise_info as a
                    left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                    left join crit_city_info as b on b.city_code = a.city_code
                    where a.`status` = 'Active' and a.state_code = '". $this->session->userdata('cr_pstate') ."'   
                    order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        } else {
            $sql = "
                    select 
                    c.franchise_type_name,
                    a.franchise_id,
                    a.contact_person,
                    a.state_code,
                    a.city_code,
                    b.city_name as city
                    from crit_franchise_info as a
                    left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                    left join crit_city_info as b on b.city_code = a.city_code
                    where a.`status` = 'Active' and a.franchise_id = '". $this->session->userdata('cr_franchise_id') ."'   
                    order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        }
        
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['franchise_opt'][$row['franchise_type_name']][$row['franchise_id']] =  $row['state_code'] . ' - ' . $row['city'] .' [ ' . $row['contact_person']. ' ]';     
        }
        
        if($data['submit_flg']) {
        
        /*$this->load->library('pagination'); 
        
        $this->db->where('a.status != ', 'Delete'); 
        $this->db->where($where); 
        $this->db->from('crit_booking_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('franchise-booking-report'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   */
        
        $this->db->query("SET SQL_BIG_SELECTS=1");
        
        $sql = "
                select  
                d.hub_branch_name as branch,
                c.contact_person as franchise,
                a.awbno,
                a.booking_date,
                a.booking_time,
                a.origin_pincode,
                a.origin_state_code,
                a.origin_city_code,
                e.city_name as origin_city,
                a.dest_pincode,
                a.dest_state_code,
                a.dest_city_code,
                f.city_name as dest_city,
                a.customer_ref_no,
                a.no_of_pieces,
                a.chargable_weight,
                a.grand_total,
                a.`status`,
                a.sender_company,
                a.sender_name,
                a.sender_mobile,
                a.sender_address,
                a.receiver_company,
                a.receiver_name,
                a.receiver_mobile,
                a.receiver_address,
                GROUP_CONCAT(g.ndr_date ,' - ', h.ndr_details ,' - ',  g.remarks) as ndr_details
                from crit_booking_info as a
                left join crit_user_info as b on b.user_id = a.created_by
                left join crit_franchise_info as c on c.franchise_id = b.franchise_id
                left join crit_hub_branch_info as d on d.hub_branch_code = c.branch_code 
                left join crit_city_info as e on e.city_code = a.origin_city_code
                left join crit_city_info as f on f.city_code = a.dest_city_code
                left join crit_drs_ndr_info as g on g.awbno = a.awbno
                left join crit_ndr_info as h on h.ndr_id = g.ndr_id
                where a.`status` != 'Delete' and a.status != 'Consignment Delivered' and
                $where   
                group by a.awbno
                order by d.hub_branch_name asc, c.contact_person , a.booking_date asc , a.booking_time asc                         
                              
        ";
        
        // limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ." 
        
        $query = $this->db->query($sql);
        
        //$data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][$row['branch'] . ' [ ' . $row['franchise'] . ' ]'][] = $row;     
        }
        
        $data['total_records'] = $query->num_rows();  
        
       // $data['pagination'] = $this->pagination->create_links();
        }
        
        $this->load->view('page/reports/franchise_wise_ndr_report',$data); 
	}
    
    
    public function inbound_consignment_report()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'reports.inc'; 
        $data['submit_flg'] = false;
        
        
       
       if(isset($_POST['srch_from_date'])) {
            $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
            $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date'); 
       } else {
            $data['srch_from_date'] = date('Y-m-d');
            $data['srch_to_date'] = date('Y-m-d'); 
       }
       /*
       if(isset($_POST['srch_franchise_id'])) {
            $data['srch_franchise_id'] = $srch_franchise_id = $this->input->post('srch_franchise_id');  
       } else {
            if($this->session->userdata('cr_is_admin') == '1' or $this->session->userdata('cr_is_admin') == '111')            
                $data['srch_franchise_id'] = '';
            else  
                $data['srch_franchise_id'] = $this->session->userdata('cr_franchise_id'); 
       } */
       
       if(!empty($srch_from_date) && !empty($srch_to_date) ){
        $where = " a.booking_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'"; 
        $data['submit_flg'] = true; 
       }  
       
       /*
       
       if(!empty($srch_franchise_id) ){
         $where .= " and a.created_by in( select a.user_id from crit_user_info as a  where a.franchise_id =  '". $srch_franchise_id ."') ";
         $data['submit_flg'] = true; 
       }  
       
       */ 
       
       if($this->session->userdata('cr_is_admin') == '111')  {
        $where .= " and a.origin_state_code = '". $this->session->userdata('cr_pstate') ."'";  
       }
       
       /* 
        if($this->session->userdata('cr_is_admin') == '1') { 
            $sql = "
                    select 
                    c.franchise_type_name,
                    a.franchise_id,
                    a.contact_person,
                    a.state_code,
                    a.city_code,
                    b.city_name as city
                    from crit_franchise_info as a
                    left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                    left join crit_city_info as b on b.city_code = a.city_code
                    where a.`status` = 'Active'   
                    order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        } elseif($this->session->userdata('cr_is_admin') == '111')  {
            $sql = "
                    select 
                    c.franchise_type_name,
                    a.franchise_id,
                    a.contact_person,
                    a.state_code,
                    a.city_code,
                    b.city_name as city
                    from crit_franchise_info as a
                    left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                    left join crit_city_info as b on b.city_code = a.city_code
                    where a.`status` = 'Active' and a.state_code = '". $this->session->userdata('cr_pstate') ."'   
                    order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        } else {
            $sql = "
                    select 
                    c.franchise_type_name,
                    a.franchise_id,
                    a.contact_person,
                    a.state_code,
                    a.city_code,
                    b.city_name as city
                    from crit_franchise_info as a
                    left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                    left join crit_city_info as b on b.city_code = a.city_code
                    where a.`status` = 'Active' and a.franchise_id = '". $this->session->userdata('cr_franchise_id') ."'   
                    order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        }
        
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['franchise_opt'][$row['franchise_type_name']][$row['franchise_id']] =  $row['state_code'] . ' - ' . $row['city'] .' [ ' . $row['contact_person']. ' ]';     
        }
        */
        
        if($data['submit_flg']) {
        
         $this->db->query("SET SQL_BIG_SELECTS=1");
        
        $sql = "
                select DISTINCT
                a.booking_id,
                a.origin_pincode,
                a.origin_state_code,
                a.origin_city_code,
                a.dest_pincode,
                a.dest_state_code,
                d.state_name as state,
                a.dest_city_code,
                a.awbno,
                a.booking_date,
                a.no_of_pieces,
                a.chargable_weight , 
                a.`status`, 
                GROUP_CONCAT(b.contact_person) as contact_person, 
                GROUP_CONCAT(c.hub_branch_name) as delivery_branch
                from crit_booking_info as a
                left join crit_franchise_info as b on FIND_IN_SET(a.dest_pincode , b.servicable_pincode)
                left join crit_hub_branch_info as c on c.hub_branch_code = b.branch_code and c.type = 'Branch'
                left join crit_states_info as d on d.state_code = a.dest_state_code 
                where a.`status` != 'Delete' and d.status='Active'
                and $where
                group by a.booking_id 
                order by d.state_name , a.booking_date ,a.booking_id asc                         
                              
        ";
        
        // limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ." 
        
        $query = $this->db->query($sql);
        
        //$data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            //$data['record_list'][$row['delivery_branch'] . ' [ ' . $row['contact_person'] . ' ]'][] = $row;     
            $data['record_list'][$row['state']][] = $row;     
        }
        
        $data['total_records'] = $query->num_rows();  
        
       // $data['pagination'] = $this->pagination->create_links();
        }
        
        $this->load->view('page/reports/inbound_consignment_report',$data); 
	}
    
    public function servicable_pincode_report()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'reports.inc'; 
        $data['submit_flg'] = false;
        
        
       
       if(isset($_POST['srch_pincode'])) {
            $data['srch_pincode'] = $srch_pincode = $this->input->post('srch_pincode');  
       } else {
            $data['srch_pincode'] = ''; 
       }
      
       
       if(!empty($srch_pincode) ){
        $where = " a.pincode = '" . $srch_pincode . "'"; 
        $data['submit_flg'] = true; 
       }  
       
      
       
        
        if($data['submit_flg']) {
        
         $this->db->query("SET SQL_BIG_SELECTS=1");
        
        $sql = " 
                select 
                a.pincode, 
                b.city_name as city,
                c.state_name as state,
                a.zone as region,
                a.metro_city,
                d.franchise_id,
                e.franchise_type_name as franchise_type,
                d.franchise_type_id,
                d.contact_person,
                d.mobile,
                d.email,
                d.branch_code,
                a.serve_type
                from crit_servicable_pincode_info as a
                left join crit_city_info as b on b.city_code = a.branch_code and b.`status` = 'Active' and b.state_code = a.state_code
                left join crit_states_info as c on c.state_code = a.state_code
                left join crit_franchise_info as d on FIND_IN_SET(a.pincode,d.servicable_pincode) and d.`status` = 'Active'
                left join crit_franchise_type_info as e on e.franchise_type_id = d.franchise_type_id
                where a.`status` = 'Active'  
                and $where 
                order by a.pincode , d.franchise_type_id asc                         
                              
        ";
      
        $query = $this->db->query($sql);
        
        //$data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
             $data['record_list'][] = $row;     
        }
        
        $data['total_records'] = $query->num_rows();  
        
       // $data['pagination'] = $this->pagination->create_links();
        }
        
        $this->load->view('page/reports/servicable_pincode_report',$data); 
	}
    
    
    public function city_wise_booking_summary()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'reports.inc'; 
        $data['submit_flg'] = false;
        
        if(isset($_POST['srch_from_date'])) {
           $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date');  
           $this->session->set_userdata('srch_from_date', $this->input->post('srch_from_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date')); 
       }
       elseif($this->session->userdata('srch_from_date')){
           $data['srch_from_date'] = $srch_from_date = $this->session->userdata('srch_from_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ; 
       } else {
        $data['srch_from_date'] = date('Y-m-d');
        $data['srch_to_date'] = date('Y-m-d'); 
       }
       
       if(!empty($srch_from_date) && !empty($srch_to_date)  ){
        $where = " a.booking_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'"; 
        
        $data['submit_flg'] = true;
         
       }  
       
       if($this->session->userdata('cr_is_admin') == '111')  {
        $where .= " and a.origin_state_code = '". $this->session->userdata('cr_pstate') ."'";  
       }  
        
        
         
        
        if($data['submit_flg']) {
        
        $this->load->library('pagination'); 
        
        $this->db->where('a.status != ', 'Delete'); 
        $this->db->where($where); 
        $this->db->from('crit_booking_info as a');         
        $this->db->group_by('a.origin_state_code,a.origin_city_code');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        /*	
        $config['base_url'] = trim(site_url('city-wise-booking-summary'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 25;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   */
        
        $sql = "
                select  
                a.origin_state_code,
                a.origin_city_code, 
                count(a.awbno) as no_of_booking,
                sum(a.no_of_pieces) as no_of_pieces,
                sum(a.chargable_weight) as weight,
                sum(a.grand_total) as total 
                from crit_booking_info as a
                where a.`status` != 'Delete' and
                $where     
                group by a.origin_state_code,a.origin_city_code                         
                order by sum(a.grand_total) desc, a.origin_state_code,a.origin_city_code asc                         
                            
        ";
        //limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."    
        
        $query = $this->db->query($sql);
        
        //$data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        }
        
        $this->load->view('page/reports/city_wise_booking_summary',$data); 
	}  
    
    
    public function manifest_report()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'reports.inc'; 
        $data['submit_flg'] = false;
        
       if(isset($_POST['srch_from_date'])) {
           $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date');  
           $this->session->set_userdata('srch_from_date', $this->input->post('srch_from_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date')); 
       }
       elseif($this->session->userdata('srch_from_date')){
           $data['srch_from_date'] = $srch_from_date = $this->session->userdata('srch_from_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ; 
       } else {
        $data['srch_from_date'] = date('Y-m-d');
        $data['srch_to_date'] = date('Y-m-d'); 
       }
       
       if(!empty($srch_from_date) && !empty($srch_to_date)  ){
        $where = " a.manifest_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'"; 
        
        if($this->session->userdata('cr_is_admin') == '1' or $this->session->userdata('cr_is_admin') == '111')            
             $where.=" and 1";
        else   
            $where .= " and a.despatch_by in (select a.user_id from crit_user_info as a  where a.franchise_id =  '". $this->session->userdata('cr_franchise_id') ."') ";
        
        $data['submit_flg'] = true;
         
       }    
        
        
         
        
        if($data['submit_flg']) {
        
        $this->load->library('pagination'); 
        
        $this->db->where('a.m_status != ', 'Delete'); 
        $this->db->where('b.status != ', 'Delete'); 
        $this->db->where($where); 
        $this->db->select('a.manifest_no'); 
        $this->db->from('crit_manifest_info as a');   
        $this->db->join('crit_booking_info as b', 'b.awbno = a.awbno', 'left');      
        $this->db->group_by('a.manifest_no');  
               
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        /*	
        $config['base_url'] = trim(site_url('manifest-report/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 50;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);  */ 
        
        $sql = "
                select 
                a.manifest_no,
                a.manifest_date,
                a.from_city_code,
                a.to_city_code,
                d.co_loader_name as co_loader,
                a.co_loader_awb_no,
                a.co_loader_remarks,
                count(a.awbno) as open_mf,
                e.received as received_mf,
                sum(c.no_of_pieces) as no_of_pieces,
                sum(c.weight) as tot_weight
                from crit_manifest_info as a
                left join crit_booking_info as c on c.awbno = a.awbno
                left join crit_co_loader_info as d on d.co_loader_id = a.co_loader_id
                left join ( select w.manifest_no , count(w.awbno) as received from crit_manifest_info as w where w.m_status = 'Received Manifest' group by w.manifest_no ) as e on e.manifest_no = a.manifest_no 
                where a.m_status != 'Delete' and c.status != 'Delete' and a.awbno!= '' and $where
                group by a.manifest_no 
                order by a.manifest_date, a.manifest_no asc                      
                        
        ";
        // limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."       
        
        $query = $this->db->query($sql);
        
        //$data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        // $data['pagination'] = $this->pagination->create_links();
        }
        
        $this->load->view('page/reports/manifest_report',$data); 
	} 
    
    public function print_manifest($manifest_no)
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        
        $data['sno'] = $this->uri->segment(2, 0);	
        	    
        $data['js'] = 'reports.inc';  
        
       $sql = "
                select 
                a.manifest_no,
                a.manifest_date,
                a.from_city_code,
                a.to_city_code,
                a.awbno,
                b.origin_pincode,
                b.dest_pincode,
                b.dest_state_code,
                b.dest_city_code,
                b.no_of_pieces,
                b.chargable_weight,
                e.co_loader_name,
                a.co_loader_awb_no,
                c.first_name as despatch_by,
                d.first_name as received_by,
                a.received_date
                from crit_manifest_info as a 
                left join crit_booking_info as b on b.awbno = a.awbno 
                left join crit_user_info as c on c.user_id = a.despatch_by
                left join crit_user_info as d on d.user_id = a.received_by
                left join crit_co_loader_info as e on e.co_loader_id = a.co_loader_id
                where a.m_status != 'Delete' and b.`status`!= 'Delete' 
                and a.manifest_no = '". $manifest_no. "' 
                order by a.manifest_date, a.manifest_no , a.awbno asc    
        ";
        
        
        $query = $this->db->query($sql); 
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        
        
        $this->load->view('page/reports/print-manifest',$data); 
	} 
    
    public function print_drs($drs_no)
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        
        $data['sno'] = $this->uri->segment(2, 0);	
        	    
        $data['js'] = 'reports.inc';  
        
       $sql = "
                select 
                a.drs_no,
                a.drs_date, 
                a.branch_city_code, 
                a.awbno,
                c.origin_state_code,
                c.origin_city_code, 
                c.sender_name,
                c.sender_company,
                c.sender_mobile,
                c.receiver_company,
                c.receiver_name,
                c.receiver_mobile, 
                c.no_of_pieces,
                c.chargable_weight,
                b.first_name as delivery_by
                from crit_drs_info as a 
                left join crit_user_info as b on b.user_id = a.delivery_by
                left join crit_booking_info as c on c.awbno = a.awbno
                where a.drs_no = '".$drs_no."' 
                order by a.drs_date , a.drs_time , c.awbno    
        ";
        
        
        $query = $this->db->query($sql); 
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        
        
        $this->load->view('page/reports/print-drs',$data); 
	}  
     
    public function drs_report()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'reports.inc'; 
        $data['submit_flg'] = false;
        
        if(isset($_POST['srch_from_date'])) {
           $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date');  
           $this->session->set_userdata('srch_from_date', $this->input->post('srch_from_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date')); 
       }
       elseif($this->session->userdata('srch_from_date')){
           $data['srch_from_date'] = $srch_from_date = $this->session->userdata('srch_from_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ; 
       } else {
        $data['srch_from_date'] = date('Y-m-d');
        $data['srch_to_date'] = date('Y-m-d'); 
       }
       
       if(!empty($srch_from_date) && !empty($srch_to_date)  ){
        $where = " a.drs_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'"; 
        
        $data['submit_flg'] = true;
         
       }    
        
        
         
        
        if($data['submit_flg']) {
        
        $this->load->library('pagination'); 
        
        $this->db->where('a.drs_status != ', 'Delete'); 
        $this->db->where($where); 
        $this->db->from('crit_drs_info as a');        
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('manifest-report/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.drs_no,
                a.drs_date,
                d.booking_date,
                a.branch_city_code,
                a.awbno,
                a.drs_status,
                a.delivered_date,
                c.ndr_details as ndr 
                from crit_drs_info as a 
                left join crit_ndr_info as c on c.ndr_id = (select w.ndr_id   from crit_drs_ndr_info as w where w.drs_no = a.drs_no and w.awbno = a.awbno order by w.ndr_date desc , w.ndr_time desc limit 1  )
                left join crit_booking_info as d on d.awbno = a.awbno and d.`status` != 'Delete'
                where 1 and $where
                order by a.drs_date , a.drs_time asc                  
                              
        ";
        
        // limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ." 
        
        $query = $this->db->query($sql);
        
        //$data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        //$data['pagination'] = $this->pagination->create_links();
        }
        
        $this->load->view('page/reports/drs_report',$data); 
	}  
    
    
     public function co_loader_report()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'reports.inc'; 
        $data['submit_flg'] = false;
        
       if(isset($_POST['srch_from_date'])) {
            $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
            $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date');  
       } else {
            $data['srch_from_date'] = date('Y-m-d');
            $data['srch_to_date'] = date('Y-m-d'); 
       }
       
       if(isset($_POST['srch_co_loader_id'])) {
            $data['srch_co_loader_id'] = $srch_co_loader_id = $this->input->post('srch_co_loader_id');  
       }else {
            $data['srch_co_loader_id'] = ''; 
       }
       
       if(!empty($srch_from_date) && !empty($srch_to_date)  ){
        $where = " and a.cl_booking_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'";  
        $data['submit_flg'] = true; 
       }    
        
       if(!empty($srch_co_loader_id)){
        $where = " and a.co_loader_id = '" . $srch_co_loader_id . "'";  
        $data['submit_flg'] = true; 
       }  
       
       
       $sql = "
                select 
                a.co_loader_id,
                a.co_loader_name 
                from crit_co_loader_info as a  
                where status = 'Active'       
                order by a.co_loader_name                
        "; 
        
        $query = $this->db->query($sql);
        
        $data['co_loader_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['co_loader_opt'][$row['co_loader_id']] = $row['co_loader_name'];     
        }
         
        
        if($data['submit_flg']) {
        
         
        $sql = "
                select 
                a.awbno,
                a.cl_no,
                b.co_loader_name,
                a.co_loader_charges,
                a.cl_chrg_weight,
                a.cl_branch,
                a.cl_contact_number,
                a.cl_booking_date,
                a.cl_delivery_date,
                a.`status`
                from crit_booking_info as a  
                left join crit_co_loader_info as b on b.co_loader_id = a.co_loader_id 
                where a.`status` != 'Delete' and b.`status` = 'Active'
                $where 
                order by b.co_loader_name , a.cl_booking_date , a.booking_id 
                 
                              
        ";
         
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][$row['co_loader_name']][] = $row;     
        }
        
         
        }
        
        $this->load->view('page/reports/co-loader-report',$data); 
	} 
    
    
    public function line_haul_booking_report()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        
        	    
        $data['js'] = 'reports.inc'; 
        $data['submit_flg'] = false;
        
        if(isset($_POST['srch_from_date'])) {
           $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date');  
           $this->session->set_userdata('srch_from_date', $this->input->post('srch_from_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date')); 
       }
       elseif($this->session->userdata('srch_from_date')){
           $data['srch_from_date'] = $srch_from_date = $this->session->userdata('srch_from_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ; 
       } else {
        $data['srch_from_date'] = date('Y-m-d');
        $data['srch_to_date'] = date('Y-m-d'); 
       }
       
       if(isset($_POST['srch_co_loader_id'])) {
           $data['srch_co_loader_id'] = $srch_co_loader_id = $this->input->post('srch_co_loader_id');  
           $this->session->set_userdata('srch_co_loader_id', $this->input->post('srch_co_loader_id'));
       }
       elseif($this->session->userdata('srch_co_loader_id')){
           $data['srch_co_loader_id'] = $srch_co_loader_id = $this->session->userdata('srch_co_loader_id') ;
       } else {
         $data['srch_co_loader_id'] = '';
       }
       
       if(!empty($srch_from_date) && !empty($srch_to_date) ){
        $where = " a.booking_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'";
         $data['submit_flg'] = true;
         
       }  
       
       if(!empty($srch_co_loader_id) ){
         $where .= " and a.co_loader_id = '". $srch_co_loader_id ."' ";
        
        $data['submit_flg'] = true;
         
       }  
        
       if($this->session->userdata('cr_is_admin') == '11'){ 
            $where .= " and a.franchise_id = '". $this->session->userdata('cr_franchise_id') ."' "; 
        }   
        
         $sql = "
                select 
                a.co_loader_id,                
                a.co_loader_name           
                from crit_co_loader_info as a  
                where status = 'Active'
                order by a.co_loader_name  asc                 
        "; 
        
        $query = $this->db->query($sql);
        
        $data['co_loader_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['co_loader_opt'][$row['co_loader_id']] = $row['co_loader_name'] ;     
        }
        
        if($data['submit_flg']) {
        
        
        
        
        $sql = "
                select  
                a.*,
                b.co_loader_name
                from crit_line_haul_info as a
                left join crit_co_loader_info as b on b.co_loader_id= a.co_loader_id
                where a.`status` != 'Delete' and
                $where      
                order by a.booking_date desc                 
        ";   
        
        $query = $this->db->query($sql);
        
        //$data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
       // $data['pagination'] = $this->pagination->create_links();
        }
        
        $this->load->view('page/reports/line_haul_booking_report',$data); 
	} 
    
    
    public function co_courier_delivery_report()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        
        	    
        $data['js'] = 'reports.inc'; 
        $data['submit_flg'] = false;
        
        if(isset($_POST['srch_from_date'])) {
           $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date');  
           $this->session->set_userdata('srch_from_date', $this->input->post('srch_from_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date')); 
       }
       elseif($this->session->userdata('srch_from_date')){
           $data['srch_from_date'] = $srch_from_date = $this->session->userdata('srch_from_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ; 
       } else {
        $data['srch_from_date'] = date('Y-m-d');
        $data['srch_to_date'] = date('Y-m-d'); 
       }
       
      if(isset($_POST['srch_co_courier_id'])) {
            $data['srch_co_courier_id'] = $srch_co_courier_id = $this->input->post('srch_co_courier_id');  
            $this->session->set_userdata('srch_co_courier_id', $this->input->post('srch_co_courier_id'));
       }
       elseif($this->session->userdata('srch_co_courier_id')){ 
           $data['srch_co_courier_id'] = $srch_co_courier_id = $this->session->userdata('srch_co_courier_id') ;
       } else {
           $data['srch_co_courier_id'] = '';
       }
       
       if(!empty($srch_from_date) && !empty($srch_to_date) ){
        $where = " a.booking_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'";
         $data['submit_flg'] = true;
         
       }  
       
       if(!empty($srch_co_courier_id) ){
        $where .= " and a.co_courier_id = '". $srch_co_courier_id ."' "; 
        $data['submit_flg'] = true; 
       }  
        
       if($this->session->userdata('cr_is_admin') == '11'){
        $where .= "and a.franchise_id = '". $this->session->userdata('cr_franchise_id') ."'";
        }
        
         
        $sql = "
                select 
                a.co_courier_id,                
                a.co_courier_name           
                from crit_co_courier_info as a  
                where status = 'Active' 
                order by a.co_courier_name                 
        ";  
        $query = $this->db->query($sql);
        
        $data['co_courier_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['co_courier_opt'][$row['co_courier_id']] = $row['co_courier_name']  ;     
        }
        
        if($data['submit_flg']) { 
        
        
        
        $sql = "
                select 
                a.co_courier_drs_id,
                d.co_courier_name as co_courier,
                c.contact_person as franchise,
                b.contact_person as delivery_by,
                a.awb_no,
                a.booking_date,
                a.pkg_type,
                a.weight,
                a.no_of_pcs,
                a.p_mode,
                a.to_pay_amt,
                (select concat(v.co_courier_drs_status_name,'<br>',z.dely_date) from co_courier_drs_dely_info as z left join co_courier_drs_status_info as v on v.co_courier_drs_status_id = z.dely_status  where z.`status`='Active' and v.`status` = 'Active' and z.co_courier_drs_id = a.co_courier_drs_id order by z.dely_date desc, z.co_courier_drs_dely_id desc limit 1) as curr_status
                from co_courier_drs_info as a  
                left join crit_agent_info as b on b.agent_id = a.delivery_by
                left join crit_franchise_info as c on c.franchise_id = a.franchise_id
                left join crit_co_courier_info as d on d.co_courier_id = a.co_courier_id
                where a.`status` != 'Delete' and $where
                order by a.booking_date , a.awb_no asc                
        ";   
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][$row['co_courier']][] = $row;     
        }
        
       
        }
        
        $this->load->view('page/reports/co_courier_delivery_report',$data); 
	} 
    
    
    
    public function wallet_statement()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'reports.inc'; 
        $data['submit_flg'] = false;
        
        if(isset($_POST['srch_month'])) {
           $data['srch_month'] = $srch_month = $this->input->post('srch_month');  
           $data['srch_franchise_id'] = $srch_franchise_id = $this->input->post('srch_franchise_id'); 
            
       }  else {
        $data['srch_month'] = date('Y-m'); 
        $data['srch_franchise_id'] = '';
       }
       
       if(!empty($srch_month) && !empty($srch_franchise_id) ){
        $data['submit_flg'] = true; 
       }    
         
       if($this->session->userdata('cr_is_admin') == '1') { 
            $sql = "
                    select 
                    c.franchise_type_name,
                    a.franchise_id,
                    a.contact_person,
                    a.state_code,
                    a.city_code,
                    b.city_name as city
                    from crit_franchise_info as a
                    left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                    left join crit_city_info as b on b.city_code = a.city_code
                    where a.`status` = 'Active'   
                    order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        } elseif($this->session->userdata('cr_is_admin') == '111')  {
            $sql = "
                    select 
                    c.franchise_type_name,
                    a.franchise_id,
                    a.contact_person,
                    a.state_code,
                    a.city_code,
                    b.city_name as city
                    from crit_franchise_info as a
                    left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                    left join crit_city_info as b on b.city_code = a.city_code
                    where a.`status` = 'Active' and a.state_code = '". $this->session->userdata('cr_pstate') ."'   
                    order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        } else {
            $sql = "
                    select 
                    c.franchise_type_name,
                    a.franchise_id,
                    a.contact_person,
                    a.state_code,
                    a.city_code,
                    b.city_name as city
                    from crit_franchise_info as a
                    left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                    left join crit_city_info as b on b.city_code = a.city_code
                    where a.`status` = 'Active' and a.franchise_id = '". $this->session->userdata('cr_franchise_id') ."'   
                    order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        }
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['franchise_opt'][$row['franchise_type_name']][$row['franchise_id']] =  $row['state_code'] . ' - ' . $row['city'] .' [ ' . $row['contact_person']. ' ]';     
        }
        
        if($data['submit_flg']) {
        
        
        
        
        
        $sql = "
        
        SELECT
            a.franchise_id,
            b.franchise_type_name,
            a.contact_person,
            a.branch_code,
            IFNULL(c.amount, 0) AS op_bal,
            c.op_date,
            SUM(IFNULL(e.int_chrg, 0)) AS int_chrg,
            SUM(IFNULL(f.topup, 0)) AS transfer,  
            ((IFNULL(c.amount, 0)  + SUM(IFNULL(f.topup, 0))) -  SUM(IFNULL(e.int_chrg, 0))) AS balance
        FROM
            crit_franchise_info AS a
        LEFT JOIN crit_franchise_type_info AS b
        ON
            b.franchise_type_id = a.franchise_type_id
        LEFT JOIN(
            SELECT a1.opening_balance_wallet_id,
                a1.franchise_id,
                a1.amount,
                a1.op_date
            FROM
                opening_balance_wallet_info AS a1
            LEFT JOIN(
                SELECT MAX(`op_date`) AS op_date,
                    franchise_id
                FROM
                    opening_balance_wallet_info
                WHERE
                    op_date <= @c_date
                GROUP BY
                    `franchise_id`
            ) AS b1
        ON
            b1.op_date = a1.op_date AND b1.franchise_id = a1.franchise_id
        WHERE
            a1.`status` = 'Active'
        GROUP BY
            a1.franchise_id
        ORDER BY
            a1.franchise_id
        ) AS c
        ON
            c.franchise_id = a.franchise_id
        LEFT JOIN(
            SELECT e1.franchise_id,
                SUM(IFNULL(e1.actual_charges, 0) + IFNULL(e1.ah_charges, 0) + IFNULL(e1.oda_charges, 0) + IFNULL(e1.markups, 0)) AS int_chrg
            FROM
                crit_international_consignment_info AS e1
            WHERE
                e1.`status` = 'Active' AND e1.booking_date BETWEEN(
                SELECT
                    w.op_date
                FROM
                    opening_balance_wallet_info AS w
                WHERE
                    w.`status` = 'Active' AND w.franchise_id = e1.franchise_id AND w.op_date <= '". $srch_month ."-01'
                ORDER BY
                    w.op_date
                DESC
                    ,
                    w.opening_balance_wallet_id
                DESC
            LIMIT 1
            ) AND DATE_SUB('". $srch_month ."-01', INTERVAL 1 DAY)
        GROUP BY
            e1.franchise_id
        ) AS e
        ON
            e.franchise_id = a.franchise_id
        LEFT JOIN(
            SELECT e2.franchise_id,
                SUM(e2.amount) AS topup
            FROM
                wallet_payment_transfer_info AS e2
            WHERE
                e2.`status` = 'Received' AND e2.payment_date BETWEEN(
                SELECT
                    w.op_date
                FROM
                    opening_balance_wallet_info AS w
                WHERE
                    w.`status` = 'Active' AND w.franchise_id = e2.franchise_id AND w.op_date <= '". $srch_month ."-01'
                ORDER BY
                    w.op_date
                DESC
                    ,
                    w.opening_balance_wallet_id
                DESC
            LIMIT 1
            ) AND DATE_SUB('". $srch_month ."-01', INTERVAL 1 DAY)
        GROUP BY
            e2.franchise_id
        ) AS f
        ON
            f.franchise_id = a.franchise_id
        WHERE
            a.`status` = 'Active' AND a.franchise_id = '". $srch_franchise_id ."'
        GROUP BY
            a.franchise_id
        ORDER BY
            a.franchise_type_id;
        
        ";
        
        $query = $this->db->query($sql); 
       
        foreach ($query->result_array() as $row)
        {
            $data['opening'] = $row;     
        } 
        
        
        $sql = "
        select
        z.*
        from
        (    
            
            (
                select 
                a.payment_date as t_date,
                concat('Mode : ',a.pay_mode, ' <br> Rec By : ', a.received_by) as particular,
                a.amount as in_amt,
                0 as out_amt,
                '1' as flg
                from wallet_payment_transfer_info as a
                where a.franchise_id = '" . $srch_franchise_id . "'
                and a.`status` = 'Received'
                and DATE_FORMAT(a.payment_date,'%Y-%m') = '" . $srch_month . "'
                group by a.wallet_payment_transfer_id
                order by a.payment_date asc  
            ) union all (
                select 
                a.booking_date as t_date,
                concat('AWB : ',a.awbno, ' <br> Country :' , b.country_name ) as particular,
                0 as in_amt,
                (IFNULL(a.actual_charges, 0) + IFNULL(a.ah_charges, 0) + IFNULL(a.oda_charges, 0) + IFNULL(a.markups, 0))  as out_amt,
                '2' as flg
                from crit_international_consignment_info as a
                left join crit_country_info as b on b.country_id = a.country_id
                where a.franchise_id = '" . $srch_franchise_id . "' 
                and a.`status` != 'Delete'
                and DATE_FORMAT(a.booking_date,'%Y-%m') = '" . $srch_month . "'
                group by a.international_consignment_id
                order by a.booking_date asc 
            )
        ) as z
        order by z.t_date , z.flg  
        ";
        
        $query = $this->db->query($sql); 
        
        $data['transaction'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['transaction'][] = $row;     
        } 
        
        }
        
        $this->load->view('page/reports/wallet_statement',$data); 
	}  
    
      
    
    
}
