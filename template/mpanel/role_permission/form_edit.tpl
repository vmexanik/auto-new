<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Roles permissions')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('action')}:</label>
			<b>{$aData.action_name}</b>
			<input type="hidden" name=data[action_name] value="{$aData.action_name}" class="form-control btn-sm">
		</div>
	</div>
</div>
			
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Description')}:</label>
   			<input type=text name=data[action_description] value='{$aData.action_description}' style="width:100%;" class="form-control btn-sm">
		</div>
	</div>
</div>
   			
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->GetDMessage('Group')}:</label>
			{html_options name=data[id_role_group] options=$aGroupList selected=$aData.id_role_group class="form-control btn-sm"}
		</div>
	</div>
</div>

<!-- body end -->
			</div>
<!-- /.card-body -->
		    <div class="card-footer">
		        <input type="hidden" name="data[id]" value="{$aData.id}">
<input type="hidden" name="mod" value="save">
<input type="hidden" name="action" value="role_permissions">
				{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
		   </div>
		   <!-- /.card-footer -->
		   </form>
		   
		</div>
	</div>
</div>