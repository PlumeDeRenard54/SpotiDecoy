<?php

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\TrackGestion\Track\AudioTrack;
require_once 'Loader/AutoLoader.php';
(new iutnc\deefy\Loader\AutoLoader("iutnc\\deefy\\", __DIR__))->register();


$target_dir = __DIR__ . "/TrackGestion/Source/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


if(isset($_POST["submit"])) {
    $tmp = '';

    $check = str_ends_with($_FILES['fileToUpload']['name'], '.mp3');
    if($check !== false) {
        $tmp.= "File is a track</br>";
        $uploadOk = 1;
    } else {
        $tmp .= "File is not a Track</br>";
        $uploadOk = 0;
    }
    if (file_exists($target_file)) {
        $tmp .= "File already exists</br>";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $tmp .= "Your file was not uploaded</br>";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            DeefyRepository::setConfig("/opt/lampp/htdocs/Config.ini");;
            $repo = DeefyRepository::getInstance();
            $track = new AudioTrack($repo->getMaxIdTrack()+1, $_POST['trackName'], "http://localhost/SpotiDecoy/TrackGestion/Source/" . $_FILES["fileToUpload"]["name"],$_POST['numeroPiste']);
            DeefyRepository::getInstance()->addTrack($track);
            $tmp = '<head>
              <meta http-equiv="refresh" content="0; URL=index.php?action=menu" />
            </head>' . $tmp;
        } else {
            $tmp.= "There was an error uploading your file.</br>";
        }
    }

    echo $tmp;

    echo "Upload : " . $uploadOk . "</br>";
    echo "File : " . $_FILES['fileToUpload']['name'] . "</br>";
    echo "Where : " . $target_file . "</br>";
    echo "<a href='http://localhost/SpotiDecoy/index.php?action=addPodcastTrack'>Go back</a>";
}

