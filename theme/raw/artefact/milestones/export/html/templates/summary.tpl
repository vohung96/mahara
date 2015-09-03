{if $milestones}
<ul>
{foreach from=$milestones item=milestone}
    <li><a href="{$milestone.link}">{$milestone.title}</a></li>
{/foreach}
</ul>
{/if}
