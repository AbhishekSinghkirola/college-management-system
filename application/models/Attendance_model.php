<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Attendance_model extends CI_Model
{
    public function get_attendance($user_id, $role_id, $date = null)
    {
        $table = '';

        if ($role_id == 2) {
            $table = 'teacher_attendance';
            $this->db->where('teacher_id', $user_id);
        } else if ($role_id == 3) {
            $table = 'student_attendance';
            $this->db->where('student_id', $user_id);
        }

        if ($table) {
            if ($date) {
                $this->db->where('DATE(date)', $date);
            }

            $this->db->order_by('date', 'desc');
            $query = $this->db->get($table);
            if ($date) {
                $data = $query->row_array();
            } else {
                $data = $query->result_array();
            }
            return $data;
        } else {
            return false;
        }
    }

    public function mark_attendance($insert_data, $role_id)
    {
        $table = '';

        if ($role_id == 2) {
            $table = 'teacher_attendance';
        } else if ($role_id == 3) {
            $table = 'student_attendance';
        }

        if ($table) {
            $this->db->insert($table, $insert_data);
            return $this->db->affected_rows() > 0;
        } else {
            return false;
        }
    }
}
