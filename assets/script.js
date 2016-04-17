$(document).ready(function() {

	// Run the init method on document ready:
	chat.init();

});

var chat = {

	// data holds variables for use in the class:

	data : {
		lastID : 0,
		noActivity : 0
	},

	// Init binds event listeners and sets up timers:

	init : function() {

		// Using the defaultText jQuery plugin, included at the bottom:
		$('#name').defaultText('Nickname');
		$('#email').defaultText('Email (Gravatars are Enabled)');

		chat.data.jspAPI = '';
		chat.data.chatElem = $('#chatLineHolder');
		chat.data.scrollpaneAPI = chat.data.chatElem.niceScroll({
			horizrailenabled : false,
			autohidemode : false
		});

		// Submitting a new chat entry:

		$('#submitForm').submit(function(e) {
			e.preventDefault();

			$('#chatContainer #submitForm button').addClass('disabled');
			$('#chatContainer #submitForm button .spinner').removeClass('hidden');
			
			var text = $('#chatText').val();
			if (text.length == 0) {
				$('#chatContainer #submitForm button .spinner').addClass('hidden');
				$('#chatContainer #submitForm button').removeClass('disabled');
				return false;
			}

			// Using our tzPOST wrapper method to send the chat
			// via a POST AJAX request:
			$.ajax({
				url : chat_Submit,
				method : "POST",
				data : $(this).serialize(),
				success : function(r) {
					$('#chatText').val('');
					
					$('#chatContainer #submitForm button .spinner').addClass('hidden');
					$('#chatContainer #submitForm button').removeClass('disabled');
				}
			});

			return false;
		});

		// Self executing timeout functions

		(function getChatsTimeoutFunction() {
			chat.getChats(getChatsTimeoutFunction);
		})();

		(function getUsersTimeoutFunction() {
			chat.getUsers(getUsersTimeoutFunction);
		})();

	},

	// The render method generates the HTML markup
	// that is needed by the other methods:

	render : function(template, params) {

		var arr = [];
		switch (template) {
		case 'loginTopBar':
			arr = [ '<span><img src="', params.gravatar,
					'" width="23" height="23" />', '<span class="name">',
					params.name,
					'</span><a href="" class="logoutButton rounded">Logout</a></span>' ];
			break;

		case 'chatLine':
			arr = [
					'<div class="chat chat-',
					params.id,
					' rounded"><span class="gravatar"><a href="',
					params.author.profile,
					'"><img src="',
					params.author.gravatar,
					'" width="23" height="23" onload="this.style.visibility=\'visible\'" /></a>',
					'</span><span class="author"><a href="',
					params.author.profile, '">', params.author.name, '</a>',
					':</span><span class="text">', params.message,
					'</span><span class="time">', params.time, '</span></div>' ];
			break;

		case 'user':
			arr = [
					'<div class="user" title="',
					params.name,
					'"><a href="',
					params.profile,
					'"><img src="',
					params.gravatar,
					'" width="23" height="23" onload="this.style.visibility=\'visible\'" /></a></div>' ];
			break;
		}

		// A single array join is faster than
		// multiple concatenations

		return arr.join('');

	},

	// The addChatLine method ads a chat entry to the page

	addChatLine : function(params) {
		// All times are displayed in the user's timezone
		var d = new Date();
		if (params.time) {
			// PHP returns the time in UTC (GMT). We use it to feed the date
			// object and later output it in the user's timezone. JavaScript
			// internally converts it for us.
			d.setUTCHours(params.time.hours === 0 ? 23 : params.time.hours - 1, params.time.minutes);
		}
		params.time = (d.getHours() < 10 ? '0' : '') + d.getHours() + ':'
				+ (d.getMinutes() < 10 ? '0' : '') + d.getMinutes();

		var markup = chat.render('chatLine', params), exists = $('#chatLineHolder .chat-' + params.id);

		if (exists.length) {
			exists.remove();
		}

		if (!chat.data.lastID) {
			// If this is the first chat, remove the
			// paragraph saying there aren't any:

			$('#chatLineHolder p').remove();
		}

		// If this isn't a temporary chat:
		if (params.id.toString().charAt(0) != 't') {
			var previous = $('#chatLineHolder .chat-' + (+params.id - 1));
			if (previous.length) {
				previous.after(markup);
			} else
				chat.data.chatElem.append(markup);
		} else
			chat.data.chatElem.append(markup);

		chat.data.scrollpaneAPI.resize();
		chat.data.scrollpaneAPI.doScrollTop(chat.data.chatElem.prop('scrollHeight'), 5);
	},

	// This method requests the latest chats
	// (since lastID), and adds them to the page.

	getChats : function(callback) {
		$.ajax({
			url : chat_GetChats,
			data : {
				lastID : chat.data.lastID
			},
			datatype : 'json',
			success : function(r) {
				for (var i = 0; i < r.length; i++) {
					chat.addChatLine(r[i]);
				}

				if (r.length) {
					chat.data.noActivity = 0;
					chat.data.lastID = r[i - 1].id;
				} else {
					// If no chats were received, increment
					// the noActivity counter.
					chat.data.noActivity++;
				}

				if (!chat.data.lastID) {
					chat.data.chatElem.html('<p class="noChats">No chats yet</p>');
				}

				// Setting a timeout for the next request,
				// depending on the chat activity:
				var nextRequest = 1000;

				// 2 seconds
				if (chat.data.noActivity > 3) {
					nextRequest = 2000;
				}

				if (chat.data.noActivity > 10) {
					nextRequest = 5000;
				}

				// 15 seconds
				if (chat.data.noActivity > 20) {
					nextRequest = 15000;
				}

				setTimeout(callback, nextRequest);
			}
		});

	},

	// Requesting a list with all the users.

	getUsers : function(callback) {
		$.ajax({
			url : chat_ListUsers,
			datatype : 'json',
			success : function(r) {
				var users = [];
				for (var i = 0; i < r.users.length; i++) {
					if (r.users[i]) {
						users.push(chat.render('user', r.users[i]));
					}
				}
				users.push('<p class="count">' + r.online + '</p>');
				$('#chatUsers').html(users.join(''));
				setTimeout(callback, 15000);
			}
		});
	},

	// This method displays an error message on the top of the page:

	displayError : function(msg) {
		var elem = $('<div>', {
			id : 'chatErrorMessage',
			html : msg
		});

		elem.click(function() {
			$(this).fadeOut(function() {
				$(this).remove();
			});
		});

		setTimeout(function() {
			elem.click();
		}, 5000);

		elem.hide().appendTo('body').slideDown();
	}
};

// A custom jQuery method for placeholder text:

$.fn.defaultText = function(value) {

	var element = this.eq(0);
	element.data('defaultText', value);

	element.focus(function() {
		if (element.val() == value) {
			element.val('').removeClass('defaultText');
		}
	}).blur(function() {
		if (element.val() == '' || element.val() == value) {
			element.addClass('defaultText').val(value);
		}
	});

	return element.blur();
}
