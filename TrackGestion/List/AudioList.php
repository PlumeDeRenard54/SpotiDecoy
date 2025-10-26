<?php

namespace iutnc\deefy\TrackGestion\List;

use iutnc\deefy\TrackGestion\Track\AudioTrack;

class AudioList
{
    protected array $tracks;
    protected int $size;
    protected String $nom;
    protected int $longueur;

    protected int $id;


    /**
     * @throws \Exception
     */
    public function __construct(int $id,String $nom, array $tracks = []){
        $this->id = $id;
        $this->nom = $nom;
        $this->tracks = $tracks;
        $this->size = count($tracks);
        $l = 0;
        foreach ($tracks as $track){
            if ($track instanceof AudioTrack){
                $l += $track->getDuree();
            }
            else{throw new \Exception($track->duree);}
        }
        $this->longueur = $l;
    }


    public function __get(string $propertyName): mixed
    {
        return $this->$propertyName;
    }

    public function __toString() : string{
        $text = "Nom : " . $this->nom . " - " . $this->size . " tracks - " . $this->longueur . "s\n";
        foreach ($this->tracks as $track){
            $text .= $track . "\n";
        }
        return $text;
    }


}