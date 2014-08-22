<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee_stats extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->model('employee_stats_model');
		$this->load->model('list_school_campus/list_school_campus_model');
		$this->load->model('list_role/list_role_model');
    }

    public function index() {
		$user_id = $this->session->userdata('user_id');
		$user_role = $this->session->userdata('role_id');
				
		$content_data = array();
        // set content data
		$content_data['staff_count'] = $this->employee_stats_model->get_staff_count();
		$content_data['pending_count'] = $this->employee_stats_model->get_pending_count();
		$content_data['block_count'] = $this->employee_stats_model->get_block_count();
		
		$campus_arr = $this->list_school_campus_model->get_campus(0,0,"campus_id","asc",array(),true);
		$campus_data_box = array();
		if($campus_arr){
			foreach($campus_arr->result_array() as $campus) {
				$_campus_data_box = array();
				$_campus_data_box['ksu_el'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],3,array(3),2);
				$_campus_data_box['edex_el'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],2,array(3),2);
				$_campus_data_box['iceat_el'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],1,array(3),2);
				$_campus_data_box['total_el'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],false,array(3),2);
				
				$_campus_data_box['ksu_inl'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],3,array(3));
				$_campus_data_box['edex_inl'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],2,array(3));
				$_campus_data_box['iceat_inl'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],1,array(3));
				$_campus_data_box['total_inl'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],false,array(3));
				
				$_campus_data_box['ksu_admin'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],3,'1,3,4',false);
				$_campus_data_box['edex_admin'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],2,'1,3,4',false);
				$_campus_data_box['iceat_admin'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],1,'1,3,4',false);
				$_campus_data_box['total_admin'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],false,'1,3,4',false);
				
				$_campus_data_box['ksu_total'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],3,'1,4',false);
				$_campus_data_box['edex_total'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],2,'1,4',false);
				$_campus_data_box['iceat_total'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],1,'1,4',false);
				$_campus_data_box['total_total'] = $this->employee_stats_model->get_teacher_count($campus['campus_id'],false,'1,4',false);
				
				$campus_data_box[$campus['campus_name']] = $_campus_data_box;
			}
		}
		$content_data['campus_data_box'] = $campus_data_box;
		
		$content_data['total_male_teachers'] = $this->employee_stats_model->get_teacher_count(false,false,array(3),false);
		$content_data['total_female_teachers'] = $this->employee_stats_model->get_teacher_count(false,false,array(3),false,'F');
		$content_data['total_male_and_female_teachers'] = $this->employee_stats_model->get_teacher_count(false,false,array(3),false,false);
		$content_data['total_male_and_female_admin'] = $this->employee_stats_model->get_teacher_count(false,false,'1,3,4',false,false);
		$content_data['total_staff'] = $this->employee_stats_model->get_teacher_count(false,false,'1,4',false,false);
		
		$content_data['departure_stats'] = $this->employee_stats_model->departure_stats();
		$content_data['new_users'] = $this->employee_stats_model->new_users();
		
		$content_data['total_ksu_employees'] = $this->employee_stats_model->get_teacher_count(false,3,'1,4',false,false);
		$content_data['total_edex_employees'] = $this->employee_stats_model->get_teacher_count(false,2,'1,4',false,false);
		$content_data['total_iceat_employees'] = $this->employee_stats_model->get_teacher_count(false,1,'1,4',false,false);
		$content_data['total_employees'] = $this->employee_stats_model->get_teacher_count(false,false,'1,4',false,false);
		
		$content_data['nationality'] = $this->employee_stats_model->get_nationality();
		$content_data['employee_state_month'] = $this->employee_stats_model->get_employee_state();
		$content_data['employee_state_year'] = $this->employee_stats_model->get_employee_state(false);
		
		$content_data['student_all_count'] = $this->employee_stats_model->get_student_count(false,false);
		$content_data['student_male_count'] = $this->employee_stats_model->get_student_count(false);
		$content_data['student_female_count'] = $this->employee_stats_model->get_student_count(false,'F');
		$content_data['latest_student'] = $this->employee_stats_model->get_latest_student();

        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        $this->template->title('employee_stats page');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        
		$this->template->build('employee_stats', $content_data);
		
    }
	
	public function get_nationality_map() {
		$content_data = array();
		$content_data['country_to_display'] = array('UK','Ireland','America','Canada','Australia');
		$content_data['nationality'] = $this->employee_stats_model->get_nationality();
		$this->template->build('nationality_map', $content_data);
	}
	
}

/* End of file employee_stats.php */