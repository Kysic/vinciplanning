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
if ( isConnected()) {
	echo json_encode(array(
			'memberId' => getUser()->memberId,
			'pseudo' => getUser()->pseudo,
			'canAccessRoamings' => canAccessRoamings(),
			'canSeeOtherMembers' => canSeeOtherMembers(),
			'canApplyForRoamings' => canApplyForRoamings(),
			'canSeeReports' => canSeeReports(),
			'canBeTutor' => canBeTutor(),
			'canValidateApplication' => canValidateApplication(),
			'canManageMembers' => canManageMembers(),
			'token' => getToken()
	));
} else {
	echo "false";
}
?>