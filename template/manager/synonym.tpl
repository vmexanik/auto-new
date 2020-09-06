<table class="datatable" cellpadding="5">
	<tr>
		<th width="50%">Бренды</th>
		<th>Синонимы</th>
	</tr>
	<tr>
		<td>
<input type="text" onkeyup="javascript: xajax_process_browse_url('?action=manager_synonym&search='+$(this).val());return false;">
<div style="height: 300px;width: 250px;overflow: auto" id="div_brand">
	<table id="id_synonym_brand" style="width: 230px;">
	{foreach from=$aBrand item=brand key=kbrand}
		<tr>
			<td id="brand_{$kbrand}"
				onclick="javascript: xajax_process_browse_url('?action=manager_synonym&brand={$brand|escape:'url'}');return false;"
				>{$brand}</td>
			<td>
				<a onclick="xajax_process_browse_url('?action=manager_synonym&new_brand={$brand}&cat_id='+$('input[name=cat_id]').val());"
					class="synonym-plus synonym-plus_{$kbrand}" style="display:none;" href="#"
					><img src="/image/plus.png"></a>
			</td>
		</tr>
	{/foreach}
	</table>
</div>
		</td>
		<td valign="top">
{include file='manager/select_synonym.tpl'}
		</td>
	</tr>
</table>