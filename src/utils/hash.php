<?php

function get_password_hash($password): string {
    $options = [
        'cost'=> 13,
    ];
    return password_hash($password, PASSWORD_DEFAULT, $options);
}