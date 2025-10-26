<?php

namespace iutnc\deefy\TrackGestion\List;

use iutnc\deefy\TrackGestion\Track\AudioTrack;

class Playlist extends AudioList
{
    public function add(AudioTrack $track) : void {
        $this->tracks[] = $track;
        $this->size++;
        $this->longueur += $track->duree;
    }

    public function remove(AudioTrack $track) : void {
        $this->tracks = array_diff($this->tracks, [$track]);
        $this->size--;
        $this->longueur -= $track->duree;
    }

    public function addlist(array $list) : void {
        if ($list instanceof AudioList) {
            foreach ($list->tracks as $track) {
                $this->add($track);
            }
        }
    }




}