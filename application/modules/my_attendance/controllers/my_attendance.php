<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class My_attendance extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('my_attendance_model');
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
        
        $this->template->title('My Attendance');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('my_attendance', $content_data);
    }
	
    public function index_json($user_unique_id=0,$order_by = "LogDate", $sort_order = "desc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('id','dwEnrollNumber','Logdate','InTime','OutTime','TotalHours','Late','Approved','StartTime','EndTime');
    	$grid_data = get_search_data($aColumns);
    	
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
    	
    	if($order_by == "")
    	{
    		$order_by = "LogDate";
    		$sort_order = "desc";
    	}
    	
		/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
    	$data = $this->my_attendance_model->get_my_attendance($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],false,$user_unique_id);
    	$count = $this->my_attendance_model->get_my_attendance($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],true,$user_unique_id);
    
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
				$dwEnrollNumber = $result_row["dwEnrollNumber"];
				$Logdate = $result_row["Logdate"];
				$InTime = $result_row["InTime"];
				$OutTime = $result_row["OutTime"];
				$TotalHours = $result_row["TotalHours"];
				$Late = $result_row["Late"];
				$Approved = $result_row["Approved"];
				$StartTime = $result_row["StartTime"];
				$EndTime = $result_row["EndTime"];
				
				$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
}
/* End of file department.php */