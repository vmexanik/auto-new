<div class="at-user-details">

    <div class="at-tabs">
        <div class="tabs-head">
            <a href="/pages/price" class="js-tab {if $smarty.request.action=='price'}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('tab price')}
            </a>
            <a href="/pages/price_profile" class="js-tab {if $smarty.request.action|strpos:'price_profile'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('price profile')}
            </a>
            <a href="/pages/manager_cat_pref" class="js-tab {if $smarty.request.action|strpos:'manager_cat_pref'!==false}selected{/if}" data-tab="1">
                {$oLanguage->GetMessage('managercatpref')}
            </a>
            {if $oLanguage->getConstant('use_price_control',0)}
            	<a href="/pages/price_control_system" class="js-tab {if $smarty.request.action=='price_control_system' || $smarty.request.action=='price_control'}selected{/if}" data-tab="1">
            		{$oLanguage->GetMessage('price_control')}
		        </a>
		        <a href="/pages/price_control_change_code" class="js-tab {if $smarty.request.action|strpos:'price_control_change_code'!==false}selected{/if}" data-tab="1">
		            {$oLanguage->GetMessage('price_control_change_code')}
		        </a>
		        <a href="/pages/price_control_edit_code" class="js-tab {if $smarty.request.action|strpos:'price_control_edit_code'!==false}selected{/if}" data-tab="1">
		            {$oLanguage->GetMessage('price_control_edit_code')}
		        </a>
   		        <a href="/pages/price_control_locked_code" class="js-tab {if $smarty.request.action|strpos:'price_control_locked_code'!==false}selected{/if}" data-tab="1">
		            {$oLanguage->GetMessage('price_control_locked_code')}
		        </a>
            {/if}
        </div>

        <div class="mob-tabs-select">
            <select class="js-select" onchange="document.location=this.options[this.selectedIndex].value;">
                <option value="/pages/price">{$oLanguage->GetMessage('tab price')}</option>
                <option value="/pages/price_profile">{$oLanguage->GetMessage('price profile')}</option>
                <option value="/pages/manager_cat_pref">{$oLanguage->GetMessage('managercatpref')}</option>
            </select>
        </div>

    </div>
</div>