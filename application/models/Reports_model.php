<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Reports_model extends CI_Model
{
    public function get_all_student_fees($student_id = null)
    {
        $this->db->select('student_fees.fees_amount, student_fees.paid_date, student.student_name, student.email,student.student_id');
        $this->db->from('student_fees');
        $this->db->join('student', 'student_fees.student_id=student.student_id');
        
        $res = $this->db->get()->result_array();

        if($res){
            return $res;
        }

    }


     
}
