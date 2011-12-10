function isValidSize(val) {
    if( val == '' ) {
		return true;
    } else {
    	var num = parseFloat(val);
    	if( num || num == 0) {
    		return val.match('px$|em$|%$');
	}  else {
			return false;
		}
	}
}
function isValidHorizontalPosition(val) {
	if ( val == 'left' || val == 'center' || val == 'right') {
		return true;
	} else return false;
}
function isValidVerticalPosition(val) {
	if ( val == 'top' || val == 'center' || val == 'bottom') {
		return true;
	} else return false;
}
function checkValid( element, checkFunction ) {
	var val = jQuery.trim(element.value);
	element.value = val;
	if(checkFunction(val)) {
		inputHasChanged(element);
		element.style.background = successColor;
	} else {
		jQuery(element).css('background-color', failColor).animate({'background-color': '#FFF'}, 'slow');
		element.value = '';
	}
}
function checkValidSize(sizeInputEl) {
	checkValid( sizeInputEl, isValidSize );
}
function checkValidHorizontalPosition( posInputEl ) {
	checkValid( posInputEl, function(val) {
			return ( isValidSize(val) || isValidHorizontalPosition(val) );
		}
	);
}
function checkValidVerticalPosition( posInputEl ) {
	checkValid( posInputEl, function(val) {
			return ( isValidSize(val) || isValidVerticalPosition(val) );
		}
	);
}