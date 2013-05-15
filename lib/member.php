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

function changePassword($pdo, $memberId, $newPassword) {
    $passwordSalt = generateSalt();
    $passwordHash = hashPasssword($passwordSalt, $newPassword); 
    $query = 'UPDATE '.MEMBERS_TABLE.
             ' SET passwordSalt=:passwordSalt, passwordHash=:passwordHash'.
             ' WHERE memberId=:memberId';
    $requete = $pdo->prepare($query);
    $requete->bindValue(':memberId', $memberId, PDO::PARAM_INT);
    $requete->bindValue(':passwordSalt', $passwordSalt, PDO::PARAM_STR);
    $requete->bindValue(':passwordHash', $passwordHash, PDO::PARAM_STR);
    $requete->execute();
	return $requete->errorCode();
}

function getGroups($pdo) {
	$query = 'SELECT groupId, groupName, rights'.
			' FROM '.GROUPS_TABLE.
			' ORDER BY rights';
	$requete = $pdo->prepare($query);
	$requete->execute();
	return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function getMembers($pdo, $firtIdx=0, $lastIdx=200) {
	$query = 'SELECT memberId, pseudo, name, firstName, email, telephone, groupId'.
			' FROM '.MEMBERS_TABLE.
			' ORDER BY pseudo';
	$requete = $pdo->prepare($query);
	$requete->execute();
	return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function modifyGroup($pdo, $memberId, $newGroupId) {
	$query = 'UPDATE '.MEMBERS_TABLE.
			' SET groupId=:groupId'.
			' WHERE memberId=:memberId';
	$requete = $pdo->prepare($query);
	$requete->bindValue(':memberId', $memberId, PDO::PARAM_INT);
	$requete->bindValue(':groupId', $newGroupId, PDO::PARAM_INT);
	$requete->execute();
	return $requete->errorCode();
}

function addMember($pdo, $pseudo, $name, $firstName, $email, $telephone, $password) {
    $passwordSalt = generateSalt();
    $passwordHash = hashPasssword($passwordSalt, $password); 
	$query = 'INSERT INTO '.MEMBERS_TABLE.
			' (pseudo, name, firstName, email, telephone, passwordSalt, passwordHash) '.
			' VALUES (:pseudo, :name, :firstName, :email, :telephone, :passwordSalt, :passwordHash)';
	$requete = $pdo->prepare($query);
	$requete->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
	$requete->bindValue(':name', $name, PDO::PARAM_STR);
	$requete->bindValue(':firstName', $firstName, PDO::PARAM_STR);
	$requete->bindValue(':email', $email, PDO::PARAM_STR);
	$requete->bindValue(':telephone', $telephone, PDO::PARAM_STR);
	$requete->bindValue(':passwordSalt', $passwordSalt, PDO::PARAM_STR);
	$requete->bindValue(':passwordHash', $passwordHash, PDO::PARAM_STR);
	$requete->execute();
	return $requete->errorCode();
}

?>

