<ul class="order-list nav nav-pills" role="tablist" style="display: inline-block;float: left;">
    <li role="presentation" {if !$search_order_status && !$filtered}class="active"{/if}>
    	<a href="/?action={$sAction}{if $search_all_manager}&search_all_manager=1{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('all')}</a>
    </li>
    <li role="presentation" {if $search_order_status=='new' && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}&search_order_status=new{if $search_all_manager}&search_all_manager=1{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('menu_new')}</a>
    </li>
    <li role="presentation" {if $search_order_status=='in_wait' && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}&search_order_status=in_wait{if $search_all_manager}&search_all_manager=1{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('menu_in_wait')}</a>
    </li>
    <li role="presentation" {if $search_order_status=='work' && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}&search_order_status=work{if $search_all_manager}&search_all_manager=1{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('menu_work')}</a>
    </li>
    <li role="presentation" {if $search_order_status=='assembled' && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}&search_order_status=assembled{if $search_all_manager}&search_all_manager=1{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('menu_assembled')}</a>
    </li>
    <li role="presentation" {if $search_order_status=='shipment' && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}&search_order_status=shipment{if $search_all_manager}&search_all_manager=1{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('menu_shipment')}</a>
    </li>
    <li role="presentation" {if $search_order_status=='shipment_2' && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}&search_order_status=shipment_2{if $search_all_manager}&search_all_manager=1{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('menu_shipment_2')}</a>
    </li>
    <li role="presentation" {if $search_order_status=='delivery' && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}&search_order_status=delivery{if $search_all_manager}&search_all_manager=1{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('menu_delivery')}</a>
    </li>    
    <li role="presentation" {if $search_order_status=='cover' && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}&search_order_status=cover{if $search_all_manager}&search_all_manager=1{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('menu_cover')}</a>
    </li> 
    <li role="presentation" {if $search_order_status=='end' && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}&search_order_status=end{if $search_all_manager}&search_all_manager=1{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('menu_end')}</a>
    </li>
    <li role="presentation" {if $search_order_status=='return' && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}&search_order_status=return{if $search_all_manager}&search_all_manager=1{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('menu_return')}</a>
    </li>
    <li role="presentation" {if $search_order_status=='refused' && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}&search_order_status=refused{if $search_all_manager}&search_all_manager=1{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('menu_refused')}</a>
    </li>
    <li role="presentation" {if $search_order_status=='archive' && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}&search_order_status=archive{if $search_all_manager}&search_all_manager=1{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('menu_archive')}</a>
    </li>    
	<li>&nbsp;</li><li>&nbsp;</li>
    <li role="presentation" {if !$search_all_manager && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}{if $search_order_status}&search_order_status={$search_order_status}{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('only_own')}</a>
    </li>
    <li role="presentation" {if $search_all_manager && !$filtered}class="active"{/if}>
       	<a href="/?action={$sAction}&search_all_manager=1{if $search_order_status}&search_order_status={$search_order_status}{/if}" onclick="xajax_process_browse_url(this.href); return false;">{$oLanguage->getMessage('all')}</a>
    </li>    
</ul>
<button type="button" class="btn btn-default btn-sm" style="margin-top: 6px;margin-left:2px;"
	onclick="xajax_process_browse_url('/?action=manager_panel_create_order'); return false;">
	<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {$oLanguage->getMessage('create package')}
</button>
<div style="float:right;padding: 15px 20px 0 0;">
	<a href="{strip}/?action=manager_panel_manager_package_list_search
			{if $search_all_manager}&search_all_manager=1{/if}
			{if $search_order_status}&search_order_status={$search_order_status}{/if}
			{/strip}"
		onclick="xajax_process_browse_url(this.href); return false;">
		<img src="/image/design/lupa2.png" style="cursor:pointer;">
	</a>
</div>