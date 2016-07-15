<?php

class login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');

		$this->load->helper('form');
		$this->load->helper('url');
        $this->load->helper('security');

		$this->load->model('login_model');
		$this->load->model('module/module_model');

		$this->lang->load('main');
    }

    function index() {
		//If Not Logged In, Go to Login Form
        if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
			$this->load->view('login/login_signup');
		} else {
			//Go to Appointment Page if logged in
			redirect('/appointment/index/all', 'refresh');
        }
    }

    function valid_signin() {
		//Check if loggin details entered
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
			$logged_in = FALSE;
            if($this->input->post('username')){
				//Check Login details
				$username = $this->input->post('username');
				$password = base64_encode($this->input->post('password'));
				$result = $this->login_model->login($username, $password);
				if(!empty($result)){
					session_start();
					$_SESSION["name"] = $result->name;
					$_SESSION["user_name"] = $result->username;
					$_SESSION["category"] = $result->level;
					$_SESSION["id"] = $result->userid;
					$_SESSION["logged_in"] = TRUE;
					$_SESSION["dep"] = "all";
					$logged_in = TRUE;
				}
			}
			//If Username and Password matches
			if ($logged_in) {
				redirect('/appointment/index/all', 'refresh');
			} else {
				$data['username'] = $this->input->post('username');
				$data['level'] = $this->input->post('level');
				$data['error'] = 'Невірний логін і/або пароль';
				$this->load->view('login/login_signup',$data);
			}
        }
    }

    public function logout() {
		//Destroy Session and go to login form
        if ( $this->is_session_started() === TRUE ){
			// remove all session variables
			session_unset();
			// destroy the session
			session_destroy();
		}
        $this->index();
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
	public function cleardata() {
		if ( $this->is_session_started() === TRUE ){
			// remove all session variables
			session_unset();
			// destroy the session
			session_destroy();
		}
		$data['message']='Використовуйте Логін / Пароль : admin/admin для входу ';
		$this->load->view('login/login_signup',$data);
    }

}

?>