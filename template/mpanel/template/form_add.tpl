<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Template')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this,Array())">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Type')}:</label>
				<select name=data[type_] class="form-control btn-sm">
 					<option value=letter{if $aData.type_=='letter'} selected{/if}>{$oLanguage->getDMessage('Letter')}</option>
			 		<option value=bill{if $aData.type_=='bill'} selected{/if}>{$oLanguage->getDMessage('Bill')}</option>
			 		<option value=content{if $aData.type_=='content'} selected{/if}>{$oLanguage->getDMessage('Content')}</option>
				</select>
		</div>
	</div>
</div>
			
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Code')}:</label>
				<input type=text name=data[code] value="{$aData.code|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
			
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Priority')}:</label>
				<input type=text name=data[priority] value="{$aData.priority|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
			
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
			<label>{$oLanguage->getDMessage('Content')}:</label>
				{$oAdmin->getCKEditor('data[content]',$aData.content,700,600)}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Is smarty')}:</label>
			   <input type="hidden" name=data[is_smarty] value="0">
			   <input type=checkbox name=data[is_smarty] value='1' style="width:22px;" {if $aData.is_smarty}checked{/if}>
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