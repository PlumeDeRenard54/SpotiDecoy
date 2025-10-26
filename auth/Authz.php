<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\TrackGestion\List\Playlist;

class Authz
{
    public static function checkRole()
    {
        $database = DeefyRepository::getInstance();
        $query = "SELECT role FROM User WHERE id=$_SESSION[User]";
        $stmt = $database->pdo->prepare($query);
        $stmt->execute();
        return FactoryRoles::factory($stmt->fetch()['role']);
    }

    /**
     * @throws \Exception
     */
    public static function isProprio(Playlist $playlist):bool{
        $database = DeefyRepository::getInstance();
        $query = "SELECT id_user user FROM user2playlist WHERE id_pl=$playlist->id";
        $stmt = $database->pdo->prepare($query);
        $stmt->execute();
        return ($stmt->fetch()['user']==$_SESSION['User']);
    }
}