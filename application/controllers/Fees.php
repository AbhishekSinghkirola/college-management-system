<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fees extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Fees_model', 'fees_md');
		
	}

	public function index()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}
		$this->load->view('template/header');
		$this->load->view('fees/pending_fees');
		$this->load->view('template/footer');
	}

	/* ------------------------- Function to Get Pending Fees ------------------------ */
	public function get_pending_fees(){
		$session = $this->session->userdata('cms_session');
		if(!$session){
			$this->session->sess_destroy();
			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];
		$all_student_fees = $this->fees_md->get_all_student_fees();

		$current_year_month = date('Y-m');

		$result = [];

		foreach($all_student_fees as $student_fees){

			$day_name = date('d', strtotime($student_fees['created_on']));
			$fees_pay_date = $current_year_month . "-" .$day_name;

			$student_id = $student_fees['student_id'];

			$pending_fees = $this->fees_md->get_pending_fees($fees_pay_date,$student_id);

			 if(empty($pending_fees)){

				$student_fees['due_date'] = $fees_pay_date;
				$result[] = $student_fees;
			 }
		}
		//dd($result);
		$data['Resp_code'] = 'RCS';
		$data['Resp_desc'] = 'Pending Fees Student Fetched Successfully'; 
		$data['data'] = is_array($result) ? $result : [];

		exit(json_encode($data));
	}


	/* -------------------------- Function to Add New Teacher -------------------------- */
	public function pay_pending_fees()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];
		$params = $this->input->post();

					if (validate_field(@$params['student_name'], 'strname')) {

						$insert_data = [
							'student_id' => $params['student_id'],
							'fees_amount' => $params['fees_amount'],
							'paid_date' => date('Y-m-d H:i:s'),
						];
			
			
						if ($this->fees_md->pay_pending_fees($insert_data)) {
							$data['Resp_code'] = 'RCS';
							$data['Resp_desc'] = 'Fees Paid successfully';
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

			$get_teacher = $this->fees_md->get_teacher($params['teacher_id']);

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

				if ($this->fees_md->update_teacher($update_array)) {
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

	/* ----------------------- Function to Delete Teacher ----------------------- */

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

			$get_teacher = $this->fees_md->get_teacher($params['teacher_id']);

			if (is_array($get_teacher) && count($get_teacher)) {

				if ($this->fees_md->delete_teacher($params['teacher_id'])) {
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


}
