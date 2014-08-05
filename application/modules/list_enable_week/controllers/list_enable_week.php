<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_enable_week extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
		$this->load->helper('general_function');
        $this->load->library('form_validation');
        $this->load->model('list_school_year/list_school_year_model');
		$this->load->model('list_school/list_school_model');
		
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

    public function index($order_by = "school_year_id", $sort_order = "asc", $search = "all", $offset = 0) {
		$content_data['school_year_list'] = get_school_year_list();
		$content_data['school_list'] = get_school_list();
		$content_data['course_subject'] = get_course_subject();		
		$content_data['course'] = get_course();		
		$content_data['teacher_list'] = get_teacher_list();		
		$content_data['course_category'] = get_course_category();	
		$content_data['shift'] = array('AM'=>'AM','PM'=>'PM');	
		$content_data['school_type'] = array('SEM' => 'Semester','QUA' => 'Quarter','YEAR' => 'Year');
		
        if (!is_numeric($offset)) {
            redirect('/list_enable_week');
        }

        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_school_year' => '', 's_school_year_title' => '', 's_school_type' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('school_year', 'School Year', 'trim|max_length[16]');
            $this->form_validation->set_rules('school_year_title', 'School Year Title', 'trim|max_length[40]');
            $this->form_validation->set_rules('school_type', 'School Type', 'trim|max_length[255]');

            if (empty($_POST['school_year']) && empty($_POST['school_year_title']) && empty($_POST['school_type'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_enable_week/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('school_year')) {
                    $this->session->set_flashdata('message', form_error('school_year'));
                }elseif (form_error('school_year_title')) {
                    $this->session->set_flashdata('message', form_error('school_year_title'));
                }elseif (form_error('school_type')) {
                    $this->session->set_flashdata('message', form_error('school_type'));
                }
                redirect('/list_enable_week/');
                exit();
            }

            $search_session = array(
                's_school_year'  => $this->input->post('school_year'),
                's_school_year_title'     => $this->input->post('school_year_title'),                
                's_school_type' => $this->input->post('school_type')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('list_enable_week/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('school_year' => $this->input->post('school_year'), 'school_year_title' => $this->input->post('school_year_title'), 'school_type' => $this->input->post('school_type'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_school_year_model->count_all_search_school_year($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }


        }elseif($search == "session") {
            $base_url = site_url('list_enable_week/index/'. $order_by .'/'. $sort_order .'/session');
             $search_data = array('school_year' => $this->input->post('school_year'), 'school_year_title' => $this->input->post('school_year_title'), 'school_type' => $this->input->post('school_type'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_school_year_model->count_all_search_school_year($search_data);
            $content_data['search'] = "session";

        }else{
            $unset_search_session = array('s_school_year' => '', 's_school_year_title' => '', 's_school_type' => '');
            $this->session->unset_userdata($unset_search_session);
            $content_data['total_rows'] = $config['total_rows'] = $this->list_school_year_model->count_all_school_year();
            $base_url = site_url('list_enable_week/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }

        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = $this->list_school_year_model->get_school_year($per_page, $offset, $order_by, $sort_order, $search_data);
        if (empty($data)) {
            redirect("/list_enable_week");
        }else{
            $content_data['school'] = $data;
        }
        if($data){
        	$wekkArr = array();
        	$dateArr = array();
        	$timeArr = array();
			$noOfDayArr = array();
        	foreach ($data->result() as $datas){
        		
        		$query = $this->list_school_year_model->get_enable_school_week($datas->school_year_id);
        		
        		if($query){
        			
        			foreach ($query->result() as $enable_week){
        				$wekkArr[$datas->school_year_id][] = $enable_week->week_id;
        				$dateArr[$datas->school_year_id.$enable_week->week_id][$enable_week->week_id] = date('Y-m-d', strtotime($enable_week->last_date));
        				$timeArr[$datas->school_year_id.$enable_week->week_id][$enable_week->week_id] = date('H:i:s', strtotime($enable_week->last_date));
						$noOfDayArr[$datas->school_year_id.$enable_week->week_id][$enable_week->week_id] = $enable_week->no_of_day;
        			}
        		}
        	}
        }
        $content_data['am_start_time'] = '';
        $content_data['am_time'] = '';
        $content_data['pm_start_time'] = '';
        $content_data['pm_time'] = '';

        $querytime = $this->list_school_year_model->get_enable_school_time();
        if($querytime){
        	foreach ($querytime->result() as $times){
        		$content_data['am_start_time'] = $times->am_start_time;
        		$content_data['am_time'] = $times->am_time;
        		$content_data['pm_start_time'] = $times->pm_start_time;
        		$content_data['pm_time'] = $times->pm_time;
        	}
        }
		
		$content_data['activation_time'] = '';

        $querytime = $this->list_school_year_model->get_activation_time();
		
        if($querytime){
        	foreach ($querytime->result() as $times){
        		$content_data['activation_time'] = $times->activation_time;
        	}
        }
        
        $content_data['pdf_export_type'] = "";
		$pdfdata = $this->list_school_year_model->get_attendance_pdf_settings();
		if($pdfdata){
			$content_data['pdf_export_type'] = $pdfdata->export_type;
        }
		
       //echo "<pre>";print_r($timeArr);exit;
        //$content_data['wekkArr'] = $wekkArr;
        $content_data['dateArr'] = $dateArr;
        $content_data['timeArr'] = $timeArr;
		$content_data['noOfDayArr'] = $noOfDayArr;
        $content_data['offset'] = $offset;
        $content_data['order_by'] = $order_by;
        $content_data['sort_order'] = $sort_order;
		
		$school_data = $this->list_school_model->get_school_data(1);
		$content_data['elsd_year'] = $school_data->elsd_year;
        $content_data['elsd_number'] = $school_data->elsd_number;	
        // set pagination config data
        $config['uri_segment'] = '7';
        $config['base_url'] = $base_url;
        $config['per_page'] = Settings_model::$db_config['members_per_page'];
        $config['prev_tag_open'] = ''; // removes &nbsp; at beginning of pagination output
        $this->pagination->initialize($config);

        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('List school Year');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_enable_week', $content_data);
    }

    public function action_enable_week($id, $offset, $order_by, $sort_order, $search) {
        if (isset($_POST) && is_array($_POST) && array_key_exists('add', $_POST)) {
            $this->_add_enable_week($id, $offset, $order_by, $sort_order, $search);
        }else{
            $this->_delete_school($id, $offset, $order_by, $sort_order, $search);
        }
    }

    public function action_add_time() {
    	$result = $this->list_school_year_model->add_enable_time($this->input->post('am_start'),$this->input->post('am'), $this->input->post('pm_start'), $this->input->post('pm'));
    	if($result){
    		redirect("/list_enable_week");
    	}
    }
    
	public function attendance_week_activation_time() {
    	$result = $this->list_school_year_model->attendance_week_activation_time($this->input->post('activation_time'));
    	if($result){
    		redirect("/list_enable_week");
    	}
    }
	
	/**
     *
     * _add_enable_week: add enable week info from add enable week
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _add_enable_week($id, $offset, $order_by, $sort_order, $search) {
        
        $this->list_school_year_model->add_enable_week($this->input->post('week'),$this->input->post('last_date'),$this->input->post('time'),$this->input->post('school_year_id'),$_POST);
        $this->session->set_flashdata('message', 'Enble weeek has been added successfully.');
        redirect('/list_enable_week/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
	
    public function attendance_generate_pdf() {
		$day1 = '';
		$day2 = '';
		$day3 = '';
		$day4 = '';
		$day5 = '';
		$date_title = '';
		$export_type = '';
		$export_type = $_POST["export_type"];
		
		$result = $this->list_school_year_model->add_attendance_pdf_settings($day1,$day2,$day3,$day4,$day5,$date_title,$export_type);
    	if($result){
    		redirect("/list_enable_week");
    	}
	}
	
	public function elsd_id_setting() {
		$elsd_year = $_POST["elsd_year"];
		$elsd_number = $_POST["elsd_number"];
		
		$result = $this->list_school_year_model->save_elsd_id_setting($elsd_year,$elsd_number);
    	redirect("/list_enable_week");
	}
}

/* End of file list_enable_week.php */