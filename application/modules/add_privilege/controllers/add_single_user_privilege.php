<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add_single_user_privilege extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');        
        $this->load->library('form_validation');
        $this->load->helper('general_function');
        $this->load->model('privilege_model');
        
    }

    public function index($user_id = 0) {
		$this->db->select('first_name');
		$this->db->from('users');
		$this->db->where('user_id =',$user_id);
		$query = $this->db->get();
		$first_name = $query->result_array();
		$content_data['user_id'] = $user_id;
		$content_data['first_name'] = $first_name[0]['first_name'];
    	$content_data['previlage_action'] = get_previlege_action();
        $content_data['privilege_data'] = $this->privilege_model->get_menu_actions();
        
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Add Privilege');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('add_single_user_privilege', $content_data);
    }

    public function add() {    	
        
        $this->load->model('privilege_model');
        if($this->privilege_model->create_single_user_privilege($this->input->post('user_id'), $this->input->post('privilege_action'))) {
        	set_activity_log('0','add','privilege','add privilege');
            $this->session->set_flashdata('message', $this->lang->line('privilege_created'));
        }else{
            $this->session->set_flashdata('message', $this->lang->line('unable_to_register'));
        }
		redirect('list_user/edit_profile/'.$this->input->post('user_id').'/');
		exit;
        //redirect('/add_privilege/add_single_user_privilege/index/'.$this->input->post('user_id'));
    }
	
    public function get_user_existing_privilege(){
    	$this->load->model('privilege_model');
    	$user_id = $_POST['user_id'];
    	$data = $this->privilege_model->get_user_existing_privilege($user_id);
    	echo $data;
    }
}

/* End of file add_single_user_privilege.php */