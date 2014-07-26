<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends MY_Controller {


    public function __construct()
    {
        parent::__construct();
        set_activity_log(0,'Logout','Logout','Logout');
        $this->session->sess_destroy();
        setcookie("unique_token", null, time() - 60*60*24*3, '/', $_SERVER['SERVER_NAME'], false, false);
        redirect('/auth/login');
    }
}

/* End of file logout.php */