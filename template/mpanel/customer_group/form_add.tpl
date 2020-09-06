<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Customer group')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this,Array())">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Name')}:</label>
   			<input type=text name=data[name] value='{$aData.cg_name}'  class="form-control btn-sm">
		</div>
	</div>
</div>
   
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Group Discount (%)')}{$sZir}:</label>
   			<input type=text name=data[group_discount] value='{if $aData.group_discount}{$aData.group_discount}{else}0{/if}' class="form-control btn-sm">
		</div>
	</div>
</div>
   
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Description')}:</label>
   			{$oAdmin->getCKEditor('data[description]',$aData.description, 650, 300)}
		</div>
	</div>
</div>
   
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Price rount')}:</label>
   			<input type=text name=data[price_round] value='{$aData.price_round}' class="form-control btn-sm">
		</div>
	</div>
</div>
   
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Hours expired cart')}:</label>
		   <input type=text name=data[hours_expired_cart] value='{if $aData.hours_expired_cart}{$aData.hours_expired_cart}
		   		{else}{$oLanguage->getConstant('customer_cart_expired_default','24')}{/if}' class="form-control btn-sm">
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
				<input type=hidden name=data[user_id] value="{$aData.id_user|escape}">
				{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
		   </div>
		   <!-- /.card-footer -->
		   </form>
		   
		</div>
	</div>
</div>
