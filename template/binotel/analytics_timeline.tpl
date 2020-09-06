<link rel="stylesheet" href="/css/binotel.css">
<script src="/js/vis.min.js"></script>


{literal}
	
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
		  // Load the Visualization API and the piechart package.
		  google.load('visualization', '1.0', { 'packages': ['corechart'] });
	</script>

	<script type="text/javascript">
	

	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-30362301-1', 'auto');
		ga('send', 'pageview');
</script>
{/literal}





<div class="timeline-navigation" align="right">
		<div>
			<button class="redesign-4-base-button" id="timelineZoomIn">Приблизить</button>
			<button class="redesign-4-base-button" id="timelineZoomOut">Уменьшить</button>
			<button class="redesign-4-base-button" id="timelineMoveLeft">Передвинуть влево</button>
			<button class="redesign-4-base-button" id="timelineMoveRight">Передвинуть вправо</button>
		</div>
	</div>

	<div class="analytics-employees-on-timeline" id="analytics-employees-on-timeline"></div>

{literal}
<script type="text/javascript">
	$(function() {
		var groups = new vis.DataSet([
				{/literal}
				{foreach from=$aGroups item=item key=key}
					{literal}
						{
							id: '{/literal}{$key}{literal}',
							content: '<span style="padding: 10px;">{/literal}{$item.content}{literal}</span>',
							order: {/literal}{$item.order}{literal}
						},	
					{/literal}
				{/foreach}	

				{literal}
					]);

		var calls = new vis.DataSet([
					{/literal}
				{foreach from=$aCalls item=item key=key}
				{if $item.id!='' && $item.content>0}
					{literal}
						{
						id: '{/literal}{$item.id}{literal}',
					group: '{/literal}{$item.group}{literal}',
					start: new Date({/literal}{$item.start}{literal} * 1000),
					end: new Date({/literal}{$item.end}{literal} * 1000),
					content: '{/literal}{$item.sContent}{literal}',
					title: '{/literal}{$item.title}{literal}',
					className: '{/literal}{$item.className}{literal}'
						},	
					{/literal}
				{/if}	
				{/foreach}	
				
				{literal}
					]);

		timeline = new vis.Timeline(document.getElementById('analytics-employees-on-timeline'), null, {
			stack: false,
			min: new Date({/literal}{$aDateRange.min}{literal} * 1000),
			max: new Date({/literal}{$aDateRange.max}{literal} * 1000),
			start: new Date({/literal}{$aDateRange.min}{literal} * 1000),
			end: new Date({/literal}{$aDateRange.max}{literal} * 1000),
			editable: false,
			margin: {
				item: 10,
				axis: 5
			},
			clickToUse: true,
			orientation: 'both',
			showCurrentTime: false,
			zoomMax: 8 * 60 * 60 * 1000,
			zoomMin: 4 * 60 * 1000,
		});

		timeline.setGroups(groups);
		timeline.setItems(calls);

		setTimeout(function() {           
			timeline.setWindow('{/literal}{$aDateRange.window}{literal} 15:00', '{/literal}{$aDateRange.window}{literal} 15:20');
		}, 1 * 1000);



		/**
		 * Move the timeline a given percentage to left or right
		 * @param { Number } percentage   For example 0.1 (left) or -0.1 (right)
		 */
		function move (percentage) {
			var range = timeline.getWindow();
			var interval = range.end - range.start;

			timeline.setWindow({
				start: range.start.valueOf() - interval * percentage,
				end: range.end.valueOf() - interval * percentage
			});
		}

		/**
		 * Zoom the timeline a given percentage in or out
		 * @param { Number } percentage   For example 0.1 (zoom out) or -0.1 (zoom in)
		 */
		function zoom (percentage) {
			var range = timeline.getWindow();
			var interval = range.end - range.start;

			timeline.setWindow({
				start: range.start.valueOf() - interval * percentage,
				end: range.end.valueOf() + interval * percentage
			});
		}

		$('#timelineZoomIn').click(function() {
			zoom(-0.2);
		});

		$('#timelineZoomOut').click(function() {
			zoom(0.2);
		});

		// $('#timelineMoveLeft').click(function() {
		// 	move(0.25);
		// });

		// $('#timelineMoveRight').click(function() {
		// 	move(-0.25);
		// });

		var timerTimelineMoveLeft;
		$('#timelineMoveLeft').mousedown(function() {
			clearInterval(timerTimelineMoveLeft);
			move(0.2);
			timerTimelineMoveLeft = setInterval(function() {
				move(0.25);
			}, 0.7 * 1000);
		});

		$('#timelineMoveLeft').mouseup(function() {
			clearInterval(timerTimelineMoveLeft);
		});


		var timerTimelineMoveRight;
		$('#timelineMoveRight').mousedown(function() {
			clearInterval(timerTimelineMoveRight);
			move(-0.2);
			timerTimelineMoveRight = setInterval(function() {
				move(-0.25);
			}, 0.7 * 1000);
		});

		$('#timelineMoveRight').mouseup(function() {
			clearInterval(timerTimelineMoveRight);
		});
	});
</script>

{/literal}
