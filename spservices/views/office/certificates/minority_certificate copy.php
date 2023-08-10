<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Output certificate</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ok</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link defer href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- PAGE LEVEL STYLES-->
    <!-- jQuery -->
    <style>
        /* *{
    margin:0;
    padding: 0;
} */
body{
    background-image: url('<?php echo FCPATH. 'assets/frontend/images/watermark.png' ?>');
    background-repeat: no-repeat; 
    background-attachment: fixed; 
   background-size: cover;
  background-position: 50% 65%;
  font-size:15px;
  /* border:2px solid red; */
}
.wrapper{
    border:2px solid #808080;
    padding: 10px;
}
.topbar {
  width: 100%;
}
.topbar td, p{
  font-size:17px;
}
.topbar .left{
    width:50%
}
.topbar .right{
    width: 50%;
}
.topbar img{
    width: 70px;  
}
.info {
    font-size:15px;
    /* margin-top:10px; */
    text-align:justify;
    line-height:2;
    letter-spacing: 1px;
}
.sign_div{
    margin-top:20px;
    /* position: absolute; */
    /* bottom: 0 */
}
.info  b {
    text-transform: capitalize;
}
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="topbar">
                <table width="100%">
                    <tr>
                        <td style="width:45%;vertical-align: top">Certificate Number:<br> <b><?= $certificate_no ?></b></td>
                        <td style="width:55%;vertical-align: top"><img src="<?= FCPATH ."assets/frontend/images/assam_govt.png" ?>" style="width:60px;"></td>
                    </tr>
                </table>
                <p style="text-align:center;margin-top:0;padding-top:0">GOVERNMENT OF ASSAM</p>
                <table width="100%">
                    <tr>
                        <td style="width:10%"></td>
                        <td style="vertical-align: top;text-align: center;">
                            <p style="text-align:center">OFFICE OF THE DEPUTY COMMISSIONER, <?= $data[0]->form_data->pa_district_name ?>  DISTRICT</p>
                            <br>
                            <p style="font-weight:bold;font-size:16px">{Sec.2(c) of the National Commission for Minorities Act, 1992 (19 of 1992)}</p>
                            <br>
                            <p style="font-weight:bold;font-size:20px">Minority Community Certificate</p>
                        </td>
                        <td style="width:12%;vertical-align: top;"><img src="<?= FCPATH .$data[0]->form_data->passport_photo ?>" style="width:82px;height:98px"></td>
                    </tr>
                </table>
            </div>
            <div class="info" >
                <p>This is to certify that Shri/Smti <b><?= $data[0]->form_data->applicant_name ?></b> S/O, D/O (Father’ Name) <b><?= $data[0]->form_data->father_name ?></b>
                (Mother’s Name) <b><?= $data[0]->form_data->mother_name ?></b> in the District <b><?= $data[0]->form_data->pa_district_name ?></b> of the State of Assam belongs to the <b><?= $data[0]->form_data->community ?></b>
                Minority Community as defined under Sec.2(c) of the National Commission for Minorities Act, 1992 (19 of 1992).
                Shri/Smti <b><?= $data[0]->form_data->applicant_name ?></b> and his/her family resides in Village/Town <b><?= $data[0]->form_data->pa_village ?></b>
                P.O.  <b><?= $data[0]->form_data->pa_post_office ?></b> P.S <b><?= ($data[0]->form_data->pa_police_station) ? $data[0]->form_data->pa_police_station : '' ?></b> at <b><?= $data[0]->form_data->pa_district_name ?></b> District of the State of Assam.</p>
            </div>
            <div class="sign_div">
                <table width="100%">
                    <tr>
                        <td style="width:60%; vertical-align: top">
                            <p>Place : <?= $data[0]->form_data->pa_district_name ?></p><br>
                            <p>Date : <?php echo date('d-m-Y') ?></p>

                        </td>
                        <td style="text-align:center;font-weight:bold">
                            <p>Additional Deputy Commissioner <br> <?= $data[0]->form_data->pa_district_name ?> <br> State: Assam</p>
                        </td>
                    </tr>
                </table>
                <img src="<?= FCPATH.$qr ?>" width="10%" >

            </div>
        </div>
    </div>
</body>
</html>