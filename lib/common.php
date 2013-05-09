<?php
/**
 VinciPlanning (https://github.com/Kysic/vinciplanning/)

 Application web de gestion de planning destinée à l'association
 Vinci-Codex : http://www.vincicodex.com/

 Copyright (C) 2013 Ludovic PLANTIN (http://kysicurl.free.fr/contact)

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
define('MAX_PASSWORD_LENGTH', '50');

session_start();

function estConnecte() {
    return isset($_SESSION['USER']);
}

function getUser() {
    return @$_SESSION['USER'];
}

function genererJeton() {
	$_SESSION['JETON_POST'] = bin2hex(openssl_random_pseudo_bytes(32));
	return $_SESSION['JETON_POST'];
}

function getJeton() {
	if ( !isset($_SESSION['JETON_POST']) ) {
		return genererJeton();
	}
	return $_SESSION['JETON_POST'];
}

function estJetonCorrect() {
	return getJeton() == $_POST['jeton'];
}

function hashMotDePasse($salt, $mdp) {
    return hash('sha256', $salt.$mdp.'ReN1E2Tv45Yct6Kf', true);
}

function genererSel() {
	return openssl_random_pseudo_bytes(16);
}

function peutAccederMaraudes() {
	return isset(getUser()->droits) && getUser()->droits >= 1;
}

function peutVoirAutresMembres() {
	return isset(getUser()->droits) && getUser()->droits >= 2;
}

function peutPartiperAuxMaraudes() {
	return isset(getUser()->droits) && getUser()->droits >= 3;
}

function peutVoirCRMaraude() {
	return isset(getUser()->droits) && getUser()->droits >= 4;
}

function peutEtreTuteur() {
	return isset(getUser()->droits) && getUser()->droits >= 5;
}

function peutValiderParticipation() {
	return isset(getUser()->droits) && getUser()->droits >= 6;
}

function peutGererMembres() {
	return isset(getUser()->droits) && getUser()->droits >= 7;
}

function connecte($pdo, $pseudo, $mdp) {
    $query = 'SELECT membreId, pseudo, email, groupe, m.groupeId, droits, mdpSalt, mdpHash'.
             ' FROM ( membres m LEFT OUTER JOIN groupes g ON m.groupeId = g.groupeId )'.
             ' WHERE pseudo = :pseudo';
    $requete = $pdo->prepare($query);
    $requete->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
    $requete->execute();
    $membre = $requete->fetch(PDO::FETCH_OBJ);
    if ( $membre && hashMotDePasse($membre->mdpSalt, $mdp) == $membre->mdpHash ) {
        unset($membre->mdpSalt);
        unset($membre->mdpHash);
        $_SESSION['USER'] = $membre;
        genererJeton();
        return true;
    }
    return false;
}

function deconnecte() {
    session_unset();
    session_destroy();
}

if ( @$_POST['action'] == 'connecte' && !empty($_POST['pseudo']) && !empty($_POST['motDePasse']) ) {
	require_once('lib/sql.php');
	if (strlen($_POST['motDePasse']) > MAX_PASSWORD_LENGTH) {
		// Réduit les possibilités d'exploitation de collision sur l'algorithme de hash
		$connexionErreur = 'Mot de passe trop long';
	} else if ( !connecte($pdo, $_POST['pseudo'], $_POST['motDePasse'] ) ) {
		$connexionErreur = 'Identifiants incorrects';
	}
} else if ( @$_POST['action'] == 'deconnecte' && estJetonCorrect() ) {
	deconnecte();
}

?>

