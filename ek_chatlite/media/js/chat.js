/**************************************************
 *
 * ChatLite extension for PunBB forum.
 *
 * @author Neck - http://www.eikylon.net
 * @version 0.5.1
 * @licence Creative Commons Attribution 3.0 Unported - http://creativecommons.org/licenses/by/3.0/
 *
 **************************************************/
ek_chatLite = {
	/**
	* Template, to format messages.
	*
	* @var Object
	*/
	msgTemplate: new Template('#{a}[#{d}#{h}:#{m}] <strong>&lt;#{name}&gt;</strong> #{msg}<br />'),

	/**
	* Is the script already sending a message ?
	*
	* @var Boolean
	*/
	isSending: false,

	/**
	* Initialize the chat: build UI and start refresher.
	*
	*/
	initialize: function () {
		if (!document.getElementById) { return }
		// get the logged in and base uri info
		this.param = this.pseudoGet('ek_chatlite/media/js/chat');

		// get the header or announcement we will use it to append our chatbox after
		var appendAfter = document.getElementById('brd-announcement');
		if(!appendAfter) appendAfter = document.getElementById('brd-visit');
		if(!appendAfter) return false;

		// Now we create the HTML structure for the chat.
		// basically looks like:
		// <div id="ek_chatLite">
		//   <h2>title</h2>
		//   <div class="chatBox">
		//     <div class="chatError"></div> (hidden)
		//     <div class="chatContent"></div>
		//     <img src="wait.gif" /> (hidden, only if logged)
		//     <input name="message" /> (only if logged)
		//   </div>
		// </div>
		var chatBlock = new Element('div', {id:'ek_chatLite'});
		chatBlock.appendChild(new Element('h2')).update(this.lang.title);
		var chatBox = chatBlock.appendChild(new Element('div', {'class':'chatBox'}));
		this.chatError = chatBox.appendChild(new Element('div', {'class':'chatError'})).hide();
		this.chatContent = chatBox.appendChild(new Element('div', {'class':'chatContent'})).update(this.lang.loading);
		if(this.param['logged'] > 0) {
			this.chatWait = chatBox.appendChild(new Element('img', {src:this.param['exturi']+'media/img/wait.gif', alt:'['+this.lang.waitImg_alt+']', title:this.lang.waitImg_title, style:'visibility:hidden;'}));
			this.chatInput = chatBox.appendChild(new Element('input', {name:'message'}));
			this.chatSend = chatBox.appendChild(new Element('input', {type:'submit', name:'chatSend', value:this.lang.chatSend_value})); // add submit button to form
		}
		//appendAfter.insert({after:chatBlock}); <- IE doesnt like this
		appendAfter.parentNode.insertBefore(chatBlock, appendAfter.nextSibling);

		// add event listeners
		if(this.param['logged'] > 0) {
			this.sizeInput();

			Event.observe(this.chatInput, 'keypress', function(e) {
				if(e.keyCode === Event.KEY_RETURN) {
					this.send();
					Event.stop(e);
				}
			}.bindAsEventListener(this));
			Event.observe(this.chatSend, 'click', function(e) {
				this.send();
			}.bindAsEventListener(this)); // add event handler for submit button
			Event.observe(window, 'resize', this.sizeInput.bindAsEventListener(this));
		}
		Event.observe(this.chatError, 'click', function(){this.chatError.hide();}.bindAsEventListener(this));

		// launch refresher
		this.pe = new this.refresher(this.param['exturi']+'data/chat.dat', {
			method: 'post',	frequency: 2.5, decay: 1.1,
			onChange: this.updater.bind(this),
			onFailure: this.error.bind(this, 'con')
		});
	},

	/**
	* Handle wait animation and send locking
	*
	*/
	sendingStart: function() {
		this.isSending = true;
		this.pe.stop();
		this.chatWait.setStyle({visibility:'visible'});
	},
	sendingStop: function() {
		this.isSending = false;
		this.pe.start();
		this.chatWait.setStyle({visibility:'hidden'});
	},

	/**
	* Submit a message
	*
	*/
	send: function() {
		// block the input
		if(this.chatIsSending == true) { return; }
		this.sendingStart();

		// process update
		new Ajax.Request(this.param['baseuri']+'misc.php?action=ek_chatlite', {
			method: 'post',
			parameters: 'do=post&'+this.chatInput.serialize(),
			onSuccess: function(response) {
				this.pe.lastCheck = response.headerJSON;
				this.updater(response.responseText);
				this.sendingStop();
				this.chatInput.value = '';
			}.bind(this),
			on401: this.error.bind(this, 'guest'),
			on403: this.error.bind(this, 'double'),
			on404: this.error.bind(this, 'empty'),
			onFailure: this.error.bind(this, 'con')
		});
	},

	/**
	* Delete a message
	*
	* @param object event
	* @param string message id
	*/
	del: function(e, msgId) {
		// confirm
		//if(!Event.isLeftClick(e)) { return; } <- its bugged for IE, need to wait for prototype update
		var ok=window.confirm(this.lang.delConfirm);
		if(!ok) { return; }

		// block the input
		if(this.chatIsSending == true) { return; }
		this.sendingStart();

		// process update
		new Ajax.Request(this.param['baseuri']+'misc.php?action=ek_chatlite', {
			method: 'post',
			parameters: 'do=del&msgId='+msgId,
			onSuccess: function(response) {
				this.pe.lastCheck = response.headerJSON;
				this.updater(response.responseText);
				this.sendingStop();
			}.bind(this),
			on401: this.error.bind(this, 'admin'),
			on404: this.error.bind(this, 'notFound'),
			onFailure: this.error.bind(this, 'con')
		});
	},

	/**
	* Update content of the chat.
	*
	* @param string the new content as JSON
	*/
	updater: function(chatContent) {
		// dates
		this.dToday = new Date();
		this.dYstd = new Date(this.dToday.getTime()-86400000);
		this.dToday = this.dateYMD(this.dToday);
		this.dYstd = this.dateYMD(this.dYstd);

		// parse content
		if(!chatContent.isJSON()) {
			this.chatContent.update(this.lang.noMessage);
			return;
		}
		chatContent = chatContent.evalJSON();
		chatContent = chatContent.inject('', this.message, this);
		this.chatContent.update(chatContent);
		if(this.param['logged'] == 2) {
			var s = this.chatContent.getElementsByTagName('span');
			for(var i=0; i<s.length; ++i) {
				Event.observe(s[i], 'click', this.del.bindAsEventListener(this, s[i].getAttribute('id')));
			}
		}

		// scroll
		var toScroll = this.chatContent.scrollHeight-this.chatContent.offsetHeight;
		if(toScroll < 0) toScroll = 0;
		this.chatContent.scrollTop = toScroll;
	},

	/**
	* Format a message.
	*
	* @param string accumulator, we will append the message to it
	* @param array message infos: id, name, date, message
	* @return string accumulator+formated message	
	*/
	message: function(chatContent, msgInfo) {
		var dMsg = new Date(msgInfo[2]);
		var dMsgYMD = this.dateYMD(dMsg);

		if(dMsgYMD === this.dToday && this.lang.date_today !== 'date') {
			var day = this.lang.date_today;
		} else if(dMsgYMD === this.dYstd && this.lang.date_ystd !== 'date') {
			var day = this.lang.date_ystd;
		} else if(this.lang.date_older !== 'date') {
			var day = this.lang.date_older;
		} else {
			var day = this.dateZero(dMsg.getDate())+'/'+this.dateZero(dMsg.getMonth()+1)+' ';
		}

		var tplInfo = {
			a:(this.param['logged'] == 2) ? '<span class="chatDel" id="'+msgInfo[0]+'">X</span> ' : '',
			d:day,
			h:this.dateZero(dMsg.getHours()),
			m:this.dateZero(dMsg.getMinutes()),
			name:msgInfo[1],
			msg:msgInfo[3]
		};

		return chatContent+this.msgTemplate.evaluate(tplInfo);
	},

	/**
	* Error message
	*
	* @param string Message Key
	*/
	error: function(msgKey) {
		this.chatError.update(this.lang.error+this.lang['error_'+msgKey]);
		this.chatError.show();
		this.sendingStop();
	},

	/**
	* Resize Input field.
	*
	*/
	sizeInput: function() {
		this.chatInput.setStyle({width:(this.chatContent.offsetWidth-90)+'px'});
	},

	/**
	 * Emulate the GET variables.
	 *
	 * @param string the file you want get vars for
	 * @return array associative array with name=>value
	 */
	pseudoGet : function(fileName) {
		// loop through all script tag looking for our file
		var s;
		var scriptTags = document.getElementsByTagName('script');
		for (var i = 0; i < scriptTags.length; ++i) {
			s = scriptTags[i];

			// if we got a correct one extract vars
			if(s.src && s.src.toLowerCase().match(fileName.toLowerCase()+'\\.js\\?')) {
				var v = s.src.toLowerCase().match(fileName.toLowerCase()+'\\.js\\?(.*)$');
				v = v[1].split('&');

				// make array and return it
				var r = new Array();
				for(var j=0; j < v.length; ++j) {
					v[j] = v[j].split('=');
					if(v[j].length == 2) {
						r[v[j][0].toLowerCase()] = v[j][1];
					}
				}
				return r;
			}
		}
	},

	/**
	 * Return a date in the y-m-d format for comparisons.
	 *
	 * @param object the date to parse
	 * @return string	 
	 */
	dateYMD: function(d) {
		var year = d.getFullYear().toString();
		var month = this.dateZero(d.getMonth());
		var day = this.dateZero(d.getDate());

		return year+month+day;
	},

	/**
	 * Adds zeros to the value until it got the desired length.
	 *
	 * @param int the value to modify
	 * @return string
	 */
	dateZero: function(n, to) {
		n = n.toString();
		to = (to || 2);
		while(n.length < to) {
			n = '0'+n;
		}
		return n;
	},

	/**
	* This class is inspirated from prototype's Ajax.PeriodicalUpdater()
	*
	*/
	refresher: Class.create(Ajax.Base, {
		initialize: function($super, url, options) {
			$super(options);
			this.onComplete = this.options.onComplete;
	
			this.frequency = (this.options.frequency || 2);
			this.options.decay = (this.options.decay || 1);
			this.decay = 1;
	
			this.updater = { };
			this.url = url;

			this.start();
		},
	
		start: function() {
			this.options.onComplete = this.updateComplete.bind(this);
			this.decay = 1;
			this.onTimerEvent();
		},
	
		stop: function() {
			this.updater.options.onComplete = undefined;
			clearTimeout(this.timer);
		},
	
		updateComplete: function(response) {
			(this.onComplete || Prototype.emptyFunction)(response);

			var check = (response.headerJSON || response.getHeader('Etag'));

			if(!check) {
				(this.options.onFailure || Prototype.emptyFunction)(response);
				this.stop();
				return;
			}

			if (this.options.decay) {
				if(check == this.lastCheck && this.decay < 60) {
					this.decay = this.decay * this.options.decay;
				}
				else {
					this.decay = 1;
					(this.options.onChange || Prototype.emptyFunction)(response.responseText);
				}
				this.lastCheck = check;
			}
			this.timer = this.onTimerEvent.bind(this).delay(this.decay * this.frequency);
		},
	
		onTimerEvent: function() {
			this.updater = new Ajax.Request(this.url, this.options);
		}
	})
};
// Init the chat once DOM is loaded.
Event.observe(window, 'load', function() {
	ek_chatLite.initialize();
});