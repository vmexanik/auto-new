<div class="at-user-details">

    <div class="at-tabs">
        <div class="tabs-head">
            <a href="/pages/catalog_cross" class="js-tab {if $aTemplateParameter=='catalog_cross'}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('cross')}
            </a>
            <a href="/pages/catalog_cross_stop" class="js-tab {if $aTemplateParameter=='catalog_cross_stop'}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('cross_stop')}
            </a>
        </div>

        <div class="mob-tabs-select">
            <select class="js-select" onchange="document.location=this.options[this.selectedIndex].value;">
                <option value="/pages/catalog_cross">{$oLanguage->GetMessage('cross')}</option>
                <option value="/pages/catalog_cross_stop">{$oLanguage->GetMessage('cross_stop')}</option>
            </select>
        </div>

    </div>
</div>