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
$route['default_controller'] = 'main_ctrl';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['1'] = 'main_ctrl';
$route['2'] = 'main_ctrl';
$route['keluar'] = 'main_ctrl/logout';
$route['main/captcha/(:any)'] = 'main_ctrl/captcha/$1';
$route['eklaim/buat_klaim'] = 'eklaim_ctrl/buat_klaim';
$route['eklaim/get_kunjungan'] = 'eklaim_ctrl/get_kunjungan';



$route['eklaim/buat_klaim/(:any)/(:any)/input'] = 'eklaim_ctrl/input';
$route['eklaim/buat_klaim/(:any)/(:any)/update'] = 'eklaim_ctrl/update';
$route['eklaim/buat_klaim/(:any)/(:any)'] = 'eklaim_ctrl/buat_klaim/$1/$2';

$route['eklaim/get_claim_status/(:any)'] = 'eklaim_ctrl/get_claim_status/$1';
$route['eklaim/sitb_validate/(:any)/(:any)'] = 'eklaim_ctrl/sitb_validate/$1/$2';


$route['eklaim/klaim/(:any)'] = 'eklaim_ctrl/$1';
$route['eklaim/klaim/grouper/(:num)'] = 'eklaim_ctrl/grouper/$1';

$route['eklaim/new_claim'] = 'eklaim_ctrl/new_claim';
$route['eklaim/set_claim'] = 'eklaim_ctrl/set_claim';
$route['eklaim/grouper/(:num)'] = 'eklaim_ctrl/grouper/$1';
$route['eklaim/finalisasi'] = 'eklaim_ctrl/finalisasi';
$route['eklaim/reedit_claim'] = 'eklaim_ctrl/reedit_claim';
$route['eklaim/claim_print'] = 'eklaim_ctrl/claim_print';
$route['eklaim/delete_claim'] = 'eklaim_ctrl/delete_claim';
$route['eklaim/download'] = 'eklaim_ctrl/download';
$route['eklaim/referensi_eklaim'] = 'eklaim_ctrl/referensi_eklaim';

$route['eklaim/(:any)'] = 'eklaim_ctrl';
$route['eklaim/(:any)/data'] = 'eklaim_ctrl/data';

$route['eklaim/(:any)/(:any)'] = 'eklaim/$1_ctrl/$2';
$route['eklaim/(:any)/(:any)/(:any)'] = 'eklaim/$1_ctrl/index/$2/$3';
$route['eklaim/(:any)/(:any)/(:any)/input'] = 'eklaim/$1_ctrl/input';
$route['eklaim/(:any)/(:any)/(:any)/update'] = 'eklaim/$1_ctrl/update';
