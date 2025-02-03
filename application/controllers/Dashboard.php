<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public function __construct(){

		parent:: __construct();
		$this->load->model('Dashboard_model', 'dash_md');
		$this->load->model('Fees_model', 'fees_md');
		$this->load->model('Teachers_model', 'teacher_md');
		$this->load->model('Courses_model', 'courses_md');
		
	}

	public function index()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$dashboard_counts = [];

		$student_count = $this->dash_md->student_count();

		$teacher_count  = $this->dash_md->teacher_count();
		
		$all_student_fees = $this->fees_md->get_all_student_fees();

		$teachers_list = $this->teacher_md->get_teacher();

		$courses = $this->courses_md->get_courses();



		/* ---------------- function for getting penidng salary count --------------- */

		if($all_student_fees){
			$current_year_month = date('Y-m');

			$result_fees = [];
	
			foreach($all_student_fees as $student_fees){
	
				$day_name = date('d', strtotime($student_fees['created_on']));
				$fees_pay_date = $current_year_month . "-" .$day_name;
	
				$student_id = $student_fees['student_id'];
	
				$pending_fees = $this->fees_md->get_pending_fees($fees_pay_date,$student_id);
	
				 if(empty($pending_fees)){
	
					$student_fees['due_date'] = $fees_pay_date;
					$result_fees[] = $student_fees;
				 }
			}
		
		}

		
		/* ---------------- function for getting penidng salary count --------------- */

		if($teachers_list){
			$result_salary = [];

			$current_year_month = date('Y-m');
		
			foreach($teachers_list as $teacher){

			$day_name = date('d', strtotime($teacher['created_on']));
			$salary_pay_date = $current_year_month . "-" .$day_name;

			$teacher_id = $teacher['teacher_id']; 

			$get_pending_salary_list = $this->teacher_md->pending_salary($salary_pay_date, $teacher_id);

			if(empty($get_pending_salary_list)){

				$teacher['due_date'] = $salary_pay_date;
				$result_salary[] = $teacher;
			}

			}
		}
		
	
		$pending_fees_count = count($result_fees);

		$pending_salaries_count = count($result_salary);
		
		$dashboard_counts = array(
			'student_count' => $student_count,
			'teacher_count' => $teacher_count,
			'pending_fees_count' => $pending_fees_count,
			'pending_salaries_count' => $pending_salaries_count,
			'courses_list' => $courses
		);
	
		// dd($dashboard_counts);
		$this->load->view('template/header');
		$this->load->view('dashboard',$dashboard_counts);
		$this->load->view('template/footer');
	}


	public function user_setting(){
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$this->load->view('template/header');
		$this->load->view('user_setting');
		$this->load->view('template/footer');
	}


	public function password(){
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$this->load->view('template/header');
		$this->load->view('password');
		$this->load->view('template/footer');
	}


	public function change_password(){
		
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$user = get_logged_in_user();
		
		$current_password_enter_by_user = md5($_POST['current_password']);
		$new_password = md5($_POST['new_password']);

		if($current_password_enter_by_user ===  $user['password']){

		$res = 	$this->dash_md->change_password($user['user_id'], $new_password,  $user['role_id']);

		if($res){
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'Password Updated Successfully';
					$data['data'] = [];
		}
		else{
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Internal Processing Error';
					$data['data'] = [];
		}
		}
		else{
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Invalid Current Password';
				$data['data'] = [];
		}

		exit(json_encode($data));

		
	}
}
