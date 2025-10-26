<?php

namespace iutnc\deefy\TrackGestion\Renderer;
use iutnc\deefy\TrackGestion\Track\AudioTrack;

abstract class Renderer
{
    protected AudioTrack $Track;

    public function __construct(AudioTrack $albumTrack)
    {
        $this->Track = $albumTrack;
    }

    public abstract function render(int $selector): string;
}