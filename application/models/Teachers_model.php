<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Teachers_model extends CI_Model
{
    public function add_teacher($insert_data)
    {
        return $this->db->insert('teacher', $insert_data);
    }

    public function get_teacher($teacher_id = null)
    {
        $this->db->from('teacher');

        if ($teacher_id) {
            $this->db->where('teacher_id', $teacher_id);
            $res = $this->db->get()->row_array();
        } else {
            $res = $this->db->get()->result_array();
        }
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

    public function pending_salary($salary_pay_date, $teacher_id, $role_id = null){
        $this->db->select('*');
        $this->db->from('teacher_salary');
        $this->db->where('teacher_id' , $teacher_id);

        $this->db->where('DATE(paid_date) >= ', $salary_pay_date);

       $res =  $this->db->get()->result_array();

       if($res){
            return $res;
       }


    }
    
    public function pay_pending_salary($insert_data)
    {
        return $this->db->insert('teacher_salary', $insert_data);
    }

    public function get_all_salary_list($teacher_id){

        $this->db->select('salary_amount, DATE(paid_date)');
        $this->db->from('teacher_salary');
        $this->db->where('teacher_id', $teacher_id);

        $res =  $this->db->get()->result_array();

        if($res){
            return $res;
        }

    }


     
}
