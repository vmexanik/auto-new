{foreach key=name item=value from=$aMessageJavascript}
	<input type="hidden" id="{$name}" value="{$value}"/>
{/foreach}
