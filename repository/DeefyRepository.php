<?php

namespace iutnc\deefy\repository;

use Exception;
use iutnc\deefy\InvalidPropertyValueException;
use iutnc\deefy\TrackGestion\List\AudioList;
use iutnc\deefy\TrackGestion\List\Playlist;
use iutnc\deefy\TrackGestion\Track\AlbumTrack;
use iutnc\deefy\TrackGestion\Track\AudioTrack;
use PDO;

class DeefyRepository
{
    private static ?array $config = null;
    private static ?DeefyRepository $instance = null;

    public PDO $pdo;

    private function __construct() {
        //mysql:host=localhost;dbname=SpotiDecoy
        $dsn = DeefyRepository::$config['driver'] . ':host=' .DeefyRepository::$config['host'] . ';dbname=' . DeefyRepository::$config['database'];
        $this->pdo = new PDO($dsn, DeefyRepository::$config['username'], DeefyRepository::$config['password']);
    }

    /**
     * @throws Exception
     */
    public static function setConfig (String $file) : void {
        if (file_exists($file)) {
            DeefyRepository::$config = parse_ini_file($file);
        }else{
            throw new Exception("Config file not found");
        }
    }

    /**
     * @throws Exception
     */
    public static function getInstance() : DeefyRepository {
        if (DeefyRepository::$config == null) {
            throw new Exception("Config file not found");
        }
        else {
            if (DeefyRepository::$instance == null) {
                DeefyRepository::$instance = new DeefyRepository();
            }
            return DeefyRepository::$instance;
        }
    }

    public function getMaxIdPlaylist(): int
    {
        $query = "SELECT MAX(id) as id FROM playlist";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'];


    }
    public function getMaxIdTrack(): int{
        $query = "SELECT MAX(id) as id FROM track";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'];
    }

    /**
     * @throws Exception
     */
    private function base2Playlist(int $id, String $nomPlaylist) : Playlist{
        $query = "SELECT playlist.nom nom ,track.id idtrack FROM playlist ".
            "INNER JOIN playlist2track ON playlist.id = playlist2track.id_pl ".
            "INNER JOIN track ON track.id = playlist2track.id_track " .
            "WHERE playlist.id = $id " .
            "ORDER BY playlist2track.no_piste_dans_liste";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $retourne = new Playlist($id,$nomPlaylist);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $retourne->add($this->base2Track($row['idtrack']));
        }

        return $retourne;
    }

    /**
     * @throws InvalidPropertyValueException
     */
    private function base2Track(int $id) : AudioTrack{
        $query = "SELECT track.titre titre,  track.filename nomFichier, track.titre_album album, track.numero_album numero, track.genre genre, track.duree duree, track.artiste_album artiste,track.annee_album annee FROM track WHERE id = " . $id;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new AudioTrack($id,$row['titre'], $row['nomFichier'],$row['numero'], $row['album'], $row['genre'], $row['duree'], $row['artiste'], $row['annee']);
    }

    public function addPlaylist(String $playlist) : void{
        $nexID = $this->getMaxIdPlaylist()+1;
        $query = "INSERT INTO playlist VALUES ($nexID,'$playlist' )";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $query = "INSERT INTO user2playlist VALUES ($_SESSION[User],$nexID)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    }

    public function createLink(int $playlist,int $track) : void{
        $len = count($this->getAllPlaylists()[$playlist]->tracks)+1;
        if ($this->isLinkPresent($playlist,$track)){print "Track already in playlist";}
        else {
            $query = "INSERT INTO playlist2track VALUES ($playlist , $track , $len )";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
    }

    public function addTrack(AudioTrack $track) : void{
        if ($track instanceof AlbumTrack){
            $type = 'A';
        }else{$type = 'P';}

        $query = "INSERT INTO track VALUES ($track->id,'$track->titre','$track->genre',$track->duree" .
            ",'$track->nomFichier','$type','$track->artiste','$track->album',$track->annee ,$track->numeroPiste , 'dummy'".
             ", STR_TO_DATE('2023/12/10', '%Y/%m/%d')  )";

        print $query;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    }

    public function getAllTracks() : array{
        $query = "SELECT * FROM track ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $retourne = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $retourne[$row['titre']] = $this->base2Track($row['id']);
        }
        return $retourne;
    }

    /**
     * @throws Exception
     */
    public function getPlaylists() : array{
        $query = "SELECT * FROM playlist INNER JOIN user2playlist ON playlist.id = user2playlist.id_pl WHERE user2playlist.id_user = $_SESSION[User]";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $retourne = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $retourne[$row['id']] = $this->base2Playlist($row['id'], $row['nom']);
        }

        return $retourne;
    }

    /**
     * @throws Exception
     */
    public function getUser(String $userEmail) : array{
        $query = "SELECT email,passwd,id FROM User WHERE email = '" . $userEmail . "'";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row){
            throw new Exception("User not found");
        }else {
            return array(
                'email' => $row['email'],
                'password' => $row['passwd'],
                'id' => $row['id']);
        }
    }

    /**
     * @throws Exception
     */
    public function addUser(String $email, String $password) : void{
        //Test Longueur password
        if (strlen($password) < 10 ){throw new Exception("Password too short");}
        //Test si l'user existe
        try {
            $this->getUser($email);
            throw new Exception("User already exists");
        }catch (Exception $e){}

        $password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO User VALUES (null, '" . $email . "', '" . $password . "', 1)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    }

    public function getAllPlaylists() : array{
        $query = "SELECT * FROM playlist";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $retourne = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $retourne[$row['id']] = $this->base2Playlist($row['id'], $row['nom']);
        }

        return $retourne;
    }

    public function isLinkPresent(int $playlist,int $track) : bool{
        $query = "SELECT * FROM playlist2track 
                WHERE id_pl=$playlist AND id_track=$track";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        if (!$stmt->fetch()){
            return false;
        }else{
            return true;
        }
    }

}