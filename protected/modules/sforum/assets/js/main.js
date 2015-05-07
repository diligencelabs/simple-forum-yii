var fadeAway = function(id, time) {
	if( typeof time == 'undefined' )
		time = 3000;
	
	setTimeout("$('#" + id +"').fadeOut('slow')", time);
}

var ConfirmDelete = function(msg) {
	return confirm(msg);
}


jQuery.fn.extend({
	insertAtCaret: function(myValue){
		return this.each(function(i) {
			if (document.selection) {
				//For browsers like Internet Explorer
				this.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
				this.focus();
			}
			else if (this.selectionStart || this.selectionStart == '0') {
				//For browsers like Firefox and Webkit based
				var startPos = this.selectionStart;
				var endPos = this.selectionEnd;
				var scrollTop = this.scrollTop;
				this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
				this.focus();
				this.selectionStart = startPos + myValue.length;
				this.selectionEnd = startPos + myValue.length;
				this.scrollTop = scrollTop;
			} else {
				this.value += myValue;
				this.focus();
			}
		})
	}
});

var getCaretPosition =  function(texta){
	var caretPos = {start:null, end:null};

	if(texta.selectionStart || texta.selectionStart == 0) {
		caretPos.start = texta.selectionStart;
		caretPos.end = texta.selectionEnd;
	}
	else if(document.selection) {
		var range = document.selection.createRange();
		
		var rangeany = document.body.createTextRange();
		rangeany.moveToElementText(texta);
		
		var start;
		for (start = 0; rangeany.compareEndPoints('StartToStart', range) < 0; start++) {		
			rangeany.moveStart('character', 1);
		}
	
		texta.sel_start = start;
		
		caretPos.start = texta.sel_start;
		caretPos.end = texta.sel_start;			
	}
	
	return caretPos;
}


var storeCaret = function(textEl) {
	if (textEl.createTextRange) {
		textEl.caretPos = document.selection.createRange().duplicate();
	}
}
