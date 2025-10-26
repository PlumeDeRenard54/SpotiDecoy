<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\auth\Roles;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\TrackGestion\Renderer\AudioListRenderer;

class DisplayPlaylistAction extends Action
{

    /**
     * @throws \Exception
     */
    public function execute(): string
    {

        $tmp = "Playlist : </br>";
        $database = DeefyRepository::getInstance()->getAllPlaylists();

        if (!isset($_GET['curPlaylist'])) {
            if (count($database) > 0) {
                foreach ($database as $playlist) {
                    if (Authz::checkRole() == "ADMIN" || Authz::isProprio($playlist)) {
                        $tmp .= "<a href='?action=displayPlaylist&curPlaylist=$playlist->id'><button>$playlist->nom ($playlist->longueur)</button></a>";
                    }
                }
            }
            else{$tmp .= "Aucune playlist";}
        }else{
            if (Authz::checkRole() == "ADMIN" || Authz::isProprio($database[$_GET['curPlaylist']])) {
                $tmp .= (new AudioListRenderer($database[$_GET['curPlaylist']]))->render(0);
            }else{
                $tmp .= "<h1> Vous n'êtes pas autorisé à acceder à cette playlist </h1>";
            }
        }




        return $tmp;
    }
}