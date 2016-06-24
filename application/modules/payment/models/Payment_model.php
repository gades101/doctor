<?php

class Payment_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function list_payments() {
        $this->db->order_by("pay_date");
        $query = $this->db->get('view_payment');
        return $query->result_array();
    }
	function insert_payment() {
		$data = array();
		$data['patient_id'] = $this->input->post('patient_id');
		$data['treatment_id'] = $this->input->post('treatment_id');
		$data['pay_amount'] = $this->input->post('pay_amount');
		$data['pay_date'] = date('Y-m-d',strtotime($this->input->post('pay_date')));
		$data['pay_mode'] = $this->input->post('pay_mode');
		$data['userid'] = $this->input->post('userid');
	//	$data['cheque_no'] = $this->input->post('cheque_number');
		$this->db->insert('payment', $data);
    }
	function get_paid_amount($bill_id){
		$this->db->select_sum('pay_amount', 'pay_total');
        $query = $this->db->get_where('payment', array('bill_id' => $bill_id));
		
        $row = $query->row();
        return $row->pay_total;
	}
	function get_payment($payment_id){
		$query = $this->db->get_where('payment', array('payment_id' => $payment_id));
        return $query->row();
	}

	function get_curr_payments($patient_id,$payment_id=0){
		//$query = $this->db->get_where('payment', array('patient_id' => $patient_id, 'count > 0'));
		$this->db->select("p.payment_id, p.patient_id, p.treatment_id, p.pay_date, p.pay_amount, p.apps_remaining, c.first_name, c.middle_name, t.treatment",FALSE);
		$this->db->from('ck_payment p');
		$this->db->join('ck_treatments t', 'p.treatment_id = t.id', 'left');
		$this->db->join('ck_doctor d', 'p.userid = d.userid', 'left');
		$this->db->join('ck_contacts c', 'd.contact_id = c.contact_id', 'left');
		$this->db->where(array('p.patient_id'=>$patient_id,'p.apps_remaining >'=>0),NULL,FALSE);
		$this->db->or_where("p.payment_id=$payment_id",NULL,FALSE);
		$query=$this->db->get();
		return $query->result_array();
	}
	function edit_payment($payment_id){
		$data['patient_id'] = $this->input->post('patient_id');
		$data['treatment_id'] = $this->input->post('treatment_id');
		$data['pay_amount'] = $this->input->post('pay_amount');
		$data['pay_mode'] = $this->input->post('pay_mode');
		$data['pay_date'] = $this->input->post('pay_date');
		$this->db->where('payment_id', $payment_id);
		$this->db->update('payment', $data);
	}
	function edit_payment_count($payment_id,$payment_id_orig){
	//file_put_contents('t1.txt',$payment_id.'-'.$payment_id_orig);
		if($payment_id!=$payment_id_orig){
			$this->db->set('apps_remaining','apps_remaining-1',false);
			$this->db->where('payment_id', $payment_id);
			$this->db->update('payment');
			if($payment_id_orig!=0){
				$this->db->set('apps_remaining','apps_remaining+1',false);
				$this->db->where('payment_id', $payment_id_orig);
				$this->db->update('payment');			
			}
		}
	}
	 function get_all_payments_by_patient($patient_id){
		$query = $this->db->get_where('payment',array('patient_id'=>$patient_id));
		$row = $query->num_rows();
		if ($row > 0) {
        return $query->result_array();
		} else {
        return FALSE;
		}
	 }
}
?>