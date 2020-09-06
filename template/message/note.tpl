<div id='message_note'>
<table width="500" cellspacing=0 cellpadding=0 class="datatable" style="margin-bottom: 10px;">
<tr>
	<th style="padding: 5px; font-size: 14px;"><nobr><font color=red>{$oLanguage->getMessage('Message to you')} :</font> {$aMessageNote.name}</th>
</tr>
<tr>
<td style="padding: 5px;">
<p>{$aMessageNote.description}</p>
<div align=center>
<input type=button class='at-btn' onClick="javascript: xajax_process_browse_url('?action=message_note_close&id={$aMessageNote.id}'); return false;"
	value="{$oLanguage->getMessage('Close Message Note')}">
<div>
</td>
</tr>
</table>
</div>