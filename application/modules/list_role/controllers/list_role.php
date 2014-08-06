<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_role extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('list_course/courses_model');
        $this->load->model('list_role_model');
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

    public function index($order_by = "user_roll_name", $sort_order = "asc", $search = "all", $offset = 0) {
        if (!is_numeric($offset)) {
            redirect('/list_role');
        }

        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_role_title' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('section_title', 'Section', 'trim|max_length[16]');
            

            if (empty($_POST['user_roll_name'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_role/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('section_title')) {
                    $this->session->set_flashdata('message', form_error('section_title'));
                }
                redirect('/list_role/');
                exit();
            }

            $search_session = array(
                's_role_title'  => $this->input->post('section_title')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('list_role/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('section_title' => $this->input->post('section_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->courses_model->count_all_search_section($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }


        }elseif($search == "session") {
            $base_url = site_url('list_role/index/'. $order_by .'/'. $sort_order .'/session');
             $search_data = array('section_title' => $this->input->post('section_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->courses_model->count_all_search_section($search_data);
            $content_data['search'] = "session";

        }else{
            $unset_search_session = array('s_role_title' => '');
            $this->session->unset_userdata($unset_search_session);
			$search_data = array('section_title' => $this->input->post('section_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->courses_model->count_all_search_section($search_data);
            $base_url = site_url('list_section/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }

        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = array();
        if (empty($data)) {
            //redirect("/list_role");
        }else{
            $content_data['sections'] = $data;
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
        
        $this->template->title('List Section');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_role', $content_data);
    }
    
    public function index_json($order_by = "user_roll_name", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array( 'user_roll_id','user_roll_name' );
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    	
    	$data = $this->list_role_model->get_role($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_role_model->count_all_role_grid($grid_data['search_data']);
    	 
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
    			$row[] = $result_row["user_roll_id"];
    			$row[] = $result_row["user_roll_name"];
    			$row[] = $result_row["user_roll_id"];
    			$output['aaData'][] = $row;
    		}
    	}
    	
    	echo json_encode( $output );
    }
    
    public function add($id = null){
    	$content_data['id'] = $id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_role_model->get_role_data($id);
    	}
    
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$user_roll_name = $this->input->post('user_roll_name');
    		$is_ca_lead = $this->input->post('is_ca_lead');
			$dashboard_page = $this->input->post('dashboard_page');
    		$error = "";
    		$error_seperator = "<br>";
    		if($id){
    
    			$this->form_validation->set_rules('user_roll_name', 'Role Name', 'trim|required|is_existing_field[user_roll.user_roll_name^user_roll.user_roll_id !=^'.$id.']');
    
    			if (!$this->form_validation->run()) {
    				if (form_error('user_roll_name')) {
    					$error .= form_error('user_roll_name').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    			 
    			$data = array();
    			$data['user_roll_name'] = $user_roll_name;
    			$data['is_ca_lead'] = $is_ca_lead;
				$data['dashboard_page'] = $dashboard_page;
    			$table = 'user_roll';
    			$wher_column_name = 'user_roll_id';
    			set_activity_data_log($id,'Update','Role & User > List Role','List Role',$table,$wher_column_name,$user_id='');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
    
    			$this->form_validation->set_rules('user_roll_name', 'Role', 'trim|required|is_existing_unique_field[user_roll.user_roll_name]');
    
    			if (!$this->form_validation->run()) {
    				if (form_error('user_roll_name')) {
    					$error .= form_error('user_roll_name').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    
    			$data = array();
    			$data['user_roll_name'] = $user_roll_name;
    			$data['is_ca_lead'] = $is_ca_lead;
				$data['dashboard_page'] = $dashboard_page;
    			$table = 'user_roll';
    			$wher_column_name = 'user_roll_id';
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Role & User > List Role','List Role',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_role_datatable', $content_data);
    }

    public function add_role(){
    	//Post data
    	$user_roll_name = $this->input->post('user_roll_name');
    
    	$data = array();
    	$data['user_roll_name'] = $user_roll_name;
    
    	//Table name
    	$table = 'user_roll';
    
    	grid_add_data($data,$table);
    }
    
    public function update_role(){
    	$error = "";
    	$error_seperator = "<br>";
    	
    	$value = $this->input->post('value');
    	$columnName = $this->input->post('columnName');
    	$id = $this->input->post('id');
    	$tablename = 'user_roll';
    	$whrid_column = 'user_roll_id';
    	
    	if($columnName != '') $_POST[$columnName] = $value;
    	$this->form_validation->set_error_delimiters('', '');
    	if ($columnName == 'user_roll_name')
    		$this->form_validation->set_rules('user_roll_name', 'Role Name', 'trim|required|is_existing_field[user_roll.user_roll_name^user_roll.user_roll_id !=^'.$id.']');
    	
    	
    	if (!$this->form_validation->run()) {
    		if (form_error('user_roll_name')) {
    			$error .= form_error('user_roll_name').$error_seperator;
    		}
    		if($error <> ""){
    			echo $error;
    			exit();
    		}
    	}
    	set_activity_data_log($id,'Update','Role & User > List Role','List Role',$tablename,$whrid_column,$user_id='');
    	grid_update_data($whrid_column,$id,$columnName,$value,$tablename);
    }
    
    public function action_role($id, $offset, $order_by, $sort_order, $search) {
        if (array_key_exists('update', $_POST)) {
            $this->_update_role($id, $offset, $order_by, $sort_order, $search);
        }else{
            $this->_delete_role($id, $offset, $order_by, $sort_order, $search);
        }
    }

    /**
     *
     * _update_role: update section info from school
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _update_role($id, $offset, $order_by, $sort_order, $search) {
        $this->form_validation->set_error_delimiters('', '');             
        $this->form_validation->set_rules('section_title', 'Section', 'trim');
        
        if (!$this->form_validation->run()) {
            if (form_error('section_title')) {
                $this->session->set_flashdata('message', form_error('section_title'));
            }
            redirect('/list_role/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }

        $this->courses_model->update_role($this->input->post('section_id'),$this->input->post('section_title'));
        set_activity_log($this->input->post('section_id'),'update','course','list section');
        $this->session->set_flashdata('message', sprintf($this->lang->line('section_updated'), $this->input->post('section_title'), $this->input->post('section_id')));
        redirect('/list_role/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
	
	public function delete($id = null){
    	if($id){
			$table = 'user_roll';
			$wher_column_name = 'user_roll_id';
			set_activity_data_log($id,'Delete','Role & User > List Role','List Role',$table,$wher_column_name,$user_id='');
			
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/list_role/');
        exit();
	}
}

/* End of file list_role.php */