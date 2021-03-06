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
?>
<form method="post" id="connectionForm" onSubmit="return connectionMenu.submit();" >
<?php
if ( isConnected() ) {
	echo '<em>'.getUser()->pseudo.' ('.getUser()->groupName.')</em><br>';
	?>
	<input type="hidden" name="action" value="disconnect">
	<input type="hidden" name="token" value="<?php echo getToken(); ?>">
	<input type="submit" value="deconnexion">
	<?php
} else {
	if (isset($connectionError)) {
		echo '<div id="connectionError">'.$connectionError.'</div>';
	}
	?>
	<input type="hidden" name="action" value="connect">
	<label for="pseudo">Login : </label>
	<input type="text" name="pseudo" id="pseudo"><br>
	<label for="passwordInput">Mot-de-passe : </label>
	<input type="password" name="password" id="password"><br>
	<input type="submit" value="connexion"><br>
	<span class="actionLink" onClick="modal.open('registration.php');">S'inscrire</span><br>
	<?php
}
?>
</form>
