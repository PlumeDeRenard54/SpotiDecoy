<?php

namespace iutnc\deefy\TrackGestion\List;

use TD4\List\AudioList;

class Album extends AudioList
{
    private String $artiste;
    private int $annee;

    public function __construct(String $nom, String $artiste, int $annee, array $tracks = []){
        parent::__construct($nom, $tracks);
        $this->artiste = $artiste;
        $this->annee = $annee;
    }

    public function __toString() : string{
        $text = "Artiste : " . $this->artiste . "(" . $this->annee . ")\n";
        return $text . parent::__toString();

    }
}