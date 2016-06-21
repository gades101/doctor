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
	function get_curr_payments($patient_id){
		$query = $this->db->get_where('payment', array('patient_id' => $patient_id, 'count > 0'));
		$this->db->select("p.payment_id, p.patient_id, p.treatment_id, p.pay_date, p.pay_amount, p.apps_remaining, t.treatment",FALSE);
		$this->db->from('ck_payment p, ck_treatments t');
		$this->db->where(array('p.patient_id'=>$patient_id, 'p.treatment_id'=>'t.id','p.apps_remaining>0'),NULL,FALSE);
		return $query->result_array();
	}
	function edit_payment($payment_id){
		//Get previous details
		//$payment = $this->get_payment($payment_id);
		//$previous_payment_amount = $payment->pay_amount;	
		//$pay_amount = $data['pay_amount'];
		$data['patient_id'] = $this->input->post('patient_id');
		$data['treatment_id'] = $this->input->post('treatment_id');
		$data['pay_amount'] = $this->input->post('pay_amount');
		$data['pay_mode'] = $this->input->post('pay_mode');
		$data['pay_date'] = $this->input->post('pay_date');
		$this->db->where('payment_id', $payment_id);
		$this->db->update('payment', $data);
		/*$data = array();
		$due_amount = $this->input->post('due_amount');
		$data['due_amount'] = $previous_due_amount + ( $previous_payment_amount - $pay_amount);
		$this->db->where('bill_id', $bill_id);
		$this->db->update('bill', $data);*/
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