<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Translation')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this,Array())">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Code')}: {$sZir}</label>
			<textarea name=data[code] class="form-control btn-sm">{$aData.code}</textarea>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Translation')}:</label>
			{$oAdmin->getCKEditor('data[content]',$aData.content)}
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Use code html')}:</label>
			<input type="hidden" name=data[use_code_html] value="0">
   			<input type=checkbox name=data[use_code_html] value='1' style="width:22px;">
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Translation html')}:</label>
			<textarea style="width: 700px;height: 300px;" name=data[content_html] class="form-control btn-sm">{$aData.content}</textarea>
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