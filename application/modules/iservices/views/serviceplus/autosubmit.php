<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<!--Jquery-->
<script type="text/javascript" src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-bottom: 16px solid blue;
  width: 60px;
  height: 60px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<script>
  'use strict';
  $(document).ready(function() {
    const usserType = "<?php echo $user_type; ?>";
    if (usserType === "CSC") {
      const splusWindow = window.open('https://sewasetu.assam.gov.in/deptusr/', "_blank", 'width=300,height=300');
      setTimeout(function() {
        splusWindow.close();
        window.open("https://sewasetu.assam.gov.in/deptusr/loginWindow.do?servApply=N&%3Ccsrf:token%20uri=%27loginWindow.do%27/%3E", "_self");
      }, 1500);
    } else {
      const splusWindow = window.open('https://sewasetu.assam.gov.in/services/', "_blank", 'width=300,height=300');
      setTimeout(function() {
        splusWindow.close();
        window.open("https://sewasetu.assam.gov.in/services/loginWindow.do?servApply=N&%3Ccsrf:token%20uri=%27loginWindow.do%27/%3E", "_self");
      }, 1500);
    }
  });
</script>
</head>
<body>
<center>
<div class="loader"></div>
<span class="sr-only">Please wait, you are getting redirect to your application form ..!</span>
<center>
</body>
</html>
