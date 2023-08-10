<html>

<head>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script>
        $(function() {
            $('form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'post',
                    dataType: "json",
                    url: '<?= base_url('digilocker/panverify/verifyPan') ?>',
                    data: $('form').serialize(),
                    success: function(res) {
                        alert(JSON.stringify(res))
                    }
                });

            });

        });
    </script>
</head>

<body>
    <form>
    <p>PAN number: <br>
            <input type="text" name="pan" class="form-control">
        </p>
        <p>First Name: <br>
            <input type="text" name="fname" class="form-control">
        </p>
        <p>Middle Name: <br>
            <input type="text" name="mname" class="form-control">
        </p>
        <p>Last Name: <br>
            <input type="text" name="lname" class="form-control">
        </p>
        <p>Type <br>
            <select name="type" id="type" class="form-control">
                <option value="IND">IND</option>
                <option value="ORG">ORG</option>
            </select>
        </p>
        <p><input name="submit" type="submit" value="Submit"></p>
    </form>
</body>

</html>