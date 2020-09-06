<h2 class="at-index-brands-title">{$oLanguage->getMessage('Select parts by auto')}</h2>

                   <div class="at-index-brands">
                       <div class="at-toggler js-brands-lists-toggle">
                           <a class="selected" href="javascript:void(0);" data-type="thumbs"></a>
                           <a href="javascript:void(0);" data-type="list"></a>
                           <div class="clear"></div>
                       </div>

                       <div class="container thumbs">
                       {foreach item=aItem from=$aCatalog}
                       		{if $oLanguage->getConstant('global:url_is_lower',0)}
						       <a class="at-brand-thumb" href="/rubricator/c/{$oContent->Translit($aItem.c_name)|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">
								{else}
						       <a class="at-brand-thumb" href="/rubricator/c/{$oContent->Translit($aItem.c_name)}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">
								{/if}
                               <span class="image"><i><img src="{$aItem.image}" alt="{$aItem.name}" title="{$aItem.name}"></i></span>
                               <span class="caption">{$oLanguage->GetMessage('spareparts')}</span>
                               <span class="brand">{$aItem.name}</span>
                           </a>
						{/foreach}
                           <div class="at-brand-thumb empty"></div>
                           <div class="at-brand-thumb empty"></div>
                           <div class="at-brand-thumb empty"></div>
                           <div class="at-brand-thumb empty"></div>
                           <div class="at-brand-thumb empty"></div>
                           <div class="at-brand-thumb empty"></div>
						{if $smarty.request.action!='catalog'}
                           <div class="show-more">
                               <a href="/rubricator" class="at-btn">{$oLanguage->GetMessage('all brands')}</a>
                           </div>
                       {/if}    
                       </div>

                       <div class="container list" style="display: none">
                           <ul class="at-brands-list">
                           {foreach item=aItem from=$aCatalog}
                           {if $oLanguage->getConstant('global:url_is_lower',0)}
						       <li><a href="/rubricator/c/{$oContent->Translit($aItem.c_name)|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">
								{else}
						       <li><a href="/rubricator/c/{$oContent->Translit($aItem.c_name)}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">
								{/if}
                               {$oLanguage->GetMessage('spareparts')} {$aItem.name}
                               </a></li>
                           {/foreach}    
                           </ul>
							{if $smarty.request.action!='catalog'}
                           <div class="show-more">
                               <a href="/rubricator" class="at-btn">Показать все</a>
                           </div>
                           {/if} 
                       </div>
                   </div>
