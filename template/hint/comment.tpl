{if !$bXajaxAssign}
<form id='123123123' method=post>
<span>
<a href="javascript:;" onclick="show_hide('comment_{$sSection}_{$iRefId}','none')"
	><img src='/image/comment.png' hspace=1 align=absmiddle
		>&nbsp;<span id='comment_link_popup_id_{$iRefId}'
		>{if $aComment}{$oLanguage->getMessage("Comment link popup $sSection")}{/if}</span></a>

<div align=left
	style="
	{if !$bXajaxAssign}display: none;{/if}
	width: 400px;" class="tip_div" id="comment_{$sSection}_{$iRefId}">
{/if}
	<p>
{foreach from=$aComment item=aItem}
	<b>{$aItem.name}</b> {$aItem.post_date}: <b>{$aItem.content}</b><br>
{/foreach}
	</p>

<b>{$oLanguage->getMessage("Add new customer comment")}</b><br>

<input name="action" value="comment_popup_post" type="hidden">

<textarea name=data[content] cols="50" rows="5"></textarea>

<p>
<div style="float:left;"><input name="submit" value="{$oLanguage->getMessage('Submit comment')}" type="button" class='at-btn'
		onclick="xajax_process_form(xajax.getFormValues(this.form));">
</div>
<div style="float:right;"><input name="submit" value="{$oLanguage->getMessage('Close comment')}" type="button" class='at-btn'
		onclick="show_hide('comment_{$sSection}_{$iRefId}','none');">
</div>



<input name=data[section] value="{$sSection}" type="hidden">
<input name=data[ref_id] value="{$iRefId}" type="hidden">


{if !$bXajaxAssign}
</div>
</span>
</form>
{/if}