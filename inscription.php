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

if (isConnected()) {
	die("Vous ne pouvez pas vous inscrire si vous êtes déjà connecter.");
}

$formParams = array(
		'pseudo' => new GenericFormEntry('Login', 'text', '2 à 30 caractères autorisés : "-", "_" et alphanumériques',
				true, 2, 30, FILTER_VALIDATE_REGEXP, array("options"=>array('regexp'=>'/^[-_[:alnum:]]{2,30}$/'))),
		'name' => new GenericFormEntry('Nom', 'text', '2 à 50 caractères autorisés : "-", alphabétiques et lettes accentuées',
				true, 2, 50, FILTER_VALIDATE_REGEXP, array("options"=>array('regexp'=>'/^[-[:alpha:]âêîôûàèìòùáéíóúäëïöüãõñç]{2,50}$/'))),
		'firstName' => new GenericFormEntry('Prenom', 'text', '2 à 50 caractères autorisés : "-", alphabétiques et lettes accentuées',
				true, 2, 50, FILTER_VALIDATE_REGEXP, array("options"=>array('regexp'=>'/^[-[:alpha:]âêîôûàèìòùáéíóúäëïöüãõñç]{2,50}$/'))),
		'email' => new GenericFormEntry('Email', 'email', 'Format invalide', true, 0, 50, FILTER_VALIDATE_EMAIL),
		'telephone' => new GenericFormEntry('Téléphone', 'tel', 'Format invalide', false, -1, 20,
				FILTER_VALIDATE_REGEXP, array("options"=>array('regexp'=>'/^\+?[0-9]{0,20}$/'))),
		'password' => new GenericFormEntry('Mot-de-passe', 'password', 'Utilisation de caractères non autorisés', true, 6, MAX_PASSWORD_LENGTH,
				FILTER_VALIDATE_REGEXP, array("options"=>array('regexp'=>'/^[[:print:]]{6,'.MAX_PASSWORD_LENGTH.'}$/'))),
		'passwordVerification' => new FormVerificationEntry('Vérification du mot-de-passe', 'password', 'Ce champ doit être identique au champ mot-de-passe', true, 6, MAX_PASSWORD_LENGTH, @$_POST['password'])
);


function printErrorMsg($formName) {
	global $formParams;
	$formEntry = $formParams[$formName];
	switch ($formEntry->isValid(@$_POST[$formName])) {
		case FormEntry::FIELD_REQUIRED_NOT_SET:
			return 'Ce champ est requis.';
		case FormEntry::FIELD_CONTENT_SIZE_UNDER_LIMIT:
			return 'Trop court, il faut au moins '.$formEntry->getMinSize().' caractères.';
		case FormEntry::FIELD_CONTENT_SIZE_OVER_LIMIT:
			return 'Trop long, il ne peut y avoir plus de '.$formEntry->getMaxSize().' caractères.';
		case FormEntry::FIELD_CONTENT_FORMAT_INVALID:
			return $formEntry->getExpectedFormat();
		default:
			return '';
	}
}


$isAllValid = true;
$isNoneSet = true;
$sqlInvalidInput = array();
foreach ($formParams as $param => $formEntry) {
	if (isset($_POST[$param])) {
		$isNoneSet = false;
	}
	if (!$formEntry->isValid(@$_POST[$param]) == FormEntry::FIELD_CONTENT_VALID) {
		$isAllValid = false;
	}
}
if ($isAllValid) {
	require_once('lib/member.php');
	$result = addMember($pdo, $_POST['pseudo'], $_POST['name'], $_POST['firstName'], $_POST['email'], $_POST['telephone'], $_POST['password']);
	if ($result == '00000') {
?>
Inscription réussie.<br>
Vous pouvez dès à présent vous connectez avec vos identifiants mais
vous ne pourrez par voir les maraudes planifées et demander à y participer
qu'une fois que votre inscription aura été validée.
<?php
		exit();
	} else if ($result == '23000') {
		$sqlInvalidInput['pseudo'] = "Un membre est déjà enregistré avec ce login."; 
	} else {
		$error = 'Erreur lors de l\'accès à la base de donnée : '.$result;
	}
}
?>

<form method="POST" action="inscription.php" id="inscriptionForm" onSubmit="return modal.submitForm($(this));">
<?php
if (isset($error)) {
	echo $error;
}
foreach ($formParams as $formName => $formEntry) {
	echo '<label for="'.$formName.'">'.$formEntry->getLabel().' : </label>';
	echo '<input type="'.$formEntry->getInputType().'" name="'.$formName.'" id="'.$formName.'" maxlength="'.$formEntry->getMaxSize().'" value="'.@$_POST[$formName].'">';
	if (!$isNoneSet) {
		echo ' '.printErrorMsg($formName);
		if (isset($sqlInvalidInput[$formName])) {
			echo ' '.$sqlInvalidInput[$formName];
		}
	} else if ($formEntry->isRequired()) {
		echo '*';
	}
	echo '<br>';
}
?>
<input type="submit" value="S'inscrire">
</form>