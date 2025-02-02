<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Dashboard_model extends CI_Model
{
   public function student_count(){

         $this->db->from('student');
        $res = $this->db->count_all_results();

        if($res){
            return $res;
        }

   }

   public function teacher_count(){
            $this->db->from('teacher');
            $res = $this->db->count_all_results();
        
            if($res){
            return $res;
            }
   }

   public function get_pending_fees_count(){
    
            $this->db->from('teacher');
            $res = $this->db->count_all_results();

            if($res){
            return $res;
            }

   }

   public function get_pending_fees($fees_pay_date, $student_id)
    {

        $this->db->from('student_fees');
        $this->db->where('student_id',$student_id);
        $this->db->where('DATE(paid_date) >= ',$fees_pay_date);
 
        $res = $this->db->get()->result_array();

        if ($res) {
            return $res;
        }
    }



}
