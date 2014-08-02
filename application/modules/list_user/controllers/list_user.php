<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_user extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->model('list_course/courses_model');
        $this->load->model('list_student/list_teacher_student_model');
		$this->load->model('list_user_model');
		$this->load->helper('general_function');
		$this->load->model('add_privilege/privilege_model');
		$this->load->model('list_school/list_school_model');
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

    public function index($order_by = "username", $sort_order = "asc", $search = "all", $offset = 0) {
    	
        if (!is_numeric($offset)) {
            redirect('/list_user');
        }

        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_username' => '', 's_first_name' => '', 's_email' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('username', 'username', 'trim|max_length[50]');
            $this->form_validation->set_rules('first_name', 'full name', 'trim|max_length[40]');
            $this->form_validation->set_rules('email', 'email', 'trim|max_length[255]');

            if (empty($_POST['username']) && empty($_POST['first_name']) && empty($_POST['email'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_user/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('username')) {
                    $this->session->set_flashdata('message', form_error('username'));
                }elseif (form_error('email')) {
                    $this->session->set_flashdata('message', form_error('email'));
                }elseif (form_error('first_name')) {
                    $this->session->set_flashdata('message', form_error('first_name'));
                }
                redirect('/list_user/');
                exit();
            }

            $search_session = array(
                's_username'  => $this->input->post('username'),
                's_first_name'     => $this->input->post('first_name'),
                's_email' => $this->input->post('email')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('list_members/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('username' => $this->input->post('username'), 'first_name' => $this->input->post('first_name'), 'email' => $this->input->post('email'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_teacher_student_model->count_all_teacher_search_members($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }


        }elseif($search == "session") {
            $base_url = site_url('list_members/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('username' => $this->session->userdata('s_username'), 'first_name' => $this->session->userdata('s_first_name'), 'email' => $this->session->userdata('s_email'));
            $content_data['total_rows'] = $config['total_rows'] = $this->list_teacher_student_model->count_all_teacher_search_members($search_data);
            $content_data['search'] = "session";

        }else{
            $unset_search_session = array('s_username' => '', 's_first_name' => '', 's_email' => '');
            $this->session->unset_userdata($unset_search_session);
            $content_data['total_rows'] = $config['total_rows'] = $this->list_teacher_student_model->count_all_teacher_members();
            $base_url = site_url('list_members/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }

        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = $this->list_teacher_student_model->get_teacher($per_page, $offset, $order_by, $sort_order, $search_data);
        if (empty($data)) {
            //redirect("/list_user");
        }else{
            $content_data['members'] = $data;
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
        
        $this->template->title('List User');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_user', $content_data);
    }
    
    public function index_json($order_by = "username", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('users.user_id',
						'users.elsd_id',
						'staff_name',
						'users.email',
						'users.personal_email',
						'users.work_mobile',
						'contractors.contractor',
						'users.status',
						'user_roll.user_roll_name',
						'department.department_name',
						'school_campus.campus_name',
						'user_profile.scanner_id',
						'user_profile.returning',
						'users.created_date',
						'users.updated_date');
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
		$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];
    
    	$data = $this->list_teacher_student_model->get_staff_members("newemployee",$per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_teacher_student_model->get_staff_members("newemployee",0, 0, "", "", $grid_data['search_data']);
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
				$row[] = $result_row['user_id'];
				$row[] = $result_row['elsd_id'];
				$row[] = $result_row['staff_name'];
				$row[] = $result_row['email'];
				$row[] = $result_row['personal_email'];
				$row[] = $result_row['work_mobile'];
				$row[] = $result_row['contractor'];
				$row[] = $result_row['status'];
				$row[] = $result_row['user_roll_name'];
				$row[] = $result_row['department_name'];
				$row[] = $result_row['campus_name'];
				$row[] = $result_row['scanner_id'];
				$row[] = $result_row['returning'];
				$row[] = $result_row['created_date'];
				$row[] = $result_row['updated_date'];
    			$row[] = $result_row["user_id"];
    			$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
    
    public function add($id = null){
    	$content_data['teacher_list'] = get_teacher_list();
    	$content_data['other_user_roll'] = get_other_user_roll();
    	$content_data['campus_list'] = get_campus_list(1);
		
    	$content_data['id'] = $id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_teacher_student_model->get_teacher_data($id);
    
    	}
    	 
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$nonce = md5(uniqid(mt_rand(), true));
			$campus_id = $this->input->post('campus_id');
    		$first_name = $this->input->post('first_name');
    		$name_suffix = $this->input->post('name_suffix');
    		$email = $this->input->post('email');
    		$address1 = $this->input->post('address1');
    		$address2 = $this->input->post('address2');
    		$city = $this->input->post('city');
    		$state = $this->input->post('state');
    		$zip = $this->input->post('zip');
    		$birth_date = $this->input->post('birth_date');
    		$birth_place = $this->input->post('birth_place');
    		$language_known = $this->input->post('language_known');
    		$work_phone = $this->input->post('work_phone');
    		$home_phone = $this->input->post('home_phone');
    		$cell_phone = $this->input->post('cell_phone');
    		$username = $this->input->post('username');
    		$password = $this->input->post('password');
    		$user_roll_id = $this->input->post('user_roll_id');
    		
			$where_campus[] = array("campus_id"=>$campus_id);
			$campus_arr = get_campus_name($where_campus);
			$campus = "";
			
			if(isset($campus_arr["campusname"]))
				$campus = $campus_arr["campusname"];
				
    		$error = "";
    		$error_seperator = "<br>";
    		if($id){
    			 
    			$this->form_validation->set_rules('first_name', 'first name', 'trim|required|max_length[40]|min_length[2]');
		        $this->form_validation->set_rules('email', 'email', 'trim|required|max_length[255]|is_valid_email|is_existing_field[users.email^users.user_id !=^'.$id.']');
				
		       	if (!$this->form_validation->run()) {
    				if (form_error('first_name')) {
    					$error .= form_error('first_name').$error_seperator;
    				}elseif (form_error('email')) {
    					$error .= form_error('email').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    			
    			$data = array();
    			$data['first_name'] = $first_name;
				$data['campus_id'] = $campus_id;
				$data['campus'] = $campus;
    			$data['name_suffix'] = $name_suffix;
    			$data['email'] = $email;
    			$data['address1'] = $address1;
    			$data['address2'] = $address2;
    			$data['city'] = $city;
    			$data['state'] = $state;
    			$data['zip'] = $zip;
    			$data['birth_date'] = date('Y-m-d',strtotime($birth_date));
    			$data['birth_place'] = $birth_place;
    			$data['language_known'] = $language_known;
    			$data['work_phone'] = $work_phone;
    			$data['home_phone'] = $home_phone;
    			$data['cell_phone'] = $cell_phone;
    			$data['user_roll_id'] = $user_roll_id;
    			$data['updated_date'] = date('Y-m-d H:i:s');
    			
    			$table = 'users';
    			$wher_column_name = 'user_id';
    			set_activity_data_log($id,'Update','Role & User > List User','List User',$table,$wher_column_name,$user_id='');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
    			 
    			$this->form_validation->set_rules('first_name', 'first name', 'trim|required|max_length[40]|min_length[2]');
		        $this->form_validation->set_rules('email', 'e-mail', 'trim|required|max_length[255]|is_valid_email|is_existing_unique_field[users.email]');
				$this->form_validation->set_rules('username', 'username', 'trim|required|max_length[50]|min_length[6]|is_existing_unique_field[users.username]');
		        $this->form_validation->set_rules('password', 'password', 'trim|required|max_length[64]|matches[password_confirm]');
		        $this->form_validation->set_rules('password_confirm', 'repeat password', 'trim|required|max_length[64]');

		       	if (!$this->form_validation->run()) {
    				if (form_error('first_name')) {
    					$error .= form_error('first_name').$error_seperator;
    				}elseif (form_error('email')) {
    					$error .= form_error('email').$error_seperator;
    				}elseif (form_error('username')) {
    					$error .= form_error('username').$error_seperator;
    				}elseif (form_error('password')) {
    					$error .= form_error('password').$error_seperator;
    				}elseif (form_error('password_confirm')) {
    					$error .= form_error('password_confirm').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    			 
    			$data = array();
    			$data['first_name'] = $first_name;
				$data['campus_id'] = $campus_id;
    			$data['campus'] = $campus;
    			$data['name_suffix'] = $name_suffix;
    			$data['email'] = $email;
    			$data['address1'] = $address1;
    			$data['address2'] = $address2;
    			$data['city'] = $city;
    			$data['state'] = $state;
    			$data['zip'] = $zip;
    			$data['birth_date'] = date('Y-m-d',strtotime($birth_date));
    			$data['birth_place'] = $birth_place;
    			$data['language_known'] = $language_known;
    			$data['work_phone'] = $work_phone;
    			$data['home_phone'] = $home_phone;
    			$data['cell_phone'] = $cell_phone;
    			$data['username'] = $username;
    			$data['password'] = hash_password($password, $nonce);
    			$data['user_roll_id'] = $user_roll_id;
    			$data['active'] = '1';
    			$data['nonce'] = $nonce;
    			$data['created_date'] = date('Y-m-d H:i:s');
    			$table = 'users';
    			$wher_column_name = 'user_id';
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Role & User > List User','List User',$table,$wher_column_name,$user_id='');
    		}
    		exit;
    	}
    	$this->template->build('add_user_datatable', $content_data);
    }
    
    public function add_student(){
    	//Post data
    	$first_name = $this->input->post('first_name');
    	$name_suffix = $this->input->post('name_suffix');
    	$username = $this->input->post('username');
    	$email = $this->input->post('email');
    	$address1 = $this->input->post('address1');
    	$address2 = $this->input->post('address2');
    	$city = $this->input->post('city');
    	$state = $this->input->post('state');
    	$zip = $this->input->post('zip');
    	$birth_date = $this->input->post('birth_date');
    	$birth_place = $this->input->post('birth_place');
    	$language_known = $this->input->post('language_known');
    	$work_phone = $this->input->post('work_phone');
    	$home_phone = $this->input->post('home_phone');
    	$cell_phone = $this->input->post('cell_phone');
    	$user_roll_id = $this->input->post('user_roll_id');
    
    	$data = array();
    	$data['user_roll_id'] = $user_roll_id;
    	$data['first_name'] = $first_name;
    	$data['name_suffix'] = $name_suffix;
    	$data['username'] = $username;
    	$data['email'] = $email;
    	$data['address1'] = $address1;
    	$data['address2'] = $address2;
    	$data['city'] = $city;
    	$data['state'] = $state;
    	$data['zip'] = $zip;
    	$data['birth_date'] = $birth_date;
    	$data['birth_place'] = $birth_place;
    	$data['language_known'] = $language_known;
    	$data['work_phone'] = $work_phone;
    	$data['home_phone'] = $home_phone;
    	$data['cell_phone'] = $cell_phone;
    	$data['created_date'] = date('Y-m-d H:i:s');
    
    	//Table name
    	$table = 'users';
    
    	grid_add_data($data,$table);
    }
    
    public function update_user(){
    	$error = "";
    	$error_seperator = "<br>";
    	
    	$value = $this->input->post('value');
    	$columnName = $this->input->post('columnName');
    	$id = $this->input->post('id');
    	$tablename = 'users';
    	$whrid_column = 'user_id';
    	
    	if($columnName != '') $_POST[$columnName] = $value;
    	$this->form_validation->set_error_delimiters('', '');
    	if ($columnName == 'username')
    		$this->form_validation->set_rules('username', 'username', 'trim|required|max_length[50]|min_length[6]|is_existing_field[users.username^users.user_id !=^'.$id.']');
    	if ($columnName == 'email')
    		$this->form_validation->set_rules('email', 'email', 'trim|required|max_length[255]|is_valid_email|is_existing_field[users.email^users.user_id !=^'.$id.']');
    	if ($columnName == 'first_name')
    		$this->form_validation->set_rules('first_name', 'first name', 'trim|required|max_length[40]|min_length[2]');
    	if ($columnName == 'password'){
    		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
    	}
    	 
    	if (!$this->form_validation->run()) {
    		if (form_error('username')) {
    			$error .= form_error('username').$error_seperator;
    		}elseif (form_error('email')) {
    			$error .= form_error('email').$error_seperator;
    		}elseif (form_error('first_name')) {
    			$error .= form_error('first_name').$error_seperator;
    		}elseif (form_error('password')) {
    			$error .= form_error('password').$error_seperator;
    		}
    		if($error <> ""){
    		echo $error;
    		exit();
    		}
    	}
    	
    	if($columnName == "campus")
			$columnName = "campus_id";
			
    	set_activity_data_log($id,'Update','Role & User > List User','List User',$tablename,$whrid_column,$user_id='');
    	grid_update_data($whrid_column,$id,$columnName,$value,$tablename);
    	
    	if($columnName == "campus_id")
		{
			$where_campus[] = array($columnName=>$value);
			$campus_arr = get_campus_name($where_campus);
			$value_campus = "";
			
			if(isset($campus_arr["campusname"]))
				$value_campus = $campus_arr["campusname"];
			
			grid_update_data($whrid_column,$id,"campus",$value_campus,$tablename);
			echo "success";
		}		
    }
    
    public function get_listbox($type){
    	$jsondata = '';
    	if($type == 'other_user_roll'){
    		$other_user_roll = get_other_user_roll();
    		$jsondata .= '{';
    		foreach ($other_user_roll as $key => $val){
    			 
    			 
    			if(end($other_user_roll) == $val){
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\'';
    			}else{
    				$jsondata .= '\''.$key.'\''.':'.'\''.$val.'\''.',';
    			}
    			 
    		}
    		$jsondata .= '}';
    		echo $jsondata;
    	}
    }

    public function action_member($id, $offset, $order_by, $sort_order, $search) {
        if (array_key_exists('update', $_POST)) {
            $this->_update_teacher($id, $offset, $order_by, $sort_order, $search);
        }else{
            $this->_delete_teacher($id, $offset, $order_by, $sort_order, $search);
        }
    }

    /**
     *
     * _update_teacher: update teacher info from adminpanel
     *
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _update_teacher($id, $offset, $order_by, $sort_order, $search) {
        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('first_name', 'full name', 'trim|required|max_length[40]|min_length[2]');
        $this->form_validation->set_rules('name_suffix', 'name suffix', 'trim|required');
		
		if ($this->input->post('username_box') == true) {
            $this->form_validation->set_rules('username', 'username', 'trim|required|max_length[50]|min_length[6]|is_existing_unique_field[users.username]');
        }
        if ($this->input->post('email_box') == true) {
            $this->form_validation->set_rules('email', 'email', 'trim|required|max_length[255]|is_valid_email|is_existing_unique_field[users.email]');
        }
		

        $username = $this->list_teacher_student_model->get_username_by_id($id);
        if ($username == ADMINISTRATOR && $this->input->post('username_box') == true) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noedit'));
            redirect('/list_user');
            exit();
        }

        if (!$this->form_validation->run()) {
            if (form_error('username')) {
                $this->session->set_flashdata('message', form_error('username'));
            }elseif (form_error('email') && ($this->input->post('email_box') == true)) {
                $this->session->set_flashdata('message', form_error('email'));
            }elseif (form_error('first_name')) {
                $this->session->set_flashdata('message', form_error('first_name'));
            }elseif (form_error('name_suffix')) {
                $this->session->set_flashdata('message', form_error('name_suffix'));
            }elseif (form_error('birth_date')) {
                $this->session->set_flashdata('message', form_error('birth_date'));
            }elseif (form_error('birth_place')) {
                $this->session->set_flashdata('message', form_error('birth_place'));
            }
            redirect('/list_user/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }

        $this->list_teacher_student_model->update_member($this->input->post('user_id'), $this->input->post('username'), $this->input->post('email'), $this->input->post('first_name'), $this->input->post('middle_name'),$this->input->post('name_suffix'),$this->input->post('address1'),$this->input->post('address2'),$this->input->post('city'),$this->input->post('state'),$this->input->post('zip'),$this->input->post('birth_date'),$this->input->post('birth_place'),$this->input->post('gender'),$this->input->post('language_known'),$this->input->post('work_phone'),$this->input->post('home_phone'),$this->input->post('cell_phone'), $this->input->post('username_box'), $this->input->post('email_box'));
        set_activity_log($this->input->post('user_id'),'update','teacher','list teacher');
        $this->session->set_flashdata('message', sprintf($this->lang->line('member_updated'), $this->input->post('username'), $this->input->post('id')));
        redirect('/list_user/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

     /**
     *
     * _delete_teacher: delete teacher from adminpanel
     *
     * @param int $id the id of the member to be deleted
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _delete_teacher($id, $offset, $order_by, $sort_order, $search) {
        $username = $this->list_teacher_student_model->get_username_by_id($id);
        if ($username == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noremove'));
        }elseif ($this->list_teacher_student_model->delete_member($id)) {
        	set_activity_log($id,'delete','teacher','list teacher');
            $this->session->set_flashdata('message', sprintf($this->lang->line('member_deleted'), $username, $id));
        }
        redirect('/list_user/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

     /**
     *
     * toggle_active: (de)activate member from adminpanel
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
        if ($this->list_teacher_student_model->get_username_by_id($id) == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noactivate'));
            redirect('/list_user/index');
            return;
        }elseif ($this->list_teacher_student_model->toggle_active($id, $active)) {
            $active ? $active = $this->lang->line('deactivated') : $active = $this->lang->line('activated');
            set_activity_log($id,$active,'teacher','list teacher');
            $this->session->set_flashdata('message', sprintf($this->lang->line('toggle_active'), $username) . $active);
        }
        redirect('/list_user/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
	
	public function delete($id = null){
    	if($id){
			$table = 'users';
			$wher_column_name = 'user_id';
			set_activity_data_log($id,'Delete','Role & User > List User','List User',$table,$wher_column_name,$user_id='');
			
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/list_user/');
        exit();
	}
	
	 public function add_profile() {
	 	
		if(isset($_POST['submit']) && $this->input->post('submit') == 'Save') {			
			$user_id = 0;
			$error = "";
			$error_seperator = "<br>";
			$nonce = md5(uniqid(mt_rand(), true));
			
			$first_name = $this->input->post('first_name');
			$email = $this->input->post('username');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$password_confirm = $this->input->post('password_confirm');
			$hash_password = hash_password($password, $nonce);
			
			$this->form_validation->set_rules('first_name', 'first name', 'trim|required|max_length[40]|min_length[2]');
			//$this->form_validation->set_rules('email', 'e-mail', 'trim|required|max_length[255]|is_valid_email|is_existing_unique_field[users.email]');
			$this->form_validation->set_rules('username', 'username', 'trim|required|max_length[255]|is_existing_unique_field[users.username]');
			$this->form_validation->set_rules('password', 'password', 'trim|required|max_length[64]|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', 'repeat password', 'trim|required|max_length[64]');
			
			if (!$this->form_validation->run()) {
				if (form_error('first_name')) {
					$this->session->set_flashdata('message', form_error('first_name'));
				//}elseif (form_error('email')) {
					//$this->session->set_flashdata('message', form_error('email'));
				}elseif (form_error('username')) {
					$this->session->set_flashdata('message', form_error('username'));
				}elseif (form_error('password')) {
					$this->session->set_flashdata('message', form_error('password'));
				}elseif (form_error('password_confirm')) {
					$this->session->set_flashdata('message', form_error('password_confirm'));
				}
				redirect('list_user/add_profile');
				//exit();
			}else {
				$elsd_id = generateElsdId($this->input->post('gender'));
				$user_data = array(
						'active'       => $this->input->post('status'),
						'first_name'       => $this->input->post('first_name'),
						'middle_name'       => $this->input->post('middle_name'),
						'last_name'       => $this->input->post('last_name'),
						'gender'       => $this->input->post('gender'),
						'elsd_id'       => $elsd_id,
						'email'       => $email,
						'username'       => $username,
						'password'       => $hash_password,
						'birth_date'       => date('Y-m-d',strtotime($this->input->post('birth_date'))),
						'user_roll_id'       => $this->input->post('user_roll_id'),
						'campus_id'       => (int)$this->input->post('campus_id'),
						'coordinator'       => $this->input->post('coordinator'),
						'cell_phone'       => $this->input->post('cell_phone'),
						'home_phone'       => $this->input->post('home_phone'),
						'work_mobile'       => $this->input->post('work_mobile'),
						'work_phone'       => $this->input->post('work_phone'),
						'work_extention'       => $this->input->post('work_extention'),
						'personal_email'       => $this->input->post('personal_email'),
						'nonce' => $nonce,
						'created_date' => date('Y-m-d H:i:s')
					);
				$user_id = grid_add_data($user_data,'users');
					
				$profile_data = array(
						'user_id'       => $user_id,
						'scanner_id'       => $this->input->post('scanner_id'),
						'nationality'       => $this->input->post('nationality'),
						'marital_status'       => $this->input->post('marital_status'),
						'job_title'       => $this->input->post('job_title'),
						//'ecl_access'       => $this->input->post('ecl_access'),
						//'banned_teacher'       => $this->input->post('banned_teacher'),
						//'responsibilities'       => $this->input->post('responsibilities'),
						'cy_joining_date'       => date('Y-m-d',strtotime($this->input->post('cy_joining_date'))),
						'original_start_date'       => date('Y-m-d',strtotime($this->input->post('original_start_date'))),
						'returning'       => $this->input->post('returning'),
						'teaching_experience'       => $this->input->post('teaching_experience'),
						'contractor'       => $this->input->post('contractor'),
						'office_no'       => $this->input->post('office_no'),
						'blood_type'       => $this->input->post('blood_type'),
						'medical_condition'       => $this->input->post('medical_condition'),
						'medical_allergies'       => $this->input->post('medical_allergies'),
						'on_timetable'       => $this->input->post('on_timetable'),
						'department_id'       => $this->input->post('department_id')
					);
				$profile_id = grid_add_data($profile_data,'user_profile');
				
				$user_cv_reference1 = array(
							'user_id'       => $user_id,
							'profile_id'       => $profile_id,
							'company_name'       => $this->input->post('cv_reference_company_name_1'),
							'name'       => $this->input->post('cv_reference_name_1'),
							'position'       => $this->input->post('cv_reference_position_1'),
							'contact_number'       => $this->input->post('cv_reference_contact_number_1'),
							'email'       => $this->input->post('cv_reference_email_1'),
							'cv_confirm'       => $this->input->post('cv_confirm_1')
						);
				grid_add_data($user_cv_reference1,'user_cv_reference');		
				$user_cv_reference2 = array(
							'user_id'       => $user_id,
							'profile_id'       => $profile_id,
							'company_name'       => $this->input->post('cv_reference_company_name_2'),
							'name'       => $this->input->post('cv_reference_name_2'),
							'position'       => $this->input->post('cv_reference_position_2'),
							'contact_number'       => $this->input->post('cv_reference_contact_number_2'),
							'email'       => $this->input->post('cv_reference_email_2'),
							'cv_confirm'       => $this->input->post('cv_confirm_2')
						);
				grid_add_data($user_cv_reference2,'user_cv_reference');
				
				$user_verifications = array(
						'user_id'       => $user_id,
						'ver_nationality'       => $this->input->post('ver_nationality'),
						/*'ver_qualification'       => $this->input->post('ver_qualification'),*/
						'degree_comments'       => $this->input->post('degree_comments'),
						'ver_experience'       => $this->input->post('ver_experience'),
						'ver_reference'       => $this->input->post('ver_reference'),
						'interviewee1'       => $this->input->post('interviewee1'),
						'interviewee2'       => $this->input->post('interviewee2'),
						'interview_date'       => date('Y-m-d',strtotime($this->input->post('interview_date'))),
						'interview_successful'       => $this->input->post('interview_successful'),
						'interview_notes'       => $this->input->post('interview_notes'),
						'updated_at'       => date('Y-m-d H:i:s')
					);
				
				grid_add_data($user_verifications,'user_verifications');
				
				$roll_privilege = $this->list_user_model->get_existing_privilege($this->input->post('user_roll_id'));
				$this->list_user_model->create_single_user_privilege($user_id, $roll_privilege);
				
				redirect('list_user/edit_profile/'.$user_id.'/');		
			}						
			
		}

    	$content_data = array();
		$content_data['user_profile_status'] = user_profile_status();
		$content_data['other_user_roll'] = get_other_user_roll();
		$content_data['other_user_list'] = get_other_user_list();
		$content_data['nationality_list'] = get_nationality_list();
		$content_data['campus_list'] = get_campus_list(1);
		$content_data['department_list'] = get_department_list();
		
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Add User');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('add_profile', $content_data);
    }
	
	public function edit_profile($user_id = 0,$tab_id = 0) {
		if(isset($_POST['submit']) && $this->input->post('submit') == 'Save') {
			
			$error = "";
			$error_seperator = "<br>";
			$user_id = $this->input->post('user_id');
			if($this->input->post('action') == 'save_departure') {
				$departure_data = array(
							'user_id'       => $user_id,
							'resignation_resons'       => $this->input->post('resignation_resons'),
							'departure_notes'       => $this->input->post('departure_notes'),
							'exit_cleared'       => $this->input->post('exit_cleared'),
							'last_day_of_work'       => date('Y-m-d',strtotime($this->input->post('last_day_of_work')))
						);
				grid_data_updates($departure_data,'user_profile', 'user_id',$user_id);
			}else {	
				$nonce = md5(uniqid(mt_rand(), true));
			
				$first_name = $this->input->post('first_name');
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				$password_confirm = $this->input->post('password_confirm');
				$hash_password = hash_password($password, $nonce);
				
				$this->form_validation->set_rules('first_name', 'first name', 'trim|required|max_length[40]|min_length[2]');
				//$this->form_validation->set_rules('email', 'e-mail', 'trim|required|max_length[255]|is_valid_email|is_existing_unique_field[users.email]');
				//$this->form_validation->set_rules('username', 'username', 'trim|required|max_length[255]|is_existing_unique_field[users.username]');
				$this->form_validation->set_rules('username', 'username', 'trim|required|max_length[255]|is_existing_field[users.username^users.user_id !=^'.$user_id.']');
				
				if (!$this->form_validation->run()) {
					if (form_error('first_name')) {
						$this->session->set_flashdata('message', form_error('first_name'));
					}elseif (form_error('username')) {
						$this->session->set_flashdata('message', form_error('username'));
					}
					redirect('list_user/edit_profile/'.$user_id);
					//exit();
				}else {
					$old_roll_id = $this->list_user_model->get_user_roll($user_id);
					$user_data = array(
							'active'       => $this->input->post('status'),
							'first_name'       => $this->input->post('first_name'),
							'middle_name'       => $this->input->post('middle_name'),
							'last_name'       => $this->input->post('last_name'),
							'gender'       => $this->input->post('gender'),
							'username'       => $username,
							'birth_date'       => date('Y-m-d',strtotime($this->input->post('birth_date'))),
							'user_roll_id'       => $this->input->post('user_roll_id'),
							'campus_id'       => (int)$this->input->post('campus_id'),
							'coordinator'       => $this->input->post('coordinator'),
							'cell_phone'       => $this->input->post('cell_phone'),
							'home_phone'       => $this->input->post('home_phone'),
							'work_mobile'       => $this->input->post('work_mobile'),
							'work_phone'       => $this->input->post('work_phone'),
							'work_extention'       => $this->input->post('work_extention'),
							'personal_email'       => $this->input->post('personal_email'),
							'updated_date' => date('Y-m-d H:i:s')
						);
						
					if($password <> '') {
						$user_data['password'] =  $hash_password;
						$user_data['nonce'] = $nonce;	
					}
						
					grid_data_updates($user_data,'users', 'user_id',$user_id);
						
					$profile_data = array(
							'user_id'       => $user_id,
							'scanner_id'       => $this->input->post('scanner_id'),
							'nationality'       => $this->input->post('nationality'),
							'marital_status'       => $this->input->post('marital_status'),
							'job_title'       => $this->input->post('job_title'),
							//'ecl_access'       => $this->input->post('ecl_access'),
							//'banned_teacher'       => $this->input->post('banned_teacher'),
							//'responsibilities'       => $this->input->post('responsibilities'),
							'cy_joining_date'       => date('Y-m-d',strtotime($this->input->post('cy_joining_date'))),
							'original_start_date'       => date('Y-m-d',strtotime($this->input->post('original_start_date'))),
							'returning'       => $this->input->post('returning'),
							'teaching_experience'       => $this->input->post('teaching_experience'),
							'contractor'       => $this->input->post('contractor'),
							'office_no'       => $this->input->post('office_no'),
							'blood_type'       => $this->input->post('blood_type'),
							'medical_condition'       => $this->input->post('medical_condition'),
							'medical_allergies'       => $this->input->post('medical_allergies'),
							'on_timetable'       => $this->input->post('on_timetable'),
							'department_id'       => $this->input->post('department_id')
						);
						
					grid_data_updates($profile_data,'user_profile', 'user_id',$user_id);
					
					$user_cv_reference1 = array(
								'company_name'       => $this->input->post('cv_reference_company_name_1'),
								'name'       => $this->input->post('cv_reference_name_1'),
								'position'       => $this->input->post('cv_reference_position_1'),
								'contact_number'       => $this->input->post('cv_reference_contact_number_1'),
								'email'       => $this->input->post('cv_reference_email_1'),
								'cv_confirm'       => $this->input->post('cv_confirm_1')
							);
							
					
					grid_data_updates($user_cv_reference1,'user_cv_reference', 'referance_id',$this->input->post('referance_id_1'));
					
					$user_cv_reference2 = array(
								'company_name'       => $this->input->post('cv_reference_company_name_2'),
								'name'       => $this->input->post('cv_reference_name_2'),
								'position'       => $this->input->post('cv_reference_position_2'),
								'contact_number'       => $this->input->post('cv_reference_contact_number_2'),
								'email'       => $this->input->post('cv_reference_email_2'),
								'cv_confirm'       => $this->input->post('cv_confirm_2')
							);
							
					grid_data_updates($user_cv_reference2,'user_cv_reference', 'referance_id',$this->input->post('referance_id_2'));
					
					//$user_verifications = array(
//							'ver_nationality'       => $this->input->post('ver_nationality'),
//							/*'ver_qualification'       => $this->input->post('ver_qualification'),*/
//							'degree_comments'       => $this->input->post('degree_comments'),
//							'ver_experience'       => $this->input->post('ver_experience'),
//							'ver_reference'       => $this->input->post('ver_reference'),
//							'interviewee1'       => $this->input->post('interviewee1'),
//							'interviewee2'       => $this->input->post('interviewee2'),
//							'interview_date'       => date('Y-m-d',strtotime($this->input->post('interview_date'))),
//							'interview_successful'       => $this->input->post('interview_successful'),
//							'interview_notes'       => $this->input->post('interview_notes'),
//							'updated_at'       => date('Y-m-d H:i:s')
//						);
//						//print_r($user_verifications);exit;
//					grid_data_updates($user_verifications,'user_verifications', 'user_id',$user_id);
					
					$this->list_user_model->set_user_verifications($user_id); 
					
					$qualifications = $this->input->post('qualifications');
					if(is_array($qualifications) && count($qualifications) > 0){
						foreach($qualifications as $qualification_id=>$qualification){
						
							$accredited=$in_class=$subject_related=0;
							if(isset($qualification['accredited']))
								$accredited = $qualification['accredited'];
							
							if(isset($qualification['in_class']))	
								$in_class = $qualification['in_class'];
							
							if(isset($qualification['subject_related']))
								$subject_related = $qualification['subject_related'];
								
							$updated_at = date('Y-m-d H:i:s');
							
							$user_qualification = array(
								'accredited'       => $accredited,
								'in_class'       => $in_class,
								'subject_related'       => $subject_related,
								'updated_at'       => $updated_at
							);
							
							grid_data_updates($user_qualification,'user_qualification','user_qualification_id',$qualification_id);
						}
					}	
					
					$user_roll_id = $this->input->post('user_roll_id');
					if($old_roll_id != $user_roll_id) {
						$roll_privilege = $this->list_user_model->get_existing_privilege($user_roll_id);
						$this->list_user_model->create_single_user_privilege($user_id, $roll_privilege);
					}
				}						
			}
		}
		
		
		$user_data = $this->list_user_model->get_user_profile($user_id);
    	if(!$user_data)
			redirect('list_user/add_profile');
		
		
		
		$cv_reference_data = $this->list_user_model->get_cv_reference($user_id);
		$cv_reference = array();
		$cv_reference_count = 0;
		if($cv_reference_data){
			$i = 1;
			foreach($cv_reference_data->result_array() as $_cv_reference_data){
				$cv_reference['referance_id_'.$i] = $_cv_reference_data['referance_id'];
				$cv_reference['cv_reference_company_name_'.$i] = $_cv_reference_data['company_name'];
				$cv_reference['cv_reference_name_'.$i] = $_cv_reference_data['name'];
				$cv_reference['cv_reference_position_'.$i] = $_cv_reference_data['position'];
				$cv_reference['cv_reference_contact_number_'.$i] = $_cv_reference_data['contact_number'];
				$cv_reference['cv_reference_email_'.$i] = $_cv_reference_data['email'];
				$cv_reference['cv_confirm_'.$i] = $_cv_reference_data['cv_confirm'];
				$i++;
				$cv_reference_count++;
			}
		}
		
		$emergency_contacts_data = $this->list_user_model->get_emergency_contacts($user_id);
		$emergency_contacts = array();
		if($emergency_contacts_data){
			foreach($emergency_contacts_data->result_array() as $_emergency_contacts_data){
				$row = array();
				$row['emergency_contact_id'] = $_emergency_contacts_data['emergency_contact_id'];
				$row['name'] = $_emergency_contacts_data['name'];
				$row['relation'] = $_emergency_contacts_data['relation'];
				$row['contact_number'] = $_emergency_contacts_data['contact_number'];
				$row['alternate_contact'] = $_emergency_contacts_data['alternate_contact'];
				$row['country_name'] = $_emergency_contacts_data['country_name'];
				
				$emergency_contacts[] = $row;
			}
		}
		
		$user_qualification_data = $this->list_user_model->get_user_quali_certi($user_id,'qualification');
		$user_qualification = array();
		if($user_qualification_data){
			foreach($user_qualification_data->result_array() as $_user_qualification_data){
				$row = array();
				$row['user_qualification_id'] = $_user_qualification_data['user_qualification_id'];
				$row['subject'] = $_user_qualification_data['subject'];
				$row['qualification'] = $_user_qualification_data['qualification'];
				$row['date'] = date("d M Y",strtotime($_user_qualification_data['date']));
				$row['accredited'] = $_user_qualification_data['accredited'];
				$row['in_class'] = $_user_qualification_data['in_class'];
				$row['subject_related'] = $_user_qualification_data['subject_related'];
				$user_qualification[] = $row;
			}
		}
		
		$user_certificate_data = $this->list_user_model->get_user_quali_certi($user_id,'certificate');
		$user_certificate = array();
		if($user_certificate_data){
			foreach($user_certificate_data->result_array() as $_user_certificate_data){
				$row = array();
				$row['user_qualification_id'] = $_user_certificate_data['user_qualification_id'];
				$row['qualification'] = $_user_certificate_data['qualification'];
				$row['date'] = date("d M Y",strtotime($_user_certificate_data['date']));
				
				$user_certificate[] = $row;
			}
		}
		
		$user_experience_data = $this->list_user_model->get_user_experience($user_id,'certificate');
		$user_experience = array();
		$user_experience_count = 0;
		if($user_experience_data){
			foreach($user_experience_data->result_array() as $_user_experience_data){
				$start_date = date(strtotime($_user_experience_data['start_date']));
				$end_date = date(strtotime($_user_experience_data['end_date']));
				$interval = $end_date-$start_date;
				$months = floor($interval / 86400 / 30 );
				$years = 0;
				if($months > 0){
					$years = $months/12;
				}
				$user_experience_count = $user_experience_count + $years;
				$row = array();
				$row['user_workhistory_id'] = $_user_experience_data['user_workhistory_id'];
				$row['company'] = $_user_experience_data['company'];
				$row['position'] = $_user_experience_data['position'];
				$row['start_date'] = date("d M Y",strtotime($_user_experience_data['start_date']));
				$row['end_date'] = date("d M Y",strtotime($_user_experience_data['end_date']));
				$row['departure_reason'] = $_user_experience_data['departure_reason'];
				
				$user_experience[] = $row;
			}
		}
		$user_workshop_data = $this->list_user_model->get_user_workshop($user_id);
		$user_workshop = array();
		if($user_workshop_data){
			foreach($user_workshop_data->result_array() as $_user_workshop_data){
				$row = array();
				$row['title'] = $_user_workshop_data['title'];
				$row['topic'] = $_user_workshop_data['topic'];
				$row['presenter_name'] = $_user_workshop_data['presenter_name'];
				$row['start_date'] = date("d M Y",strtotime($_user_workshop_data['start_date']));
				$row['type'] = $_user_workshop_data['type'];
				
				$user_workshop[] = $row;
			}
		}
		$user_data = (object) array_merge((array)$user_data,$cv_reference,array('emergency_contacts'=>$emergency_contacts),array('user_qualification'=>$user_qualification),array('user_certificate'=>$user_certificate),array('user_experience'=>$user_experience),array('user_workshop'=>$user_workshop));
		$user_permossion = $this->list_user_model->get_user_permossion($user_id);
		$user_documents = $this->list_user_model->get_user_documents($user_id);
	
		$content_data = array();
		$content_data['user_documents'] = $user_documents;
		$content_data['user_permossion'] = $user_permossion;
		$content_data['user_profile_status'] = user_profile_status();
		$content_data['other_user_roll'] = get_other_user_roll();
		$content_data['other_user_list'] = get_other_user_list();
		$content_data['nationality_list'] = get_nationality_list();
		$content_data['campus_list'] = get_campus_list(1);
		$content_data['department_list'] = get_department_list();
		
		$profile_picture = get_profile_pic($user_id);
		$profile_picture = $profile_picture[150];
		$content_data['profile_picture'] = $profile_picture;
	
		if(isset($user_data->nationality) && isset($user_data->ver_nationality) && $user_data->ver_nationality == 0){
			$ver_nationality = $this->list_user_model->is_accepted_nationality($user_data->nationality);
			$user_data->ver_nationality = 2;
			if($ver_nationality){
				$user_data->ver_nationality = 1;
			}
		}
		$school_data = $this->list_school_model->get_school_data(1);
		if(isset($user_data->ver_reference) && $user_data->ver_reference == 0){
			
			$min_referee_count = $school_data->min_referee_count;
			$user_data->ver_reference = 2;
			if($cv_reference_count >= $min_referee_count){
				$user_data->ver_reference = 1;
			}
		}
		if(isset($user_data->ver_experience) && $user_data->ver_experience == 0){
			
			$min_experience = $school_data->min_experience;
			$user_data->ver_experience = 2;
			if($user_experience_count >= $min_experience){
				$user_data->ver_experience = 1;
			}
		}
		
		$content_data['user_data'] = $user_data;
		$content_data['tab_id'] = $tab_id;
		$content_data['previlage_action'] = get_previlege_action();
        $content_data['privilege_data'] = $this->privilege_model->get_menu_actions();
		$content_data['roll_privilege'] = $this->list_user_model->get_existing_privilege($user_data->user_roll_id);
		
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Edit User');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('edit_profile', $content_data);
    }
	
	public function add_qualifications($user_id,$id = null){
    	$content_data['qualifications_list'] = get_qualifications_list();
    			
    	$content_data['id'] = $id;
		$content_data['user_id'] = $user_id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_user_model->get_quali_certi($id,'qualification');
    	}
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		
			$qualification_id = $this->input->post('qualification_id');
    		$subject_related = $this->input->post('subject_related');
    		$subject = $this->input->post('subject');
			$accredited = $this->input->post('accredited');
			$in_class = $this->input->post('in_class');
    		$date = $this->input->post('date');
    		
			$data = array();
			$data['user_id'] = $user_id;
			$data['type'] = 'qualification';
			$data['qualification_id'] = $qualification_id;
			$data['subject_related'] = $subject_related;
			$data['subject'] = $subject;
			$data['accredited'] = $accredited;
			$data['in_class'] = $in_class;
			$data['date'] = date('Y-m-d',strtotime($date));
			$error = "";
    		$error_seperator = "<br>";
			$table = 'user_qualification';
    		$wher_column_name = 'user_qualification_id';
    		if($id){
    			$data['updated_at'] = date('Y-m-d H:i:s');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    		}
    		exit;
    	}
    	$this->template->build('add_qualifications', $content_data);
    }
	
	public function add_certificate($user_id,$id = null){
    	$content_data['certificate_list'] = get_certificate_list();
    			
    	$content_data['id'] = $id;
		$content_data['user_id'] = $user_id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_user_model->get_quali_certi($id,'certificate');
    	}
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		
			$certificate_id = $this->input->post('certificate_id');
    		$date = $this->input->post('date');
    		
			$data = array();
			$data['user_id'] = $user_id;
			$data['type'] = 'certificate';
			$data['qualification_id'] = $certificate_id;
			$data['date'] = date('Y-m-d',strtotime($date));
				
			$error = "";
    		$error_seperator = "<br>";
			$table = 'user_qualification';
    		$wher_column_name = 'user_qualification_id';
    		if($id){
    			$data['updated_at'] = date('Y-m-d H:i:s');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    		}
    		exit;
    	}
    	$this->template->build('add_certificate', $content_data);
    }
	
	public function add_experience($user_id,$id = null){
    	    			
    	$content_data['id'] = $id;
		$content_data['user_id'] = $user_id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_user_model->get_experience($id);
    	}
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		
			$company = $this->input->post('company');
    		$position = $this->input->post('position');
			$start_date = $this->input->post('start_date');
    		$end_date = $this->input->post('end_date');
			$departure_reason = $this->input->post('departure_reason');

    		
			$data = array();
			$data['user_id'] = $user_id;
			$data['company'] = $company;
			$data['position'] = $position;
			$data['start_date'] = date('Y-m-d',strtotime($start_date));
			$data['end_date'] = date('Y-m-d',strtotime($end_date));
			$data['departure_reason'] = $departure_reason;
				
			$error = "";
    		$error_seperator = "<br>";
			$table = 'user_workhistory';
    		$wher_column_name = 'user_workhistory_id';
    		if($id){
    			$data['updated_at'] = date('Y-m-d H:i:s');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    		}
    		exit;
    	}
    	$this->template->build('add_experience', $content_data);
    }
	
	public function add_emergency_contact($user_id,$id = null){
    	$content_data['countries_list'] = get_countries();
    			
    	$content_data['id'] = $id;
		$content_data['user_id'] = $user_id;
    	$rowdata = array();
    	if($id){
    		$rowdata = $this->list_user_model->get_emergency_contact_data($id);
    	}
    	$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		
			$name = $this->input->post('name');
    		$relation = $this->input->post('relation');
    		$contact_number = $this->input->post('contact_number');
    		$alternate_contact = $this->input->post('alternate_contact');
			$country = $this->input->post('country');
    		
			$data = array();
			$data['user_id'] = $user_id;
			$data['name'] = $name;
			$data['relation'] = $relation;
			$data['contact_number'] = $contact_number;
			$data['alternate_contact'] = $alternate_contact;
			$data['country'] = $country;
				
			$error = "";
    		$error_seperator = "<br>";
			$table = 'emergency_contacts';
    		$wher_column_name = 'emergency_contact_id';
    		if($id){
    			$data['updated_at'] = date('Y-m-d H:i:s');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    		}
    		exit;
    	}
    	$this->template->build('add_emergency_contact', $content_data);
    }
	
	public function update_user_permossion($user_id){
    	$content_data['user_id'] = $user_id;
    	if($this->input->post()){
			$pd_workshops = $this->input->post('pd_workshops');
			$ecl_access = $this->input->post('ecl_access');
			$pd_observation_list = $this->input->post('pd_observation_list');
			$view_requests = $this->input->post('view_requests');
			$timetable = $this->input->post('timetable');
			$make_concern_note = $this->input->post('make_concern_note');
			$view_all_concerns = $this->input->post('view_all_concerns');
			$validate_concern = $this->input->post('validate_concern');
    		
			$data = array();
			$data['user_id'] = $user_id;
			$data['pd_workshops'] = $pd_workshops;
			$data['ecl_access'] = $ecl_access;
			$data['pd_observation_list'] = $pd_observation_list;
			$data['view_requests'] = $view_requests;
			$data['timetable'] = $timetable;
			$data['make_concern_note'] = $make_concern_note;
			$data['view_all_concerns'] = $view_all_concerns;
			$data['validate_concern'] = $validate_concern;
			
			$table = 'user_permissions';
    		$wher_column_name = 'id';
			
			$id = 0;
			
			$rowdata = $this->list_user_model->get_user_permossion($user_id);
			
			if(isset($rowdata->id)){
				$id = $rowdata->id;
			}
			
    		if($id){
    			$data['updated_at'] = date('Y-m-d H:i:s');
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    		}
    	}
    	redirect('list_user/edit_profile/'.$user_id.'/9');
    }
	
	public function upload_profile_document($user_id){
    	
		$arrCertificateType = getCertificateType();
		$curr_dir = str_replace("\\","/",getcwd()).'/';
		//upload and update the file
		$config['upload_path'] = $curr_dir.'uploads/'.$user_id.'/';
		$config['allowed_types'] = 'jpg|jpeg|pdf|png';
		$config['overwrite'] = true;
		$config['remove_spaces'] = true;
		$config['max_size']	= '2048';// in KB
	
		//load upload library
		$this->load->library('upload', $config);
		
		$dir_exist = true; // flag for checking the directory exist or not
		if(!is_dir($curr_dir.'uploads/'.$user_id))
		{
			mkdir($curr_dir.'uploads/'.$user_id, 0777, true);
			$dir_exist = false; // dir not exist
		}
		$data = array();
		$errors = "";
		
		if($dir_exist)
		{
			foreach($_FILES as $field => $files)
			{
				if(count($files['name']) >0)
				{
					$file_names = array();
					foreach($files['name'] as $file_name) {
						$file_names[] = $field.'_'.$file_name;
					}
					$config['file_name'] = $file_names;
					$this->upload->initialize($config);
					if($this->upload->do_multi_upload($field))
					{
						$data[$field] = $this->upload->get_multi_upload_data();
					}else{
						$errors .= str_replace("_"," ",$field).': '.$this->upload->display_errors()."<br>";
					}
					
				}
			}
		}
		
		if(is_array($data) && count($data) > 0)
		{
			$table = 'profile_certificate';
					
			foreach($data AS $certificate_type=>$arrFiles)
			{
				if(isset($arrCertificateType[$certificate_type]) && count($arrFiles) > 0)
				{
					$this->list_user_model->delete_user_document($user_id,$arrCertificateType[$certificate_type]);
					foreach($arrFiles as $file_data) {
						$certificate_file = 'uploads/'.$user_id.'/'.$file_data["file_name"];
						
						$data_document['user_id'] = $user_id;
						$data_document['certificate_type'] = $arrCertificateType[$certificate_type];
						$data_document['certificate_file'] = $certificate_file;
						
						grid_add_data($data_document,$table);
					}
				}
			}
		}
		
		//Update user profile id in profile_certificate table
		$this->list_user_model->update_user_profileid($user_id, 'profile_certificate');
		
		//$this->session->set_flashdata('message', $errors);
		redirect('list_user/edit_profile/'.$user_id.'/8');
	}	
	
	public function delete_profile_document($user_id,$certificate_type,$certificate_id = 0){
		$arrCertificateType = getCertificateType(true);
		if($this->list_user_model->delete_user_document($user_id,$certificate_type,$certificate_id))
			$this->session->set_flashdata('message', $arrCertificateType[$certificate_type]." deleted successfully");
			
		redirect('list_user/edit_profile/'.$user_id);	
	}
	
	public function upload_profile_pic(){
    	
    	if($_FILES['uploadpic']['error'] == 0){
    		$curr_dir = str_replace("\\","/",getcwd()).'/';
    		//upload and update the file
    		$config['upload_path'] = $curr_dir.'images/profile_picture/original/';
    		$config['allowed_types'] = 'gif|jpg|png';
    		$config['overwrite'] = false;
    		$config['remove_spaces'] = true;
    		//$config['max_size']	= '100';// in KB
    	
    		//load upload library
    		$this->load->library('upload', $config);
    		$this->load->library('image_lib');
    		if ( ! $this->upload->do_upload('uploadpic')){
    			$this->session->set_flashdata('message', $this->upload->display_errors('<p class="error">', '</p>'));
    		}
    		else
    		{
    			$data1 = array('upload_data' => $this->upload->data());
    			$image= $data1['upload_data']['file_name'];
    			
    			$configBig = array();
				
				$configBig['image_library'] = 'gd2';
				$configBig['source_image']    = $curr_dir.'images/profile_picture/original/'.$image;;
				$configBig['new_image'] = $curr_dir.'images/profile_picture/thumb7575/'.$image;;
				$configBig['maintain_ratio'] = TRUE;
				$configBig['width']     = 75;
				$configBig['height']    = 75;

				$this->image_lib->initialize($configBig); 

				if ( ! $this->image_lib->resize())
				{
					echo $this->image_lib->display_errors();
				}
				$this->image_lib->clear();
				unset($configBig);
				
				$configBig = array();
				
				$configBig['image_library'] = 'gd2';
				$configBig['source_image']    = $curr_dir.'images/profile_picture/original/'.$image;;
				$configBig['new_image'] = $curr_dir.'images/profile_picture/thumb150150/'.$image;;
				$configBig['maintain_ratio'] = TRUE;
				$configBig['width']     = 150;
				$configBig['height']    = 150;

				$this->image_lib->initialize($configBig); 

				if ( ! $this->image_lib->resize())
				{
					echo $this->image_lib->display_errors();
				}
				$this->image_lib->clear();
				unset($configBig);
				
				//$user_id = $this->session->userdata('user_id');
				$user_id = $this->input->post('user_id');
				$data = array('profile_picture' => $image);

				if($this->list_user_model->upload_profile_pic($user_id,$data) == 1) {
					echo "1";
				}
    			
    		}
    	}
    	 
    }
	
	
	public function get_observations_json($order_by = "username", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('elsd_id','first_name','campus','username','password','email');
    	
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
		$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];

    	$data = $this->list_user_model->get_observations($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_user_model->get_observations($per_page, $offset, $order_by, $sort_order, $grid_data['search_data'],0,true);
    
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
				if($result_row["score1"] <> '')  
					$row[] = 'OBS:'.$result_row["score1"].'<br> Ed Tech:'.$result_row["et_score1"]; 
				else
					$row[] = '';
				$row[] = $result_row["first_name"];    			
    			$row[] = $result_row["elsd_id"]; 
    			$row[] = $result_row["comment"];
    			$row[] = $result_row["obs_date"];
				$row[] = $result_row["section_title"];
    			$row[] = $result_row["user_id"];
    			$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
	
	public function add_obs_comment($user_id,$id = null){
  			
    	$content_data['id'] = $id;
		$content_data['user_id'] = $user_id;
    	$rowdata = array();
    	
		$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$id = $this->list_user_model->check_obs_exists($user_id);
			
			$comment = $this->input->post('comment');
    		$action_by = $this->session->userdata('user_id');
			
			$data = array();
			$data['user_id'] = $user_id;
			$data['comment'] = $comment;
			$data['updated_at'] = date('Y-m-d H:i:s');
			
			$table = 'obs_detail';
    		$wher_column_name = 'id';
    		if($id){
    			$data['updated_by'] = $action_by;
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
				$data['created_by'] = $action_by;
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    		}
    		exit;
    	}
    	$this->template->build('add_obs_comment', $content_data);
    }
	
	public function add_obs_score($user_id,$id = null){
  			
    	$content_data['id'] = $id;
		$content_data['user_id'] = $user_id;
    	$rowdata = array();
    	
		$content_data['rowdata'] = $rowdata;
    	if($this->input->post()){
    		$id = $this->list_user_model->check_obs_exists($user_id);
			
			$score = $this->input->post('score');
			$et_score = $this->input->post('et_score');
			
    		$action_by = $this->session->userdata('user_id');
			
			$data = array();
			$data['user_id'] = $user_id;
			$data['score1'] = $score;
			$data['et_score1'] = $et_score;
			$data['updated_at'] = date('Y-m-d H:i:s');
							
			$table = 'obs_detail';
    		$wher_column_name = 'id';
    		if($id){
    			$data['updated_by'] = $action_by;
				
    			grid_data_updates($data,$table,$wher_column_name,$id);
    			
    		}else{
				$data['created_by'] = $action_by;
    			$data['created_at'] = date('Y-m-d H:i:s');
    			$lastinsertid = grid_add_data($data,$table);
    		}
    		exit;
    	}
    	$this->template->build('add_obs_score', $content_data);
    }
	
	public function update_password() {
		$user_id = $this->input->post('user_id');
		
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('current_password', 'current password', 'trim|required|max_length[64]|is_member_password');
        $this->form_validation->set_rules('new_password', 'new password', 'trim|required|max_length[64]|min_length[4]|matches[new_password_again]');
        $this->form_validation->set_rules('new_password_again', 'new password again', 'trim|required|max_length[64]|min_length[4]');

        if (!$this->form_validation->run())
        {
            if (form_error('current_password')) {
                $this->session->set_flashdata('pwd_message', form_error('current_password'));
            }elseif (form_error('new_password')) {
                $this->session->set_flashdata('pwd_message', form_error('new_password'));
            }elseif (form_error('new_password_again')) {
                $this->session->set_flashdata('pwd_message', form_error('new_password_again'));
            }
            
            redirect('list_user/edit_profile/'.$user_id.'/10');
            exit();
        }

        if ($this->list_user_model->set_password($this->input->post('new_password'),$user_id)) {
            $this->session->set_flashdata('pwd_message', $this->lang->line('profile_success'));
        }
         redirect('list_user/edit_profile/'.$user_id.'/10');
    }
	
	public function reset_user_privilege() {
		$user_id = $this->input->post('user_id');
		$user_roll_id = $this->input->post('user_roll_id');
		
		$roll_privilege = $this->list_user_model->get_existing_privilege($user_roll_id);
		$this->list_user_model->create_single_user_privilege($user_id, $roll_privilege);
		
		redirect('list_user/edit_profile/'.$user_id.'/');
	}
	
}

/* End of file list_user.php */