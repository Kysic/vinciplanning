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

function getMaraudes($pdo, $month) {
    $query = 'SELECT maraudeId, dateMaraude, compteRendu'.
             ' FROM maraudes'.
             ' WHERE dateMaraude >= :month AND dateMaraude <= DATE_ADD(:month, interval 1 month)';
    $requete = $pdo->prepare($query);
    $requete->bindValue(':month', $month."-01", PDO::PARAM_STR);;
    $requete->execute();
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function getMembresDansMaraudes($pdo, &$maraudes, $onlyValide=TRUE) {
    $query = 'SELECT participationId, membreId, pseudo, typeParticipation, statutDemande, dateDemande, dateModifStatutDemande'.
             ' FROM ( maraudes_membres NATURAL JOIN membres )'.
             ' WHERE maraudeId = :maraudeId';
    if ($onlyValide) {
    	$query .= ' AND ( statutDemande = "valide" OR membreId = :membreId )';
    }
    $requete = $pdo->prepare($query);
    if ($onlyValide) {
   		$requete->bindValue(':membreId', getUser()->membreId, PDO::PARAM_INT);
    }
    foreach ( $maraudes as &$maraude ) {
        $requete->bindValue(':maraudeId', $maraude['maraudeId'], PDO::PARAM_INT);
        $requete->execute();
        $maraude['participants'] = $requete->fetchAll(PDO::FETCH_ASSOC);
    }
}

function getOrCreateMaraude($pdo, $dateMaraude) {
	$query = 'INSERT INTO maraudes (dateMaraude) VALUES (:dateMaraude)'.
			' ON DUPLICATE KEY UPDATE maraudeId=LAST_INSERT_ID(maraudeId)';
    $requete = $pdo->prepare($query);
    $requete->bindValue(':dateMaraude', $dateMaraude, PDO::PARAM_STR);
	$requete->execute();
	return $requete->errorCode();
}

function ajouterDemande($pdo, $membreId, $dateMaraude, $typeParticipation) {
	getOrCreateMaraude($pdo, $dateMaraude);
	$query = 'INSERT INTO maraudes_membres (membreId, maraudeId, typeParticipation)'.
			' VALUES (:membreId, LAST_INSERT_ID(), :typeParticipation)'.
			' ON DUPLICATE KEY UPDATE dateDemande = IF(statutDemande="annule", now(), dateDemande),'.
			' statutDemande = IF(statutDemande="annule", "nonTraite", statutDemande)';
    $requete = $pdo->prepare($query);
   	$requete->bindValue(':membreId', $membreId, PDO::PARAM_INT);
    $requete->bindValue(':typeParticipation', $typeParticipation, PDO::PARAM_STR);
	$requete->execute();
	return $requete->errorCode();
}

function modifierStatutDemande($pdo, $participationId, $newStatutDemande) {
	$query = 'UPDATE maraudes_membres'.
			' SET statutDemande=:statutDemande, dateModifStatutDemande=now()'.
			' WHERE participationId=:participationId';
	$requete = $pdo->prepare($query);
	$requete->bindValue(':participationId', $participationId, PDO::PARAM_INT);
	$requete->bindValue(':statutDemande', $newStatutDemande, PDO::PARAM_STR);
	$requete->execute();
	return $requete->errorCode();
}

function annulerDemande($pdo, $membreId, $participationId) {
	$query = 'UPDATE maraudes_membres'.
			' SET statutDemande="annule", dateModifStatutDemande=now()'.
			' WHERE participationId=:participationId AND membreId=:membreId';
	$requete = $pdo->prepare($query);
   	$requete->bindValue(':membreId', $membreId, PDO::PARAM_INT);
	$requete->bindValue(':participationId', $participationId, PDO::PARAM_INT);
	$requete->execute();
	return $requete->errorCode();
}

?>
