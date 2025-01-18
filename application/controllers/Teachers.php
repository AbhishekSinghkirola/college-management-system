<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Teachers extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Teachers_model', 'teacher_md');
		
	}

	public function index()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}
		$this->load->view('template/header');
		$this->load->view('teacher/teachers');
		$this->load->view('template/footer');
	}

	/* ------------------------- Function to Get Teacher ------------------------ */
	public function get_teacher(){
		$session = $this->session->userdata('cms_session');
		if(!$session){
			$this->session->sess_destroy();
			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];
		$teachers = $this->teacher_md->get_teacher();

		$data['Resp_code'] = 'RCS';
		$data['Resp_desc'] = 'Teachers Fetched Successfully'; 
		$data['data'] = is_array($teachers) ? $teachers : [];

		exit(json_encode($data));
	}


	/* -------------------------- Function to Add New Teacher -------------------------- */
	public function add_teacher()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$password = md5('welcome@123');
		$role_id = 2;
		$account_status = 'ACTIVE';

		$params = $this->input->post();
		$email = $params['email'];
		$mobile = $params['mobile'];
		$account_number = $params['account_number'];

		/* ------------------ check for email and mobile existence ------------------ */
		$all_users = $this->general_md->get_all_users($email, $mobile ,2); 

		if(empty($all_users)){

		/* ------------------ check for account number existence ------------------ */
		$macthed_account_number = $this->general_md->check_account_number_existence($account_number);

				if(empty($macthed_account_number)){
					if (validate_field(@$params['teacher_name'], 'strname')) {

						$insert_data = [
							'name' => $params['teacher_name'],
							'email' => $params['email'],
							'password' => $password,
							'mobile' => $params['mobile'],
							'address' => $params['address'],
							'courses' => $params['course_name'],
							'salary' => $params['salary'],
							'bank_name' => $params['bank_name'],
							'account_holder_name' => $params['account_holder_name'],
							'ifsc_code' => $params['ifsc_code'],
							'account_number' => $params['account_number'],
							'created_on' => date('Y-m-d H:i:s'),
							'role_id' => $role_id,
							'account_status' => $account_status,
						];
			
			
						if ($this->teacher_md->add_teacher($insert_data)) {
							$data['Resp_code'] = 'RCS';
							$data['Resp_desc'] = 'Teacher Added successfully';
							$data['data'] = [];
						} else {
							$data['Resp_code'] = 'ERR';
							$data['Resp_desc'] = 'Internal Processing Error';
							$data['data'] = [];
						}
			
						
					} else {
						$data['Resp_code'] = 'ERR';
						$data['Resp_desc'] = 'Invalid Teacher Name';
						$data['data'] = [];
					}
				}
				else{
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Account Number Already exist';
					$data['data'] = [];
				}

			}
			else{
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Email and Mobile Already Exist !';
				$data['data'] = [];
				
			}
		
		exit(json_encode($data));
	}

	/* ------------------------ Function to Edit Teacher ------------------------ */
	public function edit_teacher()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$params = $this->input->post();

		$email = $params['email'];
		$mobile = $params['mobile'];
		$account_number = $params['account_number'];
		$teacher_id = $params['teacher_id'];
		/* ------------------ check for email and mobile existence ------------------ */
		$all_users = $this->general_md->get_all_users($email, $mobile ,2); 

		//dd($all_users);
		// if($all_users[0]['teacher_id'] === $params['teacher_id']){}

		if(empty($all_users) || $all_users[0]['teacher_id'] === $params['teacher_id']){
			/* ------------------ check for account number existence ------------------ */
		$macthed_account_number = $this->general_md->check_account_number_existence($account_number);

		if(empty($macthed_account_number) || $macthed_account_number['teacher_id'] === $params['teacher_id']){

		if (isset($params['teacher_id']) && ctype_digit($params['teacher_id'])) {

			$get_teacher = $this->teacher_md->get_teacher($params['teacher_id']);

		//dd($get_teacher);

			if (is_array($get_teacher) && count($get_teacher)) {

				$update_array = [
							'name' => $params['teacher_name'],
							'email' => $params['email'],
							'mobile' => $params['mobile'],
							'address' => $params['address'],
							'courses' => $params['course_id'],
							'salary' => $params['salary'],
							'bank_name' => $params['bank_name'],
							'account_holder_name' => $params['account_holder_name'],
							'ifsc_code' => $params['ifsc_code'],
							'account_number' => $params['account_number'],
							'teacher_id' => $params['teacher_id'],
							'account_status' => $params['account_status']
				];

				if ($this->teacher_md->update_teacher($update_array)) {
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'Teacher Updated Successfully';
					$data['data'] = [];
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Internal Processing Error';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Teacher Data Not Found';
				$data['data'] = [];
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid Teacher';
			$data['data'] = [];
		}
	}

	else{
		$data['Resp_code'] = 'ERR';
		$data['Resp_desc'] = 'Account Number Already exist';
		$data['data'] = [];
	}

	}

		else{
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Email and Mobile Already Exist !';
			$data['data'] = [];
			
		}

		exit(json_encode($data));
	}

	/* ----------------------- Function to Delete Student ----------------------- */

	public function delete_teacher()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$params = $this->input->post();

		if (isset($params['teacher_id']) && ctype_digit($params['teacher_id'])) {

			$get_teacher = $this->teacher_md->get_teacher($params['teacher_id']);

			if (is_array($get_teacher) && count($get_teacher)) {

				if ($this->teacher_md->delete_teacher($params['teacher_id'])) {
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'Teacher Deleted Successfully';
					$data['data'] = [];
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Internal Processing Error';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Teacher Data Not Found';
				$data['data'] = [];
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid Teacher';
			$data['data'] = [];
		}

		exit(json_encode($data));
	}

	/* ------------- Function To Show Pending Student Registrations ------------- */
	public function pending_registration()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}
		$this->load->view('template/header');
		$this->load->view('student/pending_students');
		$this->load->view('template/footer');
	}
}
