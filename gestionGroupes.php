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
?>
<table>
<thead>
<tr><th>Groupe</th><th>Droits</th></tr>
</thead>
<tbody>
<?php
$listeGroupes = listeGroupes($pdo);
foreach ($listeGroupes  as $groupe ) {
	echo "<tr><td>".$groupe['groupe']."</td><td>".$groupe['droits']."</td></tr>";
}
?>
</tbody>
</table>