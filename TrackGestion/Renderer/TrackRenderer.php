<?php

namespace iutnc\deefy\TrackGestion\Renderer;

class TrackRenderer extends Renderer
{

    function render(int $selector): string
    {
        return /*match ($selector) {
            0 => $this->Track->titre . " - by " . $this->Track->artiste
                . " (from " . $this->Track->album . ' ) <br> <audio controls src="' . $this->Track->nomFichier . ' "> </audio>',
            1 => $this->Track->numeroPiste . " - " . $this->Track->titre . " - by " . $this->Track->artiste
                . " (from " . $this->Track->album . " ) - " . $this->Track->duree . 's  
                <br> <audio controls src="' . $this->Track->nomFichier . ' "> </audio>',
            2 => $this->Track->numeroPiste . " - " . $this->Track->titre . " - by " . $this->Track->artiste
                . " (from " . $this->Track->album . ", " . $this->Track->annee . " ) - " . $this->Track->duree .
                "s : " . $this->Track->genre .
                '<br> <audio controls src="' . $this->Track->nomFichier . ' "> </audio>',
            default => "Error",
        };*/

        $tmp = '<div class="track-player">' .
        '  <div class="track-player-header">' .
        '    <div class="track-player-cover">🎵</div>' .
        '    <div class="track-player-info">' .
        '      <div class="track-player-title">' . $this->Track->track . '</div>' .
        '      <div class="track-player-artist">' . $this->Track->artiste . '</div>' .
        '    </div>' .
            '  </div>' .
        '  <div class="track-player-controls">' .
        '    <audio controls>' .
        '      <source src="' . $this->Track->nomFichier  . '" type="audio/mpeg">' .
        '    </audio>' .
        '  </div>' .
         '</div>';

        return $tmp;
    }
}
