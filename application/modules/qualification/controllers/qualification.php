<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class qualification extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('qualification_model');
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
        
        $this->template->title('qualification');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('qualification', $content_data);
    }
	
    public function index_json($order_by = "qualification", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('id','qualification','type');
    	$grid_data = get_search_data($aColumns);
    	
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
		
		/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
    	$data = $this->qualification_model->get_qualificationdata($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->qualification_model->get_qualificationdata($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],true);
    
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
				$qualification = $result_row["qualification"];
				$type = $result_row["type"];
				$show_in_datatable = $result_row["show_in_datatable"];
				$datatable_display_order = $result_row["datatable_display_order"];
								
				$row[] = $id;
				$row[] = $qualification;
    			$row[] = $type;
				$row[] = $show_in_datatable;
				$row[] = $datatable_display_order;
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
    		$rowdata = $this->qualification_model->get_qualificationdata_by_id($id);
    	}	
    	
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$type = $this->input->post('type');
    		$qualification = $this->input->post('qualification');
			$show_in_datatable = $this->input->post('show_in_datatable');
			$datatable_display_order = $this->input->post('datatable_display_order');
			
			$data = array();
			$data['type'] = $type;
			$data['qualification'] = $qualification;
			$data['show_in_datatable'] = $show_in_datatable;
			$data['datatable_display_order'] = $datatable_display_order;
			
			$table = 'qualifications';
    		$wher_column_name = 'id';					
    		if($id){
    			set_activity_data_log($id,'Update','qualification','Edit qualification',$table,$wher_column_name,'');
    			grid_data_updates($data,$table,$wher_column_name,$id);    			
    		}else{
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','qualification','Add qualification',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_qualification', $content_data);
    }
	
	public function delete($id = null){
    	if($id){
			$table = 'qualifications';
			$wher_column_name = 'id';
			set_activity_data_log($id,'Delete','qualification > List qualification','List qualification',$table,$wher_column_name,$user_id='');
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/qualification/');
        exit();
	}
}
/* End of file qualification.php */