<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Change password')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Password')}:{$sZir}</label>
   			<input type=password name=data[password] value='{$aData.password}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Retype Password')}:{$sZir}</label>
   			<input type=password name=data[retype_password] value='{$aData.retype_password}' class="form-control btn-sm">
		</div>
	</div>
</div>

<!-- body end -->
			</div>
<!-- /.card-body -->
		    <div class="card-footer">
		        <input type=hidden name=data[id] value="{$aData.id|escape}">
				{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
				<input type=hidden name=action value=user_change_password_apply>
		   </div>
		   <!-- /.card-footer -->
		   </form>
		   
		</div>
	</div>
</div>