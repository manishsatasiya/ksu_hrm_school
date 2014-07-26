<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->model('home_model');
		$this->load->model('list_school_campus/list_school_campus_model');
    }

    public function index() {
        // set content data
						 
        $content_data['staff_count'] = $this->home_model->get_staff_count();
		$content_data['pending_count'] = $this->home_model->get_pending_count();
		$content_data['block_count'] = $this->home_model->get_block_count();
		
		$campus_arr = $this->list_school_campus_model->get_campus(0,0,"campus_id","asc",array(),true);
		$campus_data_box = array();
		if($campus_arr){
			foreach($campus_arr->result_array() as $campus) {
				$_campus_data_box = array();
				$_campus_data_box['ksu_el'] = $this->home_model->get_teacher_count($campus['campus_id'],3,array(3),2);
				$_campus_data_box['edex_el'] = $this->home_model->get_teacher_count($campus['campus_id'],2,array(3),2);
				$_campus_data_box['iceat_el'] = $this->home_model->get_teacher_count($campus['campus_id'],1,array(3),2);
				$_campus_data_box['total_el'] = $this->home_model->get_teacher_count($campus['campus_id'],false,array(3),2);
				
				$_campus_data_box['ksu_inl'] = $this->home_model->get_teacher_count($campus['campus_id'],3,array(3));
				$_campus_data_box['edex_inl'] = $this->home_model->get_teacher_count($campus['campus_id'],2,array(3));
				$_campus_data_box['iceat_inl'] = $this->home_model->get_teacher_count($campus['campus_id'],1,array(3));
				$_campus_data_box['total_inl'] = $this->home_model->get_teacher_count($campus['campus_id'],false,array(3));
				
				$_campus_data_box['ksu_admin'] = $this->home_model->get_teacher_count($campus['campus_id'],3,'1,3,4',false);
				$_campus_data_box['edex_admin'] = $this->home_model->get_teacher_count($campus['campus_id'],2,'1,3,4',false);
				$_campus_data_box['iceat_admin'] = $this->home_model->get_teacher_count($campus['campus_id'],1,'1,3,4',false);
				$_campus_data_box['total_admin'] = $this->home_model->get_teacher_count($campus['campus_id'],false,'1,3,4',false);
				
				$_campus_data_box['ksu_total'] = $this->home_model->get_teacher_count($campus['campus_id'],3,'1,4',false);
				$_campus_data_box['edex_total'] = $this->home_model->get_teacher_count($campus['campus_id'],2,'1,4',false);
				$_campus_data_box['iceat_total'] = $this->home_model->get_teacher_count($campus['campus_id'],1,'1,4',false);
				$_campus_data_box['total_total'] = $this->home_model->get_teacher_count($campus['campus_id'],false,'1,4',false);
				
				$campus_data_box[$campus['campus_name']] = $_campus_data_box;
        	}
		}
		$content_data['campus_data_box'] = $campus_data_box;
		
		$content_data['total_male_teachers'] = $this->home_model->get_teacher_count(false,false,array(3),false);
		$content_data['total_female_teachers'] = $this->home_model->get_teacher_count(false,false,array(3),false,'F');
		$content_data['total_male_and_female_teachers'] = $this->home_model->get_teacher_count(false,false,array(3),false,false);
		$content_data['total_male_and_female_admin'] = $this->home_model->get_teacher_count(false,false,'1,3,4',false,false);
		$content_data['total_staff'] = $this->home_model->get_teacher_count(false,false,'1,4',false,false);
		
		$content_data['departure_stats'] = $this->home_model->departure_stats();
		$content_data['new_users'] = $this->home_model->new_users();
		
		$content_data['total_ksu_employees'] = $this->home_model->get_teacher_count(false,3,'1,4',false,false);
		$content_data['total_edex_employees'] = $this->home_model->get_teacher_count(false,2,'1,4',false,false);
		$content_data['total_iceat_employees'] = $this->home_model->get_teacher_count(false,1,'1,4',false,false);
		$content_data['total_employees'] = $this->home_model->get_teacher_count(false,false,'1,4',false,false);
		
		$content_data['nationality'] = $this->home_model->get_nationality();
		
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        $this->template->title('home page');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('homepage', $content_data);
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