function setSiteState(state) {
	var setState = '/index/set/state/' + state + '?dt=' + getDT();
	jQuery.ajax({
		url: setState,
		//async: false,
		type: 'POST',
		success: function() {
			displayPage();
		}		
	});
	
	/*
	new Ajax.Request('/index/set/state/' + state + '?dt=' + getDT(), {
		method : 'get',
		onSuccess : function(transport) {
			// var response = transport.responseText || "no response text";
			// alert("Success! \n\n" + response + state);
			new Ajax.Updater('remote-site', url, {
				method : 'get',
				evalScripts : true
			});
		},
		onFailure : function() {
			alert('Something went wrong...')
		}
	});
	*/
}
/**
 * Unique date function
 * 
 * @return integer
 */
function getDT() {
	return new Date().getTime();
}

/**
 * Get AlarmFeed
 */
/*
new Ajax.PeriodicalUpdater('alarmFeed', '/feed', {
	method : 'get',
	// insertion: Insertion.Top,
	frequency : 5,
	decay : 10
});
*/
function generateUrl(user) {
	url = '/site/view/owner/' + user + '/?p=' + getDT();
	return url;
}

function displayPage() {
	jQuery.ajax({
		url: url,
		type: 'GET',
		//async: false,
		success: function(data) {
			jQuery("#remote-site").contents().find('html').html(data);
		},
		error: function(request, status, error) {
			jQuery("#remote-site").contents().find('html').html(request.responseText);
			//console.log(request.responseText);
		}
	});
}
/*
Ajax.Responders.register( {
	onCreate : showProcessing,
	onComplete : hideProcessing
});

function showProcessing() {
	if (Ajax.activeRequestCount > 0) {

		new Effect.Opacity('remote-site', {
			from : 1.0,
			to : 0.50,
			duration : 0.5
		});
		new Effect.Opacity('alarmFeed', {
			from : 1.0,
			to : 0.3,
			duration : 0.7
		});
		
		document.getElementById('loading').style.display = 'inline';
	}
}

function hideProcessing() {
	if (Ajax.activeRequestCount <= 0) {

		new Effect.Opacity('remote-site', {
			from : 0.50,
			to : 1.0,
			duration : 0.5
		});
		
		new Effect.Opacity('alarmFeed', {
			from : 0.3,
			to : 1,
			duration : 0.7
		});
		
		document.getElementById('loading').style.display = 'none';
	}
}
*/