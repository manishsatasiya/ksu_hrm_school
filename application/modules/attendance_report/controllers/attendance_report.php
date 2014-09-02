<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance_report extends Private_Controller {

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
		
		$arrActivateTime = $this->attendance_model->get_activation_time(); 
		$activate_time = "00:00:00";
		
		if(isset($arrActivateTime[0]["activation_time"]))
			$activate_time = $arrActivateTime[0]["activation_time"];
			
		$content_data["enable_week"] = $this->attendance_model->get_enableweek(1,$activate_time);
		
		$this->template->title('Attendance Report');
		$this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
		$this->template->set_partial('footer', 'footer');
		$this->template->build('attendance_report', $content_data);
    }
	
	public function getdata($csrf_token_name = "", $csrf_token_value = "") 
	{		
		$aColumns = array('student_uni_id','student_name','stu_schedule_date','section_title','class_room_title','course_title','shift','track','campus','teacher_name','attendance_perc','absent_hour');
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
		$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
		
		$count = $config['total_rows'] = $this->attendance_model->count_all_late_attendance_report($grid_data['search_data']);
		$data = $this->attendance_model->get_late_attendance_report($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
		
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
			$arrActivateTime = $this->attendance_model->get_activation_time(); 
			$activate_time = "00:00:00";
			
			if(isset($arrActivateTime[0]["activation_time"]))
				$activate_time = $arrActivateTime[0]["activation_time"];
				
			$enable_week = $this->attendance_model->get_enableweek(1,$activate_time);
			
			$cell_week = array();
			foreach ($enable_week as $enable_wek){
				$cell_week[$enable_wek->week_id] = 0;
			}
				
			$i=1;
			foreach($data->result() as $courseclasses)
			{
				
				$course_title = $courseclasses->course_title;
				$section_title = $courseclasses->section_title;
				$class_room_title = $courseclasses->class_room_title;
				$student_name = $courseclasses->student_name;
				$stu_schedule_date = $courseclasses->stu_schedule_date;
				$teacher_name = $courseclasses->teacher_name;
				$shift = $courseclasses->shift;
				$track = $courseclasses->track;
				$campus = $courseclasses->campus;
				$student_uni_id = $courseclasses->student_uni_id;
				$absent_hour = $courseclasses->total_absent_hour;
				$all_weeks = explode(",",$courseclasses->all_weeks);
				$all_hours = explode(",",$courseclasses->all_hours);
				$attendance_perc = 0;
				if($absent_hour > 0 && $courseclasses->max_hours > 0)
					$attendance_perc = round((($absent_hour * 100)/$courseclasses->max_hours),2);
				
				$cell = 
				array(
						$student_uni_id,
						$student_name,
						$stu_schedule_date,
						$section_title,
						$class_room_title,
						$course_title,
						$shift,
						$track,
						$campus,
						$teacher_name,
						$attendance_perc,
						$absent_hour
					);	
					
				foreach ($cell_week as $enable_wek=>$enable_wek_val){
					if(in_array($enable_wek,$all_weeks))
					{
						$key = array_search($enable_wek, $all_weeks);
						$cell[] =  $all_hours[$key];
					}
					else
					{
						$cell[] =  "N/A";
					}
				}
				
				$entry = $cell;
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
		$order_by = 'section_title,student_uni_id';
		$sort_order = 'asc';
		$query = isset($_POST['q']) ? $_POST['q'] : false;
		$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
	
		$offset = '';
		$per_page = '';
	
		$search_data = array();
		if ($query) $search_data[$qtype] = $query;
	
		$total = $config['total_rows'] = $this->attendance_model->count_all_late_attendance_report($search_data);
		$data = $this->attendance_model->get_late_attendance_report($per_page, $offset, $order_by, $sort_order, $search_data);
	
		$jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
		$enable_week = array();
			
		if(!empty($data))
		{
		
			$arrActivateTime = $this->attendance_model->get_activation_time(); 
			$activate_time = "00:00:00";
			
			if(isset($arrActivateTime[0]["activation_time"]))
				$activate_time = $arrActivateTime[0]["activation_time"];
			
			$enable_week = $this->attendance_model->get_enableweek(1,$activate_time);
				
			$cell_week = array();
			foreach ($enable_week as $enable_wek){
				$cell_week[$enable_wek->week_id] = 0;
			}
	
			$i=1;
			foreach($data->result() as $courseclasses)
			{
	
				$course_title = $courseclasses->course_title;
				$section_title = $courseclasses->section_title;
				$class_room_title = $courseclasses->class_room_title;
				$student_name = $courseclasses->student_name;
				$student_arabicname = $courseclasses->student_arabicname;
				$stu_schedule_date = $courseclasses->stu_schedule_date;
				$teacher_name = $courseclasses->teacher_name;
				$sec_teacher_name = $courseclasses->sec_teacher_name;
				$shift = $courseclasses->shift;
				$track = $courseclasses->track;
				$campus = $courseclasses->campus;
				$student_uni_id = $courseclasses->student_uni_id;
				$academic_status = $courseclasses->academic_status;
				$absent_hour = $courseclasses->total_absent_hour;
				$all_weeks = explode(",",$courseclasses->all_weeks);
				$all_hours = explode(",",$courseclasses->all_hours);
				$attendance_perc = 0;
				if($absent_hour > 0 && $courseclasses->max_hours > 0)
					$attendance_perc = round((($absent_hour * 100)/$courseclasses->max_hours),2);
	
				$cell =
				array(
						'student_uni_id'=>$student_uni_id,
						'student_name'=>$student_name,
						'student_arabicname'=>$student_arabicname,
						'stu_schedule_date'=>$stu_schedule_date,
						'academic_status'=>$academic_status,
						'section_title'=>$section_title,
						'class_room_title'=>$class_room_title,
						'course_title'=>$course_title,
						'shift'=>$shift,
						'track'=>$track,
						'campus'=>$campus,
						'teacher_name'=>$teacher_name,
						'sec_teacher_name'=>$sec_teacher_name,
						'attendance_perc'=>sprintf('%02.2f', $attendance_perc),
						'absent_hour'=>$absent_hour
				);
					
				foreach ($cell_week as $enable_wek=>$enable_wek_val){
					if(in_array($enable_wek,$all_weeks))
					{
						$key = array_search($enable_wek, $all_weeks);
						$cell["week_".$enable_wek] =  $all_hours[$key];
					}
					else
					{
						$cell["week_".$enable_wek] =  "N/A";
					}
				}
	
				$entry = $cell;
				$jsonData['rows'][] = $entry;
				$i++;
			}
		}
		$content_data['export_data'] = $jsonData;
		$content_data['enable_week'] = $enable_week;

		$this->template->build('attendance_report_excel', $content_data);
	}
}
/* End of file list_members.php */