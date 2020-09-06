<div style="display: none;"
{if !$aCarSelectUrl}
onclick="return false;" 
class="input_select hide form_button_show jpn-button at-btn" 
{else}
onclick="xajax_process_browse_url('/?action=catalog_save_selected_auto&{$sSaveLink}'); document.location='{$aCarSelectUrl|lower}'" 
class="input_select hide form_button_show jpn-button red at-btn" 
{/if}
id="selected_car_link">
Подобрать запчасти
</div>