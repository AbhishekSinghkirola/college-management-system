<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attendance extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Attendance_model', 'attendance_md');
    }


    /* -------------------------------------------------------------------------- */
    /*                             Student attendance                             */
    /* -------------------------------------------------------------------------- */
    public function student_attendance()
    {
        $session = $this->session->userdata('cms_session');
        if (!$session) {
            $this->session->sess_destroy();
            redirect('login');
        }

        $attendance = $this->attendance_md->get_attendance($session['user_id'], $session['role_id'], date('Y-m-d'));

        $this->load->view('template/header');
        $this->load->view('attendance/student_attendance', ['attendance' => $attendance]);
        $this->load->view('template/footer');
    }

    public function get_attendance()
    {
        $session = $this->session->userdata('cms_session');
        if (!$session) {
            $this->session->sess_destroy();
            redirect('login');
        }

        $this->load->model('Students_model', 'student_md');
        $this->load->model('Teachers_model', 'teacher_md');

        if ($session['role_id'] == 3) {
            $student = $this->student_md->get_students($session['user_id']);

            $join_date = $student['created_on'];
        } else if ($session['role_id'] == 2) {
            $student = $this->teacher_md->get_teacher($session['user_id']);

            $join_date = $student['created_on'];
        }

        $current_date = date('Y-m-d');
        $dates = [];

        $start_date = new DateTime($join_date);
        $end_date = new DateTime($current_date);
        $end_date = $end_date->modify('+1 day');
        while ($start_date <= $end_date) {
            $dates[] = $start_date->format('Y-m-d');
            $start_date->modify('+1 day');
        }


        usort($dates, "compareByTimeStamp");


        $attendance_data = [];
        foreach ($dates as $date) {
            $result = $this->attendance_md->get_attendance($session['user_id'], $session['role_id'], $date);

            if ($result) {
                $result['date'] = $date;
                $attendance_data[] = $result;
            } else {
                $attendance_data[] = [
                    'date' => $date,
                    'status' => 0,
                    'student_id' => $session['user_id']
                ];
            }
        }

        $data['Resp_code'] = 'RCS';
        $data['Resp_desc'] = 'Attendance Fetched Successfully';
        $data['data'] = is_array($attendance_data) ? $attendance_data : [];
        echo json_encode($data);
    }

    public function mark_attendance()
    {
        $session = $this->session->userdata('cms_session');
        if (!$session) {
            $this->session->sess_destroy();
            redirect('login');
        }

        $date = date('Y-m-d H:i:s');

        if ($session['role_id'] == 2) {
            $insert_data = [
                'teacher_id' => $session['user_id'],
                'date' => $date,
                'status' => 1,
            ];
        } else if ($session['role_id'] == 3) {
            $insert_data = [
                'student_id' => $session['user_id'],
                'date' => $date,
                'status' => 1,
            ];
        }

        $result = $this->attendance_md->mark_attendance($insert_data, $session['role_id']);

        $data['Resp_code'] = 'RCS';
        $data['Resp_desc'] = 'Attendance Marked Successfully';
        $data['data'] = [];
        echo json_encode($data);
    }

    /* -------------------------------------------------------------------------- */
    /*                             Teacher Attendance                             */
    /* -------------------------------------------------------------------------- */
    public function teacher_attendance()
    {
        $session = $this->session->userdata('cms_session');
        if (!$session) {
            $this->session->sess_destroy();
            redirect('login');
        }

        $attendance = $this->attendance_md->get_attendance($session['user_id'], $session['role_id'], date('Y-m-d'));

        $this->load->view('template/header');
        $this->load->view('attendance/teacher_attendance', ['attendance' => $attendance]);
        $this->load->view('template/footer');
    }
}
