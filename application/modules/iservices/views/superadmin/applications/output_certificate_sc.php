<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php  echo SITE_TITLE;?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/fontawesome-free/css/all.min.css"> <!-- Ionicons -->
    <link rel="stylesheet" href="<?= base_url("assets/"); ?>dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url("assets/css/"); ?>custom.css">
    <link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link defer href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- PAGE LEVEL STYLES-->
    <!-- jQuery -->
    <script src="<?= base_url("assets/"); ?>plugins/jquery/jquery.min.js"></script>
    <script src="<?= base_url("assets/"); ?>plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?= base_url("assets/"); ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
    <script src="<?= base_url("assets/"); ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="<?= base_url("assets/"); ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="<?= base_url("assets/"); ?>js/html2pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        * {
            margin:0;padding:0
        }
.wrapper{
    border:2px solid #808080;
    /* height:100% */
}
.topbox{
    margin:0 auto;
    padding:20px 0 0px 0;
    /* border-bottom: 2px solid #eee; */
}
.main{
    border-top:2px solid #9a9a9a;
    margin-top:20px;
    padding-top:20px;
    padding:20px;
}
    </style>
</head>
<body>
    <div class="" id="pg">
        <div class="wrapper">
            <div class="col-md-6 text-center topbox">
                <img class="img" src="<?= base_url("assets/frontend/images/assam_logo.png") ?>" width="9%"/>

                <h6 class="mb-0"><b>GOVERNMENT OF ASSAM</b></h6>
                <p class="mb-0">উপায়ুক্তৰ কাৰ্য্যালয়  উপায়ুক্ত</p>
                <h6 class="mt-0"><b>OFFICE OF THE DEPUTY COMMISSIONER</b></h6>
                <h7><b>KAMRUP METRO DISTRICT</b></h7>
            </div>
            <?php foreach($data as $val){ ?>
            <div class="main">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <p class="mb-0">মান প নং<br> Certificate Number</p>
                            <label for="">CERT/SC/2022/00001</label>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="form-group">
                            <p class="mb-0">আেবদন নং<br> Application No </p>
                            <label for="" id="ref"><?php echo $val->rtps_trans_id ?></label>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="form-group">
                            <p class="mb-0">তািৰখ / Date </p>
                            <label for="">04/02/2022</label>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <h6>জািতৰ মান প</h6>
                    <h5><b>CASTE CERTIFICATE</b></h5>
                    <p style="text-align:justify;margin-top:10px">ইয়াৰ াৰা 
                    মািণত কৰা হ’ল য <b><?php echo $val->applicant_name ?></b>  িপতা <b><?php echo $val->father_name ?></b> গঁাও/নগৰঃ <b><?php echo $val->pa_village ?></b> পাঃ অঃ <b><?php echo $val->pa_po ?></b> মৗজা <b><?php echo $val->pa_mouza ?></b> আৰ%ী চকীঃ
                    <b><?php echo $val->pa_ps ?></b> িজলাঃ <b><?php echo $val->pa_district ?></b> অসমৰ অনসু ুিচত জািত স)দায় (উপজািত <b><?php echo $val->sub_caste ?></b> ) / িয ভাৰতীয় সংিবধানৰ (Scheduled Caste
                    and Scheduled Tribes Order 1950 (1956 অনসু ুিচত জািত সূিচৰ াৰা সংেশািধত, the North Eastern Area (reorganisation) Act' 1971 and
                    Scheduled Castes and Tribes Order (Amendment) Act 1976) াৰা অনসু ূিচত জািত িহচােপ .ীকৃত</p>

                    <p style="text-align:justify">This is to certify that <b><?php echo $val->applicant_name ?></b> Son/Daughter of <b><?php echo $val->father_name ?></b> Vill/Town <b><?php echo $val->pa_village ?></b> P.O <b><?php echo $val->pa_po ?></b> Mouza <b><?php echo $val->pa_mouza ?></b> P.S <b><?php echo $val->pa_ps ?></b>
                    District- <b><?php echo $val->pa_district ?></b> in the State of Assam belonging to the Scheduled Caste Community (Sub Caste <b><?php echo $val->sub_caste ?></b> ) which is
                    recognised as Scheduled Caste under the Constitution of India (Scheduled Caste and Scheduled Tribes Order 1950 (as amendment
                    by the Scheduled Caste modification Order 1956, the North Eastern Area (Re-organisation) Act 1971 and Scheduled Caste and
                    Tribes Order (Amendment) Act 1976)
                    </p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <img class="img" src="<?= base_url("assets/frontend/images/assam_logo.png") ?>" width="18%"/>
                    <img class="img" src="<?= base_url("assets/frontend/images/assam_logo.png") ?>" width="18%"/>
                    
                    <!-- <img src="<?= base_url($value->applicant_photo); ?>" width="15%"> -->
                    </div>
                    <div class="col-md-6 text-right">
                        <p style="margin-top:50px"><b>Signature of Approving Authority</b></p>
                    </div>

                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <button onclick="generatePDF()">Download as PDF</button>
    <script>
 
//  var element = document.getElementById('canvas_div_pdf');
//  html2pdf(element);

			function generatePDF() {
				// Choose the element that our invoice is rendered in.
				const element = document.getElementById('pg');
				// Choose the element and save the PDF for our user.
				// html2pdf().from(element).save();
                html2pdf(element, {
  margin:       0,
  filename:     'myfile.pdf',
  image:        { type: 'jpeg', quality: 0.98 },
  html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true,width: 810, height: 1000 },
  jsPDF:        { unit: 'pt', format: 'a4', orientation: 'portrait' }
});
			}
		</script>
</body>
</html>