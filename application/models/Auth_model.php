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
}
