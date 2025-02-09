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


	public function forget_password(){

		$this->load->view('forget_password');
	}

	
	public function send_reset_link(){

		$email = $_POST['email'];

		$user = $this->auth_md->find_user_by_email($email);

		if($email){
				// bin2hex convert the string into hexadecimal value 
				$token = bin2hex(random_bytes(50));

				//strtotime() Parse English textual datetimes into Unix timestamps
				$expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

				$this->auth_md->store_reset_token($user['table'], $user['id'], $token, $expiry);	

				$reset_link = site_url('Auth/reset_form/' . $token);

				$config['protocol']="smtp";
				 //    $config['smtp_crypto']="tls";
				$config['smtp_host']='ssl://smtp.gmail.com';
				$config['smtp_user']='nr7584128@gmail.com';
				$config['smtp_pass']='csnsjfeysbvnmnpu';
				$config['smtp_port']=465;
				$config['charset'] = 'iso-8859-1';
				
				$config['wordwrap'] = TRUE;
				$config['mailtype']='html';
				$config['newline']="\r\n";
				$config['crlf']="\r\n";
								
										   
				$this->email->initialize($config);
				$this->email->from('nr7584128@gmail.com');
				$this->email->to($email);
				$this->email->subject('Password Reset Request');
				$this->email->message("Click on this link to reset your password: $reset_link");
				$this->email->set_newline("\r\n");
				// dd($this->email->send());

				if ($this->email->send()) {
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'Password reset link sent successfully.';
					$data['data'] = [];

				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Failed to send reset link. Try again.';
					$data['data'] = [];
				}
		}

		else{
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Email not found.';
				$data['data'] = [];
		}

		exit(json_encode($data));
	}

	public function reset_form($token){
	
		$user = $this->auth_md->find_user_by_token($token);

		if(!$user){
			echo "Invalid or expired token!";
			exit;
		}
		else{
			$data['token'] = $token;
			$data['user_data'] = $user;
			//dd($data);
		}
		$this->load->view('reset_form', $data);


	}

	public function update_password(){

		$token = $this->input->post('token');
   		$new_password = $this->input->post('new_password');

		$user = $this->auth_md->find_user_by_token($token);

		if($user){

		$res = $this->auth_md->update_password($user['table'], $user['id_field'], $user['id'], $new_password);

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
				$data['Resp_desc'] = 'Invalid or expired token!';
				$data['data'] = [];
		 }

		 exit(json_encode($data));




	}
}




