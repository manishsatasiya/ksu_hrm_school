<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Schedule extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('schedule_model');
    }
	
    public function index() {
		$user_id = $this->session->userdata('user_id');	
		
    	$content_data = array();
		$next_appointments = $this->schedule_model->next_appointments($user_id);
		$user_workshops = $this->schedule_model->get_user_workshops($user_id);
		
		$content_data['next_appointments'] = $next_appointments;
		$content_data['user_workshops'] = $user_workshops;
		
		// set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Schedule');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('schedule', $content_data);
    }
	
    public function index_json() {
		$user_id = $this->session->userdata('user_id');	
		
		$holidays = $this->schedule_model->get_holidays();

		$holidays_end = $this->schedule_model->get_holidays_end();

		$workshops = $this->schedule_model->get_workshops($user_id);

		//$observations = DB::table('observations')->where('user_id','=',$id)->where('date','>',$academic_start_date)->get(array('observation_type as title', 'date as event_day','observer'));

		$visa = $this->schedule_model->get_visa($user_id);

		$passport = $this->schedule_model->get_passport($user_id);

		$appointments = $this->schedule_model->get_appointments($user_id);
		
		//$user_workshops = Userworkshop::query()->where('user_workshop.user_id', '=',Auth::user()->id)->left_join('workshops','workshop_id', '=','workshops.id')->where('workshops.start_date', '>=',date('Y-m-d'))->where('workshops.presented', '=',2)->order_by('workshops.start_date', 'asc')->get(array('user_workshop.id as delete_id','workshops.title','workshops.start_date','workshops.time','workshops.venue','workshops.required'));
		
    	$data = array_merge($workshops,$visa,$passport,$holidays,$holidays_end,$appointments);		
		$today = date("Y-m-d");
    	$output = array();
		if(is_array($data) && count($data)){
    		foreach($data AS $result_row){
    			$row = array();
				$id = $result_row["id"];
				$title = $result_row["title"];
				$event_day = $result_row["event_day"];
				$time = isset($result_row["time"]) ? $result_row["time"] : '';
				$type = isset($result_row["type"]) ? $result_row["type"] : '';
				$venue = isset($result_row["venue"]) ? $result_row["venue"] : '';
				$days = isset($result_row["days"]) ? $result_row["days"] : '';
				$color = '';
				$_tital = '';
				
                if($title == 'residency card' || $title == 'visit visa' || $title == 'business visa' || $title == 'other visa' ) {

					if( $title == 'residency card') {
							$_tital.= 'IMPORTANT!<br>Iqaamah expiry';
							$color = 'red';
							
					} else if( $title == 'visit visa' || $title == 'business visa' || $title == 'other visa') {
							$_tital.= 'IMPORTANT!<br>Visa expiry';
							$color = 'red';
					}

				} else {
                                
					if( $title == 'full' ) {
						//$calendar.= '<ul class="cal-events"><li class="important">'.$value->title.'  observation, by: ' . $value->observer . '</li></ul>';
						$_tital.= ''.$title.'  observation';						
					} elseif( $title == 'passport' ) {
						$_tital.= 'IMPORTANT!<br>Passport expiry';
						$color = 'red';
					}  elseif( $title && isset($time) && !empty($venue)) {
						$_tital.= 'Workshop: ' .$title. ': ' .$time  .',  '.$venue  . '';
					} 	
					elseif( $title && isset($time) && isset($type) && $type == 'work') {
	
							//$calendar.= '<ul class="cal-events"><li class="appointments_work"><a href="'.URL::base().'/shared/edit_appointment/'.$value->id. '" class="float-left icon-gear with-tooltip icon-white modal_link" title="Edit appointment">'.ucwords($value->title).'</a><br> at ' .$value->time.'<a href="'.URL::base().'/shared/delete_appointment/'.$value->id. '" class="float-right clear-both icon-trash with-tooltip icon-white confirm" title="Delete appointment"></a><br></li></ul>';
							$_tital.= ''.ucwords($title).'<br> at ' .$time.'';				
					} elseif( $title && isset($time) && isset($type) && $type == 'private') {
	
							//$calendar.= '<ul class="cal-events"><li class="appointments_private"><a href="'.URL::base().'/shared/edit_appointment/'.$value->id. '" class="float-left icon-gear with-tooltip icon-white modal_link" title="Edit appointment">'.ucwords($value->title).'</a><br> at ' .$value->time.'<a href="'.URL::base().'/shared/delete_appointment/'.$value->id. '" class="float-right clear-both icon-trash with-tooltip icon-white confirm" title="Delete appointment"></a><br></li></ul>';
							$_tital.= ''.ucwords($title).'<br> at ' .$time.'';
					}
	
	
					elseif( $title == 'buzz') {
						$_tital.= '' .$value->title. ' observation';
					} elseif( !empty($id) ) {
						$_tital.= '' .$title. '<br>Holiday Begins.';
					} elseif( !empty($days) ) {
						$_tital.= '' .$title. '<br>Holiday Ends.';
					} else {
						 $_tital.= $title;
					}
	
				}
				
				$row['title'] = $_tital;
				$row['start'] = $event_day;
    			$row['color'] = $color;
				$output[] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
    
	public function add_appointment($id = null){
    	$user_id = $this->session->userdata('user_id');	
		
		$content_data['id'] = $id;
    	$rowdata = array();
    	/*if($id){
    		$rowdata = $this->list_user_model->get_emergency_contact_data($id);
    	}*/
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		
			$date = $this->input->post('date');
    		$time = $this->input->post('time');
    		$type = $this->input->post('type');
    		$details = $this->input->post('details');
			    		
			$data = array();
			$data['user_id'] = $user_id;
			$data['date'] = date('Y-m-d',strtotime($date));
			$data['time'] = $time;
			$data['type'] = $type;
			$data['details'] = $details;
							
			$error = "";
    		$error_seperator = "<br>";
			$table = 'appointments';
    		$wher_column_name = 'appointment_id';
    		/*if($id){
    			$data['updated_at'] = date('Y-m-d H:i:s');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{*/
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    		//}
    		exit;
    	}
    	$this->template->build('add_appointment', $content_data);
    }
	
	public function delete($id = null){
    	if($id){
			$table = 'contractors';
			$wher_column_name = 'id';
			set_activity_data_log($id,'Delete','Contractors > List Contractors','List Contractors',$table,$wher_column_name,$user_id='');
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/contractors/');
        exit();
	}
}
/* End of file contractors.php */