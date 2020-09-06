{if !$oContent->isMobile()} 

{literal}
<script type="text/javascript" src="/js/floating_panel.jquery.js?1"></script>
<script type="text/javascript">
$(function() {
  $('#fixed_block').floating_panel({
        'fromCenter': $(window).width()/2-($(window).width()-810)/2,
        'fromTop': 0,
        'minTop': 140,
        'location': 'left'
    });
});
</script>
{/literal}
<div class="fixed_block" id="fixed_block" >
    <a href="#"  onclick="toggle_link(this, '#h_block', 'manager_order_block_hidden'); return false;"
		class="toggle_link {if $smarty.cookies.manager_order_block_hidden}selected{/if}"
		>{$oLanguage->getMessage("Cart Order Items")}</a>
    <div class="block_cont" id="h_block" {if $smarty.cookies.manager_order_block_hidden}style="display: none;"{/if}>
    <ul class="items">
    <li style="padding-top: 3px;">{$oLanguage->getMessage("Change on")}</li>
    <li><a href="#" class="gl_button" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want to change status?")}'))
	mt.ChangeActionSubmit(document.getElementById('table_form'),'manager_change_status_new'); return false;"
    ><span><i class="new">{$oLanguage->getMessage("new")}</i></span></a></li>

    <li><a href="#" class="gl_button" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want to set work status?")}'))
	mt.ChangeActionSubmit(document.getElementById('table_form'),'manager_change_status_work'); return false;"
    ><span><i class="in_work">{$oLanguage->getMessage("work")}</i></span></a></li>

    <li><a href="#" class="gl_button" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want to set confirmed status?")}'))
	mt.ChangeActionSubmit(document.getElementById('table_form'),'manager_change_status_confirmed'); return false;"
    ><span><i class="confirm">{$oLanguage->getMessage("confirmed")}</i></span></a></li>

    <li><a href="#" class="gl_button" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want to set road status?")}'))
	mt.ChangeActionSubmit(document.getElementById('table_form'),'manager_change_status_road'); return false;"
    ><span><i class="working">{$oLanguage->getMessage("road")}</i></span></a></li>

    <li><a href="#" class="gl_button" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want to set refused status ?")}'))
	mt.ChangeActionSubmit(document.getElementById('table_form'),'manager_change_status_refused'); return false;"
    ><span><i class="confirm">{$oLanguage->getMessage("refused")}</i></span></a></li>
    
    <li><a href="#" class="gl_button" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want to set store status?")}'))
	mt.ChangeActionSubmit(document.getElementById('table_form'),'manager_change_status_store'); return false;"
    ><span><i class="stock">{$oLanguage->getMessage("store")}</i></span></a></li>

    <li><a href="#" class="gl_button" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want to set end status?")}'))
	mt.ChangeActionSubmit(document.getElementById('table_form'),'manager_change_status_end'); return false;"
    ><span><i class="delivered">{$oLanguage->getMessage("end")}</i></span></a></li>

    <li><a href="#" class="gl_button"><span>{$oLanguage->GetMessage('More actions')} &dArr;</span></a>
    	<ul>
    		<li><a href="#" onclick="mt.ChangeActionSubmit(document.getElementById('table_form'),'manager_export');"
    		>{$oLanguage->getMessage("Export selected to Excel")}</a></li>
    		<li><a href="/?action=manager_export_all">{$oLanguage->getMessage("Export all filtered to Excel")}</a></li>
    		<li><a href="/?action=manager_import_status">{$oLanguage->GetMessage("Import statuses from Excel")}</a></li>
    	</ul>
    </li>
    </ul>
        <div class="clear">&nbsp;</div>
    </div>
</div>

{/if}