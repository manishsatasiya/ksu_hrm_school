<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Documents extends Private_Controller {
    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('general_function');
		$this->load->model('documents_model');
		//$this->load->model('list_course/courses_model');
    }
    
	public function index() {
    	$content_data = array();
		
		$human_resources = $this->documents_model->get_documents('human_resources');
		$assessment = $this->documents_model->get_documents('assessment');
		$professional_development = $this->documents_model->get_documents('professional_development');
		$curriculum = $this->documents_model->get_documents('curriculum');
		$curriculum_quarter_2 = $this->documents_model->get_documents('curriculum_quarter_2');
		$curriculum_quarter_4 = $this->documents_model->get_documents('curriculum_quarter_4');

		$all_documents = array(
							'human_resources'=>$human_resources,
							'assessment'=>$assessment,
							'professional_development'=>$professional_development,
							'curriculum'=>$curriculum,
							'curriculum_quarter_2'=>$curriculum_quarter_2,
							'curriculum_quarter_4'=>$curriculum_quarter_4,
						 );
		
		$content_data['all_documents'] = $all_documents;
//		echo '<pre>';
	//	print_r($all_documents);
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('All Staff Documents');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('documents', $content_data);
    }
	
	public function add_document() {
    	$content_data = array();
		
		$document_types = array('human_resources'=>'Human Resources','assessment'=>'Assessment','professional_development'=>'Professional Development','curriculum'=>'Curriculum','curriculum_quarter_2'=>'Curriculum Quarter 2','curriculum_quarter_4'=>'Curriculum Quarter 4');
		
		$errors = "";
		$curr_dir = str_replace("\\","/",getcwd()).'/';
		if($this->input->post()){
			$roll_id = $this->input->post('roll_id');
			$document_type = $this->input->post('document_type');
			$name = $this->input->post('name');
			//upload and update the file
			$config['upload_path'] = $curr_dir.'downloads/docs/'.$roll_id.'/';
			$config['allowed_types'] = 'jpg|jpeg|pdf|png|xlsx|doc|zip_docx|csv';
			$config['overwrite'] = true;
			$config['remove_spaces'] = true;
			$config['max_size']	= '2048';// in KB
			
			//load upload library
			$this->load->library('upload', $config);
			
			// flag for checking the directory exist or not
			if(!is_dir($curr_dir.'downloads/docs/'.$roll_id))
			{
				mkdir($curr_dir.'downloads/docs/'.$roll_id, 0777, true);
			}
			$data = array();
			
			
			foreach($_FILES as $field => $file)
			{
				// No problems with the file
				if($file['error'] == 0)
				{
					$config['file_name'] = $document_type.'_'.$file["name"];
					$this->upload->initialize($config);
					// So lets upload
					if($this->upload->do_upload($field))
					{
						$data[$field] = $this->upload->data();
					}
					else
					{
						$errors .= ''.$this->upload->display_errors()."<br>";
					}
				}
			}
			
			
			if(isset($data) && is_array($data) && count($data) > 0)
			{
				$table = 'documents';		
				$doc_file = 'downloads/docs/'.$roll_id.'/'.$data['file']["file_name"];
				
				$data_document['roll_id'] = $roll_id;
				$data_document['document_type'] = $document_type;
				$data_document['name'] = $name;
				$data_document['file'] = $doc_file;
				grid_add_data($data_document,$table);
				
			}
			
			if($errors <> ''){
				$this->session->set_flashdata('message', $errors);
				redirect('documents/add_document');
			}else{
				$this->session->set_flashdata('message', 'Document added sucessfully.');
				redirect('documents');
			}
		}
		
		$content_data['other_user_roll'] = get_other_user_roll();
		$content_data['document_types'] = $document_types;
        // set layout data
        $this->template->set_theme(Settings_model::$db_config['default_theme']);
        $this->template->set_layout('school');
        
        $this->template->title('Add Documents');
        $this->template->set_partial('header', 'header');
		$this->template->set_partial('sidebar', 'sidebar');
        $this->template->set_partial('footer', 'footer');
        $this->template->build('add_document', $content_data);
    }
	
   
}
/* End of file documents.php */