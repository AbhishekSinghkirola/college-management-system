<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auth_model', 'auth_md');
	}

	public function login()
	{
		$session = $this->session->has_userdata('cms_session');
		if ($session) {
			redirect('/');
		} else {
			$this->load->view('login');
		}
	}

	/* ------------------------ Function To Iniate Login ------------------------ */
	public function init_login()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = [];
			$session = $this->session->has_userdata('cms_session');
			if (!$session) {
				$params = $this->input->post();
				$params['mobile'] = isset($params['mobile']) ? (ctype_digit($params['mobile']) ? trim($params['mobile']) : "") : "";
				$params['password'] = isset($params['password']) ? (is_string($params['password']) ? trim($params['password']) : "") : "";
				$params['role_id'] = isset($params['role_id']) ? (is_numeric($params['role_id']) ? trim($params['role_id']) : "") : "";

				
				if (validate_field($params['mobile'], 'mob')) {

					if (validate_field($params['password'], 'strpass')) {
						$is_valid_user = $this->auth_md->check_valid_user($params['mobile'], md5($params['password']), $params['role_id']);

						//dd($is_valid_user);
						if ($is_valid_user) {

							if ($params['role_id'] == '1') {
								$user_id = $is_valid_user['user_id'];
							} else if ($params['role_id'] == '2') {
								$user_id = $is_valid_user['teacher_id'];
							} else if ($params['role_id'] == '3') {
								$user_id = $is_valid_user['student_id'];
							}

							$session_array = array(
								"user_id" => $user_id,
								"role_id" => $is_valid_user['role_id'],
							);

							$this->session->set_userdata("cms_session", $session_array);

							$data['Resp_code'] = 'RLD';
							$data['Resp_desc'] = 'User Logged In Successfully';
							$data['data'] = [];
						} else {
							$data['Resp_code'] = 'ERR';
							$data['Resp_desc'] = 'Invalid Credentials';
							$data['data'] = [];
						}
					} else {
						$data['Resp_code'] = 'ERR';
						$data['Resp_desc'] = 'Invalid Password';
						$data['data'] = [];
					}
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Invalid Mobile Number';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'RLD';
				$data['Resp_desc'] = 'Session already exist';
				$data['data'] = [];
			}
			echo json_encode($data);
		} else {
			redirect('login');
		}
	}

	/* ---------------------- Function To Log Out the User ---------------------- */
	public function logout()
	{
		$session = $this->session->has_userdata('cms_session');
		if ($session) {
			$this->session->sess_destroy();
			redirect('login');
		} else {
			redirect('login');
		}
	}

	public function register()
	{
		$this->load->view('register');
	}

	/* --------------------- Function For User Registration --------------------- */
	public function registration()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = [];

			$params = $this->input->post();
			$params['fname'] = isset($params['fname']) ? (is_string($params['fname']) ? trim($params['fname']) : "") : "";
			$params['lname'] = isset($params['lname']) ? (is_string($params['lname']) ? trim($params['lname']) : "") : "";
			$params['email'] = isset($params['email']) ? (is_string($params['email']) ? trim($params['email']) : "") : "";
			$params['mobile'] = isset($params['mobile']) ? (ctype_digit($params['mobile']) ? trim($params['mobile']) : "") : "";
			$params['password'] = isset($params['password']) ? (is_string($params['password']) ? trim($params['password']) : "") : "";
			$params['role_id'] = isset($params['role_id']) ? (is_numeric($params['role_id']) ? trim($params['role_id']) : "") : "";

			if (validate_field($params['fname'], 'strname')) {

				if (validate_field($params['lname'], 'strname')) {

					if (validate_field($params['email'], 'email')) {

						if (validate_field($params['mobile'], 'mob')) {

							if (validate_field($params['password'], 'strpass')) {

								$check_user = $this->auth_md->check_user($params['email'], $params['mobile']);
								if ($check_user) {
									$data['Resp_code'] = 'ERR';
									$data['Resp_desc'] = 'User Already Exsist';
									$data['data'] = [];
								} else {
									$insert_array = [
										'first_name' => $params['fname'],
										'last_name' => $params['lname'],
										'email' => $params['email'],
										'mobile' => $params['mobile'],
										'password' => md5($params['password']),
										'role_id' => $params['role_id'],
										'created_on' => date('Y-m-d H:i:s'),
										'account_status' => 'PENDING',
									];
									$insert_user = $this->auth_md->insert_user($insert_array);
									if ($insert_user) {
										$data['Resp_code'] = 'RCS';
										$data['Resp_desc'] = 'Registered Successfully';
										$data['data'] = [];
									} else {
										$data['Resp_code'] = 'ERR';
										$data['Resp_desc'] = 'Something Went Wrong';
										$data['data'] = [];
									}
								}
							} else {
								$data['Resp_code'] = 'ERR';
								$data['Resp_desc'] = 'Invalid Password';
								$data['data'] = [];
							}
						} else {
							$data['Resp_code'] = 'ERR';
							$data['Resp_desc'] = 'Invalid Mobile Number';
							$data['data'] = [];
						}
					} else {
						$data['Resp_code'] = 'ERR';
						$data['Resp_desc'] = 'Invalid Email';
						$data['data'] = [];
					}
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Invalid Last Name';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Invalid First Name';
				$data['data'] = [];
			}
			echo json_encode($data);
		} else {
			redirect('login');
		}
	}
}
