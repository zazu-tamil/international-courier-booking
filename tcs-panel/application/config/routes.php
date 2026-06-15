<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['logout'] = 'login/logout';

$route['in-scan'] = 'booking/in_scan';

$route['in-scan-entry'] = 'booking/in_scan_entry';

$route['in-scan-list'] = 'booking/in_scan_list';
$route['in-scan-list/(:num)'] = 'booking/in_scan_list/$1';

$route['in-scan-edit/(:num)'] = 'booking/in_scan_edit/$1';

$route['generate-manifest'] = 'booking/generate_manifest';
$route['open-manifest'] = 'booking/open_manifest';
$route['receive-manifest'] = 'booking/receive_manifest';

$route['delivery-runsheet'] = 'booking/delivery_runsheet';
$route['delivery-updation'] = 'booking/delivery_updation';

$route['tracking-entry'] = 'booking/tracking_entry';
$route['awb-tracking'] = 'booking/tracking'; 

$route['b2h-manifest'] = 'booking/b2h_manifest';
$route['b2h-manifest-list'] = 'booking/b2h_manifest_list';

$route['h2h-manifest'] = 'booking/h2h_manifest';
$route['h2h-manifest-list'] = 'booking/h2h_manifest_list';

$route['h2b-manifest'] = 'booking/h2b_manifest';
$route['h2b-manifest-list'] = 'booking/h2b_manifest_list';

$route['b2b-manifest'] = 'booking/b2b_manifest';
$route['b2b-manifest-list'] = 'booking/b2b_manifest_list';

$route['create-booking'] = 'booking/create_booking';

$route['customer-invoice-generate'] = 'booking/customer_invoice_generate';

$route['customer-invoice-list'] = 'booking/customer_invoice_list';
$route['customer-invoice-list/(:num)'] = 'booking/customer_invoice_list/$1';

$route['line-haul-list'] = 'booking/line_haul_list';
$route['line-haul-list/(:num)'] = 'booking/line_haul_list/$1';


$route['ts-invoice-generate'] = 'booking/ts_invoice_generate';

$route['ts-invoice-list'] = 'booking/ts_invoice_list';
$route['ts-invoice-list/(:num)'] = 'booking/ts_invoice_list/$1';

$route['wallet-payment-transfer-list'] = 'booking/wallet_payment_transfer_list';
$route['wallet-payment-transfer-list/(:num)'] = 'booking/wallet_payment_transfer_list/$1';

$route['opening-balance-wallet-list'] = 'booking/opening_balance_wallet_list';
$route['opening-balance-wallet-list/(:num)'] = 'booking/opening_balance_wallet_list/$1';

$route['print-ts-invoice/(:num)'] = 'booking/print_ts_invoice/$1';

$route['drs-list'] = 'booking/drs_list';
$route['drs-list/(:num)'] = 'booking/drs_list/$1';

$route['print-invoice/(:num)'] = 'booking/print_invoice/$1'; 
$route['print-awb-label/(:num)'] = 'booking/print_awb_label/$1'; 
$route['print-awbno/(:num)'] = 'booking/print_awbno/$1'; 


$route['recycle-booking-list'] = 'booking/recycle_booking_list';
$route['recycle-booking-list/(:num)'] = 'booking/recycle_booking_list/$1';

$route['coco-delivery-runsheet-list'] = 'booking/coco_delivery_runsheet_list';
$route['coco-delivery-runsheet-list/(:num)'] = 'booking/coco_delivery_runsheet_list/$1';

$route['co-courier-drs-status-list'] = 'master/co_courier_drs_status_list';
$route['co-courier-drs-status-list/(:num)'] = 'master/co_courier_drs_status_list/$1';


$route['attendance-report'] = 'reports/attendance_report';

$route['customer-booking-report'] = 'reports/customer_wise_booking_report';
$route['customer-booking-report/(:num)'] = 'reports/customer_wise_booking_report/$1';

$route['franchise-booking-report'] = 'reports/franchise_wise_booking_report';
$route['franchise-booking-report/(:num)'] = 'reports/franchise_wise_booking_report/$1';

$route['franchise-NDR-report'] = 'reports/franchise_wise_ndr_report';
$route['co-loader-wise-booking-report'] = 'reports/co_loader_report';

$route['city-wise-booking-summary'] = 'reports/city_wise_booking_summary';
$route['city-wise-booking-summary/(:num)'] = 'reports/city_wise_booking_summary/$1';

$route['manifest-report'] = 'reports/manifest_report';
$route['manifest-report/(:num)'] = 'reports/manifest_report/$1';

$route['drs-report'] = 'reports/drs_report';
$route['drs-report/(:num)'] = 'reports/drs_report/$1';

$route['inbound-consignment-report'] = 'reports/inbound_consignment_report';
$route['servicable-pincode-report'] = 'reports/servicable_pincode_report';
$route['line-haul-report'] = 'reports/line_haul_booking_report';
$route['co-courier-delivery-report'] = 'reports/co_courier_delivery_report';

$route['print-manifest/(:num)'] = 'reports/print_manifest/$1';
$route['print-drs/(:num)'] = 'reports/print_drs/$1';

$route['wallet-statement'] = 'reports/wallet_statement';


$route['dash'] = 'dashboard';

$route['change-password'] = 'dashboard/change_password';
$route['calender'] = 'dashboard/calender';


$route['account-head-list'] = 'accounts/account_head_list';
$route['account-head-list/(:num)'] = 'accounts/account_head_list/$1';

$route['sub-account-head-list'] = 'accounts/sub_account_head_list';
$route['sub-account-head-list/(:num)'] = 'accounts/sub_account_head_list/$1';

$route['cash-inward-list'] = 'accounts/cash_inward_list';
$route['cash-inward-list/(:num)'] = 'accounts/cash_inward_list/$1';

$route['cash-outward-list'] = 'accounts/cash_outward_list';
$route['cash-outward-list/(:num)'] = 'accounts/cash_outward_list/$1';

$route['cash-ledger'] = 'accounts/cash_ledger';

$route['international-rate-calc'] = 'international/international_rate_calc';

$route['international-tracking-status'] = 'international/intl_tracking_status_list';
$route['international-tracking-status/(:num)'] = 'international/intl_tracking_status_list/$1';

$route['package-weight-list'] = 'international/package_weight_list';
$route['package-weight-list/(:num)'] = 'international/package_weight_list/$1';

$route['service-provider-list'] = 'international/service_provider_list'; 
$route['service-provider-list/(:num)'] = 'international/service_provider_list/$1'; 

$route['international-rate-list'] = 'international/international_rate_list'; 
$route['international-rate-list/(:num)'] = 'international/international_rate_list/$1'; 

$route['international-rate-list-v2'] = 'international/international_rate_list_v2'; 
$route['service-provider-charges'] = 'international/service_provider_charges'; 

$route['international-consignment-list'] = 'international/consignment_list'; 
$route['international-consignment-list/(:num)'] = 'international/consignment_list/$1'; 

$route['recycle-intl-booking-list'] = 'international/recycle_intl_booking_list'; 
$route['recycle-intl-booking-list/(:num)'] = 'international/recycle_intl_booking_list/$1'; 

$route['oda-charges-list'] = 'international/oda_charges_list'; 
$route['oda-charges-list/(:num)'] = 'international/oda_charges_list/$1'; 

$route['ah-charges-list'] = 'international/ah_charges_list'; 
$route['ah-charges-list/(:num)'] = 'international/ah_charges_list/$1'; 

$route['print-intl-awbno/(:num)'] = 'international/print_intl_awbno/$1'; 


$route['get-data'] = 'general/get_data';
$route['send-enquiry-mail'] = 'general/send_enquiry_mail';

$route['get-api-service/(:any)'] = 'general/get_api_service/$1';

$route['update-data'] = 'general/update_data';
$route['insert-data'] = 'general/insert_data';
$route['delete-record'] = 'general/delete_record';
$route['get-content'] = 'general/get_content';
$route['get-charges'] = 'general/get_courier_charges';
$route['rate-calc'] = 'general/rate_calc';

$route['get-notice'] = 'general/get_notice';

$route['get-calender-data'] = 'general/get_calender_data';

$route['pincode-list'] = 'master/pincode_list';
$route['pincode-list/(:num)'] = 'master/pincode_list/$1';

$route['servicable-pincode-list'] = 'master/servicable_pincode_list';
$route['servicable-pincode-list/(:num)'] = 'master/servicable_pincode_list/$1';

$route['doc-upload-type-list'] = 'master/doc_upload_type_list';
$route['doc-upload-type-list/(:num)'] = 'master/doc_upload_type_list/$1';

$route['country-list'] = 'master/country_list';
$route['country-list/(:num)'] = 'master/country_list/$1'; 

$route['state-list'] = 'master/state_list'; 
$route['state-list/(:num)'] = 'master/state_list/$1'; 

$route['city-list'] = 'master/city_list'; 
$route['city-list/(:num)'] = 'master/city_list/$1'; 

$route['franchise-type-list'] = 'master/franchise_type_list'; 
$route['franchise-type-list/(:num)'] = 'master/franchise_type_list/$1'; 

$route['franchise-list'] = 'master/franchise_list'; 
$route['franchise-list/(:num)'] = 'master/franchise_list/$1'; 

$route['franchise-doc-upload-list'] = 'master/franchise_doc_upload_list'; 
$route['franchise-doc-upload-list/(:num)'] = 'master/franchise_doc_upload_list/$1'; 

$route['franchise-awbill-list'] = 'master/franchise_awbill_list'; 
$route['franchise-awbill-list/(:num)'] = 'master/franchise_awbill_list/$1'; 

$route['franchise-domestic-rate'] = 'master/franchise_domestic_rate'; 
$route['franchise-domestic-rate/(:num)'] = 'master/franchise_domestic_rate/$1';   

$route['agent-list'] = 'master/agent_list'; 
$route['agent-list/(:num)'] = 'master/agent_list/$1';  

$route['customer-list'] = 'master/customer_list'; 
$route['customer-list/(:num)'] = 'master/customer_list/$1';  
$route['customer-domestic-rate/(:num)'] = 'master/customer_domestic_rate/$1'; 
$route['customer-domestic-rate-v2/(:num)'] = 'master/customer_domestic_rate_v2/$1'; 

$route['transhipment-rate'] = 'master/franchises_transhipment_rate'; 


$route['hub-branch-list'] = 'master/hub_branch_list'; 
$route['hub-branch-list/(:num)'] = 'master/hub_branch_list/$'; 

$route['carrier-list'] = 'master/carrier_list'; 
$route['carrier-list/(:num)'] = 'master/carrier_list/$1'; 

$route['tracking-status-list'] = 'master/tracking_status_list'; 
$route['tracking-status-list/(:num)'] = 'master/tracking_status_list/$1'; 

$route['ndr-list'] = 'master/ndr_list'; 
$route['ndr-list/(:num)'] = 'master/ndr_list/$1'; 

$route['service-list'] = 'master/service_list'; 
$route['service-list/(:num)'] = 'master/service_list/$1'; 

$route['co-loader-list'] = 'master/co_loader_list'; 
$route['co-loader-list/(:num)'] = 'master/co_loader_list/$1';  

$route['co-courier-list'] = 'master/co_courier_list'; 
$route['co-courier-list/(:num)'] = 'master/co_courier_list/$1'; 

$route['package-type-list'] = 'master/package_type_list'; 
$route['package-type-list/(:num)'] = 'master/package_type_list/$1';

$route['product-type-list'] = 'master/product_type_list'; 
$route['product-type-list/(:num)'] = 'master/product_type_list/$1';

$route['customer-type-list'] = 'master/customer_type_list';
$route['customer-type-list/(:num)'] = 'master/customer_type_list/$1';

$route['commodity-type-list'] = 'master/commodity_type_list';
$route['commodity-type-list/(:num)'] = 'master/commodity_type_list/$1';

$route['notice-board-list'] = 'master/notice_board_list';
$route['notice-board-list/(:num)'] = 'master/notice_board_list/$1';

$route['domestic-rate'] = 'master/domestic_rate';


$route['hd-category-list'] = 'helpdesk/hd_category_list'; 
$route['hd-category-list/(:num)'] = 'helpdesk/hd_category_list/$1'; 

$route['ticket-list'] = 'helpdesk/ticket_list'; 
$route['ticket-list/(:num)'] = 'helpdesk/ticket_list/$1';

$route['ticket/(:num)'] = 'helpdesk/ticket_info/$1';



