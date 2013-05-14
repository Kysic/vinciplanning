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

// Gere la fenetre modale
var modal = (function() {

	function moveTo(fmTop, fmLeft) {
		$('#fenetreModale').css({
			'top' : fmTop > 0 ? fmTop : 0,
			'left' : fmLeft > 0 ? fmLeft : 0
		});
	}

	function centerInWindow() {
		var fmTop = $(window).height() / 2.5 - $('#fenetreModale').height() / 2;
		var fmLeft = $(window).width() / 2 - $('#fenetreModale').width() / 2;
		moveTo(fmTop, fmLeft);
	}
	
	function show(contenuHtml, titreHtml, noCloseButton) {
		$('#fond').show();
		$('#titreFenModale').html(titreHtml ? titreHtml : '&nbsp;');
		$('#contenuFenModale').html(contenuHtml);
		if (noCloseButton) {
			$('#fermerFenModale').hide();
		} else {
			$('#fermerFenModale').show();
			$('#fermerFenModale').click(function() {
				close();
			});
		}
		centerInWindow();
		$('#fenetreModale').show();
		centerInWindow();
	}

	function showMsgChargementEnCours() {
		show("Chargement en cours, veuillez patienter.",
				"Chargement en cours...", true);
	}

	function open(url) {
		showMsgChargementEnCours();
		$.ajax({
			url : url,
			dataType : 'html',
			success : function(data) {
				show(data);
			},
			error : function(qXHR, textStatus, error) {
				$(document).trigger('erreur', [ textStatus, error ]);
			}
		});
	}

	function close() {
		$("#fond").hide();
		$("#fenetreModale").hide();
	}
	
	function postData(url, data) {
		showMsgChargementEnCours();
		$.ajax({
			type : 'POST',
			data : data,
			url : url,
			dataType : 'html',
			success : function(data) {
				show(data);
			},
			error : function(qXHR, textStatus, error) {
				$(document).trigger('erreur', [ textStatus, error ]);
			}
		});
	}

	function submitForm(modalForm) {
		postData(modalForm.attr('action'), modalForm.serialize());
		return false;
	}

	$(document).on('erreur', function(event, erreurTitre, erreurDescr) {
		show(erreurDescr, erreurTitre);
	});

	// Permet de deplacer la fenetre modale en cliquant sur le titre
	var initalPageX;
	var initalPageY;
	var intialFenTop;
	var intialFenLeft;
	var mouseMoveHandler = function(event) {
		moveTo(intialFenTop + event.pageY - initalPageY, intialFenLeft
				+ event.pageX - initalPageX)
	};
	var mouseUpHandler = function(event) {
		$(window).off('mousemove', mouseMoveHandler);
		$(window).off('mouseup', mouseUpHandler);
	};

	$('#fenetreModale').ready(function() {
		$('#fenetreModale .barreTitre').mousedown(function(event) {
			initalPageX = event.pageX;
			initalPageY = event.pageY;
			intialFenTop = parseInt($('#fenetreModale').css('top'));
			intialFenLeft = parseInt($('#fenetreModale').css('left'));
			$(window).on('mousemove', mouseMoveHandler);
			$(window).on('mouseup', mouseUpHandler);
		});
	});

	return {
		show : show,
		open : open,
		close : close,
		postData : postData,
		submitForm : submitForm
	};

})();

//Gere le menu de navigation
var menuNavigation = (function() {

	function aide() {
		modal.show('<h3>Légende</h3>'+
				'<h4>Maraude complete :</h4>'+
				'<div class="day afterToday maraudeComplete"><div class="dayNumber">XX</div>'+
				'<div class="tuteur">Tuteur</div>'+
				'<div class="coequipier">Coéquipier 1</div>'+
				'<div class="coequipier">Coéquipier 2</div>'+
				'</div><br>'+
				'<h4>Il manque un seul tuteur ou coéquipier :</h4>'+
				'<div class="day afterToday maraudeNotComplete"><div class="dayNumber">XX</div>'+
				'<div class="tuteur">Tuteur</div>'+
				'</div>'+
				'<div class="day afterToday maraudeNotComplete"><div class="dayNumber">XX</div>'+
				'<div class="coequipier">Coéquipier 1</div>'+
				'<div class="coequipier">Coéquipier 2</div>'+
				'</div>'+
				'<h4>Participation non validée :</h4>'+
				'<div class="day afterToday"><div class="dayNumber">XX</div>'+
				'<div class="nonTraite">Attente de validation</div>'+
				'<div class="refuse">Participation refusée</div>'+
				'</div>'
				, 'Aide');
	}
	
	return {
		aide : aide
	};
	
})();

// Gere le formulaire de connexion
var menuConnexion = (function() {

	var timerKeepAlive = setInterval(function() {
		keepSessionOpen()
	}, 120000);

	function compareArray(x, y) {
		if (x === y) {
			return true;
		}
		if (x.length != y.length) {
			return false;
		}
		for ( var key in x) {
			if (x[key] !== y[key]) {
				return false;
			}
		}
		return true;
	}

	function refreshMenuConnexion() {
		$('#contenuMenuConnexion').html("Chargement en cours...");
		$.ajax({
			url : 'menuConnexion.php',
			dataType : 'html',
			success : function(data) {
				$('#contenuMenuConnexion').html(data);
			},
			error : function(qXHR, textStatus, error) {
				$(document).trigger('erreur', [ textStatus, error ]);
			}
		});
	}

	function keepSessionOpen() {
		$.ajax({
			url : 'session.php',
			dataType : 'json',
			success : function(data) {
				if (!compareArray(user, data)) {
					user = data;
					refreshMenuConnexion();
					$(document).trigger('changementEtatSession', [ user ]);
				}
			},
			error : function(qXHR, textStatus, error) {
				$(document).trigger('erreur', [ textStatus, error ]);
			}
		});
	}

	function submit() {
		action = $('#formConnexion input[name="action"]').val();
		$.ajax({
			type : 'POST',
			data : $('#formConnexion').serialize(),
			url : 'connexion.php',
			dataType : 'text',
			success : function(data) {
				var tabParam = data.split('<_sep_>');
				user = $.parseJSON(tabParam[0]);
				$('#contenuMenuConnexion').html(tabParam[1]);
				$(document).trigger('changementEtatSession', [ user ]);
			},
			error : function(qXHR, textStatus, error) {
				$(document).trigger('erreur', [ textStatus, error ]);
			}
		});
		$('#contenuMenuConnexion').html("Chargement en cours...");
		return false;
	}

	return {
		submit : submit
	};

})();

// Gere le calendrier
var calendar = (function() {

	var config = {
		monthNames : [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
				'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre',
				'Décembre' ],
		dayNames : [ 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi',
				'Samedi', 'Dimanche' ],
	}

	var gridWeeks;
	var gridDays;
	var monthDisplayed;
	var monthFirstDayCell;
	var nextMonthFirstDayCell;

	var maraudes = {};
	
	function getMaraude(dateMaraude) {
		var monthMaraudes = maraudes[getMonthId(monthDisplayed)];
		var dayId = getDayId(dateMaraude);
		if (monthMaraudes) {
			for ( var maraudeKey in monthMaraudes) {
				var maraude = monthMaraudes[maraudeKey];
				if (maraude.dateMaraude == dayId) {
					return maraude;
				}
			}
		}
		return false;
	}

	function getTodayMidnight() {
		var midnight = new Date();
		midnight.setHours(0);
		midnight.setMinutes(0);
		midnight.setSeconds(0);
		midnight.setMilliseconds(0);
		return midnight;
	}
	
	function menuMaraude(dateMaraude) {
		var maraude = getMaraude(dateMaraude);
		var html = '<h3>Maraude du ' + config.dayNames[(dateMaraude.getDay() + 6) % 7].toLowerCase()
					+ ' ' + dateMaraude.getDate() + ' ' + config.monthNames[monthDisplayed.getMonth()].toLowerCase() 
					+ ' ' + dateMaraude.getFullYear() + '</h3>';
		var idMyParticipation = -1;
		var participationMembreValide = false;
		if (maraude && maraude.participants.length >= 1) {
			html += '<table><thead><tr><th>Membre</th><th>Type</th><th>Statut Demande</th>';
			if (user.peutValiderParticipation) {
				html += '<th>Actions</th>';
			}
			html += '</tr></thead><tbody>';
			for ( var idParticipants in maraude.participants) {
				var participant = maraude.participants[idParticipants];
				if (participant.membreId == user.membreId && participant.statutDemande != "annule") {
					idMyParticipation = participant.participationId;
					if (participant.statutDemande == 'valide') {
						participationMembreValide = true;
					}
				}
				html += '<tr><td>' + participant.pseudo;
				html += '</td><td>' + participant.typeParticipation;
				html += '</td><td>' + participant.statutDemande;
				if (user.peutValiderParticipation) {
					html += '</td><td>';
					if (participant.statutDemande == 'nonTraite' || participant.statutDemande == 'refuse') {
						html += ' <input type="button" value="Valider" onClick="modal.postData(\'participation.php\','+
							' \'action=validerParticipation&jeton=\'+user.jeton+\'&participationId='+
							participant.participationId+'\');">';
					}
					if (participant.statutDemande == 'nonTraite' || participant.statutDemande == 'valide') {
						html += ' <input type="button" value="Rejeter" onClick="modal.postData(\'participation.php\','+
							' \'action=rejeterParticipation&jeton=\'+user.jeton+\'&participationId='+
							participant.participationId+'\');">';
					}
				}
				html += '</td></tr>';
			}
		}
		html += '</tbody></table>';
		
		if (dateMaraude.getTime() < getTodayMidnight().getTime()) {
			if (maraude && (user.peutValiderParticipation || (participationMembreValide && user.peutEnvoyerCR) )) {
				html += '<br><input type="button" value="Ajouter CR Maraude" onClick="alert(\'Not implemented yet.\');">';
			}
		} else if(user.peutPartiperAuxMaraudes) {
			if (idMyParticipation > 0) {
				html += '<br><input value="Annuler ma demande en cours" type="button" onClick="modal.postData(\'participation.php\','+
				' \'action=annulerDemande&jeton=\'+user.jeton+\'&participationId='+
				idMyParticipation+'\');">';
			} else if (!isMaraudeComplete(maraude)) {
				html += '<input type="button" value="Demander à participer"'+
					' onClick="modal.postData(\'participation.php\','+
					' \'action=demandeAParticiper&jeton=\'+user.jeton+\'&dateMaraude='+
					getDayId(dateMaraude)+'\');">';
				}
		}
		if (user.peutValiderParticipation) {
			html += '<br><input value="Ajouter participation au nom d\'un autre" type="button" onClick="alert(\'Not implemented yet.\');">';
		}
		modal.show(html);
	}

	function createCalendarGrid() {
		var calendarDiv = $('#calendar');
		calendarDiv.empty();
		calendarDiv.append('<div class="loadingIconSpace" id="calendarLoadingIconDiv">'
							+ '<img src="img/ajax-loader.gif" id="calendarLoadingIcon">'
							+ '<img src="img/refresh.gif" id="calendarRefreshIcon" onClick="calendar.refresh()">'
						+ '</div>'
						+ '<div class="calendarHeader">'
							+ '<div class="monthMenu"><div class="previousMonth">&lt;</div><div class="currentMonth"></div><div class="nextMonth">&gt;</div></div> '
							+ '<div class="goToday">o</div> '
							+ '<div class="yearMenu"><div class="previousYear">&lt;</div><div class="currentYear"></div><div class="nextYear">&gt;</div></div>'
						+ '</div>');
		$('#calendarRefreshIcon').hide();
		calendarDiv.append('<div class="weekDays"></div>');
		for ( var i = 0; i < 7; i++) {
			$('.weekDays').append(
					'<div class="weekDay">' + config.dayNames[i] + '</div>');
		}
		for ( var i = 0; i < 6; i++) {
			$('#calendar').append('<div class="week"></div>');
		}
		gridWeeks = $('.week');
		for ( var i = 0; i < 7; i++) {
			$('.week').append('<div class="day"></div>');
		}
		gridDays = $('.day');
		for ( var i = 0; i < gridDays.size(); i++) {
			gridDays.eq(i).click((function(cell) {
				return function() {
					menuMaraude(getDate(cell));
				}
			})(i))
		}
		;
	}
	function numberOfDaysInMonth(date) {
		return new Date(date.getFullYear(), date.getMonth() + 1, -1).getDate() + 1;
	}
	function getCellNumber(date) {
		var dateTmp = new Date(date.getFullYear(), date.getMonth(), date
				.getDate());
		var diff = (dateTmp.getTime() - monthDisplayed.getTime())
				/ (1000 * 60 * 60 * 24);
		return monthFirstDayCell + diff;
	}
	function getDate(cellNumber) {
		return new Date(monthDisplayed.getFullYear(),
				monthDisplayed.getMonth(), 1 + cellNumber - monthFirstDayCell);
	}

	function displayMonthGrid() {
		$('.currentMonth').text(config.monthNames[monthDisplayed.getMonth()]);
		$('.currentYear').text(monthDisplayed.getFullYear());
		gridDays.removeClass().addClass('day');
		gridDays.empty();
		gridDays.slice(0, monthFirstDayCell).addClass('dayNotInCurrentMonth');
		for ( var i = monthFirstDayCell; i < nextMonthFirstDayCell; i++) {
			gridDays.eq(i).append(
					'<div class="dayNumber">' + (i + 1 - monthFirstDayCell)
							+ '</div>');
		}
		gridDays.slice(nextMonthFirstDayCell).addClass('dayNotInCurrentMonth');
		var now = new Date();
		var todayCell = getCellNumber(now);
		if (todayCell < 0) {
			gridDays.addClass('afterToday');
		} else if (todayCell >= 42) {
			gridDays.addClass('beforeToday');
		} else {
			gridDays.slice(0, todayCell).addClass('beforeToday');
			gridDays.eq(todayCell).addClass('today');
			gridDays.slice(todayCell + 1).addClass('afterToday');
		}
		var nbUsefullWeeks = (nextMonthFirstDayCell - 1) / 7 + 1;
		gridWeeks.slice(0, nbUsefullWeeks).show();
		gridWeeks.slice(nbUsefullWeeks).hide();
	}

	function getMonthId(month) {
		return month.getFullYear() + '-'
				+ (month.getMonth() < 10 ? '0' : '')
				+ (month.getMonth() + 1);
	}
	
	function getDayId(date) {
		return getMonthId(date) + '-' + (date.getDate() < 10 ? '0' : '') + date.getDate();
	}

	function displayMonth(date, pasDHistorique) {
		monthDisplayed = new Date(date.getFullYear(), date.getMonth(), 1);
		monthFirstDayCell = (monthDisplayed.getDay() + 6) % 7;
		nextMonthFirstDayCell = monthFirstDayCell
				+ numberOfDaysInMonth(monthDisplayed);
		displayMonthGrid();
		loadMonthMaraudes(getMonthId(monthDisplayed), false);
		if (!pasDHistorique) {
			if (window.history.pushState) {
				window.history.pushState(monthDisplayed, 'Vinci Planning '
						+ getMonthId(monthDisplayed), '?'
						+ getMonthId(monthDisplayed));
			}
		}
	}

	if (window.addEventListener) {
		window.addEventListener('popstate', function(event) {
			displayMonth(event.state, true);
		});
	}

	function registerDateChangeController() {
		$('.previousMonth').click(
				function() {
					if (monthDisplayed.getMonth() == 0) {
						displayMonth(new Date(monthDisplayed.getFullYear() - 1,
								11, 1));
					} else {
						displayMonth(new Date(monthDisplayed.getFullYear(),
								monthDisplayed.getMonth() - 1, 1));
					}
				});
		$('.nextMonth').click(
				function() {
					if (monthDisplayed.getMonth() == 11) {
						displayMonth(new Date(monthDisplayed.getFullYear() + 1,
								0, 1));
					} else {
						displayMonth(new Date(monthDisplayed.getFullYear(),
								monthDisplayed.getMonth() + 1, 1));
					}
				});
		$('.previousYear').click(
				function() {
					displayMonth(new Date(monthDisplayed.getFullYear() - 1,
							monthDisplayed.getMonth(), 1));
				});
		$('.nextYear').click(
				function() {
					displayMonth(new Date(monthDisplayed.getFullYear() + 1,
							monthDisplayed.getMonth(), 1));
				});
		$('.goToday').click(function() {
			displayMonth(new Date());
		});
	}
	
	function getNbValid(maraude) {
		if (!maraude) {
			return 0;
		}
		var compteur = 0;
		for ( var key in maraude.participants) {
			if (maraude.participants[key].statutDemande == 'valide') {
				++compteur;
			}
		}
		return compteur;
	}

	function isMaraudeComplete(maraude) {
		return getNbValid(maraude) >= 3;
	}
	function hasTuteur(maraude) {
		for ( var key in maraude.participants) {
			if (maraude.participants[key].typeParticipation == 'tuteur' && maraude.participants[key].statutDemande == 'valide') {
				return true;
			}
		}
		return false;
	}

	function displayMonthMaraudes() {
		var monthMaraudes = maraudes[getMonthId(monthDisplayed)];
		if (monthMaraudes) {
			for ( var maraudeKey in monthMaraudes) {
				var maraude = monthMaraudes[maraudeKey];
				var jour = parseInt(maraude.dateMaraude.split('-')[2], 10);
				var cellDiv = gridDays.eq(monthFirstDayCell + jour - 1);
				if (isMaraudeComplete(maraude)) {
					cellDiv.addClass('maraudeComplete');
				} else if (getNbValid(maraude) == 1 || (getNbValid(maraude) >= 1 && !hasTuteur(maraude))) {
					cellDiv.addClass('maraudeNotComplete');
				}
				for ( var idParticipants in maraude.participants) {
					var participant = maraude.participants[idParticipants];
					cellDiv.append('<div class="'
							+ participant.typeParticipation + ' '
							+ participant.statutDemande + '">'
							+ participant.pseudo + '</div>');
				}
			}
		}
	}

	function loadMonthMaraudes(monthId, force) {
		if (force || !maraudes[monthId]) {
			$('#calendarRefreshIcon').hide();
			$('#calendarLoadingIcon').show();
			$.getJSON('maraudes.php', {
				month : monthId
			}).done(function(data) {
				if (force) {
					displayMonthGrid();
				}
				receiveMonthMaraudes(monthId, data);
				$('#calendarLoadingIcon').hide();
				$('#calendarRefreshIcon').show();
			}).fail(function(jqxhr, textStatus, error) {
				$(document).trigger('erreur', [ textStatus, error ]);
			});
		} else {
			displayMonthMaraudes();
		}
	}

	function receiveMonthMaraudes(monthId, data) {
		maraudes[monthId] = data;
		if (monthId == getMonthId(monthDisplayed)) {
			displayMonthMaraudes();
		}
	}
	
	function refreshMaraudes() {
		var monthId = getMonthId(monthDisplayed);
		maraudes = new Array(maraudes[monthId]);
		loadMonthMaraudes(monthId, true);
	}

	$('#calendar').ready(function() {
		createCalendarGrid();
		registerDateChangeController();
		var monthDate = new Date();
		var params = location.search.match(/^\?([0-9]{4})-([0-9]{2})$/);
		if (params) {
			var year = params[1];
			var month = params[2];
			monthDate = new Date(year, month - 1, 1);
		}
		displayMonth(monthDate);
	});

	$(document).on('changementEtatSession', function(event, user) {
		refreshMaraudes();
	});

	return {
		refresh : refreshMaraudes
	};

})();
