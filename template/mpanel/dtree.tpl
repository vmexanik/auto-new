<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column nav-compact text-sm nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->
    
    <li class="nav-item">
      <a href="#" class="nav-link {if $smarty.request.action=='' || $smarty.request.action=='splash_xajax'}active{/if}" onclick="doAction(this, 'splash_xajax');">
        <i class="nav-icon fas fa-home"></i>
        <p>{$oLanguage->GetDMessage('Home')}</p>
      </a>
    </li>
    {if $aAdmin.login == $CheckLogin}
    <li class="nav-item">
      <a href="#" class="nav-link" onclick="doAction(this, 'admin_regulations');">
        <i class="nav-icon fas fa-home"></i>
        <p>{$oLanguage->GetDMessage('Admin regulations')}</p>
      </a>
    </li>
    {/if}
    
    <li class="nav-item has-treeview">
      <a href="#" class="nav-link"><i class="nav-icon fas fa-cogs"></i><p>{$oLanguage->GetDMessage('Configuration')}<i class="right fas fa-angle-left"></i></p></a>
      <ul class="nav nav-treeview">
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'general_constant');">
          <i class="fas fa-sliders-h nav-icon"></i><p>{$oLanguage->GetDMessage('General constants')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'constant');">
          <i class="fas fa-tasks nav-icon"></i><p>{$oLanguage->GetDMessage('Constants')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'currency');">
          <i class="fas fa-coins nav-icon"></i><p>{$oLanguage->GetDMessage('Currencies')}</p></a>
        </li>
        
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'language');">
          <i class="fas fa-user-cog nav-icon"></i><p>{$oLanguage->GetDMessage('Languages')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'admin');">
          <i class="fas fa-user-cog nav-icon"></i><p>{$oLanguage->GetDMessage('Administrators')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'style_colored');">
          <i class="fas fa-paint-brush nav-icon"></i><p>{$oLanguage->GetDMessage('style_colored')}</p></a>
        </li>
       
      </ul>
    </li>
    
    <li class="nav-item has-treeview">
      <a href="#" class="nav-link"><i class="nav-icon fas fa-file-signature"></i><p>{$oLanguage->GetDMessage('Content')}<i class="right fas fa-angle-left"></i></p></a>
      <ul class="nav nav-treeview">
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'drop_down');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('Dropdown Manager')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'content_editor');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('Content Editor')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'banner');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('Caorusel Editor')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'drop_down_additional');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('Dropdown Additional')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'translate_message');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('Message translate')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'translate_text');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('Text translate')}</p></a>
        </li>
        
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'translate');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('Translate')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'template');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('Templates')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'news');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('news')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'delivery_type');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('Delivery types')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'payment_type');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('Payment types')}</p></a>
        </li>

        <!-- li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'rating');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('Rating')}</p></a>
        </li -->
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'popular_products');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('popular products')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'export_xml');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('Export xml')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'sitemap_links');">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('Sitemap')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, ''); javascript:OpenFileBrowser('/libp/kcfinder/browse.php?Type=Image&Connector=php_connector/connector.php&return_id={$sType}', 600, 400);">
          <i class="fas fa-file-signature nav-icon"></i><p>{$oLanguage->GetDMessage('upload_images')}</p></a>
        </li>
       
      </ul>
    </li>

    <li class="nav-item has-treeview">
      <a href="#" class="nav-link"><i class="nav-icon fas fa-user-friends"></i><p>{$oLanguage->GetDMessage('Users')}<i class="right fas fa-angle-left"></i></p></a>
      <ul class="nav nav-treeview">
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'customer_group');">
          <i class="fas fa-user-friends nav-icon"></i><p>{$oLanguage->GetDMessage('Customer groups')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'customer');">
          <i class="fas fa-user-friends nav-icon"></i><p>{$oLanguage->GetDMessage('Customers')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'manager');">
          <i class="fas fa-user-friends nav-icon"></i><p>{$oLanguage->GetDMessage('Manager')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'provider_group');">
          <i class="fas fa-user-friends nav-icon"></i><p>{$oLanguage->GetDMessage('Provider groups')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'provider_region');">
          <i class="fas fa-user-friends nav-icon"></i><p>{$oLanguage->GetDMessage('Provider regions')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'provider');">
          <i class="fas fa-user-friends nav-icon"></i><p>{$oLanguage->GetDMessage('Providers')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'complex_margin');">
          <i class="fas fa-user-friends nav-icon"></i><p>{$oLanguage->GetDMessage('Complex Margin')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'discount');">
          <i class="fas fa-user-friends nav-icon"></i><p>{$oLanguage->GetDMessage('Dynamic discounts')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'account');">
          <i class="fas fa-user-friends nav-icon"></i><p>{$oLanguage->GetDMessage('Account')}</p></a>
        </li>
       
      </ul>
    </li>
    
    <li class="nav-item has-treeview">
      <a href="#" class="nav-link"><i class="nav-icon fas fa-user-shield"></i><p>{$oLanguage->GetDMessage('Access manager roles')}<i class="right fas fa-angle-left"></i></p></a>
      <ul class="nav nav-treeview">
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'role_action_exeption');">
          <i class="fas fa-user-shield nav-icon"></i><p>{$oLanguage->GetDMessage('Exceptions')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'role_action_group');">
          <i class="fas fa-user-shield nav-icon"></i><p>{$oLanguage->GetDMessage('Group permissions')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'role_manager');">
          <i class="fas fa-user-shield nav-icon"></i><p>{$oLanguage->GetDMessage('Manager roles')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'role_permissions');">
          <i class="fas fa-user-shield nav-icon"></i><p>{$oLanguage->GetDMessage('Roles permissions')}</p></a>
        </li>
       
      </ul>
    </li>
    
    <li class="nav-item has-treeview">
      <a href="#" class="nav-link"><i class="nav-icon fas fa-question-circle"></i><p>{$oLanguage->GetDMessage('Customer support')}<i class="right fas fa-angle-left"></i></p></a>
      <ul class="nav nav-treeview">
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'context_hint');">
          <i class="fas fa-question-circle nav-icon"></i><p>{$oLanguage->GetDMessage('Context hints')}</p></a>
        </li>
       
      </ul>
    </li>

    <li class="nav-item has-treeview">
      <a href="#" class="nav-link"><i class="nav-icon fas fa-list"></i><p>{$oLanguage->GetDMessage('Logs')}<i class="right fas fa-angle-left"></i></p></a>
      <ul class="nav nav-treeview">
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'log_finance');">
          <i class="fas fa-list nav-icon"></i><p>{$oLanguage->GetDMessage('Finance log')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'log_mail');">
          <i class="fas fa-list nav-icon"></i><p>{$oLanguage->GetDMessage('Mail Queue')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'log_visit');">
          <i class="fas fa-list nav-icon"></i><p>{$oLanguage->GetDMessage('Visit log')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'log_admin');">
          <i class="fas fa-list nav-icon"></i><p>{$oLanguage->GetDMessage('Log Admin')}</p></a>
        </li>
       
      </ul>
    </li>

	<li class="nav-item has-treeview">
      <a href="#" class="nav-link"><i class="nav-icon fas fa-car-side"></i><p>{$oLanguage->GetDMessage('Auto catalog')}<i class="right fas fa-angle-left"></i></p></a>
      <ul class="nav nav-treeview">
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'cat');">
          <i class="fas fa-car-side nav-icon"></i><p>{$oLanguage->GetDMessage('Catalog list')}</p></a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'cat_part');">
          <i class="fas fa-car-side nav-icon"></i><p>{$oLanguage->GetDMessage('Parameter Parts')}</p></a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'cat_pref');">
          <i class="fas fa-car-side nav-icon"></i><p>{$oLanguage->GetDMessage('Cat pref')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'cat_model');">
          <i class="fas fa-car-side nav-icon"></i><p>{$oLanguage->GetDMessage('Cat model')}</p></a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'cat_model_group');">
          <i class="fas fa-car-side nav-icon"></i><p>{$oLanguage->GetDMessage('Cat model group')}</p></a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'price');">
          <i class="fas fa-car-side nav-icon"></i><p>{$oLanguage->GetDMessage('Price')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'price_group');">
          <i class="fas fa-car-side nav-icon"></i><p>{$oLanguage->GetDMessage('Price group')}</p></a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'handbook');">
          <i class="fas fa-car-side nav-icon"></i><p>{$oLanguage->GetDMessage('Handbook')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'hbparams_editor');">
          <i class="fas fa-car-side nav-icon"></i><p>{$oLanguage->GetDMessage('Handbook params editor')}</p></a>
        </li>
      
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="doAction(this, 'rubricator');">
          <i class="fas fa-car-side nav-icon"></i><p>{$oLanguage->GetDMessage('Rubricator')}</p></a>
        </li>
       
      </ul>
    </li>

    <li class="nav-item">
      <a href="#" class="nav-link" onclick="doAction(this, 'trash');">
        <i class="nav-icon fas fa-trash"></i>
        <p>{$oLanguage->GetDMessage('Trash')}</p>
      </a>
    </li>
    
    <li class="nav-item">
      <a href="./?action=quit" class="nav-link">
        <i class="nav-icon fas fa-door-open"></i>
        <p>{$oLanguage->GetDMessage('Logout')}</p>
      </a>
    </li>
   
    <li class="nav-header">{$oLanguage->GetDMessage('Помощь')}</li>
    <li class="nav-item">
      <a target="_blank" href="http://manual.mstarproject.com/index.php/%D0%94%D0%B5%D0%BC%D0%BE_%D1%81%D0%B0%D0%B9%D1%82_%D0%B0%D0%B2%D1%82%D0%BE%D0%B7%D0%B0%D0%BF%D1%87%D0%B0%D1%81%D1%82%D0%B5%D0%B9_%D1%80%D0%B5%D0%B4%D0%B8%D0%B7%D0%B0%D0%B9%D0%BD_-_%D0%9F%D0%B0%D0%BA%D0%B5%D1%82_%D0%A1%D1%82%D0%B0%D0%BD%D0%B4%D0%B0%D1%80%D1%82" class="nav-link">
        <i class="nav-icon fas fa-file"></i>
        <p>{$oLanguage->GetDMessage('Документация сайта')}</p>
      </a>
    </li>
    
  </ul>
</nav>



{literal}
<script type="text/javascript">
	function doAction(tag, action) {
		$('.nav-link').each(function( index ) {
			$(this).removeClass("active");
		});
		
		$(tag).addClass("active");
		$(tag).parent().parent().parent().find('a').first().addClass("active");

		xajax_process_browse_url('?action='+action+'&click_from_menu=1');
		return false;
	}
</script>
{/literal}