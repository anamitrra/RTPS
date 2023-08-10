<?php
class Redirection
{
    function checkUri()
    {
        $ci = &get_instance();
        echo $currentUrl = current_url();
        echo '<br>';
        if (strpos($currentUrl, 'commonapplication') !== false) {
            // need to redirect epramaan and do some checking 
            $common_application = true;
        } else {
            // no need to any check, we can redirect directly
            $common_application = false;
        }
        var_dump($common_application);
        // die();
        $host_list = ['rtps.assam.gov.in', 'rtps.assam.statedatacenter.in', 'sewasetu.assam.gov.in'];
        if ($common_application) {
            $token = "service/";
            $result = "";
            $index = strpos($currentUrl, $token);
            if ($index !== false) {
                $result = substr($currentUrl, $index + strlen($token));
            }
            $url = $this->base64url_decode($result);
            // echo '<br>';

            // Host
            $parsed_url = (parse_url($url));

            if (in_array($parsed_url['host'], $host_list)) {
                if (strpos($url, 'directApply.do?serviceId=0000') !== false) {
                    $host = base_url();  // sewasetu, rtps
                    $url = $host . 'services/loginWindow.do?servApply=N&&lt;csrf:token%20uri=%27loginWindow.do%27/&gt;';
                }
                echo 'epramn login';
                echo '<br>';
                redirect('iservices/login');

                // echo $url;
            } else {
                echo 'no need to login. redirect externally';
                echo '<br>';
                redirect('iservices/login');

                // redirect($url);
                // echo $url;
            }
            echo $url;
            // redirect($url);

            // die();
            // if (strpos($url, 'rtps/spservices') !== false) {
            //     $redirectTortps = true;
            //     echo 'spservices';
            //     echo '<br>';

            //     // $this->redirectTo($url);
            //     redirect('iservices/login');
            // } else if (strpos($url, 'rtps.assam') !== false) {
            //     $redirectTortps = true;
            //     echo 'spservices';
            //     echo '<br>';

            //     // $this->redirectTo($url);
            //     // redirect($url);
            //     redirect('iservices/login');

            // } else {
            //     $redirectTortps = false;
            //     redirect($url);
            //     echo 'other, eodb,';

            //     // $this->redirectTo($url);
            //     echo '<br>';
            // }
        } else {
            echo 'not from commonapplication';
            echo '<br>';
            echo $currentUrl;
            redirect('spservices/common_application/CommonApplication/index');
            // redirect($currentUrl);
            // $this->redirectTo($currentUrl);
        }
        // var_dump($redirectTortps);
        // die();
    }

    function base64url_decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    function redirectTo($url)
    {
        echo $url;
        // redirect($url);
        // exit();
    }
}
