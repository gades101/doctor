<?php

class Doctor_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
	public function find_doctor($user_id = NULL) {

		$this->db->select('*');
		$this->db->from('doctor');
		$this->db->join('contacts', 'doctor.contact_id = contacts.contact_id');
		if(isset($user_id)){
			$this->db->where('userid',$user_id);
		}
		$query = $this->db->get();
		//echo $this->db->last_query();
		if(isset($user_id)){
			return $query->row_array();
		}else{
			return $query->result_array();
		}
    }
	public function get_doctor_user_id($user_id) {
		$query = $this->db->get_where('doctor', array('userid' => $user_id));
		return $query->row_array();
    }
    function insert_doctors($contact_id) {
		$this->load->helper('string');


		$query = $this->db->get_where('contacts', array('contact_id' => $contact_id));
        $contact = $query->row_array();
		$name = $contact['first_name'].' '.$contact['middle_name'].' '.$contact['last_name'];
		$username = slugify($contact['first_name']).$contact_id;
		$data = array(
            'name' => $name,
            'username' => $username,
            'level' => 'Doctor',
            'password' => base64_encode($username)
        );
        $this->db->insert('users', $data);
		$userid = $this->db->insert_id();

		$data = array();
		$data['contact_id'] = $contact_id;
		/*$data['degree'] = $this->input->post('degree');
		$data['specification'] = $this->input->post('specification');
		$data['joining_date'] = $this->input->post('joining_date');
		$data['licence_number'] = $this->input->post('licence_number');
		$data['department_id'] = $this->input->post('department_id');
		$data['gender'] = $this->input->post('gender');*/
		if($this->input->post('degree') != false){
            $data['degree'] = $this->input->post('degree');
		}
		if($this->input->post('specification') != false){
            $data['specification'] = $this->input->post('specification');
		}
		if($this->input->post('joining_date') != false){
            $data['joining_date'] = $this->input->post('joining_date');
		}
		if($this->input->post('licence_number') != false){
            $data['licence_number'] = $this->input->post('licence_number');
		}
		if($this->input->post('department_id') != false){
			$data['department_id'] = $this->input->post('department_id');
		}
		if($this->input->post('gender') != false){
			$data['gender'] = $this->input->post('gender');
        }

		$data['userid'] = $userid;
        $this->db->insert('doctor', $data);
		return $this->db->insert_id();
    }
	function update_doctors() {

		$doctor_id	=	$this->input->post('doctor_id');
        $data['degree'] = $this->input->post('degree');
		$data['specification'] = $this->input->post('specification');
		$data['experience'] = $this->input->post('experience');
		$data['joining_date'] = $this->input->post('joining_date');
		$data['licence_number'] = $this->input->post('licence_number');
		$data['department_id'] = $this->input->post('department_id');
		$data['gender'] = $this->input->post('gender');
		$this->db->update('doctor', $data, array('doctor_id' =>  $doctor_id));
    }
	function get_doctor_details($doctor_id){
		$query = $this->db->get_where('doctor', array('doctor_id' => $doctor_id));
        return $query->row_array();
	}
	function delete_doctor($doctor_id) {
		$doctor = $this->get_doctor_details($doctor_id);
		$contact_id = $doctor['contact_id'];
		$this->db->delete('doctor', array('doctor_id' => $doctor_id));
		$this->db->delete('contacts', array('contact_id' => $contact_id));
    }
	function copy_from_users(){
		//Loop through all doctor
		$query = $this->db->get_where('users', array('level' => 'Doctor'));
        $doctors =  $query->result_array();
		foreach($doctors as $doctor){
			//print_r ($doctor);
			$query = $this->db->get_where('doctor', array('userid' => $doctor['userid']));
			if ($query->num_rows() > 0){
				//Doctor already created
			}else{
				$display_name = $doctor['name'];
				$display_name = str_replace("Dr. ","",$display_name);
				$display_name = str_replace("Dr ","",$display_name);

				$name = explode(" ", $display_name);
				if(count($name) >= 3){
					$first_name = $name[0];
					$middle_name = $name[1];
					$last_name = $name[2];
				}elseif (count($name) == 2){
					$first_name = $name[0];
					$middle_name = '';
					$last_name = $name[1];
				}elseif (count($name) == 1){
					$first_name = $name[0];
					$middle_name = '';
					$last_name = '';
				}
				//Insert into Contact
				$data_contacts['first_name'] = $first_name;
				$data_contacts['middle_name'] = $middle_name;
				$data_contacts['last_name'] = $last_name;
				$data_contacts['display_name'] = $display_name;
				$this->db->insert('contacts', $data_contacts);
				$contact_id = $this->db->insert_id();

				//Insert in Doctor's Table
				$data['contact_id'] = $contact_id;
				$data['userid'] = $doctor['userid'];
				$this->db->insert('doctor', $data);
			}
		}


	}
	/*department ---------------------------------------------------------------------------------------*/
	public function get_all_departments() {
        $query = $this->db->get("department");
        return $query->result_array();

    }
	public function get_department($department_id) {
		$query = $this->db->get_where('department', array('department_id' => $department_id));
		return $query->row_array();
	}
    public function update_department() {
		$department_id = $this->input->post('department_id');
		$data['department_id'] = $this->input->post('department_id');
		$data['department_name'] = $this->input->post('department_name');
		$this->db->update('department', $data, array('department_id' =>  $department_id));
	}
	function add_department() {
        $data['department_name'] = $this->input->post('department_name');
        $this->db->insert('department', $data);
		return $this->db->insert_id();
		//echo $this->db->last_query();

    }
	function delete_department($id) {
        $this->db->delete('department', array('department_id' => $id));
    }
	/* fees master ------------------------------------------------------------------------*/
	public function find_fees() {
         $query = $this->db->get("fee_master");
        return $query->result_array();

    }
	public function get_fees($id) {
        $query = $this->db->get_where('fee_master', array('id' => $id));
        return $query->row_array();
	}
	public function get_doctor_fees($doctor_id) {
        $query = $this->db->get_where('fee_master', array('doctor_id' => $doctor_id));
        return $query->result_array();
	}
    public function update_fees() {
		$id = $this->input->post('id');
		$data['id'] = $this->input->post('id');
		$data['doctor_id'] = $this->input->post('doctor');
		$data['detail'] = $this->input->post('detail');
		$data['fees'] = $this->input->post('fees');
		$this->db->update('fee_master', $data, array('id' =>  $id));
	}
	function add_fees() {
        $data['doctor_id'] = $this->input->post('doctor');
		$data['detail'] = $this->input->post('detail');
		$data['fees'] = $this->input->post('fees');
        $this->db->insert('fee_master', $data);
		return $this->db->insert_id();
    }
	function delete_fees($id) {
        $this->db->delete('fee_master', array('id' => $id));
    }
	/*Doctor Schedule -----------------------------------------------------------------------------------------------*/
	function find_drschedule(){
		$query = $this->db->get("doctor_schedule");
        return $query->result_array();
	}
	function get_schedule_from_id($schedule_id){
		$this->db->where('schedule_id', $schedule_id);
		$query = $this->db->get("doctor_schedule");
        return $query->row_array();
	}
	function add_drschedule(){
		$data['doctor_id'] = $this->input->post('doctor');
		$data['schedule_day'] = implode(',', $this->input->post('day'));
		$data['from_time'] = date('H:i:s',strtotime($this->input->post('from_time')));
		$data['to_time'] = date('H:i:s',strtotime($this->input->post('to_time')));
        $this->db->insert('doctor_schedule', $data);
		return $this->db->insert_id();
	}
	function edit_drschedule(){
		$schedule_id = $this->input->post('schedule_id');
		$data['doctor_id'] = $this->input->post('doctor_id');
		$data['schedule_day'] = implode(',', $this->input->post('day'));
		$data['from_time'] = $this->input->post('from_time');
		$data['to_time'] = $this->input->post('to_time');
		$this->db->update('doctor_schedule', $data, array('schedule_id' =>  $schedule_id));
	}
	function delete_drschedule($id) {
        $this->db->delete('doctor_schedule', array('schedule_id' => $id));
    }
	function find_patientid($id){
		$this->db->select('patient_id');
		$this->db->from('doctor_schedule');
		$this->db->where('id', $id);
		return $this->db->get()->result();
	}

	public function get_dr_inavailability($appointment_id = NULL, $user_id = NULL) {
        $level = $_SESSION['category'];

		if($appointment_id != NULL && $user_id!=NULL)
		{
				$this->db->where('end_date IS NOT NULL');
				$this->db->where('appointment_id', $appointment_id);
				$this->db->where('userid', $user_id);
				$query=$this->db->get('appointments');

				return $query->row_array();
		}
		else
		{
			if($level == 'Doctor')
			{
				$userid = $_SESSION['id'];
				$this->db->where('end_date IS NOT NULL');
				$this->db->where('status', 'NotAvailable');
				$this->db->where('userid', $userid);
				$this->db->order_by('appointment_id');
				$query=$this->db->get('appointments');

			}
			else
			{
				$this->db->where('end_date IS NOT NULL');
				$this->db->where('status', 'NotAvailable');
				$this->db->order_by('appointment_id');
				$query=$this->db->get('appointments');

			}
			return $query->result_array();
		}
    }
	function insert_availability($appointment_id = NULL){
		$start_date = date("Y-m-d", strtotime($this->input->post('start_date')));
        $data['appointment_date'] = $start_date;
		$end_date=date("Y-m-d", strtotime($this->input->post('end_date')));
		$data['end_date']=$end_date;

		//Set Time Zone
		$timezone = $this->settings_model->get_time_zone();
        if (function_exists('date_default_timezone_set'))
            date_default_timezone_set($timezone);

		//$timeformat = $this->settings_model->get_time_formate();
		$data['start_time'] = date('H:i',strtotime($this->input->post('start_time')));
		$data['end_time'] =  date('H:i',strtotime($this->input->post('end_time')));
		//$data['start_time'] = $this->input->post('start_time');
		//$data['end_time'] =  $this->input->post('end_time');

		$data['status'] = 'NotAvailable';
		$data['visit_id']=0;
		$data['patient_id']=0;
		$data['title']="";
		if($this->input->post('doctor_id')==0)
		{
			$doc_id = $this->input->post('doctor');
		}
		else
		{
			$doc_id = $this->input->post('doctor_id');
		}
		$data['userid'] = $doc_id;

		if($appointment_id == NULL){
			$this->db->insert('appointments', $data);
		}else{
			$this->db->where('appointment_id', $appointment_id);
			$this->db->update('appointments', $data);

		}
	}
}
?>