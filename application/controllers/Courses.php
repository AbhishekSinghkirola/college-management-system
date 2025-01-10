<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Courses extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Courses_model', 'courses_md');
	}

	public function index()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}
		$this->load->view('template/header');
		$this->load->view('courses/courses');
		$this->load->view('template/footer');
	}


	/* -------------------------------------------------------------------------- */
	/*                                  Category                                  */
	/* -------------------------------------------------------------------------- */

	public function category()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}
		$this->load->view('template/header');
		$this->load->view('courses/category');
		$this->load->view('template/footer');
	}

	public function get_categories()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];
		$categories = $this->courses_md->get_categories();

		$data['Resp_code'] = 'RCS';
		$data['Resp_desc'] = 'Internal Processing Error';
		$data['data'] = is_array($categories) ? $categories : [];

		exit(json_encode($data));
	}

	public function add_category()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$params = $this->input->post();

		if (validate_field(@$params['cat_name'], 'strname')) {

			$insert_data = [
				'category_name' => $params['cat_name'],
			];
			if ($this->courses_md->add_category($insert_data)) {
				$data['Resp_code'] = 'RCS';
				$data['Resp_desc'] = 'Category Added successfully';
				$data['data'] = [];
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Internal Processing Error';
				$data['data'] = [];
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid Category Name';
			$data['data'] = [];
		}

		exit(json_encode($data));
	}

	public function delete_category()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$params = $this->input->post();

		if (isset($params['cat_id']) && ctype_digit($params['cat_id'])) {

			$get_category = $this->courses_md->get_categories($params['cat_id']);

			if (is_array($get_category) && count($get_category)) {
				if ($this->courses_md->delete_category($params['cat_id'])) {
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'Category Deleted Successfully';
					$data['data'] = [];
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Internal Processing Error';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Category Data Not Found';
				$data['data'] = [];
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid Category';
			$data['data'] = [];
		}

		exit(json_encode($data));
	}

	public function edit_category()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$params = $this->input->post();

		if (isset($params['cat_id']) && ctype_digit($params['cat_id'])) {

			$get_category = $this->courses_md->get_categories($params['cat_id']);

			if (is_array($get_category) && count($get_category)) {

				$update_array = [
					'category_name' => $params['category_name'],
					'cat_id' => $params['cat_id']
				];

				if ($this->courses_md->update_category($update_array)) {
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'Category Updated Successfully';
					$data['data'] = [];
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Internal Processing Error';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Category Data Not Found';
				$data['data'] = [];
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid Category';
			$data['data'] = [];
		}

		exit(json_encode($data));
	}

	/* -------------------------------------------------------------------------- */
	/*                                   Courses                                  */
	/* -------------------------------------------------------------------------- */

	public function get_courses()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];
		$categories = $this->courses_md->get_courses();

		$data['Resp_code'] = 'RCS';
		$data['Resp_desc'] = 'Courses Fetched successfully';
		$data['data'] = is_array($categories) ? $categories : [];

		exit(json_encode($data));
	}

	public function add_course()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$params = $this->input->post();

		if (validate_field(@$params['course_name'], 'strname')) {

			$insert_data = [
				'course_name' => $params['course_name'],
				'course_category' => $params['course_category'],
				'course_duration' => $params['course_duration'],
			];
			if ($this->courses_md->add_course($insert_data)) {
				$data['Resp_code'] = 'RCS';
				$data['Resp_desc'] = 'Course Added successfully';
				$data['data'] = [];
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Internal Processing Error';
				$data['data'] = [];
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid Category Name';
			$data['data'] = [];
		}

		exit(json_encode($data));
	}

	public function delete_course()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$params = $this->input->post();

		if (isset($params['course_id']) && ctype_digit($params['course_id'])) {

			$get_courses = $this->courses_md->get_courses($params['course_id']);

			if (is_array($get_courses) && count($get_courses)) {
				if ($this->courses_md->delete_course($params['course_id'])) {
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'Course Deleted Successfully';
					$data['data'] = [];
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Internal Processing Error';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Category Data Not Found';
				$data['data'] = [];
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid Category';
			$data['data'] = [];
		}

		exit(json_encode($data));
	}

	public function edit_course()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$params = $this->input->post();

		if (isset($params['course_id']) && ctype_digit($params['course_id'])) {

			$get_courses = $this->courses_md->get_courses($params['course_id']);

			if (is_array($get_courses) && count($get_courses)) {

				$update_array = [
					'course_name' => $params['course_name'],
					'course_category' => $params['course_category'],
					'course_duration' => $params['course_duration'],
					'id' => $params['course_id']
				];

				if ($this->courses_md->update_course($update_array)) {
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'Course Updated Successfully';
					$data['data'] = [];
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Internal Processing Error';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Category Data Not Found';
				$data['data'] = [];
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid Category';
			$data['data'] = [];
		}

		exit(json_encode($data));
	}

	/* -------------------------------------------------------------------------- */
	/*                                   Content                                  */
	/* -------------------------------------------------------------------------- */

	public function content()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();
			redirect('login');
		}
		$this->load->view('template/header');
		$this->load->view('courses/content');
		$this->load->view('template/footer');
	}

	public function get_content()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];
		$content = $this->courses_md->get_content();

		$data['Resp_code'] = 'RCS';
		$data['Resp_desc'] = 'Content Fetched successfully';
		$data['data'] = is_array($content) ? $content : [];

		exit(json_encode($data));
	}

	public function add_content()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$params = $this->input->post();

		if (validate_field(@$params['content_name'], 'strname')) {

			$insert_data = [
				'name' => $params['content_name'],
			];
			if ($this->courses_md->add_content($insert_data)) {
				$data['Resp_code'] = 'RCS';
				$data['Resp_desc'] = 'Content Added successfully';
				$data['data'] = [];
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Internal Processing Error';
				$data['data'] = [];
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid Content Name';
			$data['data'] = [];
		}

		exit(json_encode($data));
	}

	public function edit_content()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$params = $this->input->post();

		if (isset($params['content_id']) && ctype_digit($params['content_id'])) {

			$get_content = $this->courses_md->get_content($params['content_id']);

			if (is_array($get_content) && count($get_content)) {

				$update_array = [
					'name' => $params['content_name'],
					'id' => $params['content_id']
				];

				if ($this->courses_md->update_content($update_array)) {
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'Content Updated Successfully';
					$data['data'] = [];
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Internal Processing Error';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Content Data Not Found';
				$data['data'] = [];
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid Content';
			$data['data'] = [];
		}

		exit(json_encode($data));
	}


	public function delete_content()
	{
		$session = $this->session->userdata('cms_session');
		if (!$session) {
			$this->session->sess_destroy();

			exit(json_encode(['Resp_code' => 'RLD', 'Resp_desc' => 'Session Destroyed']));
		}

		$data = [];

		$params = $this->input->post();

		if (isset($params['content_id']) && ctype_digit($params['content_id'])) {

			$get_content = $this->courses_md->get_content($params['content_id']);

			if (is_array($get_content) && count($get_content)) {
				if ($this->courses_md->delete_content($params['content_id'])) {
					$data['Resp_code'] = 'RCS';
					$data['Resp_desc'] = 'Content Deleted Successfully';
					$data['data'] = [];
				} else {
					$data['Resp_code'] = 'ERR';
					$data['Resp_desc'] = 'Internal Processing Error';
					$data['data'] = [];
				}
			} else {
				$data['Resp_code'] = 'ERR';
				$data['Resp_desc'] = 'Content Data Not Found';
				$data['data'] = [];
			}
		} else {
			$data['Resp_code'] = 'ERR';
			$data['Resp_desc'] = 'Invalid Content';
			$data['data'] = [];
		}

		exit(json_encode($data));
	}
}
