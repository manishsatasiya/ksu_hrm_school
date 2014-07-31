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
		   
        $this->db->select('documents.*',FALSE);
        $this->db->from('documents');
		$this->db->where('document_type', $document_type);
		$query = $this->db->get();
		
		$data = array();
		if($query->num_rows() > 0) {
            if($query){
				foreach($query->result_array() AS $result_row){
					$row = array();
					$row['document_id'] = $result_row['document_id'];
					$row['roll_id'] = $result_row['roll_id'];
					$row['document_type'] = $result_row['document_type'];
					$row['name'] = $result_row['name'];
					$row['file'] = $result_row['file'];
					
					$data[] = $row;
				}
			}
        }
		return $data;
    }

}

/* End of file contractors_model.php */