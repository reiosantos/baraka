<?php
/**
 * Created by PhpStorm.
 * User: ronaldsekitto
 * Date: 24/08/2018
 * Time: 21:03
 */

/**
 * @param $email
 * @return false|int
 */
function validate_email($email) {
    return preg_match_all('/^([a-zA-Z0-9_\-.]+)@([a-zA-Z0-9_\-.]+)\.([a-zA-Z]{2,5})$/', $email);
}

/**
 * @param $phone
 * @return false|int
 */
function validate_contact($phone) {
    return preg_match_all('/^[0-9]{10,13}$/', $phone);
}