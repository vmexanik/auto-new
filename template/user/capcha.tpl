<table class="capcha">
    <tr>
        <td>
            <strong>{$aCapcha.mathematic_formula} = </strong>
        </td>
        <td>
            <input type="hidden" name="capcha[validation_hash]" value='{$aCapcha.validation_hash}' />
			<input type="hidden" name="capcha[mathematic_formula]" value='{$aCapcha.mathematic_formula}' />
			<input type="text" name="capcha[result]" value='{if $aCapcha.result}{$aCapcha.result}{/if}' maxlength='5' />
        </td>
    </tr>
</table>