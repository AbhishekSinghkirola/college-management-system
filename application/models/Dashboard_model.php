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

    public function change_password($user_id, $new_password, $role_id){
        $table = '';

        if ($role_id == '1') {
            $table = 'users';
            $this->db->where('user_id', $user_id);
        } 
        
        else if ($role_id == '2') {
            $table = 'teacher';
            $this->db->where('teacher_id', $user_id);
        } 
        
        else if ($role_id == '3') {
            $table = 'student';
            $this->db->where('student_id', $user_id);
        }

        else {
            return false;
        }

       $res = $this->db->update($table , ['password' => $new_password]);

       //dd($this->db->last_query());
         if ($res) {
             return true;
            } else {
                return false;
            }

    }


    public function update_reset_token($token, $expiry){
        
		$user = get_logged_in_user();

        if($user['role_type'] == 'ADMIN'){

            $id = $user['user_id'];
            $this->db->where('user_id', $id);
            $this->db->update('users', ['reset_token' => $token, 'token_expiry' => $expiry]);    

        }
        elseif($user['role_type'] == 'EMP'){

            $id = $user['teacher_id'];
            $this->db->where('teacher_id', $id);
            $this->db->update('users', ['reset_token' => $token, 'token_expiry' => $expiry]);    

        }
        elseif($user['role_type'] == 'USER'){

            $id = $user['student_id'];
            $this->db->where('student_id', $id);
            $this->db->update('users', ['reset_token' => $token, 'token_expiry' => $expiry]);    

        }

    }


}
