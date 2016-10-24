<?php

class Payment_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function list_payments() {
		$start_date = date("Y-m-d H:i", strtotime($this->input->post('start_date')));
    	$end_date = date("Y-m-d H:i", strtotime($this->input->post('end_date')));
    	$this->db->where('pay_date>=',$this->db->escape($start_date)." AND pay_date<".$this->db->escape($end_date),false);
        $this->db->order_by("payment_id","desc");
        $query = $this->db->get('view_payment');
        return $query->result_array();
    }



	function insert_payment() {
		$data = array();
		$data['patient_id'] = $this->input->post('patient_id');
		$data['treatment_id'] = $this->input->post('treatment_id');
		$data['paid'] = $this->input->post('add_money');
		$data['pay_amount'] = $this->input->post('pay_amount');
		$data['pay_date'] = date('Y-m-d H:i',strtotime($this->input->post('pay_date')));
		$data['pay_mode'] = $this->input->post('pay_mode');
		$data['userid'] = $this->input->post('user_id');
		$data['apps_remaining']=($this->input->post('apps_remaining')=="") ? 1 : $this->input->post('apps_remaining');
		$data['notes'] = $this->input->post('notes');
		$data['department_id'] = $this->input->post('department_id');
		$this->db->insert('payment', $data);
  		$this->event_log('Рахунок','Створення',$data);
		if($data['paid']!=0 && $this->db->insert_id()){
			$this->db->insert('payment_fee',array('payment_id'=>$this->db->insert_id(),'pay_date'=>$data['pay_date'],'paid'=>$data['paid']));
			$this->event_log('Оплата','Створення',array('payment_id'=>$this->db->insert_id(),'pay_date'=>$data['pay_date'],'paid'=>$data['paid']));

		}
		$this->db->set('all_paid','all_paid + '.$data['paid'],false);
		$this->db->where('patient_id', $data['patient_id']);
		$this->db->update('patient');

    }

	function new_payment_from_app($treatment) {
		$data = array();
		$data['patient_id'] = $this->input->post('patient_id');
		$data['treatment_id'] = $treatment['id'];
		$data['paid'] = $this->input->post('add_money');	
		$data['pay_amount'] = $this->input->post('pay_amount');		
		$data['pay_date'] = date('Y-m-d H:i');
		$data['pay_mode'] = 'cash';
		$data['userid'] = $this->input->post('doctor_id');
		$data['apps_remaining']=($treatment['count']=="") ? 0 : $treatment['count']-1;
		$data['department_id'] = $this->input->post('department_id');
		$this->db->insert('payment', $data);
  		$this->event_log('Оплата','Створення',$data);
		if($data['paid']!=0 && $this->db->insert_id()){
			$this->db->insert('payment_fee',array('payment_id'=>$this->db->insert_id(),'pay_date'=>$data['pay_date'],'paid'=>$data['paid']));
			$this->event_log('Оплата','Створення',array('payment_id'=>$this->db->insert_id(),'pay_date'=>$data['pay_date'],'paid'=>$data['paid']));
		}
		$_POST['payment_id']=$this->db->insert_id();
		$this->db->set('all_paid','all_paid + '.$data['paid'],false);
		$this->db->where('patient_id', $data['patient_id']);
		$this->db->update('patient');
    }

 	function new_fee_from_app() {
 		$this->db->insert('payment_fee',array('payment_id'=>$this->input->post('payment_id'),'pay_date'=>date('Y-m-d H:i'),'paid'=>$this->input->post('add_money')));
  		$this->event_log('Оплата','Створення',array('payment_id'=>$this->input->post('payment_id'),'pay_date'=>date('Y-m-d H:i'),'paid'=>$this->input->post('add_money')));
 		$this->db->set('paid', 'paid+'.$this->input->post('add_money'),false);
		$this->db->where('payment_id', $this->input->post('payment_id'));
 		$this->db->update('payment');
 	}
	
	function get_payment($payment_id){
		$query = $this->db->get_where('payment', array('payment_id' => $payment_id));
        return $query->row();
	}


	function get_curr_payments($patient_id,$payment_id=0){
		//$query = $this->db->get_where('payment', array('patient_id' => $patient_id, 'count > 0'));
		$this->db->select("p.payment_id, p.pay_date, p.paid, p.patient_id, p.treatment_id, p.pay_amount, p.apps_remaining, t.treatment",FALSE);
		$this->db->from('ck_payment p');
		$this->db->join('ck_treatments t', 'p.treatment_id = t.id', 'left');
		$this->db->where("(p.patient_id=$patient_id AND p.apps_remaining > 0) or (p.payment_id=$payment_id AND p.apps_remaining = 0)");
		$this->db->order_by('p.payment_id','desc');
		$query=$this->db->get();
		return $query->result_array();
	}

	/*function get_curr_payments($patient_id,$payment_id=0){
		//$query = $this->db->get_where('payment', array('patient_id' => $patient_id, 'count > 0'));
		$this->db->select("p.payment_id, p.pay_date, p.paid, p.patient_id, p.treatment_id, p.pay_amount, p.apps_remaining, t.treatment",FALSE);
		$this->db->from('ck_payment p');
		$this->db->join('ck_treatments t', 'p.treatment_id = t.id', 'left');
		$this->db->where(array('p.patient_id'=>$patient_id),NULL,FALSE);
		$this->db->or_where("p.payment_id=$payment_id",NULL,FALSE);
		$this->db->order_by('payment_id','desc');
		$query=$this->db->get();
		return $query->result_array();
	}*/
	function get_curr_fees($patient_id,$payment_id=0){
		$this->db->select("ch.payment_fee_id, ch.payment_id, ch.pay_date, ch.paid, p.patient_id, p.treatment_id, p.pay_amount, p.apps_remaining, c.first_name, c.middle_name, t.treatment",FALSE);
		$this->db->from('ck_payment_fee ch');
		$this->db->join('ck_payment p', 'ch.payment_id = p.payment_id', 'left');
		$this->db->join('ck_treatments t', 'p.treatment_id = t.id', 'left');
		$this->db->join('ck_doctor d', 'p.userid = d.userid', 'left');
		$this->db->join('ck_contacts c', 'd.contact_id = c.contact_id', 'left');
		$this->db->where(array('p.patient_id'=>$patient_id),NULL,FALSE);
		$this->db->or_where("p.payment_id=$payment_id",NULL,FALSE);
		$this->db->order_by('payment_id','desc');
		$query=$this->db->get();
		return $query->result_array();
	}

	function get_curr_ajax_fees($payment_id){
		$query = $this->db->get_where('payment_fee', array('payment_id' => $payment_id));
		return $query->result_array();
	}
	function edit_payment($payment_id){
		$this->db->set('patient_id',$this->input->post('patient_id'));
		$this->db->set('treatment_id', $this->input->post('treatment_id'));
		$this->db->set('pay_amount', $this->input->post('pay_amount'));
		if($this->input->post('add_money')>0) $this->db->set('paid', 'paid+'.$this->input->post('add_money'),false);
		else $this->db->set('paid', $this->input->post('paid'));
		$this->db->set('pay_mode', $this->input->post('pay_mode'));
		$this->db->set('pay_date', $this->input->post('pay_date'));
		$this->db->set('notes', $this->input->post('notes'));
		$this->db->set('apps_remaining', $this->input->post('apps_remaining'));
		$this->db->set('department_id', $this->input->post('department_id'));
		$this->db->where('payment_id', $payment_id);
		$this->db->update('payment');
  		$this->event_log('Рахунок','Зміна',$this->input->post());
		if($this->input->post('add_money')>0){
			$this->db->set('all_paid','all_paid+'.$this->input->post('add_money'),false);
			$this->db->where('patient_id', $this->input->post('patient_id'));
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
  		$this->event_log('Рахунок','Видалення',array('payment_id' => $payment_id));        
        $this->db->delete('payment_fee', array('payment_id' => $payment_id));
  		$this->event_log('Оплата','Видалення',array('payment_id' => $payment_id));  
		$this->db->set('payment_id',0,false);
		$this->db->where('payment_id', $payment_id);
		$this->db->update('appointments');

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
 		$start_date = date("Y-m-d H:i", strtotime($this->input->post('start_date')));
    	$end_date = date("Y-m-d H:i", strtotime($this->input->post('end_date')));
		$this->db->select("e.id, e.user_id, DATE_FORMAT(e.expense_date, '%Y-%m-%d %H:%i') AS expense_date, e.goal, c.title, e.sum, ck_users.name",FALSE);
		$this->db->from('ck_expense e');
		$this->db->join('ck_users', 'e.user_id = ck_users.userid', 'left');
		$this->db->join('ck_expense_categories c', 'e.cat_id=c.id', 'left');
		$this->db->where("e.expense_date>=".$this->db->escape($start_date)." AND e.expense_date<".$this->db->escape($end_date),NULL,FALSE);
		$this->db->order_by('e.id','desc');
        $query = $this->db->get();
        return $query->result_array();
    }
 	function insert_expense() {
		$data = array();
		$data['user_id'] = $this->input->post('user_id');
		$data['expense_date'] = date('Y-m-d H:i',strtotime($this->input->post('expense_date')));
		$data['goal'] = $this->input->post('goal');
		$data['sum'] = $this->input->post('sum');
		$data['cat_id'] = $this->input->post('cat_id');
		$data['department_id'] = $this->input->post('department_id');
		$this->db->insert('expense', $data);
  		$this->event_log('Витрата','Створення',$data);

    }
    function delete_expense($id) {
        $this->db->delete('expense', array('id' => $id));
  		$this->event_log('Витрата','Видалення', array('id' => $id));  

    }
	function get_edit_expense($id){
		$query = $this->db->get_where('expense', array('id' => $id));
        return $query->row_array();
	}
	function edit_expense($id){  	 	 	 	 	
		$data['id'] = $id;
		$data['expense_date'] =  date("Y-m-d",strtotime($this->input->post('expense_date')));
		$data['user_id'] = $this->input->post('user_id');
		$data['cat_id'] = $this->input->post('cat_id');
		$data['goal'] = $this->input->post('goal');
		$data['sum'] = $this->input->post('sum');
		$data['department_id'] = $this->input->post('department_id');
		$this->db->where('id', $id);
		$this->db->update('expense', $data);
  		$this->event_log('Витрата','Зміна', $data);  
	}
    public function list_expense_cat() {
        $this->db->order_by("id");
        $query = $this->db->get('expense_categories');
        $expense_categories= $query->result_array();
		foreach ($expense_categories as $key => $expense) {
			$id=$expense['id'];
			$len=strlen($expense['id']);
			$j=$len-10;
			$view_id=substr($id, 0, $j-1);

			for ($i=0; $i < 10; $i+=2) {
				$para=substr($id, $j, 2);
				if($para==='00') break;
				else {
					$view_id.='.'.(int)$para;
					$j+=2;

				}
			}
			$expense_categories[$key]['view_id']=$view_id;
		}
		return $expense_categories;
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
				if($parent_id-floor($parent_id)=='0'){
					$parent_id.=".";
				}
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
	function create_report(){
        $start_date = date("Y-m-d H:i", strtotime($this->input->post('start_date')));
        $end_date = date("Y-m-d H:i", strtotime($this->input->post('end_date')));
 		$departments = $this->doctor_model->get_all_departments();
 		$i=0; $query_str="";
 		foreach ($departments as $dep) {
 			$id=$dep['department_id'];
 			if ($i!=0) $query_str.=" UNION ";
 			$query_str.="SELECT ".$this->db->escape($dep['department_name'])." department_name, sum(p.paid) pay_summ, (SELECT sum(e.sum) summ FROM ck_expense e WHERE e.expense_date >=".$this->db->escape($start_date)." AND e.expense_date <".$this->db->escape($end_date)." AND e.department_id=$id) exp_summ  FROM ck_payment p  WHERE p.pay_date >=". $this->db->escape($start_date)." AND p.pay_date < ". $this->db->escape($end_date)." AND p.department_id=$id";
 			$i+=1;
 		}
		$res=$this->db->query($query_str);
		return $res->result_array();
	}
	function create_dir_report($page){
		$start_date = date("Y-m-d H:i", strtotime($this->input->post('start_date')));
    	$end_date = date("Y-m-d H:i", strtotime($this->input->post('end_date')));
    	if($page==1){
	    	if($this->input->post('operation')==1){
		    	$date=" c.pay_date >=". $this->db->escape($start_date)." and c.pay_date < ". $this->db->escape($end_date)." ";
				$user=($this->input->post('user_id'))?" AND p.userid=".$this->input->post('user_id')." " :"";
				$treatment=($this->input->post('treatment_id'))?" AND p.treatment_id=".$this->input->post('treatment_id')." ":"";
		 		$department = ($this->input->post('department_id'))?" AND p.department_id=".$this->input->post('department_id')." ":"";
		 		//$query_str="SELECT u.name, p.pay_date date, p.paid summ, d.department_name department, t.treatment FROM ck_payment p LEFT JOIN ck_users u ON p.userid=u.userid LEFT JOIN ck_department d ON p.department_id=d.department_id LEFT JOIN ck_treatments t ON p.treatment_id=t.id WHERE".$date.$user.$treatment.$department."ORDER BY p.payment_id DESC";
		 		$query_str="SELECT u.name, c.pay_date date, c.paid summ, d.department_name department, t.treatment FROM ck_payment_fee c LEFT JOIN ck_payment p ON c.payment_id = p.payment_id LEFT JOIN ck_users u ON p.userid=u.userid LEFT JOIN ck_department d ON p.department_id=d.department_id LEFT JOIN ck_treatments t ON p.treatment_id=t.id WHERE".$date.$user.$treatment.$department."ORDER BY p.payment_id DESC";
	 		}
	    	if($this->input->post('operation')==2){
		    	$date=" e.expense_date >=". $this->db->escape($start_date)." AND e.expense_date < ". $this->db->escape($end_date)." ";
				$user=($this->input->post('user_id'))?" AND e.user_id=".$this->input->post('user_id')." " :"";
		 		$department = ($this->input->post('department_id'))?" AND e.department_id=".$this->input->post('department_id')." ":"";
		 		$exp_category = ($this->input->post('cat_id'))?" AND e.cat_id=".$this->input->post('cat_id')." ":"";
		 		$query_str="SELECT u.name, e.expense_date date, e.sum summ, e.goal,ek.title, d.department_name department FROM ck_expense e LEFT JOIN ck_users u ON e.user_id=u.userid LEFT JOIN ck_department d ON e.department_id=d.department_id LEFT JOIN ck_expense_categories ek ON e.cat_id=ek.id WHERE".$date.$user.$department.$exp_category."ORDER BY e.id DESC";
			}
		}
    	if($page==2){
		    $date="WHERE pay.pay_date >=". $this->db->escape($start_date)." AND pay.pay_date < ". $this->db->escape($end_date)." ";
		    $limit=$this->input->post('number_of_patients');
	 		$query_str="SELECT sum(pay.paid) summ, c.first_name, c.middle_name FROM ck_payment pay LEFT JOIN ck_patient pat ON pay.patient_id=pat.patient_id LEFT JOIN ck_contacts c ON pat.contact_id=c.contact_id ".$date."GROUP BY pay.patient_id ORDER BY summ DESC LIMIT ".$limit;
	 	}
		$res=$this->db->query($query_str);
		return $res->result_array();
	}


    public function event_log($table,$type,$vars){
    	$data['user_name']=$_SESSION['name'];
    	$data['event_table']=$table;
    	$data['type']=$type;
    	$vars=array_map(function($k,$v) { return "$k - $v"; }, array_keys($vars), $vars);
    	$data['vars']=implode(', ',$vars);
    	$data['query']=$this->db->last_query();
    	$error=$this->db->error();
    	$data['error']=$error['message'];
    	$this->db->insert('ck_event_log',$data);
    	//file_put_contents('t1.txt', print_r($this->db->last_query(),true));
    	//file_put_contents('t1.txt', print_r($this->db->error(),true));
    }


}
?>