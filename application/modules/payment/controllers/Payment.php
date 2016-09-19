<?php

class Payment extends CI_Controller {

    function __construct() {
        parent::__construct();
		$this->load->model('payment_model');
		$this->load->model('patient/patient_model');
		$this->load->model('admin/admin_model');
		$this->load->model('settings/settings_model');
		$this->load->model('treatment/treatment_model');
		$this->load->model('discount/discount_model');	
        $this->load->model('doctor/doctor_model');			
		$this->load->model('menu_model');

		$this->load->helper('form');
		$this->load->helper('currency');
		$this->load->helper('url');

		$this->load->library('form_validation');
		$this->lang->load('main');
        $prefs = array(
            'show_next_prev' => TRUE,
			'next_prev_url' => base_url() . 'index.php/payment',
        );
        $this->load->library('calendar', $prefs);
    }
	public function is_session_started(){
		if ( php_sapi_name() !== 'cli' ) {
			if ( version_compare(phpversion(), '5.4.0', '>=') ) {
				return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
			} else {
				return session_id() === '' ? FALSE : TRUE;
			}
		}
		return FALSE;
	}
    public function index() {

		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
			redirect('login/index');
        } else {
			$data['payments'] = $this->payment_model->list_payments();
			$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
			$this->load->view('templates/header');
			$this->load->view('templates/menu');
			$this->load->view('browse',$data);
			$this->load->view('templates/footer');
        }
    }
	public function insert($curr_patient_id=NULL,$called_from = 'bill') {
        session_start();
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('patient_id', 'Patient', 'required');
            //$this->form_validation->set_rules('bill_id', 'Bill Id', 'required');

			if ($this->form_validation->run() === FALSE) {
				$data['treatments'] = $this->treatment_model->get_treatments();
				if ($curr_patient_id) 	$data['curr_patient'] = $this->patient_model->get_patient_detail($curr_patient_id);
				else $data['patients'] = $this->patient_model->get_patient();
				$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
				$data['users'] = $this->admin_model->get_work_users();
				$data['departments'] = $this->doctor_model->get_all_departments();
				$data['discounts'] = $this->discount_model->get_discounts();
				$data['selected_doctor_id'] = NULL;
				$data['def_dateformate'] = $this->settings_model->get_date_formate();
				file_put_contents('t1.txt', print_r($data,true));
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('form',$data);
				$this->load->view('templates/footer');
			}else{
				$this->payment_model->insert_payment();
				$this->index();
			}
        }
    }

	public function edit($payment_id){
		session_start();
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('patient_id', 'Patient', 'required');
			$this->form_validation->set_rules('pay_amount', 'Payment Amount', 'required');
			$this->form_validation->set_rules('pay_date', 'Payment Date', 'required');
			$this->form_validation->set_rules('pay_mode', 'Payment Mode', 'required');

			if ($this->form_validation->run() === FALSE) {
				$data['patients'] = $this->patient_model->get_patient();
				$data['treatments'] = $this->treatment_model->get_treatments();		
				$data['discounts'] = $this->discount_model->get_discounts();				
				$payment = $this->payment_model->get_payment($payment_id);
				$data['payment'] = $payment;
				$data['payment_id'] = $payment->payment_id;
				$data['patient_id'] = $payment->patient_id;
				$data['patient'] = $this->patient_model->get_patient_detail($data['patient_id']);
				$data['def_dateformate'] = $this->settings_model->get_date_formate();
				//25-12-15
				$data['called_from'] = "";
				$data['curr_treatment']=$this->treatment_model->get_edit_treatment($payment->treatment_id);
				$data['users'] = $this->admin_model->get_work_users();
				$data['departments'] = $this->doctor_model->get_all_departments();
				$data['selected_doctor_id']=$payment->userid;			
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('form',$data);
				$this->load->view('templates/footer');
			}else{
				$this->payment_model->edit_payment($payment_id);
				$this->index();
			}

		}
	}
    public function del($payment_id) {
        session_start();
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index/');
        } else {
            $this->payment_model->delete_payment($payment_id);
            $this->index();
        }
    }
    public function expense() {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
			redirect('login/index');
        } else {
            $this->form_validation->set_rules('expense_date', 'expense_date', 'trim|required');
 	  		$data['def_dateformate'] = $this->settings_model->get_date_formate();
            if ($this->form_validation->run() === FALSE) {
				$data['users'] = $this->admin_model->get_work_users();
				$data['expense_categories'] = $this->payment_model->list_expense_cat();
				$data['expenses'] = $this->payment_model->list_expenses();
				$data['departments'] = $this->doctor_model->get_all_departments();
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('exp_browse',$data);
				$this->load->view('templates/footer');
			} else {
				$this->payment_model->insert_expense();
				$data['expenses'] = $this->payment_model->list_expenses();
				redirect('payment/expense');
			}
        }
    }
	public function edit_expense($id) {
        if ( $this->is_session_started() === FALSE ){
			session_start();
		}
        if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('cat_id', 'Категорія', 'trim|required');
 	  		$data['def_dateformate'] = $this->settings_model->get_date_formate();
			if ($this->form_validation->run() === FALSE) {
				$data['users'] = $this->admin_model->get_work_users();
				$data['departments'] = $this->doctor_model->get_all_departments();
				$data['expense_categories'] = $this->payment_model->list_expense_cat();
				$data['edit_expense'] = $this->payment_model->get_edit_expense($id);
				//file_put_contents('t1.txt',print_r($data,true));
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('edit_expense', $data);
				$this->load->view('templates/footer');
			} else {
                $this->payment_model->edit_expense($id);
				$data['users'] = $this->admin_model->get_work_users();
				$data['departments'] = $this->doctor_model->get_all_departments();
				$data['expense_categories'] = $this->payment_model->list_expense_cat();
				$data['expenses'] = $this->payment_model->list_expenses();             
                $this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('exp_browse', $data);
                $this->load->view('templates/footer');
            }
        }
    }
    public function delete_expense($id) {
        session_start();
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index/');
        } else {
            $this->payment_model->delete_expense($id);
            $this->expense();
        }
    }
    public function expense_categories() {

		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
			redirect('login/index');
        } else {
            $this->form_validation->set_rules('title', 'Категорія', 'trim|required');
			$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
            if ($this->form_validation->run() === FALSE) {
				$data['expense_categories'] = $this->payment_model->list_expense_cat();
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('exp_categories',$data);
				$this->load->view('templates/footer');
			} else {
				$this->payment_model->insert_expense_cat();
				redirect('payment/expense_categories');
			}
        }
    }
	public function edit_expense_cat($id) {
        if ( $this->is_session_started() === FALSE ){
			session_start();
		}
        if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('title', 'Назва Категорії', 'trim|required');
			if ($this->form_validation->run() === FALSE) {
				$data['exp_category'] = $this->payment_model->get_edit_exp_category($id);
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('edit_category', $data);
				$this->load->view('templates/footer');
			} else {
                $this->payment_model->edit_exp_category($id);
				$data['expense_categories'] = $this->payment_model->list_expense_cat();                
                $this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('exp_categories', $data);
                $this->load->view('templates/footer');
            }
        }
    }	
	
	public function payment_ajax_info($patient_id){
		$data=$this->payment_model->get_curr_payments($patient_id);
		echo json_encode($data);
		//else return json_encode($data);
	}

	public function close_payment($payment_id){
        session_start();
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index/');
        } else {
            $this->payment_model->close_payment($payment_id);
            $this->index();
        }		
	}

    public function payment_report($year=NULL,$month=NULL,$day=NULL) {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
			redirect('login/index');
        } else {
			$this->form_validation->set_rules('start_date', 'Від ', 'required');
			$this->form_validation->set_rules('end_date', 'До', 'required');
			if ($this->form_validation->run() === FALSE) {
 	  			$data['def_dateformate'] = $this->settings_model->get_date_formate();
				$data['departments'] = $this->doctor_model->get_all_departments();
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('pay_report',$data);
				$this->load->view('templates/footer');
			} else {
 	  			$data['def_dateformate'] = $this->settings_model->get_date_formate();
                $data['report'] = $this->payment_model->create_report();
                $data['start_date'] = $this->input->post('start_date');
                $data['end_date'] = $this->input->post('end_date');
                $this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('pay_report', $data);
                $this->load->view('templates/footer');
            }
        }
    }

    public function dir_payment_report($year=NULL,$month=NULL,$day=NULL) {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
			redirect('login/index');
        } else {
			$this->form_validation->set_rules('start_date', 'Від ', 'required');
			$this->form_validation->set_rules('end_date', 'До', 'required');
			if ($this->form_validation->run() === FALSE) {
				$data['users'] = $this->admin_model->get_work_users();
				$data['treatments'] = $this->treatment_model->get_treatments();
 	  			$data['def_dateformate'] = $this->settings_model->get_date_formate();
				$data['departments'] = $this->doctor_model->get_all_departments();
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('dir_pay_report',$data);
				$this->load->view('templates/footer');
			} else {
				$data['users'] = $this->admin_model->get_work_users();
				$data['treatments'] = $this->treatment_model->get_treatments();
 	  			$data['def_dateformate'] = $this->settings_model->get_date_formate();
                $data['report'] = $this->payment_model->create_dir_report();
                $data['start_date'] = $this->input->post('start_date');
                $data['end_date'] = $this->input->post('end_date');
   				file_put_contents('t1.txt', print_r($data,true));

                $this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('pay_report', $data);
                $this->load->view('templates/footer');
            }
        }
    }
}
?>