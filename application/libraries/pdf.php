<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class pdf {
 
    function pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
 
    function load($param=NULL)
    {
        include_once APPPATH.'/third_party/mpdf/mpdf.php';
 
        if ($params == NULL)
        {
            //$param = '"en-GB-x","A4","","",10,10,10,10,6,3,"L"';
            $param = '"c","A4","9","",10,10,3,8,6,3,"L"';
        }
		
        return new mPDF("c","A4","9","serif",10,10,3,8,6,3,"L");
    }
}
