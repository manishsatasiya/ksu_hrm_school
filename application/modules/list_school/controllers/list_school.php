<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_school extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('list_school_model');
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

    public function index($order_by = "school_id", $sort_order = "asc", $search = "all", $offset = 0) {
        if (!is_numeric($offset)) {
            redirect('/list_school');
        }

        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_school_name' => '', 's_principal' => '', 's_email' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('school_name', 'school name', 'trim|max_length[16]');
            $this->form_validation->set_rules('principal', 'principal', 'trim|max_length[40]');
            $this->form_validation->set_rules('email', 'email', 'trim|max_length[255]');

            if (empty($_POST['school_name']) && empty($_POST['principal']) && empty($_POST['email'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_school/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('school_name')) {
                    $this->session->set_flashdata('message', form_error('school_name'));
                }elseif (form_error('email')) {
                    $this->session->set_flashdata('message', form_error('email'));
                }elseif (form_error('principal')) {
                    $this->session->set_flashdata('message', form_error('principal'));
                }
                redirect('/list_school/');
                exit();
            }

            $search_session = array(
                's_school_name'  => $this->input->post('school_name'),
                's_principal'     => $this->input->post('principal'),                
                's_email' => $this->input->post('email')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('list_school/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('school_name' => $this->input->post('school_name'), 'principal' => $this->input->post('principal'), 'email' => $this->input->post('email'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_school_model->count_all_search_school($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }


        }elseif($search == "session") {
            $base_url = site_url('list_school/index/'. $order_by .'/'. $sort_order .'/session');
             $search_data = array('school_name' => $this->input->post('school_name'), 'principal' => $this->input->post('principal'), 'city' => $this->input->post('city'), 'email' => $this->input->post('email'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_school_model->count_all_search_school($search_data);
            $content_data['search'] = "session";

        }else{
		
            $unset_search_session = array('s_school_name' => '', 's_principal' => '', 's_city' => '', 's_email' => '');
            $this->session->unset_userdata($unset_search_session);
            $content_data['total_rows'] = $config['total_rows'] = $this->list_school_model->count_all_school();
            $base_url = site_url('list_school/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }

        // set content data
		$content_data['arr_show_total_grade'] = array('Yes'=>'Yes','No'=>'No');
		$content_data['arr_show_grade_range'] = array('Yes'=>'Yes','No'=>'No');
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = $this->list_school_model->get_school($per_page, $offset, $order_by, $sort_order, $search_data);
		
        if (empty($data)) {
            redirect("/list_school");
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
        
        $this->template->title('List School');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');

		$this->template->build('list_school', $content_data);
		
    }
    
	public function index_json($order_by = "school_id", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('school_id','school_name','principal','email','www_address','address','city','state','zip','area_code','phone');
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
		$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    
    	$data = $this->list_school_model->get_school($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_school_model->count_all_search_school($grid_data['search_data']);
    
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
    			$row[] = $result_row["school_id"];
    			$row[] = $result_row["school_name"];
    			$row[] = $result_row["principal"];
    			$row[] = $result_row["email"];
    			$row[] = $result_row["www_address"];
    			$row[] = $result_row["address"];
    			$row[] = $result_row["city"];
    			$row[] = $result_row["state"];
    			$row[] = $result_row["zip"];
    			$row[] = $result_row["area_code"];
    			$row[] = $result_row["phone"];
    			$row[] = $result_row["school_id"];
    			$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
    
 	public function add($id = null){
    	
    	$content_data['id'] = $id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_school_model->get_school_data($id);
    
    	}
    	 
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		
    		$school_name = $this->input->post('school_name');
    		$principal = $this->input->post('principal');
    		$email = $this->input->post('email');
    		$www_address = $this->input->post('www_address');
    		$address = $this->input->post('address');
    		$city = $this->input->post('city');
    		$state = $this->input->post('state');
    		$zip = $this->input->post('zip');
    		$area_code = $this->input->post('area_code');
    		$phone = $this->input->post('phone');
    		$show_total_grade = $this->input->post('show_total_grade');
    		$show_grade_range = $this->input->post('show_grade_range');
    		$attendance_time_limit = $this->input->post('attendance_time_limit');
    		$grade_time_limit = $this->input->post('grade_time_limit');
    		
    		$error = "";
    		$error_seperator = "<br>";
    		if($id){
    			 
    			$this->form_validation->set_rules('school_name', 'School Name', 'trim|required|max_length[40]|min_length[2]');
		        
		       	if (!$this->form_validation->run()) {
    				if (form_error('school_name')) {
    					$error .= form_error('school_name').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    			
    			$data = array();
    			$data['school_name'] = $school_name;
    			$data['principal'] = $principal;
    			$data['email'] = $email;
    			$data['www_address'] = $www_address;
    			$data['address'] = $address;
    			$data['city'] = $city;
    			$data['state'] = $state;
    			$data['zip'] = $zip;
    			$data['area_code'] = $area_code;
    			$data['phone'] = $phone;
    			$data['show_total_grade'] = $show_total_grade;
    			$data['show_grade_range'] = $show_grade_range;
    			$data['attendance_time_limit'] = $attendance_time_limit;
    			$data['grade_time_limit'] = $grade_time_limit;
    			$table = 'school';
    			$wher_column_name = 'school_id';
    			grid_data_updates($data,$table,$wher_column_name,$id);
    		}else{
    			 
    			$this->form_validation->set_rules('school_name', 'School Name', 'trim|required|max_length[40]|min_length[2]');

		       	if (!$this->form_validation->run()) {
    				if (form_error('school_name')) {
    					$error .= form_error('school_name').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    			 
    			$data = array();
    			$data['school_name'] = $school_name;
    			$data['principal'] = $principal;
    			$data['email'] = $email;
    			$data['www_address'] = $www_address;
    			$data['address'] = $address;
    			$data['city'] = $city;
    			$data['state'] = $state;
    			$data['zip'] = $zip;
    			$data['area_code'] = $area_code;
    			$data['phone'] = $phone;
    			$data['show_total_grade'] = $show_total_grade;
    			$data['show_grade_range'] = $show_grade_range;
    			$data['attendance_time_limit'] = $attendance_time_limit;
    			$data['grade_time_limit'] = $grade_time_limit;
    			$table = 'school';
    			grid_add_data($data,$table);
    		}
    		exit;
    	}
    	$this->template->build('add_school_datatable', $content_data);
    }
    
    public function add_school(){
    	//Post data
    	$school_name = $this->input->post('school_name');
    	$principal = $this->input->post('principal');
    	$email = $this->input->post('email');
    	$www_address = $this->input->post('www_address');
    	$address = $this->input->post('address');
    	$city = $this->input->post('city');
    	$state = $this->input->post('state');
    	$zip = $this->input->post('zip');
    	$area_code = $this->input->post('area_code');
    	$phone = $this->input->post('phone');
    	$show_total_grade = $this->input->post('show_total_grade');
    	$show_grade_range = $this->input->post('show_grade_range');
    	$attendance_time_limit = $this->input->post('attendance_time_limit');
    	$grade_time_limit = $this->input->post('grade_time_limit');
    
    	$data = array();
    	$data['school_name'] = $school_name;
    	$data['principal'] = $principal;
    	$data['email'] = $email;
    	$data['www_address'] = $www_address;
    	$data['address'] = $address;
    	$data['city'] = $city;
    	$data['state'] = $state;
    	$data['zip'] = $zip;
    	$data['area_code'] = $area_code;
    	$data['phone'] = $phone;
    	$data['show_total_grade'] = $show_total_grade;
    	$data['show_grade_range'] = $show_grade_range;
    	$data['attendance_time_limit'] = $attendance_time_limit;
    	$data['grade_time_limit'] = $grade_time_limit;
    
    	//Table name
    	$table = 'school';
    
    	grid_add_data($data,$table);
    }
    
    public function update_school(){
    	$error = "";
    	$error_seperator = "<br>";
    	
    	$value = $this->input->post('value');
    	$columnName = $this->input->post('columnName');
    	$id = $this->input->post('id');
    	$tablename = 'school';
    	$whrid_column = 'school_id';
    	
    	if($columnName != '') $_POST[$columnName] = $value;
    	$this->form_validation->set_error_delimiters('', '');
    	if ($columnName == 'school_name')
    		$this->form_validation->set_rules('school_name', 'School Name', 'trim|required');
    	
    	if (!$this->form_validation->run()) {
    		if (form_error('school_name')) {
    			$error .= form_error('school_name').$error_seperator;
    		}
    	
    		if($error <> ""){
    		echo $error;
    		exit();
    		}
    	}
    	
    	grid_update_data($whrid_column,$id,$columnName,$value,$tablename);
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
		

        $username = $this->list_school_model->get_school_name_by_id($id);
        
        if (!$this->form_validation->run()) {
            if (form_error('school_name')) {
                $this->session->set_flashdata('message', form_error('school_name'));
            }elseif (form_error('email')) {
                $this->session->set_flashdata('message', form_error('email'));
            }elseif (form_error('principal')) {
                $this->session->set_flashdata('message', form_error('principal'));
            }
            redirect('/list_school/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }

        $this->list_school_model->update_school($this->input->post('school_id'),$this->input->post('school_name'), $this->input->post('address'), $this->input->post('city'), $this->input->post('state'), $this->input->post('zip'), $this->input->post('area_code'),$this->input->post('phone'),$this->input->post('principal'),$this->input->post('www_address'),$this->input->post('email'),$this->input->post('show_total_grade'),$this->input->post('show_grade_range'),$this->input->post('attendance_time_limit'),$this->input->post('grade_time_limit'),$this->input->post('min_referee_count'),$this->input->post('min_experience'));
        set_activity_log($this->input->post('school_id'),'update','school','list school');
        $this->session->set_flashdata('message', sprintf($this->lang->line('school_updated'), $this->input->post('school_name'), $this->input->post('school_id')));
        redirect('/list_school/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

     /**
     *
     * _delete_school: delete school from school
     *
     * @param int $id the id of the member to be deleted
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _delete_school($id, $offset, $order_by, $sort_order, $search) {
        $username = $this->list_school_model->get_school_name_by_id($id);
        if ($username == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noremove'));
        }elseif ($this->list_school_model->delete_member($id)) {
        	set_activity_log($id,'delete','school','list school');
            $this->session->set_flashdata('message', sprintf($this->lang->line('school_deleted'), $username, $id));
        }
        redirect('/list_school/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

    
}

/* End of file list_school.php */