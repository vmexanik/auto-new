<?php /* Smarty version 2.6.18, created on 2019-10-07 11:29:50
         compiled from contact_form/form_static.tpl */ ?>
<table>
    <tr>
        <td>
    	   <div class="field-name"><?php echo $this->_tpl_vars['oLanguage']->getMessage("Ваше имя"); ?>
: <?php echo $this->_tpl_vars['sZir']; ?>
</div>
        </td>
        <td>
            <input type="text" name="data[name]" value='<?php echo $_REQUEST['data']['name']; ?>
'>
        </td>
    </tr>
    <tr>
        <td>
    	   <div class="field-name"><?php echo $this->_tpl_vars['oLanguage']->getMessage("Ваш e-mail"); ?>
: <?php echo $this->_tpl_vars['sZir']; ?>
</div>
        </td>
        <td>
            <input type="text" name="data[email]" value='<?php echo $_REQUEST['data']['email']; ?>
'>
        </td>
    </tr>
    <tr>
        <td>
    	   <div class="field-name"><?php echo $this->_tpl_vars['oLanguage']->getMessage("Номер вашего телефона"); ?>
: <?php echo $this->_tpl_vars['sZir']; ?>
</div>
        </td>
        <td>
            <input type="text" name="data[phone]" value='<?php echo $_REQUEST['data']['phone']; ?>
' class='js-masked-input' placeholder="(___)___ __ __">
        </td>
    </tr>
    <tr>
        <td>
    	   <div class="field-name"><?php echo $this->_tpl_vars['oLanguage']->getMessage("Тема"); ?>
:</div>
        </td>
        <td>
            <input type="text" name="data[subject]" value='<?php echo $_REQUEST['data']['subject']; ?>
'>
        </td>
    </tr>
    <tr>
        <td>
    	   <div class="field-name"><?php echo $this->_tpl_vars['oLanguage']->getMessage("Ваш запрос"); ?>
: <?php echo $this->_tpl_vars['sZir']; ?>
</div>
        </td>
        <td>
            <input type="text" name="data[description]" value='<?php echo $_REQUEST['data']['description']; ?>
'>
        </td>
    </tr>
</table>

<div class="bordered">
    <table>
        <tr>
            <td>
                <div class="field-name">
                    <?php echo $this->_tpl_vars['oLanguage']->getMessage('New capcha field'); ?>
: <?php echo $this->_tpl_vars['sZir']; ?>

                </div>

            </td>
            <td>
            	<div class="g-recaptcha" data-sitekey="6LeZKLUUAAAAALX1zbIK2N7l3_ubJRlCdlUpsR0T"></div>
                
                <div class="capcha-text">Проверка от спам ботов (капча)</div>
            </td>
        </tr>
    </table>
</div>