$(document).ready(function() {
    $(".select_search").searchable({
    maxListSize: 50,
    maxMultiMatch: 25,
    wildcards: true,
    ignoreCase: true,
    latency: 10,
    warnNoMatch: 'no matches...',
    zIndex: 'auto'
    });
});