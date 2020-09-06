{$oLanguage->GetMessage('check math')}: {$aCapcha.mathematic_formula} =
<input type="hidden" name="capcha[validation_hash]" value='{$aCapcha.validation_hash}' />
<input type="hidden" name="capcha[mathematic_formula]" value='{$aCapcha.mathematic_formula}' />
<input type="text" class="form-control grey" name="capcha[result]" value='{if $aCapcha.result}{$aCapcha.result}{/if}' maxlength='5' style="width: 50px !important;display: initial" />