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
