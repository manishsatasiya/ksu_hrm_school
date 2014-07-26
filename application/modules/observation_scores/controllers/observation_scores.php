<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Observation_scores extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->model('observation_scores_model');
    }

    public function index() {
        $content_data = array();
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('List Teacher');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('observation_scores', $content_data);
    }
    
    public function index_json($order_by = "username", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('first_name','elsd_id','obs_detail.created_at','obs_detail.created_by','obs_detail.score1','obs_detail.comment');
    	
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
		$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];

    	$data = $this->observation_scores_model->get_observations($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->observation_scores_model->get_observations($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],0,true);
    
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
				$row[] = $result_row["first_name"];    			
    			$row[] = $result_row["elsd_id"]; 
    			$row[] = $result_row["obs_date"];
				$row[] = $result_row["observer_name"];
				if($result_row["score1"] <> '')
					$row[] = $result_row["score1"];
				else
					$row[] = 'TBA';
					
				$row[] = $result_row["comment"];
    			
    			$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
	
}

/* End of file observation_scores.php */
