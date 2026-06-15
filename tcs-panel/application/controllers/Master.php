<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('page/dashboard');
	}
    
    public function pincode_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('cr_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'pincode.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                'pincode' => $this->input->post('pincode'),
                'area_name' => $this->input->post('area_name'),
                'state_name' => $this->input->post('state_name'),
                'district_name' => $this->input->post('district_name'),
                'status' => $this->input->post('status'),                           
            );
            
            $this->db->insert('crit_pincode_info', $ins); 
            redirect('pincode-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                'pincode' => $this->input->post('pincode'),
                'area_name' => $this->input->post('area_name'),
                'state_name' => $this->input->post('state_name'),
                'district_name' => $this->input->post('district_name'),
                'status' => $this->input->post('status'),                 
            );
            
           // print_r($upd);
            
            $this->db->where('pincode_id', $this->input->post('pincode_id'));
            $this->db->update('crit_pincode_info', $upd); 
                            
            redirect('pincode-list/' . $this->uri->segment(2, 0));  
        } 
        
        
        $query = $this->db->query("select state_name , state_name as state_code  from crit_pincode_info as a where a.status= 'Active' group by state_name order by state_name asc ");
        
        //$data['state_info'][] = 'Select the State';

        foreach ($query->result_array() as $row)
        {
            $data['state_info'][$row['state_code']] = $row['state_name'];     
        } 
        
        
       if(isset($_POST['srch_state'])) {
           $data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_area'] = $srch_area = $this->input->post('srch_area');
           $this->session->set_userdata('srch_state', $this->input->post('srch_state'));
           $this->session->set_userdata('srch_area', $this->input->post('srch_area'));
       }
       elseif($this->session->userdata('srch_state')){
           $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ;
           $data['srch_area'] = $srch_area = $this->session->userdata('srch_area') ;
       }
       
       if(!empty($srch_state)){
        $where = " a.state_name = '" . $srch_state . "'";
        $where .= " and (a.area_name like '%" . $srch_area . "%' or a.pincode like '%". $srch_area ."%' or a.district_name like '%". $srch_area ."%') ";
         
       } else {
        $where = " a.state_name = 'Tamil Nadu' ";
        $this->session->set_userdata('srch_state', 'Tamil Nadu');
        $data['srch_state'] = $srch_state =  'Tamil Nadu';
        $data['srch_area'] = $srch_area =  '';
       }
         
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->where($where);
        $this->db->from('crit_pincode_info as a');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('pincode-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                pincode_id, 
                pincode, 
                state_name, 
                district_name, 
                circle_name, 
                region_name, 
                division_name, 
                area_name as area, 
                `status`
                from crit_pincode_info as a 
                where status != 'Delete' and ". $where ."
                order by  a.pincode  asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        $data['record_list'] = array();
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/pincode-list',$data); 
	} 
    
    public function country_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'country.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'country_name' => $this->input->post('country_name'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_country_info', $ins); 
            redirect('country-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'country_name' => $this->input->post('country_name'),
                    'status' => $this->input->post('status'),                 
            );
            
            $this->db->where('country_id', $this->input->post('country_id'));
            $this->db->update('crit_country_info', $upd); 
                            
            redirect('country-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
       
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_country_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('country-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.country_id,
                a.country_name,                
                a.status
                from crit_country_info as a 
                where status != 'Delete'
                order by a.status asc , a.country_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/country-list',$data); 
	} 
    
    public function state_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'state.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'state_name' => $this->input->post('state_name'),
                    'state_code' => $this->input->post('state_code'),
                    'status' => $this->input->post('status'),                           
            );
            
            $this->db->insert('crit_states_info', $ins); 
            redirect('state-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'state_name' => $this->input->post('state_name'),
                    'state_code' => $this->input->post('state_code'),    
                    'status' => $this->input->post('status'),              
            );
            
            $this->db->where('state_id', $this->input->post('state_id'));
            $this->db->update('crit_states_info', $upd); 
                            
            redirect('state-list/' . $this->uri->segment(2, 0)); 
        }
         
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->from('crit_states_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);	
        	
        $config['base_url'] = trim(site_url('state-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 50;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        
        // a.status = 'Active'
        
        $sql = "
                select 
                a.state_id,
                a.state_name,                
                a.state_code,
                a.status                
                from crit_states_info as a  
                where status != 'Delete'
                order by a.state_name asc  
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/state-list',$data); 
	}
    
    public function city_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'city.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'city_name' => $this->input->post('city_name'),
                    'state_code' => $this->input->post('state_code'),
                    'city_code' => $this->input->post('city_code'),
                    'status' => $this->input->post('status'),                           
            );
            
            $this->db->insert('crit_city_info', $ins); 
            redirect('city-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                   'city_name' => $this->input->post('city_name'),
                    'state_code' => $this->input->post('state_code'),
                    'city_code' => $this->input->post('city_code'),
                    'status' => $this->input->post('status'),              
            );
            
            $this->db->where('city_id', $this->input->post('city_id'));
            $this->db->update('crit_city_info', $upd); 
                            
            redirect('city-list/' . $this->uri->segment(2, 0)); 
        }
        
        
       if(isset($_POST['srch_state'])) {
           $data['srch_state'] = $srch_state = $this->input->post('srch_state'); 
           $this->session->set_userdata('srch_state', $this->input->post('srch_state')); 
       }
       elseif($this->session->userdata('srch_state')){
           $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ; 
       }
       
       if(isset($_POST['srch_key'])) {
            $data['srch_key'] = $srch_key = $this->input->post('srch_key'); 
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_key')){ 
           $data['srch_key'] = $srch_key = $this->session->userdata('srch_key') ;
       }
       
       if(!empty($srch_state)){
        $where = " a.state_code = '" . $srch_state . "'"; 
         
       } else {
        $where = " 1 ";
        $this->session->set_userdata('srch_state', '');
        $data['srch_state'] = $srch_state =  ''; 
       }
       
       if(!empty($srch_key)){ 
        $where .= " and (a.city_name like '%" . $srch_key . "%' or a.city_code like '%". $srch_key ."%') ";
         
       } else { 
        $data['srch_key'] = $srch_key =  '';
       }
         
        
        $this->load->library('pagination');
        
        $this->db->where('a.status != ', 'Delete');
        $this->db->where($where);
        $this->db->from('crit_city_info as a');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);	
        	
        $config['base_url'] = trim(site_url('city-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 50;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        
        // a.status = 'Active'
        
        $sql = "
                select 
                a.city_id,
                a.city_name,                
                a.state_code,
                a.city_code,
                a.status                
                from crit_city_info as a  
                where status != 'Delete'
                and $where
                order by a.state_code, a.city_name asc  
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        
        $sql = "
                select 
                a.state_name,                
                a.state_code             
                from crit_states_info as a  
                where status = 'Active'
                order by a.state_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_code']] = $row['state_name']. ' [ ' . $row['state_code'] . ' ]';     
        }
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/city-list',$data); 
	}
    
    public function commodity_type_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'commodity-type.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'commodity_type_name' => $this->input->post('commodity_type_name'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_commodity_type_info', $ins); 
            redirect('commodity-type-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'commodity_type_name' => $this->input->post('commodity_type_name'),
                    'status' => $this->input->post('status'),                 
            );
            
            $this->db->where('commodity_type_id', $this->input->post('commodity_type_id'));
            $this->db->update('crit_commodity_type_info', $upd); 
                            
            redirect('commodity-type-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination'); 
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_commodity_type_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('commodity-type-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.commodity_type_id,
                a.commodity_type_name,                
                a.status
                from crit_commodity_type_info as a 
                where status != 'Delete'
                order by a.status asc , a.commodity_type_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
         $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/commodity-type-list',$data); 
	} 
    
    public function franchise_type_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'franchise-type.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'franchise_type_name' => $this->input->post('franchise_type_name'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_franchise_type_info', $ins); 
            redirect('franchise-type-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'franchise_type_name' => $this->input->post('franchise_type_name'),
                    'status' => $this->input->post('status'),               
            );
            
            $this->db->where('franchise_type_id', $this->input->post('franchise_type_id'));
            $this->db->update('crit_franchise_type_info', $upd); 
                            
            redirect('franchise-type-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_franchise_type_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('franchise-type-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.franchise_type_id,
                a.franchise_type_name,                
                a.status
                from crit_franchise_type_info as a 
                where status != 'Delete'
                order by a.status asc , a.franchise_type_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        $data['record_list'] = array();
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/franchise-type-list',$data); 
	} 
    
      
    
    public function servicable_pincode_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        
        if($this->input->post('export') == 'xls')
       {
          $this->load->library("excel");
          $this->excel->setActiveSheetIndex(0);
          //$this->excel->setActiveSheetIndexByName('Franchisee-Enquiry');
         
         $sql = "
                select DISTINCT 
                a.pincode, 
                a.area, 
                a.serve_type,
                a.premium_express, 
                a.business_express,  
                b.state_name as state, 
                c.city_name as city,
                a.zone,  
                a.metro_city, 
                d.city_name as ops_by, 
                a.service_by, 
                a.`status` 
                from crit_servicable_pincode_info as a 
                left join crit_states_info as b on b.state_code = a.state_code and b.status = 'Active'
                left join crit_city_info as c on c.state_code = a.state_code and c.city_code = a.branch_code and c.status = 'Active'
                left join crit_city_info as d on  d.city_code = a.ops_by and d.status = 'Active'
                where  a.status != 'Delete'  
                order by a.status asc , a.pincode asc  
        "; 
          
          
         $this->db->query('SET SQL_BIG_SELECTS=1;');
         
         $query = $this->db->query($sql);
         
        $export_data = array();  

        foreach ($query->result_array() as $row)
        {
            $export_data[] = $row;     
        }
        
        $this->excel->stream( 'servicable-pincode-List-'. date('Y-m-d-his').'.xls', $export_data);
         
       } 
        	    
                
                
        $data['js'] = 'servicable_pincode.inc';   
         
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'pincode' => $this->input->post('pincode'),
                    'area' => $this->input->post('area'),
                    'premium_express' => $this->input->post('premium_express'),
                    'business_express' => $this->input->post('business_express'),
                    'state_code' => $this->input->post('state_code'),
                    'zone' => $this->input->post('zone'),
                    'branch_code' => $this->input->post('branch_code'),
                    'ops_by' => $this->input->post('ops_by'),
                    'service_by' => $this->input->post('service_by'),
                    'metro_city' => $this->input->post('metro_city'),
                    'status' => $this->input->post('status'),                          
                    'serve_type' => $this->input->post('serve_type'),                          
            );
            
            $this->db->insert('crit_servicable_pincode_info', $ins); 
            redirect('servicable-pincode-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'pincode' => $this->input->post('pincode'),
                    'area' => $this->input->post('area'),
                    'premium_express' => $this->input->post('premium_express'),
                    'business_express' => $this->input->post('business_express'),
                    'state_code' => $this->input->post('state_code'),
                    'zone' => $this->input->post('zone'),
                    'branch_code' => $this->input->post('branch_code'),
                    'ops_by' => $this->input->post('ops_by'),
                    'service_by' => $this->input->post('service_by'),
                    'metro_city' => $this->input->post('metro_city'),
                    'status' => $this->input->post('status')  ,                 
                    'serve_type' => $this->input->post('serve_type')  ,                 
            );
            
            $this->db->where('pincode_id', $this->input->post('pincode_id'));
            $this->db->update('crit_servicable_pincode_info', $upd); 
                            
            redirect('servicable-pincode-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
       if(isset($_POST['srch_state'])) {
           $data['srch_state'] = $srch_state = $this->input->post('srch_state');
           //$data['srch_key'] = $srch_key = $this->input->post('srch_key');
           $this->session->set_userdata('srch_state', $this->input->post('srch_state'));
           //$this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_state')){
           $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ;
           //$data['srch_key'] = $srch_key = $this->session->userdata('srch_key') ;
       }
       
       if(isset($_POST['srch_key'])) { 
           $data['srch_key'] = $srch_key = $this->input->post('srch_key'); 
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_key')){ 
           $data['srch_key'] = $srch_key = $this->session->userdata('srch_key') ;
       }
       if(isset($_POST['srch_serve_type'])) { 
           $data['srch_serve_type'] = $srch_serve_type = $this->input->post('srch_serve_type'); 
           $this->session->set_userdata('srch_serve_type', $this->input->post('srch_serve_type'));
       }
       elseif($this->session->userdata('srch_serve_type')){ 
           $data['srch_serve_type'] = $srch_serve_type = $this->session->userdata('srch_serve_type') ;
       }
       
       
      $where = '1';
       if(!empty($srch_state)){
         $where .= " and a.state_code = '". $srch_state ."'";
       } else {
        
        $data['srch_state'] = $srch_state =  '';
       }
       if(!empty($srch_key)) {
         $where .= " and (a.pincode like '%" . $srch_key . "%' or a.area like '%". $srch_key ."%' or a.state_code like '%". $srch_key ."%' or a.branch_code like '%". $srch_key ."%' ) ";
         
       } else {
          
        $data['srch_key'] = $srch_key =  '';
       }
       
       if(!empty($srch_serve_type)){
         $where .= " and a.serve_type = '". $srch_serve_type ."'";
       } else {        
         $data['srch_serve_type'] = $srch_serve_type =  '';
       }
        
        
        $this->db->where('status != ', 'Delete');  
        if($where != '1')
            $this->db->where($where);
        $this->db->from('crit_servicable_pincode_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('servicable-pincode-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $this->db->query("SET SQL_BIG_SELECTS=1");
        
        $sql = "
                select DISTINCT
                a.pincode_id, 
                a.pincode, 
                a.area, 
                a.premium_express, 
                a.business_express, 
                a.state_code,
                b.state_name as state, 
                a.zone, 
                a.branch_code, 
                c.city_name as city,
                d.city_name as ops_by, 
                a.service_by,
                a.metro_city, 
                a.`status`,
                a.serve_type
                from crit_servicable_pincode_info as a 
                left join crit_states_info as b on b.state_code = a.state_code and b.status = 'Active'
                left join crit_city_info as c on c.state_code = a.state_code and c.city_code = a.branch_code and c.status = 'Active'
                left join crit_city_info as d on  d.city_code = a.ops_by and d.status = 'Active'
                where  a.status != 'Delete' and ". $where ."
                order by a.status asc , a.pincode asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $sql = "
                select 
                a.state_name,                
                a.state_code             
                from crit_states_info as a  
                where status = 'Active'
                order by a.state_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_code']] = $row['state_name']. ' [ ' . $row['state_code'] . ' ]';     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/servicable-pincode-list',$data); 
	} 
    
    public function franchise_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'franchise-list.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'franchise_type_id' => $this->input->post('franchise_type_id'),
                    'contact_person' => $this->input->post('contact_person'),
                    'mobile' => $this->input->post('mobile'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'gst_no' => $this->input->post('gst_no'),
                    'address' => $this->input->post('address'),
                    'state_code' => $this->input->post('state_code'),
                    'city_code' => $this->input->post('city_code'),
                    'servicable_pincode' => implode(',',$this->input->post('servicable_pincode')),
                    'branch_code' => $this->input->post('branch_code'),
                    'hub_code' => $this->input->post('hub_code'),
                    'tracking_upd_rights' => $this->input->post('tracking_upd_rights'),
                    'status' => $this->input->post('status'),
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                           
            );
            
            $this->db->insert('crit_franchise_info', $ins); 
            redirect('franchise-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'franchise_type_id' => $this->input->post('franchise_type_id'),
                    'contact_person' => $this->input->post('contact_person'),
                    'mobile' => $this->input->post('mobile'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'gst_no' => $this->input->post('gst_no'),
                    'address' => $this->input->post('address'),
                    'state_code' => $this->input->post('state_code'),
                    'city_code' => $this->input->post('city_code'),
                    'servicable_pincode' => implode(',',$this->input->post('servicable_pincode')),
                    'branch_code' => $this->input->post('branch_code'),
                    'hub_code' => $this->input->post('hub_code'),
                    'tracking_upd_rights' => $this->input->post('tracking_upd_rights'),
                    'status' => $this->input->post('status')  , 
                    'updated_by' => $this->session->userdata('cr_user_id'),                          
                    'updated_datetime' => date('Y-m-d H:i:s')                 
            );
            
            $this->db->where('franchise_id', $this->input->post('franchise_id'));
            $this->db->update('crit_franchise_info', $upd); 
                            
            redirect('franchise-list/' . $this->uri->segment(2, 0)); 
        } 
        
        if($this->input->post('mode') == 'Add User')
        {
            
            $insert_sql = "insert into crit_user_info (first_name, user_name, pwd, `level`, email, mobile, state, city, pincodes, franchise_id, `status`) (
                           select 
                           '".$this->input->post('first_name')."' as first_name, 
                           '".$this->input->post('user_name')."' as user_name, 
                           '".$this->input->post('pwd')."' as pwd, 
                           '11' as level,
                           '".$this->input->post('email')."' as email, 
                           '".$this->input->post('mobile')."' as mobile, 
                           a.state_code,
                           a.city_code,
                           a.servicable_pincode,
                           a.franchise_id,
                           '".$this->input->post('status')."' as status
                           from crit_franchise_info as a
                           where a.franchise_id = '".$this->input->post('franchise_id')."'
                            
                          )";
            
            $this->db->query($insert_sql);
             
                             
            redirect('franchise-list/' . $this->uri->segment(2, 0)); 
        }
        
        if($this->input->post('mode') == 'Edit User')
        {
            $upd = array(
                    'first_name' => $this->input->post('first_name'),
                    'user_name' => $this->input->post('user_name'),
                    'pwd' => $this->input->post('pwd'), 
                    'email' => $this->input->post('email'),
                    'mobile' => $this->input->post('mobile'), 
                    'status' => $this->input->post('status')                 
            );
            
            $this->db->where('user_id', $this->input->post('user_id'));
            $this->db->update('crit_user_info', $upd); 
                            
            redirect('franchise-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
       if(isset($_POST['srch_state'])) {
           $data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_key'] = $srch_key = $this->input->post('srch_key');
           $this->session->set_userdata('srch_state', $this->input->post('srch_state'));
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_state')){
           $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ; 
       }else {
           $data['srch_state'] = $srch_state = '';
       }
       
       if(isset($_POST['srch_key'])) { 
           $data['srch_key'] = $srch_key = $this->input->post('srch_key'); 
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_key')){ 
           $data['srch_key'] = $srch_key = $this->session->userdata('srch_key') ;
       } else {
         $data['srch_key'] = $srch_key = '';
       }
       
       if(isset($_POST['srch_franchise'])) { 
           $data['srch_franchise'] = $srch_franchise = $this->input->post('srch_franchise'); 
           $this->session->set_userdata('srch_franchise', $this->input->post('srch_franchise'));
       }
       elseif($this->session->userdata('srch_franchise')){ 
           $data['srch_franchise'] = $srch_franchise = $this->session->userdata('srch_franchise') ;
       } else {
         $data['srch_franchise'] = $srch_franchise = '';
       }
       
       $where = '1';

       if(!empty($srch_franchise)){
         $where .= " and a.franchise_type_id = '". $srch_franchise ."'";
       }
        
       if(!empty($srch_state)){
         $where .= " and a.state_code = '". $srch_state ."'";
       }  
       if(!empty($srch_key)) {
         $where .= " and ( 
                        a.servicable_pincode like '%" . $srch_key . "%' or 
                        a.mobile like '%". $srch_key ."%' or 
                        a.contact_person like '%". $srch_key ."%' or 
                        a.email like '%". $srch_key ."%' or 
                        a.phone like '%". $srch_key ."%'
                        ) ";
         
       } 
        
        
        $this->db->where('status != ', 'Delete');
        //if(!empty($srch_key))
            $this->db->where($where);
        $this->db->from('crit_franchise_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('franchise-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.franchise_id,
                b.franchise_type_name as franchise_type,
                a.contact_person, 
                a.mobile, 
                a.phone, 
                a.email, 
                a.gst_no, 
                a.address, 
                a.state_code, 
                a.city_code, 
                a.servicable_pincode, 
                a.branch_code,
                a.hub_code,
                a.`status`
                from crit_franchise_info as a
                left join crit_franchise_type_info as b on b.franchise_type_id = a.franchise_type_id
                where b.`status` = 'Active' and  a.status != 'Delete' 
                and ". $where ."
                order by a.status asc , b.franchise_type_name , a.contact_person asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        //and exists ( select * from crit_franchise_info where state_code = a.state_code )
        
        $sql = "
                select 
                a.state_name,                
                a.state_code             
                from crit_states_info as a  
                where status = 'Active'                
                order by a.state_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_code']] = $row['state_name']. ' [ ' . $row['state_code'] . ' ]';     
        }
        
        $sql = "
                select 
                a.franchise_type_id,                
                a.franchise_type_name             
                from crit_franchise_type_info as a  
                where status = 'Active' 
                order by a.franchise_type_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['franchise_type_opt'][$row['franchise_type_id']] = $row['franchise_type_name'];     
        }
        
        $sql = "
                select 
                a.hub_branch_code,                
                a.hub_branch_name             
                from crit_hub_branch_info as a  
                where a.status = 'Active' and a.type = 'Branch'
                order by a.hub_branch_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['branch_code_opt'][$row['hub_branch_code']] = $row['hub_branch_name']. ' [ ' . $row['hub_branch_code'] . ' ]';       
        }
        
        $sql = "
                select 
                a.hub_branch_code,                
                a.hub_branch_name             
                from crit_hub_branch_info as a  
                where a.status = 'Active' and a.type = 'HUB'
                order by a.hub_branch_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['hub_code_opt'][$row['hub_branch_code']] = $row['hub_branch_name']. ' [ ' . $row['hub_branch_code'] . ' ]';       
        }
        
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/franchise-list',$data); 
	} 
    
    
    public function franchise_doc_upload_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
       /* if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        }  */
        	    
        $data['js'] = 'franchise-doc-upload.inc';  
        
        if(isset($_POST['srch_franchise_id'])) {
        $data['srch_franchise_id'] = $srch_franchise_id = $this->input->post('srch_franchise_id'); 
        } else {
            $data['srch_franchise_id'] = $srch_franchise_id = 1;
        }
        
        if($this->input->post('mode') == 'Add')
        {
            $franchise_id = $this->input->post('franchise_id');
            
            $config['upload_path'] = 'franchise-doc/';
            $config['file_name'] = $franchise_id. "_". date('YmdHis');
    		$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
            
            $this->load->library('upload', $config); 
            
            
            if ($this->upload->do_upload('doc_path'))
            {
                $file_array = $this->upload->data();	
                //$image_path	= 'booking-doc/'. $booking_id. "_". date('YmdHis') . $file_array['file_name']; 
                $image_path	= 'franchise-doc/'. $file_array['file_name']; 
           
            }
            else
            {
                 $image_path = '';    
            }
            
            $ins = array(
                    'franchise_id' => $this->input->post('franchise_id'),
                    'doc_name' => $this->input->post('doc_name'),
                    'doc_path' => $image_path,
                    'remarks' => $this->input->post('remarks'),
                    'status' => 'Active'  ,                          
            );
            
            $this->db->insert('crit_franchise_doc_upload_info', $ins); 
            redirect('franchise-doc-upload-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'franchise_id' => $this->input->post('franchise_id'),
                    'doc_name' => $this->input->post('doc_name'),
                    'doc_path' => $this->input->post('doc_path'),
                    'remarks' => $this->input->post('remarks'),
                    //'status' => $this->input->post('status')  ,                  
            );
            
            $this->db->where('franchise_doc_upload_id', $this->input->post('franchise_doc_upload_id'));
            $this->db->update('crit_franchise_doc_upload_info', $upd); 
                            
            redirect('franchise-doc-upload-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination'); 
        
        
        $this->db->where('franchise_id', $srch_franchise_id); 
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_franchise_doc_upload_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('franchise-doc-upload-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.*
                from crit_franchise_doc_upload_info as a 
                where a.status != 'Delete' and franchise_id = $srch_franchise_id
                order by a.status asc , a.created_date desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
       /* $sql = "
                    select 
                    c.franchise_type_name,
                    a.franchise_id,
                    a.contact_person,
                    a.state_code,
                    a.city_code,
                    b.city_name as city
                    from crit_franchise_info as a
                    left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                    left join crit_city_info as b on b.city_code = a.city_code and b.`status` = 'Active' 
                    where a.`status` = 'Active'   
                    order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['franchise_opt'][$row['franchise_type_name']][$row['franchise_id']] =  $row['state_code'] . ' - ' . $row['city'] .' [ ' . $row['contact_person']. ' ]';     
        } */
        
         $sql = "
                select 
                c.franchise_type_name,
                a.franchise_id,
                a.contact_person,
                a.state_code,
                a.city_code,
                b.city_name as city,
                a.branch_code
                from crit_franchise_info as a
                left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                left join crit_city_info as b on b.city_code = a.city_code and b.`status` = 'Active' 
                where a.`status` = 'Active'  
                group by a.franchise_id 
                order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['franchise_opt'][$row['franchise_type_name']][$row['franchise_id']] =  $row['state_code'] . ' - ' . $row['city'] .' [ ' . $row['contact_person']. ' ]' .' [ ' . $row['branch_code']. ' ]';     
                
        }
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/franchise-doc-upload-list',$data); 
	} 
    
    
    public function franchise_awbill_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
       /* if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        }  */
        	    
        $data['js'] = 'franchise-awbill.inc';  
        
        if(isset($_POST['srch_franchise_id'])) {
        $data['srch_franchise_id'] = $srch_franchise_id = $this->input->post('srch_franchise_id'); 
        $this->session->set_userdata('srch_franchise_id', $this->input->post('srch_franchise_id'));
        }  elseif($this->session->userdata('srch_franchise_id')){
            $data['srch_franchise_id'] = $srch_franchise_id = $this->session->userdata('srch_franchise_id') ;
       } else {
            $data['srch_franchise_id'] = $srch_franchise_id = 1;
        }
        
        if($this->input->post('mode') == 'Add')
        {
            $franchise_id = $this->input->post('franchise_id');
             
            
            $ins = array(
                    'franchise_id' => $this->input->post('franchise_id'),
                    'awbill_from' => $this->input->post('awbill_from'),
                    'awbill_to' => $this->input->post('awbill_to'), 
                    'remarks' => $this->input->post('remarks'),
                    'status' => 'Active',                          
            );
            
            $this->db->insert('crit_franchise_awbill_info', $ins); 
            redirect('franchise-awbill-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'franchise_id' => $this->input->post('franchise_id'),
                    'awbill_from' => $this->input->post('awbill_from'),
                    'awbill_to' => $this->input->post('awbill_to'), 
                    'remarks' => $this->input->post('remarks'),                  
            );
            
            $this->db->where('franchise_awbill_id', $this->input->post('franchise_awbill_id'));
            $this->db->update('crit_franchise_awbill_info', $upd); 
                            
            redirect('franchise-awbill-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination'); 
        
        
        $this->db->where('franchise_id', $srch_franchise_id); 
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_franchise_awbill_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('franchise-awbill-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.*
                from crit_franchise_awbill_info as a 
                where a.status != 'Delete' and franchise_id = $srch_franchise_id
                order by a.status asc , a.created_date desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
         
        
         $sql = "
                select 
                c.franchise_type_name,
                a.franchise_id,
                a.contact_person,
                a.state_code,
                a.city_code,
                b.city_name as city,
                a.branch_code
                from crit_franchise_info as a
                left join crit_franchise_type_info as c on c.franchise_type_id = a.franchise_type_id
                left join crit_city_info as b on b.city_code = a.city_code and b.`status` = 'Active' 
                where a.`status` = 'Active'  
                group by a.franchise_id 
                order by c.franchise_type_name asc , a.state_code , b.city_name, a.contact_person asc          
            "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['franchise_opt'][$row['franchise_type_name']][$row['franchise_id']] =  $row['state_code'] . ' - ' . $row['city'] .' [ ' . $row['contact_person']. ' ]' .' [ ' . $row['branch_code']. ' ]';     
                
        }
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/franchise-awbill-list',$data); 
	} 
    
    
    
    public function agent_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'agent.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'franchise_id' => ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id') ),
                    'contact_person' => $this->input->post('contact_person'),
                    'mobile' => $this->input->post('mobile'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'), 
                    'address' => $this->input->post('address'),
                    'state_code' => $this->input->post('state_code'),
                    'city_code' => $this->input->post('city_code'),
                    'servicable_pincode' => implode(',',$this->input->post('servicable_pincode')),
                    'status' => $this->input->post('status'),
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                           
            );
            
            $this->db->insert('crit_agent_info', $ins); 
            redirect('agent-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array( 
                    'franchise_id' => ($this->session->userdata('cr_franchise_id') == '' ? 0 : $this->session->userdata('cr_franchise_id') ),
                    'contact_person' => $this->input->post('contact_person'),
                    'mobile' => $this->input->post('mobile'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'), 
                    'address' => $this->input->post('address'),
                    'state_code' => $this->input->post('state_code'),
                    'city_code' => $this->input->post('city_code'),
                    'servicable_pincode' => implode(',',$this->input->post('servicable_pincode')),
                    'status' => $this->input->post('status')  , 
                    'updated_by' => $this->session->userdata('cr_user_id'),                          
                    'updated_datetime' => date('Y-m-d H:i:s')                 
            );
            
            $this->db->where('agent_id', $this->input->post('agent_id'));
            $this->db->update('crit_agent_info', $upd); 
                            
            redirect('agent-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
       if(isset($_POST['srch_state'])) {
           $data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_key'] = $srch_key = $this->input->post('srch_key');
           $this->session->set_userdata('srch_state', $this->input->post('srch_state'));
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_state')){
           $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ; 
       }else {
           $data['srch_state'] = $srch_state = '';
       }
       
       if(isset($_POST['srch_key'])) { 
           $data['srch_key'] = $srch_key = $this->input->post('srch_key'); 
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_key')){ 
           $data['srch_key'] = $srch_key = $this->session->userdata('srch_key') ;
       } else {
         $data['srch_key'] = $srch_key = '';
       }
       
        
       
       $where = '1=1';

       if($this->session->userdata('cr_is_admin') == '11'){
        $where .= " and a.franchise_id = '". $this->session->userdata('cr_franchise_id') ."'";
       }
        
       if(!empty($srch_state)){
         $where .= " and a.state_code = '". $srch_state ."'";
       }  
       if(!empty($srch_key)) {
         $where .= " and ( 
                        a.servicable_pincode like '%" . $srch_key . "%' or 
                        a.mobile like '%". $srch_key ."%' or 
                        a.contact_person like '%". $srch_key ."%' or 
                        a.email like '%". $srch_key ."%' or 
                        a.phone like '%". $srch_key ."%'
                        ) ";
         
       } 
        
        
        $this->db->where('status != ', 'Delete');
        //if(!empty($srch_key))
            $this->db->where($where);
        $this->db->from('crit_agent_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('agent-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.agent_id, 
                a.contact_person, 
                a.mobile, 
                a.phone, 
                a.email,  
                a.address, 
                a.state_code, 
                a.city_code, 
                a.servicable_pincode, 
                a.`status`,
                b.contact_person as franchise
                from crit_agent_info as a 
                left join crit_franchise_info as b on b.franchise_id = a.franchise_id
                where a.status != 'Delete' 
                and ". $where ."
                order by a.status asc , a.contact_person asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $sql = "
                select 
                a.state_name,                
                a.state_code             
                from crit_states_info as a  
                where status = 'Active' 
                order by a.state_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_code']] = $row['state_name']. ' [ ' . $row['state_code'] . ' ]';     
        }
        
         
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/agent-list',$data); 
	} 
    
    
    public function customer_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'customer.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'customer_code' => $this->input->post('customer_code'),
                    'customer_type_id' => $this->input->post('customer_type_id'),
                    'company' => $this->input->post('company'),
                    'contact_person' => $this->input->post('contact_person'),
                    'gst_no' => $this->input->post('gst_no'),
                    'aadhar_no' => $this->input->post('aadhar_no'),
                    'mobile' => $this->input->post('mobile'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'), 
                    'address' => $this->input->post('address'),
                    'state_code' => $this->input->post('state_code'),
                    'city_code' => $this->input->post('city_code'),
                    'pincode' => $this->input->post('pincode'),
                    'status' => $this->input->post('status'),
                    'franchise_type_id' => $this->input->post('franchise_type_id'),
                    'franchise_id' => $this->input->post('franchise_id'),
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                           
            );
            
            $this->db->insert('crit_customer_info', $ins); 
            redirect('customer-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array( 
                    'customer_code' => $this->input->post('customer_code'),
                    'customer_type_id' => $this->input->post('customer_type_id'),
                    'company' => $this->input->post('company'),
                    'contact_person' => $this->input->post('contact_person'),
                    'gst_no' => $this->input->post('gst_no'),
                    'aadhar_no' => $this->input->post('aadhar_no'),
                    'mobile' => $this->input->post('mobile'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'), 
                    'address' => $this->input->post('address'),
                    'state_code' => $this->input->post('state_code'),
                    'city_code' => $this->input->post('city_code'),
                    'pincode' => $this->input->post('pincode'),
                    'status' => $this->input->post('status'),
                    'franchise_type_id' => $this->input->post('franchise_type_id'),
                    'franchise_id' => $this->input->post('franchise_id'),
                    'updated_by' => $this->session->userdata('cr_user_id'),                          
                    'updated_datetime' => date('Y-m-d H:i:s')                 
            );
            
            $this->db->where('customer_id', $this->input->post('customer_id'));
            $this->db->update('crit_customer_info', $upd); 
                            
            redirect('customer-list/' . $this->uri->segment(2, 0)); 
        } 
        
        if($this->input->post('mode') == 'Add Contact Group')
        {
            $ins = array(
                    'customer_id' => $this->input->post('customer_id'),
                    'cc_code' => $this->input->post('cc_code'),
                    'customer_group' => $this->input->post('customer_group'),
                    'company' => $this->input->post('company'),
                    'contact_person' => $this->input->post('contact_person'),
                    'gst_no' => $this->input->post('gst_no'),
                    'aadhar_no' => $this->input->post('aadhar_no'),
                    'mobile' => $this->input->post('mobile'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'), 
                    'address' => $this->input->post('address'),
                    'state_code' => $this->input->post('state_code'),
                    'city_code' => $this->input->post('city_code'),
                    'pincode' => $this->input->post('pincode'),
                    'status' => $this->input->post('status'), 
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                           
            );
            
            $this->db->insert('crit_customer_contact_info', $ins); 
            redirect('customer-list');
        }
        
        if($this->input->post('mode') == 'Edit Contact Group')
        {
            $upd = array( 
                    'cc_code' => $this->input->post('cc_code'),
                    'customer_group' => $this->input->post('customer_group'),
                    'company' => $this->input->post('company'),
                    'contact_person' => $this->input->post('contact_person'),
                    'gst_no' => $this->input->post('gst_no'),
                    'aadhar_no' => $this->input->post('aadhar_no'),
                    'mobile' => $this->input->post('mobile'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'), 
                    'address' => $this->input->post('address'),
                    'state_code' => $this->input->post('state_code'),
                    'city_code' => $this->input->post('city_code'),
                    'pincode' => $this->input->post('pincode'),
                    'status' => $this->input->post('status'), 
                    'updated_by' => $this->session->userdata('cr_user_id'),                          
                    'updated_datetime' => date('Y-m-d H:i:s')                           
            );
            
            $this->db->where('customer_contact_id', $this->input->post('customer_contact_id')); 
            $this->db->update('crit_customer_contact_info', $upd); 
            
            redirect('customer-list/' . $this->uri->segment(2, 0)); 
        }
         
        
        $this->load->library('pagination');
        
       if(isset($_POST['srch_state'])) {
           $data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_key'] = $srch_key = $this->input->post('srch_key');
           $this->session->set_userdata('srch_state', $this->input->post('srch_state'));
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_state')){
           $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ; 
       }else {
           $data['srch_state'] = $srch_state = '';
       }
       
       if(isset($_POST['srch_key'])) { 
           $data['srch_key'] = $srch_key = $this->input->post('srch_key'); 
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_key')){ 
           $data['srch_key'] = $srch_key = $this->session->userdata('srch_key') ;
       } else {
         $data['srch_key'] = $srch_key = '';
       }
       
        
       
       $where = '1=1';
       
       if($this->session->userdata('cr_is_admin') == '11'){
         $where .= " and a.franchise_id = '". $this->session->userdata('cr_franchise_id') ."'";
       }

       
        
       if(!empty($srch_state)){
         $where .= " and a.state_code = '". $srch_state ."'";
       }  
       if(!empty($srch_key)) {
         $where .= " and ( 
                        a.customer_code like '%" . $srch_key . "%' or 
                        a.pincode like '%" . $srch_key . "%' or 
                        a.mobile like '%". $srch_key ."%' or 
                        a.contact_person like '%". $srch_key ."%' or 
                        a.company like '%". $srch_key ."%' or 
                        a.email like '%". $srch_key ."%' or 
                        a.phone like '%". $srch_key ."%'
                        ) ";
         
       } 
        
        
        $this->db->where('status != ', 'Delete');
        //if(!empty($srch_key))
            $this->db->where($where);
        $this->db->from('crit_customer_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('customer-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.customer_id, 
                a.customer_code,
                a.customer_type_id,
                b.customer_type_name,
                a.company, 
                a.contact_person, 
                a.mobile, 
                a.phone, 
                a.email,  
                a.address, 
                a.state_code, 
                a.city_code, 
                a.pincode, 
                a.`status`,
                c.contact_person as franchise
                from crit_customer_info as a 
                left join crit_customer_type_info as b on b.customer_type_id = a.customer_type_id 
                left join crit_franchise_info as c on c.franchise_id = a.franchise_id  
                where a.status != 'Delete' 
                and ". $where ."
                order by a.status asc , a.contact_person asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $sql = "
                select 
                a.state_name,                
                a.state_code             
                from crit_states_info as a  
                where status = 'Active' 
                order by a.state_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_code']] = $row['state_name']. ' [ ' . $row['state_code'] . ' ]';     
        }
        
        
        $sql = "
                select 
                a.customer_type_id,                
                a.customer_type_name             
                from crit_customer_type_info as a  
                where status = 'Active' 
                order by a.customer_type_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['customer_type_opt'][$row['customer_type_id']] = $row['customer_type_name'];     
        }
        
        $sql = "
                select 
                a.franchise_type_id,                
                a.franchise_type_name             
                from crit_franchise_type_info as a  
                where status = 'Active' 
                order by a.franchise_type_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['franchise_type_opt'][$row['franchise_type_id']] = $row['franchise_type_name'];     
        }
        
        $sql = "
                select               
                a.customer_group_name             
                from crit_customer_group_info as a  
                where status = 'Active' 
                order by a.customer_group_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['customer_group_opt'][$row['customer_group_name']] = $row['customer_group_name'];     
        }
         
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/customer-list',$data); 
	} 
    
    public function customer_domestic_rate_v2($customer_id) 
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN && $this->session->userdata('m_is_admin') != USER_MANAGER ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        if($this->input->post('mode') == 'Add Rate')
        {
            $ins = array(
                    'customer_id' => $customer_id,
                    'c_type' => $this->input->post('c_type'),
                    'from_weight' => $this->input->post('from_weight'),
                    'to_weight' => $this->input->post('to_weight'),
                    'rate_as_on' => date('Y-m-d'),
                    'flg_region' => ($this->input->post('flg_region') == 1 ?  1 : 0),
                    'flg_state' => ($this->input->post('flg_state') == 1 ?  1 : 0),
                    'flg_city' => ($this->input->post('flg_city') == 1 ?  1 : 0), 
                    'flg_metro' => ($this->input->post('flg_metro') == 1 ?  1 : 0), 
                    'min_weight' => $this->input->post('min_weight'), 
                    'min_charges' => $this->input->post('min_charges'),
                    'addt_weight' => $this->input->post('addt_weight'),
                    'addt_charges' => $this->input->post('addt_charges'),
                    'status' => $this->input->post('status'), 
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                           
            );
            
            $this->db->insert('crit_customer_domestic_rate_info_v2', $ins); 
            redirect('customer-domestic-rate-v2/'.$customer_id);
        }
        
        $data['js'] = 'customer-domestic-rate-v2.inc';  
        
       
            
          $sql = "
                    select 
                    a.customer_domestic_rate_id, 
                    a.customer_id, 
                    a.c_type,
                    a.from_weight,
                    a.to_weight,
                    concat(a.c_type,' [ ', a.from_weight,' - ', a.to_weight,'Kgs ]') as rate_slap, 
                    a.rate_as_on,
                    a.flg_region, 
                    a.flg_state, 
                    a.flg_city, 
                    a.flg_metro, 
                    a.min_weight, 
                    a.min_charges,
                    a.addt_weight,  
                    a.addt_charges,  
                    a.`status`
                    from crit_customer_domestic_rate_info_v2 as a  
                    where a.status = 'Active' and a.customer_id = $customer_id
                    order by a.status asc ,a.c_type , a.from_weight, a.flg_state desc , a.flg_city desc             
            "; 
            
            $query = $this->db->query($sql);
            $data['record_list'] = array();
            foreach ($query->result_array() as $row)
            {
                $data['record_list'][$row['rate_slap']][] = $row;     
            }  
        
        
         
        $this->load->view('page/customer-domestic-rate-v2',$data); 
	}
    
    public function customer_domestic_rate($customer_id) 
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN && $this->session->userdata('m_is_admin') != USER_MANAGER ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'customer-domestic-rate.inc';    
        
       
         
       if(!empty($customer_id)) {          
            $this->db->where('customer_id = ', $customer_id);
            $this->db->from('crit_customer_domestic_rate_info');
            $cnt  = $this->db->count_all_results();
            if($cnt == 0 ) {
            $ins_sql = "insert into crit_customer_domestic_rate_info ( 
                        select
                        '' as id,  
                         '".$customer_id ."' as fr_id,  
                         c_type, 
                         current_date() rate_as_on, 
                         flg_region, 
                         flg_state, 
                         flg_city,
                         flg_metro,
                         min_weight, 
                         min_charges, 
                         addt_weight, 
                         addt_charges, 
                         `status`, 
                         '". $this->session->userdata('cr_user_id') ."' as created_by, 
                         now() created_datetime,
                         '' as updated_by,
                         '' as update_datetime
                        from crit_domestic_rate_info 
                        where status = 'Active' and p_type= 'Premium Express'
                        )";
             $this->db->query($ins_sql);  
                     
                        
            }  
            
            $sql = "
                    select 
                    a.customer_domestic_rate_id, 
                    a.customer_id, 
                    a.c_type,
                    a.rate_as_on,
                    a.flg_region, 
                    a.flg_state, 
                    a.flg_city, 
                    a.flg_metro, 
                    a.min_weight, 
                    a.min_charges, 
                    a.addt_weight, 
                    a.addt_charges, 
                    a.`status`
                    from crit_customer_domestic_rate_info as a  
                    where a.status = 'Active' and a.customer_id = $customer_id
                    order by a.status asc ,a.c_type ,  a.flg_state desc , a.flg_city desc             
            "; 
            
            $query = $this->db->query($sql);
            $data['record_list'] = array();
            foreach ($query->result_array() as $row)
            {
                $data['record_list'][$row['c_type']][] = $row;     
            } 
           
         
       } else { 
            
            $data['record_list']['Air'] = array();
            $data['record_list']['Surface'] = array();
            
             
       } 
        
         
        $this->load->view('page/customer-domestic-rate',$data); 
	}
 
    
    
    public function carrier_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'carrier.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'carrier_name' => $this->input->post('carrier_name'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_carrier_info', $ins); 
            redirect('carrier-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'carrier_name' => $this->input->post('carrier_name'),
                    'status' => $this->input->post('status')  ,                 
            );
            
            $this->db->where('carrier_id', $this->input->post('carrier_id'));
            $this->db->update('crit_carrier_info', $upd); 
                            
            redirect('carrier-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_carrier_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('carrier-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.carrier_id,
                a.carrier_name,                
                a.status
                from crit_carrier_info as a 
                where status != 'Delete'
                order by a.status asc , a.carrier_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        $data['record_list'] = array();
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/carrier-list',$data); 
	} 
    
    public function doc_upload_type_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'doc-upload-type.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'doc_upload_type_name' => $this->input->post('doc_upload_type_name'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_doc_upload_type_info', $ins); 
            redirect('doc-upload-type-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'doc_upload_type_name' => $this->input->post('doc_upload_type_name'),
                    'status' => $this->input->post('status')  ,                 
            );
            
            $this->db->where('doc_upload_type_id', $this->input->post('doc_upload_type_id'));
            $this->db->update('crit_doc_upload_type_info', $upd); 
                            
            redirect('doc-upload-type-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_doc_upload_type_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('carrier-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.doc_upload_type_id,
                a.doc_upload_type_name,                
                a.status
                from crit_doc_upload_type_info as a 
                where status != 'Delete'
                order by a.status asc , a.doc_upload_type_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        $data['record_list'] = array();
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/doc-upload-type-list',$data); 
	} 
    
    
    public function hub_branch_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'hub-branch.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'type' => $this->input->post('type'),
                    'hub_branch_name' => $this->input->post('hub_branch_name'),
                    'hub_branch_code' => $this->input->post('hub_branch_code'),
                    'status' => $this->input->post('status')  ,
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                          
            );
            
            $this->db->insert('crit_hub_branch_info', $ins); 
            redirect('hub-branch-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'type' => $this->input->post('type'),
                    'hub_branch_name' => $this->input->post('hub_branch_name'),
                    'hub_branch_code' => $this->input->post('hub_branch_code'),
                    'status' => $this->input->post('status')  , 
                    'updated_by' => $this->session->userdata('cr_user_id'),                          
                    'updated_datetime' => date('Y-m-d H:i:s')                 
            );
            
            $this->db->where('hub_branch_id', $this->input->post('hub_branch_id'));
            $this->db->update('crit_hub_branch_info', $upd); 
                            
            redirect('hub-branch-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
       if(isset($_POST['srch_type'])) {
           $data['srch_type'] = $srch_type = $this->input->post('srch_type');  
           $this->session->set_userdata('srch_type', $this->input->post('srch_type')); 
       }
       elseif($this->session->userdata('srch_type')){
           $data['srch_type'] = $srch_type = $this->session->userdata('srch_type') ; 
       } else {
             $data['srch_type'] = $srch_type = 'Branch';  
       } 
       
       if(isset($_POST['srch_key'])) {
           $data['srch_key'] = $srch_key = $this->input->post('srch_key');  
           $this->session->set_userdata('srch_key', $this->input->post('srch_key')); 
       }
       elseif($this->session->userdata('srch_key')){
           $data['srch_key'] = $srch_key = $this->session->userdata('srch_key') ; 
       } else {
             $data['srch_key'] = $srch_key = '';  
       }
       $where = ' 1=1 ';
       if(!empty($srch_type)){
        $where .= " and a.type = '" . $srch_type . "'";  
        $data['srch_type'] = $srch_type;
        $data['submit_flg'] = true; 
       } 
       if(!empty($srch_key)){
        $where .= "and ( a.hub_branch_name like '%" . $srch_key . "%' or a.hub_branch_code like '%" . $srch_key . "%' )";  
        $data['submit_flg'] = true; 
       }  
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->where($where); 
        $this->db->from('crit_hub_branch_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('hub-branch-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.hub_branch_id,
                a.type,                
                a.hub_branch_name,                
                a.hub_branch_code,                
                a.status
                from crit_hub_branch_info as a 
                where status != 'Delete'
                and $where
                order by a.status asc , a.hub_branch_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
         
        $query = $this->db->query($sql);
       
        $data['record_list'] = array();
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/hub-branch-list',$data); 
	} 
    
    public function ndr_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'ndr.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'ndr_code' => $this->input->post('ndr_code'),
                    'ndr_details' => $this->input->post('ndr_details'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_ndr_info', $ins); 
            redirect('ndr-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'ndr_code' => $this->input->post('ndr_code'),
                    'ndr_details' => $this->input->post('ndr_details'),
                    'status' => $this->input->post('status')  ,                 
            );
            
            $this->db->where('ndr_id', $this->input->post('ndr_id'));
            $this->db->update('crit_ndr_info', $upd); 
                            
            redirect('ndr-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_ndr_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('ndr-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.ndr_id,
                a.ndr_code,                
                a.ndr_details,                
                a.status
                from crit_ndr_info as a 
                where status != 'Delete'
                order by a.status asc , a.ndr_code asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        $data['record_list'] = array();
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/ndr-list',$data); 
	} 
    
    public function service_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'service.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'service_name' => $this->input->post('service_name'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_service_info', $ins); 
            redirect('service-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'service_name' => $this->input->post('service_name'),
                    'status' => $this->input->post('status')  ,                 
            );
            
            $this->db->where('service_id', $this->input->post('service_id'));
            $this->db->update('crit_service_info', $upd); 
                            
            redirect('service-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_service_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('service-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.service_id,
                a.service_name,                
                a.status
                from crit_service_info as a 
                where status != 'Delete'
                order by a.status asc , a.service_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        $data['record_list'] = array();
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/service-list',$data); 
	} 
    
    public function package_type_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'package-type.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'package_type_name' => $this->input->post('package_type_name'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_package_type_info', $ins); 
            redirect('package-type-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'package_type_name' => $this->input->post('package_type_name'),
                    'status' => $this->input->post('status'),               
            );
            
            $this->db->where('package_type_id', $this->input->post('package_type_id'));
            $this->db->update('crit_package_type_info', $upd); 
                            
            redirect('package-type-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_package_type_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('package-type-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.package_type_id,
                a.package_type_name,                
                a.status
                from crit_package_type_info as a 
                where status != 'Delete'
                order by a.status asc , a.package_type_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        $data['record_list'] = array();
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/package-type-list',$data); 
	} 
    
    public function product_type_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'product-type.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'product_type_name' => $this->input->post('product_type_name'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_product_type_info', $ins); 
            redirect('product-type-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'product_type_name' => $this->input->post('product_type_name'),
                    'status' => $this->input->post('status'),               
            );
            
            $this->db->where('product_type_id', $this->input->post('product_type_id'));
            $this->db->update('crit_product_type_info', $upd); 
                            
            redirect('product-type-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_product_type_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('product-type-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.product_type_id,
                a.product_type_name,                
                a.status
                from crit_product_type_info as a 
                where status != 'Delete'
                order by a.status asc , a.product_type_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        $data['record_list'] = array();
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/product-type-list',$data); 
	} 
    
    public function tracking_status_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'tracking-status.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'tracking_status' => $this->input->post('tracking_status'),
                    'status' => $this->input->post('status'),                          
                    'sort' => $this->input->post('sort'),                          
            );
            
            $this->db->insert('crit_tracking_status_info', $ins); 
            redirect('tracking-status-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'tracking_status' => $this->input->post('tracking_status'),
                    'status' => $this->input->post('status')  ,                 
                    'sort' => $this->input->post('sort')  ,                 
            );
            
            $this->db->where('tracking_status_id', $this->input->post('tracking_status_id'));
            $this->db->update('crit_tracking_status_info', $upd); 
                            
            redirect('tracking-status-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_tracking_status_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('tracking-status-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.tracking_status_id,
                a.tracking_status,                
                a.status,
                a.sort
                from crit_tracking_status_info as a 
                where status != 'Delete'
                order by a.sort asc , a.status asc , a.tracking_status asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        $data['record_list'] = array();
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/tracking-status-list',$data); 
	} 
    
    
    public function customer_type_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'customer-type.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'customer_type_name' => $this->input->post('customer_type_name'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_customer_type_info', $ins); 
            redirect('customer-type-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'customer_type_name' => $this->input->post('product_type_name'),
                    'status' => $this->input->post('status')  ,             
            );
            
            $this->db->where('customer_type_id', $this->input->post('customer_type_id'));
            $this->db->update('crit_customer_type_info', $upd); 
                            
            redirect('customer-type-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_customer_type_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('customer-type-list/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.customer_type_id,
                a.customer_type_name,                
                a.status
                from crit_customer_type_info as a 
                where status != 'Delete'
                order by a.status asc , a.customer_type_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        $data['record_list'] = array();
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/customer-type-list',$data); 
	} 
    
    public function domestic_rate() 
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN && $this->session->userdata('m_is_admin') != USER_MANAGER ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'domestic.inc';  
        
     
        
        if($this->input->post('mode') == 'Edit' && ($this->input->post('domestic_rate_id')!= ''))
        {
            $ins = array(
                    'flg_region' => ($this->input->post('flg_region')== '' ? 0 : $this->input->post('flg_region') ),
                    'flg_state' => ($this->input->post('flg_state')== '' ? 0 : $this->input->post('flg_state')),
                    'flg_city' => ($this->input->post('flg_city')== '' ? 0 : $this->input->post('flg_city')), 
                    'flg_metro' => ($this->input->post('flg_metro')== '' ? 0 : $this->input->post('flg_metro')),  
                    'min_weight' => $this->input->post('min_weight'),
                    'min_charges' => $this->input->post('min_charges'),
                    'addt_weight' => $this->input->post('addt_weight'),
                    'addt_charges' => $this->input->post('addt_charges'),   
                    'addt_weight_1' => $this->input->post('addt_weight_1'),           
                    'addt_charges_1' => $this->input->post('addt_charges_1'),              
                    'c_type' => $this->input->post('c_type'),              
                    'p_type' => $this->input->post('p_type'),              
                    'rate_as_on' => date('Y-m-d'),
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')             
            );
            
            $this->db->where('domestic_rate_id', $this->input->post('domestic_rate_id'));
            $this->db->update('crit_domestic_rate_info', array(
                                                                'status' => 'In-Active' ,
                                                                'updated_by' => $this->session->userdata('cr_user_id'),                          
                                                                'update_datetime' => date('Y-m-d H:i:s')   
                                                               )); 
            
            $this->db->insert('crit_domestic_rate_info', $ins); 
            
                            
            redirect('domestic-rate'); 
        } 
         
        
        $this->load->library('pagination');
        
        $this->db->where('status = ', 'Active');
        $this->db->from('crit_domestic_rate_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('domestic-rate/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
                a.domestic_rate_id,
                a.p_type,
                a.c_type,
                a.rate_as_on,
                a.flg_region, 
                a.flg_state, 
                a.flg_city, 
                a.flg_metro, 
                a.min_weight, 
                a.min_charges, 
                a.addt_weight, 
                a.addt_charges, 
                a.addt_weight_1, 
                a.addt_charges_1, 
                a.`status`
                from crit_domestic_rate_info as a 
                where a.status = 'Active' and a.p_type = 'Business Express'
                order by a.c_type , a.p_type desc ,   a.flg_region desc, a.flg_state desc , a.flg_city desc ,a.flg_metro  desc
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            //$data['record_list'][$row['p_type'] . '-' . $row['c_type']][] = $row;     
            $data['record_list'][$row['c_type']][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/domestic-rate',$data); 
	}
    
    
    public function franchise_domestic_rate() 
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN && $this->session->userdata('m_is_admin') != USER_MANAGER ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'franchise-domestic-rate.inc';   
        
         
        
        
       if(isset($_POST['srch_franchise_type'])) { 
         $data['srch_franchise_type'] = $srch_franchise_type = $this->input->post('srch_franchise_type'); 
       } else {
         $data['srch_franchise_type'] = $srch_franchise_type = '';
       }
        
       if(isset($_POST['srch_state'])) {
         $data['srch_state'] = $srch_state = $this->input->post('srch_state'); 
       } else {
         $data['srch_state'] = $srch_state = '';
       } 
       
       if(isset($_POST['srch_franchise_id'])) { 
          $data['srch_franchise_id'] = $srch_franchise_id = $this->input->post('srch_franchise_id');  
       } else {
          $data['srch_franchise_id'] = $srch_franchise_id = '';
       }
       
       $where = '1';
       $where1 = '1';

       if(!empty($srch_franchise_type)){
         //$where1 .= " and a.franchise_type_id = '". $srch_franchise_type ."'";
         $data['franchise_opt'] = array();
       } 
       if(!empty($srch_state)){
         //$where1 .= " and a.state_code = '". $srch_state ."'";
         $data['franchise_opt'] = array();
       }  
       if(!empty($srch_franchise_id)) {
         $where .= " and a.franchise_id = " .$srch_franchise_id; 
         
            $this->db->where('franchise_id = ', $srch_franchise_id);
            $this->db->from('crit_franchise_domestic_rate_info');
            $cnt  = $this->db->count_all_results();
            if($cnt == 0 ) {
             $ins_sql = "insert into crit_franchise_domestic_rate_info ( 
                        select
                         0 as id,  
                         '".$srch_franchise_id ."' as fr_id,  
                         c_type, 
                         current_date() rate_as_on, 
                         flg_region,
                         flg_state, 
                         flg_city,
                         flg_metro,
                         min_weight, 
                         min_charges, 
                         addt_weight, 
                         addt_charges, 
                         `status`, 
                         '". $this->session->userdata('cr_user_id') ."' as created_by, 
                         now() created_datetime,
                         '0' as updated_by,
                         now() as update_datetime
                        from crit_domestic_rate_info 
                        where status = 'Active'
                        and p_type = 'Business Express'
                        )";
            $this->db->query($ins_sql);  
                     
                        
            }  
            
           $sql = "
                    select 
                    a.franchise_domestic_rate_id, 
                    a.franchise_id,
                    c.contact_person,
                    a.c_type,
                    a.rate_as_on,
                    a.flg_state, 
                    a.flg_city, 
                    a.min_weight, 
                    a.min_charges, 
                    a.addt_weight, 
                    a.addt_charges, 
                    a.`status`
                    from crit_franchise_domestic_rate_info as a 
                    left join crit_franchise_info as c on c.franchise_id = a.franchise_id 
                    where a.status = 'Active' and $where 
                    order by a.status asc ,a.c_type ,  a.flg_state desc , a.flg_city desc             
            "; 
            
            $query = $this->db->query($sql);
            $data['record_list'] = array();
            foreach ($query->result_array() as $row)
            {
                $data['record_list'][$row['c_type']][] = $row;     
            }
                      
         
            $sql = "select 
                    a.franchise_id,   
                    a.contact_person, 
                    a.mobile,
                    a.city_code    
                    from crit_franchise_info as a 
                    where a.state_code = '". $srch_state ."'
                    and a.franchise_type_id = '". $srch_franchise_type ."'
                    and a.status = 'Active' 
                    order by a.contact_person asc
                    ";
          
            $query = $this->db->query($sql);
       
            foreach ($query->result_array() as $row)
            {
                $data['franchise_opt'][$row['franchise_id']] = $row['contact_person']. " [ " . $row['mobile'] . " ] [" . $row['city_code'] . " ] " ;     
            }      
         
       } else {
            //$data['franchise_opt'] = array();
            
            $data['record_list']['Air'] = array();
            $data['record_list']['Surface'] = array();
            
            if(!empty($srch_franchise_type)) {
             $sql = "select 
                    a.franchise_id,   
                    a.contact_person, 
                    a.mobile,
                    a.city_code    
                    from crit_franchise_info as a 
                    where a.state_code = '". $srch_state ."'
                    and a.franchise_type_id = '". $srch_franchise_type ."'
                    and a.status = 'Active'";
          
            $query = $this->db->query($sql);
       
            foreach ($query->result_array() as $row)
            {
                $data['franchise_opt'][$row['franchise_id']] = $row['contact_person']. " [ " . $row['mobile'] . " ] [" . $row['city_code'] . " ] " ;     
            } 
            } else {
                $data['franchise_opt'] = array();
            }
       }
        
            
        
        
        
        $sql = "
                select 
                a.franchise_type_id,                
                a.franchise_type_name             
                from crit_franchise_type_info as a  
                where status = 'Active' 
                order by a.franchise_type_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['franchise_type_opt'][$row['franchise_type_id']] = $row['franchise_type_name'];     
        }
        
        $sql = "
                select 
                a.state_name,                
                a.state_code             
                from crit_states_info as a  
                where status = 'Active'
                and exists ( select * from crit_franchise_info where state_code = a.state_code )
                order by a.state_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_code']] = $row['state_name']. ' [ ' . $row['state_code'] . ' ]';     
        }
        
        
         
        $this->load->view('page/franchise-domestic-rate',$data); 
	}
    
    public function franchises_transhipment_rate() 
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
         
        	    
        $data['js'] = 'franchises-transhipment-rate.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'flg_region' => ($this->input->post('flg_region') == 1 ?  1 : 0),
                    'flg_state' => ($this->input->post('flg_state') == 1 ?  1 : 0),
                    'flg_city' => ($this->input->post('flg_city') == 1 ?  1 : 0), 
                    'flg_metro' => ($this->input->post('flg_metro') == 1 ?  1 : 0),   
                    'min_charges' => $this->input->post('min_charges'),           
                    'delivery_charges' => $this->input->post('delivery_charges'),           
                    'c_type' => $this->input->post('c_type'),              
                    'from_weight' => $this->input->post('from_weight'),              
                    'to_weight' => $this->input->post('to_weight'),              
                    'rate_as_on' => date('Y-m-d'),
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')              
            );
            
            $this->db->insert('crit_transhipment_rate_info', $ins); 
                            
            redirect('transhipment-rate'); 
        } 
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'flg_region' => ($this->input->post('flg_region') == 1 ?  1 : 0),
                    'flg_state' => ($this->input->post('flg_state') == 1 ?  1 : 0),
                    'flg_city' => ($this->input->post('flg_city') == 1 ?  1 : 0), 
                    'flg_metro' => ($this->input->post('flg_metro') == 1 ?  1 : 0),   
                    'min_charges' => $this->input->post('min_charges'),           
                    'delivery_charges' => $this->input->post('delivery_charges'),           
                    'c_type' => $this->input->post('c_type'),              
                    'from_weight' => $this->input->post('from_weight'),              
                    'to_weight' => $this->input->post('to_weight'),              
                    'rate_as_on' => date('Y-m-d')  ,
                    'updated_by' => $this->session->userdata('cr_user_id'),                          
                    'update_datetime' => date('Y-m-d H:i:s')              
            );
            
            $this->db->where('transhipment_rate_id', $this->input->post('transhipment_rate_id'));
            $this->db->update('crit_transhipment_rate_info', $upd); 
                            
            redirect('transhipment-rate');  
        } 
         
         
        
        $sql = "
                select 
                a.transhipment_rate_id, 
                a.c_type,
                a.from_weight,
                a.to_weight,
                a.rate_as_on,
                a.flg_region, 
                a.flg_state, 
                a.flg_city, 
                a.flg_metro, 
                a.min_charges,  
                a.delivery_charges,  
                a.`status`
                from crit_transhipment_rate_info as a 
                where status = 'Active'
                order by a.c_type,a.from_weight , a.to_weight ,a.flg_region asc , a.flg_state asc , a.flg_city ,a.flg_metro asc                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][$row['c_type']][$row['from_weight'] .' - ' . $row['to_weight']][] = $row;     
        } 
        
        $this->load->view('page/franchises-transhipment-rate',$data); 
	}
    
    public function co_loader_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'co-loader.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'co_loader_name' => $this->input->post('co_loader_name'),
                    'status' => $this->input->post('status')  ,  
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                          
            );
            
            $this->db->insert('crit_co_loader_info', $ins); 
            redirect('co-loader-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'co_loader_name' => $this->input->post('co_loader_name'),
                    'status' => $this->input->post('status'),                 
            );
            
            $this->db->where('co_loader_id', $this->input->post('co_loader_id'));
            $this->db->update('crit_co_loader_info', $upd); 
                            
            redirect('co-loader-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
       
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_co_loader_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('co-loader-list/'), '/'. $this->uri->segment(2, 0));
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
                a.co_loader_id,
                a.co_loader_name,                
                a.status
                from crit_co_loader_info as a 
                where status != 'Delete'
                order by a.status asc , a.co_loader_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/co-loader-list',$data); 
	} 
    
    public function co_courier_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        }  */
        	    
        $data['js'] = 'co-courier.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'co_courier_name' => $this->input->post('co_courier_name'),
                    'status' => $this->input->post('status')  ,  
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                          
            );
            
            $this->db->insert('crit_co_courier_info', $ins); 
            redirect('co-courier-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'co_courier_name' => $this->input->post('co_courier_name'),
                    'status' => $this->input->post('status'),                 
            );
            
            $this->db->where('co_courier_id', $this->input->post('co_courier_id'));
            $this->db->update('crit_co_courier_info', $upd); 
                            
            redirect('co-courier-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
       
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_co_courier_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('co-courier-list/'), '/'. $this->uri->segment(2, 0));
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
                a.co_courier_id,
                a.co_courier_name,                
                a.status
                from crit_co_courier_info as a 
                where status != 'Delete'
                order by a.status asc , a.co_courier_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/co-courier-list',$data); 
	}
    
    public function co_courier_drs_status_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        }  */
        	    
        $data['js'] = 'co-courier-drs-status.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'co_courier_drs_status_name' => $this->input->post('co_courier_drs_status_name'),
                    'status' => $this->input->post('status')                          
            );
            
            $this->db->insert('co_courier_drs_status_info', $ins); 
            redirect('co-courier-drs-status-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'co_courier_drs_status_name' => $this->input->post('co_courier_drs_status_name'),
                    'status' => $this->input->post('status')                  
            );
            
            $this->db->where('co_courier_drs_status_id', $this->input->post('co_courier_drs_status_id'));
            $this->db->update('co_courier_drs_status_info', $upd); 
                            
            redirect('co-courier-drs-status-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
       
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_co_courier_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('co-courier-drs-status-list/'), '/'. $this->uri->segment(2, 0));
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
                a.co_courier_drs_status_id,
                a.co_courier_drs_status_name,                
                a.status
                from co_courier_drs_status_info as a 
                where status != 'Delete'
                order by a.status asc , a.co_courier_drs_status_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
       
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/co-courier-drs-status-list',$data); 
	}
    
    
    public function notice_board_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'notice-board-list.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'notice_date' => $this->input->post('notice_date'),
                    'notice_msg' => $this->input->post('notice_msg'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('notice_board_info', $ins); 
            redirect('notice-board-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'notice_date' => $this->input->post('notice_date'),
                    'notice_msg' => $this->input->post('notice_msg'),
                    'status' => $this->input->post('status')  ,                 
            );
            
            $this->db->where('notice_board_id', $this->input->post('notice_board_id'));
            $this->db->update('notice_board_info', $upd); 
                            
            redirect('notice-board-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('notice_board_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('notice-board-listt/'), '/'. $this->uri->segment(2, 0));
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
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.*
                from notice_board_info as a 
                where status != 'Delete'
                order by a.status asc , a.notice_date desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        $data['record_list'] = array();
        
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/notice-board-list',$data); 
	} 
}
