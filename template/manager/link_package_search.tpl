<div class="at-user-details">

    <div class="at-tabs">
        <div class="tabs-head">
            <a href="/?action=manager_package_list" class="js-tab {if !$smarty.request.search_order_status}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('All')}
            </a>
            <a href="/?action=manager_package_list&search_order_status=work" class="js-tab {if $smarty.request.search_order_status=='work'}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('Work')}
            </a>
            <a href="/?action=manager_package_list&search_order_status=pending" class="js-tab {if $smarty.request.search_order_status=='pending'}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('Pending')}
            </a>
            <a href="/?action=manager_package_list&search_order_status=end" class="js-tab {if $smarty.request.search_order_status=='end'}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('End')}
            </a>
            <a href="/?action=manager_package_list&search_order_status=refused" class="js-tab {if $smarty.request.search_order_status=='refused'}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('Refused')}
            </a>
        </div>

        <div class="mob-tabs-select">
            <select class="js-select" onchange="document.location=this.options[this.selectedIndex].value;">
                <option value="/?action=manager_package_list">{$oLanguage->GetMessage('All')}</option>
                <option value="/?action=manager_package_list&search_order_status=work">{$oLanguage->GetMessage('Work')}</option>
                <option value="/?action=manager_package_list&search_order_status=pending">{$oLanguage->GetMessage('Pending')}</option>
                <option value="/?action=manager_package_list&search_order_status=end">{$oLanguage->GetMessage('End')}</option>
                <option value="/?action=manager_package_list&search_order_status=refused">{$oLanguage->GetMessage('Refused')}</option>
            </select>
        </div>

    </div>
</div>