<div class="at-user-top">
    <div class="at-user-card">
        <div class="inner js-cabinet-block">
            <div class="image">
                <a href="javascript:void(0);" style="background-image: url('/image/user-nophoto.png')"></a>
            </div>

            <div class="data">
                <div class="caption">{$oLanguage->getMessage("Your manager")}</div>
                <div class="name">{$aAuthUser.manager_name}</div>
                <br />
                <a class="link" href="/?action=message_compose&amp;compose_to={$aAuthUser.manager_login}">{$oLanguage->getMessage("Send message to manager")}</a>

                <div class="massages">
                    <a href="/pages/message">{$oLanguage->GetMessage('new messages')} <span>{$aTemplateNumber.message_number}</span></a>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="at-user-settings">
        <div class="inner js-cabinet-block">
            <div class="caption">{$oLanguage->GetMessage('My profile')}</div>

            <a href="/pages/customer_profile" class="settings-link">Настроить</a>

            <div class="login">
                <a href="/pages/customer_profile">{$aAuthUser.name}</a>
            </div>

            <table>
                <tr>
                    <td>{$oLanguage->GetMessage('Discount Static')}:</td>
                    <td>{$aAuthUser.discount_static}%</td>
                </tr>
                <tr>
                    <td>{$oLanguage->GetMessage('Discount Dynamic')}:</td>
                    <td>{$aAuthUser.discount_dynamic}%</td>
                </tr>
                <tr>
                    <td>{$oLanguage->GetMessage('Group Discount')}:</td>
                    <td>{$aAuthUser.group_discount}%</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="clear"></div>
</div>

<div class="at-user-details">
    <div class="header">{$oLanguage->getMessage("Current orders")}</div>

    <div class="at-tabs">
        <div class="tabs-head">
            <a href="/?action=dashboard&status=all_except_archive" class="js-tab {if !$smarty.request.status || $smarty.request.status=='all_except_archive'}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('All except Archive')}
                <span>({$aDashboardOrder.all_except_archive})</span>
            </a>
            <a href="/?action=dashboard&status=refused" class="js-tab {if $smarty.request.status && $smarty.request.status=='refused'}selected{/if}" data-tab="2">
                {$oLanguage->GetMessage('Refused')}
                <span>({$aDashboardOrder.refused})</span>
            </a>
            <a href="/?action=dashboard&status=pending" class="js-tab {if $smarty.request.status && $smarty.request.status=='pending'}selected{/if}" data-tab="3">
                {$oLanguage->GetMessage('Pending')}
                <span>({$aDashboardOrder.pending})</span>
            </a>
            <a href="/?action=dashboard&status=store" class="js-tab {if $smarty.request.status && $smarty.request.status=='store'}selected{/if}" data-tab="4">
                {$oLanguage->GetMessage('store')}
                <span>({$aDashboardOrder.store})</span>
            </a>
        </div>

        <div class="mob-tabs-select">
            <select class="js-select" onchange="document.location=this.options[this.selectedIndex].value;">
                <option value="/?action=dashboard&status=all_except_archive">{$oLanguage->GetMessage('All except Archive')} ({$aDashboardOrder.all_except_archive})</option>
                <option value="/?action=dashboard&status=refused">{$oLanguage->GetMessage('Refused')} ({$aDashboardOrder.refused})</option>
                <option value="/?action=dashboard&status=pending">{$oLanguage->GetMessage('Pending')} ({$aDashboardOrder.pending})</option>
                <option value="/?action=dashboard&status=store">{$oLanguage->GetMessage('store')} ({$aDashboardOrder.store})</option>
            </select>
        </div>

        <div class="tabs-body">
            {$sDashboardOrder}
        </div>
    </div>
</div>

{$sDashboardVinRequest}

{$sDashboardPriceSearchLog}    