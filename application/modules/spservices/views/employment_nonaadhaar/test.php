<html>

<head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/redmond/jquery-ui.css">
    <script type="text/javascript">
        var unavailableDates = ["9-5-2023", "14-5-2023", "15-5-2023"];
        function unavailable(date) {
            dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
            if ($.inArray(dmy, unavailableDates) == -1) {
                return [true, ""];
            } else {
                return [false, "", "Unavailable"];
            }
        }

        $(function() {
            $("#iDate").datepicker({
                dateFormat: 'dd MM yy',
                beforeShowDay: unavailable
            });

        });
    </script>
</head>

<body>
    <input type="text" id="iDate">



</body>

</html>