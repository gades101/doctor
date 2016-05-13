<?php

class Event extends CI_Controller {

    function __construct() {
        parent::__construct();
		
		$this->load->model('menu_model');
		$this->load->model('event_model');
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
	/**Events*/
    public function index() {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
        if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
            $this->form_validation->set_rules('title', 'title', 'trim|required|xss_clean|is_unique[events.title]');
            //$this->form_validation->set_rules('event_price', 'Treatment Price', 'trim|required|xss_clean');
            //$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
            if ($this->form_validation->run() === FALSE) {
                file_put_contents('t1.txt',print_r('no',true));
                $data['events'] = $this->event_model->get_events();                
                $this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('events_list', $data);
                $this->load->view('templates/footer');
            } else {
                $this->event_model->add_event();
                //file_put_contents('t1.txt',print_r('add',true));
                $data['events'] = $this->event_model->get_events();
                $this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('events_list', $data);
                $this->load->view('templates/footer');
            }
        }
    }
	public function edit_event($id) {
        if ( $this->is_session_started() === FALSE ){
			session_start();
		}
        if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('event', 'title', 'trim|required|xss_clean');
            //$this->form_validation->set_rules('event_price', 'Treatment Price', 'trim|required|xss_clean');
			$data['currency_postfix'] = $this->settings_model->get_currency_postfix();
			if ($this->form_validation->run() === FALSE) {
				$data['event'] = $this->event_model->get_edit_event($id);
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('edit_event', $data);
				$this->load->view('templates/footer');
			} else {
				$event_id = $this->input->post('event_id');
                $this->event_model->edit_event($event_id);
				$data['events'] = $this->event_model->get_events();                
                $this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('events_list', $data);
                $this->load->view('templates/footer');
            }
        }
    }
	public function delete_event($id) {
        if ( $this->is_session_started() === FALSE ){
			session_start();
		}
        if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
            $this->event_model->delete_event($id);
            $this->index();
        }
    }

	public function events(){
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
        if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->load->view('templates/header');
			$this->load->view('templates/menu');
			$this->load->view('settings/events');
			$this->load->view('templates/footer');
		}
	}	
}	
?>