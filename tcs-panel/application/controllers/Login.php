<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	 
public function index()
	{
	   
	    $data['js'] = '';
        $data['login'] = true; 
        
        date_default_timezone_set("Asia/Calcutta"); 
       
       	 
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_name', 'User Name', 'required');
        $this->form_validation->set_rules('user_pwd', 'Password', 'required',array('required' => 'You must provide %s.'));
        if ($this->form_validation->run() == FALSE)
        {
             
            $this->load->view('page/login',$data); 
        }
        else
        {
              
            $user_info = array(); 
            
              $sql = "
              select 
              a.user_id as id, 
              a.first_name as  name , 
              a.level , 
              'staff' as typ, 
              0 as reset_flg,
              a.state,
              a.city ,
              ifnull(a.franchise_id,0) as franchise_id ,
              b.branch_code,
              b.hub_code,
              a.user_name,
              b.tracking_upd_rights
              from crit_user_info as a  
              left join crit_franchise_info as b on b.franchise_id = a.franchise_id 
              where a.user_name = '".($this->input->post('user_name'))."' 
              and a.pwd = '". ($this->input->post('user_pwd'))."' 
              and a.status = 'Active' 
            "; 
          
            $query = $this->db->query($sql); 

            $cnt = $query->num_rows(); 
            
            
             
            $row = $query->row();
            
            if (isset($row))
            { 
                $newdata = array(
                   'cr_user_id'  => $row->id,
                   'cr_user_name'  => $row->name, 
                   'cr_login_name'  => $row->user_name, 
                   'cr_user_type'  => $row->typ, 
                   'cr_reset_flg'  => $row->reset_flg, 
                   'cr_pstate'  => $row->state, 
                   'cr_pcity'  => $row->city, 
                   'cr_franchise_id'  => $row->franchise_id, 
                   'cr_is_admin'  =>  $row->level, 
                   'cr_branch_code'  =>  $row->branch_code, 
                   'cr_hub_code'  =>  $row->hub_code, 
                   'cr_tracking_upd_rights'  => ($row->level == 1 ? '1' : $row->tracking_upd_rights), 
                   'cr_logged_in' => TRUE
               );
               
                $this->session->set_userdata($newdata);
                
                
              //$this->db->insert('crit_user_history_info',array('user_id' => $this->session->userdata('cr_user_id') , 'page' => 'Login', 'date_time' => date('Y-m-d H:i:s'))) ; 
                 
                 /*if($row->level == 1 or $row->level == 5)
                     redirect('dash');   
                 elseif($row->level == 4)
                     redirect('customer-pick-pack-list');
                 elseif($row->level == USER_PICKUP)
                     redirect('pickup-delivery');    
                 else */   
                     redirect('dash');
                    // redirect('change-login-pwd');   
            
            } 
            else 
            {
				$data['msg'] = ' Invalid User';
				$data['login'] =false;	                 
				$this->load->view('page/login',$data);
			} 			 
        } 		
	} 
    
    
    public function logout()
	{	 
	    
	    //$this->db->insert('crit_user_history_info',array('user_id' => $this->session->userdata('m_user_id') , 'page' => 'Logout' , 'date_time' => date('Y-m-d H:i:s'))) ; 
       
	    $this->session->unset_userdata('cr_logged_in');
        $this->session->unset_userdata('cr_user_id');
        $this->session->unset_userdata('cr_user_name');
        $this->session->unset_userdata('cr_user_type');
        $this->session->unset_userdata('cr_reset_flg');
        $this->session->unset_userdata('cr_pstate');
        $this->session->unset_userdata('cr_pcity');
        $this->session->unset_userdata('cr_franchise_id');
        $this->session->unset_userdata('cr_is_admin');
        $this->session->unset_userdata('cr_logged_in');
        $this->session->unset_userdata('cr_branch_code');
        $this->session->unset_userdata('cr_hub_code');
        $this->session->unset_userdata('cr_login_name');
		$this->session->sess_destroy();
	    redirect('', 'refresh');
	}
    
}
