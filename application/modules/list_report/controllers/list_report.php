<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_report extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('list_report_model');
		$this->load->helper('general_function');
		
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

    public function index($order_by = "user_activity_log.created_date", $sort_order = "desc", $search = "all", $offset = 0) {
        if (!is_numeric($offset)) {
            redirect('/list_report');
        }
        
        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_parent_menu' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('parent_menu', 'parent menu', 'trim|max_length[16]');
            
            if (empty($_POST['parent_menu'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_report/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('parent_menu')) {
                    $this->session->set_flashdata('message', form_error('parent_menu'));
                }
                redirect('/list_report/');
                exit();
            }

            $search_session = array(
                's_parent_menu'  => $this->input->post('parent_menu')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('list_report/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('parent_menu' => $this->input->post('parent_menu'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_report_model->count_all_search_report($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }


        }elseif($search == "session") {
            $base_url = site_url('list_report/index/'. $order_by .'/'. $sort_order .'/session');
             $search_data = array('parent_menu' => $this->input->post('parent_menu'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_report_model->count_all_search_report($search_data);
            $content_data['search'] = "session";

        }else{
            $unset_search_session = array('s_parent_menu' => '');
            $this->session->unset_userdata($unset_search_session);
            $content_data['total_rows'] = $config['total_rows'] = $this->list_report_model->count_all_report(array());
            $base_url = site_url('list_report/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }
        
        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = $this->list_report_model->get_activity_log($per_page, $offset, $order_by, $sort_order, $search_data);
       	if (empty($data)) {
            //redirect("/list_report");
        }else{
            $content_data['report'] = $data;
        }
        
        $content_data['offset'] = $offset;
        $content_data['order_by'] = $order_by;
        $content_data['sort_order'] = $sort_order;

        // set pagination config data
        $config['uri_segment'] = '7';
        $config['base_url'] = $base_url;
        $config['per_page'] = Settings_model::$db_config['members_per_page'];
        $config['prev_tag_open'] = ''; // removes &nbsp; at beginning of pagination output
        $this->pagination->initialize($config);

        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('List Report');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_report', $content_data);
    }
    
    public function index_json($order_by = "user_activity_log.created_date", $sort_order = "desc", $search = "all", $offset = 0){
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array( 'data_id','first_name','parent_menu','sub_menu','action','user_ip','created_date','user_activity_id');
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order']; 
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    
    	$data = $this->list_report_model->get_activity_log($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_report_model->count_all_report($grid_data['search_data']);
    
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
    			$row[] = $result_row["data_id"];
    			$row[] = $result_row["first_name"];
    			$row[] = $result_row["parent_menu"];
    			$row[] = $result_row["sub_menu"];
    			$row[] = $result_row["action"];
    			$row[] = $result_row["user_ip"];
    			$row[] = $result_row["created_date"];
    			$row[] = $result_row["user_activity_id"];
    			$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }

	public function viewlog($id)
	{
		$arrLogData = get_log_data_field_array();
		$data = $this->list_report_model->get_viewactivity_log($id);
		$content_data["data_array"] = $data[0]["data_array"];
		if (array_key_exists($data[0]["tablename"], $arrLogData)) {
		$content_data["arrLogData"] = $arrLogData[$data[0]["tablename"]];
		}
		$this->template->build('view_log_report', $content_data);
	}
	
    public function action_school($id, $offset, $order_by, $sort_order, $search) {
        if (array_key_exists('update', $_POST)) {
            $this->_update_school($id, $offset, $order_by, $sort_order, $search);
        }else{
            $this->_delete_school($id, $offset, $order_by, $sort_order, $search);
        }
    }

    /**
     *
     * _update_member: update member info from school
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _update_school($id, $offset, $order_by, $sort_order, $search) {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('email', 'email', 'trim|required|max_length[255]|is_valid_email|is_existing_unique_field[users.email]');        
        $this->form_validation->set_rules('school_name', 'School name', 'trim|required');
        $this->form_validation->set_rules('principal', 'principal', 'trim|required');
		

        $username = $this->list_report_model->get_parent_menu_by_id($id);
        
        if (!$this->form_validation->run()) {
            if (form_error('school_name')) {
                $this->session->set_flashdata('message', form_error('school_name'));
            }elseif (form_error('email')) {
                $this->session->set_flashdata('message', form_error('email'));
            }elseif (form_error('principal')) {
                $this->session->set_flashdata('message', form_error('principal'));
            }
            redirect('/list_report/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }

        $this->list_report_model->update_school($this->input->post('school_id'),$this->input->post('school_name'), $this->input->post('address'), $this->input->post('city'), $this->input->post('state'), $this->input->post('zip'), $this->input->post('area_code'),$this->input->post('phone'),$this->input->post('principal'),$this->input->post('www_address'),$this->input->post('email'));
        set_activity_log($this->input->post('school_id'),'update','school','list school');
        $this->session->set_flashdata('message', sprintf($this->lang->line('school_updated'), $this->input->post('school_name'), $this->input->post('school_id')));
        redirect('/list_report/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

     /**
     *
     * _delete_member: delete member from school
     *
     * @param int $id the id of the member to be deleted
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _delete_school($id, $offset, $order_by, $sort_order, $search) {
        $username = $this->list_report_model->get_parent_menu_by_id($id);
        if ($username == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noremove'));
        }elseif ($this->list_report_model->delete_member($id)) {
            $this->session->set_flashdata('message', sprintf($this->lang->line('school_deleted'), $username, $id));
        }
        redirect('/list_report/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

    
}

/* End of file list_members.php */