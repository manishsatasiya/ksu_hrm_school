<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View_attendance_weekly_report extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
		$this->load->helper('general_function');
        $this->load->library('form_validation');
        $this->load->model('view_attendance_weekly_report_model');
		$this->output->enable_profiler(FALSE);
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
	
	public function generatereport()
	{
		ini_set('memory_limit','2024M');
		$data = $this->view_attendance_weekly_report_model->get_weekly_data_for_report();
		redirect('/view_attendance_weekly_report');
	}
    public function index($type = "number") {
    	$school_year_list = get_school_year_list();
    	$campus_list = get_campus_list();
		
    	$content_data = array();
    	$school_year_id = '';
    	$campus_id = '';
    	$week = '';
    	
    	if (!empty($_POST['school_year_id']) || !empty($_POST['week'])) {
    		$school_year_id = $this->input->post('school_year_id');
    		$campus_id = $this->input->post('campus_id');
    		$week = $this->input->post('week');
    	}else{
    		if($school_year_list){
    			$tmparr = array_keys($school_year_list);
    			$tmparrc = array_keys($campus_list);
    			
    			$school_year_id = $tmparr[1];
    			$campus_id = $tmparrc[0];
    			$week = 1;
    		}
    	}
    	
    	$data = $this->view_attendance_weekly_report_model->get_report_data($school_year_id,$week,$campus_id);
    	$content_data['data'] = $data;
    	$content_data['type'] = $type;
    	$content_data['school_week'] = $this->view_attendance_weekly_report_model->get_school_enable_week();
    	$content_data['school_year_list'] = $school_year_list;
		$content_data['campus_list'] = $campus_list;
    	$content_data['school_year_id'] = $school_year_id;
    	$content_data['campus_id'] = $campus_id;
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('KSU weekly report');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('view_attendance_weekly_report', $content_data);
        
    }

    function getweek(){
    
    	$id = $this->input->post('id');
    	
    	$week_data = $this->view_attendance_weekly_report_model->getweek($id);
    	$week_arr = array();
    	$week_arr[0] = '--Select--';
    	foreach ($week_data as $week_datas){
    		$week_arr[$week_datas['week_id']] = "Week ".$week_datas['week_id'];
    	}
    	print "Week:- ".form_dropdown('week',$week_arr,'0','id="reg_week" class="input_text1 qtip_week"');
    	exit;
    
    }
    
    public function export_to_excel()
    {
    	$content_data = array();
    	$type = "number";
    	
    	$school_year_id = (int)$this->uri->segment(3);
    	$week = (int)$this->uri->segment(4);
    	$campus_id = (int)$this->uri->segment(5);
    	
    	if($school_year_id == '' || $school_year_id == '0'){
    		$school_year_id = '';
    	}
    	if($week == '' || $week == '0'){
    		$week = '';
    	}
    	
    	$data = $this->view_attendance_weekly_report_model->get_report_data($school_year_id,$week,$campus_id);
    	$content_data['data'] = $data;
    	$content_data['type'] = $type;
    	$this->template->build('export_ksu_report', $content_data);
    }
}

/* End of file list_enable_week.php */