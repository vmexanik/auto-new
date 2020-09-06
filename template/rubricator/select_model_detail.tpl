<select name="data[id_model_detail]" id="id_model_detail" class="formstyler empty-select" 
onchange="xajax_process_browse_url('/?action=rubricator_set_model_detail&data[id_model_detail]='+$(this).val()+'&data[id_make]='+$('#id_mfa').val()+'&data[id_model]='+$('#id_model').val(){if $smarty.request.action=='rubricator_subcategory'}+'&show=1'{/if} );return false;">
{html_options options=$aModelDetail selected=$smarty.cookies.id_model_detail}
</select>