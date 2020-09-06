<div class="at-user-details">

    <div class="at-tabs">
        <div class="tabs-head">
            <a href="/pages/manager_customer" class="js-tab {if $smarty.request.action|strpos:'manager_customer'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('customers')}
            </a>
            <a href="/pages/manager_provider" class="js-tab {if $smarty.request.action|strpos:'manager_provider'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('providers')}
            </a>
            <a href="/pages/finance_customer" class="js-tab {if $smarty.request.action|strpos:'finance_customer'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('finance customer')}
            </a>
            <a href="/pages/finance_provider" class="js-tab {if $smarty.request.action|strpos:'finance_provider'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('finance provider')}
            </a>
            <a href="/pages/finance_profit" class="js-tab {if $smarty.request.action|strpos:'finance_profit'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('finance profit')}
            </a>
        </div>

        <div class="mob-tabs-select">
            <select class="js-select" onchange="document.location=this.options[this.selectedIndex].value;">
                <option value="/pages/manager_customer">{$oLanguage->GetMessage('customers')}</option>
                <option value="/pages/manager_provider">{$oLanguage->GetMessage('providers')}</option>
                <option value="/pages/finance_customer">{$oLanguage->GetMessage('finance customer')}</option>
                <option value="/pages/finance_provider">{$oLanguage->GetMessage('finance provider')}</option>
                <option value="/pages/finance_profit">{$oLanguage->GetMessage('finance profit')}</option>
            </select>
        </div>

    </div>
</div>