<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_other_staff extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->model('list_course/courses_model');
        $this->load->model('list_student/list_teacher_student_model');
		//$this->load->model('list_user_model');
		$this->load->helper('general_function');
		$this->load->model('add_privilege/privilege_model');
		
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

    public function index($order_by = "username", $sort_order = "asc", $search = "all", $offset = 0) {
    	$content_data = array();
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('List User');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_other_staff', $content_data);
    }
    
    public function index_json($order_by = "username", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('users.user_id',
						'users.elsd_id',
						'staff_name',
						'users.email',
						'users.personal_email',
						'users.cell_phone',
						'contractors.contractor',
						'users.status',
						'user_roll.user_roll_name',
						'department.department_name',
						'school_campus.campus_name',
						'user_profile.scanner_id',
						'user_profile.returning',
						'users.created_date',
						'users.updated_date');
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
		$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    
    	$data = $this->list_teacher_student_model->get_staff_members("otherstaff",$per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_teacher_student_model->get_staff_members("otherstaff",0, 0, "", "", $grid_data['search_data']);
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
    			$row = array();
    			$row[] = $result_row['user_id'];
				$row[] = $result_row['elsd_id'];
				$row[] = $result_row['staff_name'];
				$row[] = $result_row['email'];
				$row[] = $result_row['personal_email'];
				$row[] = $result_row['cell_phone'];
				$row[] = $result_row['contractor'];
				$row[] = $result_row['status'];
				$row[] = $result_row['user_roll_name'];
				$row[] = $result_row['department_name'];
				$row[] = $result_row['campus_name'];
				$row[] = $result_row['scanner_id'];
				$row[] = $result_row['returning'];
				$row[] = $result_row['created_date'];
				$row[] = $result_row['updated_date'];
    			$row[] = $result_row["user_id"];
    			$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
    	
}

/* End of file list_user.php */