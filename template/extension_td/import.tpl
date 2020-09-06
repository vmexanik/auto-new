<table width="100%" class="datatable">
<tr>
   <th><a href='/pages/extension_td_tree_rubric?data[id_model_detail]={$smarty.request.data.id_model_detail}&data[id_rubric]={$smarty.request.data.id_rubric}'><< Назад</a></th>
</tr>
</table>

<table width="99%">
   	<tr>	
   		<td><b>{$oLanguage->getMessage("File to import")}:</b></td>
   		<td><input type=file name=import_file></td>
  	</tr>
</table>

<input type="hidden" name="data[id_model_detail]" value="{$smarty.request.data.id_model_detail}">
<input type="hidden" name="data[id_rubric]" value="{$smarty.request.data.id_rubric}">