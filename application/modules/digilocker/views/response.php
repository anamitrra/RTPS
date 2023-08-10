<!DOCTYPE html>
<html lang="en">
<head>
    <title>Digilocker login response</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
    <?php
    if ($status === true) {
        if ($state == 'consent') {
            if (!empty($this->session->userdata('redirectTo'))) {
                redirect($this->session->userdata('redirectTo'));
                $this->session->unset_userdata('redirectTo');
            } else {
                redirect(base_url('iservices/transactions'));
            }
    ?>
    <?php
        } else {
            if (!empty($this->session->userdata('enclosure_redirection_path'))) {
                redirect($this->session->userdata('enclosure_redirection_path'));
                $this->session->unset_userdata('enclosure_redirection_path');
            } else {
                echo 'Something went wrong. Please try after sometime.';
            }
            // echo "<script>
            // window.opener.updateLoginBtn('digilogin_btn');
            // window.close();
            // </script>";
        }
    } else {
        echo 'Unauthorized access.';
        // echo "<script>
        // window.opener.updateLoginBtn('digilogin_btn');
        // window.close();
        // </script>";
        // pre($this->session->userdata());
    }
    ?>
</body>
</html>