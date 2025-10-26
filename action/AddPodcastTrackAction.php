<?php

namespace iutnc\deefy\action;

use Exception;
use iutnc\deefy\InvalidPropertyValueException;
use iutnc\deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action
{

    /**
     * @throws InvalidPropertyValueException
     * @throws Exception
     */
    public function execute(): string
    {
        //Check si la playlist est selectionnée
        if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            return "pas de playlist selectionnée";
        }

        //Check si la playlist existe
        if (!isset(DeefyRepository::getInstance()->getAllPlaylists()[ $_POST['chosenPlaylist']])){
            return "Playlist Inexistante";
        }

        //Recupere les données
        $tmp = "";
        $currentPlaylist = DeefyRepository::getInstance()->getPlaylists()[$_POST['chosenPlaylist']];
        $tracks = DeefyRepository::getInstance()->getAllTracks();


        //Ajout d'une track
        if (isset($_POST['TrackID'])){
            DeefyRepository::getInstance()->createLink($currentPlaylist->id, $_POST['TrackID']);
            $tmp.= "<h3></br>Action effectuée</h3>";
        }

        //Form de demande d'ajout d'une track
        $tmp .= "Ajouter une Track : " .
            "<form action='?action=addPodcastTrack' method='post'></br>" ;

        //Liste des tracks disponibles
        foreach ($tracks as $key => $track) {
            $tmp .= "</br><button type='submit' name='TrackID' value = '$track->id' >$key</button>";
        }

        //Renvoi de la valeur de la playlist
        $tmp .= '<input type="hidden" name=\'chosenPlaylist\' value="' . $_POST['chosenPlaylist'] . '">';


        //Upload a file
        $tmp .= '</form></br></br><form action="Upload.php"  method="post" enctype="multipart/form-data">
                Select track to upload:
          <input type="file" name="fileToUpload" id="fileToUpload">
          <input type="text" name="trackName" placeholder="Titre" id="trackName">
          <input type="text" name="numeroPiste" placeholder="Numero de la Piste" id="numeroPiste">
          <input type="submit" value="Upload Image" name="submit">
        </form>';



        return $tmp;
    }
}