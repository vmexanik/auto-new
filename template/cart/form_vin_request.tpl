<input type="hidden" value="{$oLanguage->getMessage("VIN must contain 17 symbols")}" id="jsGTvin">
<input type="hidden" value="{$oLanguage->getMessage("Model and serie empty")}" id="jsGTmodel">
<input type="hidden" value="{$oLanguage->getMessage("Fill the spareparts list needed")}" id="jsGTazpDescript1">
<input type="hidden" value="{$oLanguage->getMessage("Mobile phone format is incorrect")}" id="jsGTmobile">
<input type="hidden" value="{$oLanguage->getMessage("Email is empty")}" id="jsGTemail">

<script type="text/javascript" src="/js/vin_request.js?845"></script>
<table border=0 width=100%>
{if !$aAuthUser.id}
<tr>
   	<td width=40%><b>{$oLanguage->getMessage("Mobile")}:</b>{$sZir}</td>
	<td>
	<!--select name="operator" style="width: 70px;">
		{html_options values=$aVinOperator output=$aVinOperator selected=$aData.operator}
	</select-->
	<input type=text id=mobile name=mobile value='{$aData.mobile}' maxlength=12 style="width: 120px;">
	{$oLanguage->getMessage("Example")}:<font color=red><b>+7095</b></font>1234567
	</td>
</tr>
<tr>
	<td><b>{$oLanguage->GetMessage("Email")}:</b></td>
	<td><input type=text name=email value='{$aData.email}' ></td>
</tr>
{/if}

  	<tr>
		<td><b>{$oLanguage->getMessage("VIN")}:</b>{$sZir}</td>
		<td><input type=text id=vin name=vin value='{$aData.vin}' maxlength=17></td>
	</tr>
	<tr>
   		<td width=40%><b>{$oLanguage->getMessage("Marka")}:</b></td>
   		<td width=60%>
		<select name="marka" id='marka' style="width: 120px;" onChange="mvr.ChangeForm()">
		{html_options values=$aVinMarka output=$aVinMarka selected=$aData.marka}
		</select>
		{if $aData.marka=='Renault' || $aData.marka=='Opel'}
			{assign var="style" value=""}
		{else}
			{assign var="style" value="display: none"}
		{/if}
   		</td>
  	</tr>
  	<tr>
		<td><b>{$oLanguage->getMessage("Model/Serie")}:</b>{$sZir}</td>
		<td><input type=text name=model value='{$aData.model}' ></td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Wheel")}:</b></td>
		<td>
		<select name="wheel" id='wheel' style="width: 120px;">
		{html_options values=$aVinWheel output=$aVinWheel selected=$aData.wheel}
		</select>
		</td>
	</tr>
  	<tr>
		<td><b>{$oLanguage->getMessage("Engine")}:</b></td>
		<td><input type=text name=engine value='{$aData.engine}' ></td>
	</tr>
	<!-- ������ �������������  -->
	<tr>
		<td><b>{$oLanguage->getMessage("Country producer")}:</b></td>
		<td><input type=text name=country_producer value='{$aData.country_producer}' >
   		</td>
  	</tr>

  <!--	<tr>
		<td><b>{$oLanguage->getMessage("Month/Year")}:</b>{$sZir}</td>
		<td><input type=text name=month value='{$aData.month}' maxlength=2 size=5> /
			<input type=text name=year value='{$aData.year}' maxlength=4 size=10 style="width: 61px;"></td>
	</tr>-->
	<tr>
		<td><b>{$oLanguage->getMessage("Month/Year")}:{$sZir}</b></td>
		<td>
			{html_options name=Month options=$aVinMonth selected=$aData.Month} /

			{html_select_date prefix="" year_extra="style='width:67px'"
				display_days=false time=$aData.date start_year="1959" field_order=Y reverse_years=true}
		</td>
	</tr>
  	<tr>
		<td><b>{$oLanguage->getMessage("Volume")}:</b></td>
		<td><input type=text name=volume value='{$aData.volume}' ></td>
	</tr>
  	<tr>
		<td><b>{$oLanguage->getMessage("Body")}:</b></td>
		<td>
<select name="body" style="width: 120px;">
{html_options values=$aVinBody output=$aVinBody selected=$aData.body}
</select>
   		</td>
  	</tr>
  	<!-- ���  -->
  	<tr>
		<td><b>{$oLanguage->getMessage("KPP")}:</b></td>
		<td>
			<select name="kpp" style="width: 120px;">
			{html_options  values=$aVinKpp output=$aVinKpp selected=$aData.kpp}
			</select>
   		</td>
  	</tr>
  	<tr>
		<td><b>{$oLanguage->getMessage("Additional")}</b></td>
		<td>
			<input type=checkbox name='additional[]' value='ABS'
				{if $smarty.request.additional && in_array('ABS',$smarty.request.additional)} checked {/if}
				>&nbsp;{$oLanguage->getMessage("ABS")}
			<input type=checkbox name='additional[]' value='Hydromultiplier'
				{if $smarty.request.additional && in_array('Hydromultiplier',$smarty.request.additional)} checked {/if}
				>&nbsp;{$oLanguage->getMessage("Hydromultiplier")}
			<input type=checkbox name='additional[]' value='Conditioner'
				{if $smarty.request.additional && in_array('Conditioner',$smarty.request.additional)} checked {/if}
				>&nbsp;{$oLanguage->getMessage("Conditioner")}
		</td>
	</tr>
	<tr>
		<td valign=top><b>{$oLanguage->getMessage("Customer Comment")}:</b>
		{$oLanguage->getContextHint("vin_request customer_comment")}
		</td>
		<td><textarea name=customer_comment style='width:270px'>{$aData.customer_comment}</textarea></td>
	</tr>
	{*
	<tr>
   		<td><b>{$oLanguage->getMessage("Passport image (jpg, gif)")}:</b></td>
   		<td><input type=file name=passport_image[1] style='width:270px'></td>
  	</tr>
	*}
	<tr>
		<td colspan=2 align=center><hr><b>{$oLanguage->getText("describe spare parts")}</b>
		{$oLanguage->getContextHint("vin_request_add")}<br>
		<hr>
		</td>
	</tr>

	<tr>
		<td colspan=2 align=center>

<table id="queryByVIN" border=0 align=center>
    <tbody>
      <tr align="right">
        <td>1</td>
        <td><input type="text" name="azpDescript1" maxlength="100" style="width:330px;" value=""></td>

        <td><input type="text" name="azpCnt1" maxlength="2" style="width:25px;" value="1"></td>
      </tr>
    </tbody>
</table>

	<br>
      <input type="button" class='at-btn' value="{$oLanguage->getMessage("Add line")}"
				onclick="javascript:mvr.AddRow(this.form);" />&nbsp;&nbsp;
      <input type="button" class='at-btn' value="{$oLanguage->getMessage("Delete line")}"
				onclick="javascript:mvr.DeleteRow(this.form);" /><br />&nbsp;

		</td>
	</tr>

</table>

<input type="hidden" name="RowCount" value="1">
<input type="hidden" id="isUserAuth" name="isUserAuth" value="{if $smarty.session.user.id}1{else}0{/if}">
