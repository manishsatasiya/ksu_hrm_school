<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Line_Managers_list extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->model('list_course/courses_model');
        $this->load->model('list_student/list_teacher_student_model');
		$this->load->model('line_managers_list_model');
		$this->load->helper('general_function');
		$this->load->model('add_privilege/privilege_model');
		$this->load->model('list_school/list_school_model');
		$this->load->model('my_inductions/my_inductions_model');
    }
    /*
     *
     * index
     */
    public function index() {
		
		if(isset($_POST['attendance']) && is_array($_POST['attendance']) && count($_POST['attendance'])){
			
			foreach($_POST['attendance'] as $user_id=>$attendance) {
				if($attendance <> ''){	
					$data['user_id'] = $user_id;
					$data['attendance'] = $attendance;
					$this->line_managers_list_model->set_user_attendance($data);
				}
			}
		}
		
    	$content_data = array();
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Line managers list');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('line_managers_list', $content_data);
    }
    
    public function index_json($order_by = "username", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('users.user_id',
						'staff_name',
						'users.elsd_id',
						'user_profile.job_title',
						'users.email',
						'users.personal_email',
						'users.cell_phone',
						'contractors.contractor');
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
		$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    
    	$data = $this->line_managers_list_model->get_line_managers($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->line_managers_list_model->get_line_managers(0, 0, "", "", $grid_data['search_data']);
		/*
    	 * Output
    	*/
    	$output = array(
    			"sEcho" => intval($_GET['sEcho']),
    			"iTotalRecords" => $count,
    			"iTotalDisplayRecords" => $count,
    			"aaData" => array()
    	);
    
    	if($data){
    		foreach($data->result_array() AS $result_row){
				$current_day = date('w');
				$attendance_dropdown = '';
				$line_manager_attendance_day = getTableField('school_campus', 'line_manager_attendance_day', 'campus_id',$this->session->userdata('campus_id'));
				if($line_manager_attendance_day <> '' && $line_manager_attendance_day == $current_day){
					$attendance_dropdown = '<select name="attendance['.$result_row['user_id'].']" style="width:100%;"><option value="">Select</option><option value="present">Present</option><option value="absent">Absent</option><option value="late">Late</option></select>';
				}
    			$row = array();
				$row[] = $result_row['user_id'];
				$row[] = $attendance_dropdown;
				$row[] = $result_row['staff_name'];
				$row[] = $result_row['elsd_id'];
				$row[] = $result_row['job_title'];
				$row[] = $result_row['personal_email'];
				$row[] = $result_row['email'];				
				$row[] = $result_row['cell_phone'];
				$row[] = $result_row['contractor'];
    			$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
    
    
}
/* End of file list_user.php */