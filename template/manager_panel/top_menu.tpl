<nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Сайт</a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
            	{foreach from=$aMenuTop item=sTitle key=sKey}
			  		<li id="{$sKey}"><a href="#" onclick="xajax_process_browse_url('?action={$sKey}&click_from_menu=1'); return false;">{$sTitle}</a></li>
			  	{/foreach}
            </ul>
            <div class="bell">
          		<a href="#" onclick="xajax_process_browse_url('?action=manager_panel_manager_package_list_view_logcp&click_from_menu=1&display='+$('.js_manager_panel_popup_log').css('display')); return false;">
			    <img id="id_ding" src="/image/design/{if $aAuthUser.is_warning}ding_alarm.png{else}ding.png{/if}" style="border:0px;vertical-align: middle;">
          		{*if $aTemplateNumber.message_number}<span class="cnt_bell">{$aTemplateNumber.message_number}</span>{/if*}</a>
	    <div class="js_manager_panel_popup_log" style="display: none;">
		<div class="block-popup panel-default">
    		    <div class="panel-body" id="body_popup_logcp" style="overflow:auto;"></div>
    		</div>
	    </div>

          		<div class="currency">
	          		{foreach from=$aListCurrency key=key item=sItem}
	          			<span>{$key}: {$sItem}</span>
	          		{/foreach}
          		</div>
          		<div class="logout">
          			<a href="./?action=quit">{$oLanguage->getDMessage('logout')}</a>
          		</div>
          	</div>
          </div><!--/.nav-collapse -->
        </div>
      </nav>