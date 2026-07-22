<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get Tracking Idle Report
     * Returns shipments within a date range with their latest tracking status and idle days.
     */
    public function get_tracking_idle_report($from_date, $to_date) {
        $sql = "
            SELECT 
                sm.id as shipment_id,
                sm.awb_number,
                sm.booking_date,
                sm.status as master_status,
                c.name as customer_name,
                c.company_name,
                st.status as tracking_status,
                st.date_time as last_update_date,
                st.location as current_location,
                DATEDIFF(CURRENT_DATE(), DATE(st.date_time)) as idle_days,
                dest.country_name as destination_country
            FROM shipment_master sm
            LEFT JOIN customers c ON sm.customer_id = c.id
            LEFT JOIN countries dest ON sm.destination_country_id = dest.id
            LEFT JOIN (
                SELECT t1.*
                FROM shipment_tracking t1
                INNER JOIN (
                    SELECT shipment_id, MAX(date_time) as max_date
                    FROM shipment_tracking
                    GROUP BY shipment_id
                ) t2 ON t1.shipment_id = t2.shipment_id AND t1.date_time = t2.max_date
                GROUP BY t1.shipment_id
            ) st ON sm.id = st.shipment_id
            WHERE sm.booking_date >= ? AND sm.booking_date <= ?
            AND sm.deleted_at IS NULL
            ORDER BY idle_days DESC, sm.booking_date DESC
        ";
        
        $query = $this->db->query($sql, array($from_date, $to_date));
        return $query->result_array();
    }
}
