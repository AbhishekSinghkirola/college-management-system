<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Students_model', 'students_md');
		
	}

	public function index()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}
		$this->load->view('template/header');
		$this->load->view('student/students');
		$this->load->view('template/footer');
	}

	/* ------------------------- Function to Get Student ------------------------ */
	public function get_students(){
		$session = $this->session->userdata('cms_session');
		if(!$session){
			$this->session->sess_destroy();
			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));

		}

		$data = [];
		$students = $this->students_md->get_students();

		$data['Resp_code'] = 'RCS';
		$data['Resp_desc'] = 'Students Fetched Successfully'; 
		$data['data'] = is_array($students) ? $students : [];

		exit(json_encode($data));
	}

	/* -------------------------- Function to Add New Student -------------------------- */
	public function add_student()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$password = md5('welcome@123');
		$role_id = 3;
		$account_status = 'ACTIVE';

		$params = $this->input->post();

		$email = $params['email'];
		$mobile = $params['mobile'];

		$all_users = $this->general_md->get_all_users($email, $mobile);

		if(empty($all_users)){
				
				if (validate_field(@$params['student_name'], 'strname')) {

					$insert_data = [
						'student_name' => $params['student_name'],
						'email' => $params['email'],
						'password' => $password,
						'mobile' => $params['mobile'],
						'address' => $params['address'],
						'father_name' => $params['father_name'],
						'mother_name' => $params['mother_name'],
						'course' => $params['course_name'],
						'created_on' => date('Y-m-d H:i:s'),
						'role_id' => $role_id,
						'account_status' => $account_status,
		
					];
		
		
					if ($this->students_md->add_students($insert_data)) {
						$data['Resp_code'] = 'RCS';
						$data['Resp_desc'] = 'Student Added successfully';
						$data['data'] = [];
					} else {
						$data['Resp_code'] = 'ERR';
						$data['Resp_desc'] = 'Internal Processing Error';
						$data['data'] = [];
					}
		
					
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Invalid Student Name';
					$data['data'] = [];
				}
			}
			else{
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Email  or Mobile Already Exist !';
				$data['data'] = [];
				
			}
		
		exit(json_encode($data));
	}

	/* ------------------------ Function to Edit Student ------------------------ */
	public function edit_student()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$params = $this->input->post();

		if (isset($params['student_id']) && ctype_digit($params['student_id'])) {

			$get_student = $this->students_md->get_students($params['student_id']);

		//dd($params);

			if (is_array($get_student) && count($get_student)) {

				$update_array = [
					'student_name' => $params['student_name'],
					'email' => $params['email'],
					'mobile' => $params['mobile'],
					'address' => $params['address'],
					'father_name' => $params['father_name'],
					'mother_name' => $params['mother_name'],
					'course' => $params['course_id'], // update course id on studnet table
					'student_id' => $params['student_id'],
					'account_status' => $params['account_status']
				];

				if ($this->students_md->update_student($update_array)) {
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'Student Updated Successfully';
					$data['data'] = [];
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Internal Processing Error';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Student Data Not Found';
				$data['data'] = [];
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid Student';
			$data['data'] = [];
		}

		exit(json_encode($data));
	}

	/* ----------------------- Function to Delete Student ----------------------- */

	public function delete_student()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$params = $this->input->post();

		if (isset($params['student_id']) && ctype_digit($params['student_id'])) {

			$get_student = $this->students_md->get_students($params['student_id']);

			if (is_array($get_student) && count($get_student)) {

				if ($this->students_md->delete_student($params['student_id'])) {
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'Student Deleted Successfully';
					$data['data'] = [];
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Internal Processing Error';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Student Data Not Found';
				$data['data'] = [];
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid Student';
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
