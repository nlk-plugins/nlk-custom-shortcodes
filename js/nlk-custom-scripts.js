/**
 *	Tracker Javascript
 *
 *	Dependencies: jQuery, jQuery.cookie
 *
 */


if (!Date.now) {
	Date.now = function() { return new Date().getTime(); };
}

/**
 *	Site Tracking Script
 *
 */
jQuery( function($) {

	var session_limit = 5; // Minutes to consider an active session last page view time limit

	// Data values we will be tracking
	var cur_time = Math.round( Date.now() / 1000 ),
		cur_url = document.URL,
		cur_title = document.title;

	// Set working variables
	var new_obj = new Object(),
		old_obj = new Object(),
		obj_len = 0,
		then = 0;
	
	// Get current tracking cookie value
	var old_json = $.cookie('__nlkpv');
	if ( typeof old_json !== 'undefined' ) {
		old_obj = JSON.parse( old_json );
		obj_len = Object.keys(old_obj).length;
	}

	// Start creating the new object we wil pass to the cookie
	new_obj[0] = {
		"enter_time":	cur_time,
		"exit_time":	cur_time + ( 60 * session_limit ),
		"url":			cur_url,
		"title":		cur_title
	};

	// If the old cookie contained values we combine it with our new data and reset all the keys
	if ( obj_len > 0 ) {

		// Set the latest row with an exit time (i.e. new page)
		old_obj[0]["exit_time"] = cur_time;

		// While loop merges objects and resets keys
		var i = 0,
			j = 1;
		while ( i < obj_len ) {
			new_obj[ j ] = old_obj[ i ];
			i++;
			j++;
		};

		// used for Time-On-Site calculations
		var then = old_obj[ obj_len - 1 ]["enter_time"];

	}
	else {
		var then = new_obj[0]["enter_time"];
	}

	// set Pages Viewed session cookie
	$.cookie('__nlkpv', JSON.stringify( new_obj ), { path: '/' });

	// Set cookie with time entered site
	$.cookie('__nlken', then, { path: '/' });
	
});

