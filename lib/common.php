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

function isConnected() {
    return isset($_SESSION['USER']);
}

function getUser() {
    return @$_SESSION['USER'];
}

function generateToken() {
	$_SESSION['POST_TOKEN'] = bin2hex(openssl_random_pseudo_bytes(32));
	return $_SESSION['POST_TOKEN'];
}

function getToken() {
	if ( !isset($_SESSION['POST_TOKEN']) ) {
		return generateToken();
	}
	return $_SESSION['POST_TOKEN'];
}

function isTokenCorrect() {
	return getToken() == @$_POST['token'];
}

function hashPasssword($salt, $password) {
    return hash('sha256', $salt.$password.'ReN1E2Tv45Yct6Kf', true);
}

function generateSalt() {
	return openssl_random_pseudo_bytes(16);
}

function canAccessRoamings() {
	return isset(getUser()->rights) && getUser()->rights >= 1;
}

function canSeeOtherMembers() {
	return isset(getUser()->rights) && getUser()->rights >= 2;
}

function canApplyForRoamings() {
	return isset(getUser()->rights) && getUser()->rights >= 3;
}

function canSeeReports() {
	return isset(getUser()->rights) && getUser()->rights >= 4;
}

function canBeTutor() {
	return isset(getUser()->rights) && getUser()->rights >= 5;
}

function canValidateApplication() {
	return isset(getUser()->rights) && getUser()->rights >= 6;
}

function canManageMembers() {
	return isset(getUser()->rights) && getUser()->rights >= 7;
}

function connect($pdo, $pseudo, $password) {
    $query = 'SELECT memberId, pseudo, name, firstName, email, telephone, groupName, m.groupId, rights, passwordSalt, passwordHash'.
             ' FROM ( '.MEMBERS_TABLE.' m LEFT OUTER JOIN '.GROUPS_TABLE.' g ON m.groupId = g.groupId )'.
             ' WHERE pseudo = :pseudo';
    $requete = $pdo->prepare($query);
    $requete->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
    $requete->execute();
    $member = $requete->fetch(PDO::FETCH_OBJ);
    if ( $member && hashPasssword($member->passwordSalt, $password) == $member->passwordHash ) {
        unset($member->passwordSalt);
        unset($member->passwordHash);
        $_SESSION['USER'] = $member;
        generateToken();
        return true;
    }
    return false;
}

function disconnect() {
    session_unset();
    session_destroy();
}

if ( @$_POST['action'] == 'connect' && !empty($_POST['pseudo']) && !empty($_POST['password']) ) {
	require_once('lib/sql.php');
	if (strlen($_POST['password']) > MAX_PASSWORD_LENGTH) {
		// Réduit les possibilités d'exploitation de collision sur l'algorithme de hash
		$connectionError = 'Mot de passe trop long';
	} else if ( !connect($pdo, $_POST['pseudo'], $_POST['password'] ) ) {
		$connectionError = 'Identifiants incorrects';
	}
} else if ( @$_POST['action'] == 'disconnect' && isTokenCorrect() ) {
	disconnect();
}

?>

