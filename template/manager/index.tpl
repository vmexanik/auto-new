
<a name='top'>
{section name=d loop=$aManagerList}
<div>
	<img src="/image/icn_arrow_anchor.gif" hspace=5 border=0><b><a href="#{$aManagerList[d].id}">{$aManagerList[d].city}</b></a>
</div>
{/section}
<br>
<br>

<p>
{section name=d loop=$aManagerList}
<div><a name="{$aManagerList[d].id}">
	<img src="/image/icn_back_top.gif" hspace=5 border=0><b><a href="#top">{$aManagerList[d].city}</a></b><br>
<p>
{$aManagerList[d].name}
<p>
{$aManagerList[d].address}
</div>
{/section}

