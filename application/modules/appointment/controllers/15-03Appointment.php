<?php

class Appointment extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('admin/admin_model');
		$this->load->model('contact/contact_model');
        $this->load->model('patient/patient_model');
		$this->load->model('payment/payment_model');
        $this->load->model('settings/settings_model');
		$this->load->model('module/module_model');
		$this->load->model('menu_model');
		$this->load->model('treatment/treatment_model');

        $this->load->helper('url');
        $this->load->helper('form');
		$this->load->helper('currency_helper');
		$this->load->helper('directory' );
		$this->load->helper('inflector');
		//$this->load->helper('my_string_helper');

		$this->lang->load('main');
        $this->load->library('form_validation');
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		$dep=$_SESSION['dep'];
        $prefs = array(
            'show_next_prev' => TRUE,
			'next_prev_url' => base_url() . 'index.php/appointment/index/'.$dep,
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
	public function index($dep= "all", $year = NULL, $month = NULL, $day = NULL) {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		// Check If user has logged in or not
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$timezone = $this->settings_model->get_time_zone();
			if (function_exists('date_default_timezone_set'))
				date_default_timezone_set($timezone);

			//Default to today's date if date is not mentioned
            if ($year == NULL) { $year = date("Y"); }
            if ($month == NULL) { $month = date("m"); }
            if ($day == NULL) { $day = date("d");}

            $data['year'] = $year;
            $data['month'] = $month;
            $data['day'] = $day;
			$data['dep'] = $dep;

			$_SESSION['dep'] = $dep;

			//Fetch Time Interval from settings
            $data['time_interval'] = $this->settings_model->get_time_interval();
			$data['time_format'] = 'H:i';//$this->settings_model->get_time_formate();

			//Generate display date in YYYY-MM-DD formate
            $appointment_date = date("Y-n-d", gmmktime(0, 0, 0, $month, $day, $year));
			$data['appointment_date']= $appointment_date;

			//Fetch Clinic Start Time and Clinic End Time
            $data['start_time'] = $this->settings_model->get_clinic_start_time();
            $data['end_time'] = $this->settings_model->get_clinic_end_time();

			//Fetch Task Details
            $data['todos'] = $this->appointment_model->get_todos();

			//Display Followups for next 8 days
			$followup_date = date('Y-m-d', strtotime("+8 days"));
			$data['followups'] = $this->appointment_model->get_followup($followup_date);

			//Fetch all patient details
			$data['patients'] = $this->patient_model->get_patient();
			//Fetch Doctor Schedules
			$doctor="doctor";
			$doctor_active=$this->module_model->is_active($doctor);
			$data['doctor_active']=$doctor_active;

			if($doctor_active){
				$this->load->model('doctor/doctor_model');
				$data['doctors_data'] = $this->doctor_model->find_doctor();
				$data['drschedules'] = $this->doctor_model->find_drschedule();
				$data['inavailability'] = $this->appointment_model->get_dr_inavailability();
			}

			//Fetch Level of Current User
            $level = $_SESSION["category"];

			//For Doctor's login
            if ($level == 'Doctor') {
				//Fetch this doctor's appointments for the date
                $doctor_id = $_SESSION['id'];
				$data['appointments'] = $this->appointment_model->get_appointments($appointment_date,$dep,$doctor_id);

            } else {
				//Fetch details of all Doctors
				$data['doctors'] = $this->admin_model->get_doctor2($dep);

				//Fetch appointments for the date
                $data['appointments'] = $this->appointment_model->get_appointments($appointment_date,$dep);

            }
			//Load the view
			$this->load->view('templates/header');
			$this->load->view('templates/menu');
			$this->load->view('browse', $data);
			$this->load->view('templates/footer');
        }
    }
	/** Add Appointment */
	public function add($year = NULL, $month = NULL, $day = NULL, $hour = NULL, $min = NULL,$status = NULL,$patient_id=NULL,$doctor_id=NULL) {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		//Check if user has logged in
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$timezone = $this->settings_model->get_time_zone();
			if (function_exists('date_default_timezone_set'))
				date_default_timezone_set($timezone);

			$level = $_SESSION['category'];

            if ($year == NULL) { $year = date("Y");}
            if ($month == NULL) { $month = date("m");}
            if ($day == NULL) { $day = date("d");}

			if ($hour == NULL) { $hour = date("H");}
            if ($min == NULL) { $min = date("i");}
         $dep=$_SESSION['dep'];
			$data['year'] = $year;
			$data['month'] = $month;
			$data['day'] = $day;
			$data['dep'] = $dep;

            $today = date('Y-m-d');

			$data['hour'] = $hour;
			$data['min'] = $min;
			$time = $hour . ":" . $min;

            $appointment_dt = date("Y-m-d", gmmktime(0, 0, 0, $month, $day, $year));

            $data['appointment_date'] = $appointment_dt;
			$data['appointment_time'] = $time;
			$data['appointment_id']=0;
			if($status == NULL){
				$data['app_status'] = 'Appointments';
			}else{
				$data['app_status']=$status;
			}

			//Form Validation Rules
			$this->form_validation->set_rules('patient_id', 'Patient', 'required');
			$this->form_validation->set_rules('doctor_id', 'Doctor Name', 'required');
			$this->form_validation->set_rules('start_time', 'Start Time', 'required');
			$this->form_validation->set_rules('end_time', 'End Time', 'required');
			$this->form_validation->set_rules('appointment_date', 'Date', 'required');

			if ($this->form_validation->run() === FALSE){
				$data['clinic_start_time'] = $this->settings_model->get_clinic_start_time();
				$data['clinic_end_time'] = $this->settings_model->get_clinic_end_time();
				$data['time_interval'] = $this->settings_model->get_time_interval();
				$data['patients'] = $this->patient_model->get_patient();
				$data['treatments']=$this->treatment_model->get_treatments();
				$data['def_dateformate'] = $this->settings_model->get_date_formate();
				$data['def_timeformate'] = $this->settings_model->get_time_formate();
				if ($patient_id) {
					$data['curr_patient'] = $this->patient_model->get_patient_detail($patient_id);
				}
				if ($level == 'Doctor'){
					$doctor_id = $_SESSION['id'];
					$data['doctor']=$this->admin_model->get_doctor($doctor_id);
					$data['selected_doctor_id'] = $doctor_id;
					$data['doctors'][0]=$data['doctor'];
				}else{
					$data['doctors'] = $this->admin_model->get_doctor();
				}
				$data['selected_doctor_id'] = $doctor_id;
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('form', $data);
				$this->load->view('templates/footer');
			}else{
				$this->appointment_model->add_appointment($this->input->post('status'));
				$year = date("Y", strtotime($this->input->post('appointment_date')));
				$month = date("m", strtotime($this->input->post('appointment_date')));
				$day = date("d", strtotime($this->input->post('appointment_date')));
				redirect('appointment/index/'.$dep.'/'.$year.'/'.$month.'/'.$day);
				//$this->index($dep,$year,$month,$day);

			}
        }
    }

	function edit_appointment($appointment_id) {
		//Check if user has logged in
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
			$this->form_validation->set_rules('patient_id', 'Patient Name', 'required');
			$this->form_validation->set_rules('doctor_id', 'Doctor Name', 'required');
			$this->form_validation->set_rules('start_time', 'Start Time', 'required');
			$this->form_validation->set_rules('end_time', 'End Time', 'required');
			$this->form_validation->set_rules('appointment_date', 'Date', 'required');
			$dep=$_SESSION['dep'];
			if ($this->form_validation->run() === FALSE){
				$appointment = $this->appointment_model->get_appointments_id($appointment_id);
				//$appointment['appointment_details']=htmlspecialchars($appointment['appointment_details']);
				$data['appointment']=$appointment;
				$data['treatments']=$this->treatment_model->get_treatments();
				$data['curr_treatment']=$this->treatment_model->get_edit_treatment($appointment['treatment_id']);
				$patient_id = $appointment['patient_id'];
				$data['curr_patient']=$this->patient_model->get_patient_detail($patient_id);
				$data['patients']=$this->patient_model->get_patient();
				$doctor_id = $appointment['userid'];
				$data['doctors'] = $this->admin_model->get_doctor();
				$data['selected_doctor_id'] = $doctor_id;
				$data['def_dateformate'] = $this->settings_model->get_date_formate();
				$data['def_timeformate'] = $this->settings_model->get_time_formate();
				$data['time_interval'] = $this->settings_model->get_time_interval();
				$data['dep']=$dep;


				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('form', $data);
				$this->load->view('templates/footer');
			}else{
				$patient_id = $this->input->post('patient_id');
				$curr_patient = $this->patient_model->get_patient_detail($patient_id);
				$title = $curr_patient['first_name']." " .$curr_patient['middle_name'].$curr_patient['last_name'];
				$this->appointment_model->update_appointment($title);
				$year = date('Y', strtotime($this->input->post('appointment_date')));
				$month = date('m', strtotime($this->input->post('appointment_date')));
				$day = date('d', strtotime($this->input->post('appointment_date')));
				redirect('appointment/index/'.$dep.'/'.$year.'/'.$month.'/'.$day);
				//$this->index($_SESSION['dep'], $year, $month, $day);
			}
		}
	}

	function del($appointment_id) {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
        if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        }else{
			$appointment = $this->appointment_model->get_appointment_from_id($appointment_id);
			$appointment_date = $appointment['appointment_date'];
			$year = date("Y", strtotime($appointment_date));
         $month = date("m", strtotime($appointment_date));
         $day = date("d", strtotime($appointment_date));
			$this->appointment_model->delete_appointment($appointment_id);

         $dep=$_SESSION['dep'];
			redirect('appointment/index/'.$dep.'/'.$year.'/'. $month.'/'.$day);
        }
    }

	public function insert_patient_add_appointment($hour = NULL, $min =NULL, $appointment_date = NULL, $status = NULL, $doc_id = NULL,$pid=NULL,$appid=NULL) {
        if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index/');
        }
		else
		{
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');

            if ($this->form_validation->run() === FALSE) {
				$this->load->view('templates/header');
				$this->load->view('templates/menu');
				$this->load->view('form', $data);
				$this->load->view('templates/footer');
            }
			else
			{
				$contact_id = $this->contact_model->insert_contact();
                $patient_id = $this->patient_model->insert_patient($contact_id);
				$appointment_date = date('Y-m-d',strtotime($appointment_date));
				list($year, $month, $day) = explode('-', $appointment_date);
				redirect('appointment/add/' . $year . '/' . $month . '/' . $day . '/' . $hour . "/" . $min . '/Appointments/' . $patient_id . "/" . $doc_id );
            }
        }
    }

	function change_status($appointment_id = NULL,$new_status = NULL) {

		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
        if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        }else{
				$this->appointment_model->change_status($appointment_id,$new_status);
				$appointment = $this->appointment_model->get_appointment_from_id($appointment_id);
				$appointment_date = $appointment['appointment_date'];
				$year = date("Y", strtotime($appointment_date));
					$month = date("m", strtotime($appointment_date));
					$day = date("d", strtotime($appointment_date));
				redirect('appointment/index/'.$_SESSION['dep'].'/'.$year.'/'.$month.'/'.$day);
			$this->appointment_model->change_status($appointment_id,$new_status);
			$appointment = $this->appointment_model->get_appointment_from_id($appointment_id);
			$appointment_date = $appointment['appointment_date'];
			$year = date("Y", strtotime($appointment_date));
            $month = date("m", strtotime($appointment_date));
            $day = date("d", strtotime($appointment_date));
			redirect('appointment/index/'.$_SESSION['dep'].'/'.$year.'/'.$month.'/'.$day);

        }
    }

	function change_status_visit($visit_id = NULL){
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
            $this->appointment_model->change_status_visit($visit_id);
            $this->index();
        }
	}

	function view_appointment($appointment_id) {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
		if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
			redirect('login/index');
        } else {
			$appointment = $this->appointment_model->get_appointments_id($appointment_id);
			$data['appointment']=$appointment;
			$patient_id = $appointment['patient_id'];
			$data['patient']=$this->patient_model->get_patient_detail($patient_id);
			$doctor_id = $appointment['userid'];
			$data['doctor'] = $this->admin_model->get_doctor($doctor_id);
			$visit_id = $appointment['visit_id'];
			$data['visit'] = $this->appointment_model->get_visit_from_id($visit_id);
			$data['def_dateformate'] = $this->settings_model->get_date_formate();
			$data['def_timeformate'] = $this->settings_model->get_time_formate();
			$data['bill'] = $this->patient_model->get_bill($visit_id);
			$data['bill_details'] = $this->patient_model->get_bill_detail($visit_id);
			$data['particular_total'] = $this->patient_model->get_particular_total($visit_id);
			$active_modules=$this->module_model->get_active_modules();
			if (in_array("doctor", $active_modules)) {
				$data['fees_total'] = $this->patient_model->get_fee_total($visit_id);
			}
			if (in_array("treatment", $active_modules)) {
				$data['treatment_total'] = $this->patient_model->get_treatment_total($visit_id);
			}
			$bill_id = $this->patient_model->get_bill_id($visit_id);
			$data['paid_amount'] = $this->payment_model->get_paid_amount($bill_id);
			$data['discount'] = $this->patient_model->get_discount_amount($bill_id);
			$this->load->view('templates/header');
			$this->load->view('templates/menu');
			$this->load->view('view_appointment', $data);
			$this->load->view('templates/footer');
		}
	}

	function appointment_report() {
		if ( $this->is_session_started() === FALSE ){
			session_start();
		}
        if (!isset($_SESSION["user_name"]) || $_SESSION["user_name"] == '') {
            redirect('login/index');
        } else {
            $data['doctors'] = $this->admin_model->get_doctor();
            $level = $_SESSION["category"];
            $data['currency_postfix'] = $this->settings_model->get_currency_postfix();
			$data['def_dateformate'] = $this->settings_model->get_date_formate();
            if ($level == 'Doctor')
			{
                $this->form_validation->set_rules('app_date', 'Appointment Date', 'required');
                if ($this->form_validation->run() === FALSE) {
					$timezone = $this->settings_model->get_time_zone();
					if (function_exists('date_default_timezone_set'))
						date_default_timezone_set($timezone);
					$app_date = date('Y-m-d');
                    $user_id = $_SESSION['id'];
					$data['app_date'] = $app_date;

                    $data['app_reports'] = $this->appointment_model->get_report($app_date, $user_id);
                } else {
                    $app_date = date('Y-m-d', strtotime($this->input->post('app_date')));
                    $user_id = $_SESSION['id'];
					$data['app_date'] = $app_date;
                    $data['app_reports'] = $this->appointment_model->get_report($app_date, $user_id);
                }
				//var_dump($data);
                $this->load->view('templates/header');
                $this->load->view('templates/menu');
                $this->load->view('appointment/report', $data);
                $this->load->view('templates/footer');
            }
			else
			{
                $this->form_validation->set_rules('app_date', 'Appointment Date', 'required');
                $this->form_validation->set_rules('doctor', 'Doctor Name', 'required');
                if ($this->form_validation->run() === FALSE) {
                    $timezone = $this->settings_model->get_time_zone();
					if (function_exists('date_default_timezone_set'))
						date_default_timezone_set($timezone);

					$app_date = date('Y-m-d');
                    $user_id = $_SESSION['id'];

					$data['app_date'] = $app_date;
					$data['doctor_id'] = $user_id;
                    $data['app_reports'] = $this->appointment_model->get_report($app_date, NULL);
                    if ($level == 'Administrator') {
                        $this->load->view('templates/header');
                        $this->load->view('templates/menu');
                        $this->load->view('appointment/report', $data);
                        $this->load->view('templates/footer');
                    } else {
                        $this->load->view('templates/header');
                        $this->load->view('templates/menu');
                        $this->load->view('appointment/report', $data);
                        $this->load->view('templates/footer');
                    }
                }
				else
				{
                    $app_date = date('Y-m-d', strtotime($this->input->post('app_date')));
                    $user_id = $this->input->post('doctor');
					$data['app_date'] = $app_date;
					$data['doctor_id'] = $user_id;
                    $data['app_reports'] = $this->appointment_model->get_report($app_date, $user_id);

                    if ($level == 'Administrator') {
                        $this->load->view('templates/header');
                        $this->load->view('templates/menu');
                        $this->load->view('appointment/report', $data);
                        $this->load->view('templates/footer');
                    } else {
                        $this->load->view('templates/header');
                        $this->load->view('templates/menu');
                        $this->load->view('appointment/report', $data);
                        $this->load->view('templates/footer');
                    }
                }
            }
        }
    }

    function todos() {
        $this->appointment_model->add_todos();
        $this->index();
    }

    function todos_done($done, $id) {

        $this->appointment_model->todo_done($done, $id);
    }

    function delete_todo($id) {
        $this->appointment_model->delete_todo($id);
        $this->index();
    }

	function uploadfiles($patient_id=NULL,$app_id=NULL){
		$data = array();$error = false;$files = array();
		$uploaddir="patient_media/".$patient_id."/".$app_id."/foto/";
		if( ! is_dir("patient_media/".$patient_id."/" ) ) {mkdir( "patient_media/".$patient_id."/", 0777 );}
		if( ! is_dir( "patient_media/".$patient_id."/".$app_id."/") ){ mkdir( "patient_media/".$patient_id."/".$app_id."/", 0777 );
				if( ! is_dir( $uploaddir ) ) mkdir( $uploaddir, 0777 );
		}
		//$uploaddir="patient_images/";
		// Создадим папку если её нет
		if( ! is_dir( $uploaddir ) ) mkdir( $uploaddir, 0777 );

		// переместим файлы из временной директории в указанную
		/*foreach( $_FILES as $file ){
		   if($file == "image/gif" || $file['type'] == "image/png" ||
			$file['type'] == "image/jpg" || $file['type'] == "image/jpeg")
			{
			  //черный список типов файлов
				$blacklist = array(".php", ".phtml", ".php3", ".php4");
				foreach ($blacklist as $item)
				{
					if(preg_match("/$item\$/i", $file['name']))
					{
					  $error = true;
					  exit;
					}
				}
			   $files[] = $this->resize($file, $uploaddir);
			   //if( move_uploaded_file( $file['tmp_name'], $uploaddir . basename($file['name']) ) ){
			//		$files[] = realpath( $uploaddir . $file['name'] );
			//	}
			//	else{
			//		$error = true;	}
			}
		}
		$data = $error ? array('error' => 'Ошибка загрузки файлов.') : array('files' => $files );
	*/
	if ($_POST) {
		$pdata = $_POST['images'];
		$pdata = str_replace('data:image/jpeg;base64,', '|', $pdata);
		$pdata = str_replace(' ', '+', $pdata);
		$imgs=explode("|",$pdata);
		$j=0;
		foreach ($imgs as $i){
				if ($j!=0){
					$data = base64_decode($i);
					$file = $uploaddir . mt_rand() . '.jpg';
					//file_put_contents('t.txt',print_r($file,true),FILE_APPEND);
					$success = file_put_contents($file, $data);
				}
				$j=1;
		}
		//print $success ? $file : 'Unable to save the file.';
		echo "11";
	}
		//echo json_encode( $data );
	}

	function resize($file, $uploaddir, $type = 2, $quality = null, $rotate = null)
	{
		global $tmp_path;
ini_set('log_errors', 'On');
ini_set('error_log', 't1');
		// Ограничение по ширине в пикселях
		$max_thumb_size = 120;
		$max_size = 1000;
		//ini_set('gd.jpeg_ignore_warning',1);
		// Качество изображения по умолчанию
		if ($quality == null)
			$quality = 75;
fwrite('t1','asd');

		// Cоздаём исходное изображение на основе исходного файла
		if ($file['type'] == 'image/jpeg')
			$source = imagecreatefromjpeg($file['tmp_name']);
		elseif ($file['type'] == 'image/png')
			$source = imagecreatefrompng($file['tmp_name']);
		elseif ($file['type'] == 'image/gif')
			$source = imagecreatefromgif($file['tmp_name']);
		else
			return false;

		// Поворачиваем изображение
		if ($rotate != null)
			$src = imagerotate($source, $rotate, 0);
		else
			$src = $source;

		// Определяем ширину и высоту изображения
		$w_src = imagesx($src);
		$h_src = imagesy($src);

		// В зависимости от типа (эскиз или большое изображение) устанавливаем ограничение по ширине.
		if ($type == 1)
			$h = $max_thumb_size;
		elseif ($type == 2)
			$h = $max_size;
//file_put_contents('t1',print_r(memory_get_usage(),true));
		// Если высота больше заданной
		if ($h_src > $w)
		{
			// Вычисление пропорций
			$ratio = $h_src/$h;
			$w_dest = round($w_src/$ratio);
			$h_dest = round($h_src/$ratio);

			// Создаём пустую картинку
			$dest = imagecreatetruecolor($w_dest, $h_dest);

			// Копируем старое изображение в новое с изменением параметров
			imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

			// Вывод картинки и очистка памяти

			imagejpeg($dest, $uploaddir . $file['name'], $quality);
			//imagejpeg($dest, $tmp_path . $file['name'], $quality);
			imagedestroy($dest);
			imagedestroy($src);

			return $file['name'];
		}
		else
		{
			// Вывод картинки и очистка памяти
			imagejpeg($src, $uploaddir . $file['name'], $quality);
			imagedestroy($src);

			return $file['name'];
		}
	}

	public function showmedia($patient_id,$app_id){
		$uploaddir="patient_media/".$patient_id."/".$app_id."/foto/";
		//$uploaddir="patient_images/";
		$error = false;
		$data=scandir($uploaddir);
		echo json_encode( $data );
	}



}
?>