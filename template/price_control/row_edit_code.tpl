{foreach key=sKey item=item from=$oTable->aColumn}

{if $sKey=='action'}
<td>
<div class="order-num">
	<a href="javascript:;" onclick="update_cat_parse({$aRow.id});">
		<img src="/image/apply.png" title="{$oLanguage->getMessage('save')}">{$oLanguage->getMessage('save')}
	</a>
	<br><a href="javascript:;" onclick="check_install_cat_parse({$aRow.id});">
		<img src="/image/build.png" title="{$oLanguage->getMessage('check_and_install_brand')}">&nbsp;{$oLanguage->getMessage('check_and_install_brand')}
	</a>
</div>
</td>
{elseif $sKey=='parser_info'}
<td>
<div class="">
	<div class="line-block" style="min-width: 250px; max-width: 250px;">
		<span>{$oLanguage->getDMessage('parser_before')}:</span> <input type=text id=parser_before_{$aRow.id} name=data[parser_before] value='{$aRow.parser_before|escape}' maxlength=50 style='width:250px'>
		<span>{$oLanguage->getDMessage('parser_after')}:</span> <input type=text id=parser_after_{$aRow.id} name=data[parser_after] value='{$aRow.parser_after|escape}' maxlength=50 style='width:250px'>
		<span>{$oLanguage->getDMessage('trim_left_by')}:</span> <input type=text id=trim_left_by_{$aRow.id} name=data[trim_left_by] value='{$aRow.trim_left_by|escape}' maxlength=50 style='width:250px'>
		<span>{$oLanguage->getDMessage('trim_right_by')}:</span> <input type=text id=trim_right_by_{$aRow.id} name=data[trim_right_by] value='{$aRow.trim_right_by|escape}' maxlength=50 style='width:250px'>
	</div>
</div>
</td>
{else}
<td>
<div class="order-num">
  <span>{$item.sTitle}</span>
    <p>{$aRow.$sKey}</p>
</div>
</td>
{/if}
{/foreach}