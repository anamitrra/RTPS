<!DOCTYPE html>
<html>

<head>
</head>

<body>

    <?php echo $app_id . '<br>' . $hash . '<br>' . $ts; ?>
    <div class="share_fm_dl" id="attachment_poi"></div>
    <br>
    <a id="share_id" href="DOCUMENT_URL" class="locker_saver"></a>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script type="text/javascript" src="https://services.digitallocker.gov.in/requester/api/2/dl.js" id="dlshare" data-app-id="<?php echo $app_id ?>" data-app-hash="<?php echo $hash ?>" time-stamp="<?php echo $ts ?>" data-callback="myCallback(data)">
    </script>

    <script type="text/javascript" src="https://services.digitallocker.gov.in/savelocker/api/1/savelocker.js" id="dlshare" data-app-id="<?php echo $app_id ?>" data-app-hash="<?php echo $hash ?>" time-stamp="<?php echo $ts ?>">
    </script>


    <script>
        function myCallback(arg) {
            console.log("SUCCESS"); // or “FAILURE” 
        }
    </script>
</body>

</html>