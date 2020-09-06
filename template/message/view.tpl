<input type="hidden" name="id_message" value="{$id_message}">



<table class="at-tab-table">
<tr>
    <td>
        <div class="order-num">{$oLanguage->GetMessage('Date')}</div>
        {$post_date}
    </td>
</tr>
<tr>
    <td>
        <div class="order-num">{$oLanguage->GetMessage('From')}</div>
        {$aMessage.from}
    </td>
</tr>
<tr>
    <td>
		{if $to_input}<div class='warning_message'>*{$oLanguage->getMessage("Enter Nickname Only")}</div>{/if}
        <div class="order-num">{$oLanguage->GetMessage('To')}</div>
        {if $to_input} <input type=text name=to value="{$aMessage.to}{$smarty.request.compose_to}">{/if}
        {if $to_text}{$aMessage.to}{/if}
    </td>
</tr>
<tr>
    <td>
        <div class="order-num">{$oLanguage->GetMessage('Subject')}</div>
    	{if $subject_input } <input type=text name=subject value="{$aMessage.subject}"> {/if}
    	{if $subject_text } {$aMessage.subject}{/if}
    </td>
</tr>
<tr>
    <td>
        <div class="order-num">{$oLanguage->GetMessage('message')}</div>
        <div style="font-size:12px;margin-bottom:0">
        {if $textarea_begin }<textarea rows='6' cols='60' name='text'>{/if}{if $textarea_begin }{$text}{else}{$text|nl2br}{/if}{if $textarea_end }</textarea>{/if}
        {if $textarea_reply }<b>{$oLanguage->getMessage("Reply to message")}</b>:<textarea rows=6 cols=60 name=reply_text readonly style="width:531px;">{$reply_text}</textarea>{/if}
        </div>
        {if $smarty.request.action=="message_preview" || $smarty.request.action=="message_reply" || $smarty.request.action=="message_forward"}
        {if $aFiles|@count>0}
        <hr>
        {$oLanguage->GetText("message_attachment: view header")}
        {foreach from=$aFiles item=aValue}
        <a href="{$aValue.file_link}"><img src="{$oLanguage->GetCOnstant('message_attachment: attach image','/image/attach.png')}">&nbsp;{$aValue.file_name}</a>
        {/foreach}
        {/if}
        {/if}
        {if $smarty.request.action!="message_preview" || $bDraft==1}
        <hr>
        {$oLanguage->GetText("message_attachment: add header")}
        <table id="attachment" border=0 align=center>
            <tbody>
              <tr align="right">
                <td><input type="file" size=70 name="patch1" accept="image/*"></td>
              </tr>
            </tbody>
        </table>
        	
        <input type="button" class='at-btn' value="{$oLanguage->getMessage("Add line")}" onclick="javascript:maf.AddRow(this.form);" />
        {/if}
    </td>
</tr>
</table>

{if $compose_button }<input class=at-btn type=button value='{$oLanguage->getMessage("Compose")}'
	onclick="change_form_action('message_form_id','message_compose');">
{/if}
{if $reply_button }<input class=at-btn type=button value='{$oLanguage->getMessage("Reply")}'
	onclick="change_form_action('message_form_id','message_reply');">
{/if}
{if $delete_button }<input class=at-btn type=button value='{$oLanguage->getMessage("Delete")}'
	onclick="change_form_action('message_form_id','message_delete');">
{/if}
{if $send_button }<input class=at-btn type=button value='{$oLanguage->getMessage("Send")}'
	onclick="change_form_action('message_form_id','message_send');">
{/if}
{if $draft_button }<input class=at-btn type=button value='{$oLanguage->getMessage("Save Draft")}'
	onclick="change_form_action('message_form_id','message_draft');">
{/if}
{if $forward_button }<input class=at-btn type=button value='{$oLanguage->getMessage("Forward")}'
	onclick="change_form_action('message_form_id','message_forward');">
{/if}
{if $discard_button }<input class=at-btn type=button value='{$oLanguage->getMessage("Discard")}'
	onclick=" location.href='/?action=message';">
{/if}

<select name='move_to_folder' style="width: 200px!important;;height: 55px;">
	<option value=1>{$oLanguage->getMessage("Inbox")}</option>
	<option value=2>{$oLanguage->getMessage("Outbox")}</option>
	<option value=3>{$oLanguage->getMessage("Draft")}</option>
	<option value=4>{$oLanguage->getMessage("Deleted")}</option>
</select>

<input class=at-btn type=button class='at-btn' value='{$oLanguage->getMessage("Move")}' onclick="change_form_action('message_form_id','message_move_to_folder');">
	