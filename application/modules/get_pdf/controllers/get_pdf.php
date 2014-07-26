<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Get_pdf extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->model('get_pdf_model');
		$this->load->model('list_course/courses_model');
		$this->load->model('list_school_year/list_school_year_model');
    }

    /**
     *
     * index
     *
     */

    public function index() {
		$content_data['school_campus'] = $this->get_pdf_model->get_campus_pdf();
        
		if($this->session->userdata('campus_id') > 0)
			$content_data['school_campus'] = $this->get_pdf_model->get_campus_pdf($this->session->userdata('campus_id'));
			
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
			$content_data['section'] = $this->get_campus_section($this->session->userdata('campus_id'),1);
		if($this->session->userdata('role_id') == '3')
		{
			redirect("get_pdf/createpdf/index/".$this->session->userdata('campus_id')."/attendance_pdf_".$this->session->userdata('user_id'));
			$am_start_time = '';
			$am_time = '';
			$pm_start_time = '';
			$pm_time = '';
			
			$querytime = $this->get_pdf_model->get_enable_school_time();
			if($querytime){
				foreach ($querytime->result() as $times){
					$am_start_time = $times->am_start_time;
					$am_time = $times->am_time;
					$pm_start_time = $times->pm_start_time;
					$pm_time = $times->pm_time;
				}
			}
			
			$arrActivateTime =$this->get_pdf_model->get_activation_time();
			$activate_time = "00:00:00";
			
			if(isset($arrActivateTime[0]["activation_time"]))
				$activate_time = $arrActivateTime[0]["activation_time"];
				
			$enable_week = $this->get_pdf_model->get_enableweek($activate_time);
			
			$pdfdata = $this->list_school_year_model->get_attendance_pdf_settings();
			if($pdfdata){
				$content_data['pdf_week'] = $enable_week[0]["week_id"];
				$content_data['pdf_am_start'] = $am_start_time;
				$content_data['pdf_am_end'] = $am_time;
				$content_data['pdf_pm_start'] = $pm_start_time;
				$content_data['pdf_pm_end'] = $pm_time;
				$content_data['pdf_day1'] = date('m/d/Y',strtotime($pdfdata->day1));
				$content_data['pdf_day2'] = date('m/d/Y',strtotime($pdfdata->day2));
				$content_data['pdf_day3'] = date('m/d/Y',strtotime($pdfdata->day3));
				$content_data['pdf_day4'] = date('m/d/Y',strtotime($pdfdata->day4));
				$content_data['pdf_day5'] = date('m/d/Y',strtotime($pdfdata->day5));
				$content_data['pdf_date_title'] = date('m/d/Y',strtotime($pdfdata->date_title));
				$content_data['pdf_export_type'] = $pdfdata->export_type;
			}
			else
			{
				$content_data['pdf_week'] = "";
				$content_data['pdf_am_start'] = "";
				$content_data['pdf_am_end'] = "";
				$content_data['pdf_pm_start'] = "";
				$content_data['pdf_pm_end'] = "";
				$content_data['pdf_day1'] = "";
				$content_data['pdf_day2'] = "";
				$content_data['pdf_day3'] = "";
				$content_data['pdf_day4'] = "";
				$content_data['pdf_day5'] = "";
				$content_data['pdf_date_title'] = "";
				$content_data['pdf_export_type'] = "normal";
			}
		}
        $this->template->title('Get PDF');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('get_pdf', $content_data);        
    }
	
	function get_campus_section($campus_id,$dropdown=0)
	{
		$rec = $this->get_pdf_model->get_course_class_section($campus_id);
		$dropdown_arr = '';
		if(isset($rec))
		{
			$dropdown_data = $rec->result_array();
			
			if($dropdown == 1)
			{
				$dropdown_arr = array();
				$dropdown_arr[0] = '--All--';
				foreach ($dropdown_data as $dropdown_datas){
					$dropdown_arr[$dropdown_datas['section_id']."j"] = $dropdown_datas['section_title'];    		
				}
				return $dropdown_arr;
			}
			else
			{
				$dropdown_arr .= '<option value="0">--All--</option>';
				if(count($dropdown_data) > 0){
					foreach ($dropdown_data as $dropdown_datas){
						$dropdown_arr .= '<option value="'.$dropdown_datas['section_id']."j".'">'.$dropdown_datas['section_title'].'</option>';   		
					}
				}
				echo $dropdown_arr;
				exit;
			}	
		}
	}
    
}

/* End of file list_members.php */