	&nbsp;&nbsp;<a href="javascript:;" onclick=" $('#yestarday_div').show(); return false;"
		>{$oLanguage->GetDMessage('Yestarday report')} <img src="/image/icn_arrow_anchor.gif"></a>

<div id='yestarday_div' style="display: none;">
	<div style="float: right;"> <a href='' onclick=" $('#yestarday_div').hide(); return false;"
		><img src='/image/delete.png' width=10px /></a>
	</div>

	<input type=checkbox name=search[is_yestarday_report] value='1'
		style="width: 22px;" /> <b>{$oLanguage->GetDMessage('Show yestarday money ordered')}:</b>
&nbsp;&nbsp;

	<a href='javascript:;' onclick="$('#date_from').val('{$smarty.now-1*86400|date_format:"%d.%m.%Y"}');
		$('#date_to').val('{$smarty.now|date_format:"%d.%m.%Y"}');"
		>{$oLanguage->GetDMessage('For Yestarday')}</a>&nbsp;|
	<a href='javascript:;' onclick="$('#date_from').val('{$smarty.now-2*86400|date_format:"%d.%m.%Y"}');
		$('#date_to').val('{$smarty.now|date_format:"%d.%m.%Y"}');"
		>{$oLanguage->GetDMessage('for 2 days')}</a>&nbsp;|
	<a href='javascript:;' onclick="$('#date_from').val('{$smarty.now-3*86400|date_format:"%d.%m.%Y"}');
		$('#date_to').val('{$smarty.now|date_format:"%d.%m.%Y"}');"
		>{$oLanguage->GetDMessage('for 3 days')}</a>&nbsp;|
	<a href='javascript:;' onclick="$('#date_from').val('{$smarty.now-7*86400|date_format:"%d.%m.%Y"}');
		$('#date_to').val('{$smarty.now|date_format:"%d.%m.%Y"}');"
		>{$oLanguage->GetDMessage('for a week')}</a>

	<hr>
</div>