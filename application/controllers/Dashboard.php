<?php

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';  

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public function __construct(){

		parent:: __construct();
		$this->load->model('Dashboard_model', 'dash_md');
		$this->load->model('Fees_model', 'fees_md');
		$this->load->model('Teachers_model', 'teacher_md');
		$this->load->model('Courses_model', 'courses_md');
		
		$this->load->library('email');

		
	}

	public function index()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$dashboard_counts = [];

		$student_count = $this->dash_md->student_count();

		$teacher_count  = $this->dash_md->teacher_count();
		
		$all_student_fees = $this->fees_md->get_all_student_fees();

		$teachers_list = $this->teacher_md->get_teacher();

		$courses = $this->courses_md->get_courses();



		/* ---------------- function for getting penidng salary count --------------- */

		if($all_student_fees){
			$current_year_month = date('Y-m');

			$result_fees = [];
	
			foreach($all_student_fees as $student_fees){
	
				$day_name = date('d', strtotime($student_fees['created_on']));
				$fees_pay_date = $current_year_month . "-" .$day_name;
	
				$student_id = $student_fees['student_id'];
	
				$pending_fees = $this->fees_md->get_pending_fees($fees_pay_date,$student_id);
	
				 if(empty($pending_fees)){
	
					$student_fees['due_date'] = $fees_pay_date;
					$result_fees[] = $student_fees;
				 }
			}
		
		}

		
		/* ---------------- function for getting penidng salary count --------------- */

		if($teachers_list){
			$result_salary = [];

			$current_year_month = date('Y-m');
		
			foreach($teachers_list as $teacher){

			$day_name = date('d', strtotime($teacher['created_on']));
			$salary_pay_date = $current_year_month . "-" .$day_name;

			$teacher_id = $teacher['teacher_id']; 

			$get_pending_salary_list = $this->teacher_md->pending_salary($salary_pay_date, $teacher_id);

			if(empty($get_pending_salary_list)){

				$teacher['due_date'] = $salary_pay_date;
				$result_salary[] = $teacher;
			}

			}
		}
		
	
		$pending_fees_count = count($result_fees);

		$pending_salaries_count = count($result_salary);
		
		$dashboard_counts = array(
			'student_count' => $student_count,
			'teacher_count' => $teacher_count,
			'pending_fees_count' => $pending_fees_count,
			'pending_salaries_count' => $pending_salaries_count,
			'courses_list' => $courses
		);
	
		// dd($dashboard_counts);
		$this->load->view('template/header');
		$this->load->view('dashboard',$dashboard_counts);
		$this->load->view('template/footer');
	}


	public function user_setting(){
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$this->load->view('template/header');
		$this->load->view('user_setting');
		$this->load->view('template/footer');
	}


	public function password(){
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$this->load->view('template/header');
		$this->load->view('password');
		$this->load->view('template/footer');
	}


	public function change_password(){
		
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}

		$user = get_logged_in_user();
		
		$current_password_enter_by_user = md5($_POST['current_password']);
		$new_password = md5($_POST['new_password']);

		if($current_password_enter_by_user ===  $user['password']){

		$res = 	$this->dash_md->change_password($user['user_id'], $new_password,  $user['role_id']);

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
				$data['Resp_desc'] = 'Invalid Current Password';
				$data['data'] = [];
		}

		exit(json_encode($data));

		
	}

	public function send_reset_link(){
		// csns jfey sbvn mnpu
		$user = get_logged_in_user();
		$email = $user['email'];

		if($email){
				// bin2hex convert the string into hexadecimal value 
				$token = bin2hex(random_bytes(50));

				//strtotime() Parse English textual datetimes into Unix timestamps
				$expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

				$this->dash_md->update_reset_token($token, $expiry);	

				$reset_link = site_url('passwordreset/reset_form/'.$token);

				// $this->email->from('', 'Password Reset');
				// $this->email->to($email);
				// $this->email->subject('Password Reset Request');
				// $this->email->message("Click on this link to reset your password: $reset_link");


				$config['protocol']="smtp";
				                        //    $config['smtp_crypto']="tls";
				$config['smtp_host']='ssl://smtp.gmail.com';
				$config['smtp_user']='';
				$config['smtp_pass']='';
				$config['smtp_port']=465;
				$config['charset'] = 'iso-8859-1';
				
				$config['wordwrap'] = TRUE;
											$config['mailtype']='html';
											$config['newline']="\r\n";
											$config['crlf']="\r\n";
								
										   
											$this->email->initialize($config);
											$this->email->from('');
											$this->email->to($email);
				$this->email->subject('Password Reset Request');
											$this->email->message("Click on this link to reset your password: $reset_link");
											$this->email->set_newline("\r\n");
											dd($this->email->send());


				// if ($this->email->send()) {

				// 	$data['Resp_code'] = 'RCS';
				// 	$data['Resp_desc'] = 'Password reset link sent successfully.';
				// 	$data['data'] = [];

				// } else {
				// 	$data['Resp_code'] = 'ERR';
				// 	$data['Resp_desc'] = 'Failed to send reset link. Try again.';
				// 	$data['data'] = [];
				// }

				// $mail = new PHPMailer(true);

				// try {
				// 	// SMTP Configuration
				// 	$mail->isSMTP();
				// 	$mail->Host       = 'smtp.gmail.com';
				// 	$mail->SMTPAuth   = true;
				// 	$mail->Username   = ''; // Replace with your email
				// 	$mail->Password   = 'rawat9015'; // Use Gmail App Password
				// 	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS
				// 	$mail->Port       = 587; // Port for TLS

				// 	// Email details
				// 	$mail->setFrom('your-email@gmail.com', 'Your Name');
				// 	$mail->addAddress('recipient@example.com');  // Replace with recipient email
				// 	$mail->Subject = 'Test Email via PHPMailer';
				// 	$mail->isHTML(true);
				// 	$mail->Body    = '<p>Hello, this is a test email sent using PHPMailer in CodeIgniter 3.</p>';

				// 	// Send email
				// 	$mail->send();
				// 	echo 'Email sent successfully!';
				// } catch (Exception $e) {
				// 	echo "Error: {$mail->ErrorInfo}";
				// }
		}

		else{
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Email not found.';
				$data['data'] = [];
		}

		exit(json_encode($data));
	}
}
