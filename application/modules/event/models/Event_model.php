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
      $data['title'] = $this->input->post('title');
	  $date=$this->input->post('date');
      $data['day'] = substr($date,0,2);
      $data['month'] = substr($date,3,2);
      if($this->input->post('year')){
			$data['year'] = $this->input->post('year');      
      }
      $this->db->insert('events',$data);
   }

   public function add_patients_event() {
		$this->db->select("p.patient_id, p.dob, c.first_name, c.middle_name",FALSE);
		$this->db->from('ck_patient p,ck_contacts c');
		$this->db->where(array('p.dob is not NULL', 'p.contact_id'=>'c.contact_id'),NULL,FALSE);
		$query=$this->db->get();
		$arr= $query->result_array();
		foreach ($arr as $patient){
			$data['title'] = $patient['first_name']." ".$patient['middle_name']." День Народження";
			$data['day'] = substr($patient['dob'],8,2);
			$data['month'] = substr($patient['dob'],5,2);
			$data['patient_id'] = $patient['patient_id'];		
			$this->db->insert('events',$data);			
		}
   }   
   
   public function get_edit_event($id) {    
      $this->db->where("id", $id);
      $query = $this->db->get("events");
      return $query->row_array();    
   }
   public function edit_event($id){
      $data['title'] = $this->input->post('title');
		$date=$this->input->post('date');
      $data['day'] = substr($date,0,2);
      $data['month'] = substr($date,3,5);      if($this->input->post('year')){
			$data['year'] = $this->input->post('year');      
      }    
      $this->db->where('id', $id);
      $this->db->update('events', $data);
   }
    public function delete_event($id) {
        $this->db->delete('events', array('id' => $id));
    }	
}

?>
