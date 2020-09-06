function send_param(href, sAction, sValue)
{
	$('.js-select-'+sAction).find('span').html(sValue);
	xajax_process_browse_url(href);
	$('.js-select-'+sAction).removeClass('opened');
	$('.js-select-'+sAction).find('.select-drop').hide();
	return false;
}