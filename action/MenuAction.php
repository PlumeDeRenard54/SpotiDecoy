<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;

class MenuAction extends Action
{

    public function execute(): string
    {
        return "<h1>Welcome to the menu</h1>";
    }
}