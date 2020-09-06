<?php /* Smarty version 2.6.18, created on 2020-08-13 11:44:13
         compiled from index_include/body_header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'index_include/body_header.tpl', 62, false),array('modifier', 'lower', 'index_include/body_header.tpl', 328, false),)), $this); ?>
 <div class="at-hWrapper">
       <div class="wrapper-cell">
           <div class="at-mainer">
               <div class="at-pages-menu js-menu-pages">
                   <div class="inner-pages">
                       <div class="mob-header-pages">
                           <span class="close" onclick="atTopMenuClose()"></span>
                       </div>
                       <?php $_from = $this->_tpl_vars['aDropdownMenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sKey'] => $this->_tpl_vars['aItem']):
        $this->_foreach['menu']['iteration']++;
?>
					   	<a href="/pages/<?php echo $this->_tpl_vars['aItem']['code']; ?>
" ><?php echo $this->_tpl_vars['aItem']['name']; ?>
</a>
					   <?php endforeach; endif; unset($_from); ?>
                       <div class="at-docs-link">
	                       <select id="lang1" onchange="document.location=this.options[this.selectedIndex].value;"
	                       		style="-webkit-appearance: none;-moz-appearance: none;appearance: none;height: 30px;border: none;">
							   <?php if ($_REQUEST['locale'] == 'ua'): ?>
							   <option value=""><?php echo $this->_tpl_vars['oLanguage']->GetMessage('UA'); ?>
</option>
							   <option id="multilanguage_option" value="<?php echo $this->_tpl_vars['sMultiUrl']; ?>
"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('RU'); ?>
</option>
							   <?php else: ?>
							   <option value=""><?php echo $this->_tpl_vars['oLanguage']->GetMessage('RU'); ?>
</option>
							   <option id="multilanguage_option" value="<?php echo $this->_tpl_vars['sMultiUrlUa']; ?>
"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('UA'); ?>
</option>
							   <?php endif; ?>
						   </select>
                       </div>

                                          </div>
               </div>
           </div>

           <div class="at-mid-header">
               <div class="at-mainer">
                   <div class="mid-header-wrap">
                       <div class="mid-header-part logo-part">
                           <?php if ($this->_tpl_vars['oLanguage']->GetConstant('global:project_url') == 'http://auto-carta.com.ua/'): ?>
                            <div class="ar-logo">
					            <a href="/">АвтоКарта</a><br>
					            <span>Интернет-магазин автозапчастей</span>
					        </div>
					        <?php endif; ?>
                            <?php if ($this->_tpl_vars['oLanguage']->GetConstant('global:project_url') != 'http://auto-carta.com.ua/'): ?>
                             <a class="at-logo-top" href="/">
                                <img src="<?php echo $this->_tpl_vars['oLanguage']->GetConstant('logo','/image/logo-top.png'); ?>
" alt="<?php echo $this->_tpl_vars['oLanguage']->getConstant('global:title'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getConstant('global:title'); ?>
">
                             </a>
                            <?php endif; ?> 
                       </div>

                       <div class="mid-header-part phones-part<?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'manager'): ?>_not_style<?php endif; ?>">
                       <?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'manager'): ?>
                       <?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'manager' && $this->_tpl_vars['isAllowManagerChangePrice']): ?>
                       <div class="header-select">
                        	<form action="/">
	                        	<div class="user-select">
		                        	<div class="radio_user" style="padding-bottom: 4px;">
		                        		<input type="radio" name="type_price" class="js-radio" value="user" <?php if ($this->_tpl_vars['aAuthUser']['type_price'] == 'user'): ?>checked<?php endif; ?>>
		                        		<?php echo $this->_tpl_vars['oLanguage']->getMessage('user'); ?>
:<br>
		                        	</div>
		                        	<label class="user_select">
		                        		<?php echo smarty_function_html_options(array('name' => "data[id_type_price_user]",'options' => $this->_tpl_vars['aNameManager'],'selected' => $this->_tpl_vars['aAuthUser']['id_type_price_user'],'id' => 'select_name_user_id'), $this);?>

		                        	</label>
	                        	</div>
	                        	
	                        	<div class="group-select">
		                        	<div class="radio_group" style="padding-bottom: 4px;">
		                        		<input type="radio" name="type_price" class="js-radio" value="group" <?php if ($this->_tpl_vars['aAuthUser']['type_price'] == 'group' || $this->_tpl_vars['aAuthUser']['type_price'] == 'none'): ?>checked<?php endif; ?>>
		                        		<?php echo $this->_tpl_vars['oLanguage']->getMessage('group user'); ?>
:<br>
		                        	</div>
		                        	
		                        	<label class="group_select">
										<?php if ($this->_tpl_vars['aAuthUser']['id_type_price_group'] != 0): ?>
											<?php $this->assign('id_type_price_group', $this->_tpl_vars['aAuthUser']['id_type_price_group']); ?>
		                        		<?php else: ?>
		                        			<?php $this->assign('id_type_price_group', $this->_tpl_vars['oLanguage']->getConstant('IdDefaultPriceGroupManager',1)); ?>
		                        		<?php endif; ?>                        		
		                        		<?php echo smarty_function_html_options(array('name' => "data[id_type_price_group]",'id' => 'select_group_user','options' => $this->_tpl_vars['aCustomerGroup'],'selected' => $this->_tpl_vars['id_type_price_group']), $this);?>
 
		                        	</label>
	                        	</div>
	                        	<input name="action" value="user_change_level_price" type="hidden">
	                        	<input name="uri" value="<?php echo $this->_tpl_vars['sURI']; ?>
" type="hidden">
	                        	<input type="submit" value="<?php echo $this->_tpl_vars['oLanguage']->getMessage('OK'); ?>
" class="at-btn" 
	                        	style="min-width: 45px !important; height: 40px!important;line-height: 40px;">
                        	</form>
                        	<?php echo '
								<script type="text/javascript">    
								    $(document).ready(function() {
								    	 $(\'#select_name_user_id\').select2({
										    language: \'ru\',
								    		    ajax: {
								    		      url: "/?action=manager_get_user_select",
								    		      dataType: \'json\',
								    		      data: function (term, page) {
								    		        return {
								    		          data: term
								    		        };
								    		      },
								    		      processResults: function (data) {
								    		            return {
								    		                results: $.map(data, function (item) {
								    		                    return {
								    		                        text: item.name,
								    		                        id: item.id
								    		                    }
								    		                })
								    		            };
								    		        }
								    		    }
								    		  });
								    	 $(\'#select_group_user\').select2();
								    });									
							    </script>
								'; ?>

								
								<?php if ($_REQUEST['action'] != ''): ?>
									<?php echo '
									<style>
										.header-select .user-select{
											width:300px;
										}
										.header-select .group-select{
											width:300px;
										}
								    </style>
							     '; ?>

							     <?php endif; ?>
                       </div>
                       <?php endif; ?>
                       <?php else: ?>
                           <div class="at-phones-top no-mob-phones">
                               <div class="inner-wrap">
                                   <div class="main-phone" onclick="
                                   $('.at-phones-top').toggleClass('active');
                                   $('.js-phones-drop, .js-phones-drop-mask').toggle();
                                   ">
                                       <?php echo $this->_tpl_vars['oLanguage']->GetConstant('global:project_phone'); ?>

                                       <i></i>
                                   </div>
									<a href="javascript:void(0);" onclick="popupOpen('.js-popup-call-block');">
										<?php echo $this->_tpl_vars['oLanguage']->GetMessage('callme'); ?>

									</a>
                                   <div class="phones-top-drop js-phones-drop">
                                       <div class="phone mts"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('phone1'); ?>
</div>
                                       <div class="phone kiyv"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('phone2'); ?>
</div>
                                       <div class="phone life"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('phone3'); ?>
</div>
                                   </div>
                               </div>

                           </div>
                       <?php endif; ?>
                       </div>

                       <div class="mid-header-part user-part">
                           <div class="at-mob-toggle-menu">
                               <a href="javascript:void(0);" onclick="atTopMenuOpen();">
                                   <span></span>
                               </a>
                           </div>
                          <?php if ($this->_tpl_vars['aAuthUser']['type_'] != 'manager'): ?>
                           <div class="at-phones-top no-pk-phones">
	                              <div class="inner-wrap">
	                                  <div class="main-phone" onclick="
	                                  $('.at-phones-top').toggleClass('active');
	                                  $('.js-phones-drop, .js-phones-drop-mask').toggle();
	                                  ">
	                                      	                                      <i></i>
	                                  </div>
	                                  <div class="phones-top-drop js-phones-drop">
		                                     <a href="javascript:void(0);" onclick="popupOpen('.js-popup-call-block');">
												<?php echo $this->_tpl_vars['oLanguage']->GetMessage('callme'); ?>

											</a>
	                                      <div class="phone mts"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('phone1'); ?>
</div>
	                                      <div class="phone kiyv"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('phone2'); ?>
</div>
	                                      <div class="phone life"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('phone3'); ?>
</div>
	                                  </div>
	                              </div>
	                          </div>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['aAuthUser']['id'] && ! ( $this->_tpl_vars['oContent']->IsChangeableLogin($this->_tpl_vars['aAuthUser']['login']) )): ?>
                           <div class="at-auth-block loged">
								<?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'manager'): ?>	
									<div class="callback">
										<span class="count-call"><?php echo $this->_tpl_vars['aTemplateNumber']['resolved']; ?>
</span>
									</div>
									<div class="neworder">
										<span class="count-order"><?php echo $this->_tpl_vars['iNotViewedOrders']; ?>
</span>
									</div>
								<?php endif; ?>	
                               <a href="javascript:void(0);" onclick="atCabinetMenuOpen();" >
                                   <span><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Личный кабинет'); ?>
</span>
                               </a>

                               <div class="auth-menu js-auth-menu">
                                   <div class="menu-header">
                                       <?php echo $this->_tpl_vars['oLanguage']->GetMessage('Личный кабинет'); ?>


                                       <a class="close" href="javascript:void(0);" onclick="atCabinetMenuClose();"></a>
                                   </div>
                                   <table class="user-name">
                                       <tr>
                                           <td>
                                               <a href="/pages/<?php echo $this->_tpl_vars['aAuthUser']['type_']; ?>
_profile"><strong><?php echo $this->_tpl_vars['aAuthUser']['login']; ?>
</strong></a>
                                           </td>
                                       </tr>
                                   </table>
                                   <ul class="list">
				                       <?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'manager'): ?>
								  		 <li>
								  		 	<a href="/mpanel" target="_blank">Перeйти в MPanel</a>
								   	  	 </li>
								   	  <?php endif; ?>
				                       <?php $_from = $this->_tpl_vars['aAccountMenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
				                       <li>
                                           <a href="/pages/<?php if (! $this->_tpl_vars['aItem']['link']): ?><?php echo $this->_tpl_vars['aItem']['code']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aItem']['code']; ?>
<?php endif; ?>">
                                           <?php echo $this->_tpl_vars['aItem']['name']; ?>

											<?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'manager'): ?>
												<?php if ($this->_tpl_vars['aItem']['code'] == 'message'): ?><?php if ($this->_tpl_vars['aTemplateNumber']['message_number']): ?> <span class="count"><?php echo $this->_tpl_vars['aTemplateNumber']['message_number']; ?>
</span><?php endif; ?><?php endif; ?>
												<?php if ($this->_tpl_vars['aItem']['code'] == 'payment_report_manager'): ?><?php if ($this->_tpl_vars['aTemplateNumber']['payment_report_id']): ?> <span class="count"><?php echo $this->_tpl_vars['aTemplateNumber']['payment_report_id']; ?>
</span><?php endif; ?><?php endif; ?>
												<?php if ($this->_tpl_vars['aItem']['code'] == 'vin_request_manager'): ?><?php if ($this->_tpl_vars['iNotViewedVins']): ?> <span class="count"><?php echo $this->_tpl_vars['iNotViewedVins']; ?>
</span><?php endif; ?><?php endif; ?>
												<?php if ($this->_tpl_vars['aItem']['code'] == 'manager_package_list'): ?><?php if ($this->_tpl_vars['iNotViewedOrders']): ?> <span class="count"><?php echo $this->_tpl_vars['iNotViewedOrders']; ?>
</span><?php endif; ?><?php endif; ?>
												<?php if ($this->_tpl_vars['aItem']['code'] == 'call_me_show_manager'): ?><?php if ($this->_tpl_vars['aTemplateNumber']['resolved']): ?> <span class="count"><?php echo $this->_tpl_vars['aTemplateNumber']['resolved']; ?>
</span><?php endif; ?><?php endif; ?>
											<?php endif; ?>
											<?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'customer'): ?>
												<?php if ($this->_tpl_vars['aItem']['code'] == 'payment_declaration'): ?><?php if ($this->_tpl_vars['aTemplateNumber']['payment_declaration_id']): ?> <span class="count"><?php echo $this->_tpl_vars['aTemplateNumber']['payment_declaration_id']; ?>
</span><?php endif; ?><?php endif; ?>
												<?php if ($this->_tpl_vars['aItem']['code'] == 'message_change_current_folder'): ?><?php if ($this->_tpl_vars['aTemplateNumber']['message_number']): ?> <span class="count"><?php echo $this->_tpl_vars['aTemplateNumber']['message_number']; ?>
</span><?php endif; ?><?php endif; ?>
											<?php endif; ?>
											</a>
										<?php endforeach; endif; unset($_from); ?>
                                       <li class="logout">
                                           <a href="/pages/user_logout"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Выход'); ?>
</a>
                                       </li>
                                   </ul>

                                   <div class="manager">
                                       <a href="/?action=message_compose&compose_to=<?php echo $this->_tpl_vars['aAuthUser']['manager_login']; ?>
"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Your personal manager'); ?>
</a>
                                   </div>
                               </div>
                           </div>
							<?php else: ?>
							<div class="at-auth-block" >
                               <a href="javascript:void(0);" onclick="popupOpen('.js-popup-auth-block');"><span><?php echo $this->_tpl_vars['oLanguage']->GetMessage('enter'); ?>
</span></a>
                           </div>
                           <?php endif; ?>
                           <div class="at-basket-widget" id='cart_block'>
                               <a href="javascript:void(0);" onclick="xajax_process_browse_url('/?action=cart_show_popup_cart'); return false;">
                                   <span class="count <?php if ($this->_tpl_vars['aTemplateNumber']['cart_number'] <= 0): ?> empty<?php endif; ?>" id="icart_id"><?php echo $this->_tpl_vars['aTemplateNumber']['cart_number']; ?>
</span>
                                   <span class="name">Корзина</span>
                               </a>
                           </div>
                           <div class="clear"></div>
                       </div>
                   </div>
				<?php if ($this->_tpl_vars['oLanguage']->GetConstant('global:additional_baner')): ?>
					<div class="at-index-banner-wrap">
						<?php echo $this->_tpl_vars['oLanguage']->GetText('additional_baner'); ?>

					</div>
				<?php endif; ?>
               </div>
           </div>

           <div class="clear"></div>
           
           
           <div class="at-post-header no-mob-phones">
               <div class="at-mainer">
                   
                   <div class="post-header">
                      
                       <div class="post-header-part part-button">
                           <a class="at-top-button" href="/pages/vin_request_add"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('VIN request'); ?>
</a>
                       </div>
                       <div class="post-header-part part-search" >
                           <div class="at-block-search">
                           <form class="at-search-from" action="/" onsubmit="document.location='/price/'+$('#js-search-code').val(); return false;">
                               <input name="code" type="text" id="js-search-code" placeholder="Введите номер запчасти" onclick="if (this.value=='<?php echo $this->_tpl_vars['oLanguage']->GetMessage('default_code'); ?>
') this.value=''"
					value="<?php if ($_GET['code']): ?><?php echo $_GET['code']; ?>
<?php else: ?><?php echo $this->_tpl_vars['oLanguage']->GetMessage('default_code'); ?>
<?php endif; ?>">
                               <input type="submit" value="Найти" style="background-image:none;width:60px;" onclick="document.location='/price/'+$('#js-search-code').val(); return false;">
							   <input name="action" value="catalog_price_view" type="hidden"/>
                               <i class="close" onclick="$('.js-block-search').hide();"></i>
                               </form>
                           </div>
                       </div>

                       <div class="post-header-part part-button">
                           <a class="at-top-button" href="/pages/price_search_log"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('You searched'); ?>
</a>
                       </div>
						
						<div class="post-header-part part-button button-document">
						<?php if ($this->_tpl_vars['oLanguage']->GetConstant('global:project_url') == 'http://irbis.mstarproject.com'): ?>
							<?php if ($this->_tpl_vars['sLocale'] == 'en'): ?>
							<?php $this->assign('sHref', 'http://manual.mstarproject.com/index.php/Standard_manual_-_English_Version'); ?>
							<?php else: ?>
							<?php $this->assign('sHref', 'http://manual.mstarproject.com/index.php/Демо_сайт_автозапчастей_редизайн_-_Пакет_Стандарт'); ?>
							<?php endif; ?>
							<a href="<?php echo $this->_tpl_vars['sHref']; ?>
" target="_blank" class="at-top-button"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Read documentation'); ?>
</a>
						<?php endif; ?>
						</div>
                   </div>
               </div>
           </div>
           
           <div class="at-post-header no-mob-phones"  style="background-color: #4394cc;" >
               <div class="at-mainer">
                   <div class="post-header">
                       
                       <div class="post-header-part part-nav">
                                                   <nav class="mi-nav">
                                <a href="javascript:void(0);" class="mi-mob-nav-toggle js-mob-nav-toggle">
                                    <span>Каталог товаров</span>
                                </a>

                                <div class="inner-nav">
                                    <?php $_from = $this->_tpl_vars['aGroups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aBaseGroup']):
?>
                                            <?php if ($this->_tpl_vars['aBaseGroup']['is_rubricator']): ?>
                                    <div class="nav-element">
                                        <div class="inner-element">
                                            <a href="/rubricator/<?php echo $this->_tpl_vars['aBaseGroup']['url']; ?>
">
                                                <span><?php echo $this->_tpl_vars['aBaseGroup']['name']; ?>
</span>
                                            </a>
                                        </div>
                                          <div class="nav-drop">
                                            <ul>
                                           <?php $_from = $this->_tpl_vars['aBaseGroup']['childs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
                                                <li>
                                                       <div class="name">
                                                           <a href="/rubricator/<?php echo smarty_modifier_lower($this->_tpl_vars['aItem']['url']); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>"><?php echo $this->_tpl_vars['aItem']['name']; ?>
</a>
                                                       </div>
                                                      <?php $_from = $this->_tpl_vars['aItem']['childs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItemChild']):
?>
                                                        <div class="sub-item">
                                                           <a href="/rubricator/<?php echo smarty_modifier_lower($this->_tpl_vars['aItemChild']['url']); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>"><?php echo $this->_tpl_vars['aItemChild']['name']; ?>
</a>
                                                       </div>
                                                      <?php endforeach; endif; unset($_from); ?>
                                                </li>
                                                <?php endforeach; endif; unset($_from); ?> 
                                            </ul>
                                            
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="nav-element">
                                        <div class="inner-element">
                                            <a href="/select/<?php echo $this->_tpl_vars['aBaseGroup']['code_name']; ?>
">
                                                <span><?php echo $this->_tpl_vars['aBaseGroup']['name']; ?>
</span>
                                            </a>
                                        </div>
                                          <div class="nav-drop">
                                            <ul>
                                            <?php $_from = $this->_tpl_vars['aBaseGroup']['childs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
                                                <li>
                                                    <div class="sub-goup">
	                                                        <div class="name">
	                                                            <a href="/select/<?php echo smarty_modifier_lower($this->_tpl_vars['aItem']['code_name']); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>"><?php echo $this->_tpl_vars['aItem']['name']; ?>
</a>
	                                                        </div>
                                                        <?php $_from = $this->_tpl_vars['aItem']['childs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItemChild']):
?>
	                                                         <div class="sub-item">
	                                                            <a href="/select/<?php echo smarty_modifier_lower($this->_tpl_vars['aItemChild']['code_name']); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>"><?php echo $this->_tpl_vars['aItemChild']['name']; ?>
</a>
	                                                        </div>
                                                        <?php endforeach; endif; unset($_from); ?>
                                                    </div>
                                                </li>
                                                <?php endforeach; endif; unset($_from); ?> 
                                            </ul>
                                        </div>
                                    </div>
                                  		<?php endif; ?>  
                                    <?php endforeach; endif; unset($_from); ?>
                                </div>
                            </nav>
                        </div>
                   </div>
               </div>
           </div>
           
                     <!-- PANEL FOR MOBILE -->
              <div class="at-post-header no-pk-phones" >
               <div class="at-mainer">
                   <div class="post-header">
                       

                           <div class="at-block-search " style="display:block;position: inherit;">
                           <form class="at-search-from" action="/" onsubmit="document.location='/price/'+$('#js-search-code').val(); return false;">
                               <input name="code" type="text" id="js-search-code-mobile" placeholder="Введите номер запчасти" onclick="if (this.value=='<?php echo $this->_tpl_vars['oLanguage']->GetMessage('default_code'); ?>
') this.value=''"
					value="<?php if ($_GET['code']): ?><?php echo $_GET['code']; ?>
<?php endif; ?>">
                               <input type="submit" value="" onclick="document.location='/price/'+$('#js-search-code-mobile').val(); return false;">
							   <input name="action" value="catalog_price_view" type="hidden"/>
                                                              </form>
                           </div>
                       
                            <div class="at-nav">
                                <a class="mob-toggle" href="javascript:void(0);" onclick="
                                    $('.at-nav .nav-drop').show();
                                    $('body').addClass('overscroll-stop');
                                ">
                                    <strong><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Catalog'); ?>
<i></i></strong>
                                </a>
                                <a href="javascript:void(0);"><strong><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Catalog'); ?>
<i></i></strong></a>

                                <div class="nav-drop">
                                    <div class="mob-head">
                                        <span class="close" onclick="
                                            $('.at-nav .nav-drop').hide();
                                    $('body').removeClass('overscroll-stop');
                                        "></span>
                                    </div>
                                    <div class="nav-drop-inner">
                                    
                                        <ul class="lvl1">
                                            
                                            <?php $_from = $this->_tpl_vars['aGroups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aBaseGroup']):
?>
                                            <?php if ($this->_tpl_vars['aBaseGroup']['is_rubricator']): ?>
                                            <li class="js-has-lvl2">
                                                <a href="/rubricator/<?php echo $this->_tpl_vars['aBaseGroup']['url']; ?>
"><?php echo $this->_tpl_vars['aBaseGroup']['name']; ?>
</a>
                                                <ul class="lvl2">
                                                    <?php $_from = $this->_tpl_vars['aBaseGroup']['childs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
                                                    <?php if ($this->_tpl_vars['aItem']['childs']): ?>
                                                    <li class="js-has-lvl3">
                                                        <a href="javascript:void(0);"><?php echo $this->_tpl_vars['aItem']['name']; ?>
</a>

                                                        <ul class="lvl3">
                                                        <?php $_from = $this->_tpl_vars['aItem']['childs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItemChild']):
?>
                                                            <li><a href="/rubricator/<?php echo smarty_modifier_lower($this->_tpl_vars['aItemChild']['url']); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>"><?php echo $this->_tpl_vars['aItemChild']['name']; ?>
</a></li>
                                                        <?php endforeach; endif; unset($_from); ?>
                                                        </ul>
                                                    </li>
                                                    <?php else: ?>
                                                    <li>
                                                        <a href="/rubricator/<?php echo smarty_modifier_lower($this->_tpl_vars['aItem']['url']); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>"><?php echo $this->_tpl_vars['aItem']['name']; ?>
</a>
                                                    </li>
                                                    <?php endif; ?>
                                                    <?php endforeach; endif; unset($_from); ?>
                                                </ul>
                                            </li>
                                            <?php else: ?>
                                            <li class="js-has-lvl2">
                                                <a href="/select/<?php echo $this->_tpl_vars['aBaseGroup']['code_name']; ?>
"><?php echo $this->_tpl_vars['aBaseGroup']['name']; ?>
</a>
                                                <ul class="lvl2">
                                                <?php $_from = $this->_tpl_vars['aBaseGroup']['childs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
                                                <?php if ($this->_tpl_vars['aItem']['is_menu']): ?>
                                                    <?php if ($this->_tpl_vars['aItem']['childs']): ?>
                                                    <li class="js-has-lvl3">
                                                        <a href="/select/<?php echo smarty_modifier_lower($this->_tpl_vars['aItem']['code_name']); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>"><?php echo $this->_tpl_vars['aItem']['name']; ?>
</a>

                                                        <ul class="lvl3">
                                                        <?php $_from = $this->_tpl_vars['aItem']['childs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItemChild']):
?>
	                                                    <?php if ($this->_tpl_vars['aItemChild']['is_menu']): ?>
                                                            <li><a href="/select/<?php echo smarty_modifier_lower($this->_tpl_vars['aItemChild']['code_name']); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>"><?php echo $this->_tpl_vars['aItemChild']['name']; ?>
</a></li>
                                                        <?php endif; ?>
	                                                    <?php endforeach; endif; unset($_from); ?>
                                                        </ul>
                                                    </li>
                                                    <?php else: ?>
                                                    <li>
                                                        <a href="/select/<?php echo smarty_modifier_lower($this->_tpl_vars['aItem']['code_name']); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>"><?php echo $this->_tpl_vars['aItem']['name']; ?>
</a>
                                                    </li>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php endforeach; endif; unset($_from); ?>
                                                </ul>
                                            </li>
                                            <?php endif; ?>
                                            <?php endforeach; endif; unset($_from); ?>
                                            
                                        </ul>
                                        
                                    </div>
                            </div>
                        </div>
                       


                                          </div>
               </div>
           </div>
           
           <!-- PANEL FOR MOBILE END-->
           
           
       </div>
   </div>