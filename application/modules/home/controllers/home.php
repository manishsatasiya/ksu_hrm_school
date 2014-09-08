<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->model('home_model');
		//$this->load->model('employee_stats/employee_stats_model');
		$this->load->model('list_school_campus/list_school_campus_model');
		$this->load->model('list_role/list_role_model');
    }

    public function index() {
		$user_id = $this->session->userdata('user_id');
		$user_role = $this->session->userdata('role_id');
		
		$role_data = $this->list_role_model->get_role_data($user_role);
		$dashboard_page = $role_data->dashboard_page;
		if($dashboard_page == 'Advance'){
			$template = 'homepage';
		}elseif($dashboard_page == 'Normal'){
			$template = 'homepage_normal';
		}else {
			$template = 'homepage_text';
		}
		
		$content_data = array();
        // set content data
		if($template == 'homepage_normal'){
			$course_class_of_teacher = $this->home_model->count_all_course_class_of_teacher($user_id);
			
			$course_class_of_teacher_arr = array();
			if($course_class_of_teacher){
				foreach($course_class_of_teacher->result_array() as $course_class) {
					$course_class_of_teacher_arr[] = $course_class['section_title'];
				}
			}
			$count_course_class_of_teacher = count($course_class_of_teacher_arr);
			$content_data['student_of_teacher'] = $this->home_model->count_all_student_of_teacher($user_id);
			$content_data['count_course_class_of_teacher'] = $count_course_class_of_teacher;
			$content_data['course_class_of_teacher_arr'] = $course_class_of_teacher_arr;
		}elseif($template == 'homepage'){
			$content_data['total_male_teachers'] = $this->home_model->get_teacher_count(false,false,array(3),false);
			$content_data['total_female_teachers'] = $this->home_model->get_teacher_count(false,false,array(3),false,'F');
			
			$content_data['total_male_staff'] = $this->home_model->get_teacher_count(false,false,'1,4',false);
			$content_data['total_female_staff'] = $this->home_model->get_teacher_count(false,false,'1,4',false,'F');
			$content_data['total_staff'] = $this->home_model->get_teacher_count(false,false,'1,4',false,false);
			
			$content_data['student_all_count'] = $this->home_model->get_student_count(false,false);
			$content_data['student_male_count'] = $this->home_model->get_student_count(false);
			$content_data['student_female_count'] = $this->home_model->get_student_count(false,'F');
			
			$campus_arr = $this->list_school_campus_model->get_campus(0,0,"campus_id","asc",array(),true);
			$course_class = array();
			if($campus_arr){
				foreach($campus_arr->result_array() as $campus) {
					$course_class[$campus['campus_name']] = $this->home_model->get_course_class_count($campus['campus_id']);
				}
			}
			
			$content_data['course_class'] = $course_class;
			
			$content_data['staff_count'] = $this->home_model->get_staff_count();
			$content_data['employee_state_month'] = $this->home_model->get_employee_state();
			$content_data['employee_state_year'] = $this->home_model->get_employee_state(false);
			
			$latest_student = array();
			$latest_student_created = $this->home_model->get_latest_student();
			$latest_student_updated = $this->home_model->get_latest_student('updated_date');
			if($latest_student_created) {
				foreach($latest_student_created->result_array() as $student){
					$latest_student[$student['user_id']] = array('date'=>$student['created_date'],'student_uni_id'=>$student['student_uni_id'],'name'=>$student['first_name'].' '.$student['last_name']);
				}
			}
			
			if($latest_student_updated) {
				foreach($latest_student_updated->result_array() as $_student){
					$latest_student[$_student['user_id']] = array('date'=>$_student['updated_date'],'student_uni_id'=>$_student['student_uni_id'],'name'=>$_student['first_name'].' '.$_student['last_name']);
				}
			}		
			uasort($latest_student, array($this, 'ascendingCustomSort'));
			
			$content_data['latest_student'] = $latest_student;
		}
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        $this->template->title('home page');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        
		$this->template->build($template, $content_data);
		
    }
	
	public function ascendingCustomSort($a, $b){
		$ascending = false;
		$d1 = strtotime($a['date']);
		$d2 = strtotime($b['date']);
		return $ascending ? ($d1 - $d2) : ($d2 - $d1);
	}
	public function get_nationality_map() {
		$content_data = array();
		$content_data['country_to_display'] = array('UK','Ireland','America','Canada','Australia');
		$content_data['nationality'] = $this->home_model->get_nationality();
		$this->template->build('nationality_map', $content_data);
	}
	// no access view if user has no privilage
    function no_access(){
    	$content_data = array();
    	$this->template->set_theme(Settings_model::$db_config['default_theme']);
    	$this->template->set_layout('school');
    	$this->template->title('No Access');
    	$this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
    	$this->template->set_partial('footer', 'footer');
    	$this->template->build('no_access', $content_data);
    }
}

/* End of file home.php */