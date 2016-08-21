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
	function find_exp_cat_new_id($parent_id=NULL){
		if($parent_id){
			$this->db->order_by("id","desc");
			$this->db->where('parent_id', $parent_id);
			$query =$this->db->get('expense_categories','id');
			$id=$query->row_array();
			$parent_id=(float)$parent_id;
			if($id){
				$id=(float)$id['id'];
				if(strlen($id-floor($id))%2!=0)$id.='0';
				$last=substr($id,-1)+1;
				$id=substr_replace($id,$last,-1);					
			}
			else {
				if($id-floor($parent_id)=='0')$parent_id.=".";
				$id=$parent_id."01";
			}
			return $id;
		}
		else {
			$this->db->order_by("id","desc");
			$query =$this->db->get('expense_categories','id');
			$id=$query->row_array();
			$id=intval($id['id'])+1;
			return $id;		
		}
	}
 	function insert_expense_cat() {
		$data = array();
		$data['title'] = strtr($this->input->post('title'),'"',"'");
		$data['parent_id']=$this->input->post('parent_id');
		if( $this->input->post('parent_id')){
			//$pid=$this->input->post('parent_id');
			//if(is_int($pid)){$pid=(int)$pid.'.';		
			//else {$pid=(float)$pid;
			$data['id']=$this->find_exp_cat_new_id($this->input->post('parent_id'));		
			//$data['id']=$pid.sprintf("%'.02d",(int)$this->input->post('id'));			
		}
		else $data['id']=$this->find_exp_cat_new_id(); /*$data['id']=$this->input->post('id');*/
		$this->db->insert('expense_categories', $data);
    }
	function get_edit_exp_category($id){
		$query = $this->db->get_where('expense_categories', array('id' => $id));
        return $query->row_array();
	}
	function edit_exp_category($exp_catgory_id){
		$data['title'] = strtr($this->input->post('title'),'"',"'");
		$this->db->where('id', $exp_catgory_id);
		$this->db->update('expense_categories', $data);	
	}
}
?>