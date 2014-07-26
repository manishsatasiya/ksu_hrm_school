<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Nationality extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('nationality_model');
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
        
        $this->template->title('nationality');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('nationality', $content_data);
    }
	
    public function index_json($order_by = "nationality", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('id','nationality','native');
    	$grid_data = get_search_data($aColumns);
    	
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
		
		/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
    	$data = $this->nationality_model->get_nationalitydata($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->nationality_model->get_nationalitydata($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],true);
    
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
				$nationality = $result_row["nationality"];
				$native = $result_row["native"];
				
				$row[] = $id;
				$row[] = $nationality;
    			$row[] = $native;
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
    		$rowdata = $this->nationality_model->get_nationalitydata_by_id($id);
    	}	
    	
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$native = $this->input->post('native');
    		$nationality = $this->input->post('nationality');
			
			$data = array();
			$data['native'] = $native;
			$data['nationality'] = $nationality;
			
			$table = 'countries';
    		$wher_column_name = 'id';					
    		if($id){
    			set_activity_data_log($id,'Update','Nationality','Edit nationality',$table,$wher_column_name,'');
    			grid_data_updates($data,$table,$wher_column_name,$id);    			
    		}else{
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Nationality','Add nationality',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_nationality', $content_data);
    }
	
	public function delete($id = null){
    	if($id){
			$table = 'countries';
			$wher_column_name = 'id';
			set_activity_data_log($id,'Delete','Nationality > List Nationality','List Nationality',$table,$wher_column_name,$user_id='');
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/nationality/');
        exit();
	}
}
/* End of file nationality.php */