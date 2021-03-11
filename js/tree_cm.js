function tree_toggle(event) {
	event = event || window.event;
	var clickedElem = event.target || event.srcElement;

	if (!hasClass(clickedElem, 'Expand')) {
		return
	}

	var node = clickedElem.parentNode;
	if (hasClass(node, 'ExpandLeaf')) {
		return
	}

	var newClass = hasClass(node, 'ExpandOpen') ? 'ExpandClosed' : 'ExpandOpen';
	var re =  /(^|\s)(ExpandOpen|ExpandClosed)(\s|$)/
	node.className = node.className.replace(re, '$1'+newClass+'$3')
}


function hasClass(elem, className) {
	return new RegExp("(^|\\s)"+className+"(\\s|$)").test(elem.className)
}

$(function() {
    $('.expand-p').click(function(){
        $(this).parent().parent().find('.ExpandClosed').removeClass('ExpandClosed').addClass('ExpandOpen');
        return false;
    });
    $('.expand-m').click(function(){
        $(this).parent().parent().find('.ExpandOpen').removeClass('ExpandOpen').addClass('ExpandClosed');
        return false;
    });
});

$(function() {
    $('.dContent>a.expand').click(function(){
        if (!($(this).closest('.Node').hasClass('ExpandLeaf'))){
            $(this).closest('.Node').toggleClass('ExpandOpen').toggleClass('ExpandClosed');
            return false;
        }
    });
});