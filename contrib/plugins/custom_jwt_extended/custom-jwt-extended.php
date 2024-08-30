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
    // Ensure ACF is available
    if (function_exists('get_field')) {
        // Get custom fields
        $state = get_field('state', 'user_' . $user->ID);
        $school = get_field('school', 'user_' . $user->ID);
        $city = get_field('city', 'user_' . $user->ID);
        $zipcode = get_field('zip_code', 'user_' . $user->ID);
        $roles = $user->roles;
        $role = !empty($roles) ? $roles[0] : '';

        $data['role'] = $role;
        // Add fields to the JWT response
        $data['state'] = $state;
        $data['school'] = $school;
        $data['city'] = $city;
        $data['zip_code'] = $zipcode;
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