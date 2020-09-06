<div class="flags">
	<a href="http://{$aGeneralConf.CookieDomain}/"><img src="/imgbank/Image/flag/ua.gif" alt="UA" width="18" height="12" /></a>
	<a href="http://{$aGeneralConf.RegionDomain}/ru/"><img src="/imgbank/Image/flag/flag_ru.gif" alt="RU" width="18" height="12" /></a>
	<a href="http://{$aGeneralConf.RegionDomain}/en/"><img src="/imgbank/Image/flag/flag_en.gif" alt="EN" width="18" height="12" /></a>
	<a href="http://{$aGeneralConf.RegionDomain}/me/"><img src="/imgbank/Image/flag/flag_me.gif" alt="ME" width="18" height="12" /></a>
</div>


{*
<div><a href='/{$sQueryString}' style="
	{if $sLocale=='ru'}color:red; font-size: 14px;{/if}
	">{$oLanguage->getMessage("Russian")}<img src='/imgbank/Image/flag/ru.gif'
	width=30 hspace=2 border=0 align=absmiddle style="padding-left: 5px;"
	title='Russian'></a>  </div>

{section name=d loop=$aLanguageList}
<div style="padding-top:10px;">
<a href='/{$aLanguageList[d].code}/{$sQueryString}' style="
	{if $sLocale==$aLanguageList[d].code}color:red; font-size: 14px;{/if}
	">{$aLanguageList[d].name}<img src='{$aLanguageList[d].image}'
	width=30 hspace=2 border=0 align=absmiddle  style="padding-left: 5px;"
	title='{$aLanguageList[d].name}'></a>
<div>
{/section}

*}