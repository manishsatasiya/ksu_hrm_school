<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teachers_attendance extends Private_Controller {

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
		
		$this->template->title('Teachers Attendance Report');
		$this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
		$this->template->set_partial('footer', 'footer');
		$this->template->build('teachers_attendance', $content_data);
    }
	
    public function getdata($csrf_token_name = "", $csrf_token_value = "") 
	{
		$aColumns = array('elsd_id','first_name','section_title','course_title','shift','campus');
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
		$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
		$count = $config['total_rows'] = $this->attendance_model->count_teacher_attendance($grid_data['search_data']);
		
		/*
    	 * Output
    	*/
    	$output = array(
    			"sEcho" => intval($_GET['sEcho']),
    			"iTotalRecords" => $count,
    			"iTotalDisplayRecords" => $count,
    			"aaData" => array()
    	);
		
		if($count > 0) 
		{
			$arrActivateTime = $this->attendance_model->get_activation_time();
			$am_time = "00:00:00";
			$pm_time = "00:00:00";
			
			if(isset($arrActivateTime[0]["am_time"]))
				$am_time = $arrActivateTime[0]["am_time"];
			if(isset($arrActivateTime[0]["pm_time"]))
				$pm_time = $arrActivateTime[0]["pm_time"];
				
			$enable_week = $this->attendance_model->get_schoolenableweek($pm_time,$am_time); 
			
			$cell_week = array();
			foreach ($enable_week as $enable_wek){
				$cell_week["am"][$enable_wek->week_id] = $enable_wek->lastsubmitiondate_am.str_replace(":", "", trim($am_time));
				$cell_week["pm"][$enable_wek->week_id] = $enable_wek->lastsubmitiondate_pm.str_replace(":", "", trim($pm_time));
			}
			
			$data = $this->attendance_model->getdata_teacher_attendance($per_page, $offset, $order_by, $sort_order,$grid_data['search_data'],count($cell_week["am"]));
			
			$i=1;
			foreach($data AS $courseclasses)
			{
				
				$elsd_id = $courseclasses["elsd_id"];
				$school_year_title = $courseclasses["school_year_title"];
				$course_title = $courseclasses["course_title"];
				$section_title = $courseclasses["section_title"];
				$teacher_name = $courseclasses["first_name"];
				$shift = $courseclasses["shift"];
				$campus = $courseclasses["campus"];
				
				$cell = 
				array(
						$elsd_id,
						$teacher_name,
						$section_title,
						$course_title,
						$shift,
						$campus,
						$school_year_title
					);	
				$cell_weekarr = $cell_week["am"];

				if(strtolower($shift) == "pm")
					$cell_weekarr = $cell_week["pm"];
				
				foreach ($cell_weekarr as $enable_wek=>$enable_wek_val)
				{
					if(array_key_exists("week_".$enable_wek,$courseclasses))
					{
						if($courseclasses["week_".$enable_wek] != "" && $courseclasses["week_".$enable_wek] != NULL)
						{
							if($courseclasses["week_".$enable_wek] <= $enable_wek_val)
							{
								$cell[] =  "On Time";
							}
							else							
							{
								$cell[] =  "Late Submission";
							}
						}
						else
						{						
							$cell[] =  "N/A";
						}	
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
	
		$total = $config['total_rows'] = $this->attendance_model->count_teacher_attendance($search_data);
		$jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
		
		if($total > 0) 
		{
			$arrActivateTime = $this->attendance_model->get_activation_time();
			$am_time = "00:00:00";
			$pm_time = "00:00:00";
			
			if(isset($arrActivateTime[0]["am_time"]))
				$am_time = $arrActivateTime[0]["am_time"];
			if(isset($arrActivateTime[0]["pm_time"]))
				$pm_time = $arrActivateTime[0]["pm_time"];
				
			$enable_week = $this->attendance_model->get_enableweek(1,$activate_time); 
			
			$cell_week = array();
			foreach ($enable_week as $enable_wek){
				$cell_week["am"][$enable_wek->week_id] = $enable_wek->lastsubmitiondate_am.str_replace(":", "", trim($am_time));
				$cell_week["pm"][$enable_wek->week_id] = $enable_wek->lastsubmitiondate_pm.str_replace(":", "", trim($pm_time));
			}
			
			$data = $this->attendance_model->getdata_teacher_attendance($per_page, $offset, $order_by, $sort_order,$search_data,count($cell_week["am"]));
			
			$i=1;
			foreach($data AS $courseclasses)
			{
				$elsd_id = $courseclasses["elsd_id"];
				$school_year_title = $courseclasses["school_year_title"];
				$course_title = $courseclasses["course_title"];
				$section_title = $courseclasses["section_title"];
				$teacher_name = $courseclasses["first_name"];
				$shift = $courseclasses["shift"];
				$campus = $courseclasses["campus"];
				
				$cell = 
				array(
						'elsd_id'=>$elsd_id,
						'first_name'=>$teacher_name,
						'section_title'=>$section_title,
						'course_title'=>$course_title,
						'shift'=>$shift,
						'campus'=>$campus
					);	
				
				$cell_weekarr = $cell_week["am"];

				if(strtolower($shift) == "pm")
					$cell_weekarr = $cell_week["pm"];
					
				foreach ($cell_weekarr as $enable_wek=>$enable_wek_val)
				{
					if(array_key_exists("week_".$enable_wek,$courseclasses))
					{
						if($courseclasses["week_".$enable_wek] != "" && $courseclasses["week_".$enable_wek] != NULL)
						{
							if($courseclasses["week_".$enable_wek] <= $enable_wek_val)
							{
								$cell["week_".$enable_wek] =  "On Time";
							}
							else							
							{
								$cell["week_".$enable_wek] =  "Late Submission";
							}
						}	
						else	
							$cell["week_".$enable_wek] =  "N/A";
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
		$this->template->build('teacher_attendance_report_excel', $content_data);
		
	}
}
/* End of file list_members.php */