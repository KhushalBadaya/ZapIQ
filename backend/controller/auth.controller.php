// in this file the code for login logout signup and updateprofile function will be created using php 
<?php
require_once '../lib/utils.php';
require_once '../models/User.php';

function signup($req, $res) {
    $fullName = $req['body']['fullName'] ?? null;
    $email    = $req['body']['email']    ?? null;
    $password = $req['body']['password'] ?? null;

    try {
        if (!$fullName || !$email || !$password) {
            return $res->status(400)->json(['message' => 'All fields required']);
        }

        if (strlen($password) < 6) {
            return $res->status(400)->json(['message' => 'Password should be 6 characters or more']);
        }

        $user = User::findOne(['email' => $email]);
        if ($user) {
            return $res->status(400)->json(['message' => 'Email Already Existed']);
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

        $newUser = new User([
            'fullName' => $fullName,
            'email'    => $email,
            'password' => $hashedPassword,
        ]);

        $savedUser = $newUser->save();
        generateToken($savedUser->_id, $res);

        return $res->status(201)->json([
            '_id'        => $savedUser->_id,
            'fullName'   => $savedUser->fullName,
            'email'      => $savedUser->email,
            'profilePic' => $savedUser->profilePic,
        ]);

    } catch (Exception $e) {
        error_log('Signup Error: ' . $e->getMessage());
        return $res->status(500)->json(['message' => 'Internal Server Error']);
    }
}