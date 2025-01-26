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

	public function get_fees_list(){

		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];
		$user = get_logged_in_user();	
		
		$all_fees_list = $this->reports_md->get_all_student_fees();

		$data['Resp_code'] = 'RCS';
		$data['Resp_desc'] = 'Fees Fetched Successfully';
		$data['data'] = is_array($all_fees_list) ? $all_fees_list : [];

		exit(json_encode($data));
	}

	public function fees_filter(){

		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}
		$data = [];

		$params = $this->input->post();

		if($params['student_name'] != '' || $params['from_date'] != '' || $params['to_date'] != '') {

			$student_id = $params['student_name'];
			$from_date = $params['from_date'];
			$to_date = $params['to_date'];

			if ($this->reports_md->get_all_student_fees($student_id)) {

				$data['Resp_code'] = 'RCS';
				$data['Resp_desc'] = 'Fees Fetched successfully';
				$data['data'] = [];

			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Internal Processing Error';
				$data['data'] = [];
			}


		}

		else{
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Need Value for Search';
				$data['data'] = [];
		}

		exit(json_encode($data));


	}
	



}


?>