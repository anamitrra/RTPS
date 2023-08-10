<?php
json_decode($pdfFile);
$registration_no = '';
$deliver_dt = '';
$renew_dt = '';
if (json_last_error() === JSON_ERROR_NONE) {
    $decodedData = (array)json_decode($pdfFile);
    if ($decodedData['service'] == 'EMP_REG_NA') {
        $registration_no = $decodedData['certificate_no'];
        $deliver_dt = $decodedData['deliver_dt'];
        $renew_dt = $decodedData['renrew_dt'];
        // $pdfPath = base_url($decodedData['pdf_path']);
        $pdfPath = $decodedData['pdf_path'];
        $pdfPath1 = FCPATH . $decodedData['pdf_path'];
    }
} else {
    $pdfPath = ($pdfFile);
    $pdfPath1 = FCPATH . $pdfFile;
}
// $pdfPath = base_url($pdfFile);
// $pdfPath1 = FCPATH . $pdfFile;
$encodedPdf = base64_encode(file_get_contents($pdfPath1));
?>
<!DOCTYPE html>
<html>

<head>
    <title>DSC Sign</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="<?= base_url("assets/digitalSign/resources/js/jquery.js") ?>"></script>
    <script src="<?= base_url("assets/digitalSign/resources/js/bootstrap.min.js") ?>"></script>

    <script src="<?= base_url("assets/digitalSign/resources/js/dsc-signer.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/digitalSign/resources/js/dscapi-conf.js") ?>" type="text/javascript"></script>

    <link type="text/css" rel="stylesheet" href="<?= base_url("assets/digitalSign/resources/css/bootstrap.min.css") ?>">
    <link type="text/css" rel="stylesheet" href="<?= base_url("assets/digitalSign/resources/css/dsc-signer.css") ?>">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div style="width: 100%;  border: 2px double #ccc; margin: 10px auto">
                    <div style=" background: #337ab7; margin: 5px; padding: 5px;">
                        <h2 style="text-align: left; display: inline-block; width: auto; margin: 0px; color: #fff">PDF PREVIEW</h2>
                        <button id="signPdf" class="btn btn-warning pull-right" type="button">SIGN PDF</button>
                        <a id="backtohome" href='<?= base_url('/spservices/upms/myapplications') ?>' type="application/pdf"></a>
                        <a id="downloadDiv" href='#' type="application/pdf" download="SignedPdf.pdf"></a>

                    </div>
                    <embed src="<?= base_url($pdfPath) ?>" type="application/pdf" frameBorder="0" scrolling="auto" height="500" width="100%"></embed>
                </div>
                <div class="well-sm">
                    <form id="pdfForm">
                        <input type="hidden" id="signingReason" name="signingReason" value="" />
                        <input type="hidden" id="signingLocation" name="signingLocation" value="" />
                        <input type="hidden" id="stampingX" name="stampingX" value="<?= $stampingX ?>" />
                        <input type="hidden" id="stampingY" name="stampingY" value="<?= $stampingY ?>" />
                        <input type="hidden" id="tsaURL" name="tsaURL" value="" maxlength="100" style="width: 400px;" />
                        <input type="hidden" id="timeServerURL" name="timeServerURL" value="https://rurban.gov.in/dscapi/getServerTime" maxlength="100" style="width: 400px;" />
                        <!-- <input type="hidden" id="tsaURL" name="tsaURL" value="http://timestamp.digicert.com" /> -->
                        <!-- <input type="hidden" id="timeServerURL" name="timeServerURL" value="https://rurban.gov.in/dscapi/getServerTime" />                          -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="panel-modal"></div>
    <script type="text/javascript">
        $(document).ready(function() {
            var initConfig = {
                "preSignCallback": function() {
                    return true;
                },
                "postSignCallback": function(alias, sign, key) {
                    var requestData = {
                        action: "DECRYPT",
                        en_sig: sign,
                        ek: key
                    };

                    $.ajax({
                        url: dscapibaseurl + "/pdfsignature",
                        type: "post",
                        dataType: "json",
                        contentType: 'application/json',
                        data: JSON.stringify(requestData),
                        async: false,
                        beforeSend: function() {
                            $("#downloadDiv").text("PLEASE WAIT...");
                        },
                        success: function(data) {
                            // console.log(data)
                            if (data.status_cd == 1) {
                                var jsonData = JSON.parse(atob(data.data));
                                alert(jsonData.status);
                                if (jsonData.status === "SUCCESS") {
                                    var pdfData = jsonData.sig;
                                    // spservices/office/dscsign/index
                                    $.post("<?= base_url('spservices/upms/dscsign/pdfsigned') ?>", {
                                        "rtps_trans_id": "<?= $rtps_trans_id ?>",
                                        "pdf_file": "<?= $pdfPath ?>",
                                        "token": "<?= $objId ?>",
                                        "signed_pdf": pdfData,
                                        "registration_no": "<?= $registration_no ?>",
                                        "deliver_dt": "<?= $deliver_dt ?>",
                                        "renew_dt": "<?= $renew_dt ?>",
                                    }, function(res) {
                                        // alert("Res: " + res);
                                    });
                                    $('#downloadDiv').addClass('btn btn-success pull-right');
                                    var dlnk = document.getElementById('downloadDiv');
                                    dlnk.href = 'data:application/pdf;base64,' + pdfData;
                                    $("#downloadDiv").text("DOWNLOAD SIGNED PDF");
                                    $('#backtohome').addClass('btn btn-warning pull-right');
                                    $("#backtohome").text("BACK to DASHBOARD");

                                    $('#signPdf').hide();
                                }
                            } else {
                                if (data.error.error_cd == 1002) {
                                    alert(data.error.message);
                                    return false;
                                } else {
                                    alert("Decryption Failed for Signed PDF File");
                                    return false;
                                }
                            }
                        },
                        error: function() {
                            alert("Something went wrong!");
                        }
                    });
                },
                signType: 'pdf',
                mode: 'nostampingv2'
            };
            dscSigner.configure(initConfig);

            $('#signPdf').click(function() {
                var data = "<?= $encodedPdf ?>";
                if (data != null || data != '') {
                    dscSigner.sign(data);
                }
            });
        });
    </script>
</body>

</html>