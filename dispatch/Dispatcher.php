<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\MenuAction;
use iutnc\deefy\action\SignInAction;
use iutnc\deefy\InvalidPropertyValueException;
use iutnc\deefy\repository\DeefyRepository;

class Dispatcher
{
    private String $action;
    public function __construct(String|null $action)
    {
        if ($action == null) {$this->action = "default";}
        else {
            $this->action = $action;    
        }
    }

    /**
     * @throws InvalidPropertyValueException
     * @throws \Exception
     */
    public function run() : void {
        DeefyRepository::setConfig("/opt/lampp/htdocs/Config.ini");
        session_start();
        echo "<head> <link rel = \"stylesheet\" href=\"./CSSGPT.css\">" .
            "<title> SpotiDecoy </title></head>";

        echo '<div class="container">';
        echo '<header>';
        echo '<h1>SpotiDecoy';
        if (isset($_SESSION['User'])) {
            echo ' : ' . $_SESSION['UserEmail'];
        }
        echo '</h1><h3>Votre gestionnaire de musique<h3>';
        echo '</header>';

        if (!isset($_SESSION['User'])) {
            if ($this->action == 'register'){$this->renderPage((new AddUserAction())->execute());}
            else{$this->renderPage((new SignInAction())->execute());}

        }else {
            switch ($this->action) {
                case 'addPodcastTrack':
                    $this->renderPage((new AddPodcastTrackAction())->execute());
                    break;

                case 'addPlaylist':
                    $this->renderPage((new AddPlaylistAction())->execute());
                    break;

                case 'displayPlaylist':
                    $this->renderPage((new DisplayPlaylistAction())->execute());
                    break;

                case 'menu':
                    $this->renderPage((new MenuAction())->execute());
                    break;

                case 'signIn':
                    $this->renderPage((new SignInAction())->execute());
                    break;

                case 'register':
                    $this->renderPage((new AddUserAction())->execute());
                    break;

                case 'reset':
                    unset($_SESSION['User']);
                    unset($_SESSION['UserEmail']);
                    $this->renderPage((new SignInAction())->execute());
                    break;

                default:
                    $this->renderPage((new DefaultAction())->execute());

            }
        }

        echo '<nav>';
        echo '<h2>Menu Principal</h2>';
        echo '<div class="card-grid">';

        echo '<div class="card">';
        echo '<h3>🎵 Playlists</h3>';
        echo '<p class="track-artist">Créer et gérer vos playlists</p>';
        echo '<a href="?action=addPlaylist"><button>Gérer les playlists</button></a>';
        echo '</div>';

        echo '<div class="card">';
        echo '<h3>📋 Affichage</h3>';
        echo '<p class="track-artist">Voir vos playlists</p>';
        echo '<a href="?action=displayPlaylist"><button>Afficher une playlist</button></a>';
        echo '</div>';

        echo '<div class="card">';
        echo '<h3>🏠 Navigation</h3>';
        echo '<p class="track-artist">Retour au menu</p>';
        echo '<a href="?action=menu"><button>Aller au Menu</button></a>';
        echo '</div>';

        echo '<div class="card">';
        echo '<h3>👤 Utilisateurs</h3>';
        echo '<p class="track-artist">Gérer les comptes</p>';
        echo '<a href="?action=register"><button>Ajouter un Utilisateur</button></a>';
        echo '</div>';

        echo '<div class="card">';
        echo '<h3>LogOut</h3>';
        echo '<p class="track-artist">Se deconnecter</p>';
        echo '<a href="?action=reset"><button>Reinitialiser utilisateur</button></a>';
        echo '</div>';

        echo '<div class="card">';
        echo '<h3>LogIn</h3>';
        echo '<p class="track-artist">Se connecter à SpotiDecoy</p>';
        echo '<a href="?action=signIn"><button>Login</button></a>';
        echo '</div>';

        echo '</div>'; // fin card-grid
        echo '</nav>';
        echo '</div>'; // fin container

    }

    private function renderPage(string $html): void{
        echo $html;
    }


}