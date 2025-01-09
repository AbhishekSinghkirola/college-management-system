<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class General_model extends CI_Model
{
    public function get_roles($with_admin = true)
    {
        if (!$with_admin) {
            $this->db->where('role_type!=', 'ADMIN');
        }
        $res = $this->db->get('role')->result_array();
        if ($res) return $res;
    }

    public function get_course_categories()
    {
        $res = $this->db->get('category')->result_array();
        return $res;
    }
}
