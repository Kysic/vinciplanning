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
require_once('lib/common.php');
require_once('lib/maraude.php');

if ( @$_POST['action'] == 'demandeAParticiper' && !empty($_POST['dateMaraude']) ) {
	if (!peutPartiperAuxMaraudes()) {
		die("Vous ne disposez pas des droits nécessaires.");
	}
	if (!estJetonCorrect()) {
		die('Erreur sur le jeton de session. Veuillez vous déconnecter/reconnecter.');
	}
	$tabDate = split('-', $_POST['dateMaraude']);
	if (count($tabDate) != 3 || !checkdate($tabDate[1], $tabDate[2], $tabDate[0])) {
		die('Date demandée invalide.');
	}
	$result = ajouterDemande($pdo, getUser()->membreId, $_POST['dateMaraude'], peutEtreTuteur() ? 'tuteur' : 'coequipier');
	if ($result == '00000') {
		echo 'Demande de participation à la maraude du '.$tabDate[2].
			'/'.$tabDate[1].'/'.$tabDate[0].' enregistrée.<br>'.
			'<input type="button" value="Fermer" onClick="calendar.refresh(); modal.close();">';
	} else {
		echo 'Erreur lors de l\'accès à la base de donnée : '.$result;
	}
} else if ( (@$_POST['action'] == 'validerParticipation' || @$_POST['action'] == 'rejeterParticipation') && !empty($_POST['participationId']) ) {
	if (!peutValiderParticipation()) {
		die("Vous ne disposez pas des droits nécessaires.");
	}
	if (!estJetonCorrect()) {
		die('Erreur sur le jeton de session. Veuillez vous déconnecter/reconnecter.');
	}
	$result = modifierStatutDemande($pdo, $_POST['participationId'], @$_POST['action'] == 'validerParticipation' ? 'valide' : 'refuse');
	if ($result == '00000') {
		echo 'Modification enregistrée.<br>'.
			'<input type="button" value="Fermer" onClick="calendar.refresh(); modal.close();">';
	} else {
		echo 'Erreur lors de l\'accès à la base de donnée : '.$result;
	}
} else if ( @$_POST['action'] == 'annulerDemande' && !empty($_POST['participationId']) ) {
	 if (!peutPartiperAuxMaraudes()) {
		die("Vous ne disposez pas des droits nécessaires.");
	 }
	 $result = annulerDemande($pdo, getUser()->membreId, $_POST['participationId']);
	 if ($result == '00000') {
	 	echo 'Modification enregistrée.<br>'.
	 			'<input type="button" value="Fermer" onClick="calendar.refresh(); modal.close();">';
	 } else {
	 	echo 'Erreur lors de l\'accès à la base de donnée : '.$result;
	 }
}else {
	echo 'Action demandée invalide.';
}


?>