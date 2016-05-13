<?php

class Event_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
	public function get_events(){
		$result=$this->db->get('events');
		return $result->result_array();
	}

   public function add_event() {
       file_put_contents('t1.txt',print_r($this->input->post('title'),true));

      $data['title'] = $this->input->post('title');
      $data['date'] = $this->input->post('date');
      if($this->input->post('year')){
			$data['year'] = $this->input->post('year');      
      }
      $this->db->insert('events',$data);
   }
   public function get_edit_event($id) {    
      $this->db->where("id", $id);
      $query = $this->db->get("events");
      return $query->row_array();    
   }
   public function edit_event($id){
      $data['title'] = $this->input->post('title');
      $data['date'] = $this->input->post('date');
      if($this->input->post('year')){
			$data['year'] = $this->input->post('year');      
      }
      $this->db->where('id', $id);
      $this->db->update('treatments', $data);
   }
    public function delete_event($id) {
        $this->db->delete('events', array('id' => $id));
    }	
}

?>
