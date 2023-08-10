<?php
defined("BASEPATH") or exit("No direct script access allowed");
if (!function_exists("employmentcertificate")) {
    function employmentcertificate($type)
    {
        switch ($type) {
            case 'proof_of_residence':
                $data = array(
                    "enclosure_type" => "Proof of Residence",
                    "recomended_documents" => array(
                        "Driving License (either self or parents)",
                        "Copy of Passport (either self or parents)",
                        "Certified Copy of Electoral Roll/EPIC (either self or parents)",
                        "Copy of Chitha/Jamabandi (either self or parents)",
                        "Voter Card"
                    )
                );
                break;
            case 'noc_from_current_employeer':
                $data = array(
                    "enclosure_type" => "NOC from Current Employer",
                    "recomended_documents" => array(
                        "NOC from Employer"
                    )
                );
                break;
            case 'age_proof':
                $data = array(
                    "enclosure_type" => "Age Proof ",
                    "recomended_documents" => array(
                        "Birth Certificate", "School Certificate", "HSLC Admit Card"
                    )
                );
                break;
            case 'caste_certificate':
                $data = array(
                    "enclosure_type" => "Copy of caste certificate",
                    "recomended_documents" => array(
                        "Copy of caste certificate", "EWS Certificate"
                    )
                );
                break;
            case 'educational_qualification':
                $data = array(
                    "enclosure_type" => "Educational Qualification certificate",
                    "recomended_documents" => array(
                        "Pass Certificates and Marksheets"
                    )
                );
                break;
            case 'other_qualification_certificate':
                $data = array(
                    "enclosure_type" => "Other Qualifications/Trainings/Courses Certificate",
                    "recomended_documents" => array(
                        "Others", "Skill training", "Computer diploma"
                    )
                );
                break;
            case 'previous_employment':
                $data = array(
                    "enclosure_type" => "Previous employment certificates",
                    "recomended_documents" => array(
                        "Previous employment certificates"
                    )
                );
                break;
            case 'pwd_certificate':
                $data = array(
                    "enclosure_type" => "Persons with disability certificate",
                    "recomended_documents" => array(
                        "Persons with disability certificate"
                    )
                );
                break;
            case 'ex_servicemen_certificate':
                $data = array(
                    "enclosure_type" => "Ex-servicemen certificate",
                    "recomended_documents" => array(
                        "Ex-servicemen certificate"
                    )
                );
                break;
            case 'work_experience':
                $data = array(
                    "enclosure_type" => "Work experience",
                    "recomended_documents" => array(
                        "Work Experience Certificate"
                    )
                );
                break;
            case 'any_other_document':
                $data = array(
                    "enclosure_type" => "Any other document",
                    "recomended_documents" => array(
                        "Any other document"
                    )
                );
                break;
            case 'aadhaar_card':
                $data = array(
                    "enclosure_type" => "Aadhaar Card",
                    "recomended_documents" => array(
                        "Aadhaar Card"
                    )
                );
                break;
            case 'unique_document':
                $data = array(
                    "enclosure_type" => "Unique Identification Document",
                    "recomended_documents" => array(
                        "Driving License",
                        "Voter Card",
                        "Copy of Passport",
                        "Copy of PAN",
                        "Aadhaar Card",
                        "Unique Identification Document"
                    )
                );
                break;
            case 'soft_copy':
                $data = array(
                    "enclosure_type" => "Upload the Soft Copy of Application Form",
                    "recomended_documents" => array(
                        "Upload the Soft Copy of Application Form"
                    )
                );
                break;
            case 'passport_photo':
                $data = array(
                    "enclosure_type" => "Passport Size Photo",
                    "recomended_documents" => array(
                        "Passport Photo"
                    )
                );
                break;
            case 'signature':
                $data = array(
                    "enclosure_type" => "Signature",
                    "recomended_documents" => array(
                        "Signature"
                    )
                );
                break;
            default:
                $data = $type;
                break;
        } //End of switch
        return $data;
    } // End of employmentcertificate()
} // End of if statement