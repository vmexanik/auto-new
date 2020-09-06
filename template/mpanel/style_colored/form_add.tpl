<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('style_colored')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)" >
<div class="card-body">
				<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('name')}:{$sZir}</label>
			<input type=hidden name=data[name] value="{$aData.name|escape}" class="form-control btn-sm">{$aData.name|escape}
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
		<label>{$oLanguage->getDMessage('value')}:{$sZir}</label>
		    {if $aData.name=='@image1'}
		        {include file='addon/mpanel/form_image.tpl' aData=$aData sFieldName=value}
		    {else}
	       <input type=text name=data[value] value="{$aData.value|escape}" class="form-control btn-sm">
	       {/if}
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