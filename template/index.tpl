{ include file=header.tpl }

{if $aAuthUser.type_=='manager' && ($smarty.request.action!='' && $smarty.request.action!='home')}
<style>
   .at-mainer {ldelim}
    max-width:97% !important;
   {rdelim}
    
    .at-plist-thumbs > li {ldelim}
    	width: 19.70% !important;
    {rdelim}
 	.mi-nav {ldelim}
    	margin:0 !important;
    {rdelim}
</style>
{/if}

<body>
{$sTimer}
{$template.sOuterJavascript}
<div id="opaco" style="display:none; background-color: #777; z-index: 101; left:0; top:0; position: fixed; width: 100%;height: 4000px;
	 filter:progid:DXImageTransform.Microsoft.Alpha(opacity=50);-moz-opacity: 0.5;-khtml-opacity: 0.5;opacity: 0.5;"></div>
	 
<div class="at-gWrapper">
{include file=index_include/body_content.tpl }
{include file=index_include/body_header.tpl}
{include file=index_include/body_footer.tpl}
</div>


{include file=index_include/popup_basket.tpl }
{include file=index_include/popup_auth.tpl }
{include file=index_include/popup_callme.tpl }
{include file=index_include/popup_ok.tpl }

<div class="at-mask js-phones-drop-mask"
    onclick="$('.at-phones-top').removeClass('active');
    $('.js-phones-drop, .js-phones-drop-mask').hide();
    "></div>
{*<div class="at-mask js-year-choose-mask" style="display:block;"
    onclick="$('.at-custom-select-wrap').removeClass('opened');
    $('.select-drop, .js-year-choose-mask').hide();
    "></div>*}

<div class="at-mask js-auth-mask" onclick="atCabinetMenuClose();"></div>
<div class="at-mask-tpages js-tpages-mask" onclick="atTopMenuClose();"></div>
<div class="res"></div>

{include file=footer.tpl }