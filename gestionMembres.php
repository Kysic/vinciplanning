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
require_once('lib/membre.php');

if (!peutGererMembres()) {
	die("Vous ne disposez pas des droits nécessaires.");
}

if ( @$_POST['action'] == 'modifierGroupe' && estJetonCorrect() && !empty($_POST['membreId']) && !empty($_POST['groupeId']) ) {
	modifierGroupe($pdo, $_POST['membreId'], $_POST['groupeId']);
}
?>
<table>
<thead>
<tr><th>Pseudo</th><th>Nom</th><th>Prenom</th><th>Email</th><th>Telephone</th><th>Groupe</th></tr>
</thead>
<tbody>
<?php
$listeGroupes = listeGroupes($pdo);
$listeMembres = listeMembres($pdo);
foreach ( $listeMembres as $membre ) {
	echo '<tr><td>'.$membre['pseudo'].'</td><td>'.$membre['nom'].'</td><td>'.
		$membre['prenom'].'</td><td>'.$membre['email'].'</td><td>'.
		$membre['telephone'].'</td><td>';
	echo '<select name="groupeId" onChange="modal.postData(\'gestionMembres.php\', ';
	echo '\'action=modifierGroupe&jeton='.getJeton().'&membreId='.$membre['membreId'].'&groupeId=\'+$(this).val())"';
	if ($membre['membreId'] == getUser()->membreId) {
		echo ' disabled="disabled"';
	}
	echo '>';
	echo '<option disabled="disabled"';
	if ($membre['groupeId'] == NULL) {
		echo ' selected="selected"';
	}
	echo '>Sans groupe</option>';
	foreach ( $listeGroupes as $groupe ) {
		echo '<option value="'.$groupe['groupeId'].'"';
		if ($membre['groupeId'] == $groupe['groupeId']) {
			echo ' selected="selected"';
		}
		echo '>'.$groupe['groupe'].'</option>';
	}
	echo '</select>';
	echo '</td></tr>';
}
?>
</tbody>
</table>