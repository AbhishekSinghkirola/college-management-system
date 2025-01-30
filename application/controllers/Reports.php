<?php 

defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends CI_Controller{

    public function __construct(){
        parent::__construct();
		$this->load->model('Reports_model', 'reports_md');

    }

    public function index(){
        $session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$this->load->view('template/header');
		$this->load->view('reports/fees_reports');
		$this->load->view('template/footer');
    }

	/* --------------------- Function to Get fees reports -------------------- */

	public function get_fees_list(){

		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];
		$user = get_logged_in_user();	
		$params = $this->input->post();

		if(!empty($params)){
			$student_id = $params['student_name'];
			$from_date = $params['from_date'];
			$to_date = $params['to_date'];
			
			$all_fees_list = $this->reports_md->fetch_fees_data($student_id, $from_date, $to_date);
		}
		else {
		$all_fees_list = $this->reports_md->fetch_fees_data();
		}

		$data['Resp_code'] = 'RCS';
		$data['Resp_desc'] = 'Fees Fetched Successfully';
		$data['data'] = is_array($all_fees_list) ? $all_fees_list : [];

		exit(json_encode($data));
	}

	public function salary(){

		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$this->load->view('template/header');
		$this->load->view('reports/salary_reports');
		$this->load->view('template/footer');
	}

	/* --------------------- Function to Get Salary reports -------------------- */


	public function get_salary_list(){

		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];
		$user = get_logged_in_user();	
		$params = $this->input->post();

		if(!empty($params)){
			$teacher_id = $params['teacher_name'];
			$from_date = $params['from_date'];
			$to_date = $params['to_date'];
			
			$salary_list = $this->reports_md->fetch_salary_data($teacher_id, $from_date, $to_date);
		}
		else {
		$salary_list = $this->reports_md->fetch_salary_data();
		}

		$data['Resp_code'] = 'RCS';
		$data['Resp_desc'] = 'Salary Fetched Successfully';
		$data['data'] = is_array($salary_list) ? $salary_list : [];

		exit(json_encode($data));
	}

	



}


?>