<div class="at-user-details">

    <div class="at-tabs">
        <div class="tabs-head">
            <a href="/pages/finance_reestr_pko" class="js-tab {if $smarty.request.action|strpos:'finance_reestr_pko'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('reestr_pko')}
            </a>
            <a href="/pages/finance_reestr_bv" class="js-tab {if $smarty.request.action|strpos:'finance_reestr_bv'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('reestr_bv')}
            </a>
            <a href="/pages/finance_reestr_rko" class="js-tab {if $smarty.request.action|strpos:'finance_reestr_rko'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('reestr_rko')}
            </a>
        </div>

        <div class="mob-tabs-select">
            <select class="js-select" onchange="document.location=this.options[this.selectedIndex].value;">
                <option value="/pages/finance_reestr_pko">{$oLanguage->GetMessage('reestr_pko')}</option>
                <option value="/pages/finance_reestr_bv">{$oLanguage->GetMessage('reestr_bv')}</option>
                <option value="/pages/finance_reestr_rko">{$oLanguage->GetMessage('reestr_rko')}</option>
            </select>
        </div>

    </div>
</div>