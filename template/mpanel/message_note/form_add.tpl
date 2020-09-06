<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Message Note')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Login')}:{$sZir}</td>
   <td><input type=text name=data[login] value="{$aData.login|escape}"></td>
</tr>

<tr>
   <td>{$oLanguage->getDMessage('Reply To')}:</td>
   <td><input type=text name=data[reply_to] value="{$aData.reply_to|escape}"></td>
</tr>
<tr>
   <td>{$oLanguage->getDMessage('Name')}:{$sZir}</td>
   <td><input type=text name=data[name] value="{$aData.name|escape}"></td>
</tr>

<tr>
   <td>{$oLanguage->getDMessage('Description')}:{$sZir}</td>
   <td><textarea name=data[description]>{$aData.description}</textarea></td>
</tr>

<tr>
   <td>{$oLanguage->getDMessage('URL')}:</td>
   <td><input type=text name=data[url] value="{$aData.url|escape}"></td>
</tr>

<tr>
   <td>{$oLanguage->getDMessage('Is Closed')}:</td>
   <td><input type="hidden" name=data[is_closed] value="0">
   <input type=checkbox name=data[is_closed] value='1' style="width:22px;" {if $aData.is_closed}checked{/if}></td>
</tr>

</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>