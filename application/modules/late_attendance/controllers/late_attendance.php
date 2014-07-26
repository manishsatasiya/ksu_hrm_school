<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Late_attendance extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('attendance/attendance_model');
		$this->load->model('list_course_class/list_student_class_model');
		$this->load->helper('general_function');
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

    public function index($order_by = "course_id", $sort_order = "asc", $search = "all", $offset = 0) {
		
		$content_data = array();
		// set layout data
		$this->template->set_theme(Settings_model::$db_config['default_theme']);
		$this->template->set_layout('school');
		
		$this->template->title('Late Attendance');
		$this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
		$this->template->set_partial('footer', 'footer');
		$this->template->build('late_attendance', $content_data);
		
		$this->synclateattendance();
    }
	
    function synclateattendance()
    {
		$this->attendance_model->truncate_late_attendance();
		
		$enable_week = $this->attendance_model->get_late_attendance_week();
		
		$search_data = array();
		$limit = $this->list_student_class_model->count_all_course_class($where=array(),$search_data);
		
		if($limit > 0)
		{
			$data = $this->list_student_class_model->get_course_class($limit, $offset=0, $order_by="course_class_id", $sort_order="", $search_data,$where=array());	
			if(!empty($data)) 
			{
				foreach($enable_week as $enable_wek)
				{
					foreach($data->result() as $courseclasses)
					{
						$course_class_id = $courseclasses->course_class_id;
				    	$course_id = $courseclasses->course_id;
				    	$category_id = $courseclasses->category_id;
				    	$school_year_id = $courseclasses->school_year_id;
				    	$school_id = $courseclasses->school_id;
				    	$primary_teacher_id = $courseclasses->primary_teacher_id;
				    	$secondary_teacher_id = $courseclasses->secondary_teacher_id;
				    	$class_room_id = $courseclasses->class_room_id;
				    	$section_id = $courseclasses->section_id;
				    	$shift = $courseclasses->shift;
				    	$attendence_week = $enable_wek->week_id;
				    	$attendence_am_last_date = $enable_wek->am_shift;
				    	$attendence_pm_last_date = $enable_wek->pm_shift;
				    	
						$this->attendance_model->add_late_attendance($course_class_id,
											    	$course_id,
											    	$category_id,
											    	$school_year_id,
											    	$school_id,
											    	$primary_teacher_id,
											    	$secondary_teacher_id,
											    	$class_room_id,
											    	$section_id,
											    	$shift,
											    	$attendence_week,
											    	$attendence_am_last_date,
											    	$attendence_pm_last_date
			    									); 
					}
				}
			}
		}
		
		$this->attendance_model->update_show_flag_late_attendance(); 
		$data_show_flag_late_attendance = $this->attendance_model->get_show_flag_late_attendance_data(); 
		
		if(!empty($data_show_flag_late_attendance)) 
		{
			foreach($data_show_flag_late_attendance->result() as $late_attendance)
			{
				$late_attendance_id = $late_attendance->id;
				$course_class_id = $late_attendance->course_class_id;
		    	$attendence_week = $late_attendance->attendence_week;
		    	$attendence_last_date = "";
		    	
		    	if($late_attendance->shift == "AM")
		    		$attendence_last_date = $late_attendance->attendence_am_last_date;
		    	if($late_attendance->shift == "PM")
		    		$attendence_last_date = $late_attendance->attendence_pm_last_date;
		    		
		    	$data_check_attendance_report = $this->attendance_model->check_attendance_report_for_late_attendance($course_class_id,$attendence_week);
		    
		    	if(isset($data_check_attendance_report[0]["cnt"]) && $data_check_attendance_report[0]["created_date"])
		    	{		
		    		if($data_check_attendance_report[0]["cnt"] == 0)
		    		{
		    			$this->attendance_model->update_active_flag_late_attendance($late_attendance_id,"");
		    		}
		    		else if($data_check_attendance_report[0]["cnt"] > 0 && $data_check_attendance_report[0]["created_date"] > $attendence_last_date)
		    		{
		    			$attendence_submitted_date = $data_check_attendance_report[0]["created_date"];
		    			$this->attendance_model->update_active_flag_late_attendance($late_attendance_id,$attendence_submitted_date);	
		    		}
		    	}
		    	else if(empty($data_check_attendance_report))
	    		{
	    			$this->attendance_model->update_active_flag_late_attendance($late_attendance_id,"");
	    		}
			}
		}
    }
    
	public function getdata($csrf_token_name = "", $csrf_token_value = "") 
	{
		$aColumns = array('elsd_id','first_name','section_title','class_room_title','campus','course_title','attendence_week','shift','attendance_last_date','attendence_submitted_date');
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
		$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
		$count = $config['total_rows'] = $this->attendance_model->count_all_late_attendance_class($grid_data['search_data']);
		$data = $this->attendance_model->get_late_attendance_course($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
        
		/*
    	 * Output
    	*/
    	$output = array(
    			"sEcho" => intval($_GET['sEcho']),
    			"iTotalRecords" => $count,
    			"iTotalDisplayRecords" => $count,
    			"aaData" => array()
    	);
		
		if(!empty($data)) 
		{
			$i=1;
			foreach($data->result() as $courseclasses)
			{
				$elsd_id = $courseclasses->elsd_id;
				$course_title = $courseclasses->course_title;
				$school_year_title = $courseclasses->school_year_title;
				$attendence_week = $courseclasses->attendence_week;
				$section_title = $courseclasses->section_title;
				$class_room_title = $courseclasses->class_room_title;
				$first_name = $courseclasses->first_name;
				$campus = $courseclasses->campus;
				$shift = $courseclasses->shift;
				$attendance_last_date = "";
				
				if($shift == "AM")
					$attendance_last_date = $courseclasses->attendence_am_last_date;
				else if($shift == "PM")
					$attendance_last_date = $courseclasses->attendence_pm_last_date;
						
				$attendence_submitted_date = $courseclasses->attendence_submitted_date;

				$entry = array(
						$elsd_id,
						$first_name,
						$section_title,
						$campus,
						$course_title,
						$class_room_title,
						$attendence_week,
						$shift,
						date('d-M-Y h:i:s',strtotime($attendance_last_date)),
						date('d-M-Y h:i:s',strtotime($attendence_submitted_date))
				);
				$output['aaData'][] = $entry;
				$i++;
			}
		}
		
        echo json_encode($output);
	}
	
	
	public function export_to_excel()
	{
		ini_set('memory_limit','1024M');
		$page = isset($_POST['page']) ? $_POST['page'] : 1;
		$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
		$order_by = 'section_title ASC,attendence_submitted_date DESC';
		$sort_order = '';
		$query = isset($_POST['q']) ? $_POST['q'] : false;
		$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;

		if(isset($_POST['letter_pressed']) && $_POST['letter_pressed']!=''){
			$qtype = "first_name";
			$query = $_POST['letter_pressed'];	
		}
		
		$offset = '';
		$per_page = '';

		$search_data = array();
		if ($query) $search_data[$qtype] = $query;
		
		$total = $config['total_rows'] = $this->attendance_model->count_all_late_attendance_class($search_data);
		$data = $this->attendance_model->get_late_attendance_course($per_page, $offset, $order_by, $sort_order, $search_data);
        
		$jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
		
		if(!empty($data)) 
		{
			$i=1;
			foreach($data->result() as $courseclasses)
			{
				$elsd_id = $courseclasses->elsd_id;
				$course_title = $courseclasses->course_title;
				$school_year_title = $courseclasses->school_year_title;
				$attendence_week = $courseclasses->attendence_week;
				$section_title = $courseclasses->section_title;
				$class_room_title = $courseclasses->class_room_title;
				$first_name = $courseclasses->first_name;
				$campus = $courseclasses->campus;
				$shift = $courseclasses->shift;
				$attendance_last_date = "";
				
				if($shift == "AM")
					$attendance_last_date = $courseclasses->attendence_am_last_date;
				else if($shift == "PM")
					$attendance_last_date = $courseclasses->attendence_pm_last_date;
						
				$attendence_submitted_date = $courseclasses->attendence_submitted_date;

				$entry = array(
						'elsd_id'=>$elsd_id,
						'first_name'=>$first_name,
						'section_title'=>$section_title,
						'campus'=>$campus,
						'course_title'=>$course_title,
						'class_room_title'=>$class_room_title,
						'attendence_week'=>$attendence_week,
						'shift'=>$shift,
						'attendance_last_date'=>date('d-M-Y h:i:s',strtotime($attendance_last_date)),
						'attendence_submitted_date'=>date('d-M-Y h:i:s',strtotime($attendence_submitted_date))
						);
				$jsonData['rows'][] = $entry;
				$i++;
			}
		}
		
		$content_data['export_data'] = $jsonData;
		$this->template->build('late_attendance_excel', $content_data);
		
	}
	
}
/* End of file list_members.php */