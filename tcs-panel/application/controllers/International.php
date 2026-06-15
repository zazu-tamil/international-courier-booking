<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class International extends CI_Controller {
    
    public function package_weight_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'international/package_weight.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'package_type_id' => $this->input->post('package_type_id'),
                    'package_weight_range' => $this->input->post('package_weight_range'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_package_weight_info', $ins); 
            redirect('package-weight-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'package_type_id' => $this->input->post('package_type_id'),
                    'package_weight_range' => $this->input->post('package_weight_range'),
                    'status' => $this->input->post('status'),                 
            );
            
            $this->db->where('package_weight_id', $this->input->post('package_weight_id'));
            $this->db->update('crit_package_weight_info', $upd); 
                            
            redirect('package-weight-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination'); 
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_package_weight_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('package-weight-list/'), '/'. $this->uri->segment(2, 0));
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
                a.package_weight_id,
                b.package_type_name,
                a.package_weight_range,                
                a.status
                from crit_package_weight_info as a 
                left join crit_package_type_info as b on b.package_type_id = a.package_type_id
                where a.status != 'Delete'
                order by a.status asc , b.package_type_name, a.package_weight_range asc 
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
                a.package_type_id,                
                a.package_type_name             
                from crit_package_type_info as a  
                where status = 'Active' 
                order by a.package_type_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['package_type_opt'][$row['package_type_id']] = $row['package_type_name'] ;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/international/package-weight-list',$data); 
	} 
    
    public function service_provider_charges()
    {
        if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        if($this->session->userdata('cr_is_admin') != '1')  
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        }  
        	    
        $data['js'] = 'international/service_provider_charges.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'service_provider' => $this->input->post('service_provider'), 
                    'gst' => $this->input->post('gst'),
                    'fsc' => $this->input->post('fsc'), 
                    'addt_sc' => $this->input->post('addt_sc')  ,                          
                    'last_updated_date' => date('Y-m-d H:i:s')  ,                          
            );
            
            $this->db->insert('crit_intl_setting_info_v2', $ins); 
            redirect('service-provider-charges');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'service_provider' => $this->input->post('service_provider'), 
                    'gst' => $this->input->post('gst'),
                    'fsc' => $this->input->post('fsc'), 
                    'addt_sc' => $this->input->post('addt_sc')  ,                          
                    'last_updated_date' => date('Y-m-d H:i:s')  ,                          
            );
            
            $this->db->where('intl_setting_id',$this->input->post('intl_setting_id') ); 
            $this->db->update('crit_intl_setting_info_v2', $upd); 
            redirect('service-provider-charges');
        }
        
        if($this->input->post('mode') == 'Add Margin')
        {
           //echo "<pre>";
           //print_r($_POST);
           //echo "</pre>";
           
           $intl_setting_id = $this->input->post('intl_setting_id');
           
           $this->db->where('intl_setting_id', $intl_setting_id); 
           $this->db->delete('crit_intl_fr_ty_margin_info'); 
           
           $franchise_type_ids = $this->input->post('franchise_type_id');
           $margin = $this->input->post('margin');
           
           foreach($franchise_type_ids as $id => $fy){
                $ins = array(
                        'intl_setting_id' => $intl_setting_id, 
                        'franchise_type_id' => $fy,          
                        'margin' => $margin[$fy],          
                ); 
               $this->db->insert('crit_intl_fr_ty_margin_info', $ins); 
           }
           
           redirect('service-provider-charges'); 
           
        } 
         
        
         
        
        $sql = "
                select 
                a.service_provider,
                ifnull(b.intl_setting_id,0) as intl_setting_id,
                b.gst,
                b.fsc,
                b.addt_sc,
                b.last_updated_date
                from crit_international_rate_info_v2 as a  
                left join crit_intl_setting_info_v2 as b on b.service_provider = a.service_provider and b.status = 'Active'
                where a.service_provider != ''
                group by a.service_provider               
                order by a.service_provider asc               
        ";
         
        
        $query = $this->db->query($sql);
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
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
            $data['franchise_type_opt'][$row['franchise_type_id']] = $row['franchise_type_name'] ;     
        }
        
        $this->load->view('page/international/service-provider-charges',$data); 
    
    }
    
    
    public function service_provider_list()
    {
        if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'international/service_provider.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'international_service_provider' => $this->input->post('international_service_provider'), 
                    'logo_url' => $this->input->post('logo_url'),
                    'tracking_url' => $this->input->post('tracking_url'), 
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_international_service_provider_info', $ins); 
            redirect('service-provider-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'international_service_provider' => $this->input->post('international_service_provider'),
                    'logo_url' => $this->input->post('logo_url'),
                    'tracking_url' => $this->input->post('tracking_url'), 
                    'status' => $this->input->post('status')  ,         
            );
            
            $this->db->where('international_service_provider_id', $this->input->post('international_service_provider_id'));
            $this->db->update('crit_international_service_provider_info', $upd); 
                            
            redirect('service-provider-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination'); 
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_international_service_provider_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('service-provider-list/'), '/'. $this->uri->segment(2, 0));
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
                a.international_service_provider_id,
                a.international_service_provider, 
                a.logo_url,
                a.status
                from crit_international_service_provider_info as a  
                where a.status != 'Delete'
                order by a.status asc , a.international_service_provider asc 
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
        
        $this->load->view('page/international/service-provider-list',$data); 
    
    }
    
    public function international_rate_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'international/international-rate.inc';  
        
        if($this->input->post('mode') == 'setting')
        {
            $upd = array(
                    'gst' => $this->input->post('gst'),
                    'fsc' => $this->input->post('fsc'),
                    'last_updated_date' => date('Y-m-d H:i:s') , 
                    'status' => 'Active'  ,         
            );
            
            $this->db->where('intl_setting_id', $this->input->post('intl_setting_id'));
            $this->db->update('crit_intl_setting_info', $upd); 
            
            redirect('international-rate-list');
        }
        
        if($this->input->post('btn_save') == 'Save')
        {
            
            $country_ids = $this->input->post('country_id');
            $international_service_provider_ids = $this->input->post('international_service_provider_id');
            $package_type_ids = $this->input->post('package_type_id');
            $package_weight_ids = $this->input->post('package_weight_id');
            $rates = $this->input->post('rate');
            
            foreach($country_ids as $k => $country_id) {
            
                $this->db->where('country_id', $country_id);
                $this->db->where('international_service_provider_id', $international_service_provider_ids[$k]);
                $this->db->where('package_type_id', $package_type_ids[$k]);
                $this->db->where('package_weight_id', $package_weight_ids[$k]);
                $this->db->delete('crit_international_rate_info'); 
                
                $ins = array(
                        'country_id' => $country_id,
                        'international_service_provider_id' => $international_service_provider_ids[$k],
                        'package_type_id' => $package_type_ids[$k],
                        'package_weight_id' => $package_weight_ids[$k],
                        'rate' => $rates[$k], 
                        'status' => 'Active',                          
                );
                
                $this->db->insert('crit_international_rate_info', $ins); 
            
            }
            redirect('international-rate-list');
        }
        
       
       if(isset($_POST['srch_pkg_type'])) {
           $data['srch_pkg_type'] = $srch_pkg_type = $this->input->post('srch_pkg_type');
            
           $data['srch_service_provider_id'] = $srch_service_provider_id = $this->input->post('srch_service_provider_id');
           /*$this->session->set_userdata('srch_pkg_type', $this->input->post('srch_pkg_type'));
           $this->session->set_userdata('srch_pkg_weight', $this->input->post('srch_pkg_weight'));  
           $this->session->set_userdata('srch_service_provider_id', $this->input->post('srch_service_provider_id'));  */
       } else {
            $data['package_weight_opt'] = array();  
            $data['srch_service_provider_id'] = $srch_service_provider_id = '';
            $data['record_list'] = array();
            
       }  
       
       if(isset($_POST['srch_country_id'])) {
            $data['srch_country_id'] = $srch_country_id = $this->input->post('srch_country_id');  
            $this->session->set_userdata('srch_country_id', $this->input->post('srch_country_id'));
       } elseif($this->session->userdata('srch_country_id')){
           $data['srch_country_id'] = $srch_country_id = $this->session->userdata('srch_country_id') ;  
       } else {
            $data['srch_country_id'] = $srch_country_id = '';   
       }
       
       if(isset($_POST['srch_pkg_type'])) {
            $data['srch_pkg_type'] = $srch_pkg_type = $this->input->post('srch_pkg_type');  
            $this->session->set_userdata('srch_pkg_type', $this->input->post('srch_pkg_type'));
       } elseif($this->session->userdata('srch_pkg_type')){
           $data['srch_pkg_type'] = $srch_pkg_type = $this->session->userdata('srch_pkg_type') ;  
       } else {
            $data['srch_pkg_type'] = $srch_pkg_type = '';   
       }  
       
       if(isset($_POST['srch_pkg_weight'])) {
            $data['srch_pkg_weight'] = $srch_pkg_weight = $this->input->post('srch_pkg_weight');  
            $this->session->set_userdata('srch_pkg_weight', $this->input->post('srch_pkg_weight'));
       } elseif($this->session->userdata('srch_pkg_weight')){
           $data['srch_pkg_weight'] = $srch_pkg_weight = $this->session->userdata('srch_pkg_weight') ;  
       } else {
            $data['srch_pkg_weight'] = $srch_pkg_weight = '';   
       } 
       
       if(isset($_POST['srch_service_provider_id'])) {
            $data['srch_service_provider_id'] = $srch_service_provider_id = $this->input->post('srch_service_provider_id');
            $this->session->set_userdata('srch_service_provider_id', $this->input->post('srch_service_provider_id'));  
       }  elseif($this->session->userdata('srch_service_provider_id')){
           $data['srch_service_provider_id'] = $srch_service_provider_id = $this->session->userdata('srch_service_provider_id') ;  
       } else {
            $data['srch_service_provider_id'] = $srch_service_provider_id = '';   
       }
       
       
        
         if(!empty($srch_pkg_type)){
        
           $sql = "
                   select 
                    a.package_weight_id,
                    a.package_weight_range,
                    b.package_type_id,
                    b.package_type_name ,
                    e.country_id,
                    e.country_name,
                    c.international_service_provider_id,
                    c.international_service_provider , 
                    d.rate
                    from crit_package_weight_info as a 
                    left join crit_package_type_info as b on b.package_type_id = a.package_type_id and b.status = 'Active'
                    left join crit_international_service_provider_info as c on c.status = 'Active'
                    left join crit_country_info as e on e.status = 'Active'
                    left join crit_international_rate_info as d on d.international_service_provider_id = c.international_service_provider_id and d.package_type_id = b.package_type_id and d.package_weight_id = a.package_weight_id and d.`status` = 'Active' and d.country_id = e.country_id
                    where a.status = 'Active' and  b.status = 'Active' and  b.status = 'Active' 
                    and b.package_type_id =  '". $srch_pkg_type ."'
                    and e.country_id =  '". $srch_country_id ."'"; 
            if(!empty($srch_service_provider_id))
                $sql.=" and c.international_service_provider_id = '". $srch_service_provider_id ."'";
            if(!empty($srch_pkg_weight))
                $sql.=" and a.package_weight_id  = '". $srch_pkg_weight."'  ";    
                    
            $sql .="order by c.international_service_provider ,a.package_weight_range  asc  ";
            
             
            $query = $this->db->query($sql);
           
            $data['record_list'] = array();
            
            foreach ($query->result_array() as $row)
            {
                $data['record_list'][] = $row;     
            }
        
        
            $sql = "
                    select 
                    a.package_weight_id,
                    a.package_weight_range             
                    from crit_package_weight_info as a 
                    where a.status = 'Active' and a.package_type_id = $srch_pkg_type
                    order by a.package_weight_range asc                 
            "; 
            
            $query = $this->db->query($sql);
           
            foreach ($query->result_array() as $row)
            {
                $data['package_weight_opt'][$row['package_weight_id']] = $row['package_weight_range'];     
            } 
        }
        
        $sql = "
                select 
                a.package_type_id,                
                a.package_type_name             
                from crit_package_type_info as a  
                where status = 'Active' 
                order by a.package_type_id asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['package_type_opt'][$row['package_type_id']] = $row['package_type_name'] ;     
        }
        
        $sql = "
                select 
                a.country_id,              
                a.country_name              
                from crit_country_info as a  
                where status = 'Active'
                order by  a.country_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['country_opt'][$row['country_id']] = $row['country_name'];     
        }
        
        $sql = "
                select 
                a.international_service_provider_id,              
                a.international_service_provider              
                from crit_international_service_provider_info as a  
                where status = 'Active'
                order by  a.international_service_provider asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['service_provider_opt'][$row['international_service_provider_id']] = $row['international_service_provider'];     
        }
        
        
        $sql = "
                select 
                a.*            
                from crit_intl_setting_info as a  
                where status = 'Active'
                order by  a.last_updated_date asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['intl_setting_info'] = $row;     
        }
        
        
        
        
        
        //$data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/international/international-rate-list',$data); 
	} 
    
    
    public function international_rate_list_v2()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        if($this->session->userdata('cr_is_admin') != '1')  
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'international/international-rate-v2.inc';  
        
        //echo str_replace(' ','_', strtolower('Service Provider'));
        
        if($this->input->post('btn_import') == 'Import_xls')
        {
            $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
            if(isset($_FILES['xls_file']['name']) && in_array($_FILES['xls_file']['type'], $file_mimes)) {
                $arr_file = explode('.', $_FILES['xls_file']['name']);
                $extension = end($arr_file);
                if('csv' == $extension){
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                }elseif('xls' == $extension){
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                }else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                $spreadsheet = $reader->load($_FILES['xls_file']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(); 
               
                
                for($i=1;$i<count($sheetData);$i++)
                //'for($i=1;$i<10;$i++)
                {   //$ins1 = array();
                    
                    foreach($sheetData[$i] as $j => $val){
                        //$ins1 .= $sheetData[0][$j] .  " => " . $val .',';
                        //$ins1[$sheetData[0][$j]] =  $this->db->escape($val);
                        $fld = str_replace(' ','_', strtolower($sheetData[0][$j]));
                        $ins[$fld] =  $val ;
                    } 
                    $this->db->where('country',$ins['country']);
                    $this->db->where('service_provider',$ins['service_provider']);
                    $this->db->where('weight',$ins['weight']);
                    $query = $this->db->get('crit_international_rate_info_v2');
                    if ($query->num_rows() > 0){
                        //return true;
                        $this->db->where('country',$ins['country']);
                        $this->db->where('service_provider',$ins['service_provider']);
                        $this->db->where('weight',$ins['weight']);
                        $this->db->update('crit_international_rate_info_v2', array('base_rate' => $ins['base_rate'])); 
                    }
                    else{
                        //return false;
                        $this->db->insert('crit_international_rate_info_v2', $ins); 
                    }
                    
                    
                    
                } 
                redirect('international-rate-list-v2');
                /*echo "<pre>";
                print_r($ins);
                echo "</pre>";
                exit();*/
            }
        }
        
        
        if($this->input->post('mode') == 'setting')
        {
            $upd = array(
                    'gst' => $this->input->post('gst'),
                    'fsc' => $this->input->post('fsc'),
                    'last_updated_date' => date('Y-m-d H:i:s') , 
                    'status' => 'Active'  ,         
            );
            
            $this->db->where('intl_setting_id', $this->input->post('intl_setting_id'));
            $this->db->update('crit_intl_setting_info', $upd); 
            
            redirect('international-rate-list');
        }
        
        if($this->input->post('btn_save') == 'Save')
        {
            
            $countries = $this->input->post('country');
            $service_providers = $this->input->post('service_provider'); 
            $weights = $this->input->post('weight');
            $base_rates = $this->input->post('base_rate');
            
            foreach($countries as $k => $country) { 
                $this->db->where('country', $country);
                $this->db->where('service_provider', $service_providers[$k]); 
                $this->db->where('weight', $weights[$k]);
                $this->db->update('crit_international_rate_info_v2', array('base_rate' => $base_rates[$k]));  
            }
            redirect('international-rate-list-v2');
        }
        
       $data['flg'] = false; 
       
       if(isset($_POST['srch_country'])) {
            $data['srch_country'] = $srch_country = $this->input->post('srch_country');  
            $this->session->set_userdata('srch_country', $this->input->post('srch_country'));
       } elseif($this->session->userdata('srch_country')){
           $data['srch_country'] = $srch_country = $this->session->userdata('srch_country') ;
           $data['flg'] = true;  
       }  else {
            $data['srch_country'] = $srch_country = '';    
       } 
       
       if(isset($_POST['srch_pkg_weight'])) {
         $data['srch_pkg_weight'] = $srch_pkg_weight = $this->input->post('srch_pkg_weight');  
         $this->session->set_userdata('srch_pkg_weight', $this->input->post('srch_pkg_weight'));
         $data['flg'] = true;
       } elseif($this->session->userdata('srch_pkg_weight')){
           $data['srch_pkg_weight'] = $srch_pkg_weight = $this->session->userdata('srch_pkg_weight') ;
           $data['flg'] = true;  
       }  else {
            $data['srch_pkg_weight'] = $srch_pkg_weight = '';  
       } 
       
       if(isset($_POST['srch_service_provider'])) {
            $data['srch_service_provider'] = $srch_service_provider = $this->input->post('srch_service_provider'); 
            $this->session->set_userdata('srch_service_provider', $this->input->post('srch_service_provider'));
            $data['flg'] = true; 
       } elseif($this->session->userdata('srch_service_provider')){
           $data['srch_service_provider'] = $srch_service_provider = $this->session->userdata('srch_service_provider') ;
           $data['flg'] = true;  
       } else {
            $data['srch_service_provider'] = $srch_service_provider = '';  
       }
       
       $data['record_list'] = array();
       
        
         if($data['flg']){
        
           $sql = "
                    select 
                    a.*,
                    b.*,
                    round(((a.base_rate + (a.base_rate * ifnull(b.fsc,0) / 100)) + (((a.base_rate + (a.base_rate * ifnull(b.fsc,0) / 100)) * ifnull(b.gst,0) /100)) + (ifnull(b.addt_sc,0)) ),0) as sp_rate
                    from crit_international_rate_info_v2 as a 
                    left join crit_intl_setting_info_v2 as b on b.service_provider = a.service_provider
                    where 1=1 "; 
            if(!empty($srch_service_provider))
                $sql.=" and a.service_provider = '". $srch_service_provider ."'";
            if(!empty($srch_country))
                $sql.=" and a.country = '". $srch_country ."'";
            if(!empty($srch_pkg_weight))
                $sql.=" and a.weight  = '". $srch_pkg_weight."'  ";    
                    
            $sql .="order by a.service_provider , a.country , a.weight asc  ";
            
             
            $query = $this->db->query($sql);
           
            
            
            foreach ($query->result_array() as $row)
            {
                $data['record_list'][] = $row;     
            } 
            
        } 
        
        $sql = "
                select 
                a.weight             
                from crit_international_rate_info_v2 as a 
                where 1=1  
                group by a.weight                 
                order by a.weight asc                 
        "; 
        
        $query = $this->db->query($sql);
        $data['package_weight_opt'] = array();
        
        foreach ($query->result_array() as $row)
        {
            $data['package_weight_opt'][$row['weight']] = $row['weight'];     
        } 
        
        $sql = "
                select               
                a.country              
                from crit_international_rate_info_v2 as a  
                where 1=1
                group by a.country                 
                order by a.country asc                 
        "; 
        
        $query = $this->db->query($sql);
        $data['country_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['country_opt'][$row['country']] = $row['country'];     
        }
        
        $sql = "
                select               
                a.service_provider              
                from crit_international_rate_info_v2 as a  
                where 1=1
                group by a.service_provider                 
                order by a.service_provider asc                 
        "; 
        
        $query = $this->db->query($sql);
        $data['service_provider_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['service_provider_opt'][$row['service_provider']] = $row['service_provider'];     
        } 
         
        
        $this->load->view('page/international/international-rate-list-v2',$data); 
	} 
    
     public function international_rate_calc()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        
        
        $data['js'] = 'international/international-rate-calc.inc'; 
        
        $sql = "
                select 
                a.country_id,              
                a.country_name              
                from crit_country_info as a  
                where status = 'Active'
                and exists ( select * from crit_international_rate_info as q  where q.status='Active' and q.country_id = a.country_id group by q.country_id )
                order by  a.country_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['country_opt'][$row['country_id']] = $row['country_name'];     
        }
        
        $sql = "
                select 
                a.package_type_id,                
                a.package_type_name             
                from crit_package_type_info as a  
                where status = 'Active' 
                and exists ( select * from crit_international_rate_info as q  where q.status='Active' and q.package_type_id = a.package_type_id group by q.package_type_id )
                order by a.package_type_id asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['package_type_opt'][$row['package_type_id']] = $row['package_type_name'] ;     
        }
        
        $data['package_weight_opt'] = array();
        
        $data['btn_flg'] = 0;
        
       if($this->input->post('srch_pkg_type') != ''){
        
            $data['btn_flg'] = 1;
        
            $data['srch_pkg_type'] = $this->input->post('srch_pkg_type'); 
            $data['srch_pkg_weight'] = $this->input->post('srch_pkg_weight'); 
            
            $sql = "
                    select 
                    a.package_weight_id,                
                    a.package_weight_range             
                    from crit_package_weight_info as a  
                    where status = 'Active' and  a.package_type_id = '". $data['srch_pkg_type'] ."'
                order by cast(a.package_weight_range as DECIMAL(12,2)) asc              
            "; 
            
            $query = $this->db->query($sql);
           
            foreach ($query->result_array() as $row)
            {
                $data['package_weight_opt'][$row['package_weight_id']] = $row['package_weight_range'] ;     
            }
            
            
        } else {
            $data['srch_pkg_type'] = "";
            $data['srch_pkg_weight'] = "";
            
            $data['package_weight_opt'] = array();
        }
        
        if($this->session->userdata('cr_is_admin') == '1')  {
            
            $sql = "
                select 
                a.country               
                from crit_international_rate_info_v2 as a  
                where 1
                group by a.country                 
                order by a.country asc                 
        "; 
        
        $query = $this->db->query($sql);
        
        $data['country_opt1'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['country_opt1'][$row['country']] = $row['country'];     
        }
            
        }
        
        
        $this->load->view('page/international/international-rate-calc',$data); 
    }
    
   	public function consignment_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
         date_default_timezone_set("Asia/Calcutta"); 
        
        /*if($this->session->userdata('cr_user_type') == 'HUB') 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
      $data['js'] = 'international/consignment.inc';  
      
      if($this->input->post('mode') == 'Add')
        {
            
           /* $this->db->select('(ifnull(max(a.awbno),0) + 1) as awbno');
            $this->db->where('a.status != ', 'Delete'); 
           
            $query = $this->db->get('crit_international_consignment_info as a');
            $row = $query->row();
            if (isset($row)) {
                $awbno = $row->awbno;
            }*/  
            
            $config['upload_path'] = 'intl-id-proof/';
    	    $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('id_proof_doc'))
            {
                $file_array = $this->upload->data();	 
                $id_proof_doc	= 'intl-id-proof/'. $file_array['file_name'];  
            }
            else
            {
                 $id_proof_doc = '';    
            }
            
            $ins = array(
                'booking_date' => $this->input->post('booking_date'),
                'booking_time' => $this->input->post('booking_time'),
                'awbno' => $this->input->post('awbno'),
                'prefix_awbno' => 'TCS',
                //'channel_type' => $this->input->post('channel_type') ,
                //'channel_id' => $this->input->post('channel_id') ,
                'sender_company' => $this->input->post('sender_company') ,
                'sender_name' => $this->input->post('sender_name') ,
                'sender_mobile' => $this->input->post('sender_mobile') ,
                'sender_address' => $this->input->post('sender_address') ,
               // 'sender_gst' => $this->input->post('sender_gst') ,
                'receiver_company' => $this->input->post('receiver_company') ,
                'receiver_name' => $this->input->post('receiver_name') ,
                'receiver_mobile' => $this->input->post('receiver_mobile') ,
                'receiver_address' => $this->input->post('receiver_address') , 
                //'receiver_gst' => $this->input->post('receiver_gst') , 
                'no_of_pieces' => $this->input->post('no_of_pieces') ,
                'country_id' => $this->input->post('country_id') ,
                'international_service_provider_id' => $this->input->post('international_service_provider_id') ,
                'package_type_id' => $this->input->post('package_type_id') ,
                'package_weight_id' => $this->input->post('package_weight_id') , 
                'description_of_goods' => $this->input->post('description_of_goods') ,
                'actual_charges' => $this->input->post('actual_charges') , 
                'markups' => $this->input->post('markups') , 
                'addt_charges' => $this->input->post('addt_charges') , 
                'ah_charges_id' => $this->input->post('ah_charges_id') , 
                'ah_charges' => $this->input->post('ah_charges') , 
                'oda_charges_id' => $this->input->post('oda_charges_id') , 
                'oda_charges' => $this->input->post('oda_charges') , 
                'tot_charges' => $this->input->post('tot_charges') , 
                'status' => $this->input->post('status'),   
                'sr_awbno' => $this->input->post('sr_awbno'),   
                'exp_dv_date' => $this->input->post('exp_dv_date'),   
                'franchise_id' => ($this->session->userdata('cr_franchise_id')!= '' ? $this->session->userdata('cr_franchise_id') : 0),                          
                'id_proof_type' => $this->input->post('id_proof_type'),
                'id_proof_no' => $this->input->post('id_proof_no'),
                'id_proof_doc' => $id_proof_doc,
                'created_by' => $this->session->userdata('cr_user_id'),                          
                'created_datetime' => date('Y-m-d H:i:s') ,   
                'updated_by' => $this->session->userdata('cr_user_id'),                          
                'updated_datetime' => date('Y-m-d H:i:s') ,                     
            );
            
            $this->db->insert('crit_international_consignment_info', $ins); 
            
            $international_consignment_id = $this->db->insert_id();
            
            $ins = array(
                'status_date' => $this->input->post('booking_date'), 
                'status_time' => $this->input->post('booking_time'), 
                'international_consignment_id' => $international_consignment_id ,
                'tracking_status' => 'Booked' ,
                'location' => '' ,
                'remarks' => '' ,
                'created_by' => $this->session->userdata('cr_user_id'),                          
                'created_datetime' => date('Y-m-d H:i:s') ,                    
            );
            
            $this->db->insert('crit_intl_tracking_info', $ins);  
            
            redirect('international-consignment-list');
        }
        
       if($this->input->post('mode') == 'Edit')
        {
            $config['upload_path'] = 'intl-id-proof/';
    		$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('id_proof_doc'))
            {
                $file_array = $this->upload->data();	 
                $id_proof_doc	= 'intl-id-proof/'. $file_array['file_name'];  
            }
            else
            {    
                 $id_proof_doc = $this->input->post('id_proof_doc_path');    
            }   
            
            $upd = array(
                'prefix_awbno' => 'TCS',
                'booking_date' => $this->input->post('booking_date'),
                'booking_time' => $this->input->post('booking_time'),
                'awbno' => $this->input->post('awbno'), 
                'sender_company' => $this->input->post('sender_company') ,
                'sender_name' => $this->input->post('sender_name') ,
                'sender_mobile' => $this->input->post('sender_mobile') ,
                'sender_address' => $this->input->post('sender_address') ,
               // 'sender_gst' => $this->input->post('sender_gst') ,
                'receiver_company' => $this->input->post('receiver_company') ,
                'receiver_name' => $this->input->post('receiver_name') ,
                'receiver_mobile' => $this->input->post('receiver_mobile') ,
                'receiver_address' => $this->input->post('receiver_address') , 
                //'receiver_gst' => $this->input->post('receiver_gst') , 
                'no_of_pieces' => $this->input->post('no_of_pieces') ,
                'country_id' => $this->input->post('country_id') ,
                'international_service_provider_id' => $this->input->post('international_service_provider_id') ,
                'package_type_id' => $this->input->post('package_type_id') ,
                'package_weight_id' => $this->input->post('package_weight_id') , 
                'description_of_goods' => $this->input->post('description_of_goods') ,
                'actual_charges' => $this->input->post('actual_charges') , 
                'ah_charges_id' => $this->input->post('ah_charges_id') , 
                'ah_charges' => $this->input->post('ah_charges') , 
                'oda_charges_id' => $this->input->post('oda_charges_id') , 
                'oda_charges' => $this->input->post('oda_charges') , 
                'tot_charges' => $this->input->post('tot_charges') , 
                'markups' => $this->input->post('markups') , 
                'addt_charges' => $this->input->post('addt_charges') , 
                'sr_awbno' => $this->input->post('sr_awbno'),   
                'status' => $this->input->post('status'),  
                'exp_dv_date' => $this->input->post('exp_dv_date'), 
                'id_proof_type' => $this->input->post('id_proof_type'),
                'id_proof_no' => $this->input->post('id_proof_no'),
                'id_proof_doc' => $id_proof_doc, 
                'updated_by' => $this->session->userdata('cr_user_id'),                          
                'updated_datetime' => date('Y-m-d H:i:s') ,                     
            ); 
             
            $this->db->where('international_consignment_id', $this->input->post('international_consignment_id'));
            $this->db->update('crit_international_consignment_info', $upd); 
                            
            redirect('international-consignment-list/' . $this->uri->segment(2, 0));  
        } 
        
        if($this->input->post('mode') == 'Add Tracking')
        { 
            
            $config['upload_path'] = 'intl-pod/';
    		$config['allowed_types'] = 'gif|jpg|png|jpeg';
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('intl_pod_path'))
            {
                $file_array = $this->upload->data();	 
                $intl_pod_path	= 'intl-pod/'. $file_array['file_name']; 
           
            }
            else
            {
                 $intl_pod_path = '';    
            }
            
            
            
            $ins = array(
                'status_date' => $this->input->post('status_date'), 
                'status_time' => $this->input->post('status_time'), 
                'international_consignment_id' => $this->input->post('international_consignment_id') ,
                'tracking_status' => $this->input->post('tracking_status') ,
                'location' => $this->input->post('location') ,
                'remarks' => $this->input->post('remarks') ,
                'pod' => $intl_pod_path , 
                'created_by' => $this->session->userdata('cr_user_id'),                          
                'created_datetime' => date('Y-m-d H:i:s') ,                    
            );
            
            $this->db->insert('crit_intl_tracking_info', $ins); 
            redirect('international-consignment-list/' . $this->uri->segment(2, 0));  
        }
        
        
        
       if(isset($_POST['srch_awbno']) && $_POST['srch_awbno'] != '') {
           $data['srch_awbno'] = $srch_awbno = $this->input->post('srch_awbno');  
           $data['srch_from_date'] = $srch_from_date = '';
           $data['srch_to_date'] = $srch_to_date = ''; 
       }
       else {  
        
           if(isset($_POST['srch_from_date'])) {
               $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
               $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date');  
               $this->session->set_userdata('srch_from_date', $this->input->post('srch_from_date'));
               $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date')); 
               $data['srch_awbno'] = $srch_awbno = '';
           }
           elseif($this->session->userdata('srch_from_date')){
               $data['srch_from_date'] = $srch_from_date = $this->session->userdata('srch_from_date') ;
               $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ; 
               $data['srch_awbno'] = $srch_awbno = '';
           } else {
                $data['srch_from_date'] = $srch_from_date = date('Y-m-d');
                $data['srch_to_date'] = $srch_to_date = date('Y-m-d');
                $data['srch_awbno'] = $srch_awbno = ''; 
                
           } 
           
       }
       
       if(!empty($srch_from_date) && !empty($srch_to_date)  ){
        $where = " a.booking_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'"; 
        
        $data['submit_flg'] = true;
         
       } else if(!empty($srch_awbno)){
       // $where = " concat(a.prefix_awbno,a.awbno) = '" . $srch_awbno . "'";  
        $where = " a.awbno  = '" . $srch_awbno . "'";  
        $data['submit_flg'] = true;
         
       } else {
        $where = ' 1=1 ';
       }  
        
         
        
        $this->load->library('pagination');
        
        $this->db->where('a.status != ', 'Delete'); 
        $this->db->where($where); 
        if($this->session->userdata('cr_is_admin') != '1') {
            $this->db->where(" exists ( select w.user_id from crit_franchise_info as q left join crit_user_info as w on w.franchise_id = q.franchise_id where w.user_id = a.created_by and  q.franchise_id = '". $this->session->userdata('cr_franchise_id') ."')");
        }
        
        $this->db->from('crit_international_consignment_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('international-consignment-list/'), '/'. $this->uri->segment(2, 0));
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
                a.international_consignment_id,
                f.branch_code,
                concat(a.prefix_awbno,a.awbno) as awbno1,
                a.awbno as awbno,
                b.country_name as country,
                c.package_weight_range,
                a.booking_date,                
                a.booking_time,   
                a.status,
                a.actual_charges, 
                a.addt_charges,
                a.tot_charges, 
                g.international_service_provider,
                g.tracking_url,
                a.sr_awbno
                from crit_international_consignment_info as a 
                left join crit_country_info as b on b.country_id = a.country_id
                left join crit_package_weight_info as c on c.package_weight_id = a.package_weight_id
                left join crit_user_info as d on d.user_id = a.created_by
                left join crit_franchise_info as f on f.franchise_id = d.franchise_id
                left join crit_international_service_provider_info as g on g.international_service_provider_id = a.international_service_provider_id
                where a.status != 'Delete' and $where ";
           if($this->session->userdata('cr_tracking_upd_rights') != '1') 
                $sql .= " and exists ( select w.user_id from crit_franchise_info as q left join crit_user_info as w on w.franchise_id = q.franchise_id where w.user_id = a.created_by and  q.franchise_id = '". $this->session->userdata('cr_franchise_id') ."')";     
           
            $sql .= " order by a.booking_date desc, a.booking_time desc , a.awbno desc 
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
                a.country_id,              
                a.country_name              
                from crit_country_info as a  
                where status = 'Active'
                and exists ( select * from crit_international_rate_info as q  where q.status='Active' and q.country_id = a.country_id group by q.country_id )
                order by  a.country_name asc                 
        "; 
        
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['country_opt'][$row['country_id']] = $row['country_name'];     
        }
        
         $sql = "
                select 
                a.package_type_id,                
                a.package_type_name             
                from crit_package_type_info as a  
                where status = 'Active' 
                and exists ( select * from crit_international_rate_info as q  where q.status='Active' and q.package_type_id = a.package_type_id group by q.package_type_id )
                order by a.package_type_id asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['package_type_opt'][$row['package_type_id']] = $row['package_type_name'] ;     
        }
        
        $sql = "
                select 
                a.tracking_status
                from crit_intl_tracking_status_info as a  
                where status = 'Active'                 
                order by a.sort asc                
        "; 
        
        $query = $this->db->query($sql);
        
        
       $data['tracking_opt'] = array();
        foreach ($query->result_array() as $row)
        {
            $data['tracking_opt'][$row['tracking_status']]  = $row['tracking_status'];     
        } 
        
        
        $sql = "
                select 
                a.state_name,                
                a.state_code             
                from crit_states_info as a  
                where status != 'Delete' 
                order by a.state_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_name']] = $row['state_name'];     
        }
        
        $sql = "
                select 
                a.oda_charges_id,                
                a.oda_charges_type,
                a.oda_charges             
                from oda_charges_info as a  
                where status != 'Delete' 
                order by a.oda_charges_id asc                 
        "; 
        
        $query = $this->db->query($sql);
        $data['oda_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            
            $data['oda_opt'][] = $row;     
        }
        
        $sql = "
                select 
                a.ah_charges_id,                
                a.ah_charges_type,
                a.ah_charges             
                from ah_charges_info as a  
                where a.status != 'Delete' 
                order by a.ah_charges_id asc                 
        "; 
        
        $query = $this->db->query($sql);
         $data['ah_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            
            $data['ah_opt'][] = $row;     
        }
        
        
        $sql = "
                select 
                a.doc_upload_type_name    
                from crit_doc_upload_type_info as a  
                where status = 'Active' 
                order by a.doc_upload_type_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['doc_upload_type_opt'][$row['doc_upload_type_name']] = $row['doc_upload_type_name'];     
        }
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/international/consignment-list',$data); 
	} 
    
    
    public function intl_tracking_status_list()
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
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                         
            );
            
            $this->db->insert('crit_intl_tracking_status_info', $ins); 
            redirect('international-tracking-status');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'tracking_status' => $this->input->post('tracking_status'),
                    'status' => $this->input->post('status')  ,                 
                    'sort' => $this->input->post('sort'),                 
            );
            
            $this->db->where('tracking_status_id', $this->input->post('tracking_status_id'));
            $this->db->update('crit_intl_tracking_status_info', $upd); 
                            
            //redirect('international-tracking-status/' . $this->uri->segment(2, 0)); 
            redirect('international-tracking-status'); 
        } 
         
        
        $this->load->library('pagination');
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_intl_tracking_status_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('international-tracking-status'), '/'. $this->uri->segment(2, 0));
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
                from crit_intl_tracking_status_info as a 
                where a.status != 'Delete'
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
        
        $this->load->view('page/international/intl-tracking-status-list',$data); 
	} 
    
    
    public function print_intl_awbno($booking_id)
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	 
        
        
        $sql = "
                select  
                a.*,
                b.international_service_provider,
                c.package_weight_range as package_weight,
                d.country_name as country
                from crit_international_consignment_info as a 
                left join crit_international_service_provider_info as b on b.international_service_provider_id = a.international_service_provider_id
                left join crit_package_weight_info as c on c.package_weight_id = a.package_weight_id
                left join crit_country_info as d on d.country_id = a.country_id
                where a.`status` != 'Delete' 
                and a.international_consignment_id  = $booking_id   
                order by a.booking_date asc , a.awbno asc                 
        ";
        
        
        $query = $this->db->query($sql); 
        
        $code = 0;
       
        foreach ($query->result_array() as $row)
        {
            $data['label']  = $row; 
            
            $code = $row['awbno'];    
        }
        
        
        //echo file_exists('barcodes/'.$code.'.png');
        
        if(!file_exists('barcodes/'.$code.'.png')){
        
        //load library
        $this->load->library('zend');
        //load in folder Zend
        $this->zend->load('Zend/Barcode');
        //generate barcode
        $imageResource = Zend_Barcode::factory('code128', 'image', array('text'=>$code), array())->draw();
        imagepng($imageResource, 'barcodes/'.$code.'.png');
        
        }
        
        
        $data['barcode'] = 'barcodes/'.$code.'.png';  
        
        $this->load->view('page/international/print-intl-awbno',$data); 
	} 
    
    public function recycle_intl_booking_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();  
        
        if($this->session->userdata('cr_is_admin') != 1) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        }       
        
        	    
        $data['js'] = 'international/recycle-intl-booking.inc';  
        
        
       if(isset($_POST['srch_awbno']) && $_POST['srch_awbno'] != '') {
           $data['srch_awbno'] = $srch_awbno = $this->input->post('srch_awbno');  
           $data['srch_from_date'] = $srch_from_date = '';
           $data['srch_to_date'] = $srch_to_date = ''; 
       }
       else {  
        
           if(isset($_POST['srch_from_date'])) {
               $data['srch_from_date'] = $srch_from_date = $this->input->post('srch_from_date'); 
               $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date');  
               $this->session->set_userdata('srch_from_date', $this->input->post('srch_from_date'));
               $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date')); 
               $data['srch_awbno'] = $srch_awbno = '';
           }
           elseif($this->session->userdata('srch_from_date')){
               $data['srch_from_date'] = $srch_from_date = $this->session->userdata('srch_from_date') ;
               $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ; 
               $data['srch_awbno'] = $srch_awbno = '';
           } else {
                $data['srch_from_date'] = $srch_from_date = date('Y-m-d');
                $data['srch_to_date'] = $srch_to_date = date('Y-m-d');
                $data['srch_awbno'] = $srch_awbno = ''; 
                
           } 
           
       }
       
       if(!empty($srch_from_date) && !empty($srch_to_date)  ){
        $where = " a.booking_date between '" . $srch_from_date . "' and  '". $srch_to_date ."'"; 
        
        $data['submit_flg'] = true;
         
       } else if(!empty($srch_awbno)){
        $where = " a.awbno = '" . $srch_awbno . "'";  
        $data['submit_flg'] = true;
         
       } else {
        $where = ' 1 ';
       }  
        
         
        
        $this->load->library('pagination');
        
        $this->db->where('a.status = ', 'Delete'); 
        $this->db->where($where);  
         
        $this->db->where("not exists( select z.awbno from crit_international_consignment_info as z where z.`status` != 'Delete' and z.awbno = a.awbno )");  
        $this->db->from('crit_international_consignment_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('recycle-booking-list/'), '/'. $this->uri->segment(2, 0));
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
                a.international_consignment_id,
                f.branch_code,
                concat(a.prefix_awbno,a.awbno) as awbno1,
                a.awbno as awbno,
                b.country_name as country,
                c.package_weight_range,
                a.booking_date,                
                a.booking_time,   
                a.status,
                a.actual_charges, 
                g.international_service_provider,
                g.tracking_url,
                a.sr_awbno
                from crit_international_consignment_info as a 
                left join crit_country_info as b on b.country_id = a.country_id
                left join crit_package_weight_info as c on c.package_weight_id = a.package_weight_id
                left join crit_user_info as d on d.user_id = a.created_by
                left join crit_franchise_info as f on f.franchise_id = d.franchise_id
                left join crit_international_service_provider_info as g on g.international_service_provider_id = a.international_service_provider_id
                where a.status = 'Delete' and not exists( select z.awbno from crit_international_consignment_info as z where z.`status` != 'Delete' and z.awbno = a.awbno )
                and $where "; 
                
            $sql .= " order by a.booking_date desc, a.booking_time desc , a.awbno desc 
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
        
        $this->load->view('page/international/recycle-intl-booking-list',$data); 
	}   
    
    
    public function oda_charges_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'international/oda_charges.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'oda_charges_type' => $this->input->post('oda_charges_type'),
                    'oda_charges' => $this->input->post('oda_charges'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('oda_charges_info', $ins); 
            redirect('oda-charges-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'oda_charges_type' => $this->input->post('oda_charges_type'),
                    'oda_charges' => $this->input->post('oda_charges'),
                    'status' => $this->input->post('status')  ,               
            );
            
            $this->db->where('oda_charges_id', $this->input->post('oda_charges_id'));
            $this->db->update('oda_charges_info', $upd); 
                            
            redirect('oda-charges-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination'); 
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('oda_charges_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('oda-charges-list/'), '/'. $this->uri->segment(2, 0));
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
                from oda_charges_info as a  
                where a.status != 'Delete'
                order by a.status asc , a.oda_charges_type 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        "; 
        
        $query = $this->db->query($sql);
         $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/international/oda-charges-list',$data); 
	} 
    
    public function ah_charges_list()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'international/ah_charges.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'ah_charges_type' => $this->input->post('ah_charges_type'),
                    'ah_charges' => $this->input->post('ah_charges'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('ah_charges_info', $ins); 
            redirect('ah-charges-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'ah_charges_type' => $this->input->post('ah_charges_type'),
                    'ah_charges' => $this->input->post('ah_charges'),
                    'status' => $this->input->post('status')  ,               
            );
            
            $this->db->where('ah_charges_id', $this->input->post('ah_charges_id'));
            $this->db->update('ah_charges_info', $upd); 
                            
            redirect('ah-charges-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination'); 
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('ah_charges_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('oda-charges-list/'), '/'. $this->uri->segment(2, 0));
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
                from ah_charges_info as a  
                where a.status != 'Delete'
                order by a.status asc , a.ah_charges_type 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        "; 
        
        $query = $this->db->query($sql);
         $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('page/international/ah-charges-list',$data); 
	} 


}
?>