<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Courses_model extends CI_Model
{
    public function add_category($insert_data)
    {
        return $this->db->insert('category', $insert_data);
    }

    public function get_categories($cat_id = null)
    {
        $this->db->from('category');

        if ($cat_id) {
            $this->db->where('category_id', $cat_id);
            $res = $this->db->get()->row_array();
        } else {
            $res = $this->db->get()->result_array();
        }

        if ($res) {
            return $res;
        }
    }

    public function delete_category($cat_id)
    {
        if ($cat_id) {
            return $this->db->delete('category', ['category_id' => $cat_id]);
        }
    }

    public function update_category($update_array)
    {
        $cat_id = $update_array['cat_id'];

        if ($cat_id) {
            unset($update_array['cat_id']);
            return $this->db->update('category', $update_array, ['category_id' => $cat_id]);
        }
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

    public function add_course($insert_data)
    {
        return $this->db->insert('courses', $insert_data);
    }

    public function delete_course($course_id)
    {
        if ($course_id) {
            return $this->db->delete('courses', ['id' => $course_id]);
        }
    }


    public function update_course($update_array)
    {
        $id = $update_array['id'];

        if ($id) {
            unset($update_array['id']);
            return $this->db->update('courses', $update_array, ['id' => $id]);
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

    public function add_content($insert_data)
    {
        return $this->db->insert('content', $insert_data);
    }

    public function update_content($update_array)
    {
        $id = $update_array['id'];

        if ($id) {
            unset($update_array['id']);
            return $this->db->update('content', $update_array, ['id' => $id]);
        }
    }

    public function delete_content($content_id)
    {
        if ($content_id) {
            return $this->db->delete('content', ['id' => $content_id]);
        }
    }


    public function get_assigned_content($assigned_id = null)
    {

        $this->db->select('assigned_content.id as assigned_id, courses.course_name, content.name as content_name, assigned_content.course_id, assigned_content.content_id');
        $this->db->from('assigned_content');
        $this->db->join('courses', 'assigned_content.course_id=courses.id');
        $this->db->join('content', 'assigned_content.content_id=content.id');

        if ($assigned_id) {
            $this->db->where('assigned_content.id', $assigned_id);
            $res = $this->db->get()->row_array();
        } else {
            $res = $this->db->get()->result_array();
        }


        if ($res) {
            return $res;
        }
    }

    public function save_assigned_content($insert_data)
    {
        return $this->db->insert('assigned_content', $insert_data);
    }

    public function update_assigned_content($update_array)
    {
        $id = $update_array['id'];

        if ($id) {
            unset($update_array['id']);
            return $this->db->update('assigned_content', $update_array, ['id' => $id]);
        }
    }

    public function delete_assigend_content($assigned_id)
    {
        if ($assigned_id) {
            return $this->db->delete('assigned_content', ['id' => $assigned_id]);
        }
    }
}
