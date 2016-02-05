<?php

class Payment_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function list_payments() {
        $this->db->order_by("pay_date", "desc");
        $query = $this->db->get('view_payment');
        return $query->result_array();
    }
	function insert_payment() {
		$data = array();
		$data['bill_id'] = $this->input->post('bill_id');
		$bill_id = $data['bill_id'];
		$data['pay_amount'] = $this->input->post('payment_amount');
		$pay_amount = $data['pay_amount'];
		$data['pay_date'] = date('Y-m-d',strtotime($this->input->post('payment_date')));
		$data['pay_mode'] = $this->input->post('pay_mode');
		$data['cheque_no'] = $this->input->post('cheque_number');
		$this->db->insert('payment', $data);
		
		$data = array();
		$due_amount = $this->input->post('due_amount');
		$data['due_amount'] = $due_amount - $pay_amount;
		$this->db->where('bill_id', $bill_id);
		$this->db->update('bill', $data);
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
	function edit_payment($payment_id){
		//Get previous details
		$payment = $this->get_payment($payment_id);
		$bill_id = $payment->bill_id;
		$query = $this->db->get_where('bill', array('bill_id' => $bill_id));
		$bill = $query->row();
		$previous_due_amount = $bill->due_amount;
		$previous_payment_amount = $payment->pay_amount;
		
		$data['pay_amount'] = $this->input->post('payment_amount');
		$pay_amount = $data['pay_amount'];
		$data['pay_mode'] = $this->input->post('pay_mode');
		$data['pay_date'] = date('Y-m-d',strtotime($this->input->post('payment_date')));
		$this->db->where('payment_id', $payment_id);
		$this->db->update('payment', $data);
		
		$data = array();
		$due_amount = $this->input->post('due_amount');
		$data['due_amount'] = $previous_due_amount + ( $previous_payment_amount - $pay_amount);
		$this->db->where('bill_id', $bill_id);
		$this->db->update('bill', $data);
	}
}
?>