<?php



namespace iutnc\deefy\TrackGestion\Track;

class PodcastTrack extends AudioTrack
{
    public function __construct(string $titre, string $nomFichier, string $genre = " ", int $duree = 0, string $artiste = " ", int $annee = 0)
    {
        parent::__construct($titre, $nomFichier, 0, "none", $genre, $duree, $artiste, $annee);
    }
}