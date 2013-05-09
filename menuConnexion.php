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
<form method="post" id="formConnexion" onSubmit="return menuConnexion.submit();" >
<?php
if ( estConnecte() ) {
	echo '<em>'.getUser()->pseudo.' ('.getUser()->groupe.')</em><br>';
	?>
	<span class="fauxLien" onClick="modal.open('gestionProfil.php');">Gestion profil</span><br>
	<?php 
	if (peutGererMembres()) {
?>
<!-- 
	<span class="fauxLien" onClick="modal.open('gestionGroupes.php');">Gestion Groupes</span><br>
-->
	<span class="fauxLien" onClick="modal.open('gestionMembres.php');">Gestion Membres</span><br>
<?php
	}
	?>
	<input type="hidden" name="action" value="deconnecte">
	<input type="hidden" name="jeton" value="<?php echo getJeton(); ?>">
	<input type="submit" value="deconnexion">
	<?php
} else {
	if (isset($connexionErreur)) {
		echo '<div id="connexionErreur">'.$connexionErreur.'</div>';
	}
	?>
	<input type="hidden" name="action" value="connecte">
	<label for="pseudo">Login : </label>
	<input type="text" name="pseudo" id="pseudo"><br>
	<label for="motDePasse">Mot-de-passe : </label>
	<input type="password" name="motDePasse" id="motDePasse"><br>
	<input type="submit" value="connexion"><br>
	<span class="fauxLien" onClick="modal.open('inscription.php');">S'inscrire</span><br>
	<?php
}
?>
</form>