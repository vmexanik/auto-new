<div class="at-fWrapper">
       <div class="wrapper-cell">
           <div class="at-footer">
               <div class="at-mainer">
                   <div class="mob-call">
                       <a href="tel:{$oLanguage->GetConstant('global:project_phone')}" class="at-btn call-at-btn">
                           <span>{$oLanguage->GetConstant('global:project_phone')}</span>
                       </a>
                   </div>

                   <div class="footer-wrapper">
                       <div class="foot-part part-info">
                           <div class="at-footer-info">
                               {*<strong class="caption">График работы:</strong><br/>
                               Пн-Пт: 9:00 - 19:00<br/>
                               Суббота: 10:00 - 14:00<br/>
                               Воскресенье: выходной<br/>

                               <a href="#" class="at-map-link">
                                   Как проехать в магазин
                               </a>*}
                               {$oLanguage->GetText('bottom_graphik')}
                           </div>
                       </div>

                       <div class="foot-part part-cats">
                           <div class="at-foot-cats">
                               {*<div class="item">
                                   <a href="#">Масла, автохимия</a>
                               </div>
                               <div class="item">
                                   <a href="#">Расходные материалы</a>
                               </div>
                               <div class="item">
                                   <a href="#">Аксессуары</a>
                               </div>
                               <div class="item">
                                   <a href="#">Подбор запчастей по авто</a>
                               </div>
                               <div class="item">
                                   <a href="#">Оставить VIN-запрос запчасти</a>
                               </div>*}
                               {$oLanguage->GetText('bottom_links1')}
                           </div>
                       </div>

                       <div class="foot-part part-soc">
                           {*<strong class="caption">Наши группы:</strong><br/>
                           <div class="at-soc">
                               <a href="#" class="vk"></a>
                               <a href="#" class="fb"></a>
                               <a href="#" class="gp"></a>
                               <div class="clear"></div>
                           </div>*}
                           {$oLanguage->GetText('bottom_links2')}
                       </div>

                       <div class="foot-part part-pay">
							{$oLanguage->GetText('accepted_for_payment')}
							{$oLanguage->GetText('site_counters')}
                           <div class="at-copy">
                               {$oLanguage->GetMessage('copyright')} - {$smarty.now|date_format:"%Y"}<br/>
                               <div class="dev-logo">
                     		  	 <a rel="nofollow" title="Разработка сайта автозапчастей" href="http://www.mstarproject.com/?action=tecdoc_mysql_site">
                       	   		   <img src="/image/dev-logo.png" alt="Разработка сайта автозапчастей">
                       			 </a>
                    		  </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
