<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Department extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('department_model');
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
        
        $this->template->title('Department');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('department', $content_data);
    }
	
    public function index_json($order_by = "department_name", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('id','department_name','created_at','updated_at');
    	$grid_data = get_search_data($aColumns);
    	
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
		
		/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
    	$data = $this->department_model->get_departmentdata($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->department_model->get_departmentdata($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],true);
    
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
				$id = $result_row["id"];
				$department_name = $result_row["department_name"];
				$created_at = $result_row["created_at"];
				$updated_at = $result_row["updated_at"];
				
				$row[] = $id;
				$row[] = $department_name;
    			$row[] = $created_at;
				$row[] = $updated_at;
    			$row[] = $id;
				$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
    
    public function add($id = null){
    	$content_data['id'] = $id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->department_model->get_departmentdata_by_id($id);
    	}	
    	
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$department_name = $this->input->post('department_name');
			
			$data = array();
			$data['department_name'] = $department_name;
			
			$table = 'department';
    		$wher_column_name = 'id';					
    		if($id){
				$data['updated_at'] = date('Y-m-d H:i:s');
    			set_activity_data_log($id,'Update','Department','Edit department',$table,$wher_column_name,'');
    			grid_data_updates($data,$table,$wher_column_name,$id);    			
    		}else{
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Department','Add department',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_department', $content_data);
    }
	
	public function delete($id = null){
    	if($id){
			$table = 'department';
			$wher_column_name = 'id';
			set_activity_data_log($id,'Delete','Department > List Department','List Department',$table,$wher_column_name,$user_id='');
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/department/');
        exit();
	}
}
/* End of file department.php */