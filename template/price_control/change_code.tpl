<div class="at-user-details">
    <div class="header">
        {$oLanguage->getMessage('change code')}
    </div>
</div>

<form class="">
	<div class="at-block-form" style="background-color: #ffffff;box-shadow: 0 0 10px #cadae2;margin: 0 0 20px 0;">
	<table width="100%" border=0 class="at-tab-table">
		<tr>
			<td><div class="order-num">{$oLanguage->getMessage('provider')}</div>
			    <p>{$aPi.name_provider}</p>
			    <input type=hidden id=id_provider_{$aPi.id} value={$iIdProvider}>
			</td>
			<td>
			  	<div class="order-num">{$oLanguage->getMessage('brand')}</div>
			    <p>{$aPi.cat}</p>
			    <span style="font-size:12px;">
			    {if $aPi.id_tof}
			    	бренд TecDoc
			    {else}
			    	не TecDoc бренд
			    {/if}
			    </span>
	   		    <input type=hidden id=cat_{$aPi.id} value={$aPi.cat}>
			</td>
			<td>
			  <div class="order-num">{$oLanguage->getMessage('price cod')}</div>
			    <p>{$aPi.code_in}</p>
	   		    <input type=hidden id=code_in_{$aPi.id} value={$aPi.code_in}>
			</td>
			<td>
			  <div class="order-num">{$oLanguage->getMessage('brand replace')}</div>
			    <p>
					<select class="js-select" name=data[pref] id="pref_{$aPi.id}" style='width:270px'">
					{html_options options=$aPrefAssoc selected=$aPi.pref}
					</select>
				</p>
			</td>
			<td>
		  		<div class="order-num">{$oLanguage->getMessage('code change')}</div>
			    <p><input type="text" id="code_change_{$aPi.id}" name="data[code_change]" value="" required></p>
			</td>
		</tr>
		<tr>
			<td colspan=5><hr></td>
		</tr>
		<tr>
			<td colspan=5>
				<a href="javascript:;" onclick="check_product({$aPi.id});">
					<img src="/image/apply.png" title="{$oLanguage->getMessage('check_product')}">{$oLanguage->getMessage('check_product')}
				</a>
				<a href="javascript:;" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want replace code?")}'))
					{literal}{{/literal}change_code({$aPi.id}){literal}}{/literal} else {literal}{{/literal}return false;}">
					<img src="/image/plus.png" title="{$oLanguage->getMessage('replace_code')}">{$oLanguage->getMessage('replace_code')}
				</a>
				<a href="javascript:;" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want locked product?")}'))
					{literal}{{/literal}locked_product({$aPi.id}){literal}}{/literal} else {literal}{{/literal}return false;}">
					<img src="/image/delete.png" title="{$oLanguage->getMessage('locked product')}">{$oLanguage->getMessage('locked product')}
				</a>
			</td>
		</tr>
		<tr>
			<td id="info_change_code" colspan=5></td>
		</tr>
	</table>
	{if $sReturnAction}
		<input type=hidden id="return_action_{$aPi.id}" name="return_action" value="{$sReturnAction|urlencode}">
	{/if}
	<input type=hidden id="checked_code_ok_{$aPi.id}" value="0">
</form>