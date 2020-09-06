<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Manager')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Login')}:{$sZir}</label>
   			<input type=text name=data[login] value="{$aData.login|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>

{if !$aData.id}
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Password')}:{$sZir}</label>
   			<input type=password name=data[password] value="{$aData.password|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
{/if}

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Name')}:</label>
   			<input type=text name=data[name] value="{$aData.name|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
		{include file='addon/mpanel/form_image.tpl' aData=$aData}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Address')}:</label>
   			<input type=text name=data[address] value="{$aData.address|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Email')}:</label>
   			<input type=text name=data[email] value="{$aData.email|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Phone')}:</label>
   			<input type=text name=data[phone] value="{$aData.phone|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Phone 2')}:</label>
   			<input type=text name=data[phone2] value="{$aData.phone2|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Mobile Phone')}:</label>
   			<input type=text name=data[phone3] value="{$aData.phone3|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Skype')}:</label>
   			<input type=text name=data[skype] value="{$aData.skype|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Icq')}:</label>
   			<input type=text name=data[icq] value="{$aData.icq|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Remarks')}:</label>
   			<textarea name=data[remark] class="form-control btn-sm">{$aData.remark}</textarea>
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
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Approved')}:</label>
			<input type="hidden" name=data[approved] value="0">
			<input type=checkbox name=data[approved] value='1' style="width:22px;" {if $aData.approved}checked{/if}>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Has Customers')}:</label>
			<input type="hidden" name=data[has_customer] value="0">
			<input type=checkbox name=data[has_customer] value='1' style="width:22px;"
			{if $aData.has_customer || !$aData.id}checked{/if}>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Is Super Manager')}:</label>
			<input type="hidden" name=data[is_super_manager] value="0">
			<input type=checkbox name=data[is_super_manager] value='1' style="width:22px;" {if $aData.is_super_manager}checked{/if}>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>Роли</label>
			<hr>
{foreach from=$aRoles item=aRole}
	{$aRole.name}
		<input type=checkbox name=data[id_role][] value='{$aRole.id}' style="width:22px;" {if $aRole.id_manager}checked{/if}><br>
{/foreach}
		</div>
	</div>
</div>

<!-- body end -->
			</div>
<!-- /.card-body -->
		    <div class="card-footer">
		        <input type=hidden name=data[id] value="{$aData.id|escape}">
				<input type=hidden name=data[type_] value="manager">
				{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
		   </div>
		   <!-- /.card-footer -->
		   </form>
		   
		</div>
	</div>
</div>