<?php

namespace iutnc\deefy\TrackGestion\Track;
use iutnc\deefy\InvalidPropertyNameException;
use iutnc\deefy\InvalidPropertyValueException;

class AudioTrack
{
    protected ?string $titre;
    protected ?string $artiste;
    protected ?string $album;
    protected ?int $annee;
    protected ?int $numeroPiste;
    protected ?string $genre;
    protected ?int $duree;
    protected ?string $nomFichier;

    protected int $id;

    /**
     * @throws InvalidPropertyValueException
     */
    public function __construct(int $id,string $titre, string $nomFichier, int|null $numeroPiste, string|null $album = "Dummy", string|null $genre = " ", int|null $duree = 0, string|null $artiste = " ", int|null $annee = 0)
    {
        $this->titre = $titre;
        $this->nomFichier = $nomFichier;
        $this->album = $album;
        $this->numeroPiste = $numeroPiste;
        $this->genre = $genre;
        if ($duree < 0) throw new InvalidPropertyValueException();
        $this->duree = $duree;
        $this->artiste = $artiste;
        $this->annee = $annee;
        $this->id = $id;
    }

    public function __toString()
    {
        return json_encode(get_object_vars($this));
    }


    /**
     * @param string $propertyName
     * @return mixed
     * @throws InvalidPropertyNameException
     */
    public function __get(string $propertyName): mixed
    {
        try {
            if ($propertyName == "titre" || $propertyName == "artiste" || $propertyName == "album" || $propertyName == "genre" || $propertyName == "annee" || $propertyName == "numeroPiste" || $propertyName == "nomFichier" || $propertyName == "duree" || $propertyName == "id")
            return $this->$propertyName;

        }catch (\Exception $e){
            throw new InvalidPropertyNameException();
        }
        return null;
    }

    public function getDuree() : int{
        return $this->duree;
    }


}