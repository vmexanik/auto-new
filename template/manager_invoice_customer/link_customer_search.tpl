<div class="at-user-details">

    <div class="at-tabs">
        <div class="tabs-head">
            <a href="/?action=manager_invoice_customer&search[num_rating]=0" class="js-tab {if $smarty.request.search.num_rating==0}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('All')}
            </a>
            {foreach from=$aRatingAssoc item=aItem key=sKey}
            <a href="/?action=manager_invoice_customer&search[num_rating]={$sKey}" class="js-tab {if $smarty.request.search.num_rating==$sKey}selected{/if}" data-tab="1">
                {$aItem}
            </a>
            {/foreach}
        </div>

        <div class="mob-tabs-select">
            <select class="js-select" onchange="document.location=this.options[this.selectedIndex].value;">
                <option value="/?action=manager_invoice_customer&search[num_rating]=0">{$oLanguage->GetMessage('All')}</option>
                {foreach from=$aRatingAssoc item=aItem key=sKey}
                <option value="/?action=manager_invoice_customer&search[num_rating]={$sKey}">{$aItem}</option>
                {/foreach}
            </select>
        </div>

    </div>
</div>