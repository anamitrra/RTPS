<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Login (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Prasenjit Das
 * @version : 1.1
 * @since : 08 may 2020
 */
class Pagenotfound extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->load->view('404');
    }
    
    /**
     * This function used to check the user is logged in or not
     */

}

?>