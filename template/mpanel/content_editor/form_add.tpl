<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Content editor')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this,Array())">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Select page for edit')}:</label>
			<select name=data[id] id='drop_down_id'  class="form-control btn-sm"
				onChange="xajax_process_browse_url('?action=content_editor_change&data[id]='+$('#drop_down_id').val());">
				{foreach from=$aDropDown item=aItem}
					<option value='{$aItem.id}' {if $aItem.id==$aData.id}selected{/if}>
					{if $aItem.level>1}&nbsp;&nbsp;{/if}{if $aItem.level>2}&nbsp;&nbsp;{/if}
					{$aItem.name}</option>
				{/foreach}
			</select>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			{$sTextEditor}
		</div>
	</div>
</div>

{if $oLanguage->GetConstant('mpanel:is_left_bottom_text_active')}
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_text_left_visible' bChecked=$aData.is_text_left_visible sOnClick="$('#text_left_editor_id').toggle();"}
			<label>{$oLanguage->getDMessage('is_text_left_visible')}</label>

	<span id='text_left_editor_id' {if !$aData.is_text_left_visible}style="display: none;"{/if}>
		{$sTextLeftEditor}
	</span>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_text_bottom_visible' bChecked=$aData.is_text_bottom_visible sOnClick="$('#text_bottom_editor_id').toggle();"}
			<label>{$oLanguage->getDMessage('is_text_bottom_visible')}</label>

	<span id='text_bottom_editor_id' {if !$aData.is_text_bottom_visible}style="display: none;"{/if}>
		{$sTextBottomEditor}
	</span>
		</div>
	</div>
</div>
{/if}


<!-- body end -->
			</div>
<!-- /.card-body -->
		    <div class="card-footer">
		        <input type=hidden name=action value=content_editor_apply>
				{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction bHideReturn=true}
		   </div>
		   <!-- /.card-footer -->
		   </form>
		   
		</div>
	</div>
</div>