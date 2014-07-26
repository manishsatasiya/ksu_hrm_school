<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Duties extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('duties_model');
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
        
        $this->template->title('duties');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('duties', $content_data);
    }
	
    public function index_json($order_by = "user_roll_name", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('id','user_roll_name','duties','created_at','updated_at');
    	$grid_data = get_search_data($aColumns);
    	
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
		
		/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
    	$data = $this->duties_model->get_dutiesdata($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->duties_model->get_dutiesdata($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],true);
    
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
				$user_roll_name = $result_row["user_roll_name"];
				$duties = $result_row["duties"];
				$created_at = $result_row["created_at"];
				$updated_at = $result_row["updated_at"];
				
				$row[] = $id;
				$row[] = $user_roll_name;
				$row[] = $duties;
    			$row[] = $created_at;
				$row[] = $updated_at;
    			$row[] = $id;
				$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
    
    public function add($id = null){
		$content_data['other_user_roll'] = get_other_user_roll();
    	$content_data['id'] = $id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->duties_model->get_dutiesdata_by_id($id);
    	}	
    	
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$user_roll_id = $this->input->post('user_roll_id');
    		$duties = $this->input->post('duties');
			$user_id = $this->session->userdata('user_id');
			
			$data = array();
			$data['user_roll_id'] = $user_roll_id;
			$data['duties'] = $duties;
			
			$table = 'duties';
    		$wher_column_name = 'id';					
    		if($id){
				$data['updated_at'] = date('Y-m-d H:i:s');
    			set_activity_data_log($id,'Update','Duties','Edit duties',$table,$wher_column_name,'');
    			grid_data_updates($data,$table,$wher_column_name,$id);    			
    		}else{
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Duties','Add duties',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_duties', $content_data);
    }
	
	public function delete($id = null){
    	if($id){
			$table = 'duties';
			$wher_column_name = 'id';
			set_activity_data_log($id,'Delete','Duties > List Duties','List Duties',$table,$wher_column_name,$user_id='');
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/duties/');
        exit();
	}
}
/* End of file duties.php */