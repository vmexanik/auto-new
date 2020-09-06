<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Export xml')}</h3>
            </div>
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this,Array())">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Code')}:{$sZir}</label>
   			<input type=text name=data[code] value="{$aData.code|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Name')}:{$sZir}</label>
   			<input type=text name=data[name] value="{$aData.name|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('description')}:</label>
			{$oAdmin->getCKEditor('data[description]',$aData.description, 700, 300)}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('brand')}:</label>
	<br><a href="#" onclick="for(i={$iMinBrandId};i<={$iMaxBrandId};i++) if(document.getElementById('chkb'+(i))) document.getElementById('chkb'+(i)).checked=true; return false;">{$oLanguage->getDMessage('select all')}</a>
	<br><a href="#" onclick="for(i={$iMinBrandId};i<={$iMaxBrandId};i++) if(document.getElementById('chkb'+(i))) document.getElementById('chkb'+(i)).checked=false; return false;">{$oLanguage->getDMessage('select none')}</a>
	
	
		<div style="height: 600px;width:100%;overflow: auto;border: 1px solid #000000;">
		<table border=0 width=100% cellpadding=0 cellspacing=0>
		{foreach from=$aBrand item=aItem}
		<tr>
		<td style="padding:0; width:20px;"><input id="chkb{$aItem.id}" type="checkbox" name="data[id_brand][{$aItem.id}]" {if in_array($aItem.id,$aBrandId)}checked{/if}></td>
		<td nowrap><label for="chk{$aItem.id}">{$aItem.title}</label></td>
		</tr>
		{/foreach}
		</table>
		</div>
	

		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('provider')}:</label>
	<br><a href="#" onclick="for(i={$iMinProviderId};i<={$iMaxProviderId};i++) if(document.getElementById('chkp'+(i))) document.getElementById('chkp'+(i)).checked=true; return false;">{$oLanguage->getDMessage('select all')}</a>
	<br><a href="#" onclick="for(i={$iMinProviderId};i<={$iMaxProviderId};i++) if(document.getElementById('chkp'+(i))) document.getElementById('chkp'+(i)).checked=false; return false;">{$oLanguage->getDMessage('select none')}</a>
	
	
		<div style="height: 600px;width:100%;overflow: auto;border: 1px solid #000000;">
		<table border=0 width=100% cellpadding=0 cellspacing=0>
		{foreach from=$aProvider item=aItem}
		<tr>
		<td style="padding:0; width:20px;"><input id="chkp{$aItem.id}" type="checkbox" name="data[id_provider][{$aItem.id}]" {if in_array($aItem.id,$aProviderId)}checked{/if}></td>
		<td nowrap><label for="chk{$aItem.id}">{$aItem.name}</label></td>
		</tr>
		{/foreach}
		</table>
		</div>
	

		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('price group')}:</label>
	<br><a href="#" onclick="for(i={$iMinPGId};i<={$iMaxPGId};i++) if(document.getElementById('chkpg'+(i))) document.getElementById('chkpg'+(i)).checked=true; return false;">{$oLanguage->getDMessage('select all')}</a>
	<br><a href="#" onclick="for(i={$iMinPGId};i<={$iMaxPGId};i++) if(document.getElementById('chkpg'+(i))) document.getElementById('chkpg'+(i)).checked=false; return false;">{$oLanguage->getDMessage('select none')}</a>
	
	
		<div style="height: 600px;width:100%;overflow: auto;border: 1px solid #000000;">
		<table border=0 width=100% cellpadding=0 cellspacing=0>
		{foreach from=$aPriceGroup item=aItem}
		<tr>
		<td style="padding:0; width:20px;"><input id="chkpg{$aItem.id}" type="checkbox" name="data[id_price_group][{$aItem.id}]" {if in_array($aItem.id,$aPriceGroupId)}checked{/if}></td>
		<td nowrap><label for="chk{$aItem.id}">{$aItem.name}</label></td>
		</tr>
		{/foreach}
		</table>
		</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('count limit')}:</label>
   			<input type=text name=data[limit_count] value="{$aData.limit_count|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('filename')}:</label>
   			<input type=text name=data[filename] value="{$aData.filename|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			{include file='addon/mpanel/form_visible.tpl' aData=$aData}
		</div>
	</div>
</div>

<!-- body end -->
			</div>
<!-- /.card-body -->
		    <div class="card-footer">
		        <input type=hidden name=data[id] value="{$aData.id|escape}">
				{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
		   </div>
		   <!-- /.card-footer -->
		   </form>
		   
		</div>
	</div>
</div>
