<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add_user_privilege extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');        
        $this->load->library('form_validation');
        $this->load->helper('general_function');
        $this->load->model('privilege_model');
        $this->load->model('list_student/list_teacher_student_model');
    }

        /**
     * add: add school year from post data.
     */

    public function add() { 
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('user_roll_id', 'school name', 'trim|required|callback_combobox_check');
        

        if (!$this->form_validation->run()) {
        	if (form_error('user_roll_id')) {
                $this->session->set_flashdata('message', form_error('user_roll_id'));
            }
            
            $data['post'] = $_POST;
            $this->session->set_flashdata($data['post']);
			
            redirect('/add_privilege');
            exit();
        }
        // load school model model
        $this->load->model('privilege_model');
        if($this->privilege_model->create_privilege($this->input->post('user_roll_id'), $this->input->post('action'))) {
        	set_activity_log('0','add','privilege','add privilege');
            $this->session->set_flashdata('message', $this->lang->line('privilege_created'));
        }else{
            $this->session->set_flashdata('message', $this->lang->line('unable_to_register'));
        }
        redirect('/add_privilege');
    }
	
    public function get_existing_privilege(){
    	$this->load->model('privilege_model');
    	$role_id = $_POST['role_id'];
    	$data = $this->privilege_model->get_existing_privilege($role_id);
    	echo $data;
    }
	
	// USER PRIVILEGE
	public function index($order_by = "first_name", $sort_order = "asc", $search = "all", $offset = 0) {
		if($this->session->userdata('user_privilege_per_page') == '') 
			$this->session->set_userdata('user_privilege_per_page', 10);
		if (isset($_POST['per_page']) && !empty($_POST['per_page'])) {
			$this->session->set_userdata('user_privilege_per_page', $_POST['per_page']);
		}
		$this->load->library('pagination');
		$per_page = $this->session->userdata('user_privilege_per_page');
		$search_data = array();
		if(isset($_POST['search_user']) && $_POST['search_user'] <> '') {
			$search_data['first_name'] = trim($_POST['search_user']);
		}
		$base_url = site_url('add_user_privilege/index/'. $order_by .'/'. $sort_order .'/all');
		
		$get_users = $this->list_teacher_student_model->get_other_user($per_page, $offset,$order_by, $sort_order, $search_data);
		$count_users = $this->list_teacher_student_model->count_all_other_mem($search_data);
		$users = array();
		foreach($get_users->result_array() AS $data)
		{
			$row = array();
			$current_privilege = $this->get_user_menu_action_privilege($data['user_id']);
			
			$row['user_id'] = $data['user_id'];
			$row['first_name'] = $data['first_name'];
			$row['current_privilege'] = $current_privilege;
			$users[] = $row;
		}		
    	
		$content_data['users'] = $users;
		$content_data['offset'] = $offset;
        $content_data['order_by'] = $order_by;
        $content_data['sort_order'] = $sort_order;
		
		// set pagination config data
        $config['uri_segment'] = '6';
        $config['base_url'] = $base_url;
        $config['per_page'] = $per_page;
		$config['total_rows'] = $count_users;
        $config['prev_tag_open'] = ''; // removes &nbsp; at beginning of pagination output
        $this->pagination->initialize($config);
		
		$content_data['previlage_action'] = get_previlege_action();
        $content_data['privilege_data'] = $this->privilege_model->get_menu_actions();
        
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        $this->template->title('User Privilege');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('user_privilege', $content_data);
    }
	
	public function add_custom_privilege() 
	{
		if (isset($_POST['action']) && count($_POST['action']) > 0) {
			$user_ids = explode(',',$_POST['user_ids']);
			$this->db->where_in('user_id',$user_ids);
    		$this->db->delete('user_custom_privilege');
			foreach($_POST['action'] as $key=>$value)
			{
				$this->load->model('privilege_model');
				if($this->privilege_model->create_user_privilege($key, $value)) {
					set_activity_log('0','add','privilege','add privilege');
					$this->session->set_flashdata('message', $this->lang->line('privilege_created'));
				}
			}
        }
		redirect('/add_privilege/add_user_privilege');
	}
	
	public function get_user_menu_action_privilege($user_id){
    	$this->load->model('privilege_model');
    	return $data = $this->privilege_model->get_user_menu_action_privilege($user_id);
    }
}

/* End of file add_user_privilege.php */