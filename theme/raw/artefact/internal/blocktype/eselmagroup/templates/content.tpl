{if $eselma_groups->count != 0}
    <style type="text/css">
    .list_group {
        margin-left: 10px;
        font-size: 1.167em;
        line-height: 1.25em;
    }
    .list_group ul {     
        list-style-type: none;
    }
    </style>
    <div class="list_group">
        <ul>
            {foreach from=$eselma_groups->data item=group}
                <li>
                    <a href="{$WWWROOT}group/view.php?id={$group.id}">{$group.name}</a> - {$group.group_admin}
                </li>
            {/foreach}
        </ul>
    </div>
    <div style="text-align: right; padding-right:5px">
        <a href="{$WWWROOT}group/edit.php" class="btn">{str tag='creategroup' section=blocktype.internal/eselmagroup}</a>
    </div>
{else}
    <div style="text-align:center">
        {str tag='havemygroups' section=blocktype.internal/eselmagroup}<a href="{$WWWROOT}group/edit.php">here</a>
    </div>
{/if}