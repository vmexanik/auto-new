<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Item')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">
<div class="card-body">
<!-- body begin -->

{include file='mpanel/drop_down/form_add_part.tpl'}

<!-- body end -->
			</div>
<!-- /.card-body -->
		    <div class="card-footer">
		        <input type=hidden name=data[level] value="{$aData.level|escape}">
				<input type=hidden name=data[id] value="{$aData.id|escape}">
				<input type=hidden name=data[p_num1] value='{$aData.p_num1|escape}'>
				<input type=hidden name=data[site] value='{$aData.site|escape}'>
				<input type=hidden name=data[id_parent] value='{$idParent|escape}'>
				{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
		   </div>
		   <!-- /.card-footer -->
		   </form>
		   
		</div>
	</div>
</div>