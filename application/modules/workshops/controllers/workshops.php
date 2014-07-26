<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Workshops extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('workshops_model');
		$this->load->model('list_course/courses_model');
		
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
        
        $this->template->title('Workshops');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('workshops', $content_data);
    }
    public function index_json($order_by = "workshops.workshop_id", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('workshop_id','title','start_date','time','presenter','workshop_type','venue','attendee_limit');
    	$grid_data = get_search_data($aColumns);
    	
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
		
		/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
    	$data = $this->workshops_model->get_workshop($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->workshops_model->get_workshop($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],true);
    
    	/*
    	 * Output
    	*/
    	$output = array(
    			"sEcho" => intval($_GET['sEcho']),
    			"iTotalRecords" => $count,
    			"iTotalDisplayRecords" => $count,
    			"aaData" => array()
    	);
    
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
			
					
				$row[] = $workshop_id;
				$row[] = $result_row["title"];
    			$row[] = date("D, d M Y",strtotime($result_row["start_date"]));
    			$row[] = $result_row["time"];
				$row[] = ucwords(strtolower($result_row["presenter_name"]));
				$row[] = $result_row["workshop_type"];
				$row[] = $venue;
    			$row[] = $attendee_limit;
				$row[] = $registered;
				$row[] = $spaces;
    			$row[] = $workshop_id;
				$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
    
    public function add($workshop_id = null){
    	$content_data['presenter_list'] = get_other_user_list();
		$content_data['workshop_type_list'] = $this->workshops_model->get_workshop_types();
		$content_data['semester'] = array('0'=>'Select Semester','1'=>'1','2'=>'2');
		$content_data['status'] = array('0'=>'Select Status','1'=>'Active','2'=>'In Active');
		
		$content_data['workshop_id'] = $workshop_id;
    	$rowdata = array();
    	if($workshop_id){
    		$rowdata = $this->workshops_model->get_workshop_data($workshop_id);
    	}	
    	
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$presenter = $this->input->post('presenter');
			$title = $this->input->post('title');
			$attendee_limit = $this->input->post('attendee_limit');
    		$topic = $this->input->post('topic');
    		$venue = $this->input->post('venue');
    		$time = $this->input->post('time');
    		$start_date = date("Y-m-d",strtotime($this->input->post('start_date')));
    		$semester = $this->input->post('semester');
    		$workshop_type_id = $this->input->post('workshop_type_id');
    		$status = $this->input->post('status');
    		$user_id = $this->session->userdata('user_id');
			
			$data = array();
			$data['presenter'] = $presenter;
			$data['user_id'] = $user_id;
			$data['title'] = $title;
			$data['attendee_limit'] = $attendee_limit;
			$data['topic'] = $topic;
			$data['venue'] = $venue;
			$data['time'] = $time;
			$data['start_date'] = $start_date;
			$data['semester'] = $semester;
			$data['workshop_type_id'] = $workshop_type_id;
			$data['status'] = $status;
			$data['presented'] = 2;
			$data['campus'] = '';
			$data['academic_year'] = '';
			
			$data['updated_at'] = date('Y-m-d H:i:s');
			
			$table = 'workshops';
    		$wher_column_name = 'workshop_id';					
    		if($workshop_id){
    			set_activity_data_log($workshop_id,'Update','Workshop','Edit Workshop',$table,$wher_column_name,'');
    			grid_data_updates($data,$table,$wher_column_name,$workshop_id);    			
    		}else{
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Workshop','Add Workshop',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_workshop', $content_data);
    }
	
	public function add_attendee($id = 0,$attendee = 0,$workshop_id = 0){
    	$content_data['attendee_list'] = get_other_user_list();
		$content_data['workshop_list'] = $this->workshops_model->get_workshops();
		$content_data['sel_workshop_id'] = 0;
		if($id){
    		$rowdata = $this->workshops_model->get_attendee_data($id);
			$attendee = $rowdata->attendee;
			$content_data['sel_workshop_id'] = $rowdata->workshop_id;
    	}
		$content_data['id'] = $id;
		$content_data['attendee'] = $attendee;
		$content_data['workshop_id'] = $workshop_id;
    	$rowdata = array();
    	
    	/*$content_data['rowdata'] = $rowdata;*/
    	if($this->input->post()){
    		$attendee = $this->input->post('attendee');
			$workshop_id = $this->input->post('workshop_id');
			
			$error = "";
    		$error_seperator = "<br>";
						
			$data = array();
			$data['attendee'] = $attendee;
			$data['workshop_id'] = $workshop_id;
			$data['semester'] = '';
			$data['academic_year'] = '';
			$data['updated_at'] = date('Y-m-d H:i:s');
			
			$attendee_limit = 0;
			$workshop_data = $this->workshops_model->get_workshop_data($workshop_id);

			if(isset($workshop_data->attendee_limit))
				$attendee_limit = $workshop_data->attendee_limit;
				
			$total_attendee = $this->workshops_model->get_attendee($workshop_id,true);
			
			if($attendee_limit <= $total_attendee) {
				$error .= 'Sorry! This workshop is full.';
				echo $error;
    			exit();
			}
			$validate_unique = $this->workshops_model->checkAttendeeUser($attendee,$workshop_id);
			if($validate_unique == false) {
				$error .= 'This user has already signed up for selected workshop.';
				echo $error;
    			exit();
			}
			$table = 'user_workshop';
    		$wher_column_name = 'user_workshop_id';					
    		if($id){
    			set_activity_data_log($id,'Update','Workshop','Edit attendee',$table,$wher_column_name,'');
    			grid_data_updates($data,$table,$wher_column_name,$id);    			
    		}else{
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Workshop','Add attendee',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_attendee', $content_data);
    }
    
    
	public function delete($id = null){
    	if($id){
			$table = 'workshops';
			$wher_column_name = 'workshop_id';
			set_activity_data_log($id,'Delete','Workshops > List Workshop','List Workshop',$table,$wher_column_name,$user_id='');
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/workshops/');
        exit();
	}
	
	//////////////////////////////////////// Attendees page START 	////////////////////////////////////////
	
	 public function attendees($workshop_id = null) {
	 	if(!$workshop_id){
			redirect('/workshops/');
        	exit();
		}
		$workshop_data = $this->workshops_model->get_workshop_data($workshop_id);
		$registered = $this->workshops_model->get_attendee($workshop_id,true);

    	$content_data = array();
		$content_data['workshop_id'] = $workshop_id;
		$content_data['title'] = $workshop_data->title;
		$content_data['presenter_name'] = $workshop_data->presenter_name;
		$content_data['start_date'] = $workshop_data->start_date;
		$content_data['time'] = $workshop_data->time;
		$content_data['venue'] = $workshop_data->venue;
		$content_data['attendee_limit'] = $workshop_data->attendee_limit;
		$content_data['registered'] = $registered;
		$content_data['workshop_type'] = $workshop_data->workshop_type;
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Workshops attendees');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('attendees', $content_data);
    }
	
    public function get_attendees_json($order_by = "user_workshop.user_workshop_id", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('user_workshop_id','users.first_name','users.elsd_id','users.email','created_at');
    	$grid_data = get_search_data($aColumns);
    	
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
		$workshop_id = $_GET['workshop_id'];
		/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
    	$data = $this->workshops_model->get_workshop_attendees($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],$workshop_id);
    	$count = $this->workshops_model->get_workshop_attendees($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],$workshop_id,true);
    
    	/*
    	 * Output
    	*/
    	$output = array(
    			"sEcho" => intval($_GET['sEcho']),
    			"iTotalRecords" => $count,
    			"iTotalDisplayRecords" => $count,
    			"aaData" => array()
    	);
    
    	if($data){
    		foreach($data->result_array() AS $result_row){
    			$row = array();
				$user_workshop_id = $result_row["user_workshop_id"];
					
				$row[] = $user_workshop_id;
				$row[] = ucwords(strtolower($result_row["attendee_name"]));
				$row[] = $result_row["elsd_id"];
				$row[] = $result_row["email"];
    			$row[] = date("D, d M Y",strtotime($result_row["created_at"]));
    			$row[] = $user_workshop_id;
				$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
	
	public function delete_attendee($id = null){
    	if($id){
			$table = 'user_workshop';
			$wher_column_name = 'user_workshop_id';
			set_activity_data_log($id,'Delete','Workshops > List Workshop','Attendee',$table,$wher_column_name,$user_id='');
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/workshops/');
        exit();
	}
	
	//////////////////////////////////////// Attendees page END ////////////////////////////////////////////////
	
	//////////////////////////////////////// In active page START ////////////////////////////////////////////////
	public function inactive() 
	{
    	$content_data = array();
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Workshops inactive');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('workshops_inactive', $content_data);
    }
    public function workshop_inactive_json($order_by = "workshops.workshop_id", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('workshop_id','title','start_date','time','presenter','workshop_type','venue','attendee_limit');
    	$grid_data = get_search_data($aColumns);
    	
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
		
		/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
    	$data = $this->workshops_model->get_workshop_inactive($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->workshops_model->get_workshop_inactive($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],true);
    
    	/*
    	 * Output
    	*/
    	$output = array(
    			"sEcho" => intval($_GET['sEcho']),
    			"iTotalRecords" => $count,
    			"iTotalDisplayRecords" => $count,
    			"aaData" => array()
    	);
    
    	if($data){
    		foreach($data->result_array() AS $result_row){
    			$row = array();
				$workshop_id = $result_row["workshop_id"];
				$venue = $result_row["venue"];
				$attendee_limit = $result_row["attendee_limit"];
				if( empty($venue ))
        			$venue = 'TBA';
				$registered = $this->workshops_model->get_attendee($workshop_id,true);			
				$spaces = '';	
				if($attendee_limit == $registered){
    				$spaces = ' <small class="label label-important">Full</small>';
    			}			
				$presented = '';
				if($result_row["presented"] == 2 ){
				  $presented = '<i class="fa fa-times"></i>';
				}elseif ($result_row["presented"] == 1 ) {
				  $presented = '<i class="fa fa-check"></i>';
				};
				$row[] = $workshop_id;
				$row[] = $result_row["title"];
    			$row[] = date("D, d M Y",strtotime($result_row["start_date"]));
    			$row[] = $result_row["time"];
				$row[] = ucwords(strtolower($result_row["presenter_name"]));
				$row[] = $result_row["workshop_type"];
				$row[] = $venue;
				$row[] = $registered.$spaces;
				$row[] = $presented;
				$row[] = $result_row["semester"];
    			$row[] = $workshop_id;
				$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
	
	//////////////////////////////////////// In active page END ////////////////////////////////////////////////
	
	//////////////////////////////////////// attended page START 	////////////////////////////////////////
	
	 public function attended($workshop_id = null) {
	 	if(!$workshop_id){
			redirect('/workshops/');
        	exit();
		}
		if(isset($_POST['attendance']) && is_array($_POST['attendance']) && count($_POST['attendance'])){
			$table = 'user_workshop';
			$wher_column_name = 'user_workshop_id';
			foreach($_POST['attendance'] as $user_workshop_id) {	
				$data['attendance'] = 1;
				grid_data_updates($data,$table,$wher_column_name,$user_workshop_id);
			}
		}
		$workshop_data = $this->workshops_model->get_workshop_data($workshop_id);
		$registered = $this->workshops_model->get_attendee($workshop_id,true);

    	$content_data = array();
		$content_data['workshop_id'] = $workshop_id;
		$content_data['title'] = $workshop_data->title;
		$content_data['presenter_name'] = $workshop_data->presenter_name;
		$content_data['start_date'] = $workshop_data->start_date;
		$content_data['time'] = $workshop_data->time;
		$content_data['venue'] = $workshop_data->venue;
		$content_data['attendee_limit'] = $workshop_data->attendee_limit;
		$content_data['registered'] = $registered;
		$content_data['workshop_type'] = $workshop_data->workshop_type;
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Workshops attended');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('attended', $content_data);
    }
	
    public function get_attended_json($order_by = "user_workshop.user_workshop_id", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('user_workshop_id','users.first_name','users.elsd_id','users.email','created_at');
    	$grid_data = get_search_data($aColumns);
    	
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
		$workshop_id = $_GET['workshop_id'];
		/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
    	$data = $this->workshops_model->get_workshop_attendees($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],$workshop_id);
    	$count = $this->workshops_model->get_workshop_attendees($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],$workshop_id,true);
    
    	/*
    	 * Output
    	*/
    	$output = array(
    			"sEcho" => intval($_GET['sEcho']),
    			"iTotalRecords" => $count,
    			"iTotalDisplayRecords" => $count,
    			"aaData" => array()
    	);
    
    	if($data){
    		foreach($data->result_array() AS $result_row){
    			$row = array();
				$user_workshop_id = $result_row["user_workshop_id"];
				$attendance = $result_row["attendance"];
				
				$actions = '';
				//if($attendance == 0 && $attendees[0]->presented != 1)	{
					//$actions .= '<a href="'.base_url().'workshops/add_attendee/'.$user_workshop_id.'" data-target="#myModal" data-toggle="modal" class="modal-link">Change</a>';
				//}
				if( $attendance == 1 ){
					$actions .= ' <a href="'.base_url().'workshops/absent/'.$user_workshop_id.'/'.$workshop_id.'"><i class="fa fa-times"></i></a> <em>(mark absent)</em>';
				}elseif( $attendance == 0 ) {
					$actions .= ' <a href="'.base_url().'workshops/delete_attendee/'.$user_workshop_id.'"><i class="fa fa-trash-o"></i></a> <em>(remove)</em>';
				}
				$_attendance = '';
				if( $attendance == 0 )
		   			$_attendance = '<input name="attendance[]" type="checkbox" value="'.$user_workshop_id.'"/>';
				else
					$_attendance = '<i class="fa fa-check"></i>';
										
				$row[] = $_attendance;
				$row[] = $user_workshop_id;
				$row[] = ucwords(strtolower($result_row["attendee_name"]));
				$row[] = $result_row["elsd_id"];
				$row[] = $result_row["email"];
    			$row[] = $actions;
				$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
	
	public function absent($id = null,$workshop_id){
    	if($id){
			$table = 'user_workshop';
			$wher_column_name = 'user_workshop_id';
			$data['attendance'] = 0;
			grid_data_updates($data,$table,$wher_column_name,$id);
    	}
		if($workshop_id)
			redirect('/workshops/attended/'.$workshop_id);
		else
			redirect('/workshops/');	
        exit();
	}
	//////////////////////////////////////// attended page END ////////////////////////////////////////////////+


    //////////////////////////////////////// sign_up_sheet page START   ////////////////////////////////////////
    
     public function sign_up_sheet($workshop_id = null) {
        if(!$workshop_id){
            redirect('/workshops/');
            exit();
        }
        $workshop_data = $this->workshops_model->get_workshop_data($workshop_id);
        $registered = $this->workshops_model->get_attendee($workshop_id,true);

        $content_data = array();
        $content_data['workshop_id'] = $workshop_id;
        $content_data['title'] = $workshop_data->title;
        $content_data['presenter_name'] = $workshop_data->presenter_name;
        $content_data['start_date'] = $workshop_data->start_date;
        $content_data['time'] = $workshop_data->time;
        $content_data['venue'] = $workshop_data->venue;
        $content_data['attendee_limit'] = $workshop_data->attendee_limit;
        $content_data['registered'] = $registered;
        $content_data['workshop_type'] = $workshop_data->workshop_type;
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Workshops attendees');
        $this->template->set_partial('header', 'header');
        $this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('sign_up_sheet', $content_data);
    }
    
    public function get_sign_up_sheet_json($order_by = "user_workshop.user_workshop_id", $sort_order = "asc", $search = "all", $offset = 0) {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
        */
        $aColumns = array('user_workshop_id','users.first_name','users.elsd_id');
        $grid_data = get_search_data($aColumns);
        
        $sort_order = $grid_data['sort_order'];
        $order_by = $grid_data['order_by'];
        $workshop_id = $_GET['workshop_id'];
        /*
         * Paging
        */
        $per_page =  $grid_data['per_page'];
        $offset =  $grid_data['offset'];
        
        $data = $this->workshops_model->get_workshop_attendees($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],$workshop_id);
        $count = $this->workshops_model->get_workshop_attendees($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],$workshop_id,true);
    
        /*
         * Output
        */
        $output = array(
                "sEcho" => intval($_GET['sEcho']),
                "iTotalRecords" => $count,
                "iTotalDisplayRecords" => $count,
                "aaData" => array()
        );
    
        if($data){
            foreach($data->result_array() AS $result_row){
                $row = array();
                $user_workshop_id = $result_row["user_workshop_id"];
                    
                $row[] = $user_workshop_id;
                $row[] = ucwords(strtolower($result_row["attendee_name"]));
                $row[] = $result_row["elsd_id"];
                $row[] = '';
                $output['aaData'][] = $row;
            }
        }
    
        echo json_encode( $output );
    }
    
    
    //////////////////////////////////////// sign_up_sheet page END ////////////////////////////////////////////////
	
}
/* End of file workshops.php */