<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\auth\AuthnProvider;

class AddUserAction extends Action
{

    public function execute(): string
    {
        $tmp = "<div class='card'><h3>";
        if (($_SERVER['REQUEST_METHOD']) == 'GET') {
            $tmp .= "<form action='?action=register' method='post'>" .
                "<input type='text' name='newUName' placeholder='Email' >" .
                "<input type='text' name='newUEmail' placeholder='Password' >" .
                "<input type=\"submit\" value=\"Register\" />".
                "</form>";
        }else{
            try {
                AuthnProvider::register($_POST['newUName'], $_POST['newUEmail']);
                $tmp .= "L'enregistrement de l'utilisateur s'est passé sans problème";
            }catch (\Exception $e){
                $tmp .= $e->getMessage();
            }
        }

        return $tmp . "</h3></div>";
    }
}