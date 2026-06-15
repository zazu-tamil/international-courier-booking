<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {

	 
	public function account_head_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        }  */
        	    
        $data['js'] = 'accounts/account-head.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'franchise_id' => ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')),
                    'account_head_name' => $this->input->post('account_head_name'),
                    'type' => $this->input->post('type'),
                    'status' => $this->input->post('status'),
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                            
            );
            
            //print_r($ins); exit;
            
            $this->db->insert('crit_account_head_info', $ins); 
            redirect('account-head-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'franchise_id' => ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')),
                    'account_head_name' => $this->input->post('account_head_name'),
                    'type' => $this->input->post('type'),
                    'status' => $this->input->post('status'),
                    'updated_by' => $this->session->userdata('cr_user_id'),                          
                    'updated_datetime' => date('Y-m-d H:i:s')              
            );
            
            $this->db->where('account_head_id', $this->input->post('account_head_id'));
            $this->db->update('crit_account_head_info', $upd); 
                            
            redirect('account-head-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
       
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_account_head_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('account-head-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.*
                from crit_account_head_info as a 
                where status != 'Delete'
                order by a.status asc , a.account_head_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
       
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/accounts/account-head-list',$data); 
	}
    
    
    public function sub_account_head_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        }  */
        	    
        $data['js'] = 'accounts/sub-account-head.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'franchise_id' => ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')),
                    'account_head_id' => $this->input->post('account_head_id'),
                    'sub_account_head_name' => $this->input->post('sub_account_head_name'),
                    'type' => $this->input->post('type'),
                    'status' => $this->input->post('status'),
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                            
            );
            
            //print_r($ins); exit;
            
            $this->db->insert('crit_sub_account_head_info', $ins); 
            redirect('sub-account-head-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'franchise_id' => ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')),
                    'account_head_id' => $this->input->post('account_head_id'),
                    'sub_account_head_name' => $this->input->post('sub_account_head_name'),
                    'type' => $this->input->post('type'),
                    'status' => $this->input->post('status'),
                    'updated_by' => $this->session->userdata('cr_user_id'),                          
                    'updated_datetime' => date('Y-m-d H:i:s')              
            );
            
            $this->db->where('sub_account_head_id', $this->input->post('sub_account_head_id'));
            $this->db->update('crit_sub_account_head_info', $upd); 
                            
            redirect('sub-account-head-list/' . $this->uri->segment(2, 0)); 
        } 
         
          
         
        
        $this->load->library('pagination'); 
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_sub_account_head_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('sub-account-head-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.*,
                b.account_head_name
                from crit_sub_account_head_info as a 
                left join crit_account_head_info as b on b.account_head_id = a.account_head_id
                where a.status != 'Delete' and b.status != 'Delete'
                order by a.status asc , a.sub_account_head_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        "; 
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/accounts/sub-account-head-list',$data); 
	}
    
    public function cash_inward_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        }  */
        	    
        $data['js'] = 'accounts/cash-inward.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'franchise_id' => ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')),
                    'agent_id' => $this->input->post('agent_id'),
                    'inward_date' => $this->input->post('inward_date'),
                    'account_head_id' => $this->input->post('account_head_id'),
                    'sub_account_head_id' => $this->input->post('sub_account_head_id'),
                    'amount' => $this->input->post('amount'),
                    'remarks' => $this->input->post('remarks'),
                    'status' => $this->input->post('status'),
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                            
            );
            
            //print_r($ins); exit;
            
            $this->db->insert('crit_cash_inward_info', $ins); 
            redirect('cash-inward-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'franchise_id' => ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')),
                    'agent_id' => $this->input->post('agent_id'),
                    'inward_date' => $this->input->post('inward_date'),
                    'account_head_id' => $this->input->post('account_head_id'),
                    'sub_account_head_id' => $this->input->post('sub_account_head_id'),
                    'amount' => $this->input->post('amount'),
                    'remarks' => $this->input->post('remarks'),
                    'status' => $this->input->post('status'),
                    'updated_by' => $this->session->userdata('cr_user_id'),                          
                    'updated_datetime' => date('Y-m-d H:i:s')              
            );
            
            $this->db->where('cash_inward_id', $this->input->post('cash_inward_id'));
            $this->db->update('crit_cash_inward_info', $upd); 
                            
            redirect('cash-inward-list/' . $this->uri->segment(2, 0)); 
        } 
        
        $where = " a.franchise_id = '". ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')) ."'";
         
           
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
            $data['srch_from_date'] = $srch_from_date = date('Y-m-d');
            $data['srch_to_date'] = $srch_to_date = date('Y-m-d'); 
        } 
        
        if(!empty($srch_from_date) && !empty($srch_to_date)  ){
            $where .= " and a.inward_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'";  
        }  
         
        
        $this->load->library('pagination'); 
        
        
        $this->db->where('a.status != ', 'Delete'); 
        $this->db->where($where); 
        $this->db->from('crit_cash_inward_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('cash-inward-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.*,
                b.account_head_name,
                c.sub_account_head_name,
                d.contact_person as emp
                from crit_cash_inward_info as a 
                left join crit_account_head_info as b on b.account_head_id = a.account_head_id and b.status != 'Delete'
                left join crit_sub_account_head_info as c on c.sub_account_head_id = a.sub_account_head_id and c.status != 'Delete'
                left join crit_agent_info as d on d.agent_id = a.agent_id and d.status != 'Delete'
                where a.status != 'Delete' and $where  
                order by a.status asc , a.inward_date desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        "; 
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        
        $sql = "
                select 
                a.agent_id,                
                a.contact_person             
                from crit_agent_info as a  
                where status = 'Active'
                and a.franchise_id = '". ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')) ."'
                order by a.contact_person asc                 
        "; 
        
        $query = $this->db->query($sql);
        
        $data['emp_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['emp_opt'][$row['agent_id']] = $row['contact_person'];     
        }
        
        $sql = "
                select 
                a.account_head_id,                
                a.account_head_name             
                from crit_account_head_info as a  
                where a.status = 'Active' and a.type = 'Inward'
                and a.franchise_id = '". ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')) ."'
                order by a.account_head_name asc                 
        "; 
        
        $query = $this->db->query($sql);
        
        $data['account_head_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['account_head_opt'][$row['account_head_id']] = $row['account_head_name'];     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/accounts/cash-inward-list',$data); 
	}
    
    public function cash_outward_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        }  */
        	    
        $data['js'] = 'accounts/cash-outward.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'franchise_id' => ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')),
                    'agent_id' => $this->input->post('agent_id'),
                    'outward_date' => $this->input->post('outward_date'),
                    'account_head_id' => $this->input->post('account_head_id'),
                    'sub_account_head_id' => $this->input->post('sub_account_head_id'),
                    'amount' => $this->input->post('amount'),
                    'remarks' => $this->input->post('remarks'),
                    'status' => $this->input->post('status'),
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                            
            );
            
            //print_r($ins); exit;
            
            $this->db->insert('crit_cash_outward_info', $ins); 
            redirect('cash-outward-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'franchise_id' => ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')),
                    'agent_id' => $this->input->post('agent_id'),
                    'outward_date' => $this->input->post('outward_date'),
                    'account_head_id' => $this->input->post('account_head_id'),
                    'sub_account_head_id' => $this->input->post('sub_account_head_id'),
                    'amount' => $this->input->post('amount'),
                    'remarks' => $this->input->post('remarks'),
                    'status' => $this->input->post('status'),
                    'updated_by' => $this->session->userdata('cr_user_id'),                          
                    'updated_datetime' => date('Y-m-d H:i:s')              
            );
            
            $this->db->where('cash_outward_id', $this->input->post('cash_outward_id'));
            $this->db->update('crit_cash_outward_info', $upd); 
                            
            redirect('cash-outward-list/' . $this->uri->segment(2, 0)); 
        } 
        
        $where = " a.franchise_id = '". ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')) ."'";
         
           
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
            $data['srch_from_date'] = $srch_from_date = date('Y-m-d');
            $data['srch_to_date'] = $srch_to_date = date('Y-m-d'); 
        } 
        
        if(!empty($srch_from_date) && !empty($srch_to_date)  ){
            $where .= " and a.outward_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'";  
        }  
         
        
        $this->load->library('pagination'); 
        
        
        $this->db->where('a.status != ', 'Delete'); 
        $this->db->where($where); 
        $this->db->from('crit_cash_outward_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('cash-outward-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.*,
                b.account_head_name,
                c.sub_account_head_name,
                d.contact_person as emp
                from crit_cash_outward_info as a 
                left join crit_account_head_info as b on b.account_head_id = a.account_head_id and b.status != 'Delete'
                left join crit_sub_account_head_info as c on c.sub_account_head_id = a.sub_account_head_id and c.status != 'Delete'
                left join crit_agent_info as d on d.agent_id = a.agent_id and d.status != 'Delete'
                where a.status != 'Delete' and $where  
                order by a.status asc , a.outward_date desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        "; 
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        
        $sql = "
                select 
                a.agent_id,                
                a.contact_person             
                from crit_agent_info as a  
                where status = 'Active'
                and a.franchise_id = '". ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')) ."'
                order by a.contact_person asc                 
        "; 
        
        $query = $this->db->query($sql);
        
        $data['emp_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['emp_opt'][$row['agent_id']] = $row['contact_person'];     
        }
        
        $sql = "
                select 
                a.account_head_id,                
                a.account_head_name             
                from crit_account_head_info as a  
                where a.status = 'Active' and a.type = 'Outward'
                and a.franchise_id = '". ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')) ."'
                order by a.account_head_name asc                 
        "; 
        
        $query = $this->db->query($sql);
        
        $data['account_head_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['account_head_opt'][$row['account_head_id']] = $row['account_head_name'];     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/accounts/cash-outward-list',$data); 
	}
    
    public function cash_ledger()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        }  */
        	    
        $data['js'] = 'accounts/cash-ledger.inc';  
        
        if(isset($_POST['srch_from_date'])) {
           $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date');  
           
        } else {
            $data['srch_from_date'] = $srch_from_date = date('Y-m-d');
            $data['srch_to_date'] = $srch_to_date = date('Y-m-d'); 
        } 
        
        if(isset($_POST['srch_agent_id'])) {
           $data['srch_agent_id'] = $srch_agent_id = $this->input->post('srch_agent_id');  
           
        } else {
            $data['srch_agent_id'] = $srch_agent_id = ''; 
        } 
        
        $sql = "
                select 
                a.agent_id,                
                a.contact_person             
                from crit_agent_info as a  
                where status = 'Active'
                and a.franchise_id = '". ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')) ."'
                order by a.contact_person asc                 
        "; 
        
        $query = $this->db->query($sql);
        
        $data['emp_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['emp_opt'][$row['agent_id']] = $row['contact_person'];     
        }
        
        
        $data['record_list'] = array(); 
        
        if(!empty($srch_agent_id)) {
        
        $sql_op = "

         select 

         '" . $srch_from_date . "' as t_date,

         'Opening Balance' as particular,

         (sum(z.cash_in) - sum(z.cash_out)) as cash_in,

         0 as cash_out

         from 

         (

        

             (

               select 

                1 as sort,

                a.inward_date as t_date,

                CONCAT(b.account_head_name,' - ', c.sub_account_head_name , '<br>', a.remarks ) as particular,

                a.amount as cash_in,

                0 as cash_out

                from crit_cash_inward_info as a

                left join crit_account_head_info as b on b.account_head_id = a.account_head_id

                left join crit_sub_account_head_info as c on c.sub_account_head_id  = a.sub_account_head_id

                where a.inward_date < '" . $srch_from_date . "' 
                
                and a.franchise_id = '". ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')) ."'
                
                and a.agent_id = '". $srch_agent_id ."'

                order by a.inward_date asc , a.cash_inward_id 

             )   union all (

                select 

                4 as sort,

                a.outward_date as t_date,

                CONCAT(b.account_head_name,' - ', c.sub_account_head_name , '<br>', a.remarks ) as particular,

                0 as cash_in,

                a.amount as cash_out

                from crit_cash_outward_info as a

                left join crit_account_head_info as b on b.account_head_id = a.account_head_id

                left join crit_sub_account_head_info as c on c.sub_account_head_id  = a.sub_account_head_id

                where a.outward_date < '" . $srch_from_date . "' 
                
                and a.franchise_id = '". ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')) ."'

                and a.agent_id = '". $srch_agent_id ."'
                
                order by a.outward_date asc , a.cash_outward_id 

              )

          ) as z

                        

        ";


        $sql_tr = "

         select 

         z.t_date,

         z.particular,

         (z.cash_in) as cash_in,

         (z.cash_out) as cash_out

         from 

         (

        

             (

               select 

                1 as sort,

                a.inward_date as t_date,

                CONCAT(b.account_head_name,' - ', c.sub_account_head_name , '<br>', a.remarks ) as particular,

                a.amount as cash_in,

                0 as cash_out

                from crit_cash_inward_info as a

                left join crit_account_head_info as b on b.account_head_id = a.account_head_id

                left join crit_sub_account_head_info as c on c.sub_account_head_id  = a.sub_account_head_id

                where a.inward_date between '" . $srch_from_date . "' and  '" . $srch_to_date .  "'
                
                and a.franchise_id = '". ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')) ."'

                and a.agent_id = '". $srch_agent_id ."'
                
                order by a.inward_date asc , a.cash_inward_id 

             ) union all (

                select 

                4 as sort,

                a.outward_date as t_date,

                CONCAT(b.account_head_name,' - ', c.sub_account_head_name , '<br>', a.remarks ) as particular,

                0 as cash_in,

                a.amount as cash_out

                from crit_cash_outward_info as a

                left join crit_account_head_info as b on b.account_head_id = a.account_head_id

                left join crit_sub_account_head_info as c on c.sub_account_head_id  = a.sub_account_head_id

                where a.outward_date between '" . $srch_from_date . "' and  '" . $srch_to_date .  "'
                
                and a.franchise_id = '". ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id')) ."'

                and a.agent_id = '". $srch_agent_id ."'
                
                order by a.outward_date asc , a.cash_outward_id 

              )

          ) as z

          order by z.t_date asc , z.sort asc             

        ";


        $sql = "

        select 

         q.t_date,

         q.particular,

         (q.cash_in) as cash_in,

         (q.cash_out) as cash_out

         from (

                (" . $sql_op . ") union all (" . $sql_tr . ") 

              ) as q

         order by q.t_date asc      

            ";
            
           


        $query = $this->db->query($sql);  
                
        

        foreach ($query->result_array() as $row) { 

            $data['record_list'][] = $row;

        }
       } 
        
        $this->load->view('page/accounts/cash-ledger',$data); 
    }
    
 }
 ?>   