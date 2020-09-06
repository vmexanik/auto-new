<h1 class="at-plist-header">&nbsp;</h1>

<div class="at-plist-tools">
    <div class="at-sort">
         <select class="js-selectbox" onchange="javascript:window.location.href=($(this).val())">
                {foreach from=$aSortArray item=aSortItem}
                <option value="{$sGroupTableUrl}/sort={$aSortItem.sort}/way={$aSortItem.way}">{$oLanguage->getMessage($aSortItem.name)}</option>
                {/foreach}
          </select>
    </div>

    <div class="at-toggler">
        {if $smarty.request.table!='gallery'}
          <a href='{$sGroupChangeTableUrl}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}' class="selected" data-type="thumbs"></a>
          <a href='javascript:void(0);' data-type="list"></a>
        {else}  
          <a href='javascript:void(0);' data-type="thumbs"></a>
          <a href='{$sGroupChangeTableUrl}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}' class="selected" data-type="list"></a>
        {/if}
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>

<div class="at-layer-left">
    {include file='table/sidebar_filter.tpl'}
</div>

<div class="at-layer-mid">
    <ul class="at-plist-thumbs">
        {assign var="iTr" value="0"}
		{section name=d loop=$aItem}
		{assign var=aRow value=$aItem[d]}
		{assign var=iTr value=$iTr+1}
			{include file=$sDataTemplate}
		{/section}
    </ul>
    
    {if !$aItem}

        {if $sNoItem}
    		{$oLanguage->getMessage($sNoItem)}
    	{else}
    		{$oLanguage->getMessage("No items found")}
    	{/if}
    	
    {/if}

    {if $sStepper}
		{$sStepper}
	{/if}
</div>
<div class="clear"></div>
<br>