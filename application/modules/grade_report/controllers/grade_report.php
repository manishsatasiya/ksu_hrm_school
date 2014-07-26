<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grade_report extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('grade_report_model');
		$this->load->model('list_course_class/list_student_class_model');
		$this->load->model('list_student/list_teacher_student_model');
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
		
		$this->grade_report_model->update_grade_range();
		
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
		$search_data = array();
		
		if($this->session->userdata('role_id') == '3')
		{
			$where[] = array('teacher_id'=>$this->session->userdata('user_id'));	
			$where1[] = array('primary_teacher_id'=>$this->session->userdata('user_id'));	
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
			$student_user_data = $this->list_teacher_student_model->get_student_data_uni_id($search_student_id);
			$search_sec_id = $student_user_data->section_id;
			$student_user_id = $student_user_data->user_id;
			$where1[] = array("course_class.section_id"=>$search_sec_id);
		}
		// set content data
        $content_data['total_rows'] = $config['total_rows'] = $this->list_student_class_model->count_all_course_class($where1,$search_data,$without_third_marks);
		$data = $this->list_student_class_model->get_course_class($per_page, $offset, $order_by, $sort_order, $search_data,$where1,$without_third_marks);
		
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
        
        $this->template->title('Add Grades');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('add_grades', $content_data);
    }

    public function action_course_class($id, $offset, $order_by, $sort_order, $search) {
        if (array_key_exists('update', $_POST)) {
            $this->_update_course_class($id, $offset, $order_by, $sort_order, $search);
        }else{
            $this->_delete_course_class($id, $offset, $order_by, $sort_order, $search);
        }
    }
	
	public function add_grades() {
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
		$grade_arr = $this->input->post('grade');
		$grade2_arr = $this->input->post('grade2');
		$grade3_arr = $this->input->post('grade3');
		$grade_status_arr = $this->input->post('grade_status');
		$arrgrade_type_exam = $this->grade_report_model->get_grade_type_from_exam();
		
		$fixed_status_key = "";
		foreach($grade_arr AS $key=>$exam_mark)
		{
			$arrID = explode("_",$key);
			$exam_status = "";
			$exam_mark_2 = "";	
			$exam_mark_3 = NULL;	
			if(isset($grade2_arr[$key]))
				$exam_mark_2 = $grade2_arr[$key];	

			if(isset($grade3_arr[$key]))
				$exam_mark_3 = $grade3_arr[$key];	
				
			$section_id = $arrID[0];
			$studentUniID = $arrID[1];
			$exam_type_id = $arrID[2];
			
			$grade_typeID = 0;
			if(isset($arrgrade_type_exam[$exam_type_id]))
				$arrgrade_typeID = $arrgrade_type_exam[$exam_type_id];
			
			if($arrgrade_typeID["attendancetype"] == "common")
			{
				$grade_typeID = $arrgrade_typeID["typeid"];
				$fixed_status_key = $section_id."_".$studentUniID."_".$grade_typeID;
				
				if(isset($grade_status_arr[$fixed_status_key]))
					$exam_status = $grade_status_arr[$fixed_status_key];
			}
			if($arrgrade_typeID["attendancetype"] == "examwise")
			{
				if(isset($grade_status_arr[$key]))
					$exam_status = $grade_status_arr[$key];	
			}
			
			if($exam_status != "")
			{
				if($exam_mark == "" || !is_numeric($exam_mark))
					$exam_mark = 0;
			}
			
			if($exam_mark !== "" && is_numeric($exam_mark))
			{
				$this->grade_report_model->grade_report_log($section_id,$studentUniID,$exam_type_id,$exam_mark,$exam_mark_2,$exam_mark_3,$exam_status);
				
				set_activity_log(0,'Grade Updated','Grade','Submit Grade');
				
				if(!$this->grade_report_model->update_student_class_grade($section_id,$studentUniID,$exam_type_id,$exam_mark,$exam_mark_2,$exam_mark_3,$exam_status))
	        		$this->grade_report_model->add_student_class_grade($section_id,$studentUniID,$exam_type_id,$exam_mark,$exam_mark_2,$exam_mark_3,$exam_status);
			}		
		}
		
        $this->session->set_flashdata('message', 'Grades added successfully.');
        redirect('/grade_report/index/section_id/asc/session/0');
    }
    
    function view_grade_report_log($section_id,$student_id,$grade_type_id) {
		$student_grade_report_log = $this->grade_report_model->get_grade_report_log($section_id,$student_id,$grade_type_id);
		$content_data['student_grade_report_log'] = $student_grade_report_log;
		$this->template->build('view_grade_report_log', $content_data);
	}
}

/* End of file list_members.php */
