<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profile_comment extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('profile_comment_model');
		$this->load->model('list_course/courses_model');
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
    public function index() {
    	$content_data = array();
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Comments');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('profile_comment', $content_data);
    }
	
    public function index_json($order_by = "username", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('profile_notes.id',
						'staff_name',
						'note_type',
						'profile_notes.department',
						'recommendation',
						'detail',
						'profile_notes.created_by',
						'profile_notes.created_at');
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
		$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    
    	$data = $this->profile_comment_model->get_profile_comment($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->profile_comment_model->get_profile_comment(0, 0, "", "", $grid_data['search_data']);
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
				$row[] = $result_row['id'];
				$row[] = $result_row['staff_name'];
				$row[] = $result_row['note_type'];
				$row[] = $result_row['department_name'];
				$row[] = profile_comment_recommendation($result_row['recommendation']);
				$row[] = (strlen($result_row['detail']) > 20) ? substr($result_row['detail'],0,20).'...' : $result_row['detail'];				
				$row[] = $result_row['created_name'];
				$row[] = $result_row['created_at'];
				$row[] = $result_row['id'];
    			$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
	
	public function add($id = null){
    	$content_data['department_list'] = get_department_list();
    	$content_data['note_type_list'] = profile_comment_note_type();
		$content_data['recommendation_list'] = profile_comment_recommendation();		
    	$content_data['id'] = $id;

    	$rowdata = array();
    	if($id){
    		$rowdata = $this->profile_comment_model->get_profile_note($id);
    	}
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
			$action_by = $this->session->userdata('user_id');    		
			$note_type = $this->input->post('note_type');
    		$department = $this->input->post('department');
			$recommendation = $this->input->post('recommendation');
			$show_to_employee = $this->input->post('show_to_employee');
			$detail = $this->input->post('detail');
			
			$data = array();
			//$data['user_id'] = $user_id;
			$data['note_type'] = $note_type;
			$data['department'] = $department;
			$data['recommendation'] = $recommendation;
			$data['show_to_employee'] = $show_to_employee;
			$data['detail'] = $detail;
				
			$error = "";
    		$error_seperator = "<br>";
			$table = 'profile_notes';
    		$wher_column_name = 'id';
    		if($id){
				$data['updated_by'] = $action_by;
    			$data['updated_at'] = date('Y-m-d H:i:s');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    		}else{
				$data['created_by'] = $action_by;
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    		}
    		exit;
    	}
    	$this->template->build('add_note', $content_data);
    }
	
	public function view_detail($id = null){
    	$user_id = $this->session->userdata('user_id');	
		
		$content_data['id'] = $id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->profile_comment_model->get_profile_note($id);
    	}
    	$content_data['rowdata'] = $rowdata;

    	$this->template->build('view_detail', $content_data);
    }
}
/* End of file workshops.php */