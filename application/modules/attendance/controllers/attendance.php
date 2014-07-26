<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Attendance extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('attendance_model');
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
    public function index($order_by = "course_class_id", $sort_order = "asc", $search = "all", $offset = 0, $section_title_from_class = "", $stuid_from_student = "") {
		$content_data['course'] = get_course();		
		
		$where = array();
		$where1 = array();
		$search_data = array();
		
		if($this->session->userdata('role_id') == '3')
		{
			$where[] = array('teacher_id'=>$this->session->userdata('user_id'));	
			$where1[] = array('primary_teacher_id'=>$this->session->userdata('user_id'));	
		}
		
		if(!is_numeric($offset)) 
		   redirect('/attendance');
        $this->load->library('pagination');
        
		$base_url = site_url('attendance/index/'. $order_by .'/'. $sort_order .'/session');
		$search_data = array('course_title' => $this->input->post('course_title'),'class_room_title' => $this->input->post('class_room_title'), 'section_title' => $this->input->post('section_title'), 'student_id' => $this->input->post('student_id'));
		
		$content_data['search'] = "session";
		
		$search_student_id = '';
        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
		if($search == "session") {
			$search_data = array('course_title' => $this->session->userdata('s_course_title'),'class_room_title' => $this->session->userdata('s_class_room_title'), 'section_title' => $this->session->userdata('s_section_title'));
			$search_student_id = $this->session->userdata('s_student_id');
        }else{
			if(isset($_POST['add_attendance_search_submit']) && $_POST['add_attendance_search_submit'] == 'Search') {
				$unset_search_session = array('s_course_title' => '', 's_class_room_title' => '', 's_section_title' => '', 's_student_id' => '');
				$this->session->unset_userdata($unset_search_session);
				$search_session = array(
					's_course_title'  => $this->input->post('course_title'),
					's_class_room_title' => $this->input->post('class_room_title'),                
					's_section_title' => $this->input->post('section_title'),
					's_student_id' => $this->input->post('student_id')
				);
				$search_student_id = $this->input->post('student_id');
				
				$this->session->set_userdata($search_session);
			}
			
			if($section_title_from_class != "" && $section_title_from_class != 0)
			{
				$search_data = array('course_title' => "",'class_room_title' => "", 'section_title' => $section_title_from_class);
				
				$search_session = array(
				's_course_title'  => "",
				's_class_room_title' => "",                
				's_section_title' => $section_title_from_class
				);
				$this->session->set_userdata($search_session);
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
				
				$this->session->set_userdata($search_session);
			}
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
		$content_data['total_rows'] = $config['total_rows'] = $this->list_student_class_model->count_all_course_class($where1,$search_data);
		
		$data = $this->list_student_class_model->get_course_class($per_page, $offset, $order_by, $sort_order, $search_data,$where1);
		
		if(!empty($data)) 
		{
			foreach($data->result() as $courseclasses)
			{
				$section_id = $courseclasses->section_id;
				$course_class_id = $courseclasses->course_class_id;
				$student_data = $this->list_student_class_model->get_class_wise_student($section_id,$search_student_id);
				if($student_data) $courseclasses->student = $student_data->result();
				else $courseclasses->student = array();
					
				
				$student_attendance_data = $this->attendance_model->get_student_class_attendance($course_class_id,$student_user_id);
				$courseclasses->student_attendance_data = $student_attendance_data;
				
				if($courseclasses->school_year_id){
				
					$arrActivateTime = $this->attendance_model->get_activation_time();
					$activate_time = "00:00:00";
					
					if(isset($arrActivateTime[0]["activation_time"]))
						$activate_time = $arrActivateTime[0]["activation_time"];
					
					
					$courseclasses->enable_week = $this->attendance_model->get_enableweek($courseclasses->school_year_id,$activate_time);
				}
			}
		}
		
        if(!empty($data))
		{
            $content_data['course_class'] = $data;
			$student_data = $this->list_student_class_model->get_student();
			$content_data['student_data'] = $student_data;
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
        
        $this->template->title('Add Student Class Attendance');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('add_attendance', $content_data);
    }
	
	// Student Attendance Log code START
	
	function view_attendance_log($student_id) {
		$student_attendance_log = $this->attendance_model->get_attendance_log($student_id);
		$content_data['student_attendance_log'] = $student_attendance_log;
		$content_data['log_enable_week'] = $this->attendance_model->get_log_enable_week();
		$this->template->build('view_attendance_log', $content_data);
	}
	// Student Attendance Log code END
	
	
    public function action_course_class($id, $offset, $order_by, $sort_order, $search) {
        if (array_key_exists('update', $_POST)) {
            $this->_update_course_class($id, $offset, $order_by, $sort_order, $search);
        }else{
            $this->_delete_course_class($id, $offset, $order_by, $sort_order, $search);
        }
    }
	
	public function add_attendance() {
       $this->_add_attendance();
    }
    /**
     *
     * _add_attendance: update member info from attendance
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */
	
	 private function _add_attendance() 
	 {
		$attendance_arr = $this->input->post('attendance');
		if(is_array($attendance_arr) && count($attendance_arr))
		{
			$flag = 0;
			set_activity_log(0,'Attendance Updated','Attendance','Submit Attendance');
			foreach($attendance_arr AS $key=>$weekAbsentHours)
			{
				$arrID = explode("_",$key);
				
				$classID = $arrID[0];
				$studentID = $arrID[1];
				$weekNo = $arrID[2];
				$schoolYearID = $arrID[3];
				$schoolID = $arrID[4];
				$teacherID = $arrID[5];
				
				if(isset($_POST['update_'.$weekNo])){
					if($weekAbsentHours <> "" && is_numeric($weekAbsentHours))
					{
						$this->attendance_model->attendance_log($classID,$studentID,$weekNo,$weekAbsentHours);
						
						if(!$this->attendance_model->update_student_class_attendance($classID,$studentID,$weekNo,$weekAbsentHours)){
			        		$this->attendance_model->add_student_class_attendance($classID,$studentID,$weekNo,$weekAbsentHours,$schoolYearID,$schoolID,$teacherID);
						}
					}		
				}
			}
		}
		
        $this->session->set_flashdata('message', 'Attendance added successfully.');
        redirect('/attendance/index/course_class_id/asc/session/0');
    }
}
/* End of file list_members.php */