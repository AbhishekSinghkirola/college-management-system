<?php

/* ---------------------- Function To Die and Dump Data --------------------- */
if (!function_exists('dd')) {
    function dd($array)
    {
        echo "<pre>";
        print_r($array);
        die;
    }
}

/* -------------------- Function To Dump Data Without Die ------------------- */
if (!function_exists('dnd')) {
    function dnd($array)
    {
        echo "<pre>";
        print_r($array);
    }
}

/* ----------------------- Function To Fetch All Roles ---------------------- */
if (!function_exists('get_roles')) {
    function get_roles($with_admin)
    {
        $CI = &get_instance();

        $roles = $CI->general_md->get_roles($with_admin);
        return $roles;
    }
}

/* ------------------------- Function Holding Regex ------------------------- */
if (!function_exists('regex_for_validate')) {
    function regex_for_validate($validate_for = null)
    {
        $arr = array();
        $arr = array(
            "amount" => array("has_regex" => 1, "regex" => "^\s*(?=.*[1-9])\d*(?:\.\d{1,2})?\s*$", "min" => 1, "max" => 20),
            "email" => array('has_regex' => 1, 'min' => 4, 'max' => 100, 'regex' => "^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$"),
            "mob" => array('has_regex' => 1, 'min' => 10, 'max' => '10', 'regex' => "^[6-9][0-9]{9}$"),
            "straddr" => array('has_regex' => 0, 'min' => 10, 'max' => 200, "regex" => ""),
            "strcity" => array('has_regex' => 0, 'min' => 2, 'max' => 50, "regex" => ""),
            "strstate" => array('has_regex' => 0, 'min' => 2, 'max' => 50, "regex" => ""),
            "strpin" => array('has_regex' => 1, 'min' => 6, 'max' => 6, 'regex' => "^[1-9][0-9]{5}$"),
            "strpass" => array('has_regex' => 0, 'min' => 6, 'max' => 20, "regex" => ""),
            "strname" => array('has_regex' => 0, 'min' => 2, 'max' => 100, "regex" => ""),
            "pan" => array('has_regex' => 1, 'min' => 10, 'max' => 10, 'regex' => "^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$"),
            "dob" => array('has_regex' => 1, 'min' => 10, 'max' => 10, 'regex' => "^(0?[1-9]|[12][0-9]|3[01])[\/](0?[1-9]|1[012])[\/]\d{4}$"),
            "name" => array('has_regex' => 0, 'min' => 2, 'max' => 50, "regex" => ""),
            "timeWOS" => array("has_regex" => 1, "min" => 5, "max" => 5, "regex" => "^([01]?[0-9]|2[0-3]):[0-5][0-9]$"),
            "dateymd" => array("has_regex" => 1, "min" => 8, "max" => 10, "regex" => "^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$"),
            "dateymdhis" => array("has_regex" => 1, "min" => 14, "max" => 17, "regex" => "^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01]) (00|[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9]):([0-9]|[0-5][0-9])$"),
            "aadhaar" => array('has_regex' => 1, 'min' => 12, 'max' => 12, 'regex' => "^[0-9]{12}$"),
            "urls" => array('has_regex' => 1, 'min' => 4, 'max' => 250, 'regex' => "^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&'\(\)\*\+,;=.]+$"),
            "otp" => array('has_regex' => 1, 'min' => 6, 'max' => 6, 'regex' => "^[0-9]{6}$"),
            "dateymdhi" => array("has_regex" => 1, "min" => 14, "max" => 17, "regex" => "^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01]) (00|[0][0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$"),
        );

        if ($validate_for) {
            if (isset($arr[$validate_for])) {
                return $arr[$validate_for];
            } else {
                return false;
            }
        }

        return $arr;
    }
}

/* --------------------- Function to Validate Parameters -------------------- */
if (!function_exists('validate_field')) {
    function validate_field($tobe_validated, $validate_by, $search_in = "INPUT")
    {
        if (is_string($validate_by)) {
            if ($search_in == "INPUT") {
                $regex = regex_for_validate($validate_by);
                if ($regex) {
                    if ($regex['has_regex'] == 1) {
                        if (preg_match("/" . $regex['regex'] . "/", $tobe_validated)) {
                            return true;
                        }
                    } else {
                        if (strlen($tobe_validated) >= $regex['min'] && strlen($tobe_validated) <= $regex['max']) {
                            return true;
                        }
                    }
                }
            } elseif ($search_in == "SELECT") {
                $status_array = common_status_array($validate_by);
                $validate_in_array = $status_array ? $status_array : null;
                if ($validate_in_array) {
                    if (in_array($tobe_validated, array_keys($validate_in_array))) {
                        return true;
                    }
                }
            }
        }
    }
}

/* -------- Function Of Holding Common Statuses USed Accross Website -------- */
if (!function_exists('common_status_array')) {
    function common_status_array($validate_by = null)
    {
        $array = array(
            "account_status" => array('ACTIVE' => 'ACTIVE', 'PENDING' => 'PENDING', 'BLOCKED' => 'BLOCKED', 'INACTIVE' => 'INACTIVE'),

            "gender" => array('FEMALE' => 'Female', 'MALE' => 'Male', 'OTHERS' => 'Others')
        );

        if ($validate_by) {
            if (isset($array[$validate_by])) {
                return $array[$validate_by];
            } else {
                return false;
            }
        }

        return $array;
    }
}

/* ------------------- Function To Get Courses Categories ------------------- */
if (!function_exists(('get_course_categories'))) {
    function get_course_categories()
    {
        $CI = &get_instance();

        $course_categories = $CI->general_md->get_course_categories();
        return $course_categories;
    }
}

/* ------------------------- Function to get courses ------------------------ */
if (!function_exists(('get_courses'))) {
    function get_courses()
    {
        $CI = &get_instance();

        $courses = $CI->general_md->get_courses();

        return $courses;
    }
}

/* ------------------------- Function to get content ------------------------ */
if (!function_exists(('get_content'))) {
    function get_content()
    {
        $CI = &get_instance();

        $courses = $CI->general_md->get_content();
        return $courses;
    }
}

/* --------------------- Function to get logged in user --------------------- */
if (!function_exists(('get_logged_in_user'))) {

    function get_logged_in_user()
    {
        $CI = &get_instance();

        $session = $CI->session->userdata('cms_session');
        $user = $CI->general_md->get_user($session['user_id'], $session['role_id']);

        return $user;
    }
    
}

/* -------------------------- Sorting Dates In Desc ------------------------- */
function compareByTimeStamp($time1, $time2)
{
    if (strtotime($time1) < strtotime($time2)) {
        return 1;
    } elseif (strtotime($time1) > strtotime($time2)) {
        return -1;
    } else {
        return 0;
    }
}
