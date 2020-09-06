<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:15
         compiled from mpanel/dtree.tpl */ ?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">

<div class="dtree_hd">
<a href="javascript: d.openAll();"><img border="0" src="/libp/mpanel/images/dtree/expandall.png"/></a>
<a href="javascript: d.closeAll();"><img border="0" src="/libp/mpanel/images/dtree/collapseall.png"/></a>
</div>

<script type="text/javascript">
<!--
d = new dTree('d');
d.add(0,-1,'&nbsp;<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('My dSAP menu'); ?>
');
d.add(1001,0,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Home'); ?>
','#','','','','/libp/mpanel/images/dtree/colorman.png'
,'/libp/mpanel/images/dtree/colorman.png',
' xajax_process_browse_url(\'?action=splash_xajax&click_from_menu=1\');  return false;');

<?php if ($this->_tpl_vars['aAdmin']['login'] == $this->_tpl_vars['CheckLogin']): ?>
d.add(50,0,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Admin regulations'); ?>
','#','','','','/image/mpanel/admin_regulations.png','/image/mpanel/admin_regulations.png',
'xajax_process_browse_url(\'?action=admin_regulations&lick_from_menu=1\'); return false;');
<?php endif; ?>

d.add(1,0,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Configuration'); ?>
','#','','','/libp/mpanel/images/dtree/log.png'
,'/libp/mpanel/images/dtree/log.png');
d.add(5,1,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('General constants'); ?>
','#','','','','/libp/mpanel/images/dtree/log.png'
,'/libp/mpanel/images/dtree/log.png',
'xajax_process_browse_url(\'?action=general_constant&click_from_menu=1\'); return false;');
d.add(10,1,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Constants'); ?>
','#','','','','/libp/mpanel/images/dtree/log.png'
,'/libp/mpanel/images/dtree/log.png',
'xajax_process_browse_url(\'?action=constant&click_from_menu=1\'); return false;');
d.add(11,1,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Currencies'); ?>
','#','','','','/libp/mpanel/images/dtree/log.png'
,'/libp/mpanel/images/dtree/log.png',
'xajax_process_browse_url(\'?action=currency&click_from_menu=1\'); return false;');
//d.add(12,1,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Languages'); ?>
','#','','','','/libp/mpanel/images/dtree/log.png'
//,'/libp/mpanel/images/dtree/log.png',
//' xajax_process_browse_url(\'?action=language&click_from_menu=1\');  return false;');
d.add(13,1,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Administrators'); ?>
','#','','','','/libp/mpanel/images/dtree/log.png'
,'/libp/mpanel/images/dtree/log.png',
'xajax_process_browse_url(\'?action=admin&click_from_menu=1\'); return false;');
d.add(14,1,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('style_colored'); ?>
','#','','','','/libp/mpanel/images/dtree/log.png'
,'/libp/mpanel/images/dtree/log.png',
'xajax_process_browse_url(\'?action=style_colored&click_from_menu=1\'); return false;');

d.add(100,0,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Content'); ?>
','#','','','','');
d.add(101,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Dropdown Manager'); ?>
','#','','','','',''
,'xajax_process_browse_url(\'?action=drop_down&click_from_menu=1\'); return false;');
d.add(102,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Content Editor'); ?>
','#','','','','',''
,' xajax_process_browse_url(\'?action=content_editor&click_from_menu=1\');  return false;');
d.add(103,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Caorusel Editor'); ?>
','#','','','','',''
,' xajax_process_browse_url(\'?action=banner&click_from_menu=1\');  return false;');
d.add(104,100,'<?php echo $this->_tpl_vars['oLanguage']->getDMessage('Dropdown Additional'); ?>
','#','','','','',''
,'xajax_process_browse_url(\'?action=drop_down_additional&click_from_menu=1\'); return false;');
d.add(106,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Message translate'); ?>
','#','','','','',''
,' xajax_process_browse_url(\'?action=translate_message&click_from_menu=1\');  return false;');
d.add(107,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Text translate'); ?>
','#','','','','',''
,' xajax_process_browse_url(\'?action=translate_text&click_from_menu=1\');  return false;');
//d.add(107,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Translate'); ?>
','#','','','','',''
//,' xajax_process_browse_url(\'?action=translate&click_from_menu=1\');  return false;');
d.add(110,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Templates'); ?>
','#','','','','',''
,' xajax_process_browse_url(\'?action=template&click_from_menu=1\');  return false;');
d.add(112,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('News'); ?>
','#','','','','',''
,' xajax_process_browse_url(\'?action=news&click_from_menu=1\');  return false;');
d.add(140,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Delivery types'); ?>
','#','','','','',''
,' xajax_process_browse_url(\'?action=delivery_type&click_from_menu=1\');  return false;');
d.add(150,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Payment types'); ?>
','#','','','','',''
,' xajax_process_browse_url(\'?action=payment_type&click_from_menu=1\');  return false;');
d.add(155,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Rating'); ?>
','#','','','','',''
,' xajax_process_browse_url(\'?action=rating&click_from_menu=1\');  return false;');
d.add(156,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('popular products'); ?>
','#','','','','',''
,' xajax_process_browse_url(\'?action=popular_products&click_from_menu=1\');  return false;');
d.add(157,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Export xml'); ?>
','#','','','','',''
,' xajax_process_browse_url(\'?action=export_xml&click_from_menu=1\');  return false;');
d.add(158,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Sitemap'); ?>
','#','','','','',''
,' xajax_process_browse_url(\'?action=sitemap_links&click_from_menu=1\');  return false;');
d.add(159,100,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('upload_images'); ?>
','#','','','','',''
,'return false;');




d.add(200,0,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Users'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png');
d.add(202,200,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Customer groups'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png',''
,' xajax_process_browse_url(\'?action=customer_group&click_from_menu=1\');  return false;');
d.add(203,200,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Customers'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png',''
,' xajax_process_browse_url(\'?action=customer&click_from_menu=1\');  return false;');
d.add(204,200,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Manager'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png',''
,' xajax_process_browse_url(\'?action=manager&click_from_menu=1\');  return false;');
d.add(205,200,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Provider groups'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png',''
,' xajax_process_browse_url(\'?action=provider_group&click_from_menu=1\');  return false;');
d.add(207,200,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Provider regions'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png',''
,' xajax_process_browse_url(\'?action=provider_region&click_from_menu=1\');  return false;');
d.add(210,200,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Providers'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png',''
,' xajax_process_browse_url(\'?action=provider&click_from_menu=1\');  return false;');
d.add(211,200,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Complex Margin'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png',''
,' xajax_process_browse_url(\'?action=complex_margin&click_from_menu=1\');  return false;');
d.add(231,200,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Dynamic discounts'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png',''
,' xajax_process_browse_url(\'?action=discount&click_from_menu=1\');  return false;');
d.add(240,200,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Account'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png',''
,' xajax_process_browse_url(\'?action=account&click_from_menu=1\');  return false;');

d.add(250,0,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Access manager roles'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png');
d.add(251,250,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Exceptions'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png',''
,' xajax_process_browse_url(\'?action=role_action_exeption&click_from_menu=1\');  return false;');
d.add(252,250,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Group permissions'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png',''
,' xajax_process_browse_url(\'?action=role_action_group&click_from_menu=1\');  return false;');
d.add(253,250,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Manager roles'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png',''
,' xajax_process_browse_url(\'?action=role_manager&click_from_menu=1\');  return false;');
d.add(254,250,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Roles permissions'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
,'/libp/mpanel/images/dtree/groupevent.png',''
,' xajax_process_browse_url(\'?action=role_permissions&click_from_menu=1\');  return false;');

//d.add(270,0,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('User finance'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
//,'/libp/mpanel/images/dtree/groupevent.png');
// d.add(271,270,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('User account log'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
// ,'/libp/mpanel/images/dtree/groupevent.png',''
// ,' xajax_process_browse_url(\'?action=user_account_log&click_from_menu=1\');  return false;');
// d.add(272,270,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Account Log month'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
// ,'/libp/mpanel/images/dtree/groupevent.png',''
// ,' xajax_process_browse_url(\'?action=account_log_month&click_from_menu=1\');  return false;');
// d.add(273,270,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('User Account Log Types'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
// ,'/libp/mpanel/images/dtree/groupevent.png',''
// ,' xajax_process_browse_url(\'?action=user_account_log_type&click_from_menu=1\');  return false;');
//d.add(274,270,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('User Account Type Operations'); ?>
','#','','','/libp/mpanel/images/dtree/groupevent.png'
//,'/libp/mpanel/images/dtree/groupevent.png',''
//,' xajax_process_browse_url(\'?action=user_account_type_operation&click_from_menu=1\');  return false;');

d.add(300,0,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Customer support'); ?>
','#','','','/libp/mpanel/images/dtree/aim.png'
,'/libp/mpanel/images/dtree/aim.png');
d.add(312,300,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Context hints'); ?>
','#','','','/libp/mpanel/images/dtree/aim.png'
,'/libp/mpanel/images/dtree/aim.png',''
,' xajax_process_browse_url(\'?action=context_hint&click_from_menu=1\');  return false;');

d.add(400,0,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Logs'); ?>
','#','','','/libp/mpanel/images/dtree/notebook.png'
,'/libp/mpanel/images/dtree/notebook.png');
d.add(401,400,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Finance log'); ?>
','#','','','/libp/mpanel/images/dtree/notebook.png'
,'/libp/mpanel/images/dtree/notebook.png',''
,' xajax_process_browse_url(\'?action=log_finance&click_from_menu=1\');  return false;');
d.add(413,400,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Mail Queue'); ?>
','#','','','/libp/mpanel/images/dtree/notebook.png'
,'/libp/mpanel/images/dtree/notebook.png',''
,' xajax_process_browse_url(\'?action=log_mail&click_from_menu=1\');  return false;');
d.add(420,400,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Visit log'); ?>
','#','','','/libp/mpanel/images/dtree/notebook.png'
,'/libp/mpanel/images/dtree/notebook.png',''
,' xajax_process_browse_url(\'?action=log_visit&click_from_menu=1\');  return false;');
d.add(425,400,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Log Admin'); ?>
','#','','','/libp/mpanel/images/dtree/notebook.png'
,'/libp/mpanel/images/dtree/notebook.png',''
,' xajax_process_browse_url(\'?action=log_admin&click_from_menu=1\');  return false;');
//d.add(435,400,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Rating log'); ?>
','#','','','/libp/mpanel/images/dtree/notebook.png'
//,'/libp/mpanel/images/dtree/notebook.png',''
//,' xajax_process_browse_url(\'?action=rating_log&click_from_menu=1\');  return false;');


//d.add(700,0,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Directory'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
//,'/libp/mpanel/images/dtree/contents.png');
//d.add(749,700,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Office'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
//,'/libp/mpanel/images/dtree/contents.png',''
//,' xajax_process_browse_url(\'?action=office&click_from_menu=1\');  return false;');
//d.add(750,700,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Office country'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
//,'/libp/mpanel/images/dtree/contents.png',''
//,' xajax_process_browse_url(\'?action=office_country&click_from_menu=1\');  return false;');
//d.add(751,700,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Office region'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
//,'/libp/mpanel/images/dtree/contents.png',''
//,' xajax_process_browse_url(\'?action=office_region&click_from_menu=1\');  return false;');
//d.add(752,700,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Office city'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
//,'/libp/mpanel/images/dtree/contents.png',''
//,' xajax_process_browse_url(\'?action=office_city&click_from_menu=1\');  return false;');


d.add(800,0,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Auto catalog'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
,'/libp/mpanel/images/dtree/contents.png');
d.add(801,800,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Catalog list'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
,'/libp/mpanel/images/dtree/contents.png',''
,' xajax_process_browse_url(\'?action=cat&click_from_menu=1\');  return false;');
<?php if (( $this->_tpl_vars['oLanguage']->getConstant('use_price_control',0) )): ?>
	d.add(811,800,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Parameter Parts'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
	,'/libp/mpanel/images/dtree/contents.png',''
	,' xajax_process_browse_url(\'?action=cat_part&click_from_menu=1\');  return false;');
<?php endif; ?>
d.add(815,800,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Cat pref'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
,'/libp/mpanel/images/dtree/contents.png',''
,' xajax_process_browse_url(\'?action=cat_pref&click_from_menu=1\');  return false;');
d.add(820,800,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Cat model'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
,'/libp/mpanel/images/dtree/contents.png',''
,' xajax_process_browse_url(\'?action=cat_model&click_from_menu=1\');  return false;');
d.add(821,800,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Cat model group'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
,'/libp/mpanel/images/dtree/contents.png',''
,' xajax_process_browse_url(\'?action=cat_model_group&click_from_menu=1\');  return false;');
d.add(822,800,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Price'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
,'/libp/mpanel/images/dtree/contents.png',''
,' xajax_process_browse_url(\'?action=price&click_from_menu=1\');  return false;');
d.add(830,800,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Price group'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
,'/libp/mpanel/images/dtree/contents.png',''
,' xajax_process_browse_url(\'?action=price_group&click_from_menu=1\');  return false;');
d.add(842,800,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Handbook'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
,'/libp/mpanel/images/dtree/contents.png',''
,' xajax_process_browse_url(\'?action=handbook&click_from_menu=1\');  return false;');
d.add(843,800,'<?php echo $this->_tpl_vars['oLanguage']->getDMessage('Handbook params editor'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
,'/libp/mpanel/images/dtree/contents.png',''
,' xajax_process_browse_url(\'?action=hbparams_editor&click_from_menu=1\');  return false;');
d.add(844,800,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Rubricator'); ?>
','#','','','/libp/mpanel/images/dtree/contents.png'
,'/libp/mpanel/images/dtree/contents.png',''
,' xajax_process_browse_url(\'?action=rubricator&click_from_menu=1\');  return false;');

d.add(10002,0,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Trash'); ?>
','#','','','','/libp/mpanel/images/dtree/trashcan_full.png'
,'/libp/mpanel/images/dtree/trashcan_full.png',
'xajax_process_browse_url(\'?action=trash&click_from_menu=1\'); return false;');

d.add(10003,0,'<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Logout'); ?>
','./?action=quit','','','/libp/mpanel/images/dtree/exit.gif');
document.write(d);
//-->
        </script>
<br/>

</div>

<?php echo '
<script type="text/javascript">
	$(\'.dTreeNode a\').click(function(event) {
		if ($(this).text()==\'Загрузка изображений\') {
			javascript:OpenFileBrowser(\'/libp/kcfinder/browse.php?Type=Image&Connector=php_connector/connector.php&return_id={$sType}\', 600, 400);
		}
	});
</script>
'; ?>