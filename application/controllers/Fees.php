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


	/* --------- Function to show alert in studnet view for pending fees -------- */

	public function paid_fees()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$user = get_logged_in_user();	

		$student_id = $user['student_id'];

		$current_year_month = date('Y-m');
		$day_name = date('d', strtotime($user['created_on']));
		$fees_pay_date = $current_year_month . "-" .$day_name;

		$pending_fees = $this->fees_md->get_pending_fees_for_student($fees_pay_date,$student_id);
		
		if(empty($pending_fees)){

			$data['fees_status'] = 'pending';
			$data['date'] = $fees_pay_date;


		}

		$this->load->view('template/header');
		$this->load->view('fees/already_paid_fees',$data);
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

	/* ------------------------- Function to Get All fees list already paid for Student ------------------------ */

	public function paid_fees_list(){

		$session = $this->session->userdata('cms_session');
		if(!$session){
			$this->session->sess_destroy();
			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$user = get_logged_in_user();	
		
		$all_student_fees = $this->fees_md->get_all_student_fees($user['student_id']);

		$current_year_month = date('Y-m');

		$result = [];

		foreach($all_student_fees as $student_fees){

			$day_name = date('d', strtotime($student_fees['created_on']));
			$fees_pay_date = $current_year_month . "-" .$day_name;

			$student_id = $student_fees['student_id'];
			$role_id = $user['role_id'];

			$pending_fees = $this->fees_md->get_pending_fees($fees_pay_date,$student_id,$role_id);
			
			if($pending_fees){
				foreach($pending_fees as $paid_fess){
						$already_paid['paid_date'] = $paid_fess['paid_date'];
						$already_paid['fees_amount'] = $paid_fess['fees_amount'];

						$result[] = $already_paid;
					}
			}
		}
	
		$data['Resp_code'] = 'RCS';
		$data['Resp_desc'] = 'Student Fees Fetched Successfully'; 
		$data['data'] = is_array($result) ? $result : [];

		exit(json_encode($data));
	}

	/* -------------------------- Function to Pay Fees -------------------------- */
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


}
