<table>
	<tr>
	   <td>{$oLanguage->getMessage("Receive notifications")}:
	   	{$oLanguage->getContextHint("receive_notifications")}</td>
	   <td>
	   {include file='addon/mpanel/form_checkbox.tpl' sFieldName='receive_notification' bChecked=$aData.receive_notification}
	   </td>
  	</tr>

	<tr>
	   <td>{$oLanguage->getMessage("Copy Messages")}:
	   	{$oLanguage->getContextHint("customer_copy_messages")}</td>
	   <td>
	   {include file='addon/mpanel/form_checkbox.tpl' sFieldName='copy_message' bChecked=$aData.copy_message}
	   </td>
  	</tr>

	<tr>
		<td>{$oLanguage->getMessage("Notification Type")}:
		{$oLanguage->getContextHint("customer_notification_type")}</td>
		<td>
		{html_options name=data[notification_type]
			values=$aNotificationType
			output=$oLanguage->GetMessageArray($aNotificationType)
			selected=$aData.notification_type}
		</td>
	</tr>

	<tr>
	   <td align=center colspan=2><b>{$oLanguage->getMessage("Receive such notifications")}</b></td>

  	</tr>

{foreach from=$aNotification item=aItem}
	<tr>
	   <td>{$oLanguage->getMessage($aItem.code)}:</td>
	   <td>
	   {include file='addon/mpanel/form_checkbox.tpl' sFieldName=$aItem.code bChecked=$aItem.allowed}
	   </td>
  	</tr>
{/foreach}



</table>