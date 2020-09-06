<div class="clear"></div>
<div class="body-content">
    <div role="main" id="div_main">
        <div class="mainDivError">
            Errors:
            <ol>
            <?foreach($errorText as $method => $text):?>
                <li><?=$method?>: <?=$text?></li>
            <?endforeach;?>
            </ol>
        </div>
    </div>
</div>