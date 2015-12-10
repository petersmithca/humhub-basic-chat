$(document).ready(function(){

	// Run the init method on document ready:
	chat.init();

});

var chat = {

	// data holds variables for use in the class:

	data : {
		lastID 		: 0,
		noActivity	: 0
	},

	// Init binds event listeners and sets up timers:

	init : function(){

		// Using the defaultText jQuery plugin, included at the bottom:
		$('#name').defaultText('Nickname');
		$('#email').defaultText('Email (Gravatars are Enabled)');

		// Converting the #chatLineHolder div into a jScrollPane,
		// and saving the plugin's API in chat.data:

		chat.data.jspAPI = $('#chatLineHolder').jScrollPane({
			verticalDragMinHeight: 12,
			verticalDragMaxHeight: 12
		}).data('jsp');

		// We use the working variable to prevent
		// multiple form submissions:

		var working = false;

		// Submitting a new chat entry:

		$('#submitForm').submit(function(){

			var text = $('#chatText').val();

			if(text.length == 0){
				return false;
			}

			if(working) return false;
			working = true;

			// Assigning a temporary ID to the chat:
			var tempID = 't'+Math.round(Math.random()*1000000),
				params = {
					id			: tempID,
					author		: chat.data.name,
					gravatar	: chat.data.gravatar,
					text		: text.replace(/</g,'&lt;').replace(/>/g,'&gt;')
				};

			// Using our addChatLine method to add the chat
			// to the screen immediately, without waiting for
			// the AJAX request to complete:

			chat.addChatLine($.extend({},params));

			// Using our tzPOST wrapper method to send the chat
			// via a POST AJAX request:

			$.ajax({url:'/index.php?r=humhub-chat/chat/submit',
		            data: $(this).serialize(),
		            success:function(r){
		            	working = false;
		            	$('#chatText').val('');
						$('div.chat-'+tempID).remove();

						params['id'] = r.insertID;
						chat.addChatLine($.extend({},params));
		            }
		        }
		     );

			return false;
		});

		// Self executing timeout functions

		(function getChatsTimeoutFunction(){
			chat.getChats(getChatsTimeoutFunction);
		})();

		(function getUsersTimeoutFunction(){
			chat.getUsers(getUsersTimeoutFunction);
		})();

	},

	// The render method generates the HTML markup
	// that is needed by the other methods:

	render : function(template,params){

		var arr = [];
		switch(template){
			case 'loginTopBar':
				arr = [
				'<span><img src="',params.gravatar,'" width="23" height="23" />',
				'<span class="name">',params.name,
				'</span><a href="" class="logoutButton rounded">Logout</a></span>'];
			break;

			case 'chatLine':
				arr = [
					'<div class="chat chat-',params.id,' rounded"><span class="gravatar"><img src="',params.gravatar,
					'" width="23" height="23" onload="this.style.visibility=\'visible\'" />','</span><span class="author">',params.author,
					':</span><span class="text">',params.text,'</span><span class="time">',params.time,'</span></div>'];
			break;

			case 'user':
				arr = [
					'<div class="user" title="',params.name,'"><img src="',
					params.gravatar,'" width="23" height="23" onload="this.style.visibility=\'visible\'" /></div>'
				];
			break;
		}

		// A single array join is faster than
		// multiple concatenations

		return arr.join('');

	},

	// The addChatLine method ads a chat entry to the page

	addChatLine : function(params){

		// All times are displayed in the user's timezone

		var d = new Date();
		if(params.time) {

			// PHP returns the time in UTC (GMT). We use it to feed the date
			// object and later output it in the user's timezone. JavaScript
			// internally converts it for us.

			d.setUTCHours(params.time.hours,params.time.minutes);
		}

		params.time = (d.getHours() < 10 ? '0' : '' ) + d.getHours()+':'+
					  (d.getMinutes() < 10 ? '0':'') + d.getMinutes();

		var markup = chat.render('chatLine',params),
			exists = $('#chatLineHolder .chat-'+params.id);

		if(exists.length){
			exists.remove();
		}

		if(!chat.data.lastID){
			// If this is the first chat, remove the
			// paragraph saying there aren't any:

			$('#chatLineHolder p').remove();
		}

		// If this isn't a temporary chat:
		if(params.id.toString().charAt(0) != 't'){
			var previous = $('#chatLineHolder .chat-'+(+params.id - 1));
			if(previous.length){
				previous.after(markup);
			}
			else chat.data.jspAPI.getContentPane().append(markup);
		}
		else chat.data.jspAPI.getContentPane().append(markup);

		// As we added new content, we need to
		// reinitialise the jScrollPane plugin:

		chat.data.jspAPI.reinitialise();
		chat.data.jspAPI.scrollToBottom(true);

	},

	// This method requests the latest chats
	// (since lastID), and adds them to the page.

	getChats : function(callback){
		 $.ajax({url:'/index.php?r=humhub-chat/chat/chats',
            data: {lastID: chat.data.lastID},
            datatype: 'json',
            success:function(r){
            	r = JSON.parse(r);
                for(var i=0;i<r.chats.length;i++){
					chat.addChatLine(r.chats[i]);
				}

				if(r.chats.length){
					chat.data.noActivity = 0;
					chat.data.lastID = r.chats[i-1].id;
				}
				else{
					// If no chats were received, increment
					// the noActivity counter.

					chat.data.noActivity++;
				}

				if(!chat.data.lastID){
					chat.data.jspAPI.getContentPane().html('<p class="noChats">No chats yet</p>');
				}

				// Setting a timeout for the next request,
				// depending on the chat activity:

				var nextRequest = 1000;

				// 2 seconds
				if(chat.data.noActivity > 3){
					nextRequest = 2000;
				}

				if(chat.data.noActivity > 10){
					nextRequest = 5000;
				}

				// 15 seconds
				if(chat.data.noActivity > 20){
					nextRequest = 15000;
				}

				setTimeout(callback,nextRequest);
            }
        }
     );

	},

	// Requesting a list with all the users.

	getUsers : function(callback){
		$.ajax({url:'/index.php?r=humhub-chat/chat/users',
            datatype: 'json',
            success:function(r){
                r = JSON.parse(r);
                var users = [];

                for(var i=0; i< r.users.length;i++){
                    if(r.users[i]){
                        users.push(chat.render('user',r.users[i]));
                    }
                }

                var message = '';

                if(r.users.length<1){
                    message = 'No one is online';
                }
                else {
                    message = r.users.length+' '+(r.users.length == 1 ? 'person':'people')+' online';
                }

                users.push('<p class="count">'+message+'</p>');

                $('#chatUsers').html(users.join(''));

                setTimeout(callback,15000);
            }
        }
     );

	},

	// This method displays an error message on the top of the page:

	displayError : function(msg){
		var elem = $('<div>',{
			id		: 'chatErrorMessage',
			html	: msg
		});

		elem.click(function(){
			$(this).fadeOut(function(){
				$(this).remove();
			});
		});

		setTimeout(function(){
			elem.click();
		},5000);

		elem.hide().appendTo('body').slideDown();
	}
};

// Custom GET & POST wrappers:

$.tzPOST = function(action,data,callback){
	//$.post('php/ajax.php?action='+action,data,callback,'json');
}

$.tzGET = function(action,data,callback){
	$.get(action,data,callback,'json');
}

// A custom jQuery method for placeholder text:

$.fn.defaultText = function(value){

	var element = this.eq(0);
	element.data('defaultText',value);

	element.focus(function(){
		if(element.val() == value){
			element.val('').removeClass('defaultText');
		}
	}).blur(function(){
		if(element.val() == '' || element.val() == value){
			element.addClass('defaultText').val(value);
		}
	});

	return element.blur();
}
