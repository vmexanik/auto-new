<div id="provider_form_div">
{if $sTableMessage}<div class="{$sTableMessageClass}">{$sTableMessage}</div>{/if}

<FORM name="formProvider" id="formProvider" method="post">

	<table width=800 border=0 cellspacing=0 cellpadding=0>
		<tr>
		   <td valign=top>
				
			<table cellspacing=0 cellpadding=2 class=add_form>
				<tr>
				 <th>
				 {$oLanguage->getDMessage('Provider')}
				 </th>
				</tr>
				<tr><td>
				
					<table cellspacing=2 cellpadding=1>
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Login')}:{$sZir}</td>
						   <td><input type=text name=data[login] value="{$aData.login|escape}"></td>
						</tr>
						{if !$aData.id}
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Password')}:{$sZir}</td>
						   <td><input type=text name=data[password] value="{$aData.password|escape}"></td>
						</tr>
						{/if}
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Name')}:{$sZir}</td>
						   <td><input type=text name=data[name] value="{$aData.name|escape}"></td>
						</tr>
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Description')}:</td>
						   <td><textarea name=data[description]>{$aData.description}</textarea></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Code Name')}:</td>
						   <td><input type=text name=data[code_name] value="{$aData.code_name|escape}"></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Code Delivery')}:</td>
						   <td><input type=text name=data[code_delivery] value="{$aData.code_delivery|escape}"></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Provider Group')}:</td>
						    <td>
						   {html_options name=data[id_provider_group] options=$aProviderGroupList selected=$sProviderGroupSelected}
						  </td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Provider Region')}:</td>
						    <td>
						   {html_options name=data[id_provider_region] options=$aProviderRegionList selected=$sProviderRegionSelected}
						  </td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Term delivery')}:</td>
						   <td><input type=text name=data[term] value="{$aData.term|escape}"></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Price Currency')}:</td>
						    <td>
						   {html_options name=data[id_currency] options=$aCurrency selected=$aData.id_currency}
						  </td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Country')}:</td>
						   <td><input type=text name=data[country] value="{$aData.country|escape}"></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('State')}:</td>
						   <td><input type=text name=data[state] value="{$aData.state|escape}"></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('City')}:</td>
						   <td><input type=text name=data[city] value="{$aData.city|escape}"></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Zip')}:</td>
						   <td><input type=text name=data[zip] value="{$aData.zip|escape}"></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Company')}:</td>
						   <td><input type=text name=data[company] value="{$aData.company|escape}"></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Address')}:</td>
						   <td><input type=text name=data[address] value="{$aData.address|escape}"></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Email')}:</td>
						   <td><input type=text name=data[email] value="{$aData.email|escape}"></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Phone')}:</td>
						   <td><input type=text name=data[phone] value="{$aData.phone|escape}" id="user_phone" placeholder="(___)___ __ __"></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Phone 2')}:</td>
						   <td><input type=text name=data[phone2] value="{$aData.phone2|escape}"></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Mobile Phone')}:</td>
						   <td><input type=text name=data[phone3] value="{$aData.phone3|escape}"></td>
						</tr>
						
						<tr>
						   <td width=50%>{$oLanguage->getDMessage('Remarks')}:</td>
						   <td><textarea name=data[remark]>{$aData.remark}</textarea></td>
						</tr>
						
						{include file='addon/mpanel/form_visible.tpl' aData=$aData}
						
						<tr>
						   <td>{$oLanguage->getDMessage('Is Test')}:</td>
						   <td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_test' bChecked=$aData.is_test}</td>
						</tr>
						
						<tr>
						   <td>{$oLanguage->getDMessage('Is Auction')}:</td>
						   <td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_auction' bChecked=$aData.is_auction}</td>
						</tr>
						
						<tr>
						   <td>{$oLanguage->getDMessage('Is Our Store')}:</td>
						   <td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_our_store' bChecked=$aData.is_our_store}</td>
						</tr>
						
						<tr>
						   <td>{$oLanguage->getDMessage('Approved')}:</td>
						   <td>
							{include file='addon/mpanel/form_checkbox.tpl' sFieldName='approved' bChecked=$aData.approved}
						   </td>
						</tr>
						<!--tr>
						   <td>{$oLanguage->getDMessage('Statistic Visible')}:</td>
						   <td>
							{include file='addon/mpanel/form_checkbox.tpl' sFieldName='statistic_visible' bChecked=$aData.statistic_visible}
						   </td>
						</tr>
						<tr>
						   <td>{$oLanguage->getDMessage('Statistic Manual')}:</td>
						   <td>
							{include file='addon/mpanel/form_checkbox.tpl' sFieldName='statistic_manual' bChecked=$aData.statistic_manual
								sOnClick="oMpanel.ToggleElement('provider_make_statistic_id');"}
						   </td>
						</td>
						</tr-->
					</table>
				
				</td></tr>
			</table>
			
			<input type=hidden name=data[id] value="{$aData.id|escape}">
			<input type=hidden name=data[type_] value="provider">
			{if $isEdit}
			    {include file='price_profile/profile_provider_edit_button.tpl'}
			{else}
			    {include file='price_profile/profile_add_button.tpl'}
			{/if}
		
		</td>
		
		<!-- ------------------------------- Right section ------------------------------- -->
		<td valign=top>
		
			<table  id='provider_make_statistic_id' cellspacing=0 cellpadding=2 class=add_form
				style="width: 250px; {if !$aData.statistic_manual}display: none;{/if}">
				<tr>
				 <th>
				 {$oLanguage->getDMessage('Provider Make Statistic')}
				 </th>
				</tr>
				<tr><td>
			
					<table cellspacing=2 cellpadding=1>
						<tr>
						   <td width=20%><b>{$oLanguage->GetDMessage('Make')}:</b></td>
						   <td>{$oLanguage->GetDMessage('Delivery Term')}</td>
						   <td>{$oLanguage->GetDMessage('Refuse Percent')}</td>
						   <td>{$oLanguage->GetDMessage('Confirm Term')}</td>
						</tr>
						<tr>
						   <td width=20%><input type=button value="&darr;&nbsp;{$oLanguage->GetDMessage('Default')}&nbsp;&darr;" style="width:80px;"></td>
						   <td><input type=text value="" maxlength="5" style="width: 40px;"></td>
						   <td><input type=text value="" maxlength="5" style="width: 40px;"></td>
						   <td><input type=text value="" maxlength="5" style="width: 40px;"></td>
						</tr>
						<tr>
						   <td colspan=4><hr></td>
						</tr>
					
						{foreach from=$aCat item=aItem}
						<tr>
						   <td>{$aItem.title}:</td>
						   <td><input type=text name=provider_statistic[{$aItem.name}][manual_delivery_term] maxlength="5" style="width: 40px;"
								value="{$aItem.manual_delivery_term}" ></td>
						   <td><input type=text name=provider_statistic[{$aItem.name}][manual_refuse_percent] maxlength="5" style="width: 40px;"
								value="{$aItem.manual_refuse_percent}" ></td>
						   <td><input type=text name=provider_statistic[{$aItem.name}][manual_confirm_term] maxlength="5" style="width: 40px;"
								value="{$aItem.manual_confirm_term}" ></td>
						</tr>
						{/foreach}
					
					</table>
				
				</td></tr>
			</table>		
		
		</td></tr>
	</table>

</FORM>
</div>