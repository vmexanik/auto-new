<div class="element">
  <div class="date">
      <div class="day">{$oLanguage->GetMonthDayFromPostDate($aRow.post_date)}</div>
      {$oLanguage->GetYearFromPostDate($aRow.post_date)}
  </div>
  <div class="news-item">
      <a href="/pages/news/{$aRow.id}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aRow.short}</a>
  </div>
  <div class="clear"></div>
</div>