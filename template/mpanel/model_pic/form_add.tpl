{assign var="sBaseUpload" value="upload_img"}
{include file='addon/mpanel/form_upload.tpl' sBaseUpload=$sBaseUpload sFormAction="/single/mpanel_file_upload.php?BaseUpload=$sBaseUpload"}
<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this,Array())">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('Model picture')}</th>
	</tr>
	<tr>
		<td>

		<table cellspacing=2 cellpadding=1>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Make')}: {$sZir}</td>
				<td><select name="data[id_tof]" id="id_tof" class="searcher_select"
	        onchange="javascript:
				xajax_process_browse_url('?action=model_pic_make&data[id_tof]='+this.options[this.selectedIndex].value);
	 			return false;">
			{html_options options=$aMakeTof selected=$aData.id_tof style='width:220px'}
	        </select></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Model')}: {$sZir}</td>
				<td>{include file='mpanel/model_pic/selector_model.tpl'}</td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('description')}:</td>
				<td><textarea type=text name=data[description] rows=5 >{$aData.description|escape}</textarea></td>
			</tr>
			{if $aData.image}
			<tr>
			<td width=100%>{$oLanguage->getDMessage('Image')}:</td>
			<td>
				<img src="/imgbank/Image/model/{$aData.image}" />
			</td>
			</tr>
			{/if}
			{include file='addon/mpanel/base_upload_button.tpl' sFieldName='Image to upload' sBaseUpload=$sBaseUpload}

		</table>

		</td>
	</tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">
{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}</FORM>