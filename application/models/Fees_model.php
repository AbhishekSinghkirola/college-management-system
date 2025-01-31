<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Fees_model extends CI_Model
{
    public function pay_pending_fees($insert_data)
    {
        return $this->db->insert('student_fees', $insert_data);
    }

    public function get_all_student_fees($student_id = null)
    {
        $this->db->select('student_id,student_name,email,created_on,courses.fees,courses.course_name');
        $this->db->from('student');

        if($student_id){
            $this->db->where('student_id', $student_id);
        }

        $this->db->join('courses','courses.id = student.course');

        $res = $this->db->get()->result_array();

        if ($res) {
            return $res;
        }
    }


    public function get_pending_fees($fees_pay_date, $student_id, $role_id = null)
    {
        $this->db->select('*');
        $this->db->from('student_fees');
        $this->db->where('student_id',$student_id);
    
        if($role_id == '1'){

            $this->db->where('DATE(paid_date) >= ',$fees_pay_date);
            // 2025-01-22 >= 2025-01-29
            // 2024-12-18
        }

        if($role_id == '3') {

            $this->db->where('DATE(paid_date) <= ',$fees_pay_date);
        }
        // if there is data so the student has already paid the fees


        $res = $this->db->get()->result_array();

        if ($res) {
            return $res;
        }
    }


    public function get_pending_fees_for_student($fees_pay_date, $student_id){

        $this->db->select('*');
        $this->db->from('student_fees');
        $this->db->where('student_id',$student_id);

        $this->db->where('DATE(paid_date) >= ',$fees_pay_date);

        $res = $this->db->get()->result_array();

        if ($res) {
            return $res;
        }
    
    }
     
}
