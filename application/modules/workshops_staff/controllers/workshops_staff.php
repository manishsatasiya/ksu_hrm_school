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
				if( empty($venue ))
        			$venue = 'TBA';
				$registered = $this->workshops_model->get_attendee($workshop_id,true);			
				$spaces = 0;	
				if($attendee_limit <= $registered){
    				$spaces = '<small class="label label-important">Full</small>';
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
   
}