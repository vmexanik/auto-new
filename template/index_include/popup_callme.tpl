<div class="at-block-popup js-popup-call-block " style="display: none;">
   <div class="dark" onclick="popupClose('.js-popup-call-block');"></div>
   <div class="block-popup block-popup-call-me">
       <div class="popup-head">
           <a href="javascript: void(0);" class="close" onclick="popupClose('.js-popup-call-block');">&nbsp;</a>
           {$oLanguage->getMessage("Обратный звонок")}:
       </div>

       <div class="popup-body">
			<form method="POST">
			<strong>{$oLanguage->getMessage("your name")}:</strong><br>
			<input type="text" name="name" value="" class="popup-input" required=""><br><br>
			<strong>{$oLanguage->getMessage("your phone")}:</strong><br>
			<input type="text" name="phone" value="" class="js-masked-input" id="user_phone" placeholder="(___)___ __ __" required=""><br><br>
			<div class="g-recaptcha" data-sitekey="{$oLanguage->getConstant('captcha:public_key','6LdJj9UUAAAAAB2vqztFdLAFTC9UXHquxRVKP4Vm')}"></div><br>
			<input type="submit" value="{$oLanguage->getMessage("Send")}" class="at-btn" style="width:100%"><input type="hidden" name="action" value="call_me">
			</form>
       </div>
   </div>
</div>
