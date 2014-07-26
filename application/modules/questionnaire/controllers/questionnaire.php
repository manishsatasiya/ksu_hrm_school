<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Questionnaire extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('questionnaire_model');
		//$this->load->model('list_course/courses_model');
    }
    
	public function index() {
    	$content_data = array();
		$user_id = $this->session->userdata('user_id');   
		if($this->input->post()){
			$academic_returning = $this->input->post('academic_returning');
			$holiday_pref = $this->input->post('holiday_pref');
			
			$data['academic_returning'] = $academic_returning;
			$data['holiday_pref'] = $holiday_pref;
			
			$table = 'user_profile';
    		$wher_column_name = 'user_id';			
			grid_data_updates($data,$table,$wher_column_name,$user_id);
		}	
		$check_submitted = $this->questionnaire_model->check_submitted($user_id);
		$submitted = ( $check_submitted->academic_returning == 0  ?  2  : 1  );

		$content_data['submitted'] = $submitted;

        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Questionnaire');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('questionnaire', $content_data);
    }
	
   
}
/* End of file documents.php */