<h2>{$oLanguage->GetMessage('bulk_messages')}</h2>

{foreach from=$aUserNotification item=aItem}
<b>{$aItem.subject}</b> - {$aItem.post_date} -
	<a href='{$sLetterUrl}/?action=message_preview_user_notification&id={$aItem.id}' target=_blank>{$oLanguage->GetMessage('Preview')}</a>
<br>
{/foreach}