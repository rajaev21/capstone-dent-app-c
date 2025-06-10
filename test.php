<?php

$password = "password";

$hashed_password = password_hash($password, PASSWORD_BCRYPT);
password_verify($password, $result['password']);

echo $hashed_password;