<h1>{$oLanguage->GetMessage('Каталог оригинальных запчастей')}</h1>
{*<div class="at-user-details">
    <div class="at-tabs">
        <div class="tabs-head">
            <a href="/pages/levam_catalog" class="js-tab {if $smarty.request.type==0 || !$smarty.request.type}selected{/if}" data-tab="1" onclick="changeCatalogType(0);">
                {$oLanguage->GetMessage('Легковые')}
            </a>
            <a href="/pages/levam_catalog/?type=1" class="js-tab {if $smarty.request.type==1}selected{/if}" data-tab="2" onclick="changeCatalogType(1);">
                {$oLanguage->GetMessage('Коммерческие')}
            </a>
            <a href="/pages/levam_catalog/?type=2" class="js-tab {if $smarty.request.type==2}selected{/if}" data-tab="3" onclick="changeCatalogType(2);">
                {$oLanguage->GetMessage('Мотоциклы')}
            </a>
        </div>

        <div class="mob-tabs-select">
            <select class="js-select" onchange="document.location=this.options[this.selectedIndex].value;">
                <option value="/pages/levam_catalog/">{$oLanguage->GetMessage('Легковые')}</option>
                <option value="/pages/levam_catalog/?type=1">{$oLanguage->GetMessage('Коммерческие')}</option>
                <option value="/pages/levam_catalog/?type=2">{$oLanguage->GetMessage('Мотоциклы')}</option>
            </select>
        </div>

    </div>
</div>*}

<div class="js-form-content">
	<form method="get" name="vin_search" id="vin_search">
		<input type="hidden" name="action" value="levam_vin">  
		
		<div class="at-block-form" style="background-color: #ffffff;box-shadow: 0 0 10px #cadae2;margin: 0 0 20px 0;">
			<table>
				<tr>
					<td><b>{$oLanguage->GetMessage('Найдите модель по VIN-номеру')}</b></td>
					<td><input type="text" name="vin" value="" style="max-width: 100%;"  onkeyup="checkVin()" onkeypress='enterVin(event);'></td>
					<td><input type="submit" class="at-btn" value="Искать"></td>
				</tr>
				<tr>
					<td>{$oLanguage->GetMessage('например:')}</td>
					<td>ZFA19200000508303</td>
				</tr>
			</table>
		</div>
	</form>
</div>

{*<div class="js-form-content">
	<form method="get" name="vin_search" id="vin_search">
		<input type="hidden" name="action" value="levam_frame">  
		
		<div class="at-block-form" style="background-color: #ffffff;box-shadow: 0 0 10px #cadae2;margin: 0 0 20px 0;">
			<table>
				<tr>
					<td><b>{$oLanguage->GetMessage('Найдите модель по FRAME-номеру')}</b></td>
					<td><input type="text" name="frame" value="" style="max-width: 100%;"  onkeyup="checkFrame()" onkeypress='enterFrame(event);'></td>
					<td><input type="submit" class="at-btn" value="Искать"></td>
				</tr>
				<tr>
					<td>{$oLanguage->GetMessage('например:')}</td>
					<td>NHW20-7837381</td>
				</tr>
			</table>
		</div>
	</form>
</div>*}

<h2>{$oLanguage->GetMessage('Выберите модель по параметрам')}</h2>

<div class="js-form-content">
	<div class="at-block-form" style="background-color: #ffffff;box-shadow: 0 0 10px #cadae2;margin: 0 0 20px 0;">
		<table>
			<tr>
				<td>{$oLanguage->GetMessage('Марка')}</td>
				<td>
					<select name=data[mark] id="mark" class="searcher_select" 
						onchange="javascript:xajax_process_browse_url('?action=levam_change_mark&mark='+this.options[this.selectedIndex].value);return false;">
						{html_options options=$aBrandList}
					</select>
				</td>
			</tr>
			<tr>
				<td>{$oLanguage->GetMessage('Модель')}</td>
				<td>
					<select name=data[model] id="model" class="searcher_select"></select>
				</td>
			</tr>
			<tr>
				<td>{$oLanguage->GetMessage('Уточнение')}</td>
				<td>
					<select name=data[param] id="param" class="searcher_select"></select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>{$oLanguage->GetMessage('Доп. параметры')}</td>
			</tr>
			
			<tr>
				<td id="result" colspan="2"></td>
			</tr>
			<tr>
				<td id="button" colspan="2"></td>
			</tr>
		</table>
	</div>
</div>