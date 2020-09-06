<TABLE class=itemslist>
<tr>
	<th colspan=2>{$oLanguage->getDMessage('Email Preview')}</th>
</tr>
<tr><td><b>{$oLanguage->getDMessage('Id')}:</td><td>{$aData.id}</td></tr>
<tr><td><b>{$oLanguage->getDMessage('From')}:</td><td>{$aData.from}</td></tr>
<tr><td><b>{$oLanguage->getDMessage('From Name')}:</td><td>{$aData.from_name}</td></tr>
<tr><td><b>{$oLanguage->getDMessage('Address')}:</td><td>{$aData.address}</td></tr>
<tr><td><b>{$oLanguage->getDMessage('Subject')}:</td><td>{$aData.subject}</td></tr>
<tr><td></td><td>{$aData.body}</td></tr>
<tr><td><b>{$oLanguage->getDMessage('Post')}:</td><td>{$oLanguage->GetDateTime($aData.post)}</td></tr>
<tr><td><b>{$oLanguage->getDMessage('Sent')}:</td><td>{$oLanguage->GetDateTime($aData.sent_time)}</td></tr>
</table><br>
<input type=button value='{$oLanguage->getDMessage('<< Return')}'
 onClick=" xajax_process_browse_url('?{$sReturn|escape}'); return false; " class=submit_button>