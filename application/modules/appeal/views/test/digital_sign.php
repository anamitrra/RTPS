<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <title>Sign PDF Demo - Wet ink signature stamping</title>
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
    <h1>Sign Pdf Demo - Wet ink signature stamping</h1>
    <div class="row">
        <div class="col-sm-12">
            <div class="well-sm">
                <form method="POST" id="pdfForm" action="<?=base_url('appeal/test_app/digitalsign1')?>">
                    <label for="data">Choose Local File : </label><br /> 
                    <input type="file" name="pdfFile" id="pdfFile" accept="application/pdf" />
                    <label for="pdfData">Pdf Data(Base64):</label> <br />
                    <textarea name="b64_data" placeholder="Choose pdf file above to show pdf data..." id="pdfData" cols="60" rows="8" readonly="readonly"></textarea>
                    <br />
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <div id="panel"></div>
    <script type="text/javascript">
        $(document).ready(function() {
            function readPdfURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var data = e.target.result;
                        var base64 = data
                            .replace(/^[^,]*,/, '');
                        $("#pdfData").val(base64);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#pdfFile").change(function() {
                readPdfURL(this);
            });

        });
    </script>
</body>

</html>