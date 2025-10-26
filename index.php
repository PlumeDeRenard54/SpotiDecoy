<?php

use iutnc\deefy\dispatch\Dispatcher;
require_once 'Loader/AutoLoader.php';
(new iutnc\deefy\Loader\AutoLoader("iutnc\\deefy\\", __DIR__))->register();
if (!isset($_GET['action'])) {$_GET['action'] = "menu";}
(new Dispatcher($_GET['action']))->run();
