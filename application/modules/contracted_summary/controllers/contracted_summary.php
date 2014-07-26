<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contracted_summary extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('contracted_summary_model');
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
        
        $this->template->title('Contracted summary');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('contracted_summary', $content_data);
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
		
    	$data = $this->contracted_summary_model->get_contracted_summary($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->contracted_summary_model->get_contracted_summary($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],true);
    
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
				$contractor_id = $result_row["contractor_id"];
				$campus_id = $result_row["campus_id"];
				$user_roll_id = $result_row["user_roll_id"];
				$contractor_name = $result_row["contractor_name"];
				$campus_name = $result_row["campus_name"];
				$user_roll_name = $result_row["user_roll_name"];
				$contracted_numbers = $result_row["contracted_numbers"];
				$created_at = $result_row["created_at"];
				$updated_at = $result_row["updated_at"];
				
				$actual_total_number = $this->contracted_summary_model->actual_total_number($contractor_id,$campus_id,$user_roll_id);
				$shortfall = $contracted_numbers - $actual_total_number;
				$row[] = $id;
				$row[] = $contractor_name;
				$row[] = $campus_name;
				$row[] = $user_roll_name;
				$row[] = $contracted_numbers;
    			$row[] = $actual_total_number;
				$row[] = $shortfall;
				$row[] = $updated_at;
				$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
    
    
}
/* End of file contracted_summary.php */