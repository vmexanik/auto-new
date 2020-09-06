{assign var="print_language_prefix" value=$oLanguage->GetConstant('global:print_language_prefix','ua')}
<table cellspacing="0" cellpadding="0" border="0" class="MsoNormalTable" style="margin-left: 1.4pt; border-collapse: collapse;">
    <tbody>
        <tr style="page-break-inside: avoid;">
            <td width="416" valign="bottom" colspan="19" style="padding: 0cm 1.4pt; width: 11cm;">
            <p class="MsoNormal" style="margin-left: 154.55pt;"><span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Типова форма № КО-1"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="28" rowspan="37" style="border-style: none none none solid;
border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext;
border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 21.25pt;">&nbsp;</td>
            <td width="10" valign="bottom" rowspan="37" style="border-style: none none none dashed;
border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext;
border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.15pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext;
border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="416" valign="bottom" colspan="19" style="padding: 0cm 1.4pt; width: 11cm;">
            <p class="MsoNormal" style="margin-left: 154.55pt;"><span style="font-size: 8pt;"><br />
            <o:p></o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext;
border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="416" valign="bottom" colspan="19" style="padding: 0cm 1.4pt; width: 11cm;">
            <p class="MsoNormal" style="margin-left: 154.55pt;"><span style="font-size: 8pt;"><br />
            <o:p></o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext;
border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="border-style: none none solid;
border-color: -moz-use-text-color -moz-use-text-color windowtext;
border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 184.2pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;"><o:p>{* {$aActiveAccount.name} *} <br />
            </o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="416" valign="bottom" colspan="19" style="padding: 0cm 1.4pt; width: 11cm;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext;
border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 6pt;">
   			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::(найменування підприємства (установи, організації))"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="321" valign="bottom" colspan="13" style="padding: 0cm 1.4pt; width: 241pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="94" valign="bottom" colspan="6" style="border-style: solid solid none;
 border-color: windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt medium; padding: 0cm 1.4pt; width: 70.85pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;">
           	{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
 border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
 padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="321" valign="bottom" colspan="13" style="padding: 0cm 1.4pt; width: 241pt;">
            <p align="right" class="MsoNormal" style="margin-right: 5.65pt; text-align: right;"><span style="font-size: 8pt;"><br />
            <o:p></o:p></span></p>
            </td>
            <td width="94" valign="bottom" colspan="6" style="border: 1.5pt solid windowtext; padding: 0cm 1.4pt; width: 70.85pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><br />
            <o:p></o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
 border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
 padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><b>
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Квитанція"}
			{$oLanguage->getMessage($variable_lang)}<o:p></o:p></b></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="255" valign="bottom" colspan="10" style="border-style: none none solid;
 border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt;
 padding: 0cm 1.4pt; width: 191.4pt;">
            <div style="text-align: center;"><b style=""><i style="">
            <span lang="UK" style="font-family: Arial;">{* {$aActiveAccount.name} *}</span></div>
            </td>
            <td width="66" valign="bottom" colspan="3" style="padding: 0cm 1.4pt; width: 49.6pt;">
            <p align="right" class="MsoNormal" style="margin-right: 5.65pt; text-align: right;">
            <span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код ЄДРПОУ"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="94" valign="bottom" colspan="6" style="border-style: none solid solid;
 border-color: -moz-use-text-color windowtext windowtext; border-width: medium 1.5pt 1.5pt; padding: 0cm 1.4pt; width: 70.85pt;">
  <p align="center" class="MsoNormal" style="text-align: center;"><strong>{$aActiveAccount.holder_code}</strong>
  <span style="font-size: 8pt;"><o:p> <br />
            </o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
  border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
  padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="255" valign="bottom" colspan="10" style="padding: 0cm 1.4pt; width: 191.4pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 6pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::найменування підприємства (установи, організації)"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="66" valign="bottom" colspan="3" style="padding: 0cm 1.4pt; width: 49.6pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="94" colspan="6" rowspan="2" style="border-style: none solid solid;
  border-color: -moz-use-text-color windowtext windowtext; border-width: medium 1.5pt 1.5pt; padding: 0cm 1.4pt; width: 70.85pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
  border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
  padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid; height: 8pt;">
            <td width="321" valign="bottom" colspan="13" style="border-style: none none solid;
  border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt;
  width: 241pt; height: 8pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;">
            <o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt; height: 8pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
  border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
  padding: 0cm 1.4pt; width: 7.1pt; height: 8pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="170" valign="bottom" colspan="18" style="padding: 0cm 1.4pt; width: 127.55pt; height: 8pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;">
           	{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::до прибуткового касового ордера"}
			{$oLanguage->GetMessage($variable_lang)}&nbsp; №<o:p></o:p></span></p>
            </td>
            <td width="76" valign="bottom" colspan="5" style="border-style: none none solid;
  border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt;
  width: 56.65pt; height: 8pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>{$aBill.id} <br />
            </o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="321" valign="top" colspan="13" style="padding: 0cm 1.4pt; width: 241pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><br />
            <o:p></o:p></span></p>
            </td>
            <td width="94" valign="bottom" colspan="6" style="padding: 0cm 1.4pt; width: 70.85pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
  border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
  padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="23" valign="bottom" colspan="2" style="padding: 0cm 1.4pt; width: 17pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::від"}
			{$oLanguage->GetMessage($variable_lang)} <o:p></o:p></span></p>
            </td>
            <td valign="bottom" colspan="20" style="border-style: none none solid;
  border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt;
		">{$aBill.post_date}</td>
            <td width="49" valign="bottom" style="padding: 0cm 1.4pt; width: 36.65pt;">
            <p class="MsoNormal" style="margin-left: 2.85pt;"><span style="font-size: 8pt;"><o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="274" valign="bottom" colspan="11" style="padding: 0cm 1.4pt; width: 205.55pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="66" valign="top" colspan="4" rowspan="2" style="border-style: solid solid none;
  border-color: windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt medium; padding: 0cm 1.4pt; width: 49.6pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;">
   			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Номер документа"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="76" valign="top" colspan="4" rowspan="2" style="border-style: solid solid none none;
  border-color: windowtext windowtext -moz-use-text-color -moz-use-text-color; border-width: 1pt 1pt medium medium;
  padding: 0cm 1.4pt; width: 2cm;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;">Дата <o:p></o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
  border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
  padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="274" valign="bottom" colspan="11" style="padding: 0cm 1.4pt; width: 205.55pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
  border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
  padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="66" valign="bottom" colspan="6" style="padding: 0cm 1.4pt; width: 49.6pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Прийнято від"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="179" valign="bottom" colspan="17" style="border-style: none none solid;
  border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt;
  width: 134.6pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;"><o:p>{$aUser.name}&nbsp; ({$aUser.login}) <br />
            </o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="274" valign="bottom" colspan="11" style="padding: 0cm 1.4pt; width: 205.55pt;">
            <p align="right" class="MsoNormal" style="margin-right: 5.65pt; text-align: right;">
            <b>
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Прибутковий касовий ордер"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></b></p>
            </td>
            <td width="66" colspan="4" style="border-style: solid; border-color: windowtext; border-width: 1.5pt 1pt 1.5pt 1.5pt;
  padding: 0cm 1.4pt; width: 49.6pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><b><o:p>{$aBill.id} <br />
            </o:p></b></p>
            </td>
            <td width="76" colspan="4" style="border-style: solid solid solid none;
  border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1.5pt 1.5pt 1.5pt medium;
  padding: 0cm 1.4pt; width: 2cm;">
            <p align="center" class="MsoNormal" style="text-align: center;"><b><o:p>&nbsp;</o:p></b></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
  border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
  padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="border-style: none none solid;
  border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt;
  padding: 0cm 1.4pt; width: 184.2pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid; height: 2.5pt;">
            <td width="416" valign="bottom" colspan="19" style="padding: 0cm 1.4pt; width: 11cm; height: 2.5pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt; height: 2.5pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
   border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
   padding: 0cm 1.4pt; width: 7.1pt; height: 2.5pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="57" valign="bottom" colspan="5" style="padding: 0cm 1.4pt; width: 42.5pt; height: 2.5pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Підстава"}
			{$oLanguage->GetMessage($variable_lang)}:<o:p></o:p></span></p>
            </td>
            <td width="189" valign="bottom" colspan="18" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 141.7pt; height: 2.5pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;"><o:p>             
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::передоплата по заказу"}
			{$oLanguage->GetMessage($variable_lang)} №{$aBill.id_cart_package}             <br />
            </o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="38" valign="top" rowspan="5" style="border-style: solid solid none;
   border-color: windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt medium; padding: 0cm 1.4pt; width: 1cm;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Дебет"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="217" valign="bottom" colspan="9" style="border-style: solid solid solid none;
   border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium;
   padding: 0cm 1.4pt; width: 163.05pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Кредит"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="57" valign="top" colspan="2" rowspan="5" style="border-style: solid solid solid none;
   border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium;
   padding: 0cm 1.4pt; width: 42.5pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Сума, грн"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="68" valign="top" colspan="6" rowspan="5" style="border-style: solid solid solid none;
   border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium;
   padding: 0cm 1.4pt; width: 51.35pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код цільового призначення"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="35" valign="top" rowspan="5" style="border-style: solid solid solid none;
   border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1pt 1pt 1pt medium;
   padding: 0cm 1.4pt; width: 26.6pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
   border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
   padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="border-style: none none solid;
   border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt;
   padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="28" valign="top" colspan="2" rowspan="4" style="border-style: none solid none none;
   border-color: -moz-use-text-color windowtext -moz-use-text-color -moz-use-text-color; border-width: medium 1pt medium medium;
   padding: 0cm 1.4pt; width: 21.3pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="57" valign="top" colspan="3" rowspan="4" style="border-style: solid solid none none;
   border-color: windowtext windowtext -moz-use-text-color -moz-use-text-color; border-width: 1pt 1pt medium medium;
   padding: 0cm 1.4pt; width: 42.5pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><br />
            <o:p></o:p></span></p>
            </td>
            <td width="57" valign="top" colspan="2" rowspan="4" style="border-style: solid solid none none;
   border-color: windowtext windowtext -moz-use-text-color -moz-use-text-color; border-width: 1pt 1pt medium medium;
   padding: 0cm 1.4pt; width: 42.55pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Кореспондуючий рахунок, субрахунок"}
			{$oLanguage->GetMessage($variable_lang)}</span><span style="font-size: 8pt;">
            <o:p></o:p></span></p>
            </td>
            <td width="76" valign="top" colspan="2" rowspan="4" style="border-style: solid solid none none;
   border-color: windowtext windowtext -moz-use-text-color -moz-use-text-color; border-width: 1pt 1pt medium medium;
    padding: 0cm 1.4pt; width: 2cm;">
            <p align="center" class="MsoNormal" style="text-align: center;">
            <span style="font-size: 8pt;">
   			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код аналітичного рахунку"}
			{$oLanguage->GetMessage($variable_lang)} <o:p></o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
    border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
    padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="9" valign="bottom" style="border: medium none ; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
     border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
     padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="border-style: solid none;
     border-color: windowtext -moz-use-text-color; border-width: 1pt medium; padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="9" valign="bottom" style="border: medium none ; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
     border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
     padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="9" valign="bottom" style="border: medium none ; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid;
     border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt;
     padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid; height: 15pt;">
            <td width="38" style="border-style: solid; border-color: windowtext; border-width: 1.5pt 1pt 1.5pt 1.5pt; padding: 0cm 1.4pt; width: 1cm; height: 15pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="28" colspan="2" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1.5pt 1pt 1.5pt medium; padding: 0cm 1.4pt; width: 21.3pt; height: 15pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="57" colspan="3" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1.5pt 1pt 1.5pt medium; padding: 0cm 1.4pt; width: 42.5pt; height: 15pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="57" colspan="2" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1.5pt 1pt 1.5pt medium; padding: 0cm 1.4pt; width: 42.55pt; height: 15pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="76" colspan="2" style="border-style: solid solid solid none; border-color: windowtext windowtext windowtext -moz-use-text-color; border-width: 1.5pt 1pt 1.5pt medium; padding: 0cm 1.4pt; width: 2cm; height: 15pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="57" colspan="2" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1.5pt medium; padding: 0cm 1.4pt; width: 42.45pt; height: 15pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>{$aBill.amount} <br />
            </o:p></span></p>
            </td>
            <td width="68" colspan="5" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1pt 1.5pt medium; padding: 0cm 1.4pt; width: 51.05pt; height: 15pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="36" colspan="2" style="border-style: none solid solid none; border-color: -moz-use-text-color windowtext windowtext -moz-use-text-color; border-width: medium 1.5pt 1.5pt medium; padding: 0cm 1.4pt; width: 26.95pt; height: 15pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt; height: 15pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt; height: 15pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="38" valign="bottom" colspan="3" style="padding: 0cm 1.4pt; width: 1cm; height: 15pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;">
           	{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Сума"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="85" valign="bottom" colspan="8" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 63.75pt; height: 15pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;">{$aBill.amount}</span><span style="font-size: 8pt;"><o:p> <br />
            </o:p></span></p>
            </td>
            <td width="28" valign="bottom" colspan="4" style="padding: 0cm 1.4pt; width: 21.3pt; height: 15pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;">грн.<o:p></o:p></span></p>
            </td>
            <td width="38" valign="bottom" colspan="6" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 28.3pt; height: 15pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="57" valign="bottom" colspan="2" style="padding: 0cm 1.4pt; width: 42.5pt; height: 15pt;">
            <p class="MsoNormal" style="margin-left: 2.85pt;"><span style="font-size: 8pt;">коп.<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="416" valign="bottom" colspan="19" style="padding: 0cm 1.4pt; width: 11cm;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="38" valign="bottom" colspan="3" style="padding: 0cm 1.4pt; width: 1cm;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="85" valign="bottom" colspan="8" style="padding: 0cm 1.4pt; width: 63.75pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p></o:p></span></p>
            </td>
            <td width="123" valign="bottom" colspan="12" style="padding: 0cm 1.4pt; width: 92.1pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="66" valign="bottom" colspan="3" style="padding: 0cm 1.4pt; width: 49.65pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Прийнято від"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="350" valign="bottom" colspan="16" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 262.2pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>{$aUser.name}&nbsp; ({$aUser.login}) <br />
            </o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 184.2pt;">
            <p align="center" class="MsoNormal" style="text-align: center;">{$aBill.amount_string}<span style="font-size: 8pt;"><o:p> <br />
            </o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="416" valign="bottom" colspan="19" style="padding: 0cm 1.4pt; width: 11cm;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::(словами)"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="66" valign="bottom" colspan="3" style="padding: 0cm 1.4pt; width: 49.65pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Підстава"}
			{$oLanguage->GetMessage($variable_lang)}:<o:p></o:p></span></p>
            </td>
            <td width="350" valign="bottom" colspan="16" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 262.2pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;">
            	<o:p>
            	{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::передоплата по заказу"}
				{$oLanguage->GetMessage($variable_lang)} №{$aBill.id_cart_package}<br />
            </o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 184.2pt;">
            <p align="center" class="MsoNormal" style="margin-left: 2.85pt; text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="416" valign="bottom" colspan="19" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 11cm;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="113" valign="bottom" colspan="10" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 3cm;">
            <p align="center" class="MsoNormal" style="text-align: center;"><u><span style="font-size: 8pt;"><o:p><br />
            </o:p></span></u><span style="font-size: 8pt;"><o:p></o:p></span></p>
            </td>
            <td width="28" valign="bottom" colspan="2" style="padding: 0cm 1.4pt; width: 21.25pt;">&nbsp;</td>
            <td width="35" valign="bottom" colspan="7" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 26.55pt;">&nbsp;</td>
            <td width="68" valign="bottom" colspan="4" style="padding: 0cm 1.4pt; width: 51.35pt;">
            <p class="MsoNormal" style="margin-left: 2.85pt;"><span style="font-size: 8pt;"><o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="47" valign="bottom" colspan="2" style="padding: 0cm 1.4pt; width: 35.45pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;">
           	{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Сума"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="369" valign="bottom" colspan="17" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 276.4pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span>{$aBill.amount_string}<span style="font-size: 8pt;"><o:p> </o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="66" valign="bottom" colspan="6" style="padding: 0cm 1.4pt; width: 49.6pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p></o:p></span></p>
            </td>
            <td width="179" valign="bottom" colspan="17" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 134.6pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="47" valign="bottom" colspan="2" style="padding: 0cm 1.4pt; width: 35.45pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="369" valign="bottom" colspan="17" style="padding: 0cm 1.4pt; width: 276.4pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::(словами)"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="312" valign="bottom" colspan="12" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 233.9pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="28" valign="bottom" colspan="3" style="padding: 0cm 1.4pt; width: 21.25pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::грн"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="38" valign="bottom" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 1cm;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="38" valign="bottom" colspan="3" style="padding: 0cm 1.4pt; width: 1cm;">
            <p class="MsoNormal" style="margin-left: 2.85pt;"><span style="font-size: 8pt;">коп.<o:p></o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td valign="bottom" colspan="23" style="padding: 0cm 1.4pt;" nowrap>
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::від"}
			{$oLanguage->GetMessage($variable_lang)} {$aBill.post_date}</td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="66" valign="bottom" colspan="3" style="padding: 0cm 1.4pt; width: 49.65pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><br />
            <o:p></o:p></span></p>
            </td>
            <td width="350" valign="bottom" colspan="16" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 262.2pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="416" valign="bottom" colspan="19" style="padding: 0cm 1.4pt; width: 11cm;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" colspan="23" rowspan="2" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal" style="text-indent: 26.95pt;"><span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::М.П."}
			{$oLanguage->GetMessage($variable_lang)} <o:p></o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="66" valign="bottom" colspan="3" style="padding: 0cm 1.4pt; width: 49.65pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Додатки"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="350" valign="bottom" colspan="16" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 262.2pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="416" valign="bottom" colspan="19" style="padding: 0cm 1.4pt; width: 11cm;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
{*
        <tr style="page-break-inside: avoid;">
            <td width="104" valign="bottom" colspan="5" style="padding: 0cm 1.4pt; width: 78pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;">Бухгалтер<o:p></o:p></span></p>
            </td>
            <td width="76" valign="bottom" colspan="3" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 2cm;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="19" valign="bottom" style="padding: 0cm 1.4pt; width: 14.15pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="134" valign="bottom" colspan="5" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 100.6pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="83" valign="bottom" colspan="5" style="padding: 0cm 1.4pt; width: 62.4pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="98" valign="bottom" colspan="8" style="padding: 0cm 1.4pt; width: 73.7pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;">Бухгалтер<o:p></o:p></span></p>
            </td>
            <td width="47" valign="bottom" colspan="5" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 35.45pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="6" valign="bottom" colspan="2" style="padding: 0cm 1.4pt; width: 4.25pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="94" valign="bottom" colspan="8" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 70.8pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="104" valign="bottom" colspan="5" style="padding: 0cm 1.4pt; width: 78pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="76" valign="bottom" colspan="3" style="padding: 0cm 1.4pt; width: 2cm;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::підпис"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="19" valign="bottom" style="padding: 0cm 1.4pt; width: 14.15pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="134" valign="bottom" colspan="5" style="padding: 0cm 1.4pt; width: 100.6pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::прізвище, ініціали"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="83" valign="bottom" colspan="5" style="padding: 0cm 1.4pt; width: 62.4pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="98" valign="bottom" colspan="8" style="padding: 0cm 1.4pt; width: 73.7pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="47" valign="bottom" colspan="5" style="padding: 0cm 1.4pt; width: 35.45pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::підпис"}
			{$oLanguage->GetMessage($variable_lang)}</span></p>
            </td>
            <td width="6" valign="bottom" colspan="2" style="padding: 0cm 1.4pt; width: 4.25pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="94" valign="bottom" colspan="8" style="padding: 0cm 1.4pt; width: 70.8pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;">
   			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::прізвище, ініціали"}
			{$oLanguage->GetMessage($variable_lang)}</span></p>
            </td>
        </tr>
*}
        <tr style="page-break-inside: avoid;">
            <td width="83" valign="bottom" colspan="4" style="padding: 0cm 1.4pt; width: 62.35pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Одержав"}
			{$oLanguage->GetMessage($variable_lang)}{* касир *}<o:p></o:p></span></p>
            </td>
            <td width="83" valign="bottom" colspan="3" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 62.35pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="13" valign="bottom" style="padding: 0cm 1.4pt; width: 10pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="132" valign="bottom" colspan="4" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 99.2pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="104" valign="bottom" colspan="7" style="padding: 0cm 1.4pt; width: 77.95pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="47" valign="bottom" colspan="4" style="padding: 0cm 1.4pt; width: 35.4pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;">{* Касир *}<o:p></o:p></span></p>
            </td>
            <td width="51" valign="bottom" colspan="4" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 38.25pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="6" valign="bottom" style="padding: 0cm 1.4pt; width: 4.25pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="93" valign="bottom" colspan="13" style="border-style: none none solid; border-color: -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium 1pt; padding: 0cm 1.4pt; width: 69.45pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="49" valign="bottom" style="padding: 0cm 1.4pt; width: 36.85pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="83" valign="bottom" colspan="4" style="padding: 0cm 1.4pt; width: 62.35pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="83" valign="bottom" colspan="3" style="padding: 0cm 1.4pt; width: 62.35pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::підпис"}
			{$oLanguage->GetMessage($variable_lang)}<br />
            <o:p></o:p></span></p>
            </td>
            <td width="13" valign="bottom" style="padding: 0cm 1.4pt; width: 10pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="132" valign="bottom" colspan="4" style="padding: 0cm 1.4pt; width: 99.2pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::прізвище, ініціали"}
			{$oLanguage->GetMessage($variable_lang)}<o:p></o:p></span></p>
            </td>
            <td width="104" valign="bottom" colspan="7" style="padding: 0cm 1.4pt; width: 77.95pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="47" valign="bottom" colspan="4" style="padding: 0cm 1.4pt; width: 35.4pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="51" valign="bottom" colspan="4" style="padding: 0cm 1.4pt; width: 38.25pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::підпис"}
			{$oLanguage->GetMessage($variable_lang)}</span></p>
            </td>
            <td width="6" valign="bottom" style="padding: 0cm 1.4pt; width: 4.25pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="93" valign="bottom" colspan="13" style="padding: 0cm 1.4pt; width: 69.45pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::прізвище, ініціали"}
			{$oLanguage->GetMessage($variable_lang)}</span></p>
            </td>
            <td width="49" valign="bottom" style="padding: 0cm 1.4pt; width: 36.85pt;">
            <p align="center" class="MsoNormal" style="text-align: center;"><span style="font-size: 6pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td width="416" valign="bottom" colspan="19" style="padding: 0cm 1.4pt; width: 11cm;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="9" valign="bottom" style="border-style: none none none solid; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color windowtext; border-width: medium medium medium 1pt; padding: 0cm 1.4pt; width: 7.1pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
            <td width="246" valign="bottom" colspan="23" style="padding: 0cm 1.4pt; width: 184.2pt;">
            <p class="MsoNormal"><span style="font-size: 8pt;"><o:p>&nbsp;</o:p></span></p>
            </td>
        </tr>
        <!--{12496317440460}-->
        <tr height="0">
            <td width="38" style="border: medium none ;">&nbsp;</td>
            <td width="9" style="border: medium none ;">&nbsp;</td>
            <td width="19" style="border: medium none ;">&nbsp;</td>
            <td width="17" style="border: medium none ;">&nbsp;</td>
            <td width="21" style="border: medium none ;">&nbsp;</td>
            <td width="19" style="border: medium none ;">&nbsp;</td>
            <td width="43" style="border: medium none ;">&nbsp;</td>
            <td width="13" style="border: medium none ;">&nbsp;</td>
            <td width="19" style="border: medium none ;">&nbsp;</td>
            <td width="57" style="border: medium none ;">&nbsp;</td>
            <td width="19" style="border: medium none ;">&nbsp;</td>
            <td width="38" style="border: medium none ;">&nbsp;</td>
            <td width="9" style="border: medium none ;">&nbsp;</td>
            <td width="11" style="border: medium none ;">&nbsp;</td>
            <td width="8" style="border: medium none ;">&nbsp;</td>
            <td width="38" style="border: medium none ;">&nbsp;</td>
            <td width="2" style="border: medium none ;">&nbsp;</td>
            <td width="0" style="border: medium none ;">&nbsp;</td>
            <td width="36" style="border: medium none ;">&nbsp;</td>
            <td width="9" style="border: medium none ;">&nbsp;</td>
            <td width="28" style="border: medium none ;">&nbsp;</td>
            <td width="10" style="border: medium none ;">&nbsp;</td>
            <td width="9" style="border: medium none ;">&nbsp;</td>
            <td width="12" style="border: medium none ;">&nbsp;</td>
            <td width="14" style="border: medium none ;">&nbsp;</td>
            <td width="15" style="border: medium none ;">&nbsp;</td>
            <td width="9" style="border: medium none ;">&nbsp;</td>
            <td width="9" style="border: medium none ;">&nbsp;</td>
            <td width="9" style="border: medium none ;">&nbsp;</td>
            <td width="9" style="border: medium none ;">&nbsp;</td>
            <td width="23" style="border: medium none ;">&nbsp;</td>
            <td width="6" style="border: medium none ;">&nbsp;</td>
            <td width="9" style="border: medium none ;">&nbsp;</td>
            <td width="9" style="border: medium none ;">&nbsp;</td>
            <td width="19" style="border: medium none ;">&nbsp;</td>
            <td width="4" style="border: medium none ;">&nbsp;</td>
            <td width="1" style="border: medium none ;">&nbsp;</td>
            <td width="4" style="border: medium none ;">&nbsp;</td>
            <td width="7" style="border: medium none ;">&nbsp;</td>
            <td width="5" style="border: medium none ;">&nbsp;</td>
            <td width="7" style="border: medium none ;">&nbsp;</td>
            <td width="7" style="border: medium none ;">&nbsp;</td>
            <td width="2" style="border: medium none ;">&nbsp;</td>
            <td width="9" style="border: medium none ;">&nbsp;</td>
            <td width="8" style="border: medium none ;">&nbsp;</td>
            <td width="49" style="border: medium none ;">&nbsp;</td>
        </tr>
        <!--{12496317440461}-->
    </tbody>
</table>
<p>&nbsp;</p>