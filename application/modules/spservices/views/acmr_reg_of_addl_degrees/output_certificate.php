<?php
// pre($data[0]->form_data);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Output certificate</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link defer href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        * {
            margin: 6px 0;
            /* padding: 0; */
            font-family: sans-serif;
        }

        body {
            padding: 0 20px;
            /*background-image: url('<?php echo FCPATH . 'assets/frontend/images/watermark.png' ?>');*/
            background-repeat: no-repeat;
            background-attachment: fixed;
            /* background-size: cover; */
            /* background-size: 600px 400px;
            background-position: 50% 65%; */

        }

        .wrapper {
            border: 2px solid #808080;
            padding: 10px;
        }

        .topbar {
            width: 100%;
            margin-top: 25px;
            text-align: justify;
            line-height: 1.5;
            letter-spacing: 1px;
        }

        .topbar td,
        p {
            font-size: 20px;
        }

        .topbar .left {
            width: 50%
        }

        .topbar .right {
            width: 50%;
        }

        .topbar img {
            width: 70px;
        }

        .info {
            font-size: 10px;
            margin-top: 30px;
            text-align: justify;
            line-height: 1.5;
            letter-spacing: 1px;
        }

        .info table td,
        th {
            border: 1px solid #990000;
            border-collapse: collapse;
            background-color: #ffffcc;
        }

        .sign_div {
            margin-top: 230px;
            margin-bottom: 0;
            padding-bottom: 0;


            /* position: absolute;
            bottom: 0; */

        }

        .info b {
            text-transform: capitalize;
        }

        .logos {
  overflow: auto;
}

.left,
.right {
  width: 50%;
  float: left;
},

.sign_div {
  overflow: auto;
}

    </style>
</head>

<body>
    <?php 
$passport = FCPATH . $data[0]->form_data->photo_of_the_candidate;
$passportPath = $passport;
$passportData = file_get_contents($passportPath);
$passportBase64Image = base64_encode($passportData);


$signature = FCPATH . $data[0]->form_data->signature_of_the_candidate;
$signaturePath = $signature;
$signatureData = file_get_contents($signaturePath);
$signatureBase64Image = base64_encode($signatureData);



$currentDate = date('d-m-Y'); 
$Certificate_valid_timeline = date('d-m-Y', strtotime($currentDate . ' +5 years'));

?>
    <div class="wrapper">
        <div class="main">

        
        <!-- Logos div start -->

        <div class="logos"> 
            <div class="left" ><img src="assets\acmr\logos\image-001.jpg" style="width: 20%; height: auto;" alt="Description of the image"></div>
        
            <div style="text-align:right" class="right" ><img src="assets\acmr\logos\image-002.jpg" style="width: 20%; height: auto;" alt="Description of the image"></div>
        </div>
        <!-- Logos div end -->
        <!-- Topbar start -->
        <div class="topbar">
            <p style="font-size:14px;text-align:center;font-weight:bold;margin-top:0;padding-top:0">
                <span style="color:#be550f;font-size:30px">ASSAM COUNCIL OF MEDICAL REGISTRATION</span><br>
                <span style="color:#658e4a;font-size:30px">CERTIFICATE OF REGISTRATION</span><br>
                <span style="color:#658e4a;font-size:25px">of additional Qualification</span><br>
                <span style="font-size:10px">OFFICE OF THE ASSAM COUNCIL OF MEDICAL REGISTRATION <br> GUWAHATI, ASSAM (INDIA)</span>
            </p>
            <p style="font-size:20px;text-align:center;font-weight:bold;margin-top:0;padding-top:0;">
                (Constituted under The Assam Medical Council Act, 1999, vide Government of Assam Notification No. LGL.60/98/18 dated 18th December, 1999)
            </p>
        </div>
        <!-- Topbar end -->
            <div class="info">
                <table width="100%">
                    <thead>
                        <tr>
                            <th style="text-align:center;font-weight:bold">Certificate No.</th>
                            <th style="text-align:center;font-weight:bold">Date of Registration</th>
                            <th style="text-align:center;font-weight:bold">Original Registration No.</th>
                            <th style="text-align:center;font-weight:bold">Name</th>
                            <th style="text-align:center;font-weight:bold">Additional Qualification(s) with Year</th>
                            <th style="text-align:center;font-weight:bold">Name of University</th>
                            <th style="text-align:center;font-weight:bold">
                                Permanent Address
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="20%">AS/ACMR/<?= date('Y') ?>/AQ/...</td>
                            <td width="20%"><?= $currentDate ?></td>
                            <td width="20%"><?= $data[0]->form_data->applicant_name ?></td>
                            <td width="20%"><?= $data[0]->form_data->applicant_name ?></td>
                            <td width="20%">
                                <?php
                                if (isset($data[0]->form_data->addl_qualification_details)) {
                                    $addQual = $data[0]->form_data->addl_qualification_details;
                                    foreach ($addQual as $key => $rows) { 
                                         echo $rows->addl_qualification;    
                                         if(sizeof((array) $data[0]->form_data->addl_qualification_details) > 1) 
                                         echo ', ';                            }
                                }else{
                                    echo "---";
                                }
                                ?>
                            </td>
                <td width="20%"><?= $data[0]->form_data->primary_qua_university_award_intership ?></td>
                <td width="20%"><?= $data[0]->form_data->permanent_addr ?></td>
                <!-- <td width="20%"><?= $data[0]->form_data->acmrrno ?></td> -->

                </tr>
                    </tbody>
                </table>
            </div>

                    <!-- Signature part -->
                    <br>
                    <br>
<div style="white-space: nowrap;">
  <div style="display: inline-block; width: 23%; height: 100px;">

    <?php $qrcode = base64_encode(file_get_contents(FCPATH . $qr));?>
        <div class="" style="height: 100px;; width: 23%"><img src="data:image/png;base64,<?php echo $qrcode ?>" class="">
    </div>
    </div>


  <div style="display: inline-block; width: 23%; height: 100px;">
     <div class="bordered-div" style="height:70px; width:120px; position: relative; overflow: hidden;">
        <img style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;" src="data:image/png;base64,<?= $passportBase64Image ?>"/>
        
    </div>
            <div class="bordered-div" style="height:20px; width:120px;position: relative; overflow: hidden;" >
    <img style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;" src="data:image/png;base64,<?= $passportBase64Image ?>"/>
    
    </div>
</div>
  <div style="display: inline-block; width: 23%; height: 100px;text-align:left;">
<div style="font-style:bold;font-size: 10px;">*This certificate is valid upto <br><?= $Certificate_valid_timeline ?></div>  
</div>
  <div style="display: inline-block; width: 23%;  height: 100px;font-size: 13px">
<div style="text-align:center; font-style:bold">Registrar</div>
<div style="text-align:center; font-style:bold">Assam Council of Medical Registration</div>
<div style="text-align:center; font-style:bold">Sixmile, Khanapara, Guwahati-22</div>
</div>
</div>

<br>



<!-- Signature end -->






        <!-- Ending -->
    </div>
</div>


</body>

</html>