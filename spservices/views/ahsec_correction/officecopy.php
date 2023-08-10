<?php
//Application entered Data
$objId = $dbrow->{'_id'}->{'$id'};
$Registration_No = $marksheet_data->Registration_No ?? '';
$Registration_Session = $marksheet_data->Registration_Session ?? '';
$Roll = $marksheet_data->Roll ?? '';
$No = $marksheet_data->No ?? '';
$mobile = $dbrow->form_data->mobile ?? '';
$email = $dbrow->form_data->email ?? '';
$applicant_name = $dbrow->form_data->applicant_name ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Copy - <?= $dbrow->service_data->service_name ?> </title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .certificate-container {
            border: 2px solid #555;
            padding: 20px;
            width: 70%;
            margin: 20px auto;
            text-align: center;
        }
        .certificate-logo {
            max-width: 200px; /* Adjust the logo size as needed */
            margin: 0 auto;
            display: block;
        }
        .certificate-title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 6px 0;
        }
        .certificate-title1 {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 6px 0;
            color: blue;
        }
        .certificate-title2 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 6px 0;
        }
        .certificate-hr {
            border: 1px solid #555;
            width: 100%;
            margin: 20px auto;
        }
        .certificate-text {
            text-align: justify;
            /* margin-bottom: 30px; */
        }
        .certificate-signature {
            text-align: right;
            margin-top: 30px;
        }
        .signature-line {
            border-top: 1px solid #555;
            width: 150px;
            margin: 0;
        }
        .signature-name {
            font-weight: bold;
            margin-top: 10px;
        }

        .print-button {
            display: block;
        }

        @media print {
            /* Hide the button when printing the page */
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="certificate-container">
        <img src="<?= base_url("assets/frontend/images/logo_ahsec.png") ?>" alt="Certificate Logo" width="80" height="80"  class="certificate-logo">
        <div class="certificate-title">  ASSAM HIGHER SECONDARY EDUCATION COUNCIL</div>
        <div class="certificate-text" style="text-align: center;">Bamunimaidam, Guwahati-21
        </div>
      
        <hr class="certificate-hr">

        <div class="certificate-title1"><?= $data['pageTitle'] ?></div>
        <div class="certificate-title2">ARN: <?= $dbrow->service_data->appl_ref_no ?></div>
        <div class="certificate-text" style="display: flex;">
            <div style="width: 70%">
                To,<br/>
                The Secretary,<br/>
                Assam Higher Secondary Education Council<br/>
                Bamunimaidam, Guwahati- 781021.<br/>
            </div>
            <div style="width: 30%">
                <img src="<?=base_url($dbrow->form_data->photo_of_the_candidate ?? $dbrow->form_data->passport_photo)?>" style="width:90px; margin: 3px; float: right;" />
            </div>
        </div>
        <div class="certificate-text" style="margin-top: 8px;">
        Sir,<br> I have the honour to request you to correct the following documents : <b>
        <?php
        if ($dbrow->service_data->service_id == "AHSECCRC")
           print"Registration Card";
        else if ($dbrow->service_data->service_id == "AHSECCADM")
            print" Admit Card";
        else if ($dbrow->service_data->service_id == "AHSECCMRK")
            print"Marksheet";
        else if ($dbrow->service_data->service_id == "AHSECCPC")
            print"Pass Certificate";
        ?></b>
        </div>

        <div class="certificate-text" style="margin-top: 15px;">
        Name : <b><?= $applicant_name ?></b> having Registration No : <b><?= $Registration_No ?></b> of <b><?= $Registration_Session ?></b><br>
        Roll : <b><?= $Roll ?></b> No: <b><?= $No ?></b><br>
        Mobile: <b><?= $dbrow->form_data->mobile ?></b> Email: <b><?= $dbrow->form_data->email ?></b>
        </div>

        <div class="certificate-text" style="margin-top: 25px;">
        <b>Declaration:</b><br>
        I hereby declare that all the information provided by me are correct to the best of my knowledge and belief.
        </div>

        <div class="certificate-text" style="margin-top: 45px;">
        <b>** No need to submit copy of this document to AHSEC, it is for your further reference only.</b>
        </div>

        <!-- Print Button -->
        <button class="print-button" onclick="printCertificate()" style="margin-top: 45px;">Print Certificate</button>
    </div>

    <script>
        function printCertificate() {
            // Print the certificate when the button is clicked
            window.print();
        }
    </script>
</body>
</html>