<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_school_year extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('list_school_year_model');
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

    public function index($order_by = "school_year_id", $sort_order = "asc", $search = "all", $offset = 0) {
		$content_data['school_year_list'] = get_school_year_list();
		$content_data['school_list'] = get_school_list();
		$content_data['course_subject'] = get_course_subject();		
		$content_data['course'] = get_course();		
		$content_data['teacher_list'] = get_teacher_list();		
		$content_data['course_category'] = get_course_category();	
		$content_data['shift'] = array('AM'=>'AM','PM'=>'PM');	
		$content_data['school_type'] = array('SEM' => 'Semester','QUA' => 'Quarter','YEAR' => 'Year');
		
        if (!is_numeric($offset)) {
            redirect('/list_school_year');
        }

        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_school_year' => '', 's_school_year_title' => '', 's_school_type' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('school_year', 'School Year', 'trim|max_length[16]');
            $this->form_validation->set_rules('school_year_title', 'School Year Title', 'trim|max_length[40]');
            $this->form_validation->set_rules('school_type', 'School Type', 'trim|max_length[255]');

            if (empty($_POST['school_year']) && empty($_POST['school_year_title']) && empty($_POST['school_type'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_school_year/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('school_year')) {
                    $this->session->set_flashdata('message', form_error('school_year'));
                }elseif (form_error('school_year_title')) {
                    $this->session->set_flashdata('message', form_error('school_year_title'));
                }elseif (form_error('school_type')) {
                    $this->session->set_flashdata('message', form_error('school_type'));
                }
                redirect('/list_school_year/');
                exit();
            }

            $search_session = array(
                's_school_year'  => $this->input->post('school_year'),
                's_school_year_title'     => $this->input->post('school_year_title'),                
                's_school_type' => $this->input->post('school_type')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('list_school_year/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('school_year' => $this->input->post('school_year'), 'school_year_title' => $this->input->post('school_year_title'), 'school_type' => $this->input->post('school_type'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_school_year_model->count_all_search_school_year($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }


        }elseif($search == "session") {
            $base_url = site_url('list_school_year/index/'. $order_by .'/'. $sort_order .'/session');
             $search_data = array('school_year' => $this->input->post('school_year'), 'school_year_title' => $this->input->post('school_year_title'), 'school_type' => $this->input->post('school_type'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_school_year_model->count_all_search_school_year($search_data);
            $content_data['search'] = "session";

        }else{
            $unset_search_session = array('s_school_year' => '', 's_school_year_title' => '', 's_school_type' => '');
            $this->session->unset_userdata($unset_search_session);
            $content_data['total_rows'] = $config['total_rows'] = $this->list_school_year_model->count_all_school_year();
            $base_url = site_url('list_school_year/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }

        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = $this->list_school_year_model->get_school_year($per_page, $offset, $order_by, $sort_order, $search_data);
        if (empty($data)) {
            //redirect("/list_school_year");
        }else{
            $content_data['school'] = $data;
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
        
        $this->template->title('List school Year');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_school_year', $content_data);
    }
    
    public function index_json($order_by = "school_year_id", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('school_year_id','school_id','school_year','school_year_title','school_type','school_week');
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    
    	$data = $this->list_school_year_model->get_school_year($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_school_year_model->count_all_search_school_year($grid_data['search_data']);
    
    	/*
    	 * Output
    	*/
    	$output = array(
    			"sEcho" => intval($_GET['sEcho']),
    			"iTotalRecords" => $count,
    			"iTotalDisplayRecords" => $count,
    			"aaData" => array()
    	);
    
    	//echo "<pre>";print_r($data->result_array());exit;
    	if($data){
    		foreach($data->result_array() AS $result_row){
    			$row = array();
    			$row[] = $result_row["school_year_id"];
    			$row[] = $result_row["school_name"];
    			$row[] = $result_row["school_year"];
    			$row[] = $result_row["school_year_title"];
    			$row[] = $result_row["school_type"];
    			$row[] = $result_row["school_week"];
    			$row[] = $result_row["school_year_id"];
    			$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
    
    public function add($id = null){
    	$content_data['school_list'] = get_school_list();
    	$content_data['school_type'] = array('SEM' => 'Semester','QUA' => 'Quarter','YEAR' => 'Year');
    	$content_data['id'] = $id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_school_year_model->get_school_year_data($id);
    
    	}
    
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$nonce = md5(uniqid(mt_rand(), true));
    		$school_id = $this->input->post('school_id');
    		$school_year = $this->input->post('school_year');
    		$school_year_title = $this->input->post('school_year_title');
    		$school_type = $this->input->post('school_type');
    		$school_week = $this->input->post('school_week');
    		
    		$error = "";
    		$error_seperator = "<br>";
    		if($id){
    
    			$this->form_validation->set_rules('school_year', 'school year', 'trim|required|max_length[4]|min_length[4]|numeric');
				$this->form_validation->set_rules('school_week', 'school week', 'trim|required|numeric');
				$this->form_validation->set_rules('school_year_title', 'school week', 'trim|required');
    
    			if (!$this->form_validation->run()) {
    				if (form_error('school_year')) {
    					$error .= form_error('school_year').$error_seperator;
    				}elseif (form_error('school_week')) {
    					$error .= form_error('school_week').$error_seperator;
    				}elseif (form_error('school_year_title')) {
    					$error .= form_error('school_year_title').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    			 
    			$data = array();
    			$data['school_id'] = $school_id;
    			$data['school_year'] = $school_year;
    			$data['school_year_title'] = $school_year_title;
    			$data['school_type'] = $school_type;
    			$data['school_week'] = $school_week;
    			$table = 'school_year';
    			$wher_column_name = 'school_year_id';
    			
    			set_activity_data_log($id,'Update','University > List University Year','List University Year',$table,$wher_column_name,$user_id='');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
    
    			$this->form_validation->set_rules('school_year', 'school year', 'trim|required|max_length[4]|min_length[4]|numeric');
				$this->form_validation->set_rules('school_week', 'school week', 'trim|required|numeric');
				$this->form_validation->set_rules('school_year_title', 'school week', 'trim|required');
    
    			if (!$this->form_validation->run()) {
    				if (form_error('school_year')) {
    					$error .= form_error('school_year').$error_seperator;
    				}elseif (form_error('school_week')) {
    					$error .= form_error('school_week').$error_seperator;
    				}elseif (form_error('school_year_title')) {
    					$error .= form_error('school_year_title').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    
    			$data = array();
    			$data['school_id'] = $school_id;
    			$data['school_year'] = $school_year;
    			$data['school_year_title'] = $school_year_title;
    			$data['school_type'] = $school_type;
    			$data['school_week'] = $school_week;
    			$table = 'school_year';
    			$wher_column_name = 'school_year_id';
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','University > List University Year','List University Year',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_school_year_datatable', $content_data);
    }
    
    public function add_school_year(){
    	//Post data
    	$school_id = $this->input->post('school_id');
    	$school_year = $this->input->post('school_year');
    	$school_year_title = $this->input->post('school_year_title');
    	$school_type = $this->input->post('school_type');
    	$school_week = $this->input->post('school_week');
    
    	$data = array();
    	$data['school_id'] = $school_id;
    	$data['school_year'] = $school_year;
    	$data['school_year_title'] = $school_year_title;
    	$data['school_type'] = $school_type;
    	$data['school_week'] = $school_week;
    
    	//Table name
    	$table = 'school_year';
    
    	grid_add_data($data,$table);
    }
    
    public function update_school_year(){
    	$error = "";
    	$error_seperator = "<br>";
    	 
    	$value = $this->input->post('value');
    	$columnName = $this->input->post('columnName');
    	$id = $this->input->post('id');
    	$tablename = 'school_year';
    	$whrid_column = 'school_year_id';
    	 
    	if($columnName != '') $_POST[$columnName] = $value;
    	$this->form_validation->set_error_delimiters('', '');
    	
    	if ($columnName == 'school_year')
    		$this->form_validation->set_rules('school_year', 'School Year', 'trim|required|max_length[4]|min_length[4]|numeric');
    	if ($columnName == 'school_week')
    		$this->form_validation->set_rules('school_week', 'School Week', 'trim|required|numeric');
    	if ($columnName == 'school_year_title'){
    		$this->form_validation->set_rules('school_year_title', 'School Year Title', 'trim|required');
    	}
    	 
    	if (!$this->form_validation->run()) {
    		if (form_error('school_year')) {
    			$error .= form_error('school_year').$error_seperator;
    		}elseif (form_error('school_week')) {
    			$error .= form_error('school_week').$error_seperator;
    		}elseif (form_error('school_year_title')) {
    			$error .= form_error('school_year_title').$error_seperator;
    		}
    		 
    		if($error <> ""){
    			echo $error;
    			exit();
    		}
    	}
		
    	set_activity_data_log($id,'Update','University > List University Year','List University Year',$tablename,$whrid_column,$user_id='');
    	
    	grid_update_data($whrid_column,$id,$columnName,$value,$tablename);
    }

    public function get_listbox($type){
    	$jsondata = '';
    	if($type == 'school'){
    		$school_list = get_school_list();
    		$jsondata .= '{';
    		foreach ($school_list as $key => $val){
    			 
    			 
    			if(end($school_list) == $val){
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\'';
    			}else{
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\''.',';
    			}
    			 
    		}
    		$jsondata .= '}';
    		echo $jsondata;
    	}
    }
    public function action_school($id, $offset, $order_by, $sort_order, $search) {
        if (array_key_exists('update', $_POST)) {
            $this->_update_school_year($id, $offset, $order_by, $sort_order, $search);
        }else{
            $this->_delete_school($id, $offset, $order_by, $sort_order, $search);
        }
    }

    /**
     *
     * _update_school_year: update school year info from school year
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _update_school_year($id, $offset, $order_by, $sort_order, $search) {
        $this->form_validation->set_error_delimiters('', '');             
        $this->form_validation->set_rules('school_id', 'School name', 'trim|callback_combobox_check');
       
		$this->form_validation->set_rules('school_year', 'School Year', 'trim|required|max_length[4]|min_length[4]|numeric');
		$this->form_validation->set_rules('school_week', 'School Week', 'trim|required|numeric');
		$this->form_validation->set_rules('school_year_title', 'School Year Title', 'trim|required');
		
        $username = $this->list_school_year_model->get_school_year_by_id($id);
        
        if (!$this->form_validation->run()) {
            if (form_error('school_name')) {
                $this->session->set_flashdata('message', form_error('school_name'));
            }elseif (form_error('school_year')) {
                $this->session->set_flashdata('message', form_error('school_year'));
            }elseif (form_error('school_week')) {
                $this->session->set_flashdata('message', form_error('school_week'));
            }elseif (form_error('school_year_title')) {
                $this->session->set_flashdata('message', form_error('school_year_title'));
            }
            redirect('/list_school_year/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }

        $this->list_school_year_model->update_school_year($this->input->post('school_year_id'),$this->input->post('school_id'),$this->input->post('school_year'), $this->input->post('school_year_title'), $this->input->post('school_type'), $this->input->post('school_week'));
        set_activity_log($this->input->post('school_year_id'),'update','school','list school year');
        $this->session->set_flashdata('message', sprintf($this->lang->line('school_year_updated'), $this->input->post('school_year'), $this->input->post('school_id')));
        redirect('/list_school_year/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

     /**
     *
     * _delete_school_year: delete school year from school year
     *
     * @param int $id the id of the member to be deleted
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _delete_school($id, $offset, $order_by, $sort_order, $search) {
    	
        $username = $this->list_school_year_model->get_school_year_by_id($id);
        
        if ($username == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noremove'));
        }elseif ($this->list_school_year_model->delete_school_year($id)) {
        	
        	set_activity_log($id,'delete','school','list school year');
            $this->session->set_flashdata('message', sprintf($this->lang->line('school_deleted'), $username, $id));
        }
        redirect('/list_school_year/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

}

/* End of file list_school_year.php */