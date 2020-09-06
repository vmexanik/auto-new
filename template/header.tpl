<!DOCTYPE html>
<html>
<head>
   <title>{strip}{if $template.sPageTitle}{$template.sPageTitle}
		{else}{$oLanguage->GetConstant('global:title','global:titleconstant')}{/if}{/strip}</title>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
   	<meta name="description" content="{strip}{if $template.sPageDescription}{$template.sPageDescription}
	{else}{$oLanguage->GetConstant('global:meta_description','global:meta_descriptionconstant')}{/if}{/strip}" />
	<meta name="keywords" content="{strip}{if $template.sPageKeyword}{$template.sPageKeyword}
	{else}{$oLanguage->GetConstant('global:meta_keyword','global:meta_keywordconstant')}{/if}{/strip}" />
   <meta name="format-detection" content="telephone=no"/>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
   <link rel="SHORTCUT ICON" {if $sFaviconType}type="{$sFaviconType}"{/if} href="{$oLanguage->GetConstant('favicon','/favicon.ico')}" />

   <link href="https://fonts.googleapis.com/css?family=Rubik:300,300i,400,400i,500&amp;subset=cyrillic" rel="stylesheet">
   {$template.sHeaderResource}
   {if $bHeaderPrint} {include file=header_print.tpl} {/if}
   
{if isset($sNextUrl)}
<link rel="next" href="{$sNextUrl}">
{/if}

{if isset($sPrevUrl)}
<link rel="prev" href="{$sPrevUrl}">
{/if}

{if isset($sUrlCanonical)}
<link rel="canonical" href="{$sUrlCanonical}">
{else}
  {if $bNoFollow && $bNoIndex}
  <meta name="robots" content="noindex, follow"/>
  {/if}
  {if $bNoIndexNoFollow}
  <meta name="robots" content="noindex, nofollow"/>
  {/if}
{/if}
{if $sAlternateUrl && $sAlternateUrlUa}
<link rel="alternate" href="{$sAlternateUrl}" hreflang="ru-UA"> 
<link rel="alternate" href="{$sAlternateUrlUa}" hreflang="uk-UA">
{/if}
   
   <script src="https://www.google.com/recaptcha/api.js" async defer></script>
   
</head>