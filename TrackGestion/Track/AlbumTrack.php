<?php


namespace iutnc\deefy\TrackGestion\Track;
class AlbumTrack extends AudioTrack
{
    public function __construct(string $titre, string $nomFichier, string $album, int $numeroPiste, string $genre = " ", int $duree = 0, string $artiste = " ", int $annee = 0)
    {
        parent::__construct($titre, $nomFichier,$numeroPiste, $album , $genre, $duree, $artiste, $annee);

    }
}