<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_bug extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('bug_model');
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

    public function index($order_by = "system_bugs.system_bug_id", $sort_order = "asc", $search = "all", $offset = 0) {
        if (!is_numeric($offset)) {
            redirect('/list_bug');
        }

        $this->load->library('pagination');
        if ($search == "post") {
            $this->session->unset_userdata(array('s_bug_title' => ''));
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('bug_title', 'Ticket title', 'trim|max_length[16]');
            

            if (empty($_POST['bug_title'])) {
                $this->session->set_flashdata('message', $this->lang->line('enter_search_data'));
                redirect('/list_bug/');
                exit();
            }elseif (!$this->form_validation->run()) {
                if (form_error('bug_title')) {
                    $this->session->set_flashdata('message', form_error('bug_title'));
                }
                redirect('/list_bug/');
                exit();
            }

            $search_session = array(
                's_bug_title'  => $this->input->post('bug_title')
            );
            $this->session->set_userdata($search_session);

            $base_url = site_url('list_bug/index/'. $order_by .'/'. $sort_order .'/session');
            $search_data = array('bug_title' => $this->input->post('bug_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->bug_model->count_all_search_bug($search_data);
            $content_data['search'] = "session";
            if ($config['total_rows'] == 0) {
                $this->session->set_flashdata('message', $this->lang->line('search_data_none_returned'));
            }


        }elseif($search == "session") {
            $base_url = site_url('list_bug/index/'. $order_by .'/'. $sort_order .'/session');
             $search_data = array('bug_title' => $this->input->post('bug_title'));
            $content_data['total_rows'] = $config['total_rows'] = $this->bug_model->count_all_search_bug($search_data);
            $content_data['search'] = "session";

        }else{
            $unset_search_session = array('s_bug_title' => '');
            $this->session->unset_userdata($unset_search_session);
            $content_data['total_rows'] = $config['total_rows'] = $this->bug_model->count_all_bug();
            $base_url = site_url('list_bug/index/'. $order_by .'/'. $sort_order .'/all');
            $search_data = array();
            $content_data['search'] = "all";
        }

        // set content data
        $per_page = Settings_model::$db_config['members_per_page'];
        $data = $this->bug_model->get_bug($per_page, $offset, $order_by, $sort_order, $search_data);
        if (empty($data)) {
            //redirect("/list_bug");
        }else{
            $content_data['bug'] = $data;
        }
        if($data){
        	foreach ($data->result() as $datas):
        		$query = $this->bug_model->get_bug($datas->system_bug_id);
        		$datas->totcomment = count($query->result()) + 1;
        	endforeach;
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
        
        $this->template->title('List Ticket');
        $this->template->set_partial('header', 'header');
$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('list_bug', $content_data);
    }

    public function action_bug($id, $offset, $order_by, $sort_order, $search) {
        if (array_key_exists('update', $_POST)) {
            $this->_update_bug($id, $offset, $order_by, $sort_order, $search);
        }else{
            $this->_delete_bug($id, $offset, $order_by, $sort_order, $search);
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

    private function _update_bug($id, $offset, $order_by, $sort_order, $search) {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('email', 'email', 'trim|required|max_length[255]|is_valid_email|is_existing_unique_field[users.email]');        
        $this->form_validation->set_rules('school_name', 'School name', 'trim|required');
        $this->form_validation->set_rules('principal', 'principal', 'trim|required');
		

        $username = $this->bug_model->get_bug_title_by_id($id);
        
        if (!$this->form_validation->run()) {
            if (form_error('school_name')) {
                $this->session->set_flashdata('message', form_error('school_name'));
            }elseif (form_error('email')) {
                $this->session->set_flashdata('message', form_error('email'));
            }elseif (form_error('principal')) {
                $this->session->set_flashdata('message', form_error('principal'));
            }
            redirect('/list_bug/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
            exit();
        }

        $this->bug_model->update_school($this->input->post('school_id'),$this->input->post('school_name'), $this->input->post('address'), $this->input->post('city'), $this->input->post('state'), $this->input->post('zip'), $this->input->post('area_code'),$this->input->post('phone'),$this->input->post('principal'),$this->input->post('www_address'),$this->input->post('email'));
        $this->session->set_flashdata('message', sprintf($this->lang->line('school_updated'), $this->input->post('school_name'), $this->input->post('school_id')));
        redirect('/list_bug/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

     /**
     *
     * _delete_member: delete member from school
     *
     * @param int $id the id of the member to be deleted
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    private function _delete_bug($id, $offset, $order_by, $sort_order, $search) {
        $username = $this->bug_model->get_bug_title_by_id($id);
        if ($username == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noremove'));
        }elseif ($this->bug_model->delete_member($id)) {
            $this->session->set_flashdata('message', sprintf($this->lang->line('school_deleted'), $username, $id));
        }
        redirect('/list_bug/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

    /**
     *
     * demote_member: demote member from school
     *
     * @param int $id the id of the member to be deleted
     * @param string $username the username of the member involved
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    public function demote_member($id, $username, $offset, $order_by, $sort_order, $search) {
        if ($this->bug_model->get_bug_title_by_id($id) == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_nodemote'));
            redirect('/list_bug/index');
            return;
        }elseif ($this->bug_model->demote_member($id)) {
            $this->session->set_flashdata('message', sprintf($this->lang->line('member_demoted'), $username, $id));
        }
       redirect('/list_bug/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

    /**
     *
     * promote_member: promote member from school
     *
     * @param int $id the id of the member to be deleted
     * @param string $username the username of the member involved
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     *
     */

    public function promote_member($id, $username, $offset, $order_by, $sort_order, $search) {
        if ($this->bug_model->get_bug_title_by_id($id) == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_nodemote'));
            redirect('/list_bug/index');
            return;
        }elseif ($this->bug_model->promote_member($id)) {
            $this->session->set_flashdata('message', sprintf($this->lang->line('member_promoted'), $username, $id));
        }
        redirect('/list_bug/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }

    /**
     *
     * toggle_ban: (un)ban member from school
     *
     * @param int $id the id of the member to be deleted
     * @param string $username the username of the member involved
     * @param int $offset the offset to be used for selecting data
     * @param int $order_by order by this data column
     * @param string $sort_order asc or desc
     * @param string $search search type, used in index to determine what to display
     * @param bool $banned ban or unban?
     *
     */

    public function toggle_ban($id, $username, $offset, $order_by, $sort_order, $search, $banned) {
        if ($this->list_bug->get_bug_title_by_id($id) == ADMINISTRATOR) {
            $this->session->set_flashdata('message', $this->lang->line('admin_noban'));
            redirect('/list_bug/index');
            return;
        }elseif ($this->list_bug->toggle_ban($id, $banned)) {
            $banned ? $banned = $this->lang->line('unbanned') : $banned = $this->lang->line('banned');
            $this->session->set_flashdata('message', sprintf($this->lang->line('toggle_ban'), $username) . $banned);
        }
        redirect('/list_bug/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
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

    public function toggle_active($id, $bug_title, $offset, $order_by, $sort_order, $search, $active) {
    	
        if ($this->bug_model->toggle_active($id, $active)) {
            if($active == 'open'){
            	$active1 = 'close';
            } 
            if($active == 'close'){
            	$active1 = 'open';
            }
            
            $this->session->set_flashdata('message', sprintf($this->lang->line('bug_active'), $bug_title) . $active1);
        }
       
        redirect('/list_bug/index/'. $order_by .'/'. $sort_order .'/'. $search .'/'. $offset);
    }
    
    public function downlaod_attachment($filename){
    	
    	$curr_dir = str_replace("\\","/",getcwd()).'/';
    	$directory_path = $curr_dir.'images/bug_images/';
    	download_file($directory_path,$filename);
    }
    
    public function show_comment($id){
    	$content_data = array();
    	//echo $id;
    	
    	if($id){
    		$content_data['systembug_data'] = $this->list_bug->get_bug_data($id);
    		$content_data['bugimage_data'] = $this->list_bug->get_bug_image($id);
    		$bugcomment_data = $this->list_bug->get_bugcomment_data($id);
			
    		$content_data['bugcomment_data'] = $bugcomment_data;
    		$content_data['count_comment'] = count($bugcomment_data->result());
    		//time_ago()
    		
    		$content_data['system_bug_id'] = $id;
    	}
    	
    	// set layout data
    	$this->template->set_theme(Settings_model::$db_config['default_theme']);
    	$this->template->set_layout('');
    	$this->template->build('show_comment', $content_data);
    }
    
    public function ajaxpostcomment(){
    	$this->template->set_layout('');
    	
    	$saved = $this->bug_model->save_comment($this->input->post('id'),$this->input->post('comment'));
    	if($saved){
		
    		$bugcomment_data = $this->list_bug->get_postbugcomment_data($saved);
			
    		$countallresult = $this->list_bug->count_bugs_comment($this->input->post('id'));
    		
    		$html = '';
    		foreach ($bugcomment_data->result() as $bugcomment_datas):
    		$html .= '<div class="post-body">';
    			$html .= '<div>';
    				$html .= '<span class="name">'.$bugcomment_datas->first_name.'</span>';
    				$html .= '<span class="time-ago">('.time_ago($bugcomment_datas->created_date).')</span>';
    			$html .= '</div>';    
    			$html .= '<div class="post-message">'; 
    				$html .= '<p>'.$bugcomment_datas->comment.'</p>';                            
    			$html .= '</div>';        
    		$html .= '</div>';
    		endforeach;
    		$dataArr[] = $html;
    		$dataArr[] = $countallresult;
    		echo json_encode($dataArr);
    	}else{
    		echo "0";
    	}
    	exit;
    }
}

/* End of file list_members.php */