<td>{$aRow.id}</td>
<td><b>{$aRow.address}</b> {if $aRow.description}<br>{$aRow.description}{/if}</td>
<td><b>{$aRow.from_email}</b><br>{$aRow.from_name}</td>
<td>{$aRow.subject}<br>
{if $aRow.attach_code}
    {assign var='path' value=$smarty.const.SERVER_PATH|cat:$aRow.attach_file}
    {if $path|file_exists}
	<b>{$oLanguage->GetDMessage('attach_code')}: <a href="{$aRow.attach_file}"><img src="/libp/mpanel/images/small/paperclip.png" align="absmiddle" hspace="3"> {$aRow.attach_code}</a></b>
    {else}
	<b>{$oLanguage->GetDMessage('attach_code')}: <img src="/libp/mpanel/images/small/paperclip.png" align="absmiddle" hspace="3"><span style="color:grey">{$aRow.attach_code}</span></b>
    {/if}
{/if}
</td>
<td>{$oLanguage->GetDateTime($aRow.post)}<br><b>{$oLanguage->GetDateTime($aRow.sent_time)}</b></td>
<td>{$aRow.priority}</td>
<td>
{include file='addon/mpanel/base_row_preview.tpl' sBaseAction=$sBaseAction}
</td>