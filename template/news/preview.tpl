<h1>{$aNewsRow.short}</h1>
<div class="ms-news-element">
    <div class="date" style="display:none">
	<div class="day">{$oLanguage->GetMonthDayFromPostDate($aNewsRow.post_date)}</div>
	{$oLanguage->GetYearFromPostDate($aNewsRow.post_date)}
    </div>
    <div class="news-item">
	    {$aNewsRow.full}
    </div>
    <div class="clear"></div>
</div>
