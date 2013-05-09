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
require_once('lib/sql.php');

function changeMotDePasse($pdo, $membreId, $newMdp) {
    $mdpSalt = genererSel();
    $mdpHash = hashMotDePasse($mdpSalt, $newMdp); 
    $query = 'UPDATE membres'.
             ' SET mdpSalt=:mdpSalt, mdpHash=:mdpHash'.
             ' WHERE membreId=:membreId';
    $requete = $pdo->prepare($query);
    $requete->bindValue(':membreId', $membreId, PDO::PARAM_INT);
    $requete->bindValue(':mdpSalt', $mdpSalt, PDO::PARAM_STR);
    $requete->bindValue(':mdpHash', $mdpHash, PDO::PARAM_STR);
    $requete->execute();
	return $requete->errorCode();
}

function listeGroupes($pdo) {
	$query = 'SELECT groupeId, groupe, droits'.
			' FROM groupes'.
			' ORDER BY droits';
	$requete = $pdo->prepare($query);
	$requete->execute();
	return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function listeMembres($pdo, $firtIdx=0, $lastIdx=200) {
	$query = 'SELECT membreId, pseudo, nom, prenom, email, telephone, groupeId'.
			' FROM membres'.
			' ORDER BY pseudo';
	$requete = $pdo->prepare($query);
	$requete->execute();
	return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function modifierGroupe($pdo, $membreId, $newGroupeId) {
	$query = 'UPDATE membres'.
			' SET groupeId=:groupeId'.
			' WHERE membreId=:membreId';
	$requete = $pdo->prepare($query);
	$requete->bindValue(':membreId', $membreId, PDO::PARAM_INT);
	$requete->bindValue(':groupeId', $newGroupeId, PDO::PARAM_INT);
	$requete->execute();
	return $requete->errorCode();
}

function ajoutMembre($pdo, $pseudo, $nom, $prenom, $email, $telephone, $mdp, $groupeId=NULL) {
    $mdpSalt = genererSel();
    $mdpHash = hashMotDePasse($mdpSalt, $mdp); 
	$query = 'INSERT INTO membres'.
			' (pseudo, nom, prenom, email, telephone, mdpSalt, mdpHash, groupeId) '.
			' VALUES (:pseudo, :nom, :prenom, :email, :telephone, :mdpSalt, :mdpHash, :groupeId)';
	$requete = $pdo->prepare($query);
	$requete->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
	$requete->bindValue(':nom', $nom, PDO::PARAM_STR);
	$requete->bindValue(':prenom', $prenom, PDO::PARAM_STR);
	$requete->bindValue(':email', $email, PDO::PARAM_STR);
	$requete->bindValue(':telephone', $telephone, PDO::PARAM_STR);
	$requete->bindValue(':mdpSalt', $mdpSalt, PDO::PARAM_STR);
	$requete->bindValue(':mdpHash', $mdpHash, PDO::PARAM_STR);
	$requete->bindValue(':groupeId', $groupeId, PDO::PARAM_INT);
	$requete->execute();
	return $requete->errorCode();
}

?>

