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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Vinci Planning</title>
<link rel="stylesheet" href="css/calendar.css" type="text/css">
<script src="js/jquery-1.9.1.min.js"></script>
<script>var user = <?php require('session.php'); ?>;</script>
<script src="js/calendar.js"></script>
</head>
<body>
	<div id="pageContent">
		<div id="menuLateral">
			<div id="menuConnexion" class="menu">
				<div class="barreTitre">Menu Connexion</div>
				<div id="contenuMenuConnexion" class="contenuMenu">
					<?php include('menuConnexion.php'); ?>
				</div>
			</div>
			<div id="navigation" class="menu">
				<div class="barreTitre">Navigation</div>
				<div id="contenuNavigation" class="contenuMenu">
					<span class="fauxLien" onClick="menuNavigation.aide();">Aide</span>
				</div>
			</div>
			<div id="tableauDeBord" class="menu">
				<div class="barreTitre">Tableau de bord</div>
				<div id="contenuTableauDeBord" class="contenuMenu">To be done.</div>
			</div>
			<div id="DerniersCRMaraudes" class="menu">
				<div class="barreTitre">Dernières maraudes</div>
				<div id="contenuDerniersCRMaraudes" class="contenuMenu">To be done.</div>
			</div>
		</div>
		<div id="calendar"></div>
	</div>
	<div id="fond"></div>
	<div id="fenetreModale" class="menu">
		<div class="barreTitre">
			<span id="titreFenModale"></span><span id="fermerFenModale">X</span>
		</div>
		<div id="contenuFenModale" class="contenuMenu"></div>
	</div>
</body>
</html>
