<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	 
	public function index()
	{
	  if(!$this->session->userdata('cr_logged_in'))  redirect();   
      
      date_default_timezone_set("Asia/Calcutta"); 
      
      
        if($this->input->post('btn_sign') == 'Sign-In'){ 
             
            $ins = array( 
                    'franchise_id' => $this->session->userdata('cr_franchise_id'),    
                    'user_id' => $this->session->userdata('cr_user_id'),  
                    'status' => 'In'  ,                       
                    'in_time' => date('Y-m-d H:i:s'),                       
            );
            
            $this->db->insert('crit_attendance_log_info', $ins); 
            
            redirect('dash');
        
        }
        
        if($this->input->post('btn_sign') == 'Sign-Out'){ 
             
            $upd = array(  
                    'status' => 'Out'  ,                       
                    'out_time' => date('Y-m-d H:i:s'),                       
            );
            
            $this->db->where('attendance_log_id', $this->security->xss_clean($this->input->post('attendance_log_id'))); 
            $this->db->update('crit_attendance_log_info', $upd);  
            redirect('dash');
        
        }
        
        
        
         
         $data = array(); 
         
         $data['js'] = 'dash.inc'; 
         
         $data['wallet_msg'] = '';
         
         $this->db->query("update `crit_booking_info` set awbno = trim(awbno) WHERE `awbno` like ' %'");
         
       /*if($this->session->userdata('cr_is_admin') == '111')  {
        $where .= " and a.origin_state_code = '". $this->session->userdata('cr_pstate') ."'";  
        
       }*/
       
       if($this->session->userdata('cr_tracking_upd_rights') == 1) {  
        
            $sql = 
                "  
                select 
                *
                from crit_attendance_log_info as a
                where a.`status` != 'Delete' 
                and DATE_FORMAT(a.in_time,'%Y-%m-%d') = '".date('Y-m-d')."'
                and a.franchise_id = '". $this->session->userdata('cr_franchise_id') ."'
                and a.user_id = '". $this->session->userdata('cr_user_id') ."'
                order by a.attendance_log_id desc 
                limit 1 
                ";
                    
            $query = $this->db->query($sql);
       
            $data['attendance'] = array();
       
            foreach($query->result_array() as $row)
            {                 
                $data['attendance'] = $row;
            } 
        
        
       }
		  
       if($this->session->userdata('cr_is_admin') == 1) { 
            
            
            $sql = 
                "  
                select
                count(a.hd_ticket_id) as cnt
                from hd_ticket_info as a
                where a.`status` != 'Delete'
                and a.`status` != 'Close' 
                ";
                    
            $query = $this->db->query($sql);
       
            $data['ticket'] = 0;
       
            foreach($query->result_array() as $row)
            {                 
                $data['ticket'] = $row['cnt'];
            } 
            
            
            $sql = "select
                    DATE_FORMAT(a.booking_date,'%b-%Y') as g_month,
                    count(a.international_consignment_id) as cnt,
                    sum(a.tot_charges) as tot  
                    from crit_international_consignment_info as a
                    where a.`status` = 'Active'
                    and a.booking_date >= '2024-12-01'
                    group by DATE_FORMAT(a.booking_date,'%Y%m')
                    order by DATE_FORMAT(a.booking_date,'%Y%m') desc
                    limit 12 ";
                    
            $query = $this->db->query($sql);
       
            $data['intl_summary'] = array();
       
            foreach($query->result_array() as $row)
            {                 
                $data['intl_summary'][] = $row;
            }  
            
            $sql = "select 
                    DATE_FORMAT(a.booking_date,'%b-%Y') as g_month,
                    count(a.booking_id) as cnt ,
                    sum(grand_total) as tot
                    from crit_booking_info as a  
                    where a.`status` != 'Delete'  
                    and a.booking_date >= '2024-12-01'
                    group by DATE_FORMAT(a.booking_date,'%Y%m')
                    order by DATE_FORMAT(a.booking_date,'%Y%m') desc 
                    limit 12
                    ";
                    
            $query = $this->db->query($sql);
       
            $data['dmst_summary'] = array();
       
            foreach($query->result_array() as $row)
            {                 
                $data['dmst_summary'][] = $row;
            }  
            
            
           /* $sql = "select
                    z.franchise_id,
                    z.t_date,
                    sum(z.topup) as topup,
                    sum(z.intl_chrg) as intl_chrg
                    from
                    (
                    
                    		(
                    		select 
                    		a.franchise_id,
                    		a.payment_date as t_date,
                    		sum(a.amount) as topup,
                    		0 as intl_chrg
                    		from wallet_payment_transfer_info as a  
                    		where a.`status` ='Received' 
                    		and a.payment_date <= current_date() 
                    		group by a.franchise_id , a.payment_date
                    		)  union all
                    		(
                    		select  
                    		b.franchise_id,
                    		a.booking_date as t_date,
                    		0 as topup,
                    		sum(a.actual_charges) as intl_chrg
                    		from crit_international_consignment_info as a
                    		left join crit_user_info as b on b.user_id = a.created_by 
                    		where a.`status` != 'Delete'  
                    		and a.booking_date <=  current_date()
                    		and a.booking_date >= '2024-09-27'
                    		and a.created_by != '1'
                    		GROUP by b.franchise_id  ,a.booking_date
                    		) 
                    ) as z
                    where z.franchise_id != ''
                    group by z.franchise_id , z.t_date 
                    order by z.franchise_id , z.t_date ";
                    
             */       
                    
           
            
            
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
                    w.`status` = 'Active' AND w.franchise_id = e1.franchise_id AND w.op_date <= '". date('Y-m-d')."'
                ORDER BY
                    w.op_date
                DESC
                    ,
                    w.opening_balance_wallet_id
                DESC
            LIMIT 1
            ) AND DATE_SUB('". date('Y-m-d')."', INTERVAL 0 DAY)
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
                    w.`status` = 'Active' AND w.franchise_id = e2.franchise_id AND w.op_date <= '". date('Y-m-d')."'
                ORDER BY
                    w.op_date
                DESC
                    ,
                    w.opening_balance_wallet_id
                DESC
            LIMIT 1
            ) AND DATE_SUB('". date('Y-m-d')."', INTERVAL 0 DAY)
        GROUP BY
            e2.franchise_id
        ) AS f
        ON
            f.franchise_id = a.franchise_id
        WHERE
            a.`status` = 'Active'  
        GROUP BY
            a.franchise_id
        ORDER BY
            a.franchise_type_id 
        
        "; 
            
            $query = $this->db->query($sql);
       
            $data['wallet_balance'] = array();
       
            foreach($query->result_array() as $row)
            {                 
                $data['wallet_balance'][] = $row;
            }      
                   
                    
           }
           
           
           
           if($this->session->userdata('cr_is_admin') == 1) { 
            $query = $this->db->query(" 
                    select 
                    count(a.booking_id) as no_of_booking ,
                    sum(grand_total) as amount
                    from crit_booking_info as a 
                    left join crit_user_info as b on b.user_id = a.created_by
                    where a.`status` != 'Delete' and a.booking_date = '". date('Y-m-d')."' 
                    order by a.booking_id  
            ");
            } elseif($this->session->userdata('cr_is_admin') == '111')  { 
                $query = $this->db->query(" 
                      select 
                        count(a.booking_id) as no_of_booking ,
                        sum(grand_total) as amount
                        from crit_booking_info as a 
                        left join crit_user_info as b on b.user_id = a.created_by
                        where a.`status` != 'Delete' and a.booking_date = '". date('Y-m-d')."' 
                        and a.origin_state_code = '". $this->session->userdata('cr_pstate') ."'
                        order by a.booking_id  
                ");
            } else {
                $query = $this->db->query(" 
                  select 
                    count(a.booking_id) as no_of_booking ,
                    sum(grand_total) as amount
                    from crit_booking_info as a 
                    left join crit_user_info as b on b.user_id = a.created_by
                    where a.`status` != 'Delete' and a.booking_date = '". date('Y-m-d')."'
                    and b.franchise_id = '". $this->session->userdata('cr_franchise_id') ."' 
                    order by a.booking_id 
                ");
            }
             
    
            foreach($query->result_array() as $row)
            {
                $data['fr_no_of_booking'] = $row['no_of_booking'];  
                $data['fr_day_amt'] = $row['amount'];  
            }
            
            if($this->session->userdata('cr_is_admin') == 1) { 
                $query = $this->db->query(" 
                      select 
                        count(a.booking_id) as no_of_booking ,
                        sum(grand_total) as amount
                        from crit_booking_info as a 
                        left join crit_user_info as b on b.user_id = a.created_by
                        where a.`status` != 'Delete' and DATE_FORMAT(a.booking_date,'%Y-%m') = '". date('Y-m')."' 
                        order by a.booking_id ;
                ");
            } elseif($this->session->userdata('cr_is_admin') == '111')  { 
                $query = $this->db->query(" 
                      select 
                        count(a.booking_id) as no_of_booking ,
                        sum(grand_total) as amount
                        from crit_booking_info as a 
                        left join crit_user_info as b on b.user_id = a.created_by
                        where a.`status` != 'Delete' and DATE_FORMAT(a.booking_date,'%Y-%m') = '". date('Y-m')."' 
                        and a.origin_state_code = '". $this->session->userdata('cr_pstate') ."'
                        order by a.booking_id ;
                ");
            } else {
                 $query = $this->db->query(" 
                      select 
                        count(a.booking_id) as no_of_booking ,
                        sum(grand_total) as amount
                        from crit_booking_info as a 
                        left join crit_user_info as b on b.user_id = a.created_by
                        where a.`status` != 'Delete' and DATE_FORMAT(a.booking_date,'%Y-%m') = '". date('Y-m')."'
                        and b.franchise_id = '". $this->session->userdata('cr_franchise_id') ."' 
                        order by a.booking_id ;
                ");   
            }
             
    
            foreach($query->result_array() as $row)
            {
                $data['fr_no_of_booking_month'] = $row['no_of_booking'];  
                $data['fr_month_amt'] = $row['amount'];
            }
            
           if($this->session->userdata('cr_is_admin') == 1) {  
            $query = $this->db->query(" 
                  select 
                    count(a.booking_id) as no_of_booking ,
                    sum(grand_total) as amount
                    from crit_booking_info as a 
                    left join crit_user_info as b on b.user_id = a.created_by
                    where a.`status` != 'Delete' and DATE_FORMAT(a.booking_date,'%Y-%m') = '". date('Y-m',strtotime('-1 months'))."'
                     
                    order by a.booking_id ;
            ");
            } elseif($this->session->userdata('cr_is_admin') == '111')  { 
                $query = $this->db->query(" 
                  select 
                    count(a.booking_id) as no_of_booking ,
                    sum(grand_total) as amount
                    from crit_booking_info as a 
                    left join crit_user_info as b on b.user_id = a.created_by
                    where a.`status` != 'Delete' and DATE_FORMAT(a.booking_date,'%Y-%m') = '". date('Y-m',strtotime('-1 months'))."'
                    and a.origin_state_code = '". $this->session->userdata('cr_pstate') ."' 
                    order by a.booking_id ;
                ");
            } else {
                 $query = $this->db->query(" 
                  select 
                    count(a.booking_id) as no_of_booking ,
                    sum(grand_total) as amount
                    from crit_booking_info as a 
                    left join crit_user_info as b on b.user_id = a.created_by
                    where a.`status` != 'Delete' and DATE_FORMAT(a.booking_date,'%Y-%m') = '". date('Y-m',strtotime('-1 months'))."'
                    and b.franchise_id = '". $this->session->userdata('cr_franchise_id') ."' 
                    order by a.booking_id ;
                ");
            }
             
    
            foreach($query->result_array() as $row)
            {
                 
                $data['fr_last_month_amt'] = $row['amount'];
            }
            
           
           $sql = "
                select 
                a.manifest_date,
                a.manifest_no,
                a.from_city_code,
                GROUP_CONCAT(a.awbno) as awbno,
                sum(c.no_of_pieces) as no_of_pieces,
                sum(c.weight) as weight,
                b.co_loader_name as co_loader,
                a.co_loader_awb_no,
                a.co_loader_remarks 
                from crit_manifest_info as a 
                left join crit_co_loader_info as b on b.co_loader_id = a.co_loader_id
                left join crit_booking_info as c on c.awbno = a.awbno
                where a.m_status = 'Open Manifest'
                and a.to_city_code = '". $this->session->userdata('cr_branch_code') ."'  
                and a.awbno != ''
                group by a.manifest_no 
                order by a.manifest_date asc
           ";  
           
           $query = $this->db->query($sql);
           
           $data['incoming_manifest'] = array();
           
           foreach($query->result_array() as $row)
            {                 
                $data['incoming_manifest'][] = $row;
            }   
            
            $sql = "
                select 
                c.booking_date,
                a.received_date,
                a.from_city_code,
                a.to_city_code,
                c.dest_pincode,
                a.awbno,
                c.dest_pincode,
                c.no_of_pieces,
                c.weight
                from crit_manifest_info as a 
                left join crit_co_loader_info as b on b.co_loader_id = a.co_loader_id
                left join crit_booking_info as c on c.awbno = a.awbno
                where a.m_status = 'Received Manifest'
                and a.to_city_code =  '". $this->session->userdata('cr_branch_code') ."' 
                and not exists( select * from crit_drs_info as z where z.awbno = a.awbno )
                order by c.booking_date desc
            ";    
            
            $query = $this->db->query($sql);
            
            $data['drs_to_be_prepared'] = array();
           
            foreach($query->result_array() as $row)
            {                 
                $data['drs_to_be_prepared'][] = $row;
            } 
            
            $sql = "
                select 
                a.drs_date, 
                a.drs_time , 
                a.awbno, 
                c.dest_pincode,
                c.no_of_pieces,
                c.weight,
                b.first_name as delivery_by
                from crit_drs_info as a 
                left join crit_user_info as b on b.user_id = a.delivery_by
                left join crit_booking_info as c on c.awbno = a.awbno
                where a.drs_status = 'Out for Delivery'
                and a.branch_city_code =  '". $this->session->userdata('cr_branch_code') ."'  
                order by a.drs_date , a.drs_time
            ";    
            
            $query = $this->db->query($sql);
            
            $data['out_for_delivery'] = array();
           
            foreach($query->result_array() as $row)
            {                 
                $data['out_for_delivery'][] = $row;
            } 
            
            
            $sql = "
                    select 
                    a.state_name,                
                    a.state_code             
                    from crit_states_info as a  
                    left join crit_franchise_info as b on b.state_code = a.state_code 
                    where a.status = 'Active' and b.status = 'Active'   
                    group by a.state_name          
                    order by a.state_name asc               
            "; 
            
            $query = $this->db->query($sql);
           
            foreach ($query->result_array() as $row)
            {
                $data['state_opt'][$row['state_code']] = $row['state_name']. ' [ ' . $row['state_code'] . ' ]';     
            }
            
            
            
          /*  if($this->input->post('srch_state') != ''){
                $data['srch_state'] = $this->input->post('srch_state'); 
            } else {
                $data['srch_state'] = "KL";
            }
            
            $sql = '
                select 
                b.franchise_type_name,
                a.contact_person,
                a.mobile,
                a.phone,
                a.email,
                a.address,
                a.branch_code,
                a.city_code         
                from  crit_franchise_info as a
                left join crit_franchise_type_info as b on b.franchise_type_id = a.franchise_type_id
                where a.status = "Active" and b.`status` = "Active"
                and a.state_code = "'. $data['srch_state'] .'"    
                order by b.franchise_type_name asc  
            
            ';
            
            $query = $this->db->query($sql);
           
            foreach ($query->result_array() as $row)
            {
                $data['franchise_info'][] = $row;     
            }
            */
            
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
        
        
        if($this->session->userdata('cr_is_admin') == 1) { 
        
            $sql = "
                select DISTINCT 
                a.wallet_payment_transfer_id,
                a.payment_date,
                a.amount,
                a.pay_mode,
                a.pay_photo,
                a.remarks,
                c.franchise_type_name, 
                b.contact_person,
                b.state_code,
                b.city_code,
                d.hub_branch_name as city
                from wallet_payment_transfer_info as a 
                left join crit_franchise_info as b on b.franchise_id = a.franchise_id 
                left join crit_franchise_type_info as c on c.franchise_type_id = b.franchise_type_id
                left join crit_hub_branch_info as d on d.hub_branch_code = b.branch_code and d.`status` = 'Active'
                where a.status != 'Delete' 
                and a.status = 'Pending'
                order by a.payment_date desc 
            ";
            $query = $this->db->query($sql);
           
            foreach ($query->result_array() as $row)
            {
                $data['wallet_payment_info'][] = $row;     
            }
            
            
            $sql =" select  
                    count(a.international_consignment_id) as cnt
                    from crit_international_consignment_info as a 
                    where a.`status` != 'Delete'  
                    and a.booking_date between '". date('Y-m-01')."' and '". date('Y-m-d')."'
                    ";
                    
            $query = $this->db->query($sql);
            
            $data['intl_booking'] = 0;
           
            foreach ($query->result_array() as $row)
            {
                $data['intl_booking'] = $row['cnt'];     
            }   
        
        }
        
        $data['wallet'] = 0;
        
        if($this->session->userdata('cr_is_admin') == 11) {  
            
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
            ((IFNULL(c.amount, 0)  + SUM(IFNULL(f.topup, 0))) -  SUM(IFNULL(e.int_chrg, 0))) AS wallet
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
                    w.`status` = 'Active' AND w.franchise_id = e1.franchise_id AND w.op_date <= '". date('Y-m-d')."'
                ORDER BY
                    w.op_date
                DESC
                    ,
                    w.opening_balance_wallet_id
                DESC
            LIMIT 1
            ) AND DATE_SUB('". date('Y-m-d')."', INTERVAL 0 DAY)
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
                    w.`status` = 'Active' AND w.franchise_id = e2.franchise_id AND w.op_date <= '". date('Y-m-d')."'
                ORDER BY
                    w.op_date
                DESC
                    ,
                    w.opening_balance_wallet_id
                DESC
            LIMIT 1
            ) AND DATE_SUB('". date('Y-m-d')."', INTERVAL 0 DAY)
        GROUP BY
            e2.franchise_id
        ) AS f
        ON
            f.franchise_id = a.franchise_id
        WHERE
            a.`status` = 'Active' AND a.franchise_id = '". $this->session->userdata('cr_franchise_id') ."'
        GROUP BY
            a.franchise_id
        ORDER BY
            a.franchise_type_id 
        
        "; 
            
            $query = $this->db->query($sql);
            
            $data['wallet'] = 0;
           
            foreach ($query->result_array() as $row)
            {
                $data['wallet'] = $row['wallet'];     
                if($row['wallet'] < 0)
                    $data['wallet_msg'] = " Your wallet balance is negative ( Rs. ". round($row['wallet'],2) ."/-). Please recharge it immediately to prevent your in-transit shipments from being locked. If you have already recharged, please ignore this message and kindly re-login.";
                else 
                    $data['wallet_msg'] = '';  
            }
            
            
            $sql =" select  
                    b.franchise_id, 
                    count(a.international_consignment_id) as cnt
                    from crit_international_consignment_info as a
                    left join crit_user_info as b on b.user_id = a.created_by 
                    where a.`status` != 'Delete' 
                    and b.franchise_id = '". $this->session->userdata('cr_franchise_id') ."' 
                    and a.booking_date between '". date('Y-m-01')."' and '". date('Y-m-d')."'
                    GROUP by b.franchise_id  ";
                    
            $query = $this->db->query($sql);
            
            $data['intl_booking'] = 0;
           
            foreach ($query->result_array() as $row)
            {
                $data['intl_booking'] = $row['cnt'];     
            }     
            
            
             $sql = 
                "  
                select
                count(a.hd_ticket_id) as cnt
                from hd_ticket_info as a
                where a.`status` != 'Delete'
                and a.`status` != 'Close' 
                and ( 
                    a.to_franchise_id = '". $this->session->userdata('cr_franchise_id') ."' 
                    or 
                    a.frm_franchise_id = '". $this->session->userdata('cr_franchise_id') ."' 
                    or 
                     FIND_IN_SET('". $this->session->userdata('cr_franchise_id') ."', a.share_to) 
                    )
                ";
                    
            $query = $this->db->query($sql);
       
            $data['ticket'] = 0;
       
            foreach($query->result_array() as $row)
            {                 
                $data['ticket'] = $row['cnt'];
            }   
        
        }
            
            
            
             
        $this->load->view('page/dashboard' , $data);
	}
    
    
    public function change_password()
	{
	    if(!$this->session->userdata('cr_logged_in'))  redirect();
        
         
        	    
        $data['js'] = 'change-password.inc';   
         
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'country_name' => $this->input->post('country_name'),
                    'status' => $this->input->post('status'),                 
            );
            
            $this->db->where('country_id', $this->input->post('country_id'));
            $this->db->update('crit_country_info', $upd); 
                            
            redirect('logout'); 
        } 
         
      
        $data['user_name'] = $this->session->userdata('cr_user_name');
        $data['login_name'] = $this->session->userdata('cr_login_name');
        $data['user_id'] = $this->session->userdata('cr_user_id');
        
        $this->load->view('page/change-password',$data); 
	} 
    
   	public function calender()
	{
	   if(!$this->session->userdata('cr_logged_in'))  redirect();
       $data['js'] = 'calender.inc';  
       
       
       
       $this->load->view('page/calender',$data); 
    }
    
    
    
    
}
