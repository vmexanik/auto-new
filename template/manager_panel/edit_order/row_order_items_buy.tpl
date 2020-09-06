{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='term'}
<td> {$aRow.term}</td>
{elseif $sKey=='price'}
<td>{$oCurrency->PrintPrice($aRow.price,0,0,'<none>')}</td>
{elseif $sKey=='price_total'}
<td>{$oCurrency->PrintPrice($aRow.price_total,0,0,'<none>')}</td>
{elseif $sKey=='brand'}
<td>{if $aRow.cat_name_changed}{$aRow.cat_name_changed}{else}{$aRow.brand}{/if}</td>
{elseif $sKey=='code'}
<td>{if $aRow.code_changed}{$aRow.code_changed}{else}{$aRow.code}{/if}</td>
{elseif $sKey=='image'}
	<td><div class="at-list-element">
	    {if $aRow.image}
	        <div class="photo">
	            <div class="photo-view">
	                <i>
	                    <img src="{$aRow.image}" alt="{if $aRow.price_group_name}{$aRow.price_group_name} {/if}{if $aRow.name_translate}{$aRow.name_translate} {/if}{if $aRow.brand}{$aRowPrice.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRow.code}" title="{if $aRow.price_group_name}{$aRow.price_group_name} {/if}{if $aRow.name_translate}{$aRow.name_translate} {/if}{if $aRow.brand}{$aRowPrice.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRow.code}">
	                </i>
	            </div>
	        </div>
	    {else}
	        <div class="photo nophoto"></div>
	    {/if}
	    </div>
    </td>
{elseif $sKey=='action'}
<td>
<div class="col-lg-3"> 
	<input type="text" class="form-control" placeholder="" value="1" id="number_cart_{$aRow.id}">
</div>
<div class="col-lg-2">
    <button class="btn btn-sm btn-success" type="button"
    	onclick="xajax_process_browse_url('/?action=manager_panel_edit_order_add_find_apply&id={$aRow.id}&number='+ encodeURIComponent($('#number_cart_{$aRow.id}').val())+'&id_cp={$id_cart_package}')">{$oLanguage->getMessage('in_order')}</button>
</div>
</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}