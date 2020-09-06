{assign var="print_language_prefix" value=$oLanguage->GetConstant('global:print_language_prefix','ua')}
<div style="margin-left: 35.4pt; text-indent: 27pt;"><b>
{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Заява про переказ готівки"}
{$oLanguage->GetMessage($variable_lang)} №___________</b></div>
<div style="margin-left: 35.4pt;"><span style="font-size: 10pt;">
{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Дата здійснення операції"}
{$oLanguage->GetMessage($variable_lang)}</span></div>
<div style="margin-left: 35.4pt;"><span style="font-size: 10pt;">
{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Дата валютування"}
{$oLanguage->GetMessage($variable_lang)}</span></div>
<table width="662" cellspacing="0" cellpadding="0" border="0" style="margin-left: -0.6pt; border-collapse: collapse;">
    <tbody>
        <tr style="height: 19.15pt;">
            <td width="121" valign="top" colspan="2" style="border: 1pt solid windowtext; padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 19.15pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Назва"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::валюти"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="60" valign="top" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 19.15pt;">
            <div>&nbsp;</div>
            </td>
            <td width="156" valign="top" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 19.15pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::№ рахунку"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="144" valign="top" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 19.15pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Сума"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="181" valign="top" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 19.15pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Еквівалент у гривнях"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="121" valign="bottom" nowrap="nowrap" colspan="2" style="border-style: none solid solid; border-color: -moz-use-text-color windowtext windowtext; border-width: medium 1pt 1pt; padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.6pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::UAH"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="60" valign="bottom" nowrap="nowrap" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 9.6pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Дебет"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.6pt;">
            <div><b><i>&nbsp;</i></b></div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="121" valign="bottom" nowrap="nowrap" colspan="2" style="border-style: none solid; border-color: -moz-use-text-color windowtext; border-width: medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.6pt;">
            <div><span style="font-size: 10pt;">UAH</span></div>
            </td>
            <td width="60" valign="bottom" nowrap="nowrap" style="border-style: none solid none none; border-color: -moz-use-text-color windowtext -moz-use-text-color -moz-use-text-color; border-width: medium 1pt medium medium; padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 9.6pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Кредит"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none solid none none; border-color: -moz-use-text-color windowtext -moz-use-text-color -moz-use-text-color; border-width: medium 1pt medium medium; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.6pt;">
            <div><strong>{$aActiveAccount.account_id}</strong></div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none solid none none; border-color: -moz-use-text-color windowtext -moz-use-text-color -moz-use-text-color; border-width: medium 1pt medium medium; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.6pt;">
            <div>&nbsp;<b style=""><span style="font-family: Arial;"> </span></b><b style=""><span style="font-family: Arial;">
            {$aBill.amount} {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::грн"}</span></b></div>
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="border-style: none solid none none; border-color: -moz-use-text-color windowtext -moz-use-text-color -moz-use-text-color; border-width: medium 1pt medium medium; padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 9.6pt;">
            <div>&nbsp;<b style=""><span style="font-family: Arial;"> </span></b><b style=""><span style="font-family: Arial;">
            {$aBill.amount} {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::грн"}</span></b></div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="3" style="border-style: solid none solid solid; border-color: windowtext -moz-use-text-color windowtext windowtext; border-width: 1pt medium 1pt 1pt; padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 9.6pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Загальна сума (цифрами)"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: solid none; border-color: windowtext -moz-use-text-color; border-width: 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border: 1pt solid windowtext; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.6pt;">
            <div align="right">&nbsp;</div>
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="121" valign="bottom" nowrap="nowrap" colspan="2" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.6pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Платник"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="541" valign="bottom" nowrap="nowrap" colspan="4" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 405.65pt; height: 9.6pt;">
            <div><b><i><span style="font-size: 10pt;"><br />
            </span></i></b></div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="121" valign="bottom" nowrap="nowrap" colspan="2" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.6pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код платника"}
			{$oLanguage->GetMessage($variable_lang)}**</span></div>
            </td>
            <td width="60" valign="bottom" nowrap="nowrap" style="border-style: none none solid solid; border-color: -moz-use-text-color -moz-use-text-color windowtext windowtext; border-width: medium medium 1pt 1pt; padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="121" valign="bottom" nowrap="nowrap" colspan="2" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.6pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Банк платника"}
			{$oLanguage->GetMessage($variable_lang)}*</span></div>
            </td>
            <td width="541" valign="bottom" nowrap="nowrap" colspan="4" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 405.65pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="121" valign="bottom" nowrap="nowrap" colspan="2" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.6pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Отримувач"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="541" valign="bottom" nowrap="nowrap" colspan="4" style="padding: 0.75pt 0.75pt 0cm; width: 405.65pt; height: 9.6pt;">
            <div><b style="">{$aActiveAccount.name}</b></div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="121" valign="bottom" nowrap="nowrap" colspan="2" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.6pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код отримувача"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="216" valign="bottom" nowrap="nowrap" colspan="2" style="border: 1pt solid windowtext; padding: 0.75pt 0.75pt 0cm; width: 162pt; height: 9.6pt;">
            <div align="left">&nbsp;</div>
            <strong>{$aActiveAccount.holder_code}</strong></td>
            <td width="325" valign="bottom" nowrap="nowrap" colspan="2" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 243.65pt; height: 9.6pt;">
            <div align="center">&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="121" valign="bottom" nowrap="nowrap" colspan="2" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.6pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Банк отримувача"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="216" valign="bottom" nowrap="nowrap" colspan="3" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 162pt; height: 9.6pt;">
            {$aActiveAccount.bank_name}
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="3" style="padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 9.6pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код банку отримувача"}
			{$oLanguage->GetMessage($variable_lang)}*</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.6pt;"><b style=""><span lang="UK" style="font-family: Arial;">
            {$aActiveAccount.bank_mfo}
            </span></b></td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="121" valign="bottom" nowrap="nowrap" colspan="2" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.6pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Загальна сума"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="541" valign="bottom" nowrap="nowrap" colspan="4" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 405.65pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="662" valign="bottom" nowrap="nowrap" colspan="6" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 496.4pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 10.75pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="3" style="padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 10.75pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Призначення платежу"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="481" valign="bottom" nowrap="nowrap" colspan="3" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 360.65pt; height: 10.75pt;"><b><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::поповнення рахунку на сайті (логін"}
            {assign var="variable_lang2" value=$print_language_prefix|cat:"_doc_print::без ПДВ"}
			{$oLanguage->GetMessage($variable_lang)}:{$aUser.login};), {$oLanguage->getMessage($variable_lang2)}</span></b></td>
        </tr>
        <tr style="height: 10.05pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="3" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 10.05pt;">
            <div>&nbsp;</div>
            </td>
            <td width="481" valign="bottom" nowrap="nowrap" colspan="3" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 360.65pt; height: 10.05pt;">
            <div><b><span style="font-size: 10pt;"><br />
            </span></b></div>
            </td>
        </tr>
        <tr style="height: 10.05pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="3" style="padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 10.05pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Пред`явлений документ"}
			{$oLanguage->GetMessage($variable_lang)}**</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 10.05pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 10.05pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::серія"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 10.05pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="3" style="padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 9.6pt;">
            <div align="center"><span style="font-size: 10pt;">№</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.6pt;">
            <div align="right"><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::виданий"}
			{$oLanguage->GetMessage($variable_lang)}:</span></div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.6pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="3" style="padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 9.6pt;">
            <div align="center"><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::дата видачі"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.6pt;">
            <div align="right"><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::дата народження"}
			{$oLanguage->GetMessage($variable_lang)}:</span></div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 9.6pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 10.05pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="3" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 10.05pt;">
            <div align="center"><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::місце проживання особи"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 10.05pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 10.05pt;">
            <div>&nbsp;</div>
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 10.05pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 10.05pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="3" style="padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 10.05pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Додаткові реквізити"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 10.05pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 10.05pt;">
            <div>&nbsp;</div>
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 10.05pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 3.3pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="3" style="padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 3.3pt;">
            <div>&nbsp;</div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 3.3pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 3.3pt;">
            <div>&nbsp;</div>
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 3.3pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 6.15pt;">
            <td width="109" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 81.75pt; height: 6.15pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Підпис платника"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="72" valign="bottom" colspan="2" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 54pt; height: 6.15pt;">
            <div>&nbsp;</div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 6.15pt;">
            <div align="right"><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Підпис банку"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 6.15pt;">
            <div>&nbsp;</div>
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 6.15pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 3.3pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="3" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 3.3pt;">
            <div>&nbsp;</div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 3.3pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 3.3pt;">
            <div>&nbsp;</div>
            </td>
            <td width="181" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 135.65pt; height: 3.3pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr height="0">
            <td width="109" style="border: medium none ;">&nbsp;</td>
            <td width="12" style="border: medium none ;">&nbsp;</td>
            <td width="60" style="border: medium none ;">&nbsp;</td>
            <td width="156" style="border: medium none ;">&nbsp;</td>
            <td width="144" style="border: medium none ;">&nbsp;</td>
            <td width="181" style="border: medium none ;">&nbsp;</td>
        </tr>
    </tbody>
</table>
<div style="margin-left: 35.4pt;">&nbsp;</div>
<div style="margin-left: 35.4pt; text-indent: 27pt;"><b>
{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Квитанція"}
{$oLanguage->GetMessage($variable_lang)} №___________</b></div>
<div style="margin-left: 35.4pt;"><b>&nbsp;</b><span style="font-size: 10pt;">
{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Дата здійснення операції"}
{$oLanguage->GetMessage($variable_lang)}</span></div>
<div style="margin-left: 35.4pt;"><span style="font-size: 10pt;">
{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Дата валютування"}
{$oLanguage->GetMessage($variable_lang)}</span></div>
<table width="661" cellspacing="0" cellpadding="0" border="0" style="margin-left: -0.6pt; border-collapse: collapse;">
    <tbody>
        <tr style="height: 19pt;">
            <td width="121" valign="top" style="border: 1pt solid windowtext; padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 19pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Назва валюти"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="60" valign="top" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 19pt;">
            <div>&nbsp;</div>
            </td>
            <td width="156" valign="top" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 19pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::№ рахунку"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="144" valign="top" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 19pt;">
            <div><span style="font-size: 10pt;">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Сума"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="180" valign="top" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 135pt; height: 19pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Еквівалент у гривнях"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="border-style: none solid solid; border-color: -moz-use-text-color windowtext windowtext; border-width: medium 1pt 1pt; padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">UAH</span></div>
            </td>
            <td width="60" valign="bottom" nowrap="nowrap" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Дебет"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.5pt;">
            <div><b><i>&nbsp;</i></b></div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="180" valign="bottom" nowrap="nowrap" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 135pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="border-style: none solid; border-color: -moz-use-text-color windowtext; border-width: medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">UAH</span></div>
            </td>
            <td width="60" valign="bottom" nowrap="nowrap" style="border-style: none solid none none; border-color: -moz-use-text-color windowtext -moz-use-text-color -moz-use-text-color; border-width: medium 1pt medium medium; padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Кредит"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none solid none none; border-color: -moz-use-text-color windowtext -moz-use-text-color -moz-use-text-color; border-width: medium 1pt medium medium; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.5pt;"><strong>{$aActiveAccount.account_id}</strong></td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none solid none none; border-color: -moz-use-text-color windowtext -moz-use-text-color -moz-use-text-color; border-width: medium 1pt medium medium; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.5pt;">
            <div>&nbsp;<b style=""><span style="font-family: Arial;"> </span></b><b style=""><span style="font-family: Arial;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::грн"}
            {$aBill.amount} {$oLanguage->GetMessage($variable_lang)}</span></b></div>
            </td>
            <td width="180" valign="bottom" nowrap="nowrap" style="border-style: none solid none none; border-color: -moz-use-text-color windowtext -moz-use-text-color -moz-use-text-color; border-width: medium 1pt medium medium; padding: 0.75pt 0.75pt 0cm; width: 135pt; height: 9.5pt;">
            <div>&nbsp;<b style=""><span style="font-family: Arial;"> </span></b><b style=""><span style="font-family: Arial;">
            {$aBill.amount} {$oLanguage->GetMessage($variable_lang)}</span></b></div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="2" style="border-style: solid none solid solid; border-color: windowtext -moz-use-text-color windowtext windowtext; border-width: 1pt medium 1pt 1pt; padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Загальна сума (цифрами)"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: solid none; border-color: windowtext -moz-use-text-color; border-width: 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border: 1pt solid windowtext; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.5pt;">
            <div align="right">&nbsp;</div>
            </td>
            <td width="180" valign="bottom" nowrap="nowrap" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 135pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Платник"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="540" valign="bottom" nowrap="nowrap" colspan="4" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 405pt; height: 9.5pt;">
            <div><b><i><span style="font-size: 10pt;"><br />
            </span></i></b></div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код платника"}
			{$oLanguage->GetMessage($variable_lang)}**</span></div>
            </td>
            <td width="60" valign="bottom" nowrap="nowrap" style="border-style: none none solid solid; border-color: -moz-use-text-color -moz-use-text-color windowtext windowtext; border-width: medium medium 1pt 1pt; padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="180" valign="bottom" nowrap="nowrap" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 135pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Банк платника"}
			{$oLanguage->GetMessage($variable_lang)}*</span></div>
            </td>
            <td width="60" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="180" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 135pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Отримувач"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="540" valign="bottom" nowrap="nowrap" colspan="4" style="padding: 0.75pt 0.75pt 0cm; width: 405pt; height: 9.5pt;"><b style="">{$aActiveAccount.name}</b></td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код отримувача"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="216" valign="bottom" nowrap="nowrap" colspan="2" style="border: 1pt solid windowtext; padding: 0.75pt 0.75pt 0cm; width: 162pt; height: 9.5pt;">
            <div align="left">&nbsp;</div>
            <strong>{$aActiveAccount.holder_code}</strong></td>
            <td width="324" valign="bottom" nowrap="nowrap" colspan="2" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium; padding: 0.75pt 0.75pt 0cm; width: 243pt; height: 9.5pt;">
            <div align="center">&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Банк отримувача"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="216" valign="bottom" nowrap="nowrap" colspan="3" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 162pt; height: 9.5pt;">
            {$aActiveAccount.bank_name}</td>
            <td width="180" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 135pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="2" style="padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код банку отримувача"}
			{$oLanguage->GetMessage($variable_lang)}*</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.5pt;">
            <div><b style=""><span lang="UK" style="font-family: Arial;">{$aActiveAccount.bank_mfo}</span></b></div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="180" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 135pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Загальна сума"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="540" valign="bottom" nowrap="nowrap" colspan="4" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 405pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="60" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="180" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 135pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="2" style="padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Призначення платежу"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="480" valign="bottom" nowrap="nowrap" colspan="3" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 360pt; height: 9.5pt;">
            <p><b><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::поповнення рахунку на сайті (логін"}
            {assign var="variable_lang2" value=$print_language_prefix|cat:"_doc_print::без ПДВ"}            
			{$oLanguage->GetMessage($variable_lang)}:{$aUser.login};), {$oLanguage->getMessage($variable_lang2)}<br />
            </span></b></p>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="60" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="480" valign="bottom" nowrap="nowrap" colspan="3" style="padding: 0.75pt 0.75pt 0cm; width: 360pt; height: 9.5pt;">
            <div><b><span style="font-size: 10pt;"><br />
            </span></b></div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="181" valign="bottom" nowrap="nowrap" colspan="2" style="padding: 0.75pt 0.75pt 0cm; width: 135.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Додаткові реквізити"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="180" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 135pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="60" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="180" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 135pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 9.5pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 9.5pt;">
            <div><span style="font-size: 10pt;">
   			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Підпис платника"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="60" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 9.5pt;">
            <div align="right"><span style="font-size: 10pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Підпис банку"}
			{$oLanguage->GetMessage($variable_lang)}</span></div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
            <td width="180" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0.75pt 0.75pt 0cm; width: 135pt; height: 9.5pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
        <tr style="height: 3.25pt;">
            <td width="121" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 90.75pt; height: 3.25pt;">
            <div>&nbsp;</div>
            </td>
            <td width="60" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 45pt; height: 3.25pt;">
            <div>&nbsp;</div>
            </td>
            <td width="156" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 117pt; height: 3.25pt;">
            <div>&nbsp;</div>
            </td>
            <td width="144" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 108pt; height: 3.25pt;">
            <div>&nbsp;</div>
            </td>
            <td width="180" valign="bottom" nowrap="nowrap" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1.5pt; padding: 0.75pt 0.75pt 0cm; width: 135pt; height: 3.25pt;">
            <div>&nbsp;</div>
            </td>
        </tr>
    </tbody>
</table>