<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Reports_model extends CI_Model
{
    public function fetch_fees_data($student_id = null, $from_date = null, $to_date = null)
    {
        $this->db->select('student_fees.fees_amount, student_fees.paid_date, student.student_name, student.email,student.student_id');
        $this->db->from('student_fees');
        $this->db->join('student', 'student_fees.student_id=student.student_id');
        
        if (!empty($student_id)){
            $this->db->where('student_fees.student_id', $student_id);
         
        }

        if(!empty($from_date) && !empty($to_date)){
            $this->db->where('student_fees.paid_date >=', $from_date);
            $this->db->where('student_fees.paid_date <=', $to_date);
        }

        $res = $this->db->get()->result_array();

        if($res){
            return $res;
        }

    }


    public function fetch_salary_data($teacher_id = null, $from_date = null, $to_date = null)
    {
        $this->db->select('teacher_salary.salary_amount, teacher_salary.paid_date, teacher.name, teacher.email,teacher.teacher_id');

        $this->db->from('teacher_salary');
        
        $this->db->join('teacher', 'teacher_salary.teacher_id = teacher.teacher_id');
        
        if (!empty($teacher_id)){
            $this->db->where('teacher_salary.teacher_id', $teacher_id);
         
        }

        if(!empty($from_date) && !empty($to_date)){
            $this->db->where('teacher_salary.paid_date >=', $from_date);
            $this->db->where('teacher_salary.paid_date <=', $to_date);
        }

        $res = $this->db->get()->result_array();

        if($res){
            return $res;
        }

    }


     
}
