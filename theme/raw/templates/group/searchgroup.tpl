{if $groups["data"]}
<h3>{str tag='resultsearch' section=blocktype.internal/eselmasearchgroup}:</h3>
	<ul>
		{foreach from=$groups["data"] item=group}
			<li>
				<a href="{$WWWROOT}group/view.php?id={$group->id}">{$group->name}</a> ({$group->admins[0]->firstname} {$group->admins[0]->lastname})
			</li>
		{/foreach}
	</ul>
{/if}