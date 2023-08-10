<?php  
// pre($data[0]->processing_history[1]->custom_field_values[0]->field_name);
$f_n = ($data[0]->processing_history[1]->custom_field_values[0]->field_name);
//pre($certificate_no);
//pre($f_n);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Output certificate</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0px;
        margin: 0px;
    }

    .wrapper {
        padding: 10px;
        border: 2px solid #808080;

    }

    .logos {
        text-align: center;
        margin-bottom: 30px;
    }

    .logos img {
        max-width: 150px;
    }

    .topbar {
        text-align: center;
        margin-bottom: 20px;
    }

    .topbar p {
        margin: 5px 0;
    }

    .header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .certificate-title {
        text-align: center;
        font-size: 18px;
        margin-bottom: 30px;
        color: darkblue;
        /* Set the font color to blue */
        font-family: "Times New Roman", Times, serif;
        /* Set the font style to Times New Roman */
    }


    .card {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 0px;
        margin: 0px;
        margin-bottom: 20px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;

    }

    .certificate {
        border: 1px solid #000;
        width: 600px;
        height: 80px;
        padding: 10px;
        margin: 0;
    }

    .certificate label {
        margin: 0;
        padding: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        padding: 0px;
        margin: 0px;
    }

    .info {
        text-indent: 30px;
        padding: 0px;
        margin: 0px;
    }

    .half-width {
        width: 50%;
    }

    .bottom-right {
        position: absolute;
        bottom: 10px;
        right: 10px;
        font-weight: bold;
        padding: 0px;
        margin: 0px;
    }

    .topbar {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding: 0px;
        margin: 0px;
    }

    .logo-container {
        float: left;
        margin-right: 20px;
        padding: 0px;
        margin: 0px;
    }

    .logo-img {
        max-width: 100px;
    }

    .text-container {
        font-family: "Times New Roman", Times, serif;
        /* Set the font style to Times New Roman */
        font-size: 14px;
    }

    .full-line {
        border: none;
        height: 3px;
        background-color: #000;
        margin: 20px 0;
        width: 100%;
        padding: 0px;
        margin: 0px;
    }

    .small-box {
        width: 50%;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 0px;
        margin: 0px;
        margin-bottom: 20px;
    }

    .box-line {
        border: none;
        height: 1px;
        background-color: #ccc;
        margin: 10px 0;
        width: 100%;
        padding: 0px;
        margin: 0px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-right {
        margin-left: auto;
    }

    .header {
        display: block;
        overflow: hidden;
        /* Clear floats */
    }

    .header-left {
        float: left;
    }

    .header-right {
        float: right;
    }

    .photo-td {
        width: 20%;
    }

    .passport-photo {
        width: 100px;
        height: 100px;
    }

    .qr-text {
        position: absolute;
        bottom: 10px;
        left: 10px;
        text-align: center;
        font-weight: bold;
    }

    .small-box {
        border: 1px solid #000;
        padding: 10px;
        height: 50px;
        /* Adjust the height value as needed */
        width: 400px;
        /* Adjust the width value as needed */
    }

    .half-width {
        width: 50%;
        /* Adjust the width percentage as needed */
    }

    .form-group {
        margin-bottom: 10px;
    }

    .bordered-div {
        border: 1px solid black;
        padding: 10px;
    }

    .photo-td {
        width: 20%;
    }

    .passport-photo {
        width: 150px;
        height: 100px;
    }

    .signature {
        width: 150px;
        height: 50px;
    }

    .box {
        width: 280px;
        /* Adjust the width as desired */
        height: 20px;
        /* Adjust the height as desired */
        border: 2px solid #000;
        /* Add a border */
        padding: 10px;
        /* Add padding inside the box */
        //background-color: #f0f0f0; /* Set a background color */
        box-sizing: border-box;
        /* Include border and padding within the defined width and height */
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="main">
            <div class="topbar">
                <div class="logo-container">
                    <img src="<?php echo base_url('assets/acmr/logo.jpg'); ?>" alt="ACMR Logo" class="logo-img">
                </div>
                <div class="text-container">
                    <p><b>ASSAM COUNCIL OF MEDICAL REGISTRATION<b></p>
                    <p>
                        OFFICE OF THE REGISTRAR, ASSAM COUNCIL OF MEDICAL REGISTRATION </p>
                    <p>Six Mile, Khanapara, Guwahati (Assam)-781022
                    </p>
                    <p>Email: assamcmr2016@gmail.com</p>
                </div>
            </div>
            <hr class="full-line">
            <div>
                <div style="float: left; margin-bottom: 20px;font-family:Times New Roman,Times,serif;font-size:14px">
                    No.ACMR/<?= $data[0]->service_data->appl_ref_no ?></div>
                <div style="float: right;font-family:Times New Roman,Times,serif;font-size:14px">
                    Dated:<?php echo isset($data[0]->service_data->submission_date) ? format_mongo_date($data[0]->service_data->submission_date) : ''; ?>
                </div>
            </div><br><br>
            <div>
                <h1 class="certificate-title"><u>PROVISIONAL REGISTRATION CERTIFICATE</u></h1>
            </div>
            <div class="box">
                <div class="form-group" style="font-family:Times New Roman,Times,serif;font-size:14px">
                    <label for="registration">Provisional Registration No.:<?= $certificate_no ?></label>
                </div>
            </div>
            <div
                style="float: left; width: 50%; height: 100px;margin:0;padding:15px;font-family:Times New Roman,Times,serif;font-size:16px ">
                <div class="form-group">
                    <label for="name">Name:<?= $data[0]->form_data->applicant_name ?></label>
                    <label for="parent">S/o /
                        D/o:<?= $data[0]->form_data->father_name ?><?= $data[0]->form_data->father_name ?></label>
                    <label for="address1">Address Line 1:<?= $data[0]->form_data->address1 ?></label>
                    <label for="address2">
                        Address Line 2:
                        <?php if (!empty($data[0]->form_data->address2)): ?>
                        <?= $data[0]->form_data->address2 ?>,
                        <?php endif; ?>
                        <?php if (!empty($data[0]->form_data->country)): ?>
                        <?= $data[0]->form_data->country ?>,
                        <?php endif; ?>
                        <?php if (!empty($data[0]->form_data->statee)): ?>
                        <?= $data[0]->form_data->statee ?>,
                        <?php endif; ?>
                        <?php if (!empty($data[0]->form_data->state_foreign)): ?>
                        <?= $data[0]->form_data->state_foreign ?>,
                        <?php endif; ?>
                        <?php if (!empty($data[0]->form_data->pincode)): ?>
                        <?= $data[0]->form_data->pincode ?>,
                        <?php endif; ?>
                    </label>
                </div>
            </div>
            <div style="float: right; margin-right:50px">
                <div class="passport-photo">
                    <?php if (isset($data[0]->form_data->photograph)): ?>
                    <?php
                            $photograph = base64_encode(file_get_contents(FCPATH . $data[0]->form_data->photograph));
                            ?>
                    <img src="data:image/png;base64,<?php echo $photograph ?>" class="passport-photo" width="150"><br>
                    <?php else: ?>
                    <p>No photograph available.</p>
                    <?php endif; ?>
                </div>

                <div>
                    <?php if (isset($data[0]->form_data->signature)): ?>
                    <?php
                        $signature = base64_encode(file_get_contents(FCPATH . $data[0]->form_data->signature));
                        ?>
                    <img src="data:image/png;base64,<?php echo $signature ?>" class="signature"><br>
                    <?php else: ?>
                    <p>No signature available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div style="clear: both;font-family:Times New Roman,Times,serif;font-size:16px">
            <div class="certificate">
                <label>Name of College from where passed MBBS or equivalent
                    course:<?= $data[0]->form_data->primary_qua_college_name ?></label>
                <label>Name of the
                    University:<?= $data[0]->form_data->primary_qua_university_award_intership ?></label>
                <label>Name of Institute/Hospital where CRMI is to be
                    done:<?= $data[0]->processing_history[1]->custom_field_values[0]->field_name ?></label>
            </div>
        </div>
        <div class="info" style="margin-bottom:80px;font-family:Times New Roman,Times,serif;font-size:16px">
            <p>
                Certified that your name has been Registered Provisionally with this Council and your
                Provisional Registration No. is.<?= $certificate_no ?>
            </p>
            <p>You should apply for full Registration to this Council after completion of the Compulsory
                Rotating Medical Internship (CRMI) furnishing the Internship Completion Certificate issued by
                the Principal of the College or Competent Authority along with this Provisional Registration
                Certificate.</p>
            <p>This Certificate is valid up to the date of your completion of CRMI.</p>
            <div style="float: left; width: 50%;margin-bottom:40px;padding-top:10px;padding-top:40px">
                <?php $qrcode = base64_encode(file_get_contents(FCPATH . $qr)); ?>
                <img src="data:image/png;base64,<?php echo $qrcode ?>" class="passport-photo">
            </div>
            <div
                style="float: left; width: 50%;text-align:right;padding-top:80px;font-family:Times New Roman,Times,serif;font-size:16px">
                <span>Registrar,</span><br>
                <span>Assam Council of Medical Registration</span>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

</body>

</html>