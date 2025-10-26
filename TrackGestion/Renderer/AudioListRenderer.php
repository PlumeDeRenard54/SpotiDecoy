<?php
namespace iutnc\deefy\TrackGestion\Renderer;
use iutnc\deefy\TrackGestion\List\AudioList;
use iutnc\deefy\TrackGestion\Renderer\PodcastRenderer;
use iutnc\deefy\TrackGestion\Renderer\Renderer;
use iutnc\deefy\TrackGestion\Renderer\TrackRenderer;
use iutnc\deefy\TrackGestion\Track\AlbumTrack;

class AudioListRenderer extends Renderer
{
    protected AudioList $list;

    public function __construct(AudioList $list)
    {
        $this->list = $list;
    }
    public function render(int $selector): string
    {
        $retourne = "<h2>" . $this->list->nom . " (" . $this->list->longueur . ")</h2></br>";
        foreach ($this->list->tracks as $track) {
            if ($track instanceof AlbumTrack) {
                $retourne .= (new TrackRenderer($track))->render($selector) . "</br>";
            } else {
                $retourne .= (new PodcastRenderer($track))->render($selector) . "</br>";
            }
        }
        return $retourne;
    }
}
