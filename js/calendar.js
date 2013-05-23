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

// Gère la fenêtre modale
var modal = (function() {

	function moveTo(fmTop, fmLeft) {
		$('#modalWindow').css({
			'top' : fmTop > 0 ? fmTop : 0,
			'left' : fmLeft > 0 ? fmLeft : 0
		});
	}

	function centerInWindow() {
		var fmTop = $(window).height() / 2.5 - $('#modalWindow').height() / 2;
		var fmLeft = $(window).width() / 2 - $('#modalWindow').width() / 2;
		moveTo(fmTop, fmLeft);
	}
	
	function show(contenuHtml, titreHtml, noCloseButton) {
		$('#overlay').show();
		$('#modalWindowTitle').html(titreHtml ? titreHtml : '&nbsp;');
		$('#modalWindowContent').html(contenuHtml);
		if (noCloseButton) {
			$('#modalWindowClose').hide();
		} else {
			$('#modalWindowClose').show();
			$('#modalWindowClose').click(function() {
				close();
			});
		}
		centerInWindow();
		$('#modalWindow').show();
		centerInWindow();
	}

	function showLoadingMsg() {
		show("Chargement en cours, veuillez patienter.",
				"Chargement en cours...", true);
	}

	function open(url) {
		showLoadingMsg();
		$.ajax({
			url : url,
			dataType : 'html',
			success : function(data) {
				show(data);
			},
			error : function(qXHR, textStatus, error) {
				$(document).trigger('error', [ textStatus, error ]);
			}
		});
	}

	function close() {
		$("#overlay").hide();
		$("#modalWindow").hide();
	}
	
	function postData(url, data) {
		showLoadingMsg();
		$.ajax({
			type : 'POST',
			data : data,
			url : url,
			dataType : 'html',
			success : function(data) {
				show(data);
			},
			error : function(qXHR, textStatus, error) {
				$(document).trigger('error', [ textStatus, error ]);
			}
		});
	}

	function submitForm(modalForm) {
		postData(modalForm.attr('action'), modalForm.serialize());
		return false;
	}

	$(document).on('error', function(event, errorTitle, errorMsg) {
		show(errorMsg, errorTitle);
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

	$('#modalWindow').ready(function() {
		$('#modalWindow .titleBar').mousedown(function(event) {
			initalPageX = event.pageX;
			initalPageY = event.pageY;
			intialFenTop = parseInt($('#modalWindow').css('top'));
			intialFenLeft = parseInt($('#modalWindow').css('left'));
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
var browsingMenu = (function() {
	
	function showAuthorizedNivagationLinks() {
//		if (user) {
//			$('#profileManagement').show();	
//		} else {
//			$('#profileManagement').hide();	
//		}
		if (user && user.canManageMembers) {
			$('#membersManagement').show();
		} else {
			$('#membersManagement').hide();	
		}
	}
	
	$(document).on('sessionStateChange', function(event, user) {
		showAuthorizedNivagationLinks();
	});
	
	$('#contenuNavigation').ready(function() {
		showAuthorizedNivagationLinks();
	});

	function legend() {
		modal.show('<h3>Légende</h3>'+
				'<h4>Maraude complete :</h4>'+
				'<div class="day afterToday roamingComplete"><div class="dayNumber">XX</div>'+
				'<div class="tutor">Tuteur</div>'+
				'<div class="teamMate">Coéquipier 1</div>'+
				'<div class="teamMate">Coéquipier 2</div>'+
				'</div><br>'+
				'<h4>Il manque un seul tuteur ou coéquipier :</h4>'+
				'<div class="day afterToday roamingNotComplete"><div class="dayNumber">XX</div>'+
				'<div class="tutor">Tuteur</div>'+
				'</div>'+
				'<div class="day afterToday roamingNotComplete"><div class="dayNumber">XX</div>'+
				'<div class="teamMate">Coéquipier 1</div>'+
				'<div class="teamMate">Coéquipier 2</div>'+
				'</div>'+
				'<h4>Participation non validée :</h4>'+
				'<div class="day afterToday"><div class="dayNumber">XX</div>'+
				'<div class="notProcessed">Attente de validation</div>'+
				'<div class="refused">Participation refusée</div>'+
				'</div>'
				, 'Aide');
	}
	
	return {
		help : legend
	};
	
})();

// Gere le formulaire de connexion
var connectionMenu = (function() {

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

	function refreshConnectionMenu() {
		$('#connectionMenuContent').html("Chargement en cours...");
		$.ajax({
			url : 'connectionMenu.php',
			dataType : 'html',
			success : function(data) {
				$('#connectionMenuContent').html(data);
			},
			error : function(qXHR, textStatus, error) {
				$(document).trigger('error', [ textStatus, error ]);
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
					$(document).trigger('sessionStateChange', [ user ]);
				}
			},
			error : function(qXHR, textStatus, error) {
				$(document).trigger('error', [ textStatus, error ]);
			}
		});
	}

	function submit() {
		action = $('#connectionForm input[name="action"]').val();
		$.ajax({
			type : 'POST',
			data : $('#connectionForm').serialize(),
			url : 'connection.php',
			dataType : 'text',
			success : function(data) {
				var tabParam = data.split('<_sep_>');
				user = $.parseJSON(tabParam[0]);
				$('#connectionMenuContent').html(tabParam[1]);
				$(document).trigger('sessionStateChange', [ user ]);
			},
			error : function(qXHR, textStatus, error) {
				$(document).trigger('error', [ textStatus, error ]);
			}
		});
		$('#connectionMenuContent').html("Chargement en cours...");
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

	var roamings = {};
	
	function getRoaming(roamingDate) {
		var monthRoamings = roamings[getMonthId(monthDisplayed)];
		var dayId = getDayId(roamingDate);
		if (monthRoamings) {
			for ( var roamingKey in monthRoamings) {
				var roaming = monthRoamings[roamingKey];
				if (roaming.roamingDate == dayId) {
					return roaming;
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
	
	function roamingMenu(roamingDate) {
		var roaming = getRoaming(roamingDate);
		var html = '<h3>Maraude du ' + config.dayNames[(roamingDate.getDay() + 6) % 7].toLowerCase()
					+ ' ' + roamingDate.getDate() + ' ' + config.monthNames[monthDisplayed.getMonth()].toLowerCase() 
					+ ' ' + roamingDate.getFullYear() + '</h3>';
		var myParticipationId = -1;
		var memberApplicationValidated = false;
		if (roaming && roaming.participants.length >= 1) {
			html += '<table><thead><tr><th>Membre</th><th>Type</th><th>Statut Demande</th>';
			if (user.canValidateApplication) {
				html += '<th>Actions</th>';
			}
			html += '</tr></thead><tbody>';
			for ( var idParticipants in roaming.participants) {
				var participant = roaming.participants[idParticipants];
				if (participant.memberId == user.memberId && participant.applicationStatus != "cancelled") {
					myParticipationId = participant.participationId;
					if (participant.applicationStatus == 'validated') {
						memberApplicationValidated = true;
					}
				}
				html += '<tr><td>' + participant.pseudo;
				html += '</td><td>' + (participant.participationType == 'tutor' ? 'tuteur' : 'coéquipier');
				html += '</td><td>';
				switch (participant.applicationStatus) {
				case "cancelled":
					html += 'annulée';
					break;
				case "notProcessed":
					html += 'non traitée';
					break;
				case "validated":
					html += 'validée';
					break;
				case "refused":
					html += 'refusée';
					break;
				default:
					html += 'erreur';;
				}
				if (user.canValidateApplication) {
					html += '</td><td>';
					if (participant.applicationStatus == 'notProcessed' || participant.applicationStatus == 'refused') {
						html += ' <input type="button" value="Valider" onClick="modal.postData(\'roamingApplication.php\','+
							' \'action=validateApplication&token=\'+user.token+\'&participationId='+
							participant.participationId+'\');">';
					}
					if (participant.applicationStatus == 'notProcessed' || participant.applicationStatus == 'validated') {
						html += ' <input type="button" value="Rejeter" onClick="modal.postData(\'roamingApplication.php\','+
							' \'action=rejectApplication&token=\'+user.token+\'&participationId='+
							participant.participationId+'\');">';
					}
				}
				html += '</td></tr>';
			}
		}
		html += '</tbody></table>';
		
		if (roamingDate.getTime() < getTodayMidnight().getTime()) {
			if (roaming && (user.canValidateApplication || (memberApplicationValidated && user.canSeeReports) )) {
				html += '<br><input type="button" value="Ajouter CR Maraude" onClick="alert(\'Not implemented yet.\');">';
			}
		} else if(user.canApplyForRoamings) {
			if (myParticipationId > 0) {
				html += '<br><input value="Annuler ma demande en cours" type="button" onClick="modal.postData(\'roamingApplication.php\','+
				' \'action=cancelApplication&token=\'+user.token+\'&participationId='+
				myParticipationId+'\');">';
			} else if (!isRoamingComplete(roaming)) {
				html += '<input type="button" value="Demander à participer"'+
					' onClick="modal.postData(\'roamingApplication.php\','+
					' \'action=applyForRoaming&token=\'+user.token+\'&roamingDate='+
					getDayId(roamingDate)+'\');">';
				}
		}
		if (user.canValidateApplication) {
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
					roamingMenu(getDate(cell));
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

	function displayMonth(date, noHistory) {
		monthDisplayed = new Date(date.getFullYear(), date.getMonth(), 1);
		monthFirstDayCell = (monthDisplayed.getDay() + 6) % 7;
		nextMonthFirstDayCell = monthFirstDayCell
				+ numberOfDaysInMonth(monthDisplayed);
		displayMonthGrid();
		loadMonthRoamings(getMonthId(monthDisplayed), false);
		if (!noHistory) {
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
	
	function getNbValid(roaming) {
		if (!roaming) {
			return 0;
		}
		var compteur = 0;
		for ( var key in roaming.participants) {
			if (roaming.participants[key].applicationStatus == 'validated') {
				++compteur;
			}
		}
		return compteur;
	}

	function isRoamingComplete(roaming) {
		return getNbValid(roaming) >= 3;
	}
	function hasTutor(roaming) {
		for ( var key in roaming.participants) {
			if (roaming.participants[key].participationType == 'tutor' && roaming.participants[key].applicationStatus == 'validated') {
				return true;
			}
		}
		return false;
	}

	function displayMonthRoamings() {
		var monthRoamings = roamings[getMonthId(monthDisplayed)];
		if (monthRoamings) {
			for ( var roamingKey in monthRoamings) {
				var roaming = monthRoamings[roamingKey];
				var jour = parseInt(roaming.roamingDate.split('-')[2], 10);
				var cellDiv = gridDays.eq(monthFirstDayCell + jour - 1);
				if (isRoamingComplete(roaming)) {
					cellDiv.addClass('roamingComplete');
				} else if (getNbValid(roaming) == 1 || (getNbValid(roaming) >= 1 && !hasTutor(roaming))) {
					cellDiv.addClass('roamingNotComplete');
				}
				for ( var idParticipants in roaming.participants) {
					var participant = roaming.participants[idParticipants];
					cellDiv.append('<div class="'
							+ participant.participationType + ' '
							+ participant.applicationStatus + '">'
							+ participant.pseudo + '</div>');
				}
			}
		}
	}

	function loadMonthRoamings(monthId, force) {
		if (force || !roamings[monthId]) {
			$('#calendarRefreshIcon').hide();
			$('#calendarLoadingIcon').show();
			$.getJSON('roamings.php', {
				month : monthId
			}).done(function(data) {
				if (force) {
					displayMonthGrid();
				}
				receiveMonthRoamings(monthId, data);
				$('#calendarLoadingIcon').hide();
				$('#calendarRefreshIcon').show();
			}).fail(function(jqxhr, textStatus, error) {
				$(document).trigger('error', [ textStatus, error ]);
			});
		} else {
			displayMonthRoamings();
		}
	}

	function receiveMonthRoamings(monthId, data) {
		roamings[monthId] = data;
		if (monthId == getMonthId(monthDisplayed)) {
			displayMonthRoamings();
		}
	}
	
	function refreshRoamings() {
		var monthId = getMonthId(monthDisplayed);
		roamings = new Array(roamings[monthId]);
		loadMonthRoamings(monthId, true);
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

	$(document).on('sessionStateChange', function(event, user) {
		refreshRoamings();
	});

	return {
		refresh : refreshRoamings
	};

})();
