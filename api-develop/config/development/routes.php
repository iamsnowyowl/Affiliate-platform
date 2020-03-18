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
$route['default_controller'] = 'Index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

## profile
$route['me']['get'] = 'Users/get_user_profile';
$route['me']['put'] = 'Users/update_profile';
$route['me/password']['put'] = 'Users/update_password_profile';
$route['me/picture']['put'] = 'Users/update_profile_picture';
$route['me/refresh_token/(:any)']['put'] = 'Users/refresh_token/$1';
$route['me/signature']['get'] = 'Users/get_own_signature';
$route['me/persyaratan_umum/(:any)']['get'] = 'Persyaratan_umums/get_own_persyaratan_umum_detail';
$route['me/persyaratan_umum']['get'] = 'Persyaratan_umums/get_own_persyaratan_umum_list';
$route['me/persyaratan_umum']['post'] = 'Persyaratan_umums/create_own_persyaratan_umum_session';
$route['me/persyaratan_umum/(:any)']['delete'] = 'Persyaratan_umums/delete_soft_persyaratan_umum_by_id/$1';

## development phase only
$route['public/broadcast_google_cloud_message']['get'] = 'Google/broadcast_google_cloud_message';
$route['public/debug']['get'] = 'Debugger';
$route['public/debug/test_digest']['get'] = 'Debugger/test_digest';

#TAS integration
$route['public/users/(:num)/accessors']['get'] = 'Accessors/get_public_accessor_detail/$1';
$route['public/users/accessors']['get'] = 'Accessors/get_public_accessor_list';
$route['public/competence/fields/(:num)']['get'] = 'Competence_fields/get_public_competence_field_detail/$1';
$route['public/competence/fields']['get'] = 'Competence_fields/get_public_competence_field_list';
$route['public/tuks/logo/(:any)/view']['get'] = 'Tuks/view_tuk_logo/$1';

## public access
$route['public/downloads/applicant/(:num)/assessment/(:any)/apl01']['get'] = 'Applicant_portfolios/download_apl01/$1';
$route['public/users/accessors']['post'] = 'Accessors/create_accessor_public';
$route['public/users/applicants']['post'] = 'Applicants/create_applicant_public';
$route['public/users']['post'] = 'Users/create_user_public';
$route['public/accessor/competences/(:num)/picture']['get'] = 'Accessor_competences/accessor_competence_picture/$1';
$route['public/setup/password/(:any)'] = 'Users/setup_password/$1';
$route['public/users/forgot_password']['post'] = 'Users/forgot_password';

##########BEGIN SYSTEM DEPEDENCIES MODULE############

## permission 
$route['permissions/(:num)']['get'] = 'Permissions/get_permission_detail/$1';
$route['permissions/(:num)']['put'] = 'Permissions/update_permission_by_id/$1';
$route['permissions/(.+)']['delete'] = 'Permissions/delete_permission_by_id/$1';
$route['permissions']['get'] = 'Permissions/get_permission_list';
$route['permissions/count']['get'] = 'Permissions/permission_count';
$route['permissions']['post'] = 'Permissions/create_permission_session';

## module 
$route['modules/(:num)']['get'] = 'My_modules/get_module_detail/$1';
$route['modules/(:num)']['put'] = 'My_modules/update_module_by_id/$1';
$route['modules/(.+)']['delete'] = 'My_modules/delete_module_by_id/$1';
$route['modules']['get'] = 'My_modules/get_module_list';
$route['modules/count']['get'] = 'My_modules/module_count';
$route['modules']['post'] = 'My_modules/create_module_session';


## submodule 
$route['submodules/(:num)']['get'] = 'Submodules/get_submodule_detail/$1';
$route['submodules/(:num)']['put'] = 'Submodules/update_submodule_by_id/$1';
$route['submodules/(.+)']['delete'] = 'Submodules/delete_submodule_by_id/$1';
$route['submodules']['get'] = 'Submodules/get_submodule_list';
$route['submodules/count']['get'] = 'Submodules/submodule_count';
$route['submodules']['post'] = 'Submodules/create_submodule_session';

## users 
$route['users/login']['post'] = 'Users/user_login';
$route['users/logout']['post'] = 'Users/user_logout';
$route['users/(:num)']['get'] = 'Users/get_user_detail/$1';

## users has accessor_competences
$route['users/(:num)/accessor/competences']['get'] = 'Accessor_competences/get_accessor_competence_list_by_user_id/$1';

## users has permissions
$route['users/(:num)/permissions']['get'] = 'Users/get_user_permission_module/$1';
$route['users/(:num)/permissions/(:any)']['get'] = 'Users/get_user_permission_submodule/$1/$2';

$route['users/(:num)/picture']['get'] = 'Users/user_picture/$1';
$route['users/(:num)/permission']['get'] = 'Permission/user_permission/$1';
$route['users/(:num)']['put'] = 'Users/update_user_by_id/$1';
$route['users/(:num)/password']['put'] = 'Users/update_user_password_by_id/$1';
$route['users/assignments/(.+)']['delete'] = 'Assignments/delete_assignment_by_user_id/$1';
$route['users/(:num)']['delete'] = 'Users/delete_user_by_id/$1';
$route['users']['get'] = 'Users/get_user_list';
$route['users/assign']['get'] = 'Users/get_user_assign_list';
$route['users/not_assign']['get'] = 'Users/get_user_not_assign_list';
$route['users/count']['get'] = 'Users/user_count';
$route['users']['post'] = 'Users/create_user_session';
$route['users/(:num)/picture']['post'] = 'Users/upload_user_picture/$1';
$route['deleted/users']['get'] = 'Users/get_user_deleted_list';
$route['restore/users/(:any)']['put'] = 'Users/update_deleted_by_id/$1';


## role 
$route['me/roles/(:num)']['get'] = 'Roles/get_own_role_detail/$1';
$route['me/roles']['get'] = 'Roles/get_own_role_list';
$route['me/roles/count']['get'] = 'Roles/get_own_role_count';
$route['me/roles/(:num)']['put'] = 'Roles/update_own_role_by_id/$1';
$route['me/roles/(.+)']['delete'] = 'Roles/delete_own_role_by_id/$1';

$route['roles/(:num)']['get'] = 'Roles/get_role_detail/$1';
$route['roles/(:num)']['put'] = 'Roles/update_role_by_id/$1';
$route['roles/(.+)']['delete'] = 'Roles/delete_role_by_id/$1';
$route['roles']['get'] = 'Roles/role_list';
$route['roles/count']['get'] = 'Roles/get_role_count';
$route['roles']['post'] = 'Roles/create_role_session';

##########END SYSTEM DEPEDENCIES MODULE############
## master data flow 
$route['flows/(:any)']['get'] = 'Flows/get_flow_detail/$1';
$route['flows/(:any)']['put'] = 'Flows/update_flow_by_id/$1';
$route['flows/(:any)/(:any)']['delete'] = 'Flows/delete_hard_flow_by_id/$1/$2';
$route['flows/(:any)']['delete'] = 'Flows/delete_soft_flow_by_id/$1';
$route['flows']['get'] = 'Flows/get_flow_list';
$route['flows/count']['get'] = 'Flows/get_flow_count';
$route['flows']['post'] = 'Flows/create_flow_session';

## Dashboard
$route['dashboards/assessments']['get'] = 'Dashboards/get_assessment_list';
$route['dashboards/assessments/(:any)']['get'] = 'Dashboards/get_assessment_detail/$1';

## assessment 
$route['me/assessments']['get'] = 'Assessments/owner_get_assessment_list';
$route['me/assessments/(:any)']['get'] = 'Assessments/owner_get_assessment_detail/$1';
$route['me/assessments/(:any)/applicants']['get'] = 'Assessment_applicants/owner_get_assessment_assessment_applicant_list/$1';
$route['assessments/(:any)']['get'] = 'Assessments/get_assessment_detail/$1';
$route['assessments/(:any)/request_jadwal/(:any)']['put'] = 'Assessments/admin_change_state/$1/$2';
$route['assessments/(:any)/change_state/(:any)']['put'] = 'Assessments/admin_change_state/$1/$2';
$route['assessments/(:any)']['put'] = 'Assessments/update_assessment_by_id/$1';
$route['assessments/(:any)/(:any)']['delete'] = 'Assessments/delete_hard_assessment_by_id/$1/$2';
$route['assessments/(:any)']['delete'] = 'Assessments/delete_soft_assessment_by_id/$1';
$route['drafts/assessments']['get'] = 'Assessments/get_draft_assessment_list';
$route['assessments']['get'] = 'Assessments/get_assessment_list';
$route['assessments/count']['get'] = 'Assessments/get_assessment_count';
$route['assessments']['post'] = 'Assessments/create_assessment_session';
$route['assessments/send']['post'] = 'Assessments/send_draft_assessment_by_id';
$route['deleted/assessments']['get'] = 'Assessments/get_assessment_deleted_list';
$route['restore/assessments/(:any)']['put'] = 'Assessments/update_deleted_by_id/$1';

################################################ Begin assessment_flow ################################################################### 
// direct read to children
$route['assessments/flows/(:any)']['get'] = 'Assessment_flows/get_assessment_flow_detail/$1';
$route['assessments/flows']['get'] = 'Assessment_flows/get_assessment_flow_list';
$route['assessments/flows/count']['get'] = 'Assessment_flows/get_assessment_flow_count';

$route['assessments/(:any)/flows/(:any)']['get'] = 'Assessment_flows/get_assessment_assessment_flow_detail/$1/$2';
$route['assessments/(:any)/flows/(:any)']['put'] = 'Assessment_flows/update_assessment_assessment_flow_by_id/$1/$2';
$route['assessments/(:any)/flows/(:any)/(:any)']['delete'] = 'Assessment_flows/delete_hard_assessment_assessment_flow_by_id/$1/$2/$3';
$route['assessments/(:any)/flows/(:any)']['delete'] = 'Assessment_flows/delete_soft_assessment_assessment_flow_by_id/$1/$2';
$route['assessments/(:any)/flows']['get'] = 'Assessment_flows/get_assessment_assessment_flow_list/$1';
$route['assessments/(:any)/flows/count']['get'] = 'Assessment_flows/get_assessment_assessment_flow_count/$1';
$route['assessments/(:any)/flows']['post'] = 'Assessment_flows/create_assessment_assessment_flow_session/$1';

################################################ End assessment_flow #####################################################################

################################################ Begin assessment_assessor ################################################################### 
// direct read to children
$route['assessments/assessors/(:any)']['get'] = 'Assessment_assessors/get_assessment_assessor_detail/$1';
$route['assessments/assessors']['get'] = 'Assessment_assessors/get_assessment_assessor_list';
$route['assessments/assessors/count']['get'] = 'Assessment_assessors/get_assessment_assessor_count';

$route['assessments/(:any)/find_assessor_not_assign']['get'] = 'Assessment_assessors/get_find_not_assign_assessor_list/$1';
$route['assessments/(:any)/assessors/(:any)']['get'] = 'Assessment_assessors/get_assessment_assessment_assessor_detail/$1/$2';
$route['assessments/(:any)/assessors/(:any)']['put'] = 'Assessment_assessors/update_assessment_assessment_assessor_by_id/$1/$2';
$route['assessments/(:any)/assessors/(:any)/(:any)']['delete'] = 'Assessment_assessors/delete_hard_assessment_assessment_assessor_by_id/$1/$2/$3';
$route['assessments/(:any)/assessors/(:any)']['delete'] = 'Assessment_assessors/delete_soft_assessment_assessment_assessor_by_id/$1/$2';
$route['assessments/(:any)/assessors']['get'] = 'Assessment_assessors/get_assessment_assessment_assessor_list/$1';
$route['assessments/(:any)/assessors/count']['get'] = 'Assessment_assessors/get_assessment_assessment_assessor_count/$1';
$route['assessments/(:any)/assessors']['post'] = 'Assessment_assessors/create_assessment_assessment_assessor_session/$1';
$route['assessments/(:any)/imports/applicants']['post'] = 'Imports/import_data_user_applicant/$1';
################################################ End assessment_assessor #####################################################################

################################################ Begin assessment_admin ################################################################### 
// direct read to children
$route['admins/(:any)']['get'] = 'Assessment_admins/get_assessment_admin_detail/$1';
$route['admins']['get'] = 'Assessment_admins/get_assessment_admin_list';
$route['admins/count']['get'] = 'Assessment_admins/get_assessment_admin_count';

$route['assessments/(:any)/find_admin_not_assign']['get'] = 'Assessment_admins/get_find_not_assign_admin_list/$1';
$route['assessments/(:any)/admins/(:any)']['get'] = 'Assessment_admins/get_assessment_assessment_admin_detail/$1/$2';
$route['assessments/(:any)/admins/(:any)']['put'] = 'Assessment_admins/update_assessment_assessment_admin_by_id/$1/$2';
$route['assessments/(:any)/admins/(:any)/(:any)']['delete'] = 'Assessment_admins/delete_hard_assessment_assessment_admin_by_id/$1/$2/$3';
$route['assessments/(:any)/admins/(:any)']['delete'] = 'Assessment_admins/delete_soft_assessment_assessment_admin_by_id/$1/$2';
$route['assessments/(:any)/admins']['get'] = 'Assessment_admins/get_assessment_assessment_admin_list/$1';
$route['assessments/(:any)/admins/count']['get'] = 'Assessment_admins/get_assessment_assessment_admin_count/$1';
$route['assessments/(:any)/admins']['post'] = 'Assessment_admins/create_assessment_assessment_admin_session/$1';

################################################ End assessment_admin #####################################################################

################################################ Begin assessment_applicant ################################################################### 
// direct read to children
$route['me/assessments/(:any)']['post'] = 'Assessment_applicants/owner_create_assessment_assessment_applicant_session/$1';

$route['assessments/applicants/(:any)']['get'] = 'Assessment_applicants/get_assessment_applicant_detail/$1';
$route['assessments/applicants']['get'] = 'Assessment_applicants/get_assessment_applicant_list';
$route['assessments/applicants/count']['get'] = 'Assessment_applicants/get_assessment_applicant_count';

$route['assessments/(:any)/find_applicant_not_assign/(:any)']['get'] = 'Assessment_applicants/get_find_not_assign_applicant_list/$1/$2';
$route['assessments/(:any)/applicants/(:any)']['get'] = 'Assessment_applicants/get_assessment_assessment_applicant_detail/$1/$2';
$route['assessments/(:any)/applicants/(:any)']['put'] = 'Assessment_applicants/update_assessment_assessment_applicant_by_id/$1/$2';
$route['assessments/(:any)/applicants/(:any)/(:any)']['delete'] = 'Assessment_applicants/delete_hard_assessment_assessment_applicant_by_id/$1/$2/$3';
$route['assessments/(:any)/applicants/(:any)']['delete'] = 'Assessment_applicants/delete_soft_assessment_assessment_applicant_by_id/$1/$2';
$route['assessments/(:any)/applicants']['get'] = 'Assessment_applicants/get_assessment_assessment_applicant_list/$1';
$route['assessments/(:any)/applicants/count']['get'] = 'Assessment_applicants/get_assessment_assessment_applicant_count/$1';
$route['assessments/(:any)/applicants']['post'] = 'Assessment_applicants/create_assessment_assessment_applicant_session/$1';

################################################ End assessment_applicant #####################################################################

################################################ Begin assessment_file ################################################################### 
// direct read to children
$route['assessments/files/(:any)']['get'] = 'Assessment_files/get_assessment_file_detail/$1';
$route['assessments/files']['get'] = 'Assessment_files/get_assessment_file_list';
$route['assessments/files/count']['get'] = 'Assessment_files/get_assessment_file_count';

$route['assessments/(:any)/files/(:any)']['get'] = 'Assessment_files/get_assessment_assessment_file_detail/$1/$2';
$route['assessments/(:any)/files/(:any)']['put'] = 'Assessment_files/update_assessment_assessment_file_by_id/$1/$2';
$route['assessments/(:any)/files/(:any)/(:any)']['delete'] = 'Assessment_files/delete_hard_assessment_assessment_file_by_id/$1/$2/$3';
$route['assessments/(:any)/files/(:any)']['delete'] = 'Assessment_files/delete_soft_assessment_assessment_file_by_id/$1/$2';
$route['assessments/(:any)/files']['get'] = 'Assessment_files/get_assessment_assessment_file_list/$1';
$route['assessments/(:any)/files/count']['get'] = 'Assessment_files/get_assessment_assessment_file_count/$1';
$route['assessments/(:any)/files']['post'] = 'Assessment_files/create_assessment_assessment_file_session/$1';

################################################ End assessment_file #####################################################################

################################################ Begin assessment_letter ################################################################### 
// direct read to children
$route['public/assessments/(:any)/letters/(:any)/reads']['get'] = 'Assessment_letters/pdf_assessment_assessment_letter_detail/$1/$2';
$route['public/assessments/(:any)/letters/(:any)/html']['get'] = 'Assessment_letters/html_assessment_assessment_letter_detail/$1/$2';
$route['public/assessments/(:any)/letters/request']['get'] = 'Assessment_letters/pdf_assessment_assessment_request_letter/$1';
$route['public/assessments/(:any)/letters/html']['get'] = 'Assessment_letters/html_assessment_assessment_request_letter/$1';
$route['assessments/(:any)/letters/(:any)']['get'] = 'Assessment_letters/get_assessment_assessment_letter_detail/$1/$2';
$route['assessments/(:any)/letters/(:any)/signature']['put'] = 'Assessment_letters/signature_assessment_assessment_letter_by_id/$1/$2';
$route['assessments/(:any)/letters/(:any)']['put'] = 'Assessment_letters/update_assessment_assessment_letter_by_id/$1/$2';
$route['assessments/(:any)/letters/(:any)/(:any)']['delete'] = 'Assessment_letters/delete_hard_assessment_assessment_letter_by_id/$1/$2/$3';
$route['assessments/(:any)/letters/(:any)']['delete'] = 'Assessment_letters/delete_soft_assessment_assessment_letter_by_id/$1/$2';
$route['assessments/(:any)/letters']['get'] = 'Assessment_letters/get_assessment_assessment_letter_list/$1';
$route['assessments/(:any)/letters/count']['get'] = 'Assessment_letters/get_assessment_assessment_letter_count/$1';
$route['assessments/(:any)/letters']['post'] = 'Assessment_letters/create_assessment_assessment_letter_session/$1';

################################################ End assessment_letter #####################################################################

################################################ Begin assessment_log ################################################################### 
// direct read to children
$route['assessments/logs/(:any)']['get'] = 'Assessment_logs/get_assessment_log_detail/$1';
$route['assessments/logs']['get'] = 'Assessment_logs/get_assessment_log_list';
$route['assessments/logs/count']['get'] = 'Assessment_logs/get_assessment_log_count';

$route['assessments/(:any)/logs/(:any)']['get'] = 'Assessment_logs/get_assessment_assessment_log_detail/$1/$2';
$route['assessments/(:any)/logs/(:any)']['put'] = 'Assessment_logs/update_assessment_assessment_log_by_id/$1/$2';
$route['assessments/(:any)/logs/(:any)/(:any)']['delete'] = 'Assessment_logs/delete_hard_assessment_assessment_log_by_id/$1/$2/$3';
$route['assessments/(:any)/logs/(:any)']['delete'] = 'Assessment_logs/delete_soft_assessment_assessment_log_by_id/$1/$2';
$route['assessments/(:any)/logs']['get'] = 'Assessment_logs/get_assessment_assessment_log_list/$1';
$route['assessments/(:any)/logs/count']['get'] = 'Assessment_logs/get_assessment_assessment_log_count/$1';
$route['assessments/(:any)/logs']['post'] = 'Assessment_logs/create_assessment_assessment_log_session/$1';

################################################ End assessment_log #####################################################################

################################################ Begin master_portfolio ################################################################### 
## master_portfolio 
$route['public/portfolios/test_drive']['get'] = 'Master_portfolios/test_drive';
$route['portfolios/test_drive']['get'] = 'Master_portfolios/test_drive';
$route['portfolios/(:any)']['get'] = 'Master_portfolios/get_master_portfolio_detail/$1';
$route['portfolios/(:any)']['put'] = 'Master_portfolios/update_master_portfolio_by_id/$1';
$route['portfolios/(:any)/(:any)']['delete'] = 'Master_portfolios/delete_hard_master_portfolio_by_id/$1/$2';
$route['portfolios/(:any)']['delete'] = 'Master_portfolios/delete_soft_master_portfolio_by_id/$1';
$route['portfolios']['get'] = 'Master_portfolios/get_master_portfolio_list';
$route['portfolios/count']['get'] = 'Master_portfolios/get_master_portfolio_count';
$route['portfolios']['post'] = 'Master_portfolios/create_master_portfolio_session';
################################################ End master_portfolio ################################################################### 

################################################ Begin applicant_portfolio ################################################################### 
// direct read to children
$route['public/assessments/(:any)/applicants/(:any)/portfolios/(:any)/reads']['get'] = 'Applicant_portfolios/read_applicant_applicant_portfolio_detail/$1/$2/$3';
$route['me/assessments/(:any)/portfolios']['get'] = 'Applicant_portfolios/get_assessment_applicant_portfolio_list/$1';
$route['me/assessments/(:any)/portfolios/(:any)']['get'] = 'Applicant_portfolios/get_assessment_applicant_portfolio_detail/$1/$2';
$route['me/assessments/(:any)/portfolios']['post'] = 'Applicant_portfolios/create_applicant_portfolio_session/$1';
$route['me/assessments/(:any)/portfolios/(:any)']['put'] = 'Applicant_portfolios/update_applicant_portfolio_by_id/$1/$2';
$route['me/assessments/(:any)/portfolios/(:any)']['delete'] = 'Applicant_portfolios/delete_hard_applicant_portfolio_by_id/$1/$2';

$route['assessments/(:any)/applicants/(:any)/portfolios/(:any)']['get'] = 'Applicant_portfolios/get_applicant_applicant_portfolio_detail/$1/$2/$3';
$route['assessments/(:any)/applicants/(:any)/portfolios/(:any)']['put'] = 'Applicant_portfolios/update_applicant_applicant_portfolio_by_id/$1/$2/$3';
$route['assessments/(:any)/applicants/(:any)/portfolios/(:any)']['delete'] = 'Applicant_portfolios/delete_hard_applicant_applicant_portfolio_by_id/$1/$2/$3';
$route['assessments/(:any)/applicants/(:any)/portfolios']['get'] = 'Applicant_portfolios/get_applicant_applicant_portfolio_list/$1/$2';
$route['assessments/(:any)/applicants/(:any)/portfolios/count']['get'] = 'Applicant_portfolios/get_applicant_applicant_portfolio_count/$1/$2';
$route['assessments/(:any)/applicants/(:any)/portfolios']['post'] = 'Applicant_portfolios/create_applicant_applicant_portfolio_session/$1/$2';
$route['assessments/(:any)/applicants/(:any)/portfolios/custom']['post'] = 'Applicant_portfolios/create_applicant_applicant_custom_portfolio_session/$1/$2';

################################################ End applicant_portfolio #####################################################################

################################################ Begin assessment_pleno ################################################################### 
// direct read to children
$route['plenos/(:any)']['get'] = 'Assessment_plenos/get_assessment_pleno_detail/$1';
$route['plenos']['get'] = 'Assessment_plenos/get_assessment_pleno_list';
$route['plenos/count']['get'] = 'Assessment_plenos/get_assessment_pleno_count';

$route['assessments/(:any)/plenos/(:any)']['get'] = 'Assessment_plenos/get_assessment_assessment_pleno_detail/$1/$2';
$route['assessments/(:any)/plenos/(:any)']['put'] = 'Assessment_plenos/update_assessment_assessment_pleno_by_id/$1/$2';
$route['assessments/(:any)/plenos/(:any)/(:any)']['delete'] = 'Assessment_plenos/delete_hard_assessment_assessment_pleno_by_id/$1/$2/$3';
$route['assessments/(:any)/plenos/(:any)']['delete'] = 'Assessment_plenos/delete_soft_assessment_assessment_pleno_by_id/$1/$2';
$route['assessments/(:any)/plenos']['get'] = 'Assessment_plenos/get_assessment_assessment_pleno_list/$1';
$route['assessments/(:any)/plenos/count']['get'] = 'Assessment_plenos/get_assessment_assessment_pleno_count/$1';
$route['assessments/(:any)/plenos']['post'] = 'Assessment_plenos/create_assessment_assessment_pleno_session/$1';

################################################ End assessment_pleno #####################################################################

################################################ Begin assessment_certificate ################################################################### 
// direct read to children
$route['certificates/(:any)']['get'] = 'Assessment_certificates/get_assessment_certificate_detail/$1';
$route['certificates']['get'] = 'Assessment_certificates/get_assessment_certificate_list';
$route['certificates/count']['get'] = 'Assessment_certificates/get_assessment_certificate_count';

$route['assessments/(:any)/certificates/(:any)']['get'] = 'Assessment_certificates/get_assessment_assessment_certificate_detail/$1/$2';
$route['assessments/(:any)/certificates/(:any)']['put'] = 'Assessment_certificates/update_assessment_assessment_certificate_by_id/$1/$2';
$route['assessments/(:any)/certificates/(:any)/(:any)']['delete'] = 'Assessment_certificates/delete_hard_assessment_assessment_certificate_by_id/$1/$2/$3';
$route['assessments/(:any)/certificates/(:any)']['delete'] = 'Assessment_certificates/delete_soft_assessment_assessment_certificate_by_id/$1/$2';
$route['assessments/(:any)/certificates']['get'] = 'Assessment_certificates/get_assessment_assessment_certificate_list/$1';
$route['assessments/(:any)/certificates/count']['get'] = 'Assessment_certificates/get_assessment_assessment_certificate_count/$1';
$route['assessments/(:any)/certificates']['post'] = 'Assessment_certificates/create_assessment_assessment_certificate_session/$1';

################################################ End assessment_certificate #####################################################################

## accessor 
$route['public/users/(:any)/accessors/(:any)']['get'] = 'Accessors/read_accessor_data/$1/$2';
$route['users/(:any)/accessors']['get'] = 'Accessors/get_accessor_detail/$1';
$route['users/(:any)/accessors']['put'] = 'Accessors/update_accessor_by_id/$1';
$route['users/(:any)/accessors']['delete'] = 'Accessors/delete_accessor_by_id/$1';
$route['users/accessors']['get'] = 'Accessors/get_accessor_list';
$route['users/accessors/count']['get'] = 'Accessors/get_accessor_count';
$route['users/accessors']['post'] = 'Accessors/create_accessor_session';

## management
$route['users/(:any)/managements']['get'] = 'Managements/get_management_detail/$1';
$route['users/(:any)/managements']['put'] = 'Managements/update_management_by_id/$1';
$route['users/(:any)/managements']['delete'] = 'Managements/delete_management_by_id/$1';
$route['users/managements']['get'] = 'Managements/get_management_list';
$route['users/managements/count']['get'] = 'Managements/get_management_count';
$route['users/managements']['post'] = 'Managements/create_management_session';

## applicant 
$route['users/(:any)/applicants']['get'] = 'Applicants/get_applicant_detail/$1';
$route['users/(:any)/applicants']['put'] = 'Applicants/update_applicant_by_id/$1';
$route['users/(:any)/applicants']['delete'] = 'Applicants/delete_soft_applicant_by_id/$1';
$route['users/applicants']['get'] = 'Applicants/get_applicant_list';
$route['users/applicants/count']['get'] = 'Applicants/get_applicant_count';
$route['users/applicants']['post'] = 'Applicants/create_applicant_session';

## admin_tuk 
$route['users/(:any)/admintuk']['get'] = 'Admintuk/get_admintuk_detail/$1';
$route['users/(:any)/admintuk']['put'] = 'Admintuk/update_admintuk_by_id/$1';
$route['users/(:any)/admintuk']['delete'] = 'Admintuk/delete_admintuk_by_id/$1';
$route['users/admintuk']['get'] = 'Admintuk/get_admintuk_list';
$route['users/admintuk/count']['get'] = 'Admintuk/get_admintuk_count';
$route['users/admintuk']['post'] = 'Admintuk/create_admintuk_session';

## accessor_schedule 
$route['accessor/schedules/(:num)']['get'] = 'Accessor_schedules/get_accessor_schedule_detail/$1';
$route['accessor/schedules/(:num)']['put'] = 'Accessor_schedules/update_accessor_schedule_by_id/$1';
$route['accessor/schedules/(.+)']['delete'] = 'Accessor_schedules/delete_accessor_schedule_by_id/$1';
$route['accessor/schedules']['get'] = 'Accessor_schedules/get_accessor_schedule_list';
$route['accessor/schedules/count']['get'] = 'Accessor_schedules/get_accessor_schedule_count';
$route['accessor/schedules']['post'] = 'Accessor_schedules/create_accessor_schedule_session';

## accessor_competence 
$route['me/accessor/competences/(:any)']['get'] = 'Accessor_competences/get_own_accessor_competence_detail/$1';
$route['me/accessor/competences']['get'] = 'Accessor_competences/get_own_accessor_competence_list';
$route['me/accessor/competences/count']['get'] = 'Accessor_competences/get_own_accessor_competence_count';
$route['me/accessor/competences/(:any)']['put'] = 'Accessor_competences/update_own_accessor_competence_by_id/$1';
$route['me/accessor/competences/(.+)']['delete'] = 'Accessor_competences/delete_own_accessor_competence_by_id/$1';
$route['me/accessor/competences']['post'] = 'Accessor_competences/create_accessor_competence_session';

$route['accessor/competences/(:any)']['get'] = 'Accessor_competences/get_accessor_competence_detail/$1';
$route['accessor/competences/(:any)']['put'] = 'Accessor_competences/update_accessor_competence_by_id/$1';
$route['accessor/competences/(.+)']['delete'] = 'Accessor_competences/delete_accessor_competence_by_id/$1';
$route['accessor/competences']['get'] = 'Accessor_competences/get_accessor_competence_list';
$route['accessor/competences/count']['get'] = 'Accessor_competences/get_accessor_competence_count';
$route['accessor/competences']['post'] = 'Accessor_competences/create_accessor_competence_session';

## audit_trail 
$route['me/audit_trails/(:num)']['get'] = 'Audit_trails/get_own_audit_trail_detail/$1';
$route['me/audit_trails']['get'] = 'Audit_trails/get_own_audit_trail_list';
$route['me/audit_trails/count']['get'] = 'Audit_trails/get_own_audit_trail_count';
$route['me/audit_trails/(:num)']['put'] = 'Audit_trails/update_own_audit_trail_by_id/$1';
$route['me/audit_trails/(.+)']['delete'] = 'Audit_trails/delete_own_audit_trail_by_id/$1';

$route['audit_trails/(:num)']['get'] = 'Audit_trails/get_audit_trail_detail/$1';
$route['audit_trails/(:num)']['put'] = 'Audit_trails/update_audit_trail_by_id/$1';
$route['audit_trails/(.+)']['delete'] = 'Audit_trails/delete_audit_trail_by_id/$1';
$route['audit_trails']['get'] = 'Audit_trails/get_audit_trail_list';
$route['audit_trails/count']['get'] = 'Audit_trails/get_audit_trail_count';
$route['audit_trails']['post'] = 'Audit_trails/create_audit_trail_session';

## competence_field 
$route['me/competence_fields/(:num)']['get'] = 'Competence_fields/get_own_competence_field_detail/$1';
$route['me/competence_fields']['get'] = 'Competence_fields/get_own_competence_field_list';
$route['me/competence_fields/count']['get'] = 'Competence_fields/get_own_competence_field_count';
$route['me/competence_fields/(:num)']['put'] = 'Competence_fields/update_own_competence_field_by_id/$1';
$route['me/competence_fields/(.+)']['delete'] = 'Competence_fields/delete_own_competence_field_by_id/$1';

$route['competence_fields/(:num)']['get'] = 'Competence_fields/get_competence_field_detail/$1';
$route['competence_fields/(:num)']['put'] = 'Competence_fields/update_competence_field_by_id/$1';
$route['competence_fields/(.+)']['delete'] = 'Competence_fields/delete_competence_field_by_id/$1';
$route['competence_fields']['get'] = 'Competence_fields/get_competence_field_list';
$route['competence_fields/count']['get'] = 'Competence_fields/get_competence_field_count';
$route['competence_fields']['post'] = 'Competence_fields/create_competence_field_session';

## course 
$route['me/courses/(:num)']['get'] = 'Courses/get_own_course_detail/$1';
$route['me/courses']['get'] = 'Courses/get_own_course_list';
$route['me/courses/count']['get'] = 'Courses/get_own_course_count';
$route['me/courses/(:num)']['put'] = 'Courses/update_own_course_by_id/$1';
$route['me/courses/(.+)']['delete'] = 'Courses/delete_own_course_by_id/$1';

$route['courses/(:num)']['get'] = 'Courses/get_course_detail/$1';
$route['courses/(:num)']['put'] = 'Courses/update_course_by_id/$1';
$route['courses/(.+)']['delete'] = 'Courses/delete_course_by_id/$1';
$route['courses']['get'] = 'Courses/get_course_list';
$route['courses/count']['get'] = 'Courses/get_course_count';
$route['courses']['post'] = 'Courses/create_course_session';

## schema 
$route['schemas/(:any)']['get'] = 'Schemas/get_schema_detail/$1';
$route['schemas/(:any)']['put'] = 'Schemas/update_schema_by_id/$1';
$route['schemas/(:any)/(:any)']['delete'] = 'Schemas/delete_hard_schema_by_id/$1/$2';
$route['schemas/(:any)']['delete'] = 'Schemas/delete_soft_schema_by_id/$1';
$route['schemas']['get'] = 'Schemas/get_schema_list';
$route['schemas/count']['get'] = 'Schemas/get_schema_count';
$route['schemas']['post'] = 'Schemas/create_schema_session';

################################################ Begin sub_schema ################################################################### 
// direct read to children
$route['sub_schemas/(:any)']['get'] = 'Sub_schemas/get_sub_schema_detail/$1';
$route['sub_schemas']['get'] = 'Sub_schemas/get_sub_schema_list';
$route['sub_schemas/count']['get'] = 'Sub_schemas/get_sub_schema_count';

$route['public/schemas/views/tree']['get'] = 'Sub_schemas/get_schema_full_schema_list_tree/$1';
$route['public/schemas/views/(:any)']['get'] = 'Sub_schemas/get_schema_full_schema_detail/$1/$2';
$route['public/schemas/views']['get'] = 'Sub_schemas/get_schema_full_schema_list/$1';
$route['public/schemas/views/count']['get'] = 'Sub_schemas/get_schema_full_schema_count/$1';

$route['schemas/(:any)/sub_schemas/(:any)']['get'] = 'Sub_schemas/get_schema_sub_schema_detail/$1/$2';
$route['schemas/(:any)/sub_schemas/(:any)']['put'] = 'Sub_schemas/update_schema_sub_schema_by_id/$1/$2';
$route['schemas/(:any)/sub_schemas/(:any)/(:any)']['delete'] = 'Sub_schemas/delete_hard_schema_sub_schema_by_id/$1/$2/$3';
$route['schemas/(:any)/sub_schemas/(:any)']['delete'] = 'Sub_schemas/delete_soft_schema_sub_schema_by_id/$1/$2';
$route['schemas/(:any)/sub_schemas']['get'] = 'Sub_schemas/get_schema_sub_schema_list/$1';
$route['schemas/(:any)/sub_schemas/count']['get'] = 'Sub_schemas/get_schema_sub_schema_count/$1';
$route['schemas/(:any)/sub_schemas']['post'] = 'Sub_schemas/create_schema_sub_schema_session/$1';

################################################ End sub_schema #####################################################################

## lsp 
$route['me/lsp/(:num)']['get'] = 'Lsps/get_own_lsp_detail/$1';
$route['me/lsp']['get'] = 'Lsps/get_own_lsp_list';
$route['me/lsp/count']['get'] = 'Lsps/get_own_lsp_count';
$route['me/lsp/(:num)']['put'] = 'Lsps/update_own_lsp_by_id/$1';
$route['me/lsp/(.+)']['delete'] = 'Lsps/delete_own_lsp_by_id/$1';

$route['lsp/(:num)']['get'] = 'Lsps/get_lsp_detail/$1';
$route['lsp/(:num)']['put'] = 'Lsps/update_lsp_by_id/$1';
$route['lsp/(.+)']['delete'] = 'Lsps/delete_lsp_by_id/$1';
$route['lsp']['get'] = 'Lsps/get_lsp_list';
$route['lsp/count']['get'] = 'Lsps/get_lsp_count';
$route['lsp']['post'] = 'Lsps/create_lsp_session';

## schedule_accessor 
$route['me/schedules/accessors/(:num)']['get'] = 'Schedule_accessors/get_own_schedule_accessor_detail/$1';
$route['me/schedules/accessors']['get'] = 'Schedule_accessors/get_own_schedule_accessor_list';
$route['me/schedules/accessors/count']['get'] = 'Schedule_accessors/get_own_schedule_accessor_count';
$route['me/schedules/accessors/(:num)']['put'] = 'Schedule_accessors/update_own_schedule_accessor_by_id/$1';
$route['me/schedules/accessors/(.+)']['delete'] = 'Schedule_accessors/delete_own_schedule_accessor_by_id/$1';

$route['schedules/accessors/(:num)']['get'] = 'Schedule_accessors/get_schedule_accessor_detail/$1';
$route['schedules/accessors/(:num)']['put'] = 'Schedule_accessors/update_schedule_accessor_by_id/$1';
$route['schedules/accessors/(.+)']['delete'] = 'Schedule_accessors/delete_schedule_accessor_by_id/$1';
$route['schedules/accessors']['get'] = 'Schedule_accessors/get_schedule_accessor_list';
$route['schedules/accessors/count']['get'] = 'Schedule_accessors/get_schedule_accessor_count';
$route['schedules/accessors']['post'] = 'Schedule_accessors/create_schedule_accessor_session';

## tuk 
$route['public/tuks/(:any)']['get'] = 'Tuks/get_tuk_detail/$1';
$route['tuks/(:any)']['put'] = 'Tuks/update_tuk_by_id/$1';
$route['tuks/(:any)/(:any)']['delete'] = 'Tuks/delete_hard_tuk_by_id/$1/$2';
$route['tuks/(:any)']['delete'] = 'Tuks/delete_soft_tuk_by_id/$1';
$route['public/tuks']['get'] = 'Tuks/get_tuk_list';
$route['public/tuks/count']['get'] = 'Tuks/get_tuk_count';
$route['tuks']['post'] = 'Tuks/create_tuk_session';
$route['restore/tuks/(:any)']['put'] = 'Tuks/update_deleted_by_id/$1';
$route['deleted/tuks']['get'] = 'Tuks/get_tuk_deleted_list';


## document 
$route['public/documents/integrity_pact']['get'] = 'Documents/view_integrity_pact';
$route['public/documents/assessments/(:num)/users/(:num)/assignment']['get'] = 'Documents/view_assessment_assignment/$1/$2';
$route['me/documents/integrity_pact']['get'] = 'Documents/get_own_integrity_pact';
$route['me/documents/integrity_pact/generate_pdf']['put'] = 'Documents/sign_own_integrity_pact_signature';
$route['users/(:num)/documents/integrity_pact']['get'] = 'Documents/get_integrity_pact_by_user_id/$1';

## google
$route['google/calendar/holiday']['get'] = 'Google/get_calendar_indonesia_holiday';

## notification 
$route['me/notifications/clusters']['get'] = 'Notifications/get_own_cluster_notification_list';
$route['me/notifications/(:num)']['get'] = 'Notifications/get_own_notification_detail/$1';
$route['me/notifications']['get'] = 'Notifications/get_own_notification_list';
$route['me/notifications/count']['get'] = 'Notifications/get_own_notification_count';
$route['me/notifications/(:num)']['put'] = 'Notifications/update_own_notification_by_id/$1';
$route['me/notifications/(.+)']['delete'] = 'Notifications/delete_own_notification_by_id/$1';

## gen 
$route['gens/(:any)']['get'] = 'Gens/get_gen_detail/$1';
$route['gens/(:any)']['put'] = 'Gens/update_gen_by_id/$1';
$route['gens/(:any)/(:any)']['delete'] = 'Gens/delete_hard_gen_by_id/$1/$2';
$route['gens/(:any)']['delete'] = 'Gens/delete_soft_gen_by_id/$1';
$route['gens']['get'] = 'Gens/get_gen_list';
$route['gens/count']['get'] = 'Gens/get_gen_count';
$route['gens']['post'] = 'Gens/create_gen_session';

$route['webhooks/assessments/(:any)']['put'] = 'Webhook/update_assessment_by_id/$1';
$route['webhooks/assessments/(:any)']['delete'] = 'Webhook/delete_soft_assessment_by_id/$1';
$route['webhooks/assessments']['post'] = 'Webhook/create_assessment_session';
$route['webhooks/assessments/(:any)/applicants']['post'] = 'Webhook/create_assessment_assessment_applicant_session/$1';
$route['webhooks/assessments/(:any)/applicants/(:any)']['delete'] = 'Webhook/delete_soft_assessment_assessment_applicant_by_id/$1/$2';
$route['webhooks/assessments/(:any)/applicants/(:any)']['put'] = 'Webhook/update_assessment_assessment_applicant_by_id/$1/$2';

## client 
$route['clients/assessments/count']['get'] = 'Clients/get_assessment_count';
$route['clients/assessments/(:any)']['get'] = 'Clients/get_assessment_detail/$1';
$route['clients/assessments']['get'] = 'Clients/get_assessment_list';
$route['clients/assessments/(:any)/applicants/count']['get'] = 'Clients/get_assessment_assessment_applicant_count/$1';
$route['clients/assessments/(:any)/applicants/(:any)']['get'] = 'Clients/get_assessment_assessment_applicant_detail/$1/$2';
$route['clients/assessments/(:any)/applicants']['get'] = 'Clients/get_assessment_assessment_applicant_list/$1';

$route['clients/assessments/(:any)/applicants/(:any)/portfolios/(:any)']['get'] = 'Clients/get_applicant_applicant_portfolio_detail/$1/$2/$3';
$route['clients/assessments/(:any)/applicants/(:any)/portfolios/(:any)']['put'] = 'Clients/update_applicant_applicant_portfolio_by_id/$1/$2/$3';
$route['clients/assessments/(:any)/applicants/(:any)/portfolios/(:any)']['delete'] = 'Clients/delete_hard_applicant_applicant_portfolio_by_id/$1/$2/$3';
$route['clients/assessments/(:any)/applicants/(:any)/portfolios']['post'] = 'Clients/create_applicant_applicant_portfolio/$1/$2';
$route['clients/assessments/(:any)/applicants/(:any)/portfolios']['get'] = 'Clients/get_applicant_applicant_portfolio_list/$1/$2';
$route['clients/assessments/(:any)/applicants/(:any)/portfolios/count']['get'] = 'Clients/get_applicant_applicant_portfolio_count/$1/$2';
$route['public/clients/assessments/(:any)/applicants/(:any)/portfolios/(:any)/reads']['get'] = 'Applicant_portfolios/read_applicant_applicant_portfolio_detail/$1/$2/$3';

## alumni 
$route['alumnis/(:any)']['get'] = 'Alumnis/get_alumni_detail/$1';
$route['alumnis/(:any)']['put'] = 'Alumnis/update_alumni_by_id/$1';
$route['alumnis/(:any)/(:any)']['delete'] = 'Alumnis/delete_hard_alumni_by_id/$1/$2';
$route['alumnis/(:any)']['delete'] = 'Alumnis/delete_soft_alumni_by_id/$1';
$route['alumnis']['get'] = 'Alumnis/get_alumni_list';
$route['alumnis/count']['get'] = 'Alumnis/get_alumni_count';
$route['alumnis']['post'] = 'Alumnis/create_alumni_session';
$route['/imports/alumnis']['post'] = 'Imports/import_data_alumni';


## product 
$route['products/(:any)']['get'] = 'Products/get_product_detail/$1';
$route['products/(:any)']['put'] = 'Products/update_product_by_id/$1';
$route['products/(:any)/(:any)']['delete'] = 'Products/delete_hard_product_by_id/$1/$2';
$route['products/(:any)']['delete'] = 'Products/delete_soft_product_by_id/$1';
$route['products']['get'] = 'Products/get_product_list';
$route['products/count']['get'] = 'Products/get_product_count';
$route['products']['post'] = 'Products/create_product_session';

## application 
$route['applications/(:any)']['get'] = 'Applications/get_application_detail/$1';
$route['applications/(:any)']['put'] = 'Applications/update_application_by_id/$1';
$route['applications/(:any)/(:any)']['delete'] = 'Applications/delete_hard_application_by_id/$1/$2';
$route['applications/(:any)']['delete'] = 'Applications/delete_soft_application_by_id/$1';
$route['applications']['get'] = 'Applications/get_application_list';
$route['applications/count']['get'] = 'Applications/get_application_count';
$route['applications']['post'] = 'Applications/create_application_session';

// direct read to children
################################################ Begin application_master_setting ################################################################### 
// direct read to children
$route['master_settings/(:any)']['get'] = 'Application_master_settings/get_application_master_setting_detail/$1';
$route['master_settings']['get'] = 'Application_master_settings/get_application_master_setting_list';
$route['master_settings/count']['get'] = 'Application_master_settings/get_application_master_setting_count';

$route['applications/(:any)/master_settings/(:any)']['get'] = 'Application_master_settings/get_application_application_master_setting_detail/$1/$2';
$route['applications/(:any)/master_settings/(:any)']['put'] = 'Application_master_settings/update_application_application_master_setting_by_id/$1/$2';
$route['applications/(:any)/master_settings/(:any)/(:any)']['delete'] = 'Application_master_settings/delete_hard_application_application_master_setting_by_id/$1/$2/$3';
$route['applications/(:any)/master_settings/(:any)']['delete'] = 'Application_master_settings/delete_soft_application_application_master_setting_by_id/$1/$2';
$route['applications/(:any)/master_settings']['get'] = 'Application_master_settings/get_application_application_master_setting_list/$1';
$route['applications/(:any)/master_settings/count']['get'] = 'Application_master_settings/get_application_application_master_setting_count/$1';
$route['applications/(:any)/master_settings']['post'] = 'Application_master_settings/create_application_application_master_setting_session/$1';

################################################ End application_master_setting #####################################################################
################################################ Begin application_setting ################################################################### 
// direct read to children
$route['application_settings/(:any)']['get'] = 'Application_settings/get_application_setting_detail/$1';
$route['application_settings']['get'] = 'Application_settings/get_application_setting_list';//Permission Application_list
$route['application_settings/count']['get'] = 'Application_settings/get_application_setting_count';

$route['applications/(:any)/settings/(:any)']['get'] = 'Application_settings/get_application_application_setting_detail/$1/$2';
$route['applications/(:any)/settings/(:any)']['put'] = 'Application_settings/update_application_application_setting_by_id/$1/$2';
$route['applications/(:any)/settings/(:any)/(:any)']['delete'] = 'Application_settings/delete_hard_application_application_setting_by_id/$1/$2/$3';
$route['applications/(:any)/settings/(:any)']['delete'] = 'Application_settings/delete_soft_application_application_setting_by_id/$1/$2';
$route['applications/(:any)/settings']['get'] = 'Application_settings/get_application_application_setting_list/$1';
$route['applications/(:any)/settings/count']['get'] = 'Application_settings/get_application_application_setting_count/$1';
$route['applications/(:any)/settings']['post'] = 'Application_settings/create_application_application_setting_session/$1';

################################################ End application_setting #####################################################################

## article 
$route['public/articles/(:any)']['get'] = 'Articles/get_article_detail/$1';
$route['articles/(:any)']['put'] = 'Articles/update_article_by_id/$1';
$route['articles/(:any)/(:any)']['delete'] = 'Articles/delete_hard_article_by_id/$1/$2';
$route['articles/(:any)']['delete'] = 'Articles/delete_soft_article_by_id/$1';
$route['public/articles']['get'] = 'Articles/get_article_list';
$route['public/articles/count']['get'] = 'Articles/get_article_count';
$route['articles']['post'] = 'Articles/create_article_session';

$route['letters/count']['get'] = 'Letters/get_letter_count';
$route['letters/(:any)']['get'] = 'Letters/get_letter_detail/$1';
$route['letters/(:any)/download']['get'] = 'Letters/download_letter_by_id/$1';
$route['letters/(:any)']['put'] = 'Letters/update_letter_by_id/$1';
$route['letters']['get'] = 'Letters/get_letter_list';
$route['restore/letters/(:any)']['put'] = 'Letters/update_deleted_by_id/$1';
$route['deleted/letters']['get'] = 'Letters/get_letter_deleted_list';

## kuk 
$route['kuks/(:any)']['get'] = 'Kuks/get_kuk_detail/$1';
$route['kuks/(:any)']['put'] = 'Kuks/update_kuk_by_id/$1';
$route['kuks/(:any)/(:any)']['delete'] = 'Kuks/delete_hard_kuk_by_id/$1/$2';
$route['kuks/(:any)']['delete'] = 'Kuks/delete_soft_kuk_by_id/$1';
$route['kuks']['get'] = 'Kuks/get_kuk_list';
$route['kuks/count']['get'] = 'Kuks/get_kuk_count';
$route['kuks']['post'] = 'Kuks/create_kuk_session';

################################################ Begin kuk_section ################################################################### 
// direct read to children
$route['kuks/(:any)/kuk_sections/(:any)']['get'] = 'Kuk_sections/get_kuk_kuk_section_detail/$1/$2';
$route['kuks/(:any)/kuk_sections/(:any)']['put'] = 'Kuk_sections/update_kuk_kuk_section_by_id/$1/$2';
$route['kuks/(:any)/kuk_sections/(:any)/(:any)']['delete'] = 'Kuk_sections/delete_hard_kuk_kuk_section_by_id/$1/$2/$3';
$route['kuks/(:any)/kuk_sections/(:any)']['delete'] = 'Kuk_sections/delete_soft_kuk_kuk_section_by_id/$1/$2';
$route['kuks/(:any)/kuk_sections']['get'] = 'Kuk_sections/get_kuk_kuk_section_list/$1';
$route['kuks/(:any)/kuk_sections/count']['get'] = 'Kuk_sections/get_kuk_kuk_section_count/$1';
$route['kuks/(:any)/kuk_sections']['post'] = 'Kuk_sections/create_kuk_kuk_section_session/$1';

################################################ End kuk_section #####################################################################

################################################ Begin kuk_section_detail ################################################################### 
// direct read to children
$route['kuk_sections/(:any)/kuk_section_details/(:any)']['get'] = 'Kuk_section_details/get_kuk_section_kuk_section_detail_detail/$1/$2';
$route['kuk_sections/(:any)/kuk_section_details/(:any)']['put'] = 'Kuk_section_details/update_kuk_section_kuk_section_detail_by_id/$1/$2';
$route['kuk_sections/(:any)/kuk_section_details/(:any)/(:any)']['delete'] = 'Kuk_section_details/delete_hard_kuk_section_kuk_section_detail_by_id/$1/$2/$3';
$route['kuk_sections/(:any)/kuk_section_details/(:any)']['delete'] = 'Kuk_section_details/delete_soft_kuk_section_kuk_section_detail_by_id/$1/$2';
$route['kuk_sections/(:any)/kuk_section_details']['get'] = 'Kuk_section_details/get_kuk_section_kuk_section_detail_list/$1';
$route['kuk_sections/(:any)/kuk_section_details/count']['get'] = 'Kuk_section_details/get_kuk_section_kuk_section_detail_count/$1';
$route['kuk_sections/(:any)/kuk_section_details']['post'] = 'Kuk_section_details/create_kuk_section_kuk_section_detail_session/$1';

################################################ End kuk_section_detail #####################################################################

## join_request 
$route['join_requests/(:any)']['get'] = 'Join_requests/get_join_request_detail/$1';
$route['join_requests/(:any)/downloads']['get'] = 'Join_requests/download_join_request/$1';
$route['join_requests/(:any)']['put'] = 'Join_requests/update_join_request_by_id/$1';
$route['join_requests/(:any)/(:any)']['delete'] = 'Join_requests/delete_hard_join_request_by_id/$1/$2';
$route['join_requests/(:any)']['delete'] = 'Join_requests/delete_soft_join_request_by_id/$1';
$route['join_requests']['get'] = 'Join_requests/get_join_request_list';
$route['join_requests/count']['get'] = 'Join_requests/get_join_request_count';
$route['join_requests']['post'] = 'Join_requests/create_join_request_session';
$route['join_requests/(:any)/apl01']['get'] = 'Join_requests/download_join_request/$1';

## unit_competence 
$route['unit_competences/(:any)']['get'] = 'Unit_competences/get_unit_competence_detail/$1';
$route['unit_competences/(:any)']['put'] = 'Unit_competences/update_unit_competence_by_id/$1';
$route['unit_competences/(:any)/(:any)']['delete'] = 'Unit_competences/delete_hard_unit_competence_by_id/$1/$2';
$route['unit_competences/(:any)']['delete'] = 'Unit_competences/delete_soft_unit_competence_by_id/$1';
$route['unit_competences']['get'] = 'Unit_competences/get_unit_competence_list';
$route['unit_competences/count']['get'] = 'Unit_competences/get_unit_competence_count';
$route['unit_competences']['post'] = 'Unit_competences/create_unit_competence_session';

## archive 
$route['archives/(:any)']['get'] = 'Archives/get_archive_detail/$1';
$route['archives/(:any)/downloads']['get'] = 'Archives/download_archive/$1';
$route['archives/(:any)/(:any)']['delete'] = 'Archives/delete_hard_archive_by_id/$1/$2';
$route['archives']['get'] = 'Archives/get_archive_list';
$route['archives/count']['get'] = 'Archives/get_archive_count';

## persyaratan_umum 
$route['persyaratan_umums/(:any)']['get'] = 'Persyaratan_umums/get_persyaratan_umum_detail/$1';
$route['persyaratan_umums/(:any)']['put'] = 'Persyaratan_umums/update_persyaratan_umum_by_id/$1';
$route['persyaratan_umums/(:any)/(:any)']['delete'] = 'Persyaratan_umums/delete_hard_persyaratan_umum_by_id/$1/$2';
$route['persyaratan_umums/(:any)']['delete'] = 'Persyaratan_umums/delete_soft_persyaratan_umum_by_id/$1';
$route['persyaratan_umums']['get'] = 'Persyaratan_umums/get_persyaratan_umum_list';
$route['persyaratan_umums']['post'] = 'Persyaratan_umums/create_persyaratan_umum_session';
$route['persyaratan_umums/(:any)/apl01/(:any)']['get'] = 'Persyaratan_umums/download_apl01/$1/$2';

## Jobs
$route['public/jobs']['get'] = 'Jobs/get_job_list';
$route['public/jobs/(:any)']['get'] = 'Jobs/get_job_detail';
$route['jobs']['post'] = 'Jobs/create_jobs_session';
