<?php
/*
Plugin Name: Custom JWT Fields
Description: Adds custom fields to JWT authentication response.
Version: 1.0
Author: Your Name
*/

add_filter('jwt_auth_token_before_dispatch', 'add_custom_fields_to_jwt', 10, 2);

function add_custom_fields_to_jwt($data, $user) {
    // Ensure ACF is available
    if (function_exists('get_field')) {
        // Get custom fields
        $state = get_field('state', 'user_' . $user->ID);
        $school = get_field('school', 'user_' . $user->ID);
        $roles = $user->roles;
        $role = !empty($roles) ? $roles[0] : '';

        $data['role'] = $role;
        // Add fields to the JWT response
//        $data['acf'] = array(
//            'state' => $state,
//            'school' => $school,
//        );
        $data['state'] = $state;
        $data['school'] = $school;
    }

    return $data;
}