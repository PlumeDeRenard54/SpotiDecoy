<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

class AddPlaylistAction extends Action
{

    /**
     * @throws \Exception
     */
    public function execute(): string
    {
        $tmp = "Creer une playlist : " .
            "<form action='?action=addPlaylist' method='post'>" .
            "<input type='text' name='newP' placeholder='Nom de la playlist' >" .
            "<input type=\"submit\" value=\"Submit\" />";

        //Ajout de la playlist
        if (isset($_POST['newP'])){
            DeefyRepository::getInstance()->addPlaylist($_POST['newP']);
        }

        //Liste des playlists
        $tmp .= "</form>";
        $database = DeefyRepository::getInstance()->getPlaylists();
        if (count($database) > 0) {
            $tmp .= "</br>Liste des playlists : <form method='post'>";
            foreach ($database as $playlist) {
                $tmp .= "</br>" . "<button type='submit' formaction='?action=addPodcastTrack' name='chosenPlaylist' value='".$playlist->id ."' >" . $playlist->nom . "</a></button>" ;
            }
            $tmp .= "</form>";
        }
        else{
            $tmp .= "</br>Aucune playlist";
        }

        return $tmp;
    }
}