<select name="data[id_model]" id="id_model" class="formstyler empty-select" 
onchange="xajax_process_browse_url('/?action=rubricator_set_model&data[id_model]='+$(this).val()+'&data[id_make]='+$('#id_mfa').val(){if $smarty.request.action=='rubricator_subcategory'}+'&show=1'{/if} ); return false;">
{html_options options=$aModel selected=$smarty.cookies.id_model}
</select>