{$sSearchForm}

<div class="at-user-details">
	<div class="at-tabs">
        <div class="tabs-head">
            <a href="/?action=message_change_current_folder&id_message_folder=1" class="js-tab 
				{if !$smarty.session.message.current_folder_id || $smarty.session.message.current_folder_id==1}selected{/if}" data-tab="1">
                {$oLanguage->getMessage("Inbox")}
                <span>({$aMessageNumber.inbox})</span>
            </a>
            <a href="/?action=message_change_current_folder&id_message_folder=2" class="js-tab 
				{if $smarty.session.message.current_folder_id==2}selected{/if}" data-tab="1">
                {$oLanguage->getMessage("Outbox")}
                <span>({$aMessageNumber.outbox})</span>
            </a>
            <a href="/?action=message_change_current_folder&id_message_folder=3" class="js-tab 
				{if $smarty.session.message.current_folder_id==3}selected{/if}" data-tab="1">
                {$oLanguage->getMessage("Draft")}
                <span>({$aMessageNumber.draft})</span>
            </a>
            <a href="/?action=message_change_current_folder&id_message_folder=4" class="js-tab 
				{if $smarty.session.message.current_folder_id==4}selected{/if}" data-tab="1">
                {$oLanguage->getMessage("Archived")}
                <span>({$aMessageNumber.deleted})</span>
            </a>
            <a {if $smarty.session.message.is_starred}
					href="/?action=message_change_starred&is_starred=0"
						onclick="xajax_process_browse_url(this.href);return false;"
				{else}
					href="/?action=message_change_starred&is_starred=1"
						onclick="xajax_process_browse_url(this.href);return false;"
				{/if}
				class="js-tab {if $smarty.session.message.is_starred}selected{/if}" data-tab="1">
                <span>{if $smarty.session.message.is_starred}<img src="/image/starred_on.png" align="absmiddle" />{else}<img src="/image/starred_off.png" align="absmiddle" />{/if}</span>
            </a>
        </div>

        <div class="mob-tabs-select">
            <select class="js-select" onchange="document.location=this.options[this.selectedIndex].value;">
                <option value="/?action=message_change_current_folder&id_message_folder=1">{$oLanguage->getMessage("Inbox")} ({$aMessageNumber.inbox})</option>
				<option value="/?action=message_change_current_folder&id_message_folder=2">{$oLanguage->getMessage("Outbox")} ({$aMessageNumber.outbox})</option>
				<option value="/?action=message_change_current_folder&id_message_folder=3">{$oLanguage->getMessage("Draft")} ({$aMessageNumber.draft})</option>
				<option value="/?action=message_change_current_folder&id_message_folder=4">{$oLanguage->getMessage("Archived")} ({$aMessageNumber.deleted})</option>
            </select>
        </div>

        <div class="tabs-body">
            <form method="POST" enctype="multipart/form-data" name="message_form" id="message_form_id">
				<input type="hidden" name="action" value="search">
				{if $compose}
					<input type="hidden" name="compose" value="1">
				{/if}
				{$sMainSection}
			</form>
        </div>
    </div>
</div>