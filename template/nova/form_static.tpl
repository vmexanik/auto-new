<div id="container">
    <h1>
        Оставить отзыв о сайте suvauto.com.ua
    </h1>
    
    <div class="tabs">
        <input id="tab1" type="radio" name="tabs" checked>
        <div class="green_div"><label class="green_a" for="tab1" title="Благодарности">Благодарности</label></div>
    
        <input id="tab2" type="radio" name="tabs">
        <div class="yellow_div"><label class="yellow_a" for="tab2" title="Предложения">Предложения</label></div>
    
        <input id="tab3" type="radio" name="tabs">
        <div class="red_div"> <label class="red_a" for="tab3" title="Жалобы">Жалобы</label></div>
        <div class="svetofor"><img  style="width: 310px;" src="/image/svetofor.png"/></div>
        <div class="svetofor_colors">
	 	<div class="svetofor_red"> </div>
	  	<div class="svetofor_yellow"></div>
	  	<div class="svetofor_green"></div>
    </div>
<section id="content1">
<form method="post">
<table style="padding: 15px;">
    <tr>
        <td><span class='span_css'>{$oLanguage->getMessage("Имя")}</span></td>
        <td><div class="box"><input name=data[name] type="text" class="css-input"/></div></td>
    </tr>
    <tr>
        <td><span class='span_css'>{$oLanguage->getMessage("E-mail")}</span></td>
        <td><input type="text" name=data[email] class="css-input"></td>
    </tr>
    <tr>
        <td><span class='span_css'>{$oLanguage->getMessage("Телефон")}</span></td>
        <td><input type="text" name=data[phone] class="css-input"></td>
    </tr>
    <tr>
        <td><span class='span_css'>{$oLanguage->getMessage("Комментарий")}</span></td>
        <td><textarea class="css-input" name=data[comment] style="height: 200px;"> </textarea></td>
    </tr>
        <td></td>
        <td colspam=2>{$sCapcha}</td>
    </tr>
</table>
	<div style="text-align: center;">
	<input type="text" name=data[type] style="display:none" value="1">
	<input type="text" name="is_post" style="display:none" value="1">
		<button type="submit" name="submitMessage" id="submitMessage" class="button_green">
				{$oLanguage->getMessage("Send green")}
		</button>
	</div>   
	</form>       
       </section>  
       
       
       <section id="content2">
<form method="post">
<table style="padding: 15px;">
    <tr>
        <td><span class='span_css'>{$oLanguage->getMessage("Имя")}</span></td>
        <td><div class="box"><input name=data[name] type="text" class="css-input"/></div></td>
    </tr>
    <tr>
        <td><span class='span_css'>{$oLanguage->getMessage("E-mail")}</span></td>
        <td><input type="text" name=data[email] class="css-input"></td>
    </tr>
    <tr>
        <td><span class='span_css'>{$oLanguage->getMessage("Телефон")}</span></td>
        <td><input type="text" name=data[phone] class="css-input"></td>
    </tr>
    <tr>
        <td><span class='span_css'>{$oLanguage->getMessage("Комментарий")}</span></td>
        <td><textarea class="css-input" name=data[comment] style="height: 200px;"> </textarea></td>
    </tr>
        <td></td>
        <td colspam=2>{$sCapcha}</td>
    </tr>
</table>
	<div style="text-align: center;">
	<input type="text" name=data[type] style="display:none" value="2">
	<input type="text" name="is_post" style="display:none" value="1">
		<button type="submit" name="submitMessage" id="submitMessage" class="button_yellow">
				{$oLanguage->getMessage("Send yellow")}
		</button>
	</div>   
	</form>    
        </section> 
        
        
        <section id="content3">
<form method="post">
<table style="padding: 15px;">
    <tr>
        <td><span class='span_css'>{$oLanguage->getMessage("Имя")}</span></td>
        <td><div class="box"><input name=data[name] type="text" class="css-input"/></div></td>
    </tr>
    <tr>
        <td><span class='span_css'>{$oLanguage->getMessage("E-mail")}</span></td>
        <td><input type="text" name=data[email] class="css-input"></td>
    </tr>
    <tr>
        <td><span class='span_css'>{$oLanguage->getMessage("Телефон")}</span></td>
        <td><input type="text" name=data[phone] class="css-input"></td>
    </tr>
    <tr>
        <td><span class='span_css'>{$oLanguage->getMessage("Комментарий")}</span></td>
        <td><textarea class="css-input" name=data[comment] style="height: 200px;"> </textarea></td>
    </tr>
    <tr>
    <td></td>
        <td colspam=2>{$sCapcha}</td>
    </tr>
</table>
	<div style="text-align: center;">
	<input type="text" name=data[type] style="display:none" value="3">
	<input type="text" name="is_post" style="display:none" value="1">
		<button type="submit" name="submitMessage" id="submitMessage" class="button_red">
				{$oLanguage->getMessage("Send red")}
		</button>
	</div>   
	</form>     
        </section>
        {if $aAuthUser.type_=='manager'} 
           <section id="content4">
   {$aFeedbacks_green}
   </section>
      <section id="content5">
   {$aFeedbacks_yellow}
   </section>
      <section id="content6">
   {$aFeedbacks_red}
   </section>   
   {/if}
   </div>

</div>
