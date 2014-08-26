<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Workshops_staff extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('workshops_staff_model');
		$this->load->model('workshops/workshops_model');
		
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
    public function index() {
    	$content_data = array();
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
		
		$data = $this->workshops_staff_model->get_workshop();
		$output = array();
		if($data){
    		foreach($data->result_array() AS $result_row){
    			$row = array();
				$workshop_id = $result_row["workshop_id"];
				$venue = $result_row["venue"];
				$attendee_limit = $result_row["attendee_limit"];
				if(empty($venue))
        			$venue = 'TBA';
				$registered = $this->workshops_model->get_attendee($workshop_id,true);			
				$spaces = 0;	
				if($attendee_limit <= $registered){
    				$spaces = 0;
    			}elseif ($attendee_limit >= $registered){
    				$spaces = $attendee_limit - $registered;
    			}
			
					
				$row['workshop_id'] = $workshop_id;
				$row['title'] = $result_row["title"];
    			$row['start_date'] = date("D, d M Y",strtotime($result_row["start_date"]));
    			$row['time'] = $result_row["time"];
				$row['presenter_name'] = ucwords(strtolower($result_row["presenter_name"]));
				$row['workshop_type'] = $result_row["workshop_type"];
				$row['venue'] = $venue;
    			$row['attendee_limit'] = $attendee_limit;
				$row['registered'] = $registered;
				$row['spaces'] = $spaces;

				$output[] = $row;
    		}
    	}
		
		$content_data['workshops'] = $output;
        
        $this->template->title('Workshops staff');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('workshops_staff', $content_data);
    }
   
	public function signup($workshop_id=0)
	{
		$attendee = $this->session->userdata('user_id');
		if($workshop_id == 0 || $attendee == 0)
		{
			redirect('/workshops_staff');
		}
		
		$message = "";
		
		$attendee_limit = 0;
		$workshop_data = $this->workshops_model->get_workshop_data($workshop_id);

		if(isset($workshop_data->attendee_limit))
			$attendee_limit = $workshop_data->attendee_limit;
			
		$total_attendee = $this->workshops_model->get_attendee($workshop_id,true);
		
		if($attendee_limit <= $total_attendee) {
			$message .= 'Sorry! This workshop is full.';
		}
		$validate_unique = $this->workshops_model->checkAttendeeUser($attendee,$workshop_id);
		if($validate_unique == false) {
			$message .= 'You has already signed up for selected workshop.';
		}
		
		$message = trim($message);
		
		if($message == "")
		{
			$table = 'user_workshop';
			$wher_column_name = 'user_workshop_id';					
			
			$data = array();
			$data['attendee'] = $attendee;
			$data['workshop_id'] = $workshop_id;
			$data['semester'] = '';
			$data['academic_year'] = '';	
			$data['created_at'] = date('Y-m-d H:i:s');
			$lastinsertid = grid_add_data($data,$table);
			
			if($lastinsertid > 0)
			{
			    $title = "";
				if(isset($workshop_data->title))
					$title = $workshop_data->title;
					
				$message .= 'You are successfully signed up for workshop: <b>'.$title.'</b>';
			}
			set_activity_data_log($lastinsertid,'Add','Workshop','Add attendee',$table,$wher_column_name,$user_id='');
		}
		
		$content_data = array();
		$this->session->set_flashdata('message', $message);
    	redirect('/workshops_staff');
	}
}