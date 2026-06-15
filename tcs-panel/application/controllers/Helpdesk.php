<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Helpdesk extends CI_Controller {
    
    
   public function hd_category_list()
   {
        if(!$this->session->userdata('cr_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'helpdesk/hd_category.inc';  
        $data['s_url'] = 'hd-category-list/' . $this->uri->segment(2, 0);    
        $data['f_url'] = 'hd-category-list';    
        $data['title'] = 'Helpdesk Category List';    
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'hd_category_name' => $this->input->post('hd_category_name'),  
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('hd_category_info', $ins); 
            redirect($data['s_url']);
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'hd_category_name' => $this->input->post('hd_category_name'),  
                    'status' => $this->input->post('status')  ,   
            );
            
            $this->db->where('hd_category_id', $this->input->post('hd_category_id'));
            $this->db->update('hd_category_info', $upd); 
                            
            redirect($data['s_url']); 
        } 
         
        
        $this->load->library('pagination'); 
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('hd_category_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url($data['s_url']));
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
                from hd_category_info as a  
                where a.status != 'Delete'
                order by a.status asc , a.hd_category_name asc 
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
        
        $this->load->view('page/helpdesk/'. $data['f_url'],$data); 
    
    } 
    
    public function ticket_list()
   {
        if(!$this->session->userdata('cr_logged_in'))  redirect();
        
       
        	    
        $data['js'] = 'helpdesk/ticket_list.inc';  
        $data['s_url'] = 'ticket-list/' . $this->uri->segment(2, 0);    
        $data['f_url'] = 'ticket-list';    
        $data['title'] = 'Support Ticket List';    
        
        if($this->session->userdata('cr_is_admin') == 11) {
            $where = " ( a.to_franchise_id = '". $this->session->userdata('cr_franchise_id') ."' 
                            or 
                         a.frm_franchise_id = '". $this->session->userdata('cr_franchise_id') ."' 
                            or 
                         FIND_IN_SET('". $this->session->userdata('cr_franchise_id') ."', a.share_to) 
                        )";
        }  else {
            $where = " 1=1";
        } 
         
        
        
        
        
        if($this->input->post('mode') == 'Add')
        {
            $config['upload_path'] = 'ticket-doc/';
    	    $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('attach_doc'))
            {
                $file_array = $this->upload->data();	 
                $attach_doc	= 'ticket-doc/'. $file_array['file_name'];  
            }
            else
            {
                 $attach_doc = '';    
            }
            
            
            $ins = array(
                    'hd_category_id' => $this->input->post('hd_category_id'),  
                    'frm_franchise_id' => ($this->session->userdata('cr_franchise_id')!= '' ? $this->session->userdata('cr_franchise_id') : 0),  
                    'to_franchise_id' => $this->input->post('to_franchise_id'),  
                    'user_id' => $this->session->userdata('cr_user_id'), 
                    'awbno' => $this->input->post('awbno'),  
                    'subject' => $this->input->post('subject'),  
                    'description' => $this->input->post('description'),  
                    'priority' => $this->input->post('priority'),  
                    'attach_doc' => $attach_doc,  
                    'status' => "Open",
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s') ,   
                    'updated_by' => $this->session->userdata('cr_user_id'),                          
                    'updated_datetime' => date('Y-m-d H:i:s') ,                             
            );
            
            $this->db->insert('hd_ticket_info', $ins); 
            redirect($data['s_url']);
        }
        
        
        $this->load->library('pagination'); 
        
        
        $this->db->where('a.status != ', 'Delete'); 
         
        $this->db->where($where);  
        
        $this->db->from('hd_ticket_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url($data['s_url']));
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
                a.* ,
                b.hd_category_name,
                c.branch_code as to_franchise,
                c.contact_person as to_franchise_name,
                ifnull(d.branch_code,'Associate') as frm_franchise,
                count(e.hd_ticket_comment_id) as cnt
                from hd_ticket_info as a  
                left join hd_category_info as b on b.hd_category_id = a.hd_category_id  
                left join crit_franchise_info as c on c.franchise_id = a.to_franchise_id   
                left join crit_franchise_info as d on d.franchise_id = a.frm_franchise_id   
                left join hd_ticket_comment_info as e on e.hd_ticket_id = a.hd_ticket_id   
                where a.status != 'Delete'
                and $where
                group by a.hd_ticket_id
                order by  a.created_datetime desc 
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
        
        
        $sql = "
                select 
                a.hd_category_id,                
                a.hd_category_name             
                from hd_category_info as a  
                where status = 'Active' 
                order by a.hd_category_name asc                 
        "; 
        
        $query = $this->db->query($sql);
        $data['hd_category_opt'] = array();
       
       
        foreach ($query->result_array() as $row)
        {
            $data['hd_category_opt'][$row['hd_category_id']] = $row['hd_category_name'] ;     
        }
        
        
        $data['priority_opt'] = array('' => 'Select','High' => 'High','Medium' => 'Medium','Low' => 'Low');
        
        $data['pagination'] = $this->pagination->create_links(); 
        
        $this->load->view('page/helpdesk/'. $data['f_url'],$data); 
    
    } 
    
   public function ticket_info($ticket_id)
   {
        if(!$this->session->userdata('cr_logged_in'))  redirect();
        
       
        	    
        $data['js'] = 'helpdesk/ticket.inc';  
        $data['s_url'] = 'ticket/' . $this->uri->segment(2, 0);    
        $data['f_url'] = 'ticket';    
        $data['title'] = 'Support Ticket Information';    
        
        
        
        
        if($this->input->post('mode') == 'Add Ticket Comments')
        {
            $config['upload_path'] = 'ticket-doc/';
    	    $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('attach_doc'))
            {
                $file_array = $this->upload->data();	 
                $attach_doc	= 'ticket-doc/'. $file_array['file_name'];  
            }
            else
            {
                 $attach_doc = '';    
            } 
            
            $ins = array(
                    'hd_ticket_id' => $this->input->post('hd_ticket_id'),   
                    'franchise_id' => $this->input->post('franchise_id'),  
                    'user_id' => $this->session->userdata('cr_user_id'), 
                    'comment' => $this->input->post('comment'),   
                    'attach_doc' => $attach_doc,  
                    'status' => $this->input->post('status'),
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s') ,   
                    'updated_by' => $this->session->userdata('cr_user_id'),                          
                    'updated_datetime' => date('Y-m-d H:i:s') ,                             
            );
            
            $this->db->insert('hd_ticket_comment_info', $ins); 
            
            $this->db->where('hd_ticket_id', $this->input->post('hd_ticket_id')); 
            $this->db->update('hd_ticket_info', array( 'status' => $this->input->post('status')));  
            
            redirect($data['s_url']);
            
        }
        
        
         
        
         $sql = "
                select 
                a.* ,
                b.hd_category_name,
                c.branch_code as to_franchise,
                c.contact_person as to_franchise_name,
                ifnull(d.branch_code,'Admin') as frm_franchise
                from hd_ticket_info as a  
                left join hd_category_info as b on b.hd_category_id = a.hd_category_id  
                left join crit_franchise_info as c on c.franchise_id = a.to_franchise_id   
                left join crit_franchise_info as d on d.franchise_id = a.frm_franchise_id   
                where a.status != 'Delete'
                and a.hd_ticket_id = $ticket_id
                order by  a.created_datetime desc                 
        "; 
        
        $query = $this->db->query($sql);
         $data['ticket_info'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['ticket_info'] = $row;     
        } 
        
        $sql = "
                select 
                a.*,
                c.branch_code as reply_franchise_code,
                c.contact_person as reply_franchise_name           
                from hd_ticket_comment_info as a  
                left join crit_franchise_info as c on c.franchise_id = a.franchise_id  
                where a.status != 'Delete' 
                and a.hd_ticket_id = $ticket_id
                order by a.created_datetime asc                 
        "; 
        
        $query = $this->db->query($sql);
        $data['ticket_comment_info'] = array();
       
       
        foreach ($query->result_array() as $row)
        {
            $data['ticket_comment_info'][] = $row ;     
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
            $data['franchise_opt'][$row['franchise_id']] =  $row['state_code'] . ' - ' . $row['city'] .' [ ' . $row['contact_person']. ' ]' .' [ ' . $row['branch_code']. ' ]';     
        }
        
        
        
        
        
         $data['priority_opt'] = array('' => 'Select','High' => 'High','Medium' => 'Medium','Low' => 'Low'); 
         $data['status_opt'] = array('Open' => 'Open','Agent Handling' => 'Agent Handling', 'Resolved' => 'Resolved', 'Closed' => 'Closed'); 
        
        $this->load->view('page/helpdesk/'. $data['f_url'],$data); 
    
    } 
    
    
}
?>    