<?php

class Patient_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function get_patient() {
        $this->db->group_by('patient_id');
        $query = $this->db->get('view_patient');
        return $query->result_array();
    }
   /* public function get_patient_by_id($patient_id) {
        $query = $this->db->get_where('patient', array('patient_id' => $patient_id));
        return $query->result_array();
    }*/
	
    public function find_patient() {
        $this->db->order_by("first_name", "asc");
        $this->db->group_by('patient_id');
        $query = $this->db->get('view_patient');
        return $query->result_array();
    }
    function insert_patient($contact_id) {
        $data['contact_id'] = $contact_id;
        $data['patient_since'] = date("Y-m-d");
        $data['display_id'] = $this->input->post('display_id');
        $data['reference_by'] = $this->input->post('reference_by');
		$data['gender'] = $this->input->post('gender');
		$data['diagnosis']= $this->input->post('diagnosis');
		$data['diagnosis']= $this->input->post('discount');

		if($this->input->post('dob')){
			$data['dob'] = date('Y-m-d',strtotime($this->input->post('dob')));
		}

        $this->db->insert('patient', $data);
        $p_id = $this->db->insert_id();

        //$this->display_id($p_id);
        return $p_id;
    }
    function display_id($id) {
        $lname = $this->input->post('last_name');
        $str = $lname[0];
        $str = strtoupper($str);

        $p_id = $id;
        $n = 5;
        $num = str_pad((int) $p_id, $n, "0", STR_PAD_LEFT);
        $display_id = $str . $num;

        $this->db->set("display_id", $display_id);
        $this->db->where("patient_id", $p_id);
        $this->db->update("patient");
    }
    function delete_patient($patient_id) {
        $this->db->select('contact_id');
        $query = $this->db->get_where('patient', array('patient_id' => $patient_id));
        $row = $query->row();
        if($row) {
            $c_id = $row->contact_id;

            /* Delete ck_contact_details data where Contact Id = $c_id */
            $this->db->delete('contact_details', array('contact_id' => $c_id));

            /* Delete ck_contacts data where Contact Id = $c_id */
            $this->db->delete('contacts', array('contact_id' => $c_id));

            /* Delete ck_visit_img data where Patient Id = $patient_id */
            $this->db->delete('visit_img', array('patient_id' => $patient_id));

            /* Delete ck_visit data where Patient Id = $patient_id */
            $this->db->delete('visit', array('patient_id' => $patient_id));

            /* Delete ck_appointments data where Patient Id = $patient_id */
            $this->db->delete('appointments', array('patient_id' => $patient_id));

            /* Delete ck_bill data where Patient Id = $patient_id */
            $this->db->delete('bill', array('patient_id' => $patient_id));

            /* Delete ck_patient data where Patient Id = $patient_id */
            $this->db->delete('patient', array('patient_id' => $patient_id));
        }
    }
    public function get_patient_detail($patient_id) {
        $query = $this->db->get_where('view_patient', array('patient_id' => $patient_id));
        return $query->row_array();
    }
    public function get_contact_id($patient_id) {
        $query = $this->db->get_where('patient', array('patient_id' => $patient_id));
        $row = $query->row();
        if ($row)
            return $row->contact_id;
        else
            return 0;
    }
    
   function update_pat_ids(){

	$this->db->select('patient_id');
   $query=$this->db->get('patient');
   foreach ($query->result_array() as $row){
		$display_id=array('display_id'=>substr(uniqid(),6,6));
		$this->db->update('patient',$display_id,array('patient_id'=>$row['patient_id']));
   
   }
   //$this->db->query("update patient set display_id=$display_id where patient_id=(select min(id) from foo where id > 4"));
		//$this->db->update('patient',array('display_id'=>substr(uniqid(),6,6)));
   }
    public function get_previous_visits($patient_id) {
        $level = $_SESSION['category'];
        if($level == 'Doctor'){
            $userid = $_SESSION['id'];
            $this->db->order_by("visit_date", "desc");
            $query = $this->db->get_where('visit', array('patient_id' => $patient_id, 'userid' => $userid));
        }else{
            $this->db->order_by("visit_date", "desc");
            $query = $this->db->get_where('visit', array('patient_id' => $patient_id));
        }
        return $query->result_array();
    }
 
    public function get_patient_id($visit_id) {
        $query = $this->db->get_where('visit', array('visit_id' => $visit_id));
        $row = $query->row();
        if ($row)
            return $row->patient_id;
        else
            return 0;
    }
	public function get_doctor_id($visit_id) {
        $query = $this->db->get_where('visit', array('visit_id' => $visit_id));
        $row = $query->row();
        if ($row)
            return $row->userid;
        else
            return 0;
    }

    public function get_reference_by($patient_id){
        $query = $this->db->get_where('patient', array('patient_id' => $patient_id));
        return $query->row_array();
    }
    public function update_reference_by($patient_id){
        $data['reference_by'] = $this->input->post('reference_by');
        $this->db->update('patient', $data, array('patient_id' => $patient_id));
    }
	 public function update_patient_data($patient_id){
        $data['gender'] = $this->input->post('gender');
        $data['diagnosis'] = $this->input->post('diagnosis');
        $data['discount'] = $this->input->post('discount');
		if($this->input->post('dob')){
			$data['dob'] = date('Y-m-d',strtotime($this->input->post('dob')));
		}
        $this->db->update('patient', $data, array('patient_id' => $patient_id));
    }


	function get_template(){
		$query = $this->db->get_where('receipt_template', array('is_default' => 1,'type'=>'bill'));
        $row = $query->row_array();
		return $row;
	}
	function check_patient_event($patient_id){
		$query=$this->db->get_where('events',array('patient_id'=>$patient_id));
		$row = $query->num_rows();
		if ($row > 0) {
			return 1;
		} else {
         return 0;
		}
	}
}
?>