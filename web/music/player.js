soundManager.url = 'swf/'; // Chemin du dossier "swf"
soundManager.debugMode = false; // DebugMode désactivé
soundManager.waitForWindowLoad = true; // Attendre le chargement de la page pour charger soundManager
soundManager.onerror = function () { // En cas d'erreur ...
    alert("SoundManager 2 a rencontré une erreur."); // ... on affiche un message à l'utilisateur
}

var playlist = []; // Tableau pour contenir la playlist
var current = 0; // Indice du son courant (dans playlist[]);
var act_vol = soundManager.defaultOptions.volume; // Variable pour le volume actuel, initialisée au volume par défaut de SoundManager
var loop = "none"; // Mode de répétition séléctionné
var next = false; // Indicateurs booléens du clic sur Previous et Next
var prev = false;
var listala = document.querySelector('#playlist').dataset.id;
soundManager.onload = function () {//

    var barre = document.getElementById("barre"); // On récupère la barre d'avancement,
    var chargement = document.getElementById("chargement"); // la barre de chargement,
    var cpt = document.getElementById("cpt"); // le compteur de secondes,
    var vol = document.getElementById("volume"); // la barre de volume,
    var list = document.getElementById("playlist").getElementsByTagName("a"); // et les différents liens <a> de la playlist (les titres).
    for (var i = 0; i < list.length; i++) { // On parcourt la liste
        list[i].rel = i; // On renseigne un indice arbitraire dans l'attribut rel de chaque <a>
        var sp = document.createElement("span"); // On crée un élément span
        list[i].parentNode.insertBefore(sp, list[i]); // qu'on ajoute avant chaque <a> de la liste
        list[i].onclick = function () { // On crée une fonction onclick sur chaque lien :
            soundManager.stopAll(); // On stoppe tous les sons
            current = this.rel; // On mets en "son courant" celui cliqué (grâce à l'indice du rel)
            lire_current(); // On appelle la fonction lire_current() pour lancer la lecture
            return false; // On renvoie faux pour empêcher les liens de rediriger (ils ne servent qu'aux sans-js)
        };
        (function (titre) {
            playlist[i] = soundManager.createSound( // On crée un son pour chaque lien de la playlist
                {
                    id: "piste" + i, // Id arbitraire : piste0, piste1, etc.
                    url: list[i].href, // L'url de chaque son est le href de chaque lien
                    whileloading: function () { // Pendant le chargement :
                        chargement.style.backgroundPosition = -420 + (this.bytesLoaded / this.bytesTotal * 420) + "px 50%"; // On ajuste le background de la barre de chargement
                        if (this.bytesTotal >= 1048576) { // Si taille totale >= 1 Mo
                            taille_totale = parseInt(this.bytesTotal / 1048576 * 100) / 100 + " Mo";
                            taille_chargee = parseInt(this.bytesLoaded / 1048576 * 100) / 100 + " Mo";
                        } else if (this.bytesTotal >= 1024) { // Sinon si taille totale >= 1 Ko
                            taille_totale = parseInt(this.bytesTotal / 1024 * 100) / 100 + " Ko";
                            taille_chargee = parseInt(this.bytesLoaded / 1024 * 100) / 100 + " Ko";
                        } else { // Sinon si < 1 Ko
                            taille_totale = this.bytesTotal + " o";
                            taille_chargee = this.bytesLoaded + " o";
                        }
                        document.getElementById("load").innerHTML = taille_chargee + " / " + taille_totale; // On insère dans le span de chargement.
                    },
                    whileplaying: function () { // Pendant la lecture :
                        barre.style.backgroundPosition = -420 + (this.position / this.duration * 420) + "px 50%"; // On ajuste le background de la barre d'avancement
                        act_sec = parseInt(this.position / 1000 % 60, 10); // Calcul du nb de secondes écoulées
                        if (act_sec < 10) {
                            act_sec = "0" + act_sec;
                        } // (Ajout d'un 0 si <10)
                        act_min = parseInt(this.position / 1000 / 60, 10); // Calcul du nb de minutes écoulées
                        tot_sec = parseInt(this.duration / 1000 % 60, 10); // Calcul du nb de secondes total du son
                        if (tot_sec < 10) {
                            tot_sec = "0" + tot_sec;
                        } // (Ajout d'un 0 si <10)
                        tot_min = parseInt(this.duration / 1000 / 60, 10); // Calcul du nb de minutes total du son
                        cpt.innerHTML = act_min + ":" + act_sec + " / " + tot_min + ":" + tot_sec; // On affiche ces valeurs dans le div "cpt"
                        vol.style.backgroundPosition = -100 + act_vol + "px 50%"; // On ajuste le background de la barre de son, en fonction du son actuel
                        vol.title = "Volume : " + act_vol; // On ajuste aussi le title du volume
                    },
                    onresume: // Quand on enlève "pause" :
                        function () {
                            titre.previousSibling.innerHTML = "<img src='images/played.png' alt='lecture' /> "; // On insère une image "play" dans le span créé
                        },
                    onplay: // Quand on fait "play" :
                        function () {
                            titre.previousSibling.innerHTML = "<img src='images/played.png' alt='lecture' /> "; // On insère une image "play" dans le span créé
                        },
                    onstop: // Quand on fait "stop" :
                        function () {
                            playlist[current].unload(); // On "décharge" le son, histoire de pas occuper trop de mémoire
                            titre.previousSibling.innerHTML = ""; // On enlève l'image du span
                        },
                    onfinish: // A la fin d'un son
                        function () {
                            playlist[current].unload(); // On "décharge" le son, histoire de pas occuper trop de mémoire
                            titre.previousSibling.innerHTML = ""; // On enlève l'image du span
                            if (!prev && !next) { // Si la fin du son n'est pas due à un clic sur Previous ou Next, on regarde le mode de répétition choisi
                                if (loop == "one") { // Si "Répéter un titre"
                                    lire_current(); // On (re)lance la lecture du son actuel
                                } else if (loop == "all") { // Si "Répéter la playlist"
                                    bouton_next(); // On appelle bouton_next() (On passe au titre suivante)
                                } else { // Si "Ne pas répéter"
                                    if (current < playlist.length - 1) { // Si on n'est pas encore à la fin de la playlist
                                        bouton_next(); // On passe au titre suivant
                                    } else { // Sinon
                                        current = 0; // On réinitialise juste le titre courant.
                                    }
                                }
                            }
                        },
                    onpause: // Quand on fait "pause"
                        function () {
                            titre.previousSibling.innerHTML = "<img src='images/paused.png' alt='pause' /> "; // On insère une image "pause" dans le span créé
                        },
                    onid3: function () { // A l'arrivée des Tag ID3
                        var chaine_tag = ""; // On crée une chaîne vide
                        if (this.id3["artist"]) { // Et on y insère les différents tag en vérifiant leur existence au préalable.
                            chaine_tag += "<strong>Artiste : </strong>" + this.id3["artist"] + "<br />";
                        }
                        if (this.id3["album"]) {
                            chaine_tag += "<strong>Album : </strong>" + this.id3["album"] + "<br />";
                        }
                        if (this.id3["songname"]) {
                            chaine_tag += "<strong>Titre : </strong>" + this.id3["songname"];
                        }
                        document.getElementById("tagid3").innerHTML = chaine_tag; // On insère finalement la chaîne dans la page
                    }
                });
        })(list[i]);
    }
}

function lire_current() { // Fonction de démarrage de la lecture
    soundManager.stopAll(); // On stoppe tous les sons (pour éviter le multi-shot
    playlist[current].setPosition(0); // On réinitialise la position du son courant
    playlist[current].setVolume(act_vol); // On applique le volume actuel
    playlist[current].play(); // On lance la lecture
    document.getElementById("play").src = "images/pause.png"; // Le bouton "play" devient "pause"
    document.getElementById("play").title = "Pause"; // On change le title également.
}

function bouton_play() { // Appui sur le bouton "play"
    if (playlist[current].playState) { // On teste si un titre est en cours de lecture/pause. Si oui
        b_play = document.getElementById("play"); // On récupère le bouton "play"
        if (playlist[current].paused) { // S'il est en pause
            playlist[current].resume(); // On le relance
            b_play.src = "images/pause.png"; // Le bouton "play" devient "pause"
            b_play.title = "Pause"; // On change le title également
        } else { // S'il est en lecture
            playlist[current].pause(); // On le met en pause
            b_play.src = "images/play.png"; // Le bouton "pause" devient "play"
            b_play.title = "Lecture"; // On change le title également
        }
    } else { // Si le son est stoppé
        lire_current(); // On démarre la lecture
    }
}

function bouton_stop() { // Appui sur le bouton "stop"
    playlist[current].stop(); // On stoppe le son courant
    document.getElementById("play").src = "images/play.png"; // Le bouton "pause" devient "play"
    document.getElementById("play").title = "Lecture"; // On change le title également
    document.getElementById("barre").style.backgroundPosition = "-420px 50%"; // On réinitialise la position du background de la barre d'avancement
    document.getElementById("cpt").innerHTML = "0:00 / 0:00"; // On réinitialise le compteur de temps
    document.getElementById("chargement").style.backgroundPosition = "-420px 50%"; // On réinitialise la position du background de la barre de chargement
    document.getElementById("load").innerHTML = "0 / 0"; // On réinitialise le compteur de chargement
}

function bouton_previous() { // Appui sur le bouton "précédent"
    prev = true; // On "actionne" le booléen prev
    current--; // On recule d'un titre
    if (current < 0) {
        current = playlist.length - 1;
    } // Si on a trop reculé, on prend le dernier titre
    lire_current(); // On lance la lecture
    prev = false; // On "désactionne" le booléen
}

function bouton_next() { // Appui sur le bouton "suivant"
    next = true; // On "actionne" le booléen next
    current++; // On avance d'un titre
    if (current >= playlist.length) {
        current = 0;
    } // Si on a trop avancé, on prend le premier titre
    lire_current(); // On lance la lecture
    next = false; // On "désactionne" le booléen
}

function bouton_rewind() { // Appui sur le bouton "Retour arrière"
    playlist[current].setPosition(playlist[current].position - 1000); // On recule d'une seconde
    timer = setTimeout(bouton_rewind, 50); // On relance la fonction
}

function bouton_forward() { // Appui sur le bouton "Avance rapide"
    playlist[current].setPosition(playlist[current].position + 1000); // On avance d'une seconde
    timer = setTimeout(bouton_forward, 50); // On relance la fonction
}

function bouton_loop() { // Appui sur le bouton "Répéter"
    var b_loop = document.getElementById("loop"); // On récupère le bouton
    if (loop == "one") { // Si il est sur "Répéter un titre"
        loop = "all"; // on passe à "Répéter la playlist"
        b_loop.src = "images/all.png"; // On change l'image du bouton et le title en fonction
        b_loop.title = "Ne pas répéter";
    } else if (loop == "all") { // S'il est sur "Répéter la playlist"
        loop = "none"; // On passe à "Ne pas répéter"
        b_loop.src = "images/none.png"; // Et on change l'image et le title
        b_loop.title = "Répéter le titre";
    } else { // Et enfin s'il est sur "Ne pas répéter"
        loop = "one"; // On passe à "Répéter un titre"
        b_loop.src = "images/one.png"; // Et on change l'image et le title
        b_loop.title = "Répéter la playlist";
    }
}

function bouton_toggleMute() { // Appui sur le bouton "Muet"
    var bouton_muet = document.getElementById("mute"); // On récupère le bouton
    if (bouton_muet.alt == "muet") { // S'il est en sonore
        soundManager.mute(); // On coupe le son
        bouton_muet.alt = "son"; // On change les attributs alt, title et src
        bouton_muet.title = "Son";
        bouton_muet.src = "images/mute.png";
    } else { // S'il est en muet
        soundManager.unmute(); // On remet le son
        bouton_muet.alt = "muet"; // On change les attributs alt, title et src
        bouton_muet.title = "Muet";
        bouton_muet.src = "images/sound.png";
    }
}

function bouton_volume(aug) { // Appui sur les boutons du volume
    if (aug) { // Si appui sur "+"
        if (act_vol < 100) { // Si on peut encore augmenter
            act_vol++; // On augmente
            timer = setTimeout(bouton_volume, 50, true); // Et on relance la fonction
        }
    } else { // Si appui sur "-"
        if (act_vol > 0) { // Si on peut encore diminuer
            act_vol--; // On diminue
            timer = setTimeout(bouton_volume, 50, false); // Et on relance la fonction
        }
    }
    playlist[current].setVolume(act_vol); // On applique le volume au titre courant
    var vol = document.getElementById("volume"); // On récupère la barre de volume
    vol.style.backgroundPosition = -100 + act_vol + "px 50%"; // On ajuste son background
    vol.title = "Volume : " + act_vol; // et son title
}

var v_clic = false; // Indicateur booléen du clic sur la barre de volume
var b_clic = false; // Indicateur booléen du clic sur la barre d'avancement
var v_x; // Variable de position absolue en x de la barre de volume
var b_x; // Variable de position absolue en x de la barre d'avancement
function clic(obj, event) { // Clic sur une des deux barres
    if (event.preventDefault) { // Code pour éviter que la souris embarque les images...
        event.preventDefault();
    }
    event.returnValue = false;
    if (obj == "vol") { // Si on clique sur la barre de volume
        v_clic = true; // On active le booléen
        var vol = document.getElementById("volume"); // On récupère la barre de volume
        v_x = coord(vol); // On calcule sa position absolue en x
        act_vol = event.clientX - v_x; // Une simple soustraction donne le volume
        playlist[current].setVolume(act_vol); // On applique le volume au titre courant
        vol.style.backgroundPosition = -100 + act_vol + "px 50%"; // On ajuste son background
        vol.title = "Volume : " + act_vol; // et son title
    } else { // Si on clique sur la barre d'avancement
        b_clic = true; // On active le booléen
        var barre = document.getElementById("barre"); // On récupère la barre d'avancement
        b_x = coord(barre); // On calcule sa position absolue en x
        playlist[current].setPosition(parseInt((event.clientX - b_x) / 420 * playlist[current].duration, 10)); // On change la position du titre en cours en fonction
    }
}

function move(obj, event) { // Cliquer-glisser sur une des deux barres
    if (obj == "vol" && v_clic) { // Si on est sur la barre de volume
        act_vol = event.clientX - v_x; // On calcul le volume choisi
        playlist[current].setVolume(act_vol); // On applique le volume au titre courant
        var vol = document.getElementById("volume"); // On récupère la barre de volume
        vol.style.backgroundPosition = -100 + act_vol + "px 50%"; // On ajuste son background
        vol.title = "Volume : " + act_vol; // et son title
    } else if (obj == "barre" && b_clic) { // Si on est sur la barre d'avancement
        playlist[current].setPosition(parseInt((event.clientX - b_x) / 420 * playlist[current].duration, 10)); // On change la position du titre en cours en fonction
    }
}

function coord(element) { // Fonction pour calculer la position absolue en x d'un élement
    var eX = 0; // Initialisation de la valeur
    do {
        eX += element.offsetLeft; // On ajoute l'offsetLeft
        element = element.offsetParent; // On remonte à l'objet parent
    } while (element && element.style.position != 'absolute'); // Et on recommence
    return eX; // On renvoit la position absolue en x de l'élément
}

document.onmouseup = function () { // Quand on relâche le clic
    v_clic = false; // On "désactionne" les deux indicateurs de clic.
    b_clic = false;
}