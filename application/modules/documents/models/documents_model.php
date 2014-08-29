<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Documents_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	
	public function delete_document($document_id) {
        $this->db->where('document_id', $document_id);
        $this->db->delete('documents');
        if($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }

	public function get_documents($document_type) {
		$campus_id = $this->session->userdata('campus_id');
		//$campus_id = 0;
		$_like = array();
		if($campus_id <> '' && $campus_id > 0){
			$_like['campus_id'] = $campus_id.'j';
		}
        $this->db->select('documents.*',FALSE);
        $this->db->from('documents');
		$this->db->where('document_type', $document_type);
		!empty($_like) ? $this->db->like($_like) : "";
		$query = $this->db->get();
		
		$data = array();
		if($query->num_rows() > 0) {
            if($query){
				foreach($query->result_array() AS $result_row){
					$row = array();
					$row['document_id'] = $result_row['document_id'];
					$row['campus_id'] = $result_row['campus_id'];
					$row['document_type'] = $result_row['document_type'];
					$row['name'] = $result_row['name'];
					$row['file'] = $result_row['file'];
					
					$data[] = $row;
				}
			}
        }
		return $data;
    }
	
	public function get_document_by_id($id){
    	
		$this->db->select('documents.*',FALSE);
        $this->db->from('documents');
		$this->db->where('documents.document_id', $id);
		
    	$query = $this->db->get();
    	if($query->num_rows() == 1) {
    		$row = $query->row();
    		return $row;
    	}
    	return false;
    }

}

/* End of file contractors_model.php */
