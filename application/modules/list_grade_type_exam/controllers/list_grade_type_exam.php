<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_grade_type_exam extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('list_grade_type_exam_model');
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

    public function index($order_by = "grade_type_exam_id", $sort_order = "asc", $search = "all", $offset = 0) {
        if (!is_numeric($offset)) {
            redirect('/list_grade_type_exam');
        }

        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_category_title' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('category_title', 'category title', 'trim|max_length[16]');
            

            if (empty($_POST['category_title']) && empty($_POST['category_code'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_grade_type_exam/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('category_title')) {
                    $this->session->set_flashdata('message', form_error('category_title'));
                }elseif (form_error('category_code')) {
                    $this->session->set_flashdata('message', form_error('category_code'));
                }
                redirect('/list_grade_type_exam/');
                exit();
            }

            $search_session = array(
                's_category_title'  => $this->input->post('category_title')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('list_grade_type_exam/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('category_title' => $this->input->post('category_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_grade_type_exam_model->count_all_search_course_category($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }


        }elseif($search == "session") {
            $base_url = site_url('list_grade_type_exam/index/'. $order_by .'/'. $sort_order .'/session');
             $search_data = array('category_title' => $this->input->post('category_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_grade_type_exam_model->count_all_search_course_category($search_data);
            $content_data['search'] = "session";

        }else{
            $unset_search_session = array('s_category_title' => '');
            $this->session->unset_userdata($unset_search_session);
            $content_data['total_rows'] = $config['total_rows'] = $this->list_grade_type_exam_model->count_all_course_category();
            $base_url = site_url('list_grade_type_exam/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }

        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = $this->list_grade_type_exam_model->get_grade_type_exam($per_page, $offset, $order_by, $sort_order, $search_data);
        if (empty($data)) {
            //redirect("/list_grade_type_exam");
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
        
        $this->template->title('List Grade Type Exam');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_grade_type_exam', $content_data);
    }
    
    public function index_json($order_by = "grade_type_exam_id", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array( 'grade_type_exam_id','exam_type_name','exam_marks','exam_percentage' );
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
    	$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    	
    	$data = $this->list_grade_type_exam_model->get_grade_type_exam($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_grade_type_exam_model->count_all_grade_type_type_grid($grid_data['search_data']);
    	
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
	    		$row[] = $result_row["grade_type_exam_id"];
	    		$row[] = $result_row["grade_type"];
	    		$row[] = $result_row["exam_type_name"];  
	    		$row[] = $result_row["exam_marks"];
	    		$row[] = $result_row["exam_percentage"];
	    		$row[] = $result_row["grade_type_exam_id"];
	    		$output['aaData'][] = $row;
	    	}
    	}
    	
    	echo json_encode( $output );
    }
    
    public function add($id = null){
    	$grade_types = get_grade_type();
    	$content_data['grade_types'] = $grade_types;
    	$content_data['is_two_marker'] = array('No'=>'No','Yes'=>'Yes');
    	$content_data['is_show'] = array('Yes'=>'Yes','No'=>'No');
    	$content_data['is_show_percentage'] = array('Yes'=>'Yes','No'=>'No');
    	$content_data['id'] = $id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_grade_type_exam_model->get_grade_type_exam_data($id);
    
    	}
    
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$exam_type_name = $this->input->post('exam_type_name');
    		$exam_marks = $this->input->post('exam_marks');
    		$exam_percentage = $this->input->post('exam_percentage');
    		$grade_type_id = $this->input->post('grade_type_id');
    		$is_two_marker = $this->input->post('is_two_marker');
    		$two_mark_difference = $this->input->post('two_mark_difference');
    		$is_show = $this->input->post('is_show');
    		$is_show_percentage = $this->input->post('is_show_percentage');
    		$error = "";
    		$error_seperator = "<br>";
    		if($id){
    
    			$this->form_validation->set_rules('exam_type_name', 'Grade Type', 'trim|required');
    			$this->form_validation->set_rules('exam_marks', 'Total Marks', 'trim|numeric');
    			$this->form_validation->set_rules('exam_percentage', 'Total Percentage', 'trim|numeric');
    
    			if (!$this->form_validation->run()) {
    				if (form_error('exam_type_name')) {
    					$error .= form_error('exam_type_name').$error_seperator;
    				}
    				if (form_error('exam_marks')) {
    					$error .= form_error('exam_marks').$error_seperator;
    				}
    				if (form_error('exam_percentage')) {
    					$error .= form_error('exam_percentage').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    
    			$data = array();
    			$data['exam_type_name'] = $exam_type_name;
    			$data['exam_marks'] = $exam_marks;
    			$data['exam_percentage'] = $exam_percentage;
    			$data['grade_type_id'] = $grade_type_id;
    			$data['is_two_marker'] = $is_two_marker;
    			$data['two_mark_difference'] = $two_mark_difference;
    			$data['is_show'] = $is_show;
    			$data['is_show_percentage'] = $is_show_percentage;
    
    			$table = 'grade_type_exam';
    			$wher_column_name = 'grade_type_exam_id';
    			set_activity_data_log($id,'Update','Grades > List Grade Type Exam','List Grade Type Exam',$table,$wher_column_name,$user_id='');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
    			 
    			$this->form_validation->set_rules('exam_type_name', 'Grade Exam Type', 'trim|required');
    			$this->form_validation->set_rules('exam_marks', 'Total Marks', 'trim|numeric');
    			$this->form_validation->set_rules('exam_percentage', 'Total Percentage', 'trim|numeric');
    
    			if (!$this->form_validation->run()) {
    				if (form_error('exam_type_name')) {
    					$error .= form_error('exam_type_name').$error_seperator;
    				}
    				if (form_error('exam_marks')) {
    					$error .= form_error('exam_marks').$error_seperator;
    				}
    				if (form_error('exam_percentage')) {
    					$error .= form_error('exam_percentage').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    
    			$data = array();
    			$data['exam_type_name'] = $exam_type_name;
    			$data['exam_marks'] = $exam_marks;
    			$data['exam_percentage'] = $exam_percentage;
    			$data['grade_type_id'] = $grade_type_id;
    			$data['is_two_marker'] = $is_two_marker;
    			$data['two_mark_difference'] = $two_mark_difference;
    			$data['is_show'] = $is_show;
    			$data['is_show_percentage'] = $is_show_percentage;
				
    			$table = 'grade_type_exam';
    			$wher_column_name = 'grade_type_exam_id';
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Grades > List Grade Type Exam','List Grade Type Exam',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_grade_type_exam_datatable', $content_data);
    }
    
	public function delete($id = null){
    	if($id){
			$table = 'grade_type_exam';
			$wher_column_name = 'grade_type_exam_id';
			set_activity_data_log($id,'Delete','Grades > List Grade Type Exam','List Grade Type Exam',$table,$wher_column_name,$user_id='');
			
    		$rowdata = $this->list_grade_type_exam_model->delete_grade_type_exam($id);
    	}
		redirect('/list_grade_type_exam/');
        exit();
	}
	
    public function add_grade_type_exam(){
    	//Post data
    	$category_title = $this->input->post('category_title');
    	
    	$data = array();
    	$data['category_title'] = $category_title;
    	
    	//Table name
    	$table = 'course_category';
    	
    	grid_add_data($data,$table);
    }
    
    public function update_grade_type_exam(){
    	$error = "";
    	$error_seperator = "<br>";
    	 
    	$value = $this->input->post('value');
    	$columnName = $this->input->post('columnName');
    	$id = $this->input->post('id');
    	$tablename = 'grade_type_exam';
    	$whrid_column = 'grade_type_exam_id';
    	 
    	if($columnName != '') $_POST[$columnName] = $value;
    	$this->form_validation->set_error_delimiters('', '');
    	if ($columnName == 'exam_type_name')
    		$this->form_validation->set_rules('exam_type_name', 'Grade Exam Type', 'trim|required');
    		$this->form_validation->set_rules('exam_marks', 'Total Marks', 'trim|numeric');
    		$this->form_validation->set_rules('exam_percentage', 'Total Percentage', 'trim|numeric');
    	 
    	if (!$this->form_validation->run()) {
    		if (form_error('exam_type_name')) {
    			$error .= form_error('exam_type_name').$error_seperator;
    		}
    		if (form_error('exam_marks')) {
    			$error .= form_error('exam_marks').$error_seperator;
    		}
    		if (form_error('exam_percentage')) {
    			$error .= form_error('exam_percentage').$error_seperator;
    		}
    		if($error <> ""){
    			echo $error;
    			exit();
    		}
    	}
    	set_activity_data_log($id,'Update','Grades > List Grade Type Exam','List Grade Type Exam',$tablename,$whrid_column,$user_id='');
    	grid_update_data($whrid_column,$id,$columnName,$value,$tablename);
    }
    
    public function get_listbox($type){
    	$jsondata = '';
    	if($type == 'grade_type'){
    		$grade_type_list = get_grade_type();
    		$jsondata .= '{';
    		foreach ($grade_type_list as $key => $val){
    			 
    			 
    			if(end($grade_type_list) == $val){
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\'';
    			}else{
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\''.',';
    			}
    			 
    		}
    		$jsondata .= '}';
    		echo $jsondata;
    	}
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
     * _update_course_category: update course category info from course category
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _update_course_category($id, $offset, $order_by, $sort_order, $search) {
        $this->form_validation->set_error_delimiters('', '');             
        $this->form_validation->set_rules('category_title', 'Category Title', 'trim');
        
		

        $username = $this->list_grade_type_exam_model->get_course_title_by_id($id);
        
        if (!$this->form_validation->run()) {
            if (form_error('category_title')) {
                $this->session->set_flashdata('message', form_error('category_title'));
            }
            redirect('/list_grade_type_exam/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }

        $this->list_grade_type_exam_model->update_course_category($this->input->post('category_id'),$this->input->post('category_title'));
        set_activity_log($this->input->post('category_id'),'update','course','list course category');
        $this->session->set_flashdata('message', sprintf($this->lang->line('course_category_updated'), $this->input->post('category_title'), $this->input->post('category_id')));
        redirect('/list_grade_type_exam/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

     /**
     *
     * _delete_course_category: delete course category from course category
     *
     * @param int $id the id of the member to be deleted
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _delete_course_category($id, $offset, $order_by, $sort_order, $search) {
        $username = $this->list_grade_type_exam_model->get_course_title_by_id($id);
        if ($username == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noremove'));
        }elseif ($this->list_grade_type_exam_model->delete_course_category($id)) {
        	
        	set_activity_log($id,'delete','course','list course category');
            $this->session->set_flashdata('message', sprintf($this->lang->line('course_category_deleted'), $username, $id));
        }
        redirect('/list_grade_type_exam/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
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
        if ($this->list_grade_type_exam_model->get_course_title_by_id($id) == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noactivate'));
            redirect('/list_grade_type_exam/index');
            return;
        }elseif ($this->list_grade_type_exam_model->toggle_active($id, $active)) {
            $active ? $active = $this->lang->line('deactivated') : $active = $this->lang->line('activated');
            $this->session->set_flashdata('message', sprintf($this->lang->line('toggle_active'), $username) . $active);
        }
        redirect('/list_grade_type_exam/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
}

/* End of file list_grade_type_exam.php */