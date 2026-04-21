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
|	https://codeigniter.com/userguide3/general/routing.html
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

$route['login'] = 'login';
$route['login/authenticate'] = 'login/authenticate';
$route['login/register'] = 'login/register';
$route['login/store_register'] = 'login/store_register';
$route['login/forgot-password'] = 'login/forgot_password';
$route['login/send-reset-code'] = 'login/send_reset_code';
$route['login/verify-reset-code'] = 'login/verify_reset_code';
$route['login/reset-password'] = 'login/reset_password';
$route['login/update-password'] = 'login/update_password';
$route['logout'] = 'login/logout';

$route['dashboard'] = 'dashboard';
$route['referral'] = 'dashboard/referral';
$route['dashboard/mark-notifications-read'] = 'dashboard/mark_notifications_read';
$route['questions'] = 'questions';
$route['questions/category-data/(:num)'] = 'questions/category_data/$1';
$route['questions/answer/(:num)'] = 'questions/answer/$1';
$route['questions/save-answers'] = 'questions/save_answers';
$route['wallet'] = 'wallet';
$route['wallet/deposit'] = 'wallet/deposit';
$route['wallet/save-bank-details'] = 'wallet/save_bank_details';
$route['wallet/request-withdrawal'] = 'wallet/request_withdrawal';
$route['profile'] = 'profile';
$route['profile/update'] = 'profile/update';
$route['profile/change-password'] = 'profile/change_password';
$route['history'] = 'history';

$route['admin'] = 'admin/login';
$route['admin/login'] = 'admin/login';
$route['admin/login/authenticate'] = 'admin/login/authenticate';
$route['admin/logout'] = 'admin/login/logout';
$route['admin/dashboard'] = 'admin/dashboard';
$route['admin/withdrawals'] = 'admin/withdrawals';
$route['admin/withdrawals/approve/(:num)'] = 'admin/withdrawals/approve/$1';
$route['admin/withdrawals/reject/(:num)'] = 'admin/withdrawals/reject/$1';
$route['admin/users'] = 'admin/users';
$route['admin/users/view/(:num)'] = 'admin/users/view/$1';
$route['admin/users/edit/(:num)'] = 'admin/users/edit/$1';
$route['admin/users/update/(:num)'] = 'admin/users/update/$1';
$route['admin/users/delete/(:num)'] = 'admin/users/delete/$1';
$route['admin/categories'] = 'admin/categories';
$route['admin/categories/create'] = 'admin/categories/create';
$route['admin/categories/update/(:num)'] = 'admin/categories/update/$1';
$route['admin/categories/delete/(:num)'] = 'admin/categories/delete/$1';
$route['admin/questions'] = 'admin/questions';
$route['admin/questions/add'] = 'admin/questions/add';
$route['admin/questions/view'] = 'admin/questions/view';
$route['admin/questions/detail/(:num)'] = 'admin/questions/detail/$1';
$route['admin/questions/detail-users/(:num)'] = 'admin/questions/detail_users/$1';
$route['admin/questions/detail-users-data/(:num)'] = 'admin/questions/detail_users_data/$1';
$route['admin/questions/create'] = 'admin/questions/create';
$route['admin/questions/edit/(:num)'] = 'admin/questions/edit/$1';
$route['admin/questions/update/(:num)'] = 'admin/questions/update/$1';
$route['admin/questions/save-answer-keys'] = 'admin/questions/save_answer_keys';
$route['admin/questions/live-stats/(:num)'] = 'admin/questions/live_stats/$1';
$route['admin/questions/delete/(:num)'] = 'admin/questions/delete/$1';
$route['admin/referrals/add'] = 'admin/referrals/add';
$route['admin/referrals/save'] = 'admin/referrals/save';
$route['admin/referrals/list'] = 'admin/referrals/list';
$route['admin/profile'] = 'admin/profile';
$route['admin/profile/update'] = 'admin/profile/update';
$route['admin/profile/change-password'] = 'admin/profile/change_password';
$route['admin/questions/get_questions_by_category/(:num)'] = 'admin/questions/get_questions_by_category/$1';
