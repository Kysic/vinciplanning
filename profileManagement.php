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
require_once('lib/form.php');

if (!isConnected()) {
	die("Vous n'êtes pas connecté.");
}

$formParams = array(
		'name' => new GenericFormEntry('Nom', 'text', '2 à 50 caractères autorisés : "-", alphabétiques et lettes accentuées',
				true, 2, 50, FILTER_VALIDATE_REGEXP, array("options"=>array('regexp'=>'/^[-[:alpha:]âêîôûàèìòùáéíóúäëïöüãõñç]{2,50}$/'))),
		'firstName' => new GenericFormEntry('Prenom', 'text', '2 à 50 caractères autorisés : "-", alphabétiques et lettes accentuées',
				true, 2, 50, FILTER_VALIDATE_REGEXP, array("options"=>array('regexp'=>'/^[-[:alpha:]âêîôûàèìòùáéíóúäëïöüãõñç]{2,50}$/'))),
		'email' => new GenericFormEntry('Email', 'email', 'Format invalide', true, 0, 50, FILTER_VALIDATE_EMAIL),
		'telephone' => new GenericFormEntry('Téléphone', 'tel', 'Format invalide', false, -1, 20,
				FILTER_VALIDATE_REGEXP, array("options"=>array('regexp'=>'/^\+?[0-9]{0,20}$/')))
);
if (isAllValid($formParams)) {
	if (!isTokenCorrect()) {
		die('Erreur sur le jeton de session. Veuillez vous déconnecter/reconnecter.');
	}
	require_once('lib/member.php');
	$result = modifyProfil($pdo, getUser()->memberId, $_POST['name'], $_POST['firstName'], $_POST['email'], $_POST['telephone']);
	if ($result == '00000') {
		getUser()->name = $_POST['name'];
		getUser()->firstName = $_POST['firstName'];
		getUser()->email = $_POST['email'];
		getUser()->telephone = $_POST['telephone'];
?>
Votre profil a bien été modifié.
<?php
			exit();
	} else {
		$error = 'Erreur lors de l\'accès à la base de donnée : '.$result;
	}
}

$formPasswordParams = array(
		'password' => new GenericFormEntry('Nouveau mot-de-passe', 'password', 'Utilisation de caractères non autorisés', true, 6, MAX_PASSWORD_LENGTH,
				FILTER_VALIDATE_REGEXP, array("options"=>array('regexp'=>'/^[[:print:]]{6,'.MAX_PASSWORD_LENGTH.'}$/'))),
		'passwordVerification' => new FormVerificationEntry('Vérification du mot-de-passe', 'password', 'Ce champ doit être identique au champ mot-de-passe', true, 6, MAX_PASSWORD_LENGTH, @$_POST['password'])
);
if (isAllValid($formPasswordParams)) {
	if (!isTokenCorrect()) {
		die('Erreur sur le jeton de session. Veuillez vous déconnecter/reconnecter.');
	}
	require_once('lib/member.php');
	$result = changePassword($pdo, getUser()->memberId, $_POST['password']);
	if ($result == '00000') {
		?>
Changement du mot de passe terminé.<br>
Vous devrez utiliser ce nouveau mot de passe lors de votre prochaine connexion.
<?php
		exit();
	} else {
		$error = 'Erreur lors de l\'accès à la base de donnée : '.$result;
	}
}


?>
<form method="POST" action="profileManagement.php" id="profileForm" onSubmit="return modal.submitForm($(this));">
<input type="hidden" name="token" value="<?php echo getToken(); ?>">
<?php
if (isset($error)) {
	echo $error;
}
foreach ($formParams as $formName => $formEntry) {
	echo '<label for="'.$formName.'">'.$formEntry->getLabel().' : </label>';
	echo '<input type="'.$formEntry->getInputType().'" name="'.$formName.'" id="'.$formName.'" maxlength="'.$formEntry->getMaxSize().'" value="'.@getUser()->$formName.'">';
	if (isOneFill($formParams)) {
		echo ' '.printErrorMsg($formName);
	}
	echo '<br>';
}
?>
<input type="submit" value="Modifier">
</form>

<br>

<form method="POST" action="profileManagement.php" id="profileForm" onSubmit="return modal.submitForm($(this));">
<input type="hidden" name="token" value="<?php echo getToken(); ?>">
<?php
if (isset($error)) {
	echo $error;
}
foreach ($formPasswordParams as $formName => $formEntry) {
	echo '<label for="'.$formName.'">'.$formEntry->getLabel().' : </label>';
	echo '<input type="'.$formEntry->getInputType().'" name="'.$formName.'" id="'.$formName.'" maxlength="'.$formEntry->getMaxSize().'">';
	if (isOneFill($formPasswordParams)) {
		echo ' '.printErrorMsg($formName);
	}
	echo '<br>';
}
?>
<input type="submit" value="Changer le mot de passe">
</form>

