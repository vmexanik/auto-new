<h1>Настройка гуппы для поставщика: {$aProviderInfo.login}{if $aProviderInfo.name} - {$aProviderInfo.name}{/if}</h1>
<input type='hidden' id='id_provider' value='{$idProvider}'>
<input type='hidden' id='id_group' value='{$iIdGroup}'>
<div style=" padding: 5px; width: 800px;"><b>Префикс группы:</b>
	{if $iIdGroup}
		<input type=text id='pref_group' name=data[pref_group] value='{$sPref|escape}' maxlength=3 style='width:100px'>
		<img style="cursor:pointer;vertical-align: middle;" src="/image/apply.png" id="img_save_comment_{$aRow.id}" alt="Сохранить" title="Сохранить"
			onclick="javascript:xajax_process_browse_url('?action=manager_group_provider_set_pref&amp;id={$iIdGroup}&amp;cid='+encodeURIComponent($('#pref_group').val()));return false;">
		<span style="float:right">
			<a href="javascript:;" onclick="javascript:xajax_process_browse_url('?action=manager_group_provider_del_group&amp;id={$iIdGroup}');return false;"><img style="cursor:pointer;vertical-align: middle;" src="/image/delete.png" id="img_save_comment_{$aRow.id}" alt="Расформировать" title="Расформировать">
				Расформировать
			</a>
		</span>
	{else}
		<span style="color:grey;">еще не создана</span>
	{/if}
</div> 
<table style=" border: 1px solid black; ">
  	<tr>
   		<td width="auto" style="min-width: 400px;border-right: 1px solid black;padding: 0px 5px 0 5px;">
   			<div style=" text-align: center; padding: 10px; border: 1px solid black; width: 200px; margin: auto;">
   				<b>{$oLanguage->getMessage("reestr provider")}:</b>
   			</div>
		    <div style="min-height: 400px;max-height: 400px;overflow-y: scroll;border: 1px solid black;margin: 5px;">
		    {if $aProvider}
				<table>
				    {foreach item=aItem from=$aProvider}
				    <tr>
				    	<td><input id=data_id_provider_{$aItem.id} type=checkbox name=data_id_provider[] value={$aItem.id}></td>
				    	<td>{$aItem.login}{if $aItem.name} - {$aItem.name}{/if}</td>
				    </tr>
				    {/foreach}
				</table>
			{/if}
		    </div>
		    <div style=" text-align: center; ">
		    	<input type="button" class="at-btn" value="Добавить в группу >>" 
		    		onclick="ManagerGroupProviderSet();">
		    </div>
		</td>
		<td style=" vertical-align: top; min-width: 400px;padding: 0px 5px 0 5px;">
			<div style=" text-align: center; padding: 10px; border: 1px solid black; width: 200px; margin: auto;">
				<b>{$oLanguage->getMessage("group_provider")}:</b>
			</div>
			 <div style="min-height: 400px;max-height: 400px;overflow-y: scroll;border: 1px solid black;margin: 5px;">
			 {if $aProviderGroup}
				<table>
					<tr>
					<th>Исключить</th>
					<th>Название</th>
					<th>Главный</th>
					</tr>
				    {foreach item=aItem key=iKey from=$aProviderGroup}
				    <tr>
   				    	<td>{if $aItem.id_user==$idProvider}
   				    			
   				    		{else}
   				    			{if ($iIdGroup)}
   				    				<input type=checkbox id=data_id_groupprovider_{$aItem.id_user} name=data_id_groupprovider[] value={$aItem.id_user}></td>
   				    			{/if}
   				    		{/if}
				    	<td>{$aItem.login}{if $aItem.name} - {$aItem.name}{/if}</td>
				    	<td>{if ($iIdGroup)}
				    		<input type=radio id=data_id_groupprovidermain_{$iKey} name=is_main value={$iKey} {if $aItem.is_main}checked{/if}
				    			onchange="ManagerGroupProviderSetMain('{$aItem.id_user}');return false;">
				    		{/if}
				    	</td>
				    </tr>
				    {/foreach}
				</table>
			{/if}
		    </div>
		   	<div style=" text-align: center; ">
		    	<input type="button" class="at-btn" value="<< Исключить из группы" 
		    		onclick="ManagerGroupProviderUnSet();">
		    </div>
		</td>
  	</tr>
</table>