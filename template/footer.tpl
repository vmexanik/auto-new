
{if $aGeneralConf.IsLive}
	{literal}

	{/literal}
{/if}

{if $aAuthUser.type_=='manager'}
    <script type="text/javascript">
    {literal}
    $(document).ready(function() {
	if ($('#select_name_user').length) {
	    $("#select_name_user").searchable({
	    maxListSize: 50,
	    maxMultiMatch: 25,
	    wildcards: true,
	    ignoreCase: true,
	    latency: 1000,
	    {/literal}warnNoMatch: '{$oLanguage->getMessage('no matches')} ...',{literal}
	    zIndex: 'auto'
	    });
	}
    });
    {/literal}
    </script>
{/if}

</body>
</html>