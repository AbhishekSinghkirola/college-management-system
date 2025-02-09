<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Auth_model extends CI_Model
{
    /* ------------------------ Function to validate User ----------------------- */
    public function check_valid_user($mobile, $password, $role_id)
    {
        $table = '';
        $mobile = is_string($mobile) ? trim($mobile) : '';
        $password = is_string($password) ? trim($password) : '';
        $role_id = is_numeric($role_id) ? trim($role_id) : '';

        if ($mobile && $password && $role_id) {
            if ($role_id == '1') {
                $table = 'users';
            } else if ($role_id == '2') {
                $table = 'teacher';
            } else if ($role_id == '3') {
                $table = 'student';
            }

            if ($table) {
                $res = $this->db->get_where($table, ['mobile' => $mobile, 'password' => $password, 'account_status' => 'ACTIVE'])->row_array();

                
                if ($res) return $res;
            }
        }
    }

    /* ------------------- Function oCheck User Already Exsist ------------------ */
    public function check_user($email, $mobile)
    {
        if (!empty($email) && !empty($mobile)) {
            $res = $this->db->get_where('users', ['email' => $email, 'mobile' => $mobile])->row_array();
            if ($res) return $res;
        }
    }

    /* ---------------------- Function To Insert User Data ---------------------- */
    public function insert_user($insert_array)
    {
        if (!empty($insert_array)) {
            $insert = $this->db->insert('users', $insert_array);
            return true;
        }
    }

    /* --------------- Fucntion to get user table and id by email --------------- */
    public function find_user_by_email($email){
        // Check in user table
        $query = $this->db->get_where('users', ['email' => $email]);
        
        if($query->num_rows() > 0){
            return ['id' => $query->row()->user_id, 'table'=>'users'];
        }

        // Check in teacher table
        $query = $this->db->get_where('teacher', ['email' => $email]);
        
        if($query->num_rows() > 0){
            return ['id' => $query->row()->teacher_id, 'table'=>'teacher'];
        }

         // Check in student table
         $query = $this->db->get_where('student', ['email' => $email]);
        
         if($query->num_rows() > 0){
             return ['id' => $query->row()->student_id, 'table'=>'student'];
         }

         return false;
    }

    /* ----------------- Function to store or update token and expiry date ---------------- */
    public function store_reset_token($table, $id, $token, $expiry){
        
        if($table == 'users'){
            $this->db->where('user_id', $id);
            $this->db->update('users', ['reset_token' => $token, 'token_expiry' => $expiry]);    
        }
        elseif($table == 'teacher'){
            $this->db->where('teacher_id', $id);
            $this->db->update('teacher', ['reset_token' => $token, 'token_expiry' => $expiry]);    
        }
        elseif($table == 'student'){
            $this->db->where('student_id', $id);
            $this->db->update('student', ['reset_token' => $token, 'token_expiry' => $expiry]);    
        }

    }

    public function find_user_by_token($token){

        // $tables = ['users', 'teacher', 'student'];
        $tables = [
            'users' => 'user_id',
            'teacher' => 'teacher_id',
            'student' => 'student_id'
        ];
    
        
        foreach($tables as $table => $id_field){

            $this->db->where('reset_token', $token);
            $this->db->where('token_expiry >=', date('Y-m-d H:i:s'));
            $query = $this->db->get($table);

            if ($query->num_rows() > 0) {
                return ['id' => $query->row()->$id_field, 'table' => $table, 'id_field' => $id_field];
            }
        }
        return false;

    }

    public function update_password($table, $id_field, $id, $new_password){

        $this->db->where($id_field, $id);

       $res = $this->db->update($table, [
        'password' => md5($new_password),
        'reset_token' => NULL,
        'token_expiry' => NULL
        ]);

        if ($res) {
            return true;
           } else {
               return false;
           }

    }
}
