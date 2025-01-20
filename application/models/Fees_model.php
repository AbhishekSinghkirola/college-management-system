<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Fees_model extends CI_Model
{
    public function add_teacher($insert_data)
    {
        return $this->db->insert('teacher', $insert_data);
    }

    public function get_all_student_fees($student_id = null)
    {
        $this->db->select('student_id,student_name,email,created_on');
        $this->db->from('student');
        $res = $this->db->get()->result_array();

        if ($res) {
            return $res;
        }
    }


    public function get_pending_fees($fees_pay_date, $student_id)
    {
        $this->db->select('*');
        $this->db->from('student_fees');
        $this->db->where('student_id',$student_id);

        $res = $this->db->get()->result_array();

        if ($res) {
            return $res;
        }
    }


    public function update_teacher($update_array)
    {

        $id = $update_array['teacher_id'];

        if ($id) {
            unset($update_array['teacher_id']);
            return $this->db->update('teacher', $update_array, ['teacher_id' => $id]);
        }
    }

    public function delete_teacher($teacher_id)
    {
        if ($teacher_id) {
            return $this->db->delete('teacher', ['teacher_id' => $teacher_id]);
        }
    }
     
}
