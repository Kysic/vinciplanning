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

function getRoamings($pdo, $month) {
    $query = 'SELECT roamingId, roamingDate, report'.
             ' FROM '.ROAMINGS_TABLE.
             ' WHERE roamingDate >= :month AND roamingDate <= DATE_ADD(:month, interval 1 month)';
    $requete = $pdo->prepare($query);
    $requete->bindValue(':month', $month."-01", PDO::PARAM_STR);;
    $requete->execute();
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function getRoamingParticipants($pdo, &$roamings, $onlyValide=TRUE) {
    $query = 'SELECT participationId, memberId, pseudo, participationType, applicationStatus, applicationDate, applicationStatusModificationDate'.
             ' FROM ( '.APPLICATIONS_TABLE.' NATURAL JOIN '.MEMBERS_TABLE.' )'.
             ' WHERE roamingId = :roamingId';
    if ($onlyValide) {
    	$query .= ' AND ( applicationStatus = "validated" OR memberId = :memberId )';
    }
    $requete = $pdo->prepare($query);
    if ($onlyValide) {
   		$requete->bindValue(':memberId', getUser()->memberId, PDO::PARAM_INT);
    }
    foreach ( $roamings as &$roaming ) {
        $requete->bindValue(':roamingId', $roaming['roamingId'], PDO::PARAM_INT);
        $requete->execute();
        $roaming['participants'] = $requete->fetchAll(PDO::FETCH_ASSOC);
    }
}

function getOrCreateRoaming($pdo, $roamingDate) {
	$query = 'INSERT INTO '.ROAMINGS_TABLE.' (roamingDate) VALUES (:roamingDate)'.
			' ON DUPLICATE KEY UPDATE roamingId=LAST_INSERT_ID(roamingId)';
    $requete = $pdo->prepare($query);
    $requete->bindValue(':roamingDate', $roamingDate, PDO::PARAM_STR);
	$requete->execute();
	return $requete->errorCode();
}

function addApplication($pdo, $memberId, $roamingDate, $participationType) {
	getOrCreateRoaming($pdo, $roamingDate);
	$query = 'INSERT INTO '.APPLICATIONS_TABLE.' (memberId, roamingId, participationType)'.
			' VALUES (:memberId, LAST_INSERT_ID(), :participationType)'.
			' ON DUPLICATE KEY UPDATE applicationDate = IF(applicationStatus="cancelled", now(), applicationDate),'.
			' applicationStatus = IF(applicationStatus="cancelled", "notProcessed", applicationStatus)';
    $requete = $pdo->prepare($query);
   	$requete->bindValue(':memberId', $memberId, PDO::PARAM_INT);
    $requete->bindValue(':participationType', $participationType, PDO::PARAM_STR);
	$requete->execute();
	return $requete->errorCode();
}

function modifyApplication($pdo, $participationId, $newApplicationStatus) {
	$query = 'UPDATE '.APPLICATIONS_TABLE.
			' SET applicationStatus=:applicationStatus, applicationStatusModificationDate=now()'.
			' WHERE participationId=:participationId';
	$requete = $pdo->prepare($query);
	$requete->bindValue(':participationId', $participationId, PDO::PARAM_INT);
	$requete->bindValue(':applicationStatus', $newApplicationStatus, PDO::PARAM_STR);
	$requete->execute();
	return $requete->errorCode();
}

function cancelApplication($pdo, $memberId, $participationId) {
	$query = 'UPDATE '.APPLICATIONS_TABLE.
			' SET applicationStatus="cancelled", applicationStatusModificationDate=now()'.
			' WHERE participationId=:participationId AND memberId=:memberId';
	$requete = $pdo->prepare($query);
   	$requete->bindValue(':memberId', $memberId, PDO::PARAM_INT);
	$requete->bindValue(':participationId', $participationId, PDO::PARAM_INT);
	$requete->execute();
	return $requete->errorCode();
}

?>
