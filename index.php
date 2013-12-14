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
<meta name="description" content="Application web de gestion de planning destinée à l'association Vinci-Codex. See https://github.com/Kysic/vinciplanning/">
<title>Vinci Planning</title>
<link rel="stylesheet" href="css/calendar.css" type="text/css">
<script src="js/jquery-1.9.1.min.js"></script>
<script>var user = <?php require('session.php'); ?>;</script>
<script src="js/calendar.js"></script>
</head>
<body>
	<div id="pageContent">
		<div id="lateralMenu">
			<div id="connectionMenu" class="menu">
				<div class="titleBar">Menu Connexion</div>
				<div id="connectionMenuContent" class="menuContent">
					<?php include('connectionMenu.php'); ?>
				</div>
			</div>
			<div id="browsingMenu" class="menu">
				<div class="titleBar">Navigation</div>
				<div id="contenuNavigation" class="menuContent">
					<div id="profileManagement" class="actionLink hidden" onClick="modal.open('profileManagement.php');">Gestion profil</div>
					<div id="membersManagement" class="actionLink hidden" onClick="modal.open('membersManagement.php');">Gestion Membres</div>
					<div id="groupsManagement" class="actionLink hidden" onClick="modal.open('groupsManagement.php');">Gestion Groupes</div>
					<div class="actionLink" onClick="browsingMenu.help();">Aide</div>
				</div>
			</div>
			<!-- 
			<div id="dashboard" class="menu">
				<div class="titleBar">Tableau de bord</div>
				<div id="dashboardContent" class="menuContent">To be done.</div>
			</div>
			<div id="lastRoamingsReports" class="menu">
				<div class="titleBar">Dernières maraudes</div>
				<div id="lastRoamingsReportsContent" class="menuContent">To be done.</div>
			</div>
			 -->
		</div>
		<div id="calendar"></div>
	</div>
	<div id="overlay"></div>
	<div id="modalWindow" class="menu">
		<div class="titleBar">
			<span id="modalWindowTitle"></span><span id="modalWindowClose">X</span>
		</div>
		<div id="modalWindowContent" class="menuContent"></div>
	</div>
</body>
</html>
