<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_class_room extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->model('list_course/courses_model');
        $this->load->model('list_class_room_model');
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

    public function index($order_by = "class_room_title", $sort_order = "asc", $search = "all", $offset = 0) {
        if (!is_numeric($offset)) {
            redirect('/list_class_room');
        }

        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_class_room_title' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('class_room_title', 'Class room', 'trim|max_length[16]');
            

            if (empty($_POST['class_room_title'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_class_room/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('class_room_title')) {
                    $this->session->set_flashdata('message', form_error('class_room_title'));
                }
                redirect('/list_class_room/');
                exit();
            }

            $search_session = array(
                's_class_room_title'  => $this->input->post('class_room_title')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('list_class_room/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('class_room_title' => $this->input->post('class_room_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_class_room_model->count_all_search_class_room($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }


        }elseif($search == "session") {
            $base_url = site_url('list_class_room/index/'. $order_by .'/'. $sort_order .'/session');
             $search_data = array('class_room_title' => $this->input->post('class_room_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_class_room_model->count_all_search_class_room($search_data);
            $content_data['search'] = "session";

        }else{
            $unset_search_session = array('s_class_room_title' => '');
            $this->session->unset_userdata($unset_search_session);
			$search_data = array('class_room_title' => $this->input->post('class_room_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_class_room_model->count_all_search_class_room($search_data);
            $base_url = site_url('list_class_room/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }

        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = $this->list_class_room_model->get_class_room($per_page, $offset, $order_by, $sort_order, $search_data);
        if (empty($data)) {
            //redirect("/list_class_room");
        }else{
            $content_data['class_rooms'] = $data;
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
        
        $this->template->title('List class_room');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_class_room', $content_data);
    }
    
    public function index_json($order_by = "class_room_title", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array( 'class_room_id','class_room_title','campus_name');
    	$grid_data = get_search_data($aColumns);
		
    	$sort_order = $grid_data['sort_order'];
    	$order_by = trim(trim($grid_data['order_by']),",");
    	
    	if($order_by == "class_room_id")
    		$order_by = "class_room_title";
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    
    	$data = $this->list_class_room_model->get_class_room($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_class_room_model->count_all_class_room_grid($grid_data['search_data']);
    	 
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
    			$row[] = $result_row["class_room_id"];
    			$row[] = $result_row["class_room_title"];
				$row[] = $result_row["campus_name"];
    			$row[] = $result_row["class_room_id"];
    			$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
    
    public function add($id = null){
    	$content_data['id'] = $id;
		$content_data['school_campus'] = get_campus();
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_class_room_model->get_class_room_data($id);
    
    	}
    
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$class_room_title = $this->input->post('class_room_title');
			$camps_id = $this->input->post('camps_id');
    		$error = "";
    		$error_seperator = "<br>";
    		if($id){
    
    			$this->form_validation->set_rules('class_room_title', 'Class room', 'trim|required|is_existing_field[course_class_room.class_room_title^course_class_room.class_room_id !=^'.$id.']');
    
    			if (!$this->form_validation->run()) {
    				if (form_error('class_room_title')) {
    					$error .= form_error('class_room_title').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    
    			$data = array();
    			$data['class_room_title'] = $class_room_title;
    			$data['camps_id'] = $camps_id;
				
    			$table = 'course_class_room';
    			$wher_column_name = 'class_room_id';
    			set_activity_data_log($id,'Update','Course > List Class Room','List Course Class Room',$table,$wher_column_name,$user_id='');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
    			
    			$this->form_validation->set_rules('class_room_title', 'Class room', 'trim|required|is_existing_unique_field[course_class_room.class_room_title]');
    
    			if (!$this->form_validation->run()) {
    				if (form_error('class_room_title')) {
    					$error .= form_error('class_room_title').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    
    			$data = array();
    			$data['class_room_title'] = $class_room_title;
    			$data['camps_id'] = $camps_id;
				
    			$table = 'course_class_room';
    			$wher_column_name = 'class_room_id';
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Course > List Class Room','List Course Class Room',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_class_room_datatable', $content_data);
    }
    
    public function add_class_room(){
    	//Post data
    	$class_room_title = $this->input->post('class_room_title');
    	
    	$data = array();
    	$data['class_room_title'] = $class_room_title;
    	
    	//Table name
    	$table = 'course_class_room';
    
    	grid_add_data($data,$table);
    }
    
    public function update_class_room(){
    	$error = "";
    	$error_seperator = "<br>";
    	
    	$value = $this->input->post('value');
    	$columnName = $this->input->post('columnName');
    	$id = $this->input->post('id');
    	$tablename = 'course_class_room';
    	$whrid_column = 'class_room_id';
    	
    	if($columnName != '') $_POST[$columnName] = $value;
    	$this->form_validation->set_error_delimiters('', '');
    	if ($columnName == 'class_room_title')
    		$this->form_validation->set_rules('class_room_title', 'Class room', 'trim|required|is_existing_field[course_class_room.class_room_title^course_class_room.class_room_id !=^'.$id.']');
    	 
    	if($columnName == 'campus_name')
			$columnName = 'camps_id';
			
    	if (!$this->form_validation->run()) {
    		if (form_error('class_room_title')) {
    			$error .= form_error('class_room_title').$error_seperator;
    		}
    		if($error <> ""){
    			echo $error;
    			exit();
    		}
    	}
    	set_activity_data_log($id,'Update','Course > List Class Room','List Course Class Room',$tablename,$whrid_column,$user_id='');
    	grid_update_data($whrid_column,$id,$columnName,$value,$tablename);
    	
    }

    public function action_class_room($id, $offset, $order_by, $sort_order, $search) {
        if (array_key_exists('update', $_POST)) {
            $this->_update_class_room($id, $offset, $order_by, $sort_order, $search);
        }else{
            $this->_delete_class_room($id, $offset, $order_by, $sort_order, $search);
        }
    }

    /**
     *
     * _update_class_room: update class room info from class room
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _update_class_room($id, $offset, $order_by, $sort_order, $search) {
        $this->form_validation->set_error_delimiters('', '');             
        $this->form_validation->set_rules('class_room_title', 'Class room', 'trim');
        
        if (!$this->form_validation->run()) {
            if (form_error('class_room_title')) {
                $this->session->set_flashdata('message', form_error('class_room_title'));
            }
            redirect('/list_class_room/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }

        $this->list_class_room_model->update_class_room($this->input->post('class_room_id'),$this->input->post('class_room_title'));
        set_activity_log($this->input->post('class_room_id'),'update','course','list class room');
        $this->session->set_flashdata('message', sprintf($this->lang->line('class_room_updated'), $this->input->post('class_room_title'), $this->input->post('class_room_id')));
        redirect('/list_class_room/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
	
	public function delete($id = null){
    	if($id){
			$table = 'course_class_room';
			$wher_column_name = 'class_room_id';
			set_activity_data_log($id,'Delete','Course > List Course Class Room','List Course Class Room',$table,$wher_column_name,$user_id='');
			
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/list_class_room/');
        exit();
	}
}

/* End of file list_class_room.php */