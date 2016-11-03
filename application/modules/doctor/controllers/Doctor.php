<?php
class Doctor extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('contact/contact_model');
        $this->load->model('doctor_model');
		$this->load->model('menu_model');
	    $this->load->model('settings/settings_model');
        $this->load->model('admin/admin_model');
        $this->load->model('appointment/appointment_model');
        $this->load->model('payment/payment_model');

        $this->load->helper('url');
        $this->load->helper('form');
		$this->load->helper('date');
        $this->load->helper('currency');
		$this->load->helper('my_string_helper');

        $this->load->library('form_validation');
        $this->load->database();
		$this->lang->load('main');
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
	/** List All Doctors */
    public function index() {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			if($_SESSION["category"] == 'Doctor'){
				$user_id = $_SESSION['id'];
				//echo $user_id;
				$data['doctors'] = $this->doctor_model->find_doctor($user_id);
			}else{
				$data['doctors'] = $this->doctor_model->find_doctor();
			}

			//print_r($data['doctors']);
			$data['departments'] = $this->doctor_model->get_all_departments();
            $this->load->view('templates/header');
            $this->load->view('templates/menu');
            $this->load->view('doctor/browse_doctor', $data);
            $this->load->view('templates/footer');
		}
    }
	/** File Upload for Doctor Profile Image */
	function do_upload($contact_id) {
        $config['upload_path'] = './profile_picture/';
		$config['allowed_types'] = 'jpg|png';
		$config['max_size'] = '512';
		$config['max_width'] = '1024';
		$config['max_height'] = '768';
		$config['overwrite'] = TRUE;
		$config['file_name'] = $contact_id;
		$this->load->library('upload', $config);
		$image='file_name';

		if (!$this->upload->do_upload($image)) {
			$error = array('error' => $this->upload->display_errors());
			return $error;
		} else {
			$data = array('upload_data' => $this->upload->data());
			return $data['upload_data'];
		}
    }
	public function view_doctor($doctor_id) {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$data['doctor_id'] = $doctor_id;
			$doctor_details = $this->doctor_model->get_doctor_details($doctor_id);
			$data['doctor_details'] = $doctor_details;
			$contact_id = $doctor_details['contact_id'];
			$data['contacts'] = $this->contact_model->get_contacts($contact_id);
			$data['departments'] = $this->doctor_model->get_all_departments();
			$this->load->view('templates/header');
			$this->load->view('templates/menu');
			$this->load->view('view', $data);
			$this->load->view('templates/footer');
		}
    }
	public function doctor_detail($doctor_id = NULL) {
        if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			if($doctor_id == NULL || $doctor_id == 0){
				$this->form_validation->set_rules('first_name', 'Прізвище', 'required');
				$this->form_validation->set_rules('middle_name', 'Ім\'я', 'required');
				//$this->form_validation->set_rules('email', 'Email', 'valid_email');
				if ($this->form_validation->run() === FALSE) {
					//Insert New Doctor
					$data['doctor_id'] = 0;
					$data['departments'] = $this->doctor_model->get_all_departments();
					$data['file_error'] = "";
					$this->load->view('templates/header');
					$this->load->view('templates/menu');
					$this->load->view('form',$data);
					$this->load->view('templates/footer');
				}else{
					$contact_id = $this->contact_model->insert_contact();
					$doctor_id = $this->doctor_model->insert_doctors($contact_id);
					$this->index();
				}

			}else{
				//Edit Doctor
				$this->form_validation->set_rules('first_name', 'Прізвище', 'required');
				$this->form_validation->set_rules('middle_name', 'Ім\'я', 'required');
				//$this->form_validation->set_rules('email', 'Email', 'valid_email');
				if ($this->form_validation->run() === FALSE) {
					$data['doctor_id'] = $doctor_id;
					$doctor_details = $this->doctor_model->get_doctor_details($doctor_id);
					$data['doctor_details'] = $doctor_details;
					$contact_id = $doctor_details['contact_id'];
					$data['contacts'] = $this->contact_model->get_contacts($contact_id);
					$data['departments'] = $this->doctor_model->get_all_departments();
					$data['file_error'] = "";
					$this->load->view('templates/header');
					$this->load->view('templates/menu');
					$this->load->view('form', $data);
					$this->load->view('templates/footer');
				}else{
					$contact_id = $this->input->post('contact_id');
					$file_upload = $this->do_upload($contact_id);
					if(isset($file_upload['file_name'])){
						$file_name = $file_upload['file_name'];
					}else{
						$file_name = "";
						}
					$this->contact_model->update_contact($file_name);
					$this->contact_model->update_address();
					$this->doctor_model->update_doctors();
					$this->index();
				}
			}
		}
    }
	public function delete_doctor($doctor_id) {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->doctor_model->delete_doctor($doctor_id);
			$this->index();
		}
	}
	function copy_from_users(){
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->doctor_model->copy_from_users();
			$this->index();
		}
	}
	/*Department ---------------------------------------------------------------------------------------*/
	public function department(){
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$data['departments'] = $this->doctor_model->get_all_departments();
			$this->load->view('templates/header');
			$this->load->view('templates/menu');
			$this->load->view('doctor/department', $data);
			$this->load->view('templates/footer');
		}
	}
	public function add_department() {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('department_name', 'Department Name', 'required');
			if ($this->form_validation->run() === FALSE) {
				 $this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('add_department');
				$this->load->view('templates/footer');
			}
			else
			{
				$this->doctor_model->add_department();
				$this->department();
			}
		}
	}
	public function edit_department($department_id = NULL) {
        if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
            $this->form_validation->set_rules('department_name', 'Department Name', 'required');

            if ($this->form_validation->run() === FALSE) {
                $data['departments'] = $this->doctor_model->get_department($department_id);
                $this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('doctor/edit_department', $data);
                $this->load->view('templates/footer');
            } else {
                $this->doctor_model->update_department();
                $data['departments'] = $this->doctor_model->get_all_departments();
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('doctor/department', $data);
				$this->load->view('templates/footer');
            }
        }
    }
	public function delete_department($id) {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->doctor_model->delete_department($id);
			$this->department();
		}
	}

	/*doctor schedule -----------------------------------------------------------------------------------*/
	public function doctor_schedule($doctor_id = NULL){
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('doctor', 'Doctor', 'required');
			$this->form_validation->set_rules('day[]', 'Day', 'required');
			$this->form_validation->set_rules('from_time', 'From Time', 'required');
			$this->form_validation->set_rules('to_time', 'To Time', 'required');
			if ($this->form_validation->run() === FALSE) {

			}else{
				$this->doctor_model->add_drschedule();
			}
			$data['doctors'] = $this->doctor_model->find_doctor();

			$data['doctor_details']=$this->doctor_model->get_doctor_details($doctor_id);
			$data['drschedules'] = $this->doctor_model->find_drschedule();
			$data['def_timeformate']=$this->settings_model->get_time_formate();
			$this->load->view('templates/header');
			$this->load->view('templates/menu');
			$this->load->view('doctor/doctor_schedule', $data);
			$this->load->view('templates/footer');
		}
	}
	public function edit_drschedule($schedule_id){
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('doctor_id', 'Doctor', 'required');
			//$this->form_validation->set_rules('day', 'Day', 'required');
			//$this->form_validation->set_rules('from_time', 'From Time', 'required');
			//$this->form_validation->set_rules('to_time', 'To Time', 'required');
			if ($this->form_validation->run() === FALSE) {
				$data['doctors'] = $this->doctor_model->find_doctor();
				$data['schedule'] = $this->doctor_model->get_schedule_from_id($schedule_id);
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('doctor/edit_schedule', $data);
				$this->load->view('templates/footer');
			}else{
				$this->doctor_model->edit_drschedule();
				$data['doctors'] = $this->doctor_model->find_doctor();
				$data['drschedules'] = $this->doctor_model->find_drschedule();
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('doctor/doctor_schedule', $data);
				$this->load->view('templates/footer');
			}
		}
	}
	public function delete_drschedule($id) {
		$this->doctor_model->delete_drschedule($id);
		$this->doctor_schedule();
	}
	public function inavailability() {
		// Check If user has logged in or not
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        }
		else
		{
			//Change the timezone
			$timezone = $this->settings_model->get_time_zone();
			if (function_exists('date_default_timezone_set'))
				date_default_timezone_set($timezone);


			$this->form_validation->set_rules('start_date', 'Start Date', 'required');
			$this->form_validation->set_rules('start_time', 'Start Time', 'required');
			$this->form_validation->set_rules('end_date', 'End Date', 'required');
            $this->form_validation->set_rules('end_time', 'End Time', 'required');
			$this->form_validation->set_rules('doctor', 'Doctor Name', 'required');

			$level = $_SESSION['category'];
			if ($level == 'Doctor'){
				$id = $_SESSION['id'];
				$data['doctors']=$this->admin_model->get_doctor($id);
			}
			else{
				$data['doctors'] = $this->admin_model->get_doctor();
			}

			$data['clinic_start_time'] = $this->settings_model->get_clinic_start_time();
            $data['clinic_end_time'] = $this->settings_model->get_clinic_end_time();
			$data['time_interval'] = $this->settings_model->get_time_interval();
			$data['def_dateformate']=$this->settings_model->get_date_formate();
			$data['def_timeformate']=$this->settings_model->get_time_formate();
			if ($this->form_validation->run() === FALSE)
			{
				/**Do Nothing*/
            }
			else
			{
				$this->doctor_model->insert_availability();
            }
			$data['availability'] = $this->appointment_model->get_dr_inavailability();
			$this->load->view('templates/header');
			$this->load->view('templates/menu');
			$this->load->view('inavailability',$data);
			$this->load->view('templates/footer');
		}
	}
	public function edit_inavailability($appointment_id=NULL, $user_id=NULL,$end_date=NULL) {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index/');
        }
		else
		{
			$level = $_SESSION['category'];
			if ($level == 'Doctor'){
				$id = $_SESSION['id'];
				$data['doctors']=$this->admin_model->get_doctor($id);
			}else{
				$data['doctors'] = $this->admin_model->get_doctor();
			}
			$data['clinic_start_time'] = $this->settings_model->get_clinic_start_time();
            $data['clinic_end_time'] = $this->settings_model->get_clinic_end_time();
			$data['time_interval'] = $this->settings_model->get_time_interval();
			$data['def_dateformate']=$this->settings_model->get_date_formate();
			$data['def_timeformate']=$this->settings_model->get_time_formate();
            $this->form_validation->set_rules('start_time', 'Start Time', 'required');
            $this->form_validation->set_rules('end_time', 'End Time', 'required');
			$this->form_validation->set_rules('doctor', 'Doctor Name', 'required');

            if ($this->form_validation->run() === FALSE){

				if($user_id==0){
					$user_id=NULL;
				}
                $data['availability'] = $this->doctor_model->get_dr_inavailability($appointment_id, $user_id);

				$this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('edit_inavailability', $data);
                $this->load->view('templates/footer');
            }
			else
			{
                $this->doctor_model->insert_availability($appointment_id, $user_id,$end_date);
                redirect('doctor/inavailability/');
            }
        }
    }
	function delete_availability($appointment_id) {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index/');
        }else {
			$this->appointment_model->delete_availability($appointment_id);
			redirect('doctor/inavailability/');
		}
	}

	function message($do='browse'){
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            return false;
        }
		else
		{
			$id = $_SESSION['id'];
            if ($do=='browse'){
                $data['users'] = $this->admin_model->get_work_users();
				$this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('message', $data);
                $this->load->view('templates/footer');
            }
			elseif($do=='add'){
                $users = $this->admin_model->get_work_users();
                echo json_encode($users);
            }
        }

	}
}
?>