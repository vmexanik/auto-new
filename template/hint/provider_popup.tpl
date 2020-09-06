<span class="hov">
&nbsp;<a href="#" style="text-decoration:underline;"
	>&laquo;&nbsp;{$oLanguage->getMessage("Additional")}&nbsp;<img src="/image/design/icon_ask.gif" border=0 align=absmiddle />
<!--[if IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
<table style="width:440px;" class="stats-hint">
<tr>
	<th colspan="2">{$oLanguage->getMessage("Provider popup header")}</th>
</tr>
<tr>
	<td style="width:50%;">
		<div style="width: 196px;">
			<span class="span_h1">{$oLanguage->getMessage("Provider Info")}</span>
			<p>
			{if $aData.provider_description}
				{$aData.provider_description}
			{else}
				{$oLanguage->getMessage("Not assigned yet")}
			{/if}
			</p>
		</div>
		<div>
			<span class="span_h1">{$oLanguage->GetMessage("Provider Region Info")}</span>
			<p><strong>
				{*if $smarty.get.price_type!='retail' || $aData.is_always_additional || $aData.weight==0}
					{assign var=bShowAdditional value=true}
				{else}
					{assign var=bShowAdditional value=false}
				{/if*}
				{assign var=bShowAdditional value=true}
				{$oLanguage->GetMessage("RegionName")}:
				<label>{$aData.region_code}
				{if $bShowAdditional}
					{$aRow.additional_delivery}
				{/if}
				</label>
			{if $aData.code_delivery_description && $bShowAdditional}
				{$oLanguage->GetMessage("RegionDescription")}:
				<label>{$aData.code_delivery_description}</label>
			<a style="text-decoration:underline;"
				href='/?action=additional_price_info'
				target=_blank>{$oLanguage->getMessage('RegionDetails')}</a>
			{/if}
			</strong></p>
		</div>
		<div>
			<span class="span_h1">{$oLanguage->getMessage("Manufacturer Info")}</span>
			<p><strong>{$oLanguage->GetMessage("Model")}: <label>{$aRow.make}</label></strong></p>
		</div>
	</td>
	<td width="50%">
	{if $aData.cart_history}
<div>
	<span class="span_h1" align=center>{$oLanguage->GetMessage("Cart History for part")}</span>
{foreach from=$aData.cart_history item=aValue}
	{assign var="last_order_status" value=$aValue.order_status|cat:"_last"}
	{if $aValue.order_status=='refused'} {assign var="sColor" value='red'}
	{elseif $aValue.order_status} {assign var="sColor" value='green'}
	{else} {assign var="sColor" value=''}
	{/if}
	<font color="{$sColor}" style="font-size:11px;">
	{$oLanguage->getDate($aValue.post)} - {$oLanguage->getMessage($last_order_status)} {if $aValue.comment}: {$aValue.comment}{/if}</font>
	<br>
{/foreach}
</div>
{/if}
		<div style="width: 210px;" align=center>
			<span class="span_h1">{$oLanguage->GetMessage("Provider Refuse Info")}: {$aRefusedGraph.first}</span>
{if $aRefusedGraph.first}
			<img src="http://chart.apis.google.com/chart?{strip}
cht=p3
&chd=t:{$aRefusedGraph.first},{$aRefusedGraph.second}
&chs=210x70
&chl={$sRefusedText}|{$sDeliveredText}{/strip}">
{else}
{$oLanguage->GetMessage('Not assigned yet')}
{/if}
		</div>
{if $dConfirmTerm}
		<div>
			<span class="span_h1">{$oLanguage->GetMessage("Provider ConfirmTerm Info")}: {$dConfirmTermText} {$oLanguage->GetMessage('days')}</span>
<img src="http://chart.apis.google.com/chart?{strip}
cht=bhs
&chs=200x50
&chd=t:{$dConfirmTerm}|{$dConfirmTermSecond}
&chco=4d89f9,c6d9fd
&chbh=20
&chxt=x
&chxl=0:|0||30
{/strip}">
		</div>
{*cht=bhs&chco=000000,FF0000|00FF00|0000FF&chs=200x125&chd=s:FOE,elo&chxt=x,y&chxl=1:|Dec|Nov|Oct|0:||20K||60K||100K|*}
{/if}
{if $dDeliveryTerm}
		<div>
			<span class="span_h1">{$oLanguage->GetMessage("Provider DeliveryTerm Info")}: {$dDeliveryTermText} {$oLanguage->GetMessage('days')}</span>
<img src="http://chart.apis.google.com/chart?{strip}
cht=bhs
&chs=200x50
&chd=t:{$dDeliveryTerm}|{$dDeliveryTermSecond}
&chco=4d89f9,c6d9fd
&chbh=20
&chxt=x
&chxl=0:|0||30
{/strip}">
		</div>
{/if}
	</td>
</tr>
</table>
<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</span>
</span>
