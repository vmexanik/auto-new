<h2>{$sGroupName} {$aGroupsLVM.client.mark} {$aGroupsLVM.client.family} {$aGroupsLVM.client.model}</h2>

<ul class="at-index-cats">
{foreach item=aItem from=$aGroupsLVM.groups}
    <li>
	<div class="at-index-cat-thumb" style="background-image: url('{$aItem.image.0}')">
	    <div class="name">{$aItem.full_name}</div>
	    <br>
	    <br>
	    <br>
	    <br>
	    <br>
	    <br>
	    <br>
	    <br>
	    <div class="show-more">
	       <a href="/pages/levam_{$type_url}?ssd={$aGroupsLVM.client.ssd}&link={$aGroupsLVM.link}&group={$aItem.group_name}&from_data={$aItem.sFromData}">перейти</a>
       </div>
	</div>
    </li>
{/foreach}
</ul>