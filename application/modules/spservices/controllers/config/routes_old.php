<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller'] = 'login';
$route['404_override'] = 'errors/notfound404';

$route['spservices']['GET'] = 'login';
$route['spservices/get/query/cin-response'] = 'query_payment_response/cin_response';
$route['spservices/get/cin-response'] = 'payment_response/cin_response';

// for minority certificate office users 

// //minority certificate user creation open link
// $route['spservices/mcc/user-registration']['GET'] = 'mcc_office_user/registration';
// // $route['spservices/mcc/user-registration/(:any)']['GET'] = 'mcc_office_user/registration/index/$1';
// $route['spservices/mcc/user-registration/submit']['POST'] = 'mcc_office_user/registration/submit';

//minority certificate user creation open link
$route['spservices/mcc/user-registration']['GET'] = 'mcc_users/registration';
// $route['spservices/mcc/user-registration/(:any)']['GET'] = 'mcc_users/registration/index/$1';
$route['spservices/mcc/user-registration/submit']['POST'] = 'mcc_users/registration/submit';

//landing page for mcc admin login
$route['spservices/mcc/admins']['GET'] = 'mcc_office_users/home';
$route['spservices/mcc/unauthorize-user']['GET'] = 'mcc_office_users/home/unauthorize';

//For minority community certificates state & district admin
$route['spservices/mcc/state-login']['GET'] = 'mcc_office_users/login';
$route['spservices/mcc/district-login']['GET'] = 'mccdistrictadmin/login';

// for minority certificate admin 
$route['spservices/mcc/user-login']['GET'] = 'office/login';
$route['spservices/send-otp']['POST'] = 'office/login/send_otp';
$route['spservices/process-login']['POST'] = 'office/login/process_login';
// password login 
$route['spservices/password-login']['POST'] = 'office/login/password_login';
$route['spservices/office/logout']['GET'] = 'office/login/logout';
// change-password
$route['spservices/office/change-password']['GET'] = 'office/change_password/change_password';
$route['spservices/office/save-change-password']['POST'] = 'office/change_password/save_change_password';

// forgot password 
$route['spservices/office/forgot-password']['GET'] = 'office/forgot_password';
$route['spservices/check-mobile']['POST'] = 'office/forgot_password/check_mobile_number';
$route['spservices/send-forgot-otp']['POST'] = 'office/forgot_password/send_otp';
$route['spservices/reset-password']['POST'] = 'office/forgot_password/reset_password';

$route['spservices/office/dashboard']['GET'] = 'office/dashboard';

$route['spservices/office/applications']['GET'] = 'office/applications';
$route['spservices/office/get-applications']['POST'] = 'office/applications/get_all_applications';

$route['spservices/office/pending-applications']['GET'] = 'office/applications/pending_application';
$route['spservices/office/get-pending-applications']['POST'] = 'office/applications/get_pending_applications';

$route['spservices/office/forwarded-applications']['GET'] = 'office/applications/forwarded_application';
$route['spservices/office/get-forwarded-applications']['POST'] = 'office/applications/get_forwarded_applications';

$route['spservices/office/delivered-applications']['GET'] = 'office/applications/delivered_application';
$route['spservices/office/get-delivered-applications']['POST'] = 'office/applications/get_delivered_applications';

$route['spservices/office/revert-applicant-applications']['GET'] = 'office/applications/revert_applicant_application';
$route['spservices/office/get-revert-applicant-applications']['POST'] = 'office/applications/get_revert_applicant_applications';

$route['spservices/office/office-revert-applications']['GET'] = 'office/applications/office_revert_application';
$route['spservices/office/get-office-revert-applications']['POST'] = 'office/applications/get_office_revert_applications';

$route['spservices/office/rejected-applications']['GET'] = 'office/applications/rejected_application';
$route['spservices/office/get-rejected-applications']['POST'] = 'office/applications/get_rejected_applications';

$route['spservices/office/application_details/(:any)']['GET'] = 'office/applications/application_details/$1';
$route['spservices/office/applications/action/(:any)']['GET'] = 'office/actions/index/$1';

// save_action
$route['spservices/office/applications/save-action']['POST'] = 'office/actions/save_action';
$route['spservices/office/applications/get_dps']['POST'] = 'office/applications/get_dps';
$route['spservices/office/applications/get_da']['POST'] = 'office/applications/get_da';
$route['spservices/office/applications/get_co_list']['POST'] = 'office/applications/get_co_list';
$route['spservices/office/applications/get_sk_list']['POST'] = 'office/applications/get_sk_list';
$route['spservices/office/applications/get_lm_list']['POST'] = 'office/applications/get_lm_list';

// certificate list 
$route['spservices/office/certificates']['GET'] = 'office/certificate/index';
$route['spservices/office/get-certificates']['POST'] = 'office/certificate/get_all_certificate';

// verify certificate 
$route['spservices/verify-certificate/(:any)']['GET'] = 'office/verifycertificate/index/$1';

$route['spservices/minority-certificate/(:any)'] = 'minoritycertificates/registration/preview/$1';
$route['spservices/minority-certificate/(:any)'] = 'minoritycertificates/registration/preview/$1';

//For minority community certificates
$route['spservices/minority-certificate']['GET'] = 'minoritycertificates/registration';
$route['spservices/minority-certificate']['POST'] = 'minoritycertificates/registration/submit';
$route['spservices/minority-certificate/(:any)'] = 'minoritycertificates/registration/index/$1';
$route['spservices/minority-certificate-preview/(:any)'] = 'minoritycertificates/registration/preview/$1';
$route['spservices/minority-certificate-payment/(:any)'] = 'minoritycertificates/payment/initiate/$1';
$route['spservices/minority-certificate-payment-response']['POST'] = 'minoritycertificates/paymentresponse/response';
$route['spservices/minority-certificate-payment-acknowledgement/(:any)'] = 'minoritycertificates/paymentresponse/acknowledgement/$1';
$route['spservices/minority-certificate-query/(:any)'] = 'minoritycertificates/query/index/$1';
$route['spservices/minority-certificate-query-submit/(:any)'] = 'minoritycertificates/query/submit/$1';
$route['spservices/minority-certificate-query-acknowledgement']['GET'] = 'minoritycertificates/query/acknowledgement';

// api for digilocker
$route['spservices/test']['POST'] = 'digilocker_api/test';

// download-report
$route['spservices/office/download-report']['GET'] = 'office/exports/index';
$route['spservices/office/report-generation']['POST'] = 'office/exports/generate';
$route['spservices/office/download/(:any)']['GET'] = 'office/exports/download/$1';

//Senior Citizen Certificate
$route['spservices/senior-citizen-certificate']['GET'] = 'seniorcitizen/scc';
$route['spservices/senior-citizen-certificate']['POST'] = 'seniorcitizen/scc/submit';
$route['spservices/senior-citizen-certificate/fileuploads/(:any)'] = 'seniorcitizen/scc/fileuploads/$1';

//Permission for Delayed Birth Registration
$route['spservices/delayed-birth-registration']['GET'] = 'delayedbirth/pdbr';
$route['spservices/delayed-birth-registration']['POST'] = 'delayedbirth/pdbr/submit';
$route['spservices/delayed-birth-registration/fileuploads/(:any)'] = 'delayedbirth/pdbr/fileuploads/$1';


//Start of Next of Kin Route//
$route['spservices/next-of-kin']['GET'] = 'nextofkin/registration';
$route['spservices/next-of-kin']['POST'] = 'nextofkin/registration/submit';
//End of Next of Kin Route//

//Start of Delayed Death Route//
$route['spservices/delayed-death']['GET'] = 'delayeddeath/registration';
$route['spservices/delayed-death']['POST'] = 'delayeddeath/registration/submit';
//End of Delayed Death Route//

//Start of Caste Route//
$route['spservices/caste']['GET'] = 'caste/registration';
$route['spservices/caste']['POST'] = 'caste/registration/submit';
//End of Caste Route//

//Start of Building Permission Route//
$route['spservices/building-permission']['GET'] = 'buildingpermission/registration';
$route['spservices/building-permission']['POST'] = 'buildingpermission/registration/submit';

//Income Certificate Routes
//$route['spservices/income-certificate']['GET'] = 'incomecertificate/registration1';



//Start of APDCL Routes
$route['spservices/apdcl_form']['GET'] = 'apdcl/registration';
$route['spservices/apdcl_form']['POST'] = 'apdcl/registration/submit';
$route['spservices/apdcl_fileupload']['GET'] = 'apdcl/registration/fileUpload';
$route['spservices/apdcl_preview']['GET'] = 'apdcl/registration/preview';
$route['spservices/apdcl_form/(:any)'] = 'apdcl/registration/index/$1';
//End of Apdcl routes


//Income Certificate Route: Author Name: Sayeed
$route['spservices/income-certificate']['GET'] = 'income/inc';
$route['spservices/income-certificate']['POST'] = 'income/inc/submit';
$route['spservices/income-certificate/fileuploads/(:any)'] = 'income/inc/fileuploads/$1';
$route['spservices/incomecertificate/icapi/update_data'] = 'income/icapi/update_data';

//Bakijai Clearance Certificate Route: Author Name: Sayeed
$route['spservices/bakijai-clearance-certificate']['GET'] = 'bakijai/bakcl';
$route['spservices/bakijai-clearance-certificate']['POST'] = 'bakijai/bakcl/submit';
$route['spservices/bakijai-clearance-certificate/fileuploads/(:any)'] = 'bakijai/bakcl/fileuploads/$1';

//Building Permission Cancellation Service: Get Ref No
$route['spservices/cancellation-building-permission-service-ref-no']['GET'] = 'buildingpermission/registration/get_ref_no';
$route['spservices/cancellation-building-permission-service-ref-no']['POST'] = 'buildingpermission/registration/cancel_form';
$route['spservices/cancellation-building-permission-service-list']['GET'] = 'buildingpermission/registration/cancellation_service_list';
// End here

//
$route['spservices/edistrict_api/reinitiate_application'] = 'edistrict_api/reinitiate_application/get_request';

//Start of ACMR-CP CME Route//
$route['spservices/acmr-cp-cme']['GET'] = 'acmr_cp_cme/registration';
$route['spservices/acmr-cp-cme']['POST'] = 'acmr_cp_cme/registration/submit';
//End of ACMR-CP CME Route//
//Employment Exchange Routes: Author: Abhijit
$route['spservices/employment-registration-aadhaar-based']['GET'] = 'employment_aadhaar_based/registration';
$route['spservices/employment-registration/personal-details/(:any)'] = 'employment_aadhaar_based/registration/personal_details/$1';
// $route['spservices/employment-registration/personal-details/(:any'] = 'employment_aadhaar_based/registration/personal_details/$1';
$route['spservices/employment-registration/address-section/(:any)'] = 'employment_aadhaar_based/registration/address/$1';
$route['spservices/employment-registration/education-skill-details/(:any)'] = 'employment_aadhaar_based/registration/skill_education/$1';
$route['spservices/employment-registration/work-experiences/(:any)'] = 'employment_aadhaar_based/registration/work_experiences/$1';
$route['spservices/employment-registration/employment-exchange/(:any)'] = 'employment_aadhaar_based/registration/employment_exchange/$1';
$route['spservices/employment-registration/enclosures/(:any)']['GET'] = 'employment_aadhaar_based/registration/enclosures/$1';
$route['spservices/employment-registration/preview/(:any)']['GET'] = 'employment_aadhaar_based/registration/preview/$1';
$route['spservices/employment-registration/view/(:any)']['GET'] = 'employment_aadhaar_based/registration/view/$1';
$route['spservices/employment-registration/generate_certificate/(:any)']['GET'] = 'employment_aadhaar_based/registration/generate_certificate/$1';

$route['spservices/employment-registration/previous-registration-details/(:any)'] = 'employment_aadhaar_based/renewal/previous_registration_details/$1';

//Employment Exchange Re-Registration Routes: Author: Abhijit, Mandeep
$route['spservices/employment-re-registration']['GET'] = 'employment_aadhaar_based/reregistration';
$route['spservices/employment-re-registration/getOldData']['GET'] = 'employment_aadhaar_based/reregistration/fetch_previous_data';
$route['spservices/employment-re-registration/getOldData/(:any)']['GET'] = 'employment_aadhaar_based/reregistration/fetch_data/$1';
//$route['spservices/employment-re-registration/personal-details/(:any)'] = 'employment_aadhaar_based/reregistration/personal_details/$1';
$route['spservices/employment-re-registration/address-details/(:any)'] = 'employment_aadhaar_based/reregistration/address_details/$1';
$route['spservices/employment-re-registration/education-skill-details/(:any)'] = 'employment_aadhaar_based/reregistration/skill_education/$1';
$route['spservices/employment-re-registration/work-experiences/(:any)'] = 'employment_aadhaar_based/reregistration/work_experiences/$1';
$route['spservices/employment-re-registration/employment-exchange/(:any)'] = 'employment_aadhaar_based/reregistration/employment_exchange/$1';
$route['spservices/employment-re-registration/enclosures/(:any)']['GET'] = 'employment_aadhaar_based/reregistration/enclosures/$1';
$route['spservices/employment-re-registration/preview/(:any)']['GET'] = 'employment_aadhaar_based/reregistration/preview/$1';
//$route['spservices/employment-re-registration/view/(:any)']['GET'] = 'employment_aadhaar_based/reregistration/view/$1';
$route['spservices/employment-re-registration/generate_certificate/(:any)']['GET'] = 'employment_aadhaar_based/reregistration/generate_certificate/$1';
//Re-registration Ends Here

//Start of ACMR-Registration of Additional Degrees Route//
$route['spservices/acmr-reg-of-addl-degrees']['GET'] = 'acmr_reg_of_addl_degrees/registration';
$route['spservices/acmr-reg-of-addl-degrees']['POST'] = 'acmr_reg_of_addl_degrees/registration/submit';
//End of ACMR-Registration of Additional Degrees Route//

//Start of MBBS Permanenr Registration Route//
$route['spservices/permanent-registration-mbbs']['GET'] = 'permanent_registration_mbbs/registration';
$route['spservices/permanent-registration-mbbs']['POST'] = 'permanent_registration_mbbs/registration/submit';
//End of MBBS Permanenr Registration Route//

//Start of ACMR-PROVISIONAL REGISTRATION CERTIFICATE OF MBBS DOCTOR//
$route['spservices/acmr-provisional-certificate']['GET'] = 'acmr_provisional_certificate/registration';
$route['spservices/acmr-provisional-certificate']['POST'] = 'acmr_provisional_certificate/registration/submit';
//End of ACMR-PROVISIONAL REGISTRATION CERTIFICATE OF MBBS DOCTOR//
//NCL Certificate Routes
$route['spservices/noncreamylayercertificate']['GET'] = 'noncreamylayercertificate/registration';





//ACMR- No Objection Certificate
$route['spservices/acmr-noc']['GET'] = 'acmrnoc/noc';
$route['spservices/acmr-noc']['POST'] = 'acmrnoc/noc/submit';
$route['spservices/acmr-noc/fileuploads/(:any)'] = 'acmrnoc/noc/fileuploads/$1';
$route['spservices/dsign/(:any)'] = 'upms/dscsign/index/$1';






