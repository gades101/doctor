<?php

class Settings_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
	/**Clinic*/
    public function get_clinic_settings() {
        $query = $this->db->get('clinic');
        return $query->row_array();
    }
    public function save_clinic_settings() {
        $data['clinic_name'] = $this->input->post('clinic_name');
        $data['tag_line'] = $this->input->post('tag_line');
        $data['clinic_address'] = $this->input->post('clinic_address');
        $data['landline'] = $this->input->post('landline');
        $data['mobile'] = $this->input->post('mobile');
        $data['email'] = $this->input->post('email');
        $data['start_time'] = $this->input->post('start_time');
        $data['end_time'] = $this->input->post('end_time');
        $data['time_interval'] = $this->input->post('time_interval');
        $data['next_followup_days'] = $this->input->post('next_followup_days');        
		$data['facebook'] = $this->input->post('facebook');
		$data['twitter'] = $this->input->post('twitter');
		$data['google_plus'] = $this->input->post('google_plus');
        
        $this->db->update('clinic', $data, array('clinic_id' => 1));
    }
	public function update_clinic_logo($file_name) {
		if($file_name != NULL && $file_name != "" ){
			$data['clinic_logo'] = 'images/'. $file_name;
		}else{
			$data['clinic_logo'] = '';
		}
		$this->db->update('clinic', $data,array('clinic_id' => 1));
	}
	public function remove_clinic_logo() {
		$data['clinic_logo'] = '';
		$this->db->update('clinic', $data,array('clinic_id' => 1));
	}
    public function get_clinic_start_time() {
        $query = $this->db->get('clinic');
        $row = $query->row_array();
        if (!$row) {
            return '09:00';
        }
        return $row['start_time'];
    }
    public function get_clinic_end_time() {
        $query = $this->db->get('clinic');
        $row = $query->row_array();
        if (!$row) {
            return '18:00';
        }
        return $row['end_time'];
    }
    public function get_time_interval() {
        $query = $this->db->get('clinic');
        $row = $query->row_array();
        if (!$row) {
            return '0.50';
        }
        return $row['time_interval'];
    }
	/**Invoice*/
    public function get_invoice_settings() {
        $query = $this->db->get('invoice');
        return $query->row_array();
    }
    public function get_currency_postfix(){
        $this->db->select('currency_postfix');
        $query = $this->db->get('invoice');
        return $query->row_array();        
    }
    public function save_invoice_settings() {
        $data['static_prefix'] = $this->input->post('static_prefix');
        $data['left_pad'] = $this->input->post('left_pad');
        $data['currency_symbol'] = $this->input->post('currency_symbol');
        $data['currency_postfix'] = $this->input->post('currency_postfix');

        $this->db->update('invoice', $data, array('invoice_id' => 1));
    }
    public function get_invoice_next_id() {
        $query = $this->db->get('invoice');
        $row = $query->row_array();
        return $row['next_id'];
    }
    public function increment_next_id() {
        $next_id = $this->get_invoice_next_id();
        $next_id++;
        $data->next_id = $next_id;

        $this->db->update('invoice', $data, array('invoice_id' => 1));
    }
	public function get_treatments(){
        $result = $this->db->get('treatments');
        return $result->result_array();
    }
    public function add_treatment() {
        $data['treatment'] = $this->input->post('treatment');
        $data['price'] = $this->input->post('treatment_price');
        $this->db->insert('treatments',$data);
    }
    public function get_edit_treatment($id) {    
        $this->db->where("id", $id);
        $query = $this->db->get("treatments");
        return $query->row_array();    
    }
    public function edit_treatment($id){
        $data['treatment'] = $this->input->post('treatment');
        $data['price'] = $this->input->post('treatment_price');
        $this->db->where('id', $id);
        $this->db->update('treatments', $data);
    }
    public function delete_treatment($id) {
        $this->db->delete('treatments', array('id' => $id));
    }
    public function get_visit_treatment($visit_id){
        $bill_id = patient_model::get_bill_id($visit_id);
        
        $query = $this->db->get_where('bill_detail', array('bill_id' => $bill_id));
        return $query->result_array();
    }
	public function get_time_zone(){
	   $this->db->select('ck_value');
	   $query=$this->db->get_where('data',array('ck_key'=>'default_timezone'));
	   $row=$query->row();
	   return $row->ck_value;
	}
	public function save_timezone($key, $value) {
		$this->db->where('ck_key', $key);
		$db_array = array('ck_value' => $value);
		$this->db->update('data', $db_array);	
	}
	public function get_time_formate(){
	   $this->db->select('ck_value');
	   $query=$this->db->get_where('data',array('ck_key'=>'default_timeformate'));
	   $row=$query->row();
	   return $row->ck_value;
	}
	public function save_timeformate($key, $value) {
		$this->db->where('ck_key', $key);
		$db_array = array('ck_value' => $value);
		$this->db->update('data', $db_array);	
	}
	public function get_date_formate(){
	   $this->db->select('ck_value');
	   $query=$this->db->get_where('data',array('ck_key'=>'default_dateformate'));
	   $row=$query->row();
	   return $row->ck_value;
	}
	public function get_morris_date_format(){
		$date_format = $this->get_date_formate();
		if($date_format == "d-m-Y"){
			return 'DD-MM-YYYY';
		}elseif($date_format == "Y-m-d"){
			return 'YYYY-MM-DD';
		}
	}
	public function get_morris_time_format(){
		$time_format = $this->get_time_formate();
		if($time_format == "h:i A"){
			return 'h:mm a';
		}elseif($time_format == "H:i"){
			return 'H:mm';
		}elseif($time_format == "H:i:s"){
			return 'H:mm:ss';
		}
	}
	public function save_dateformate($key, $value) {
		$this->db->where('ck_key', $key);
		$db_array = array('ck_value' => $value);
		$this->db->update('data', $db_array);	
	}
    function get_log(){
        $start_date = date("Y-m-d H:i", strtotime($this->input->post('start_date')));
        $end_date = date("Y-m-d H:i", strtotime($this->input->post('end_date')));
        $user_name=($this->input->post('user_name'))?" AND user_name=".$this->db->escape($this->input->post('user_name'))." " :"";
        $event_table=($this->input->post('event_table'))?" AND event_table=".$this->db->escape($this->input->post('event_table'))." " :"";
        $type=($this->input->post('type'))?" AND type=".$this->db->escape($this->input->post('type'))." " :"";
        $query_str="SELECT  user_name, event_table, type, date, vars FROM ck_event_log WHERE date >=".$this->db->escape($start_date)." AND date <".$this->db->escape($end_date).$user_name.$event_table.$type." ORDER BY id DESC";
        $res=$this->db->query($query_str);
        return $res->result_array();
              /*  $date=" c.pay_date >=". $this->db->escape($start_date)." and c.pay_date < ". $this->db->escape($end_date)." ";
                $user=($this->input->post('user_id'))?" AND p.userid=".$this->input->post('user_id')." " :"";
                $treatment=($this->input->post('treatment_id'))?" AND p.treatment_id=".$this->input->post('treatment_id')." ":"";
                $department = ($this->input->post('department_id'))?" AND p.department_id=".$this->input->post('department_id')." ":"";
                //$query_str="SELECT u.name, p.pay_date date, p.paid summ, d.department_name department, t.treatment FROM ck_payment p LEFT JOIN ck_users u ON p.userid=u.userid LEFT JOIN ck_department d ON p.department_id=d.department_id LEFT JOIN ck_treatments t ON p.treatment_id=t.id WHERE".$date.$user.$treatment.$department."ORDER BY p.payment_id DESC";
                $query_str="SELECT u.name, c.pay_date date, c.paid summ, d.department_name department, t.treatment FROM ck_payment_fee c LEFT JOIN ck_payment p ON c.payment_id = p.payment_id LEFT JOIN ck_users u ON p.userid=u.userid LEFT JOIN ck_department d ON p.department_id=d.department_id LEFT JOIN ck_treatments t ON p.treatment_id=t.id WHERE".$date.$user.$treatment.$department."ORDER BY p.payment_id DESC";*/
    }
}

?>
