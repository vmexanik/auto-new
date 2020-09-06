<div class="at-filter-action-layoot">
    <div class="filter-block">
        <div class="filter-inner" style="height: 221px;">
            <span class="span_h2 at-caption">{$oLanguage->GetMessage('search by car model')}</span>
            <label>
                <span>{$oLanguage->GetMessage('Brand search')}: </span>
                <select name="id_mfa" id="id_mfa" onchange="xajax_process_browse_url('/?action=rubricator_set_make&data[id_make]='+$(this).val(){if $smarty.request.action=='rubricator_subcategory'}+'&show=1'{/if} );return false;">
                    {html_options options=$aMakeName selected=$smarty.request.data.id_make}
                </select>
            </label>
            <br />
            <label>
                <span>{$oLanguage->GetMessage('Model')}: </span>
                { include file=rubricator/select_model.tpl }
            </label>
            <br />
            <label>
                <span>{$oLanguage->GetMessage('Year')}: </span>
        		{ include file=rubricator/select_model_detail.tpl }
            </label>
            
            <label>
                <span></span>
        		{ include file=rubricator/button_rubricator.tpl }
            </label>
            
            <div id="model_detail_info">
        	{ include file=rubricator/model_detail_info.tpl }
        	</div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="clear">&nbsp;</div>
</div>