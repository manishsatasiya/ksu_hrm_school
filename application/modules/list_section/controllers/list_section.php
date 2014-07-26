<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_section extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('list_course/courses_model');
        $this->load->model('list_class_section_model');
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

    public function index($order_by = "section_title", $sort_order = "asc", $search = "all", $offset = 0) {
        if (!is_numeric($offset)) {
            redirect('/list_section');
        }

        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_section_title' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('section_title', 'Section', 'trim|max_length[16]');
            

            if (empty($_POST['section_title'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_section/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('section_title')) {
                    $this->session->set_flashdata('message', form_error('section_title'));
                }
                redirect('/list_section/');
                exit();
            }

            $search_session = array(
                's_section_title'  => $this->input->post('section_title')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('list_section/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('section_title' => $this->input->post('section_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->courses_model->count_all_search_section($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }


        }elseif($search == "session") {
            $base_url = site_url('list_section/index/'. $order_by .'/'. $sort_order .'/session');
             $search_data = array('section_title' => $this->input->post('section_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->courses_model->count_all_search_section($search_data);
            $content_data['search'] = "session";

        }else{
            $unset_search_session = array('s_section_title' => '');
            $this->session->unset_userdata($unset_search_session);
			$search_data = array('section_title' => $this->input->post('section_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->courses_model->count_all_search_section($search_data);
            $base_url = site_url('list_section/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }

        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = $this->courses_model->get_section($per_page, $offset, $order_by, $sort_order, $search_data);
        if (empty($data)) {
            //redirect("/list_section");
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
        $this->template->build('list_section', $content_data);
    }
    
    public function index_json($order_by = "section_title", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array( 'section_id','section_title','ca_lead_teacher','campus_name');
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
    	
    	if($order_by == "section_id")
    		$order_by = "section_title";
    			
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    	
    	$data = $this->courses_model->get_section($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_class_section_model->count_all_class_section_grid($grid_data['search_data']);
    	 
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
    			$row[] = $result_row["section_id"];
    			$row[] = $result_row["section_title"];
    			$row[] = $result_row["lead_name"];
    			$row[] = $result_row["campus_name"];
				$row[] = $result_row["track"];
				$row[] = $result_row["buildings"];
    			$row[] = $result_row["section_id"];
    			$output['aaData'][] = $row;
    		}
    	}
    	
    	echo json_encode( $output );
    }
    
    public function add($id = null){
		$content_data['school_campus'] = get_campus();
    	$content_data['get_ca_lead_teacher_list'] = get_ca_lead_teacher_list();
    	$content_data['teacher_list'] = get_teacher_list();
    	$content_data['section_list'] = get_section();
		$content_data['track'] = get_track();
    	$content_data['buildings'] = get_buildings();
    	$content_data['id'] = $id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_class_section_model->get_section_data($id);
    
    	}
    
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$section_title = $this->input->post('section_title');
    		$ca_lead_teacher = $this->input->post('ca_lead_teacher');
    		$camps_id = $this->input->post('camps_id');
    		$track = $this->input->post('track');
			$buildings = $this->input->post('buildings');
    		$error = "";
    		$error_seperator = "<br>";
    		if($id){
    
    			$this->form_validation->set_rules('section_title', 'Section', 'trim|required|is_existing_field[course_section.section_title^course_section.section_id !=^'.$id.']');
    
    			if (!$this->form_validation->run()) {
    				if (form_error('section_title')) {
    					$error .= form_error('section_title').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    			 
    			$data = array();
    			$data['section_title'] = $section_title;
    			$data['ca_lead_teacher'] = $ca_lead_teacher;
    			$data['camps_id'] = $camps_id;
				$data['track'] = $track;
				$data['buildings'] = $buildings;
    			
    			$table = 'course_section';
    			$wher_column_name = 'section_id';
    			set_activity_data_log($id,'Update','Course > List Course','List Course Section',$table,$wher_column_name,$user_id='');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
    
    			$this->form_validation->set_rules('section_title', 'Section Name', 'trim|required|is_existing_unique_field[course_section.section_title]');
    
    			if (!$this->form_validation->run()) {
    				if (form_error('section_title')) {
    					$error .= form_error('section_title').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    
    			$data = array();
    			$data['section_title'] = $section_title;
    			$data['ca_lead_teacher'] = $ca_lead_teacher;
    			$data['camps_id'] = $camps_id;
				$data['track'] = $track;
				$data['buildings'] = $buildings;
    			
    			$table = 'course_section';
    			$wher_column_name = 'section_id';
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Course > List Course','List Course Section',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_section_datatable', $content_data);
    }

    public function add_section(){
    	//Post data
    	$section_title = $this->input->post('section_title');
    
    	$data = array();
    	$data['section_title'] = $section_title;
    
    	//Table name
    	$table = 'course_section';
    
    	grid_add_data($data,$table);
    }
    
    public function update_section(){
    	$error = "";
    	$error_seperator = "<br>";
    	
    	$value = $this->input->post('value');
    	$columnName = $this->input->post('columnName');
    	$id = $this->input->post('id');
    	$tablename = 'course_section';
    	$whrid_column = 'section_id';
    	
    	if($columnName != '') $_POST[$columnName] = $value;
    	$this->form_validation->set_error_delimiters('', '');
    	if ($columnName == 'section_title')
    		$this->form_validation->set_rules('section_title', 'Section', 'trim|required|is_existing_field[course_section.section_title^course_section.section_id !=^'.$id.']');
    	
		if($columnName == 'campus_name')
			$columnName = 'camps_id';
    	if (!$this->form_validation->run()) {
    		if (form_error('section_title')) {
    			$error .= form_error('section_title').$error_seperator;
    		}
    		if($error <> ""){
    			echo $error;
    			exit();
    		}
    	}

    	set_activity_data_log($id,'Update','Course > List Course','List Course Section',$tablename,$whrid_column,$user_id='');
    	grid_update_data($whrid_column,$id,$columnName,$value,$tablename);
    	
    }
    
    public function action_section($id, $offset, $order_by, $sort_order, $search) {
        if (array_key_exists('update', $_POST)) {
            $this->_update_section($id, $offset, $order_by, $sort_order, $search);
        }else{
            $this->_delete_section($id, $offset, $order_by, $sort_order, $search);
        }
    }

    /**
     *
     * _update_section: update section info from school
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _update_section($id, $offset, $order_by, $sort_order, $search) {
        $this->form_validation->set_error_delimiters('', '');             
        $this->form_validation->set_rules('section_title', 'Section', 'trim');
        
        if (!$this->form_validation->run()) {
            if (form_error('section_title')) {
                $this->session->set_flashdata('message', form_error('section_title'));
            }
            redirect('/list_section/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }

        $this->courses_model->update_section($this->input->post('section_id'),$this->input->post('section_title'));
        set_activity_log($this->input->post('section_id'),'update','course','list section');
        $this->session->set_flashdata('message', sprintf($this->lang->line('section_updated'), $this->input->post('section_title'), $this->input->post('section_id')));
        redirect('/list_section/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
	
	public function delete($id = null){
    	if($id){
			$table = 'course_section';
			$wher_column_name = 'section_id';
			set_activity_data_log($id,'Delete','Course > List Course Section','List Course Section',$table,$wher_column_name,$user_id='');
			
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/list_section/');
        exit();
	}
}

/* End of file list_section.php */