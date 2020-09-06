<?php /* Smarty version 2.6.18, created on 2019-10-02 12:19:06
         compiled from user/login.tpl */ ?>
<form method="post">
   <div class="at-popup-auth">
       <div class="part left">
           <div class="at-popup-login-block">
               <div class="at-block-form">
                   <table>
						<?php if ($_REQUEST['error_login']): ?>
						<tr>
							<td class="no-mob">
							</td>
                            <td class="no-mob">
								<div class="error_p"><?php echo $this->_tpl_vars['oLanguage']->GetMessage("Authorization error. Please check CapsLock, Language and try again"); ?>
</div>
							</td>
						</tr>				
						<?php endif; ?>
							<?php if ($_REQUEST['login_error']): ?>
						<tr>
						   <td class="no-mob">
						   </td>
                           <td class="no-mob">
								<div class="error_p"><?php echo $this->_tpl_vars['oLanguage']->GetMessage("Authorization type error. Please, relogin."); ?>
</div>
							</td>
						</tr>	
					 	<?php endif; ?>
                       <tr>
                           <td class="no-mob">
                               <div class="field-name">
                                   <?php echo $this->_tpl_vars['oLanguage']->getMessage('Login'); ?>
:
                                   <span class="abs"></span>
                               </div>
                           </td>
                           <td class="mob-w100">
                               <input type="text" name="login" class="" >
                           </td>
                       </tr>
                       <tr>
                           <td class="no-mob">
                               <div class="field-name"><?php echo $this->_tpl_vars['oLanguage']->getMessage('Password'); ?>
:</div>
                           </td>
                           <td class="mob-w100">
                               <div class="password">
                                   <input type="password" name="password">
                                   <a href="/pages/user_restore_password" class="forgot at-link-dashed"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('lost password'); ?>
</a>
                               </div>
                           </td>
                       </tr>
                       <tr>
                           <td class="no-mob"></td>
                           <td class="mob-w100">
                               <label class="remember-label">
                                   <input type="checkbox" class="js-checkbox" name="remember_me" > <span><?php echo $this->_tpl_vars['oLanguage']->getMessage('Remember me'); ?>
</span>
                               </label>

                               <div class="button">
                                   <input class="at-btn" type="submit" value="<?php echo $this->_tpl_vars['oLanguage']->GetMessage('enter'); ?>
">
                               </div>

                               <div class="clear"></div>
                           </td>
                       </tr>
                       <tr>
                           <td class="no-mob"></td>
                           <td class="mob-w100">
                                                      </td>
                       </tr>
                   </table>
               </div>
           </div>
       </div>

       <div class="part right">
           <div class="reg-info">
               <div class="top-part">
                   <div class="caption"><?php echo $this->_tpl_vars['oLanguage']->getMessage('Why do I need to register?'); ?>
</div>

                   <ul class="at-ul">
                       <?php echo $this->_tpl_vars['oLanguage']->GetText('Right text for registration'); ?>

                   </ul>
               </div>


               <div class="bot-part">
                   <a href="/pages/user_new_account" class="at-btn"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('register'); ?>
</a>
               </div>
           </div>
       </div>
       <div class="clear"></div>
   </div>
   <input name="action" value="user_do_login" type="hidden">
</form>