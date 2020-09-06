<div class="at-user-details">
    <div class="header">
        {$oLanguage->getMessage('edit code')}
    </div>
</div>

<form class="">
	<div class="at-block-form" style="background-color: #ffffff;box-shadow: 0 0 10px #cadae2;margin: 0 0 20px 0;">
		<table width="100%" border=0 class="at-tab-table">
		<tr>
			<td><div class="order-num">{$oLanguage->getMessage('brand')}</div>
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
			<td><div class="order-num">{$oLanguage->getMessage('price cod')}</div>
			    <p>{$aPi.code_in}</p>
	   		    <input type=hidden id=code_in_{$aPi.id} value={$aPi.code_in}>
			</td>
			<td>
			  <span>{$oLanguage->getMessage('~parser_before')}</span>
			    <p><input type=text id=parser_before_{$aPi.id} name=data[parser_before] value='{$aCat.parser_before}' maxlength=50 style='width:270px'></p>
			  <span>{$oLanguage->getMessage('~parser_after')}</span>
			    <p><input type=text id=parser_after_{$aPi.id} name=data[parser_after] value='{$aCat.parser_after}' maxlength=50 style='width:270px'></p>
			  <span>{$oLanguage->getMessage('~trim_left_by')}</span>
			    <p><input type=text id=trim_left_by_{$aPi.id} name=data[trim_left_by] value='{$aCat.trim_left_by}' maxlength=50 style='width:270px'></p>
			  <span>{$oLanguage->getMessage('~trim_right_by')}</span>
			    <p><input type=text id=trim_right_by_{$aPi.id} name=data[trim_right_by] value='{$aCat.trim_right_by}' maxlength=50 style='width:270px'></p>
			</td>
		</tr>
		<tr>
			<td colspan=3><hr></td>
		</tr>
		<tr>
			<td colspan=3>
				<a href="javascript:;" onclick="check_product_e({$aPi.id});">
					<img src="/image/apply.png" title="{$oLanguage->getMessage('check_product')}">{$oLanguage->getMessage('check_product')}
				</a>
				<a href="javascript:;" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want create product?")}'))
					{literal}{{/literal}create_product({$aPi.id}){literal}}{/literal} else {literal}{{/literal}return false;}">
					<img src="/image/plus.png" title="{$oLanguage->getMessage('create_product')}">{$oLanguage->getMessage('create_product')}
				</a>
				<a href="javascript:;" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want locked product?")}'))
					{literal}{{/literal}locked_product({$aPi.id}){literal}}{/literal} else {literal}{{/literal}return false;}">
					<img src="/image/delete.png" title="{$oLanguage->getMessage('locked product')}">{$oLanguage->getMessage('locked product')}
				</a>
			</td>
		</tr>
		<tr>
			<td id="info_change_code" colspan=3></td>
		</tr>
	</table>
	{if $sReturnAction}
		<input type=hidden id="return_action_{$aPi.id}" name="return_action" value="{$sReturnAction|urlencode}">
	{/if}
	<input type=hidden id="checked_code_ok_{$aPi.id}" value="0">
	<input type=hidden id=id_provider_{$aPi.id} value={$iIdProvider}>
	</div>
</form>