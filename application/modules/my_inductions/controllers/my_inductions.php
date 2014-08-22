<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class My_inductions extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('my_inductions_model');
    }
    /**
     *
     * index
     *
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     * @param int $offset the offset to be used for selecting data
     *
     */
    public function index($user_unique_id=0) {
    	$content_data = array();
		
		$data = $this->my_inductions_model->get_my_inductions($user_unique_id);
    
    	if($data){
    		foreach($data->result_array() AS $result_row){
				$content_data["rowdata"] = array("Curriculum_Framework" => $result_row["cf"],                                                          
													"Oxford_iTools_Smart_Board" => $result_row["oi"],
													"Educational_Technology" => $result_row["et"],                                                
													"The_Saudi_Learner" => $result_row["sl"],
													"Professional_Development" => $result_row["pd"],                                                                
													"Classroom_Management" => $result_row["cm"],
													"Students_Affairs" => $result_row["sa"],                                                             
													"Lesson_Planning" => $result_row["lp"],
													"Academic_Administration_Quality" => $result_row["aaq"],
													"New_ELSD_Portal_Training" => $result_row["ep"],
													"Academic_HR" => $result_row["ahr"],                                                
													"New_Headway_Plus" => $result_row["hp"],
													"Assessment" => $result_row["as"],                                                                                 
													"Headway_Academic_Skills" => $result_row["ha"],
													"Management_Information" => $result_row["mi"],                                              
													"Qskills_Orientation" => $result_row["qs"]
												);
    		}
    	}
    
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('My Induction');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('my_inductions', $content_data);
    }
}
/* End of file department.php */