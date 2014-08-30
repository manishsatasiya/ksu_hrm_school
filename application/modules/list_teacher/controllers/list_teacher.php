<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_teacher extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->model('list_course/courses_model');
        $this->load->model('list_student/list_teacher_student_model');
		$this->load->helper('general_function');
		$this->load->model('get_pdf/get_pdf_model');
		$this->load->model('list_user/list_user_model');
		
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
            redirect('/list_teacher');
        }

        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_username' => '', 's_first_name' => '', 's_email' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('username', 'username', 'trim|max_length[16]');
            $this->form_validation->set_rules('first_name', 'full name', 'trim|max_length[40]');
            $this->form_validation->set_rules('email', 'email', 'trim|max_length[255]');

            if (empty($_POST['username']) && empty($_POST['first_name']) && empty($_POST['email'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_teacher/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('username')) {
                    $this->session->set_flashdata('message', form_error('username'));
                }elseif (form_error('email')) {
                    $this->session->set_flashdata('message', form_error('email'));
                }elseif (form_error('first_name')) {
                    $this->session->set_flashdata('message', form_error('first_name'));
                }
                redirect('/list_teacher/');
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
            //redirect("/list_teacher");
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
		
		$content_data['school_campus'] = $this->get_pdf_model->get_campus_pdf();
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('List Teacher');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_teacher', $content_data);
    }
    
    public function index_json($order_by = "username", $sort_order = "asc", $search = "all", $offset = 0) {
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
    	$aColumns = array('elsd_id','staff_name','campus_name','username','password','email');
    	
    	$grid_data = get_search_data($aColumns);
    	$sort_order = $grid_data['sort_order'];
		$order_by = $grid_data['order_by'];
    	/*
    	 * Paging
    	*/
    	$per_page =  $grid_data['per_page'];
    	$offset =  $grid_data['offset'];

    	$data = $this->list_teacher_student_model->get_teacher($per_page, $offset, $order_by, $sort_order, $grid_data['search_data']);
    	$count = $this->list_teacher_student_model->count_all_teacher_mem($grid_data['search_data']);
    
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
				if($result_row["log_cnt"] > 0) {
					$row[] = '<a href="list_teacher/view_report/'.$result_row["user_id"].'" data-target="#myModal" data-toggle="modal" style="color:red;">'.$result_row["elsd_id"].'</a>';  
				}else{ 			
    				$row[] = $result_row["elsd_id"];  
				}  			
				if($result_row["change_by"] > 0){    				
				$log_data = $this->list_teacher_student_model->get_user_log($result_row["user_id"],4);
    				$strTooltip = '<table class="table no-more-tables prop-log-table">';
					$strTooltip .= '<tr><th>Section</th><th>Update By</th><th>Date</th><th>Reason</th></tr>';
					foreach($log_data->result_array() as $data1) {
						$strTooltip .= "<tr><td>".addslashes($data1["section_title"])."</td>";
						$strTooltip .= "<td>".addslashes($data1["first_name"])."</td>";						
						$strTooltip .= "<td>".date('d-M-Y',strtotime($data1["change_date"]))."</td>";
						$strTooltip .= "<td>".$data1["reason"]."</td></tr>";
					}
    				$strTooltip .= '</table>';
					//$row[] =  "<a onmouseover='javascript:popup(\"".$strTooltip."\",\"250px\");'><font size=\"2\" color=\"red\">".$result_row["first_name"]."</font></a>";
					$row[] =  "<a onmouseover='' id=\"popover\" data-content='".$strTooltip."' data-toggle=\"popover\"><font size=\"2\" color=\"red\">".$result_row["staff_name"]."</font></a>";
				}
				else{    				
				$row[] = $result_row["staff_name"];    			
				}
    			$row[] = $result_row["campus_name"];
    			$row[] = $result_row["username"];
    			$row[] = '**********';
    			$row[] = $result_row["email"];
    			$row[] = $result_row["user_id"];
    			$output['aaData'][] = $row;
    		}
    	}
    
    	echo json_encode( $output );
    }
	
	
	public function view_report($id) 
	{
		$section_log_data = $this->list_teacher_student_model->get_user_log($id);
		$content_data['section_log_data'] = $section_log_data->result_array();
        $this->template->build('view_teacher_report', $content_data);
	}
    
    public function add($id = null){
    	$content_data['get_ca_lead_teacher_list'] = get_ca_lead_teacher_list();
    	$content_data['campus_list'] = get_campus_list();
		$content_data['line_manager_list'] = get_line_manager_list();
    	$content_data['id'] = $id;
    	$rowdata = $campus_privilages = array();
    	if($id){
    		$rowdata = $this->list_teacher_student_model->get_teacher_data($id);
			$campus_privilages = get_user_campus_privilages_data($id);
    	}
    	$content_data['rowdata'] = $rowdata;
    	$content_data['campus_privilages'] = $campus_privilages;
    	if($this->input->post()){
    		$nonce = md5(uniqid(mt_rand(), true));
			$user_id = $this->input->post('user_id');
    		$campus_id = $this->input->post('campus_id');
    		$first_name = $this->input->post('first_name');
    		$middle_name = $this->input->post('middle_name');
    		$last_name = $this->input->post('last_name');
    		$email = $this->input->post('email');
			$coordinator = $this->input->post('coordinator');
			$office_no = $this->input->post('office_no');
    		$username = $this->input->post('username');
    		$password = $this->input->post('password');
    			
    		$error = "";
    		$error_seperator = "<br>";
    		if($id){
    			 
    			$this->form_validation->set_rules('first_name', 'first name', 'trim|required|max_length[40]|min_length[2]');
		        $this->form_validation->set_rules('email', 'email', 'trim|required|max_length[255]|is_valid_email|is_existing_field[users.email^users.user_id !=^'.$user_id.']');
		        
		       	if (!$this->form_validation->run()) {
    				if (form_error('first_name')) {
    					$error .= form_error('first_name').$error_seperator;
    				}elseif (form_error('email')) {
    					$error .= form_error('email').$error_seperator;
    				}
    				echo $error;
    				exit();
    			}
    			
    			$data = $profile_data = array();
    			$data['campus_id'] = $campus_id;
    			$data['first_name'] = $first_name;
    			$data['middle_name'] = $middle_name;
    			$data['last_name'] = $last_name;
    			$data['email'] = $email;
				$data['coordinator'] = $coordinator;
    			$data['updated_date'] = date('Y-m-d H:i:s');
				$profile_data['office_no'] = $office_no;
    			
				$table = 'users';
    			$wher_column_name = 'user_id';
    			set_activity_data_log($id,'Update','Teacher > List teacher','List teacher',$table,$wher_column_name,'');

    			grid_data_updates($data,$table,$wher_column_name,$user_id);
    			$table = 'user_profile';
    			$wher_column_name = 'user_id';
    			grid_data_updates($profile_data,$table,$wher_column_name,$user_id);
				
				$campus_privilege = $this->input->post('campus_privilages');
				$this->list_user_model->create_user_campus_privilege($user_id, $campus_privilege);
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
    			 
    			$data = $profile_data = array();
    			$data['campus_id'] = $campus_id;
    			$data['first_name'] = $first_name;
    			$data['middle_name'] = $middle_name;
    			$data['last_name'] = $last_name;
    			$data['email'] = $email;
				$data['coordinator'] = $coordinator;
    			$data['username'] = $username;
    			$data['password'] = hash_password($password, $nonce);
    			$data['user_roll_id'] = '3';
    			$data['active'] = '1';
    			$data['nonce'] = $nonce;
    			$data['created_date'] = date('Y-m-d H:i:s');
				
    			$table = 'users';
    			$wher_column_name = 'elsd_id';
    			$lastinsertid = grid_add_data($data,$table);
    			set_activity_data_log($lastinsertid,'Add','Teacher > List teacher','List teacher',$table,$wher_column_name,$user_id='');
				
				$profile_data['user_id'] = $lastinsertid;
				$profile_data['office_no'] = $office_no;
				$table = 'user_profile';
    			grid_add_data($profile_data,$table);
				
				$campus_privilege = $this->input->post('campus_privilages');
				$this->list_user_model->create_user_campus_privilege($lastinsertid, $campus_privilege);
    		}
    		exit;
    	}
    	$this->template->build('add_teacher_datatable', $content_data);
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
    
    public function update_student(){
    	$error = "";
    	$error_seperator = "<br>";
    	
    	$value = $this->input->post('value');
    	$columnName = $this->input->post('columnName');
    	$arrid = explode(">",str_replace("</a","",$this->input->post('id')));
		$id = 0;
		if(isset($arrid[0]) && isset($arrid[1]))
			$id = $arrid[1];
		else
			$id = $arrid[0];	
			
    	$tablename = 'users';
    	$whrid_column = 'elsd_id';
    	
    	if($columnName != '') $_POST[$columnName] = $value;
    	$this->form_validation->set_error_delimiters('', '');
    	if ($columnName == 'username')
    		$this->form_validation->set_rules('username', 'username', 'trim|required|max_length[50]|min_length[6]|is_existing_field[users.username^users.elsd_id !=^'.$id.']');
    	if ($columnName == 'email')
    		$this->form_validation->set_rules('email', 'email', 'trim|required|max_length[255]|is_valid_email|is_existing_field[users.email^users.elsd_id !=^'.$id.']');
    	if ($columnName == 'first_name')
    		$this->form_validation->set_rules('first_name', 'first name', 'trim|required|max_length[40]|min_length[2]');
    	if ($columnName == 'password'){
    		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
    	}
		if ($columnName == 'campus'){
    		$this->form_validation->set_rules('campus', 'Campus', 'trim|required|chk_combox_value');
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
    		}elseif (form_error('campus')) {
    			$error .= form_error('campus').$error_seperator;
    		}
    	
    		if($error <> ""){
    		echo $error;
    		exit();
    		}
    	}
		
		if($columnName == "campus")
			$columnName = "campus_id";
    	
		set_activity_data_log($id,'Update','Teacher > List teacher','List teacher',$tablename,$whrid_column,$user_id='');
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
            redirect('/list_teacher');
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
            redirect('/list_teacher/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }

        $this->list_teacher_student_model->update_member($this->input->post('user_id'), $this->input->post('username'), $this->input->post('email'), $this->input->post('first_name'), $this->input->post('middle_name'),$this->input->post('name_suffix'),$this->input->post('address1'),$this->input->post('address2'),$this->input->post('city'),$this->input->post('state'),$this->input->post('zip'),$this->input->post('birth_date'),$this->input->post('birth_place'),$this->input->post('gender'),$this->input->post('language_known'),$this->input->post('work_phone'),$this->input->post('home_phone'),$this->input->post('cell_phone'), $this->input->post('username_box'), $this->input->post('email_box'));
        set_activity_log($this->input->post('user_id'),'update','teacher','list teacher');
        $this->session->set_flashdata('message', sprintf($this->lang->line('member_updated'), $this->input->post('username'), $this->input->post('id')));
        redirect('/list_teacher/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
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
        redirect('/list_teacher/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
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
            redirect('/list_teacher/index');
            return;
        }elseif ($this->list_teacher_student_model->toggle_active($id, $active)) {
            $active ? $active = $this->lang->line('deactivated') : $active = $this->lang->line('activated');
            set_activity_log($id,$active,'teacher','list teacher');
            $this->session->set_flashdata('message', sprintf($this->lang->line('toggle_active'), $username) . $active);
        }
        redirect('/list_teacher/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
	
	public function delete($id = null){
    	if($id){
			$table = 'users';
			$wher_column_name = 'user_id';
			set_activity_data_log($id,'Delete','Teacher > List Teacher','List Teacher',$table,$wher_column_name,$user_id='');
			
    		$rowdata = $this->courses_model->delete_data($table,$wher_column_name,$id);
    	}
		redirect('/list_teacher/');
        exit();
	}
	
	public function export_to_excel()
    {
    	ini_set('memory_limit','1024M');
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	*/
		$where = array();
		$where1 = array();
		$search_data = array();
		
		$campus_id = 0;
    	if(isset($_POST['campus']))
    		$campus_id =  $_POST['campus'];
		
		$order_by = "username";
    	/*
    	 * Paging
    	*/
    	$per_page =  50000;
    	$offset =  0;
		
		$data = $this->list_teacher_student_model->get_teacher($per_page, $offset, $order_by, "asc", $search_data, $campus_id);
    	$arrTeacher = array();
    	if($data){
    		foreach($data->result_array() AS $result_row){
    			$arrTeacher[] = array("elsd_id" => $result_row["elsd_id"],
											"first_name" => $result_row["staff_name"],
											"campus" => $result_row["campus_name"],
											"username" => $result_row["username"],
											"email" => $result_row["email"]
										 );	
    		}
    	}
		$content_data["arrTeacher"] = $arrTeacher;
    	$this->template->build('teacher_excel', $content_data);
    }
	
}

/* End of file list_teacher.php */
