<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 	{$oLanguage->GetDMessage('Reverse for customer')}: {$aCustomer.login}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td>{$oLanguage->getDMessage('reverse amount')}:{$sZir}</td>
   <td><input type=text name=data[amount] value='{$aData.amount|escape}'></td>
</tr>
</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
<input type=hidden name=action value=user_account_log_reverse_apply>
</FORM>