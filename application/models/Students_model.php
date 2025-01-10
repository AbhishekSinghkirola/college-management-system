<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Students_model extends CI_Model
{
    public function add_category($insert_data)
    {
        return $this->db->insert('category', $insert_data);
    }

    public function get_students($student_id = null)
    {
        $this->db->from('cms_student');
        $this->db->join('cms_courses','cms_student.course=cms_courses.id');
        $this->db->join('category', 'courses.course_category=category.category_id');


        if ($student_id) {
            $this->db->where('student_id', $student_id);
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
}
