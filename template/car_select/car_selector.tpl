<div class="at-car-picker {if $smarty.request.action=='' || $smarty.request.action=='home'}home-margin{/if}">
   <div class="at-mainer">
       <h3>{$oLanguage->getMessage("Подбор по модели автомобиля")}</h3>
       <div class="light-text">
       {$oLanguage->getText("Выбрав автомобиль вы сможете получить список запчастей подходящих к вашему автомобилю.")}
       </div>
		<form>
       <div class="selector">
           <div class="part" style="width: 153px;">
               <div class="at-custom-select-wrap js-select-year">
                   <div class="js-select-custom-drop">
                       <select class="js-select">
                           <option value="0">{$oLanguage->getMessage('Choose year','','Выберите год')}</option>
                       </select>
                   </div>

                   <div class="select-drop">
                       <div class="select-drop-inner">
                           <table>
                          {foreach from=$aCarSelectYear item=aYearGroup key=sYearGroupName}
                           <tr>
	                       <td class="year">{$sYearGroupName}-е</td>
							{foreach from=$aYearGroup item=sYear}
								<td><a rel="nofollow" href="/?action=car_select{include file='car_select/xajax_link.tpl'}&year={$sYear}" onclick="send_param(this.href, 'year','{$sYear}'); return false;">{$sYear}</a></td>
	                        {/foreach}
	                       </tr>
	                      {/foreach}
	                       </table>
                       </div>
                   </div>
               </div>
           </div>
           <div class="part" id="car_selected_brand_selector">
               <select class="js-select" disabled>
                   <option value="">{if $smarty.request.cat}{$smarty.request.cat|upper}{else}{$oLanguage->getMessage('select brand','','Выберите марку')}{/if}</option>
               </select>
           </div>
           <div class="part" id="car_selected_model_selector">
               <select class="js-select" disabled>
                   <option value="">{if $model_preselected}{$model_preselected|upper}{else}Выберите модель{/if}</option>
               </select>
           </div>
           <div class="part" id="car_selected_body_selector">
               <select class="js-select" disabled>
                   <option value="">Тип кузова</option>
               </select>
           </div>
           <div class="part" id="car_selected_volume_selector">
               <select class="js-select" disabled>
                   <option value="">Объем</option>
               </select>
           </div>
           <div class="part" id="car_selected_modif_selector">
               <select class="js-select" disabled>
                   <option value="">Двигатель</option>
               </select>
           </div>
       </div>
       </form>
   </div>
</div>