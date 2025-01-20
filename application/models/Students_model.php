<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Students_model extends CI_Model
{
    public function add_students($insert_data)
    {
        return $this->db->insert('student', $insert_data);
    }

    public function get_students($student_id = null)
    {
        $this->db->from('student');
        $this->db->join('courses', 'student.course=courses.id');
        $this->db->join('category', 'courses.course_category=category.category_id');


        if ($student_id) {
            $this->db->where('student_id', $student_id);
            $res = $this->db->get()->row_array();
        } else {
            $res = $this->db->get()->result_array();
        }
        if ($res) {
            return $res;
        }
    }

    public function update_student($update_array)
    {

        $id = $update_array['student_id'];

        if ($id) {
            unset($update_array['student_id']);
            return $this->db->update('student', $update_array, ['student_id' => $id]);
        }
    }

    public function delete_student($student_id)
    {
        if ($student_id) {
            return $this->db->delete('student', ['student_id' => $student_id]);
        }
    }

    public function get_today_attendance($date)
    {
        $this->db->select('s.*, COALESCE(sa.status, "PENDING") as status');
        $this->db->from('student s');
        $this->db->join('student_attendance sa', 's.student_id = sa.student_id and DATE(sa.date)="' . $date . '"', 'left');
        $query = $this->db->get();
        $res = $query->result_array();

        return $res;
    }
}
