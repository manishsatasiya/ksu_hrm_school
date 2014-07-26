<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contracted_numbers extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('contracted_numbers_model');
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
        
        $this->template->title('Contracted numbers');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('contracted_numbers', $content_data);
    }
	
    public function index_json($order_by = "contractor_id", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('id','contractor_id','campus_id','user_roll_name','contracted_numbers','created_at','updated_at');
    	$grid_data = get_search_data($aColumns);
    	
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
		
		/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
    	$data = $this->contracted_numbers_model->get_contracted_numbers($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->contracted_numbers_model->get_contracted_numbers($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],true);
    
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
				$contractor_name = $result_row["contractor_name"];
				$campus_name = $result_row["campus_name"];
				$user_roll_name = $result_row["user_roll_name"];
				$contracted_numbers = $result_row["contracted_numbers"];
				$created_at = $result_row["created_at"];
				$updated_at = $result_row["updated_at"];
				
				$row[] = $id;
				$row[] = $contractor_name;
				$row[] = $campus_name;
				$row[] = $user_roll_name;
				$row[] = $contracted_numbers;
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
		$content_data['campus'] = get_campus();
		$content_data['contractors'] = get_contractors();
    	$content_data['id'] = $id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->contracted_numbers_model->get_contracted_numbersdata_by_id($id);
    	}	
    	
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$contractor_id = $this->input->post('contractor_id');
			$campus_id = $this->input->post('campus_id');
			$user_roll_id = $this->input->post('user_roll_id');
    		$contracted_numbers = $this->input->post('contracted_numbers');
						
			$data = array();
			$data['contractor_id'] = $contractor_id;
			$data['campus_id'] = $campus_id;
			$data['user_roll_id'] = $user_roll_id;
			$data['contracted_numbers'] = $contracted_numbers;
			
			$table = 'contracted_numbers';
    		$wher_column_name = 'id';					
    		if($id){
				$data['updated_at'] = date('Y-m-d H:i:s');
    			set_activity_data_log($id,'Update','Contracted_numbers','Edit contracted_numbers',$table,$wher_column_name,'');
    			grid_data_updates($data,$table,$wher_column_name,$id);    			
    		}else{
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Contracted_numbers','Add contracted_numbers',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_contracted_numbers', $content_data);
    }
	
	public function delete($id = null){
    	if($id){
			$table = 'contracted_numbers';
			$wher_column_name = 'id';
			set_activity_data_log($id,'Delete','Contracted_numbers > List Contracted_numbers','List Contracted_numbers',$table,$wher_column_name,$user_id='');
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/contracted_numbers/');
        exit();
	}
}
/* End of file contracted_numbers.php */