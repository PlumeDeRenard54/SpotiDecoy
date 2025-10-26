<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\repository\DeefyRepository;

class AuthnProvider
{
    /**
     * @throws \Exception
     */
    public static function signIn(String $email, String $password):bool{
        $repo = DeefyRepository::getInstance();
        $user = $repo->getUser($email);
        return password_verify($password, $user['password']);
    }

    /**
     * @throws \Exception
     */
    public static function register(String $email, String $password):void{
        $repo = DeefyRepository::getInstance();
        $repo->addUser($email, $password);
    }

}