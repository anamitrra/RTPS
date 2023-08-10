<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Output certificate</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link defer href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- PAGE LEVEL STYLES-->
    <!-- jQuery -->
    <style>
        /* @font-face {
                font-family: 'Uxa';
        
                src: url(<?php echo base_url('assets/fonts/Uxa_Final.ttf') ?>)) format('truetype');
            }

            body {
                font-family: 'Uxa';
            } */

*{
    margin:0;
    padding: 0;
}
body{
    font-size:13px;
}
.wrapper{
    border:2px solid #808080;
    /* height:100% */
}
.topbox{
    margin:0 auto;
    padding:20px 0 0 0;
    text-align:center;

    /* border-bottom: 2px solid #eee; */
}
.main{
    border-top:2px solid #9a9a9a;
    margin-top:5px;
    padding-top:20px;
    padding:20px;
}
    </style>
</head>
<body>
     <div class="" id="pg">
        <div class="wrapper">
            <div class="col-md-6 text-center topbox">
                <img class="img" src="<?= base_url("assets/frontend/images/assam_logo.png") ?>" width="35"/>
                <h6 style="margin:5px 0 0 0 "><b>GOVERNMENT OF ASSAM</b></h6>
                <p style="margin:0">উপায়ুক্তৰ কাৰ্য্যালয়</p>
                <h6 style="margin:0"><b>OFFICE OF THE DEPUTY COMMISSIONER</b></h6>
                <p  style="margin-top:10px"><b>KAMRUP METRO DISTRICT</b></p>
            </div>
            <?php foreach($data as $val){ ?>
            <div class="main">
                <table width="100%">
                    <tr>
                        <td style="width:33%">প্ৰমান পত্ৰ নং<br> Certificate Number</td>
                        <td style="text-align: center;width:33%">আবেদন নং<br> Application No</td>
                        <td style="text-align: right;">তাৰিখ/ Date</td>
                    </tr>
                    <tr>
                        <td><b><?php echo $certificate_no; ?></b></td>
                        <td style="text-align: center;"><b><?php echo $val->rtps_trans_id ?></b></td>
                        <td style="text-align: right;"><b><?php echo date("d/m/Y"); ?></b></td>
                    </tr>
                </table>
                <!-- <div class="row second">
                    <div class="col-md-4" style="width:33%;border:1px solid red">
                            <p class="mb-0">প্ৰমান পত্ৰ নং<br> Certificate Number</p>
                            <label for="">CERT/SC/2022/00001</label>
                    </div>
                    <div class="col-md-4 text-center" style="width:33%;border:1px solid blue">
                            <p class="mb-0">আবেদন নং<br> Application No </p>
                            <label for=""><?php echo $val->rtps_trans_id ?></label>
                    </div>
                    <div class="col-md-4 text-right" style="width:33%;border:1px solid red">
                            <p class="mb-0">তাৰিখ/ Date </p>
                            <label for="">04/02/2022</label>
                    </div>
                </div> -->
                <div class="text-center" style="line-height:30px;">
                    <!-- <h6>জাতিৰ প্ৰমান পত্ৰ</h6> -->
                    <h5 style="text-align:center"><b>CASTE CERTIFICATE</b></h5>
                    <p style="text-align:justify;margin-top:10px">ইয়াৰ দ্বাৰা প্ৰমাণিত কৰা হ’ল যে <b><?php echo $val->applicant_name ?></b> পিতা <b><?php echo $val->father_name ?></b> গাঁও/নগৰ <b><?php echo $val->pa_village ?></b> পোঃ অঃ <b><?php echo $val->pa_po ?></b> মৌজা <b><?php echo $val->pa_mouza ?></b> আৰক্ষী চকীঃ
                    <b><?php echo $val->pa_ps ?></b> জিলাঃ <b><?php echo $val->pa_district ?></b> অসমৰ অনুসুচিত জাতি সম্প্ৰদায় (উপজাতি <b><?php echo $val->sub_caste ?></b> ) /যি ভাৰতীয় সংবিধানৰ (Scheduled Caste
                    and Scheduled Tribes Order 1950 (1956  অনুসুচিত জাতি সূচিৰ দ্বাৰা সংশোধিত, the North Eastern Area (reorganisation) Act' 1971 and
                    Scheduled Castes and Tribes Order (Amendment) Act 1976) দ্বাৰা অনুসূচিত জাতি হিচাপে স্বীকৃত।</p>

                    <p style="text-align:justify">This is to certify that <b><?php echo $val->applicant_name ?></b> Son/Daughter of <b><?php echo $val->father_name ?></b> Vill/Town <b><?php echo $val->pa_village ?></b> P.O <b><?php echo $val->pa_po ?></b> Mouza <b><?php echo $val->pa_mouza ?></b> P.S <b><?php echo $val->pa_ps ?></b>
                    District- <b><?php echo $val->pa_district ?></b> in the State of Assam belonging to the Scheduled Caste Community (Sub Caste <b><?php echo $val->sub_caste ?></b> ) which is
                    recognised as Scheduled Caste under the Constitution of India (Scheduled Caste and Scheduled Tribes Order 1950 (as amendment
                    by the Scheduled Caste modification Order 1956, the North Eastern Area (Re-organisation) Act 1971 and Scheduled Caste and
                    Tribes Order (Amendment) Act 1976)
                    </p>
                </div>
                <div class="row" >
                    <table width="100%">
                        <tr>
                            <td width="18%"><img src="<?= base_url($val->applicant_photo); ?>" width="15%"></td>
                            <td width="18%"><img src="<?= base_url($qr); ?>" width="20%"></td>
                            <td style="text-align:right; vertical-align: bottom;"><p style="margin-top:0px"><b>Signature of Approving Authority</b></p></td>
                        </tr>
                    </table>
                    <!-- <div class="col">
                    <img src="<?= base_url($val->applicant_photo); ?>" width="15%">
                    <img src="<?= base_url($val->applicant_photo); ?>" width="15%">
                    </div> -->
                    <!-- <div class="col text-right">
                        <p style="margin-top:0px"><b>Signature of Approving Authority</b></p>
                    </div> -->

                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>