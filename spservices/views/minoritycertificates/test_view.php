<?php
$aadhaar_no = $aadhaar_no??null;
$txn_no = $txn_no??null;       
?>
<!DOCTYPE html>
<body>    
    <?php if(strlen($aadhaar_no)) { ?>
        <form id="myfrm" method="POST" action="<?= base_url('spservices/minoritycertificates/test/otpverify') ?>">
            <input name="aadhaar_no" value="<?=$aadhaar_no?>" type="text" readonly /><br>
            <input name="txn_no" value="<?=$txn_no?>" type="text" readonly /><br>
            <input name="otp" value="" type="text" placeholder="otp" /><br>
            <input name="name" value="" type="text" placeholder="name" /><br>
            <input name="state" value="Assam" type="text" readonly /><br>
            <button type="submit">VERIFY</button>
        </form>
    <?php    
    echo "<br>Results : <br>";
    echo '<pre>'; var_dump($xml); echo '</pre>';        
    echo "<br><br><br>";
    echo "ret : ".$xml->attributes()->ret."<br>";
    echo "err : ".$xml->attributes()->err."<br>";
    } else { ?>
        <form id="myfrm" method="POST" action="<?= base_url('spservices/minoritycertificates/test/otpsend') ?>">
            <input name="aadhaar_no" value="" type="text" placeholder="aadhaar no." maxlength="12" /><br>
            <button type="submit">SEND OTP</button>
        </form>
    <?php }//End of if else ?>
</body>
</html>