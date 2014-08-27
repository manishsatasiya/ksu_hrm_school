<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_school_campus extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('list_school_campus_model');
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

    public function index($order_by = "campus_id", $sort_order = "asc", $search = "all", $offset = 0) {
        if (!is_numeric($offset)) {
            redirect('/list_school_campus');
        }

        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_category_title' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('campus_name', 'category title', 'trim|max_length[16]');
            

            if (empty($_POST['campus_name']) && empty($_POST['category_code'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_school_campus/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('campus_name')) {
                    $this->session->set_flashdata('message', form_error('campus_name'));
                }elseif (form_error('category_code')) {
                    $this->session->set_flashdata('message', form_error('category_code'));
                }
                redirect('/list_school_campus/');
                exit();
            }

            $search_session = array(
                's_category_title'  => $this->input->post('campus_name')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('list_school_campus/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('campus_name' => $this->input->post('campus_name'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_school_campus_model->count_all_search_campus($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }


        }elseif($search == "session") {
            $base_url = site_url('list_school_campus/index/'. $order_by .'/'. $sort_order .'/session');
             $search_data = array('campus_name' => $this->input->post('campus_name'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_school_campus_model->count_all_search_campus($search_data);
            $content_data['search'] = "session";

        }else{
            $unset_search_session = array('s_category_title' => '');
            $this->session->unset_userdata($unset_search_session);
            $content_data['total_rows'] = $config['total_rows'] = $this->list_school_campus_model->count_all_campus();
            $base_url = site_url('list_school_campus/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }

        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = $this->list_school_campus_model->get_campus($per_page, $offset, $order_by, $sort_order, $search_data);
        if (empty($data)) {
            //redirect("/list_school_campus");
        }else{
            $content_data['category'] = $data;
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
        
        $this->template->title('List School Campus');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_school_campus', $content_data);
    }
    
    public function index_json($order_by = "campus_id", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
		
    	$aColumns = array( 'campus_id','campus_name','campus_location' );
    	$grid_data = get_search_data($aColumns);
		
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    	
    	$data = $this->list_school_campus_model->get_campus($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_school_campus_model->count_all_campus_grid($grid_data['search_data']);
    	
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
	    		$row[] = $result_row["campus_id"];
	    		$row[] = $result_row["campus_name"];
	    		$row[] = $result_row["campus_location"];  
	    		$row[] = $result_row["campus_id"];
	    		$output['aaData'][] = $row;
	    	}
    	}
		echo json_encode( $output );
    }
    
    public function add($id = null){
    	$content_data['id'] = $id;
		$content_data['attendance_days_list'] = array('' => "Select",0 => "Sunday",

      1=> "Monday",

      2 => "Tuesday",

      3 => "Wednesday",

      4 => "Thursday",

      5 => "Friday",

      6 => "Saturday");
		
		
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_school_campus_model->get_campus_data($id);
    
    	}
    
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$campus_name = $this->input->post('campus_name');
    		$campus_location = $this->input->post('campus_location');
			$line_manager_attendance_day = $this->input->post('line_manager_attendance_day');
    		
    		$error = "";
    		$error_seperator = "<br>";
    		if($id){
    			$this->form_validation->set_rules('campus_name', 'Grade Min Range', 'trim|required');
    			$this->form_validation->set_rules('campus_location', 'Grade Max Range', 'trim|required');
    			
    			if (!$this->form_validation->run()) {
    				if (form_error('campus_name')) {
    					$error .= form_error('campus_name').$error_seperator;
    				}elseif(form_error('campus_location')) {
    					$error .= form_error('campus_location').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    
    			$data = array();
    			$data['campus_name'] = $campus_name;
    			$data['campus_location'] = $campus_location;
    			$data['line_manager_attendance_day'] = $line_manager_attendance_day;
    			
    			$table = 'school_campus';
    			$wher_column_name = 'campus_id';
    			
    			//Add activty logo
    			set_activity_data_log($id,'Update','University > Campuses','list school campus',$table,$wher_column_name,$user_id='');
    			
				$data_user = array('campus' => $campus_name);
				
				$this->list_school_campus_model->update_user_campus($id,$data_user);
				
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
    			 
    			$this->form_validation->set_rules('campus_name', 'Grade Min Range', 'trim|required');
    			$this->form_validation->set_rules('campus_location', 'Grade Max Range', 'trim|required');
    			
    			if (!$this->form_validation->run()) {
    				if (form_error('campus_name')) {
    					$error .= form_error('campus_name').$error_seperator;
    				}elseif (form_error('campus_location')) {
    					$error .= form_error('campus_location').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    
    			$data = array();
    			$data['campus_name'] = $campus_name;
    			$data['campus_location'] = $campus_location;
    			$data['line_manager_attendance_day'] = $line_manager_attendance_day;
    
    			$table = 'school_campus';
    			$lastinsertid = grid_add_data($data,$table);
    			$wher_column_name = 'campus_id';
    			//Add activty logo
    			set_activity_data_log($lastinsertid,'Add','University > Campuses','list school campus',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_school_campus_datatable', $content_data);
    }
    
    public function add_school_campus(){
    	//Post data
    	$campus_name = $this->input->post('campus_name');
    	
    	$data = array();
    	$data['campus_name'] = $campus_name;
    	
    	//Table name
    	$table = 'school_campus';
    	
    	grid_add_data($data,$table);
    }
    
    public function update_school_campus(){
    	$error = "";
    	$error_seperator = "<br>";
    	 
    	$value = $this->input->post('value');
    	$columnName = $this->input->post('columnName');
    	$id = $this->input->post('id');
    	$tablename = 'school_campus';
    	$whrid_column = 'campus_id';
    	 
    	if($columnName != '') $_POST[$columnName] = $value;
    	$this->form_validation->set_error_delimiters('', '');
    	if ($columnName == 'campus_name')
    		$this->form_validation->set_rules('campus_name', 'Grade Min Range', 'trim|required');
    	if ($columnName == 'campus_location')
    		$this->form_validation->set_rules('campus_location', 'Grade Max Range', 'trim|required');
    	 
    	 
    	if (!$this->form_validation->run()) {
    		if (form_error('campus_name')) {
    			$error .= form_error('campus_name').$error_seperator;
    		}elseif (form_error('campus_location')) {
    			$error .= form_error('campus_location').$error_seperator;
    		}
    		if($error <> ""){
    			echo $error;
    			exit();
    		}
    	}
    	
    	//Add activty logo
    	set_activity_data_log($id,'Update','University > Campuses','list school campus',$tablename,$whrid_column,$user_id='');
    	 
    	grid_update_data($whrid_column,$id,$columnName,$value,$tablename);
    	 
    }
    
    public function action_course_category($id, $offset, $order_by, $sort_order, $search) {
        if (array_key_exists('update', $_POST)) {
            $this->_update_school_campus($id, $offset, $order_by, $sort_order, $search);
        }else{
            $this->_delete_school_campus($id, $offset, $order_by, $sort_order, $search);
        }
    }

    /**
     *
     * _update_school_campus: update course category info from course category
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _update_school_campus($id, $offset, $order_by, $sort_order, $search) {
        $this->form_validation->set_error_delimiters('', '');             
        $this->form_validation->set_rules('campus_name', 'Category Title', 'trim');
        
		

        $username = $this->list_school_campus_model->get_course_title_by_id($id);
        
        if (!$this->form_validation->run()) {
            if (form_error('campus_name')) {
                $this->session->set_flashdata('message', form_error('campus_name'));
            }
            redirect('/list_school_campus/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }

        $this->list_school_campus_model->update_campus($this->input->post('campus_id'),$this->input->post('campus_name'));
        set_activity_log($this->input->post('campus_id'),'update','course','list grade range');
        $this->session->set_flashdata('message', sprintf($this->lang->line('course_category_updated'), $this->input->post('campus_name'), $this->input->post('campus_id')));
        redirect('/list_school_campus/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

     /**
     *
     * _delete_school_campus: delete course category from course category
     *
     * @param int $id the id of the member to be deleted
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _delete_school_campus($id, $offset, $order_by, $sort_order, $search) {
        $username = $this->list_school_campus_model->get_course_title_by_id($id);
        if ($username == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noremove'));
        }elseif ($this->list_school_campus_model->delete_campus($id)) {
        	
        	set_activity_log($id,'delete','course','list grade range');
            $this->session->set_flashdata('message', sprintf($this->lang->line('course_category_deleted'), $username, $id));
        }
        redirect('/list_school_campus/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

    
    /**
     *
     * toggle_active: (de)activate member from school
     *
     * @param int $id the id of the member to be deleted
     * @param string $username the username of the member involved
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     * @param bool $active or deactivate?
     *
     */

    public function toggle_active($id, $username, $offset, $order_by, $sort_order, $search, $active) {
        if ($this->list_school_campus_model->get_course_title_by_id($id) == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noactivate'));
            redirect('/list_school_campus/index');
            return;
        }elseif ($this->list_school_campus_model->toggle_active($id, $active)) {
            $active ? $active = $this->lang->line('deactivated') : $active = $this->lang->line('activated');
            $this->session->set_flashdata('message', sprintf($this->lang->line('toggle_active'), $username) . $active);
        }
        redirect('/list_school_campus/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
}

/* End of file list_school_campus.php */