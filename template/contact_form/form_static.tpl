<table>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Ваше имя")}: {$sZir}</div>
        </td>
        <td>
            <input type="text" name="data[name]" value='{$smarty.request.data.name}'>
        </td>
    </tr>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Ваш e-mail")}: {$sZir}</div>
        </td>
        <td>
            <input type="text" name="data[email]" value='{$smarty.request.data.email}'>
        </td>
    </tr>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Номер вашего телефона")}: {$sZir}</div>
        </td>
        <td>
            <input type="text" name="data[phone]" value='{$smarty.request.data.phone}' class='js-masked-input' placeholder="(___)___ __ __">
        </td>
    </tr>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Тема")}:</div>
        </td>
        <td>
            <input type="text" name="data[subject]" value='{$smarty.request.data.subject}'>
        </td>
    </tr>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Ваш запрос")}: {$sZir}</div>
        </td>
        <td>
            <input type="text" name="data[description]" value='{$smarty.request.data.description}'>
        </td>
    </tr>
</table>

<div class="bordered">
    <table>
        <tr>
            <td>
                <div class="field-name">
                    {$oLanguage->getMessage("New capcha field")}: {$sZir}
                </div>

            </td>
            <td>
            	<div class="g-recaptcha" data-sitekey="{$oLanguage->getConstant('captcha:public_key','6LdJj9UUAAAAAB2vqztFdLAFTC9UXHquxRVKP4Vm')}"></div>
                {*$sCapcha*}

                <div class="capcha-text">Проверка от спам ботов (капча)</div>
            </td>
        </tr>
    </table>
</div>