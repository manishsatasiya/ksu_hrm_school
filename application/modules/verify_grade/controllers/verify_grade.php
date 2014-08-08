<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Verify_grade extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('list_student/list_teacher_student_model');
        $this->load->model('grade_report/grade_report_model');
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
    public function index($order_by = "section_id", $sort_order = "asc", $search = "all", $offset = 0, $section_title_from_class = "", $stuid_from_student = "") {
		$content_data['course'] = get_course();		
		$content_data['without_third_marks'] = array(""=>"Select","Yes"=>"Yes");		
		
		$query_update_res = $this->grade_report_model->update_grade_range();
		
		$show_total_tab = "Yes";
		$show_grade_range = "Yes";

		$data_check_grade_report = $this->grade_report_model->get_setting();
		
		if(isset($data_check_grade_report[0]["show_total_grade"]))
			$show_total_tab = $data_check_grade_report[0]["show_total_grade"];
		if(isset($data_check_grade_report[0]["show_grade_range"]))
			$show_grade_range = $data_check_grade_report[0]["show_grade_range"];
		
		
		$content_data['show_total_tab'] = $show_total_tab;	
		$content_data['show_grade_range'] = $show_grade_range;	
		
		$arrGradeRange = array();

		$data_grade_range = $this->grade_report_model->get_grade_range();
		
		if(is_array($data_grade_range) && count($data_grade_range))
		{
			foreach($data_grade_range AS $rowrange)
			{
				$arrGradeRange[$rowrange["grade_range_id"]] = array("grade_min_range"=>$rowrange["grade_min_range"],
																    "grade_max_range"=>$rowrange["grade_max_range"],
																    "grade_name"=>$rowrange["grade_name"]
																   );
			}
		}
		
		$content_data['arrGradeRange'] = $arrGradeRange;	
		
		$where = array();
		$where1 = array();
		$where2 = array();
		$search_data = array();
		
		if($this->session->userdata('role_id') == '3')
		{
			$where[] = array('teacher_id'=>$this->session->userdata('user_id'));	
			$where2[] = array('secondary_teacher_id'=>$this->session->userdata('user_id'));	
		}
		
		if(!is_numeric($offset)) 
		   redirect('/grade_report');
        $this->load->library('pagination');
        
		$base_url = site_url('grade_report/index/'. $order_by .'/'. $sort_order .'/session');
		$search_data = array('course_title' => $this->input->post('course_title'),'class_room_title' => $this->input->post('class_room_title'), 'section_title' => $this->input->post('section_title'), 'student_id' => $this->input->post('student_id'),'without_third_marks' => $this->input->post('without_third_marks'));
        $content_data['search'] = "session";
		$search_student_id = '';
		$without_third_marks = $this->input->post('without_third_marks');
		
		$per_page = Settings_model::$db_config['members_per_page'];
		
		if($search == "session") {
			$search_data = array('course_title' => $this->session->userdata('s_course_title'),'class_room_title' => $this->session->userdata('s_class_room_title'), 'section_title' => $this->session->userdata('s_section_title'));
			$search_student_id = $this->session->userdata('s_student_id');
			$without_third_marks = $this->session->userdata('s_without_third_marks');
			
        }else{
            $unset_search_session = array('s_course_title' => '', 's_class_room_title' => '', 's_section_title' => '');
            $this->session->unset_userdata($unset_search_session);
			
			$search_session = array(
                's_course_title'  => $this->input->post('course_title'),
                's_class_room_title' => $this->input->post('class_room_title'),                
                's_section_title' => $this->input->post('section_title'),
				's_student_id' => $this->input->post('student_id'),
				's_without_third_marks' => $this->input->post('without_third_marks')
            );
            $search_student_id = $this->input->post('student_id');
            
            if($section_title_from_class != "" && $section_title_from_class != 0)
            {
            	$search_data = array('course_title' => "",'class_room_title' => "", 'section_title' => $section_title_from_class);
            	
            	$search_session = array(
                's_course_title'  => "",
                's_class_room_title' => "",                
                's_section_title' => $section_title_from_class
            	);
            }
            
            if($stuid_from_student != "" && $stuid_from_student > 0)
			{
				$student_userid_data = $this->list_teacher_student_model->get_student_data($stuid_from_student);
				$search_data = array('course_title' => "",'class_room_title' => "", 'section_title' => "");
				
				$search_session = array(
				's_course_title'  => "",
				's_class_room_title' => "",                
				's_section_title' => "",
				's_student_id' => $student_userid_data->student_uni_id
				);
				$search_student_id = $student_userid_data->student_uni_id;
			}
			
			$this->session->set_userdata($search_session);
		}
		
		$student_user_data = '';
		$search_sec_id = 0;
		$student_user_id = '';
		if($search_student_id != '')
		{
			$search_sec_id = $student_user_id = 0;
			$student_user_data = $this->list_teacher_student_model->get_student_data_uni_id($search_student_id);
			if(!empty($student_user_data)){
				$search_sec_id = $student_user_data->section_id;
				$student_user_id = $student_user_data->user_id;
			}
			$where1[] = array("course_class.section_id"=>$search_sec_id);
		}
		// set content data
        $content_data['total_rows'] = $config['total_rows'] = $this->list_student_class_model->count_all_course_class($where1,$search_data,$without_third_marks,$where2);
		$data = $this->list_student_class_model->get_course_class($per_page, $offset, $order_by, $sort_order, $search_data,$where1,$without_third_marks,$where2);
		
		if(!empty($data)) 
		{
			foreach($data->result() as $courseclasses)
			{
				$section_id = $courseclasses->section_id;
				$course_class_id = $courseclasses->course_class_id;
				$student_data = $this->list_student_class_model->get_class_wise_student($section_id,$search_student_id,$without_third_marks);
				if($student_data) $courseclasses->student = $student_data->result();
				
				$student_grade_data = $this->grade_report_model->get_student_class_grades($section_id);
				$student_grade_data_log = $this->grade_report_model->get_student_grades_log($section_id);
				$courseclasses->student_grade_data = $student_grade_data;
				$courseclasses->student_grade_data_log = $student_grade_data_log;
			}
		}
		
        if(!empty($data))
		{
            $content_data['course_class'] = $data;
			$student_data = $this->list_student_class_model->get_student();
			$content_data['student_data'] = $student_data;
			$content_data['grade_type'] = $this->grade_report_model->get_grade_type(1);
			$content_data['grade_type_exam'] = $this->grade_report_model->get_grade_type_exam(1);
			$content_data['offset'] = $offset;
			$content_data['order_by'] = $order_by;
			$content_data['sort_order'] = $sort_order;
			// set pagination config data
			$config['uri_segment'] = '6';
			$config['base_url'] = $base_url;
			$config['per_page'] = Settings_model::$db_config['members_per_page'];
			$config['prev_tag_open'] = ''; // removes &nbsp; at beginning of pagination output
			$this->pagination->initialize($config);
		}
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Verify Grades');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('verify_grade', $content_data);
    }
    public function index_json($order_by = "users.section_id", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	
		$content_data['course'] = get_course();		
		
		$show_total_tab = "Yes";
		$show_grade_range = "Yes";

		$data_check_grade_report =  $this->grade_report_model->get_setting();
		
		if(isset($data_check_grade_report[0]["show_total_grade"]))
			$show_total_tab = $data_check_grade_report[0]["show_total_grade"];
		
		if(isset($data_check_grade_report[0]["show_grade_range"]))
			$show_grade_range = $data_check_grade_report[0]["show_grade_range"];
		
		$content_data['show_total_tab'] = $show_total_tab;		
		$content_data['show_grade_range'] = $show_grade_range;		
			
		$arrGradeRange = array();

		$data_grade_range =  $this->grade_report_model->get_grade_range();
		
		if(is_array($data_grade_range) && count($data_grade_range))
		{
			foreach($data_grade_range AS $rowrange)
			{
				$arrGradeRange[$rowrange["grade_range_id"]] = array("grade_min_range"=>$rowrange["grade_min_range"],
																    "grade_max_range"=>$rowrange["grade_max_range"],
																    "grade_name"=>$rowrange["grade_name"]
																   );
			}
		}
		
		$content_data['arrGradeRange'] = $arrGradeRange;	
		
		$where = array();
		$where1 = array();
		$search_data = array();
		
		$sec_teacher = 0;
		
		if($this->session->userdata('role_id') == '3')
		{
			$where[] = array('teacher_id'=>$this->session->userdata('user_id'));	
			$where1[] = array('primary_teacher_id'=>$this->session->userdata('user_id'));	
			$sec_teacher = $this->session->userdata('user_id');	
		}
		
		if(isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
    	{
    		$search_data["section_title"] = $_GET['sSearch'];
    		$search_data["student_uni_id"] = $_GET['sSearch'];
    	}
		
		if(!is_numeric($offset)) 
		   redirect('/grade_report');
        $this->load->library('pagination');
        $base_url = site_url('grade_report/index/'. $order_by .'/'. $sort_order .'/session');
		
        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $per_page = 25;
		$content_data['grade_type'] = $this->grade_report_model->get_grade_type();
		$content_data['grade_type_exam'] = $this->grade_report_model->get_grade_type_exam();
		$arrExamType = $content_data['grade_type_exam'];
		
		if($show_total_tab == "Yes")
			$content_data['grade_type'][1111]["grade_type"] = "TOTALS";
			
		$aColumns = array();
		$grade_type = $content_data['grade_type'][$_GET["gtid"]];
		$grade_type_exam = array();
		
		if(isset($content_data['grade_type_exam'][$_GET["gtid"]]))
			$grade_type_exam = $content_data['grade_type_exam'][$_GET["gtid"]];
		
		$aColumns[] ="student_uni_id";
		$aColumns[] ="section_title";
		$aColumns[] ="first_name";
		
		if(count($grade_type_exam))
		{	
			foreach($grade_type_exam as $grade_type_exam_id=>$grade_type_exam_data)
			{
				if($grade_type["attendance_type"] == "examwise")
					$aColumns[] ="exam_status";
					
					$aColumns[] = $grade_type_exam_data["exam_marks"];
					if($grade_type_exam_data["is_show_percentage"] == "Yes")
						$aColumns[] = $grade_type_exam_data["exam_percentage"];
						
				if($grade_type["verification_type"] == "examwise" && $grade_type["is_show_verified"] == "Yes")		
					$aColumns[] = "verified";
			}
		}
		
		if(isset($grade_type["verification_type"]) && $grade_type["verification_type"] == "common" && $grade_type["is_show_verified"] == "Yes")		
				$aColumns[] = "verified";
				
		if(isset($grade_type["attendance_type"]) && $grade_type["attendance_type"] == "common")
			$aColumns[] ="exam_status";
			
		if($_GET["gtid"] == 1111 && $show_total_tab == "Yes")
		{
			foreach($grade_type AS $grade_type_id=>$grade_type_data)
			{
				if(isset($grade_type_data["show_total_marks"]) && $grade_type_data["show_total_marks"] == "Yes")
					$aColumns[] = $grade_type_data["grade_type"]." Marks(".$grade_type_data["total_markes"].")";
				if(isset($grade_type_data["show_grade_range"]) && $grade_type_data["show_grade_range"] == "Y")
					$aColumns[] = "Range";	
				if(isset($grade_type_data["show_total_per"]) && $grade_type_data["show_total_per"] == "Yes")	
					$aColumns[] = $grade_type_data["grade_type"]." %(".$grade_type_data["total_percentage"].")";
			}
		}
		else
		{	
			if($grade_type["show_total_marks"] == "Yes")
				$aColumns[] = $grade_type["total_markes"];
			if(isset($grade_type["show_grade_range"]) && $grade_type["show_grade_range"] == "Y")
				$aColumns[] = "Range";
			if($grade_type["show_total_per"] == "Yes")	
				$aColumns[] = $grade_type["total_percentage"];
		}
		
    	$grid_data = get_search_data($aColumns);
    	
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    	if($order_by == "student_uni_id")
    		$order_by = "section_title,student_uni_id";
    		
    	$count = $this->list_teacher_student_model->count_all_sec_teacher_course_class_student($sec_teacher,$search_data);
		$student_data = $this->list_teacher_student_model->get_sec_teacher_course_class_student($per_page, $offset, $order_by, $sort_order, $sec_teacher,$search_data);
				
		$content_data['student'] = $student_data;
		
    	/*
    	 * Output
    	*/
    	$output = array(
    			"sEcho" => intval($_GET['sEcho']),
    			"iTotalRecords" => $count,
    			"iTotalDisplayRecords" => $count,
    			"aaData" => array()
    	);
    	
    	$is_data_found = 0;
    	if($student_data)
    	{
	    	foreach($student_data->result() AS $student_datas)
			{
				$arrTotals_Tab = array();
				$total_percentage = 0;
				$temp_grade_type = $content_data['grade_type'];							
							
				foreach($temp_grade_type AS $temp_grade_type_id=>$temp_grade_type_data)	
				{
					$temp_total_exam_mark = 0;
					if(isset($content_data['grade_type_exam'][$temp_grade_type_id]))
					{
						$temp_arr_grade_exam = $content_data['grade_type_exam'][$temp_grade_type_id];
						foreach($temp_arr_grade_exam as $temp_grade_type_exam_id=>$temp_grade_type_exam_data)
						{
							$content_data['grade_data'] = $this->grade_report_model->get_student_class_grades($student_datas->section_id,$student_datas->student_uni_id,$temp_grade_type_exam_data["grade_type_exam_id"]);
							$temp_exam_mark = 0;
							if(isset($content_data['grade_data'][$student_datas->section_id][$temp_grade_type_exam_data["grade_type_exam_id"]][$student_datas->student_uni_id]["exam_marks"]))
								$temp_exam_mark = $content_data['grade_data'][$student_datas->section_id][$temp_grade_type_exam_data["grade_type_exam_id"]][$student_datas->student_uni_id]["exam_marks"];
							$temp_exam_mark = round($temp_exam_mark,1);

							$temp_total_exam_mark += $temp_exam_mark;
						}	

						$temp_percentage = round(($temp_total_exam_mark*$temp_grade_type_data["total_percentage"])/$temp_grade_type_data["total_markes"],2);

						$total_percentage += $temp_percentage;	
					}	
					if($_GET["gtid"] == 1111 && isset($temp_grade_type_data["total_percentage"]))
					{			
						$arrTotals_Tab[$temp_grade_type_id][$student_datas->student_uni_id]["total_marks"] = round($temp_total_exam_mark,1);
						$arrTotals_Tab[$temp_grade_type_id][$student_datas->student_uni_id]["total_perc"] =round(($temp_total_exam_mark*$temp_grade_type_data["total_percentage"])/$temp_grade_type_data["total_markes"],2);
						
					}	
					if($temp_grade_type_id == 1111 && $show_total_tab == "Yes")
						$arrTotals_Tab[$temp_grade_type_id][$student_datas->student_uni_id]["total_100_perc"] = round($total_percentage,2);	
				}
				
	    		$row = array();
	    		$row[] = $student_datas->student_uni_id;
	    		$row[] = $student_datas->section_title;
	    		$row[] = $student_datas->first_name;
	    			
	    			$total_exam_mark = 0;
	    			$cnt = 0;
					$exam_status = "Please Select";
	    			if($_GET["gtid"] != 1111)
	    			{
						foreach($grade_type_exam AS $rowExamType)
						{
							$content_data['grade_data'] = $this->grade_report_model->get_student_class_grades($student_datas->section_id,$student_datas->student_uni_id,$rowExamType["grade_type_exam_id"]);
							
							$exam_mark = "N/A";
							$verified = "No";
							$exam_status = "Please Select";
							
							if(isset($content_data['grade_data'][$student_datas->section_id][$rowExamType["grade_type_exam_id"]][$student_datas->student_uni_id]["exam_marks"]))
							{
								//$exam_mark = $content_data['grade_data'][$student_datas->section_id][$rowExamType["grade_type_exam_id"]][$student_datas->student_uni_id]["exam_marks"];
								$is_data_found = 1;
								
								$exam_mark_for_per = $content_data['grade_data'][$student_datas->section_id][$rowExamType["grade_type_exam_id"]][$student_datas->student_uni_id]["exam_marks"];
								$exam_mark = $content_data['grade_data'][$student_datas->section_id][$rowExamType["grade_type_exam_id"]][$student_datas->student_uni_id]["exam_marks"];
								$exam_mark_2 = $content_data['grade_data'][$student_datas->section_id][$rowExamType["grade_type_exam_id"]][$student_datas->student_uni_id]["exam_marks_2"];
								$exam_mark_3 = $content_data['grade_data'][$student_datas->section_id][$rowExamType["grade_type_exam_id"]][$student_datas->student_uni_id]["exam_marks_3"];
								
								if($rowExamType["is_two_marker"] == "Yes")
								{
									if(abs($exam_mark-$exam_mark_2) >= $rowExamType["two_mark_difference"])
									{
										if($exam_mark_3 !== "" && $exam_mark_3 !== "3rd")
										{
											$arrMarkerVal = array();
											$arrMarkerVal = array($exam_mark,$exam_mark_2,$exam_mark_3);
											
											rsort($arrMarkerVal);
											
											$exam_mark_for_per = round(($arrMarkerVal[0]+$arrMarkerVal[1])/2,1);
											$exam_mark = '<a data-target="#myModal" data-toggle="modal" href="verify_grade/add_ca_mark/'.$rowExamType["grade_type_exam_id"].'/'.$student_datas->student_uni_id.'">'.round(($arrMarkerVal[0]+$arrMarkerVal[1])/2,1).'</a>';
											
										}	
										else
										{									
											$exam_mark_for_per = "3rd";
											$exam_mark = '<a data-target="#myModal" data-toggle="modal" href="verify_grade/add_ca_mark/'.$rowExamType["grade_type_exam_id"].'/'.$student_datas->student_uni_id.'">3rd</a>';	
										}	
									}
									else
									{
										if($exam_mark > 0 || $exam_mark_2 > 0)
										{
											$exam_mark_for_per = round(($exam_mark+$exam_mark_2)/2,1);
											$exam_mark = '<a data-target="#myModal" data-toggle="modal" href="verify_grade/add_ca_mark/'.$rowExamType["grade_type_exam_id"].'/'.$student_datas->student_uni_id.'">'.round(($exam_mark+$exam_mark_2)/2,1).'</a>';
										}
									}
								}
							}
								
							if(isset($content_data['grade_data'][$student_datas->section_id][$rowExamType["grade_type_exam_id"]][$student_datas->student_uni_id]["exam_status"]))
								$exam_status = $content_data['grade_data'][$student_datas->section_id][$rowExamType["grade_type_exam_id"]][$student_datas->student_uni_id]["exam_status"];	
								
							if(isset($content_data['grade_data'][$student_datas->section_id][$rowExamType["grade_type_exam_id"]][$student_datas->student_uni_id]["verified"]))
								$verified = $content_data['grade_data'][$student_datas->section_id][$rowExamType["grade_type_exam_id"]][$student_datas->student_uni_id]["verified"];		
							
							if(is_numeric($exam_mark))	
								$exam_mark = round($exam_mark,1);
								
							$total_exam_mark += (int)$exam_mark;
							$percentage = ((int)$exam_mark*$rowExamType["exam_percentage"])/$rowExamType["exam_marks"];
							
							if($grade_type["attendance_type"] == "examwise")
								$row[] = $exam_status;
							
							$row[] = $exam_mark;
							if($rowExamType["is_show_percentage"] == "Yes")
								$row[] = $percentage;
							
							if($grade_type["verification_type"] == "examwise" && $grade_type["is_show_verified"] == "Yes")	
								$row[] = str_replace("Cant","Can't",$verified);
							
							$cnt++;
						}
					}
					
					if(isset($grade_type["verification_type"]) && $grade_type["verification_type"] == "common" && $grade_type["is_show_verified"] == "Yes")	
						$row[] = str_replace("Cant","Can't",$verified);
						
					if(isset($grade_type["attendance_type"]) && $grade_type["attendance_type"] == "common")
						$row[] = $exam_status;
						
					if($_GET["gtid"] == 1111 && $show_total_tab == "Yes")
					{
						$total_100_per = 0;
						foreach($temp_grade_type AS $temp_grade_type_id=>$temp_grade_type_data)	
						{
							if(isset($arrTotals_Tab[$temp_grade_type_id][$student_datas->student_uni_id]))
							{
								if($temp_grade_type_id == 1111 && $show_total_tab == "Yes")
								{
									$total_100_per = $arrTotals_Tab[$temp_grade_type_id][$student_datas->student_uni_id]["total_100_perc"];
								}
								else
								{
									if($temp_grade_type_data["show_total_marks"] == "Yes")
										$row[] = $arrTotals_Tab[$temp_grade_type_id][$student_datas->student_uni_id]["total_marks"];
									if($temp_grade_type_data["show_grade_range"] == "Y")
									{
										$range_name = "N/A";
										$range_total_marks = $arrTotals_Tab[$temp_grade_type_id][$student_datas->student_uni_id]["total_marks"];
										
										if(is_array($arrGradeRange) && count($arrGradeRange))
										{
											foreach($arrGradeRange AS $rowrange)
											{
												if($range_total_marks >= $rowrange["grade_min_range"] && $range_total_marks <= $rowrange["grade_max_range"])
													$range_name = $rowrange["grade_name"];		
											}
										}
										
										$row[] = $range_name;	
									}	
									if($temp_grade_type_data["show_total_per"] == "Yes")	
										$row[] = $arrTotals_Tab[$temp_grade_type_id][$student_datas->student_uni_id]["total_perc"];
								}
							}
							else 
							{
								if($temp_grade_type_data["show_total_marks"] == "Yes")
									$row[] = 0;
								if($temp_grade_type_data["show_grade_range"] == "Y")
									$row[] = "N/A";	
								if($temp_grade_type_data["show_total_per"] == "Yes")	
									$row[] = 0;
							}		
							
							if($temp_grade_type_id == 1111 && $show_total_tab == "Yes")
							{
								$row[] = $total_100_per;
								$range_name = "N/A";
								$range_total_marks = $total_100_per;
								
								if(is_array($arrGradeRange) && count($arrGradeRange))
								{
									foreach($arrGradeRange AS $rowrange)
									{
										if($range_total_marks >= $rowrange["grade_min_range"] && $range_total_marks <= $rowrange["grade_max_range"])
											$range_name = $rowrange["grade_name"];		
									}
								}
								if($show_grade_range == "Yes")
									$row[] = $range_name;
							}	
						}
					}
					else
					{			
						if($grade_type["show_total_marks"] == "Yes")			
							$row[] = $total_exam_mark;
						if($grade_type["show_grade_range"] == "Y")
						{
							$range_name = "N/A";
							$range_total_marks = $total_exam_mark;
							
							if(is_array($arrGradeRange) && count($arrGradeRange))
							{
								foreach($arrGradeRange AS $rowrange)
								{
									if($range_total_marks >= $rowrange["grade_min_range"] && $range_total_marks <= $rowrange["grade_max_range"])
										$range_name = $rowrange["grade_name"];		
								}
							}
							
							$row[] = $range_name;	
						}	
						if($grade_type["show_total_per"] == "Yes")	
							$row[] = round(($total_exam_mark*$grade_type["total_percentage"])/$grade_type["total_markes"],2);
					}
	    			$output['aaData'][] = $row;
	    	}
	    	
	    	echo json_encode($output);
    	}
    	else 
    	{
    		echo json_encode($output);
    	}
    	
    }
    
    public function update_grade(){
    	$error = "";
    	
    	$arrcolumnName = explode("|",$this->input->post('columnName'));
    	
    	$grade_type_exam_id = $arrcolumnName[1];
    	$student_uni_id = $this->input->post('id');
    	$section_id = $this->getstudentsectionid($student_uni_id);
    	
    	$columnName = $arrcolumnName[0];
    	$value = $this->input->post('value');
    	
    	$tablename = 'grade_report';
    	
    	if($columnName != '') $_POST[$columnName] = $value;
    	$this->form_validation->set_error_delimiters('', '');
    	if($columnName == 'exam_marks' && (trim($value) == "" || !is_numeric(trim($value))))
    	{
    		$error = "Please enter marks either numeric or decimal";
    		echo $error;
    		exit();
    	}
    	else if($section_id == 0 || $section_id == "")
    	{
    		$error = "Please assign student to specific section before update grade";
    		echo $error;
    		exit();
    	}
		
		if($columnName == "exam_marks")
			$this->grade_report_model->grade_report_log($section_id,$student_uni_id,$grade_type_exam_id,$value,"blank","blank","blank");
		if($columnName == "exam_mark_2")	
			$this->grade_report_model->grade_report_log($section_id,$student_uni_id,$grade_type_exam_id,"blank",$value,"blank","blank");
		if($columnName == "exam_mark_3")	
			$this->grade_report_model->grade_report_log($section_id,$student_uni_id,$grade_type_exam_id,"blank","blank",$value,"blank");
		if($columnName == "exam_status")	
			$this->grade_report_model->grade_report_log($section_id,$student_uni_id,$grade_type_exam_id,"blank","blank","blank",$value);
		
		if($this->addgrade_report($section_id,$student_uni_id,$grade_type_exam_id,$columnName,$value))
		{
			set_activity_log(0,'Grade Updated','Grade','Verify Grade');
			
			echo "success";
			exit;	
		}
		else 
		{
			echo "Error.....";
			exit;
		}
		
    	//grid_update_data($whrid_column,$id,$columnName,$value,$tablename);
    }
    
    public function getstudentsectionid($stundet_uni_id="")
    {

		$section = $this->grade_report_model->getstudentsectionid($stundet_uni_id);
		if(isset($section[0]["section_id"]))
			return $section[0]["section_id"];
		else 
			return 0;	
    }
    
    public function addgrade_report($section_id,$student_uni_id,$grade_type_exam_id,$columnName,$value)
    {
		$arrExamTypeID = explode(",",$grade_type_exam_id);
		if(is_array($arrExamTypeID) && count($arrExamTypeID) > 0)
		{
			foreach($arrExamTypeID AS $grade_exam_id)
			{
				if((int)$grade_exam_id > 0)
				{

					$data_check_grade_report = $this->grade_report_model->count_grade_report($section_id,$student_uni_id,$grade_exam_id);
					
					if(isset($data_check_grade_report[0]["cnt"]) && $data_check_grade_report[0]["cnt"] > 0)
					{
						$this->grade_report_model->update_grade_report($columnName,$value,$section_id,$student_uni_id,$grade_exam_id);
					}
					else 
					{
						$fields = "section_id,student_uni_id,grade_type_exam_id,created_date,$columnName";
						$values = "'$section_id','$student_uni_id','$grade_exam_id',NOW(),'$value'";

						$this->grade_report_model->insert_grade_report($fields,$values);
					}
				}
			}
			return true;
		}
		return false;
    }
	
	public function add_ca_mark($geid,$stuid){
    	$rowdata = array();
		
		$section_id = $this->getstudentsectionid($stuid);
    	if($stuid){
    		$rowdata = $this->grade_report_model->get_student_grade_ca_data($section_id,$geid,$stuid);
    	}
    	
    	$content_data['rowdata'] = $rowdata;
		
    	if($this->input->post()){
			$studentUniID = $this->input->post('studentUniID');
    		$exam_type_id = $this->input->post('exam_type_id');
    		$exam_mark = $this->input->post('exam_marks');
			$exam_mark_2 = $this->input->post('exam_marks_2');
			$exam_mark_3 = $this->input->post('exam_marks_3');
			$exam_status = $this->input->post('exam_status');
    		$error = "";
    		$error_seperator = "<br>";
			
    		set_activity_log(0,'Grade Updated (3rd Marks)','Grade','Verify Grade');
			$this->grade_report_model->grade_report_log($section_id,$studentUniID,$exam_type_id,$exam_mark,$exam_mark_2,$exam_mark_3,$exam_status);
			
    		if(!$this->grade_report_model->update_student_class_grade($section_id,$studentUniID,$exam_type_id,$exam_mark,$exam_mark_2,$exam_mark_3,$exam_status))
	        	$this->grade_report_model->add_student_class_grade($section_id,$studentUniID,$exam_type_id,$exam_mark,$exam_mark_2,$exam_mark_3,$exam_status);
			
			redirect('verify_grade', $content_data);
			die();
    	}
    	$this->template->build('add_ca_mark_verify', $content_data);
    }
    
    public function Verification() {
       $this->_add_grades();
    }
    
    /**
     *
     * _add_grades: update member info from attendance
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */
	
	 private function _add_grades() 
	 {
		$grade_verify_arr = $this->input->post('grade_verify');
		$grade_lead_verify_arr = $this->input->post('grade_lead_verify');
		
		$arrgrade_type_exam = $this->grade_report_model->get_grade_type_from_exam();
		
		$fixed_verify_key = "";
		foreach($grade_verify_arr AS $key=>$verify)
		{
			$verify = "";
			$arrID = explode("_",$key);
				
			$section_id = $arrID[0];
			$studentUniID = $arrID[1];
			$exam_type_id = $arrID[2];
			
			$grade_typeID = 0;
			
			if(isset($arrgrade_type_exam[$exam_type_id]))
				$arrgrade_typeID = $arrgrade_type_exam[$exam_type_id];
			
			if($arrgrade_typeID["verificationtype"] == "common")
			{
				$grade_typeID = $arrgrade_typeID["typeid"];
				$fixed_verify_key = $section_id."_".$studentUniID."_".$exam_type_id;
				
				if(isset($grade_verify_arr[$fixed_verify_key]))
					$verify = $grade_verify_arr[$fixed_verify_key];
			}
			if($arrgrade_typeID["verificationtype"] == "examwise")
			{
				if(isset($grade_verify_arr[$key]))
					$verify = $grade_verify_arr[$key];	
			}
			
			if($verify !== "")
			{
				
				set_activity_log(0,'Grade Verification','Grade','Verification Submitted');
				if($grade_typeID > 0) {
					foreach($arrgrade_type_exam as $exam_id=>$row_exam) {
						if($row_exam['typeid'] == $grade_typeID) {
							if(!$this->grade_report_model->update_student_class_grade_verify($section_id,$studentUniID,$exam_id,$verify))
							$this->grade_report_model->add_student_class_grade_verify($section_id,$studentUniID,$exam_id,$verify);	
						}
					}	
				}else {
					if(!$this->grade_report_model->update_student_class_grade_verify($section_id,$studentUniID,$exam_type_id,$verify)){
						$this->grade_report_model->add_student_class_grade_verify($section_id,$studentUniID,$exam_type_id,$verify);
					}	
				}	
			}		
		}
		
		$fixed_verify_key = "";
		foreach($grade_lead_verify_arr AS $key=>$verify)
		{
			$verify = "";
			$grade_lead_verify = '';
			$arrID = explode("_",$key);
				
			$section_id = $arrID[0];
			$studentUniID = $arrID[1];
			$exam_type_id = $arrID[2];
			
			$grade_typeID = 0;
			if(isset($arrgrade_type_exam[$exam_type_id]))
				$arrgrade_typeID = $arrgrade_type_exam[$exam_type_id];
			
			if($arrgrade_typeID["verificationtype"] == "common")
			{
				$grade_typeID = $arrgrade_typeID["typeid"];
				$fixed_verify_key = $section_id."_".$studentUniID."_".$exam_type_id;
				
				if(isset($grade_lead_verify_arr[$fixed_verify_key]))
					$grade_lead_verify = $grade_lead_verify_arr[$fixed_verify_key];
			}
			if($arrgrade_typeID["verificationtype"] == "examwise")
			{
				if(isset($grade_lead_verify_arr[$key]))
					$grade_lead_verify = $grade_lead_verify_arr[$key];	
			}
			
			if($grade_lead_verify !== "")
			{
				
				set_activity_log(0,'Grade Verification','Grade','Verification Submitted');
				if($grade_typeID > 0) {
					foreach($arrgrade_type_exam as $exam_id=>$row_exam) {
						if($row_exam['typeid'] == $grade_typeID) {
							if(!$this->grade_report_model->update_student_class_grade_verify($section_id,$studentUniID,$exam_id,$verify,$grade_lead_verify))
							$this->grade_report_model->add_student_class_grade_verify($section_id,$studentUniID,$exam_id,$verify,$grade_lead_verify);
						}
					}	
				}else {
					if(!$this->grade_report_model->update_student_class_grade_verify($section_id,$studentUniID,$exam_type_id,$verify,$grade_lead_verify)){
					$this->grade_report_model->add_student_class_grade_verify($section_id,$studentUniID,$exam_type_id,$verify,$grade_lead_verify);
					}
				}
			}		
		}
		
        $this->session->set_flashdata('message', 'Grades Verified successfully.');
        redirect('/verify_grade/index/section_id/asc/session/0');
    }
}
/* End of file verify_grade.php */