<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Job_title extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('job_title_model');
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
        
        $this->template->title('Job_title');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('job_title', $content_data);
    }
	
    public function index_json($order_by = "job_title", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('job_title_id','job_title','created_at','updated_at');
    	$grid_data = get_search_data($aColumns);
    	
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
		
		/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
		
    	$data = $this->job_title_model->get_job_titledata($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->job_title_model->get_job_titledata($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],true);
    
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
				$job_title_id = $result_row["job_title_id"];
				$job_title = $result_row["job_title"];
				$created_at = $result_row["created_at"];
				$updated_at = $result_row["updated_at"];
				
				$row[] = $job_title_id;
				$row[] = $job_title;
    			$row[] = $created_at;
				$row[] = $updated_at;
    			$row[] = $job_title_id;
				$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
    
    public function add($job_title_id = null){
    	$content_data['job_title_id'] = $job_title_id;
    	$rowdata = array();
    	if($job_title_id){
    		$rowdata = $this->job_title_model->get_job_titledata_by_id($job_title_id);
    	}	
    	
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$job_title = $this->input->post('job_title');
			
			$data = array();
			$data['job_title'] = $job_title;
			
			$table = 'job_title';
    		$wher_column_name = 'job_title_id';					
    		if($job_title_id){
				$data['updated_at'] = date('Y-m-d H:i:s');
    			set_activity_data_log($job_title_id,'Update','Job_title','Edit job_title',$table,$wher_column_name,'');
    			grid_data_updates($data,$table,$wher_column_name,$job_title_id);    			
    		}else{
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Job_title','Add job_title',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_job_title', $content_data);
    }
	
	public function delete($job_title_id = null){
    	if($job_title_id){
			$table = 'job_title';
			$wher_column_name = 'job_title_id';
			set_activity_data_log($job_title_id,'Delete','Job_title > List Job_title','List Job_title',$table,$wher_column_name,$user_id='');
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$job_title_id);
    	}
		redirect('/job_title/');
        exit();
	}
}
/* End of file job_title.php */