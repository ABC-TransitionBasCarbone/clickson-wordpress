<?php
/*
Plugin Name: Custom JWT Fields
Description: Adds custom fields to JWT authentication response.
Version: 1.0
Author: Khalil Elhazmiri
*/

add_filter('jwt_auth_token_before_dispatch', 'add_custom_fields_to_jwt', 10, 2);

function add_custom_fields_to_jwt($data, $user)
{
    if (function_exists('get_field')) {

        $state = get_field('state', 'user_' . $user->ID);
        $school = get_field('school', 'user_' . $user->ID);
        $city = get_field('city', 'user_' . $user->ID);
        $zipcode = get_field('zip_code', 'user_' . $user->ID);
        $number_of_students = get_field('number_of_students', 'user_' . $user->ID);
        $number_of_staff = get_field('number_of_staff', 'user_' . $user->ID);
        $construction_year = get_field('construction_year', 'user_' . $user->ID);
        $school_address = get_field('school_address', 'user_' . $user->ID);
        $roles = $user->roles;
        $role = !empty($roles) ? $roles[0] : '';

        $data['role'] = $role;
        $data['state'] = $state;
        $data['school'] = $school;
        $data['city'] = $city;
        $data['zip_code'] = $zipcode;
        $data['number_of_students'] = $number_of_students;
        $data['number_of_staff'] = $number_of_staff;
        $data['construction_year'] = $construction_year;
        $data['school_address'] = $school_address;
    }

    return $data;
}

add_filter('jwt_auth_expire', 'add_remember_me', 10, 2);
function add_remember_me($expiration, $user)
{
    $input = file_get_contents('php://input');

    $input_data = json_decode($input, true);

    if (isset($input_data['rememberMe']) && $input_data['rememberMe'] === true) {
        $expiration = time() + (100 * YEAR_IN_SECONDS);
    } else {
        $expiration = time() + HOUR_IN_SECONDS;
    }

    return $expiration;
}

function send_welcome_email_after_user_registration($user_id) {

    $user = get_userdata($user_id);
    $user_email = $user->user_email;
    $user_name = $user->display_name;

    $subject = 'Welcome';
    $message = "Hi {$user_name},\n\nWelcome abroad";

    $headers = array('Content-Type: text/plain; charset=UTF-8');

    wp_mail($user_email, $subject, $message, $headers);
}

add_action('user_register', 'send_welcome_email_after_user_registration');