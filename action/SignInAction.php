<?php

namespace iutnc\deefy\action;

use Exception;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\repository\DeefyRepository;

class SignInAction extends Action
{

    /**
     * @throws Exception
     */
    public function execute(): string
    {
        $tmp = "<div class='card'><h3>";
        if (($_SERVER['REQUEST_METHOD']) == 'GET') {
            if (!isset($_SESSION['User'])) {
                $tmp .= "<form action='?action=signIn' method='post'>" .
                    "<input type='text' name='Email' placeholder='Email'>" .
                    "<input type='text' name='Password' placeholder='Password'>" .
                    "<input type=\"submit\" value=\"Login\" />" .
                    "</form>" ;
                }
            else {
                $tmp .= "<form action='?action=signIn' method='post'>" .
                    "<button type='submit' name='LogOut' value='logOut' >LogOut</button>" .
                    "</form>";
            }
        }else{
            if (isset($_POST['LogOut'])){
                unset($_SESSION['User']);
                unset($_SESSION['UserEmail']);
            }else {
                try {
                    if (AuthnProvider::signIn($_POST['Email'], $_POST['Password'])) {
                        $_SESSION['User'] = DeefyRepository::getInstance()->getUser($_POST['Email'])['id'];
                        $_SESSION["UserEmail"] = $_POST['Email'];
                        $tmp .= "Successful login at " . $_POST['Email'];
                    }
                } catch (Exception $e) {
                    $tmp .= "Erreur de logIn";
                }
            }
        }

        return $tmp .  "</h3></div>";
    }
}