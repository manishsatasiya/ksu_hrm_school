<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_course extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('list_course_model');
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

    public function index($order_by = "course_id", $sort_order = "asc", $search = "all", $offset = 0) {
		$content_data['school_year_list'] = get_school_year_title();
		$content_data['school_list'] = get_school_list();
		$content_data['course_subject'] = get_course_subject();		
		$content_data['course'] = get_course();		
		$content_data['teacher_list'] = get_teacher_list();		
		$content_data['course_category'] = get_course_category();	
        if (!is_numeric($offset)) {
            redirect('/list_course');
        }

        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_course_title' => '', 's_course_code' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('course_title', 'Course title', 'trim');
            $this->form_validation->set_rules('course_code', 'Course Code', 'trim');
            $this->form_validation->set_rules('max_hours', 'Max Hours', 'trim');
            

            if (empty($_POST['course_title']) && empty($_POST['course_code'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_course/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('course_title')) {
                    $this->session->set_flashdata('message', form_error('course_title'));
                }elseif (form_error('course_code')) {
                    $this->session->set_flashdata('message', form_error('course_code'));
                }elseif (form_error('max_hours')) {
                    $this->session->set_flashdata('message', form_error('max_hours'));
                }
                redirect('/list_course/');
                exit();
            }

            $search_session = array(
                's_course_title'  => $this->input->post('course_title'),
                's_course_code'     => $this->input->post('course_code')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('list_course/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('course_title' => $this->input->post('course_title'), 'course_code' => $this->input->post('course_code'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_course_model->count_all_search_course($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }


        }elseif($search == "session") {
            $base_url = site_url('list_course/index/'. $order_by .'/'. $sort_order .'/session');
             $search_data = array('course_title' => $this->input->post('course_title'), 'course_code' => $this->input->post('course_code'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_course_model->count_all_search_course($search_data);
            $content_data['search'] = "session";

        }else{
            $unset_search_session = array('s_course_title' => '', 's_course_code' => '');
            $this->session->unset_userdata($unset_search_session);
            $content_data['total_rows'] = $config['total_rows'] = $this->list_course_model->count_all_course();
            $base_url = site_url('list_course/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }

        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = $this->list_course_model->get_course($per_page, $offset, $order_by, $sort_order, $search_data);
        if (empty($data)) {
            //redirect("/list_course");
        }else{
            $content_data['course'] = $data;
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
        
        $this->template->title('List Course');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_course', $content_data);        
    }
    
    public function index_json($order_by = "course_title", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array( 'course_id','course_title','max_hours','total_hours_all_weeks');
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
    	
    	if($order_by == "course_id")
    		$order_by = "course_title";
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    	 
    	$data = $this->list_course_model->get_course($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_course_model->count_all_course_grid1($grid_data['search_data']);
    	
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
    			$row[] = $result_row["course_id"];
    			$row[] = $result_row["course_title"];
    			$row[] = $result_row["max_hours"];
    			$row[] = $result_row["total_hours_all_weeks"];
    			$row[] = $result_row["course_id"];
    			$output['aaData'][] = $row;
    		}
    	}
    	 
    	echo json_encode( $output );
    }
    
    public function add($id = null){
    	$content_data['id'] = $id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_course_model->get_section_data($id);
    
    	}
    	$content_data['school_campus'] = get_campus();
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$course_title = $this->input->post('course_title');
    		$max_hours = $this->input->post('max_hours');
    		$total_hours_all_weeks = $this->input->post('total_hours_all_weeks');
			$start_time = $this->input->post('start_time');
			$end_time = $this->input->post('end_time');
			$camps_id = $this->input->post('camps_id');
			$pm_start_time = $this->input->post('pm_start_time');
			$pm_end_time = $this->input->post('pm_end_time');
    		
    		$error = "";
    		$error_seperator = "<br>";
    		if($id){
    
    			$this->form_validation->set_rules('course_title', 'Course Title', 'trim');
       			$this->form_validation->set_rules('max_hours', 'Max Hours', 'trim');
       			$this->form_validation->set_rules('total_hours_all_weeks', 'Total Hours', 'trim');
    
    			if (!$this->form_validation->run()) {
    				if (form_error('course_title')) {
    					$error .= form_error('course_title').$error_seperator;
    				}elseif (form_error('max_hours')) {
    					$error .= form_error('max_hours').$error_seperator;
    				}elseif (form_error('total_hours_all_weeks')) {
    					$error .= form_error('total_hours_all_weeks').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    
    			$data = array();
    			$data['course_title'] = $course_title;
    			$data['max_hours'] = $max_hours;
    			$data['total_hours_all_weeks'] = $total_hours_all_weeks;
				$data['start_time'] = $start_time;
    			$data['end_time'] = $end_time;
				$data['pm_start_time'] = $pm_start_time;
    			$data['pm_end_time'] = $pm_end_time;
				$data['camps_id'] = $camps_id;
				
    			$table = 'courses';
    			$wher_column_name = 'course_id';
    			set_activity_data_log($id,'Update','Course > List Course','List Course',$table,$wher_column_name,$user_id='');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
    
    			$this->form_validation->set_rules('course_title', 'Course Title', 'trim');
       			$this->form_validation->set_rules('max_hours', 'Max Hours', 'trim');
       			$this->form_validation->set_rules('total_hours_all_weeks', 'Total Hours', 'trim');
    
    			if (!$this->form_validation->run()) {
    				if (form_error('course_title')) {
    					$error .= form_error('course_title').$error_seperator;
    				}elseif (form_error('max_hours')) {
    					$error .= form_error('max_hours').$error_seperator;
    				}elseif (form_error('total_hours_all_weeks')) {
    					$error .= form_error('total_hours_all_weeks').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    
    			$data = array();
    			$data['course_title'] = $course_title;
    			$data['max_hours'] = $max_hours;
    			$data['total_hours_all_weeks'] = $total_hours_all_weeks;
				$data['start_time'] = $start_time;
    			$data['end_time'] = $end_time;
				$data['camps_id'] = $camps_id;
				$data['pm_start_time'] = $pm_start_time;
    			$data['pm_end_time'] = $pm_end_time;
				
    			 
    			$table = 'courses';
    			$wher_column_name = 'course_id';
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Course > List Course','List Course',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_course_datatable', $content_data);
    }
    
    
    public function add_course(){
    	//Post data
    	$course_title = $this->input->post('course_title');
    	$max_hours = $this->input->post('max_hours');
    	 
    	$data = array();
    	$data['course_title'] = $course_title;
    	$data['max_hours'] = $max_hours;
    	 
    	//Table name
    	$table = 'courses';
    	 
    	grid_add_data($data,$table);
    }
    
    public function update_course(){
    	$error = "";
    	$error_seperator = "<br>";
    	
    	$value = $this->input->post('value');
    	$columnName = $this->input->post('columnName');
    	$id = $this->input->post('id');
    	$tablename = 'courses';
    	$whrid_column = 'course_id';
    	
    	if($columnName != '') $_POST[$columnName] = $value;
    	$this->form_validation->set_error_delimiters('', '');
    	if ($columnName == 'course_title')
    		$this->form_validation->set_rules('course_title', 'Course Title', 'trim');
    	if ($columnName == 'max_hours')
    		$this->form_validation->set_rules('max_hours', 'Max Hours', 'trim');
    	if ($columnName == 'total_hours_all_weeks')
    		$this->form_validation->set_rules('total_hours_all_weeks', 'Total Hours', 'trim');
    	 
    	if (!$this->form_validation->run()) {
    		if (form_error('course_title')) {
    			$error .= form_error('course_title').$error_seperator;
    		}elseif (form_error('max_hours')) {
    			$error .= form_error('max_hours').$error_seperator;
    		}elseif (form_error('total_hours_all_weeks')) {
    			$error .= form_error('total_hours_all_weeks').$error_seperator;
    		}
    		if($error <> ""){
    			echo $error;
    			exit();
    		}
    	}
    	set_activity_data_log($id,'Update','Course > List Course','List Course',$tablename,$whrid_column,$user_id='');
    	grid_update_data($whrid_column,$id,$columnName,$value,$tablename);
    	
    }

    public function action_course_category($id, $offset, $order_by, $sort_order, $search) {
        if (array_key_exists('update', $_POST)) {
            $this->_update_course_category($id, $offset, $order_by, $sort_order, $search);
        }else{
            $this->_delete_course_category($id, $offset, $order_by, $sort_order, $search);
        }
    }

    /**
     *
     * _update_course_category: update course category info from school
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _update_course_category($id, $offset, $order_by, $sort_order, $search) {
        $this->form_validation->set_error_delimiters('', '');             
        $this->form_validation->set_rules('course_title', 'Course Title', 'trim');
        $this->form_validation->set_rules('course_code', 'Course Code', 'trim');
		$this->form_validation->set_rules('course_subject_id', 'Course Subject', 'trim');
		$this->form_validation->set_rules('school_id', 'School', 'trim');
		$this->form_validation->set_rules('school_year_id', 'School Year', 'trim');
		$this->form_validation->set_rules('max_hours', 'Max Hours', 'trim');

        $username = $this->list_course_model->get_course_title_by_id($id);
        
        if (!$this->form_validation->run()) {
            if (form_error('course_title')) {
                $this->session->set_flashdata('message', form_error('course_title'));
            }elseif (form_error('course_code')) {
                $this->session->set_flashdata('message', form_error('course_code'));
            }elseif (form_error('course_subject_id')) {
                $this->session->set_flashdata('message', form_error('course_subject_id'));
            }elseif (form_error('school_id')) {
                $this->session->set_flashdata('message', form_error('school_id'));
            }elseif (form_error('school_year_id')) {
                $this->session->set_flashdata('message', form_error('school_year_id'));
            }elseif (form_error('max_hours')) {
                $this->session->set_flashdata('message', form_error('max_hours'));
            }
            redirect('/list_course/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }

        $this->list_course_model->update_course($this->input->post('course_id'),$this->input->post('school_year_id'),$this->input->post('school_id'),$this->input->post('course_title'),$this->input->post('max_hours'));
        set_activity_log($this->input->post('course_id'),'update','course','list course');
        $this->session->set_flashdata('message', sprintf($this->lang->line('course_updated'), $this->input->post('category_title'), $this->input->post('course_id')));
        redirect('/list_course/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

     /**
     *
     * _delete_course_category: delete course category from school
     *
     * @param int $id the id of the member to be deleted
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _delete_course_category($id, $offset, $order_by, $sort_order, $search) {
        $username = $this->list_course_model->get_course_title_by_id($id);
        if ($username == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noremove'));
        }elseif ($this->list_course_model->delete_course($id)) {
        	set_activity_log($id,'delete','course','list course');
            $this->session->set_flashdata('message', sprintf($this->lang->line('course_deleted'), $username, $id));
        }
        redirect('/list_course/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
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
        if ($this->list_course_model->get_course_title_by_id($id) == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noactivate'));
            redirect('/list_course/index');
            return;
        }elseif ($this->list_course_model->toggle_active($id, $active)) {
            $active ? $active = $this->lang->line('deactivated') : $active = $this->lang->line('activated');
            $this->session->set_flashdata('message', sprintf($this->lang->line('toggle_active'), $username) . $active);
        }
        redirect('/list_course/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
	
	public function delete($id = null){
    	if($id){
			$table = 'courses';
			$wher_column_name = 'course_id';
			set_activity_data_log($id,'Delete','Course > List Course','List Course',$table,$wher_column_name,$user_id='');
			
    		$rowdata = $this->list_course_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/list_course/');
        exit();
	}
}

/* End of file list_members.php */