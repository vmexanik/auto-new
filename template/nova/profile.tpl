
{assign var="name" value=" "|explode:$aUser.name}
{*$aManager|@debug_print_var*}
<input type=text name=data[id_user] value="{$aManager.id}" maxlength=50 style='display:none'>
<table style='width:100%; white-space: nowrap;' >
   <tr>
      <td >
         <table style='width:100%;'>
            <tr>
               <td width=20%><b>{$oLanguage->getMessage("Name1")} <span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'><input class='name' type=text name=data[name] value="{$name[0]}" maxlength=50 ><br></div></td>
            </tr>
            <tr>
               <td width=20%><b>{$oLanguage->getMessage("SurName")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'><input class='surname' type=text name=data[surname] value='{if $name[2]}{$name[2]}{else}-{/if}' maxlength=50 ></div></td>
            </tr>
            <tr>
               <td width=20%><b>{$oLanguage->getMessage("LastName")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td align=left><div style='position:relative'><input class='lastname' type=text name=data[lastname] value='{$name[1]}' maxlength=50 ></div></td>
            </tr>
            <tr>
               <td><b>{$oLanguage->getMessage("phone")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'><input class='phone'  type=text name=data[phone] value='{$aUser.login|regex_replace:"/[\(\)\-]/":""}' maxlength=50 ></div></td>
            </tr>
            <tr>
               <td><b>{$oLanguage->getMessage("Тип доставки")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'>
               <label><input checked type="radio" name="data[ServiceType]" value="WarehouseWarehouse" onclick="$('#WarehouseDoorsdiv').hide();$('#WarehouseWarehousediv').show();">Отделение-Отделение</label><Br>
   <label><input  type="radio" name="data[ServiceType]" value="WarehouseDoors" onclick="$('#WarehouseDoorsdiv').show();$('#WarehouseWarehousediv').hide();">Отделение-Адрес</label><Br>
   
   </div></td>
            </tr>
                        <tr>
               <td><b>{$oLanguage->getMessage("State")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'>
            <select class='state'  name='data[state]' id="state" style='width:270px' onchange="javascript:
xajax_process_browse_url('?action=novaposhta&amp;area='+this.options[this.selectedIndex].value);
return false;">
			{html_options options=$aAreas selected=$sAreaSelected}
			</select></div>
               </td>
            </tr>
            <tr>
               <td><b>{$oLanguage->getMessage("City")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'>
               { include file=nova/select_city.tpl}
               </div>
               </td>
            </tr>
            <tr>
               <td><b>{$oLanguage->getMessage("Address np")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'><div id='WarehouseWarehousediv'>
               { include file=nova/select_address.tpl}
               </div>
               <div id='WarehouseDoorsdiv' style='display:none'>
               {include file=nova/select_address2.tpl}
               <br>
<label>Дом:<input type='text' style="width: 82%!important;" name="data[house]"></label><br>
<label>кв.:<input type='text' style="width: 87%!important;" name="data[apartment]"></label><br>
               </div>
               </div>
               </td>
            </tr>
            <tr>
                <td colspan="2"><hr></td>
            </tr>
            <tr>
               <td><b>{$oLanguage->getMessage("Вес груза")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'><input class='' value="0.2" type=text name=data[weight]  maxlength=50 ></div></td>
            </tr>
            <tr>
                <td><b>{$oLanguage->getMessage("ширина")}:</b></td>
                <td><input type="text" id="bulk_width" name='bulk_width' value='{if $smarty.request.bulk_width}{$smarty.request.bulk_width}{else}1{/if}' onkeyup="set_bulk_weight()"></td>
            </tr>
            <tr>
                <td><b>{$oLanguage->getMessage("глубина")}:</b></td>
                <td><input type="text" id="bulk_depth" name='bulk_depth' value='{if $smarty.request.bulk_depth}{$smarty.request.bulk_depth}{else}1{/if}' onkeyup="set_bulk_weight()"></td>
            </tr>
            <tr>
                <td><b>{$oLanguage->getMessage("высота")}:</b></td>
                <td><input type="text" id="bulk_height" name='bulk_height' value='{if $smarty.request.bulk_height}{$smarty.request.bulk_height}{else}1{/if}' onkeyup="set_bulk_weight()"></td>
            </tr>
            <tr style="display: none">
               <td><b>{$oLanguage->getMessage("Объемный вес")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div><input value="0.0004"  type=text name=data[volume_weight]  maxlength='50' id="bulk_weight"></div></td>
            </tr>
            <tr style="display: none">
               <td><b>{$oLanguage->getMessage("номер заказа на сайте")}:</b></td>
               <td><div><input value="{if $smarty.request.data.order_number}{$smarty.request.data.order_number}{else}{$iIdCartPackage}{/if}"  type=text name=data[order_number]  readonly></div></td>
            </tr>
         </table>
      </td>
      <td>
         <table style='width:100%;    white-space: nowrap;'>
            <tr>
               <td width=20%><b>{$oLanguage->getMessage("Тип оплаты")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'><select class='paymentmethod'  name='data[paymentmethod]' style='width:164px'>
               <option value='Cash' {if $smarty.request.paymentmethod=='Cash'}selected{/if}
				>{$oLanguage->getMessage("Наличный расчет")}</option>
			<option value='NonCash' {if $smarty.request.paymentmethod=='NonCash'}selected{/if}
				>{$oLanguage->getMessage("Безналичный расчет")}</option>
			</select></div></td>
            </tr>
            <tr>
               <td width=20%><b>{$oLanguage->getMessage("Кто оплачивает за доставку")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td align=left><div style='position:relative'>
               <select class='payertype'  name='data[payertype]' style='width:164px'>
            <option value='Recipient' {if $smarty.request.paymentmethod=='Recipient'}selected{/if}
			>{$oLanguage->getMessage("Получатель")}</option>
			<option value='Sender' {if $smarty.request.paymentmethod=='Sender'}selected{/if}
				>{$oLanguage->getMessage("Отправитель")}</option>
			</select>
			</div>
				</td>
            </tr>
            <tr>
               <td><b>{$oLanguage->getMessage("Стоимость груза в грн")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'><input class='price' value='{$aCartPackage.price_total|@ceil}'  type=text name=data[price] maxlength=50 style="width:164px"></div></td>
            </tr>
            <tr>
               <td><b>{$oLanguage->getMessage("Кол-во мест")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'><input class='number' value='1' type=text name=data[number]  maxlength=50 style="width:164px"></div></td>
            </tr>
            <tr>
               <td><b>{$oLanguage->getMessage("Тип доставки, дополнительно")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'><select class='servicetype2' name='data[servicetype2]' style='width:164px'>
			<option value='Cargo' {if $smarty.request.servicetype=='Cargo'}selected{/if}
				>{$oLanguage->getMessage("Груз")}</option>
			<option value='Documents' {if $smarty.request.servicetype=='Documents'}selected{/if}
				>{$oLanguage->getMessage("Документы")}</option>
			<option value='TiresWheels' {if $smarty.request.servicetype=='TiresWheels'}selected{/if}
				>{$oLanguage->getMessage("Шины-диски")}</option>
			<option value='Pallet' {if $smarty.request.servicetype=='Pallet'}selected{/if}
				>{$oLanguage->getMessage("Паллеты")}</option>
			</select>
			</div></td>
            </tr>
            <tr>
               <td><b>{$oLanguage->getMessage("Описание груза")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'><textarea class='description'  name=data[description] style="width:164px">Автозапчасти</textarea></div></td>
            </tr>
            <tr style="display: none;">
               <td><b>{$oLanguage->getMessage("Объем груза в куб.м")}<span style="color:red;"><b>*</b></span>:</b></td>
               <td><div style='position:relative'><input class='bulk' value="0.001"  type=text name=data[bulk]  maxlength='50' id='bulk'></div></td>
            </tr>
            
           <tr >
               <td><b>{$oLanguage->getMessage("Наложенный платеж")}:</b></td>
               <td><div style='position:relative'><input id='nal' class='bulk' type=checkbox name=data[nal] {if $aCartPackage.price_np>0}checked{/if}></div></td>
            </tr>
            <tr >
               <td><b>{$oLanguage->getMessage("Сумма наложенного платежа")}:</b></td>
               <td><div style='position:relative'><input id='nal_number' class='bulk' value="{$aCartPackage.price_np}" {if $aCartPackage.price_np==0}disabled{/if}  type=text name=data[nal_number]  maxlength=50 style='width:164px'></div></td>
            </tr>
            <tr style="display: none">
               <td><b>{$oLanguage->getMessage("номер пакування")}:</b></td>
               <td><div style='position:relative'><input   value="0" type=text name=data[PackingNumber]  maxlength=50 style='width:164px'></div></td>
            </tr>
         </table>
      </td>
   </tr>
   <tr>
      <td colspan="2"><input type="submit" class="btn" value="{$oLanguage->getMessage('Generate EN')}"></td>
   </tr>
   <tr>
      <td colspan=2>
      
      {*$aList|@debug_print_var*}
      <h2>{$oLanguage->getMessage("Позиции заказа")}</h2>
         <table style='width:100%' class='datatable'>
        	<tr>
               <th>
				
               </th>
               <th>
				{$oLanguage->getMessage("Ид")}
               </th>
               <th>
				{$oLanguage->getMessage("Производитель")}
               </th>
               <th>
				{$oLanguage->getMessage("code")}
               </th>
               <th>
				{$oLanguage->getMessage("Описание")}
               </th>
               <th>
				{$oLanguage->getMessage("Поставщик")}
               </th>
               <th>
				{$oLanguage->getMessage("Цена")}
               </th>
               <th>
				{$oLanguage->getMessage("кол-во")}
               </th>
            </tr>
         {foreach from=$aList item=value}
            <tr>
               <td>
				<input type="checkbox" name="position[{$value.id}]">
               </td>
               <td>
				{$value.id}
               </td>
               <td>
				{$value.cat_name}
               </td>
               <td>
				{$value.code}
               </td>
               <td style="white-space: pre-line;">
				{$value.name_translate}
               </td>
               <td>
				{$value.provider_name}
               </td>
                               <td>
				{$value.price}
               </td>
                <td>
				{$value.number} шт
               </td>
            </tr>
            {/foreach}
         </table>
      </td>
   </tr>
</table>
<input type=hidden name=id value={$iIdCartPackage} />