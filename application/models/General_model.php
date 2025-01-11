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

    public function get_courses($course_id = null)
    {
        $this->db->from('courses');
        $this->db->join('category', 'courses.course_category=category.category_id');

        if ($course_id) {
            $this->db->where('id', $course_id);
            $res = $this->db->get()->row_array();
        } else {
            $res = $this->db->get()->result_array();
        }

        if ($res) {
            return $res;
        }
    }

    public function get_content($content_id = null)
    {

        $this->db->from('content');

        if ($content_id) {
            $this->db->where('id', $content_id);
            $res = $this->db->get()->row_array();
        } else {
            $res = $this->db->get()->result_array();
        }

        if ($res) {
            return $res;
        }
    }
}
