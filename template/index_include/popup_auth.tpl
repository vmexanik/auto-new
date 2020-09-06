<div class="at-block-popup js-popup-auth-block" style="display: none;">
   <div class="dark" onclick="popupClose('.js-popup-auth-block');"></div>
   <div class="block-popup">
       <div class="popup-head">
           <a href="javascript: void(0);" class="close" onclick="popupClose('.js-popup-auth-block');">&nbsp;</a>
           Авторизация
       </div>

       <div class="popup-body">
           <form method="post">
               <div class="at-popup-auth">
                   <div class="part left">
                       <div class="at-popup-login-block">
                           <div class="at-block-form">
                               <table>
                                   <tr>
                                       <td class="no-mob">
                                           <div class="field-name">
                                               {$oLanguage->getMessage("Login")}:
                                               <span class="abs">{*({$oLanguage->getMessage("используется в качестве логина")})*}</span>
                                           </div>
                                       </td>
                                       <td class="mob-w100">
                                           <input type="text" name="login" class="" {*placeholder="(___) ___ __ __"*}>
                                       </td>
                                   </tr>
                                   <tr>
                                       <td class="no-mob">
                                           <div class="field-name">{$oLanguage->getMessage("Password")}:</div>
                                       </td>
                                       <td class="mob-w100">
                                           <div class="password">
                                               <input type="password" name="password">
                                               <a href="/pages/user_restore_password" class="forgot at-link-dashed">{$oLanguage->GetMessage('lost password')}</a>
                                           </div>
                                       </td>
                                   </tr>
                                   <tr>
                                       <td class="no-mob"></td>
                                       <td class="mob-w100">
                                           <label class="remember-label">
                                               <input type="checkbox" class="js-checkbox" name="remember_me" > <span>{$oLanguage->getMessage("Remember me")}</span>
                                           </label>

                                           <div class="button">
                                               <input class="at-btn" type="submit" value="{$oLanguage->GetMessage('enter')}">
                                           </div>

                                           <div class="clear"></div>
                                       </td>
                                   </tr>
                                   <tr>
                                       <td class="no-mob"></td>
                                       <td class="mob-w100">
                                        {*
                                           <div class="soc-login">
                                               <div class="caption">{$oLanguage->getMessage('Or enter through social networks')}</div>
											   <script src="http://ulogin.ru/js/ulogin.js" type="text/javascript"></script>
                                               <div class="at-soc" id="uLogin" 
                                               data-ulogin="display=buttons;{$oLanguage->GetConstant('ulogin:fields','first_name,last_name,email,nickname')};providers={$oLanguage->GetConstant('ulogin:providers','vkontakte,facebook,google')};hidden=other;redirect_uri={$sUloginURI};">
                                                   { *<a href="#" class="vk"></a>* }
                                                   <a href="#" class="fb" data-uloginbutton = "facebook"></a>
                                                   <a href="#" class="gp" data-uloginbutton = "googleplus"></a>
                                                   <div class="clear"></div>
                                               </div>
                                           </div>
                                           *}
                                       </td>
                                   </tr>
                               </table>
                           </div>
                       </div>
                   </div>

                   <div class="part right">
                       <div class="reg-info">
                           <div class="top-part">
                               <div class="caption">{$oLanguage->getMessage('Why do I need to register?')}</div>

                               <ul class="at-ul">
									{$oLanguage->GetText('Right text for registration')}	
                               </ul>
                           </div>


                           <div class="bot-part">
	                           <a href="/pages/user_new_account" class="at-btn">{$oLanguage->GetMessage('register')}</a>
                           </div>
                       </div>
                   </div>
                   <div class="clear"></div>
               </div>
               <input name="action" value="user_do_login" type="hidden">
           </form>
       </div>
   </div>
</div>
