<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add_bug extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');        
        $this->load->library('form_validation');
        $this->load->helper('general_function');
		
		// load bug model
        $this->load->model('list_bug/bug_model');
    }

    public function index() {
    	$content_data['teacher_list'] = get_teacher_list();       
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Add Ticket');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('add_bug', $content_data);
    }

    /**
     *
     * add: add bug from post data.
     *
     *
     */

    public function add() {    	
    	
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('bug_title', 'Ticket Title', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        
        if (!$this->form_validation->run()) {
        	if (form_error('bug_title')) {
                $this->session->set_flashdata('message', form_error('bug_title'));
            }elseif (form_error('description')) {
                $this->session->set_flashdata('message', form_error('description'));
            }

            $data['post'] = $_POST;
            $this->session->set_flashdata($data['post']);
			
            redirect('/add_bug');
            exit();
        }
       
        
        if($system_bug_id = $this->bug_model->add_bugs($this->input->post('bug_title'), $this->input->post('description'), $_FILES)) 
		{
        	set_activity_log($system_bug_id,'add','bug','add bug');
            
            $this->session->set_flashdata('message', $this->lang->line('bug_created'));
        }else{
            $this->session->set_flashdata('message', $this->lang->line('unable_to_register'));
        }
        redirect('/add_bug');
    }

}

/* End of file add_bug.php */