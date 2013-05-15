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
require_once('lib/member.php');

if (!canManageMembers()) {
	die("Vous ne disposez pas des droits nécessaires.");
}

if ( @$_POST['action'] == 'modifyGroup' && isTokenCorrect() && !empty($_POST['memberId']) && !empty($_POST['groupId']) ) {
	modifyGroup($pdo, $_POST['memberId'], $_POST['groupId']);
}
?>
<table>
<thead>
<tr><th>Pseudo</th><th>Nom</th><th>Prenom</th><th>Email</th><th>Telephone</th><th>Groupe</th></tr>
</thead>
<tbody>
<?php
$groups = getGroups($pdo);
$members = getMembers($pdo);
foreach ( $members as $member ) {
	echo '<tr><td>'.$member['pseudo'].'</td><td>'.$member['name'].'</td><td>'.
		$member['firstName'].'</td><td>'.$member['email'].'</td><td>'.
		$member['telephone'].'</td><td>';
	echo '<select name="groupId" onChange="modal.postData(\'membersManagement.php\', ';
	echo '\'action=modifyGroup&token='.getToken().'&memberId='.$member['memberId'].'&groupId=\'+$(this).val())"';
	if ($member['memberId'] == getUser()->memberId) {
		echo ' disabled="disabled"';
	}
	echo '>';
// 	echo '<option disabled="disabled"';
// 	if ($member['groupId'] == NULL) {
// 		echo ' selected="selected"';
// 	}
// 	echo '>Sans groupe</option>';
	foreach ( $groups as $group ) {
		echo '<option value="'.$group['groupId'].'"';
		if ($member['groupId'] == $group['groupId']) {
			echo ' selected="selected"';
		}
		echo '>'.$group['groupName'].'</option>';
	}
	echo '</select>';
	echo '</td></tr>';
}
?>
</tbody>
</table>