<table width="100%" cellspacing="0">

<tr>
    <td colspan="3">
        <h4>Select language</h4><br>
        {foreach from=$aLangAssoc key=lng_id item=lng_name}
        <a href="{$sUrlWithoutLang}&id_lang={$lng_id}">{if $smarty.request.id_lang==$lng_id}<b>{$lng_name}</b>{else}{$lng_name}{/if}</a>
        &nbsp;&nbsp;
        {/foreach}
    </td>
</tr>

<tr>
<td width="40%" valign="top">

<br>
	<div class="tree">
	    <div onclick="tree_toggle(arguments[0])">
	        <h4>choose group parts</h4><br>
	        <a href="#" class="expand-p"><img alt="" src="/libp/mpanel/images/dtree/expandall.png"/></a>
			<a href="#" class="expand-m"><img alt="" src="/libp/mpanel/images/dtree/collapseall.png"/></a>
	        <ul class="Container" style="margin-bottom: 0;">
	{$sTecdocTree}
			</ul>
	    </div>
	</div>  
</td>

<td width="60%" valign="top">
<br>
	<h4>choose parts</h4>
	
	{$sTablePrice}

</td>

</tr>
</table>