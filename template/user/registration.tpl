<h1>{$oLanguage->getMessage('Register new user')}</h1>

{$oForm}

<div class="at-reg-info">
    <div class="inner-panel">
        <div class="top-part">
            <div class="caption">{$oLanguage->getMessage('Why do I need to register?')}</div>

            <ul class="at-ul">
				{$oLanguage->GetText('Right text for registration')}
            </ul>
        </div>


        <div class="bot-part">
        {*
            <div class="caption">{$oLanguage->getMessage('Or enter through social networks')}</div>
			<script src="http://ulogin.ru/js/ulogin.js" type="text/javascript"></script>
            <div class="at-soc" id="uLogin" data-ulogin="display=buttons;{$oLanguage->GetConstant('ulogin:fields','first_name,last_name,email,nickname')};providers={$oLanguage->GetConstant('ulogin:providers','vkontakte,facebook,google')};hidden=other;redirect_uri={$sUloginURI};">
                { *<a href="#" class="vk" data-uloginbutton = "vkontakte"></a>* }
                <a href="#" class="fb" data-uloginbutton = "facebook"></a>
                <a href="#" class="gp" data-uloginbutton = "googleplus"></a>
                <div class="clear"></div>
            </div>
            *}
        </div>
    </div>
</div>
<div class="clear"></div>