<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
*/
$route['default_controller'] = 'auth/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Authentication Routes
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['register'] = 'auth/register';
$route['change-password'] = 'auth/change_password';

// Dashboards
$route['dashboard'] = 'dashboard/index';

// Master Data Modules
$route['branches'] = 'masters/branches';
$route['branches/add'] = 'masters/add_branch';
$route['branches/edit/(:num)'] = 'masters/edit_branch/$1';
$route['branches/delete/(:num)'] = 'masters/delete_branch/$1';
$route['branches/create-user'] = 'masters/create_branch_user';

$route['franchises'] = 'masters/franchises';
$route['franchises/add'] = 'masters/add_franchise';
$route['franchises/edit/(:num)'] = 'masters/edit_franchise/$1';
$route['franchises/delete/(:num)'] = 'masters/delete_franchise/$1';
$route['franchises/settlement'] = 'masters/franchise_settlements';

$route['countries'] = 'masters/countries';
$route['countries/add'] = 'masters/add_country';
$route['countries/edit/(:num)'] = 'masters/edit_country/$1';

$route['partners'] = 'masters/partners';
$route['partners/add'] = 'masters/add_partner';
$route['partners/edit/(:num)'] = 'masters/edit_partner/$1';

$route['rates'] = 'masters/rates';
$route['rates/add'] = 'masters/add_rate';
$route['rates/edit/(:num)'] = 'masters/edit_rate/$1';
$route['rates/delete/(:num)'] = 'masters/delete_rate/$1';

$route['terms'] = 'masters/terms';
$route['terms/add'] = 'masters/add_terms';
$route['terms/edit/(:num)'] = 'masters/edit_terms/$1';

$route['restricted-items'] = 'masters/restricted_items';
$route['restricted-items/add'] = 'masters/add_restricted_item';
$route['restricted-items/edit/(:num)'] = 'masters/edit_restricted_items/$1';
$route['restricted-items/delete/(:num)/(:any)'] = 'masters/delete_restricted_item/$1/$2';
$route['app-settings'] = 'masters/app_settings';
$route['notification-logs'] = 'masters/notification_logs';

$route['roles'] = 'masters/roles';
$route['roles/add'] = 'masters/add_role';
$route['roles/edit/(:num)'] = 'masters/edit_role/$1';
$route['roles/delete/(:num)'] = 'masters/delete_role/$1';
$route['roles/save-permissions'] = 'masters/save_role_permissions';

// Shipment Booking & Handling
$route['shipment/calculator'] = 'shipment/calculator';
$route['shipment/barcode/(:any)'] = 'shipment/barcode/$1';
$route['shipments'] = 'shipment/list';
$route['shipments/book'] = 'shipment/book';
$route['shipments/edit/(:num)'] = 'shipment/edit/$1';
$route['shipments/delete/(:num)'] = 'shipment/delete/$1';
$route['shipments/view/(:num)'] = 'shipment/view/$1';
$route['shipments/send-login/(:num)'] = 'shipment/send_login/$1';
$route['shipments/tracking/(:num)'] = 'shipment/tracking/$1';
$route['shipments/print-label/(:num)'] = 'shipment/print_label/$1';
$route['shipments/print-invoice/(:num)'] = 'shipment/print_invoice/$1';
$route['shipments/print-customs/(:num)'] = 'shipment/print_customs/$1';
$route['shipments/print-awb/(:num)'] = 'shipment/print_awb/$1';
$route['shipments/upload-doc/(:num)'] = 'shipment/upload_document/$1';

// Customer Portal Verification & Files
$route['customer/verify/(:num)'] = 'customer/verify_shipment/$1';
$route['customer/submit-signature'] = 'customer/submit_signature';
$route['customer/submit-verification'] = 'customer/submit_verification';
$route['customer/kyc'] = 'customer/kyc_upload';
$route['customer/wallet'] = 'customer/wallet';
$route['customer/statement'] = 'customer/statement';

// KYC Management (Staff Only)
$route['kyc-requests'] = 'customer/kyc_requests';
$route['kyc-requests/review/(:num)'] = 'customer/kyc_review/$1';

// Customer Management (Staff Only)
$route['customers'] = 'customer/list';
$route['customers/edit/(:num)'] = 'customer/edit/$1';
$route['customers/delete/(:num)'] = 'customer/delete/$1';
$route['kyc-requests/manage/(:num)'] = 'customer/manage_kyc_staff/$1';
$route['kyc-requests/delete/(:num)'] = 'customer/delete_kyc_staff/$1';

// Payments
$route['payments/receive/(:num)'] = 'customer/receive_payment/$1';

// Public Tracking
$route['tracking'] = 'tracking/index';
$route['tracking/status'] = 'tracking/status';

// REST API Module V1
$route['api/v1/login'] = 'api/login';
$route['api/v1/register'] = 'api/register';
$route['api/v1/dashboard'] = 'api/dashboard';
$route['api/v1/shipment'] = 'api/shipment_details';
$route['api/v1/rates'] = 'api/rates';
$route['api/v1/tracking'] = 'api/tracking';
$route['api/v1/declaration-accept'] = 'api/declaration_accept';
$route['api/v1/terms-accept'] = 'api/terms_accept';
$route['api/v1/signature'] = 'api/signature_upload';
$route['api/v1/verify-otp'] = 'api/verify_otp';
