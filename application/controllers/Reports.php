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
	



}


?>