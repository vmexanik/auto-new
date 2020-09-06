<div class="at-cWrapper">
   <div class="wrapper-cell">
       <div class="js-width-sync">
       {if $smarty.request.action=='home' || !$smarty.request.action}
           {if $oLanguage->getConstant('main_page:visible_action_block','0')}
               {include file=index_include/baner.tpl }
           {/if}
       {/if}  
           
		{if !$smarty.cookies.id_model_detail}
			{$sShowCarSelect}
		{else}
			{$sShowCarSelected}
		{/if}  
           
           <div class="at-mainer">
                {if $smarty.request.action!='' && $smarty.request.action!='home'}
                {if $aCrumbs}
                <div class="at-crumbs">
                   {foreach from=$aCrumbs item=aItem name=crumb_ar}
		                <a class="mob-link" href="{$aItem.link}">{$aItem.name}</a>
		                <div><a href="{$aItem.link}">{$aItem.name}</a></div>
				   {/foreach}
               </div>
               {/if}
               {/if}
               
               {if $sIndexMessage}<div class="{$sIndexMessageClass}">{$sIndexMessage}</div>{/if}
               {if $template.sPageH1}<h1 {if $smarty.request.action=='rubricator_category' || $smarty.request.action=='price_group'}class="js-at-plist-header"{/if}>{$template.sPageH1}</h1>{/if}
               
               {if $aAuthUser.id && !($oContent->IsChangeableLogin($aAuthUser.login)) && $oContent->CheckDashboard($smarty.request.action)}
                   {include file='user/left_panel.tpl'}               
                   <div class="at-layer-mid">
                   {$sText}
                   </div>
               {else}
                   {$sText}
               {/if}

               <div class="at-seo">
                 {if $smarty.request.action=='' || $smarty.request.action=='home'}
                    {$oLanguage->GetText('home bottom text')}
                 {else}
                    {if $template.sDescription && $template.sDescription!="&nbsp;"}{$template.sDescription}{/if}
                 {/if}
              </div>
              
           </div>
		
       </div>
   </div>
</div>