<?php
defined('BASEPATH') or exit('No direct script access allowed');
$route['mis'] = 'login/index';

/// Mis
$route['mis/view'] = 'mis/index';
$route['mis/load_dashboard'] = 'mis/load_dashboard';
$route['mis/access_denied'] = 'mis/access_denied';
$route['mis/login/forgot-password'] = 'login/forgotPassword';
$route['mis/reset-password/(:any)/(:any)'] = 'login/resetPasswordConfirmUser/$1/$2';
$route['mis/reset-password/process'] = 'login/createPasswordUser';
$route['mis/logout'] = 'mis/logout';

// users
$route['mis/users'] = 'users';
$route['mis/users/get_records'] = 'users/get_records';
$route['mis/users/create'] = 'users/create';
$route['mis/users/create_action'] = 'users/create_action';
$route['mis/users/read/(:any)'] = 'users/read/$1';
$route['mis/users/update/(:any)'] = 'users/update/$1';
$route['mis/users/update_action'] = 'users/update_action';
$route['mis/users/delete/(:any)'] = 'users/delete/$1';
// Profile
$route['mis/profile']['GET']  = 'profiles/index';
$route['mis/profile/update']['POST']  = 'profiles/update';
$route['mis/profile/password']['GET'] = 'profiles/password';
$route['mis/profile/password/update']['POST'] = 'profiles/password_update';
$route['mis/profile/photo'] = 'profiles/upload_photo';
$route['mis/profile/photo/remove'] = 'profiles/remove_photo';
// roles
$route['mis/roles'] = 'roles';
$route['mis/roles/get_records'] = 'roles/get_records';
$route['mis/roles/add'] = 'roles/add';
$route['mis/roles/get_role_info'] = 'roles/get_role_info';
$route['mis/roles/update'] = 'roles/update';

// applications
$route['mis/applications'] = 'applications';
$route['mis/applications/view'] = 'applications/view';
$route['mis/applications/get_records'] = 'applications/get_records';
$route['mis/applications/generatexls'] = 'applications/generatexls';
$route['mis/applications/top_services'] = 'applications/top_services';
$route['mis/applications/leading_departments'] = 'applications/leading_departments';
$route['mis/applications/count_applications'] = 'applications/count_applications';
$route['mis/applications/dept-wise-summary'] = 'department_wise_applications/index';
$route['mis/applications/dept-wise/list/(:any)'] = 'department_wise_applications/show/$1';
$route['mis/applications/dept-wise/get-records/(:any)'] = 'department_wise_applications/get_records/$1';
//Route for services

$route['mis/online'] = 'services/index';
$route['mis/online/get_records'] = 'services/get_records';
$route['mis/online/get_service_info'] = 'services/get_service_info';
$route['mis/online/add']['POST'] = 'services/insert';
$route['mis/online/delete'] = 'services/delete';
$route['mis/online/update']['POST'] = 'services/update';
$route['mis/online/get_department_services'] = 'services/department_service';
$route['mis/department_services/(:num)']  = 'services/get_record_dapt/$1'; //department wise services
$route['mis/online/get_office_services'] = 'services/office_service';


//Routes for Departments
$route['mis/departments'] = 'departments/index';
$route['mis/departments/get_records'] = 'departments/get_records';
$route['mis/departments/get_department_info'] = 'departments/get_department_info';
$route['mis/department/add']['POST'] = 'departments/insert';
$route['mis/department/delete'] = 'departments/delete';
$route['mis/department/update']['POST'] = 'departments/update';
$route['mis/department_office/(:num)']  = 'offices/get_record_office/$1'; //department wise office

//Routes for Offices
$route['mis/offices'] = 'offices/index';

// captcha
$route['mis/refresh-captcha']['GET'] = 'login/refresh_captcha';

//API Routes
$route['mis/api/get/status']['GET']  = 'api/site/index/$1'; //all service consolidated status
$route['mis/api/get/status/genderwise']['GET']  = 'api/site/gender_wise_application_count/$1'; //all service consolidated status
$route['mis/api/online/status']['GET']  = 'api/site/all_service_status/$1'; // service wise all service status
$route['mis/api/office/status']['GET']  = 'api/site/officewise/$1'; //Officewise status
$route['mis/api/office/status/ovca']['GET']  = 'api/site/officewise_appilcation_for_ovca/$1'; //Officewise status
$route['mis/api/office/status/ovca/v2']['GET']  = 'api/site/officewise_appilcation_for_ovca_v2/$1'; //Officewise status
$route['mis/api/office/status/ovca/total']['GET']  = 'api/site/officewise_appilcation_for_ovca_total/$1'; //Officewise status
$route['mis/api/service/getservicewisedata/(:num)']['GET']  = 'api/dashboard/getServiceWiseData/$1'; //Officewise status
$route['mis/api/office/getofficewisedata']['POST']  = 'api/dashboard/getOfficeWiseData'; //Officewise status
$route['mis/api/department/getdepartmentwisedata/(:num)']['GET']  = 'api/dashboard/getDepartmentWiseData/$1'; //Officewise status
$route['mis/api/department/getoffices/(:num)']['GET']  = 'api/dashboard/getOfficesByDepartmentID/$1'; //Officewise status
$route['mis/api/district/getofficesbydistrict/(:num)']['GET']  = 'api/district/getOffices/$1'; //Officewise status
$route['mis/api/district/getdistrictdata']['GET']  = 'api/district/getDistrictData'; //Officewise status

//Feedback
$route['mis/feedback'] = 'feedback/index';
$route['mis/feedback/save'] = 'feedback/save';

//Reports
$route['mis/reports/all_service_status'] = 'reports/servicewise_status';
$route['mis/rest/api'] = 'rest/insert';

// grievance report
$route['mis/grievance/report'] = 'grievance/index';
$route['mis/grievance/get-services/(:any)'] = 'grievance/get_services/$1';
$route['mis/grievance/get-counted-statistics'] = 'grievance/counted_statistics';

// Revenue filter
$route['mis/applications/view-revenue-filter'] = 'applications/view_revenue_filter';
$route['mis/applications/get-records-revenue-filter'] = 'applications/get_records_revenue_filter';
$route['mis/applications/get-revenue-collected'] = 'applications/get_revenue_collected';
//REST
$route['mis/find-application']['POST'] = 'mis/find_application';
$route['mis/appeal/first-appeal']['GET'] = 'appeal/first_appeal';
$route['mis/appeal/find-first-appeal']['POST'] = 'appeal/find_first_appeal';


$route['mis/appeal/first-appeal-by-disttrict']['GET'] = 'appeal/first_appeal_count_by_disttrict';
$route['mis/appeal/find-first-appeal-count-by-district']['POST'] = 'appeal/find_first_appeal_count_by_district';

$route['mis/appeal/second-appeal-by-disttrict']['GET'] = 'appeal/second_appeal_count_by_disttrict';
$route['mis/appeal/find-second-appeal-count-by-district']['POST'] = 'appeal/find_first_appeal_count_by_district';

$route['mis/appeal/second-appeal-by-service']['GET'] = 'appeal/second_appeal_count_by_service';
$route['mis/appeal/find-second-appeal-count-by-service']['POST'] = 'appeal/find_second_appeal_count_by_service';

$route['mis/citizen'] = 'citizen/index';
$route['mis/citizen/get-records']    = 'citizen/get_records';
$route['mis/citizen/access-log']     = 'citizen/view_access_log';
$route['mis/citizen/get-access-log'] = 'citizen/get_access_log';
$route['mis/citizen/download-excel'] = 'citizen/download_excel';

//Route for artps-services
$route['mis/artps-services'] = 'artps_services/index';
$route['mis/artps-services/get_records'] = 'artps_services/get_records';
$route['mis/artps-services/get_service_info'] = 'artps_services/get_service_info';
$route['mis/artps-services/add']['POST'] = 'artps_services/insert';
$route['mis/artps-services/delete'] = 'artps_services/delete';
$route['mis/artps-services/update']['POST'] = 'services/update';



// Routes for Portal
$route['mis/api/portal/popular-services']['GET']  = 'api/site/popular_services';

// Routes for Data sharing APIs for Skill Dept
$route['mis/api/jwt/get']['GET'] = 'api/department_data_api/generate_jwt';

$route['mis/api/dept/skill/total']['GET']  = 'api/skill_dept/index'; //all services consolidated status
$route['mis/api/dept/skill/gender']['GET']  = 'api/skill_dept/gender_wise_application_count'; //gender data for all services 
$route['mis/api/dept/skill/servicewise']['GET']  = 'api/skill_dept/all_service_status'; // servicewise status
$route['mis/api/dept/skill/officewise']['GET']  = 'api/skill_dept/officewise'; // Officewise status


$route['mis/api/dept/skill/exchangewise']['GET'] = 'api/skill_dept/exchangewise_report';
$route['mis/api/dept/skill/qualificationwise']['GET'] = 'api/skill_dept/qualificationwise_report';
$route['mis/api/dept/skill/employmentwise']['GET'] = 'api/skill_dept/employmentwise_report';

$route['mis/api/v2/dept/skill/exchangewise']['POST'] = 'api/skill_dept_v2/exchangewise_report';
$route['mis/api/v2/dept/skill/qualificationwise']['POST'] = 'api/skill_dept_v2/qualificationwise_report';
$route['mis/api/v2/dept/skill/employmentwise']['POST'] = 'api/skill_dept_v2/employmentwise_report';

$route['mis/api/dept/skill/applicant-details']['GET'] = 'api/skill_dept/get_applicant_details';    // Get applicant_details 
$route['mis/api/dept/skill/yearwise-data/(:num)']['POST'] = 'api/skill_dept/get_data_yearwise/$1';    // Get yearwise application status group by month


// Routes for Data sharing APIs for All Departments
$route['mis/api/departments/total']['GET']  = 'api/department_data_api/index/$1'; //all services consolidated status
$route['mis/api/departments/gender']['GET']  = 'api/department_data_api/gender_wise_application_count/$1'; //gender data for all services 
$route['mis/api/departments/servicewise']['GET']  = 'api/department_data_api/servicewise/$1'; // servicewise status
$route['mis/api/departments/officewise']['GET']  = 'api/department_data_api/officewise/$1'; // Officewise status
