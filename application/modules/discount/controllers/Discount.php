<?php

class Discount extends CI_Controller {

    function __construct() {
        parent::__construct();
		
		$this->load->model('menu_model');
		$this->load->model('discount/discount_model');
		$this->load->model('settings/settings_model');
		
		$this->lang->load('main');
		
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('security');
		$this->load->helper('currency_helper');
		
		$this->load->library('form_validation');
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
        if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
				$this->form_validation->set_rules('amount', 'Сума', 'trim|required|xss_clean');
            //$this->form_validation->set_rules('treatment_price', 'Treatment Price', 'trim|required|xss_clean');
            //$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
            if ($this->form_validation->run() === FALSE) {
                $data['discounts'] = $this->discount_model->get_discounts();                
                $this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('browse', $data);
                $this->load->view('templates/footer');
            } else {

                $this->discount_model->add_discount();
                $data['discounts'] = $this->discount_model->get_discounts();                
                $this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('browse', $data);
                $this->load->view('templates/footer');
            }
        }
    }
	public function edit_treatment($id) {
        if ( $this->is_session_started() === FALSE ){
			session_start();
		}
        if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('treatment', 'Treatment Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('treatment_price', 'Treatment Price', 'trim|required|xss_clean');
			$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
			if ($this->form_validation->run() === FALSE) {
				$data['treatment'] = $this->treatment_model->get_edit_treatment($id);
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('edit_treatment', $data);
				$this->load->view('templates/footer');
			} else {
				$treatment_id = $this->input->post('treatment_id');
                $this->treatment_model->edit_treatment($treatment_id);
				$data['treatments'] = $this->treatment_model->get_treatments();                
                $this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('treatments_list', $data);
                $this->load->view('templates/footer');
            }
        }
    }
	public function delete_discount($amount,$percent) {
        if ( $this->is_session_started() === FALSE ){
			session_start();
		}
        if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
            $this->discount_model->delete_discount($amount,$percent);
            $this->index();
        }
    }
}

?>