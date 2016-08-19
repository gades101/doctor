<?php

class Payment_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function list_payments() {
        $this->db->order_by("payment_id","desc");
        $query = $this->db->get('view_payment');
        return $query->result_array();
    }
	function insert_payment() {
		$data = array();
		$data['patient_id'] = $this->input->post('patient_id');
		$data['treatment_id'] = $this->input->post('treatment_id');
		$data['paid'] = $this->input->post('paid');
		$data['pay_amount'] = $this->input->post('pay_amount');
		$data['pay_date'] = date('Y-m-d',strtotime($this->input->post('pay_date')));
		$data['pay_mode'] = $this->input->post('pay_mode');
		$data['userid'] = $this->input->post('userid');
		$data['apps_remaining']=($this->input->post('apps_remaining')=="") ? 1 : $this->input->post('apps_remaining');
		$data['notes'] = $this->input->post('notes');
		$this->db->insert('payment', $data);
		$this->db->set('all_paid','all_paid + '.$data['paid'],false);
		$this->db->where('patient_id', $data['patient_id']);
		$this->db->update('patient');
    }

	function new_payment_from_app($treatment) {
		$data = array();
		$data['patient_id'] = $this->input->post('patient_id');
		$data['treatment_id'] = $treatment['id'];
		$data['paid'] = $this->input->post('paid');	
		$data['pay_amount'] = $this->input->post('pay_amount');		
		$data['pay_date'] = date('Y-m-d');
		$data['pay_mode'] = 'cash';
		$data['userid'] = $this->input->post('doctor_id');
		$data['apps_remaining']=($treatment['count']=="") ? 0 : $treatment['count']-1;
		$this->db->insert('payment', $data);
		$_POST['payment_id']=$this->db->insert_id();
		$this->db->set('all_paid','all_paid + '.$data['paid'],false);
		$this->db->where('patient_id', $data['patient_id']);
		$this->db->update('patient');
    }
	
	function get_payment($payment_id){
		$query = $this->db->get_where('payment', array('payment_id' => $payment_id));
        return $query->row();
	}

	function get_curr_payments($patient_id,$payment_id=0){
		//$query = $this->db->get_where('payment', array('patient_id' => $patient_id, 'count > 0'));
		$this->db->select("p.payment_id, p.patient_id, p.treatment_id, p.pay_date, p.pay_amount, p.paid, p.apps_remaining, c.first_name, c.middle_name, t.treatment",FALSE);
		$this->db->from('ck_payment p');
		$this->db->join('ck_treatments t', 'p.treatment_id = t.id', 'left');
		$this->db->join('ck_doctor d', 'p.userid = d.userid', 'left');
		$this->db->join('ck_contacts c', 'd.contact_id = c.contact_id', 'left');
		$this->db->where(array('p.patient_id'=>$patient_id),NULL,FALSE);
		$this->db->or_where("p.payment_id=$payment_id",NULL,FALSE);
		$query=$this->db->get();
		return $query->result_array();
	}
	function edit_payment($payment_id){
		$this->db->select('paid');
		$old_paid=$this->db->get_where('payment',array('payment_id'=>$payment_id));
		$old_paid=$old_paid->row();
		$old_paid=$old_paid->paid;
		$data['patient_id'] = $this->input->post('patient_id');
		$data['treatment_id'] = $this->input->post('treatment_id');
		$data['pay_amount'] = $this->input->post('pay_amount');
		$data['paid'] = $this->input->post('paid');
		$data['pay_mode'] = $this->input->post('pay_mode');
		$data['pay_date'] = $this->input->post('pay_date');
		$data['notes'] = $this->input->post('notes');
		$data['apps_remaining']=$this->input->post('apps_remaining');
		$this->db->where('payment_id', $payment_id);
		$this->db->update('payment', $data);
		if($old_paid!=$data['paid']){
			$this->db->set('all_paid','all_paid+'.($data['paid']-$old_paid),false);
			$this->db->where('patient_id', $data['patient_id']);
			$this->db->update('patient');
		}
	}
	function edit_payment_count($payment_id,$payment_id_orig){
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
    function delete_payment($payment_id) {
        $this->db->delete('payment', array('payment_id' => $payment_id));
		$this->db->set('payment_id',0,false);
		$this->db->where('payment_id', $payment_id);
		$this->db->update('appointment');

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
    public function list_expenses() {
        $this->db->order_by("id","desc");
        $query = $this->db->get('expense');
        return $query->result_array();
    }
 	function insert_expense() {
		$data = array();
		$data['user_id'] = $this->input->post('user_id');
		$data['expense_date'] = date('Y-m-d',strtotime($this->input->post('expense_date')));
		$data['goal'] = $this->input->post('goal');
		$data['sum'] = $this->input->post('sum');
		$data['cat_id'] = $this->input->post('cat_id');
		$this->db->insert('expense', $data);
    }
    public function list_expense_cat() {
        $this->db->order_by("id");
        $query = $this->db->get('expense_categories');
        return $query->result_array();
    }
 	function insert_expense_cat() {
		$data = array();
		$data['title'] = $this->input->post('title');
		if( $this->input->post('parent_id')){
			$pid=$this->input->post('parent_id');
			if(is_int($pid))$pid=(int)$pid.'.';
			else $pid=(float)$pid;
			$data['id']=$pid.'.'.sprintf("%'.02d",(int)$this->input->post('id'));			
		}
		//else $data['id']=$this->input->post('id');
		file_put_contents('t1.txt', print_r($data,true),FILE_APPEND);

		//file_put_contents('t1.txt', print_r($data,true));
		file_put_contents('t1.txt', print_r($this->input->post(),true),FILE_APPEND);

		$this->db->insert('expense_categories', $data);
		file_put_contents('t1.txt',print_r($this->db->error(),true));
    }
}
?>