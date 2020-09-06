<div class="at-user-details">

    <div class="at-tabs">
        <div class="tabs-head">
            <a href="/pages/extension_td" class="js-tab {if $smarty.request.action=='extension_td'}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('import')}
            </a>
            <a href="/pages/extension_td_history_image" class="js-tab {if $smarty.request.action|strpos:'extension_td_history_image'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('history image')}
            </a>
            <a href="/pages/extension_td_history_characteristic" class="js-tab {if $smarty.request.action|strpos:'extension_td_history_characteristic'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('history characteristic')}
            </a>
            <a href="/pages/extension_td_history_cross" class="js-tab {if $smarty.request.action|strpos:'extension_td_history_cross'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('history cross')}
            </a>
            <a href="/pages/extension_td_history_applicability" class="js-tab {if $smarty.request.action|strpos:'extension_td_history_applicability'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('history applicability')}
            </a>
            <a href="/pages/extension_td_history_tree" class="js-tab {if $smarty.request.action|strpos:'extension_td_history_tree'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('history tree')}
            </a>
        </div>

        <div class="mob-tabs-select">
            <select class="js-select" onchange="document.location=this.options[this.selectedIndex].value;">
                <option value="/pages/extension_td_cat_info_import">{$oLanguage->GetMessage('import')}</option>
                <option value="/pages/extension_td_history_image">{$oLanguage->GetMessage('history image')}</option>
                <option value="/pages/extension_td_history_characteristic">{$oLanguage->GetMessage('history characteristic')}</option>
                <option value="/pages/extension_td_history_cross">{$oLanguage->GetMessage('history cross')}</option>
                <option value="/pages/extension_td_history_applicability">{$oLanguage->GetMessage('history applicability')}</option>
                <option value="/pages/extension_td_history_tree">{$oLanguage->GetMessage('history tree')}</option>
            </select>
        </div>

    </div>
</div>