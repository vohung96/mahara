<style type="text/css">
    td.c1 {
        width: 25px;
    }
</style>
{foreach from=$facts.data item=fact}

    {if $fact->completed == -1}
        <tr class="milestone_incomplete">
            <td class="c1 incomplete">
                <img src="{$WWWROOT}theme/raw/static/images/failure_small.png" alt="{str tag=overdue section=artefact.milestones}" />
            </td>
            {if $fact->description and $fact->description != "<p></p>"}
                <td class="milestonefacttitledescript">
                    <a class="fact-title" href="">{$fact->title}</a>
                    <br><span class="fact-deadline"><i>(Deadline: <b>{date($facts.dateFormat,strtotime($fact->completiondate))}</b>)</i></span>
                    <div class="fact-desc hidden">{$fact->description|clean_html|safe}
                        {if $fact->tags}
                            <p class="tags">
                                <strong>{str tag=tags}:</strong> {list_tags owner=$fact->owner tags=$fact->tags}
                            </p>
                        {/if}
                    </div>
                </td>
            {else}
                <td class="milestonefacttitle">
                    <span class="fact-title">{$fact->title}</span>
                    <br><span class="fact-deadline"><i>(Deadline: <b>{date($facts.dateFormat,strtotime($fact->completiondate))}</b>)</i></span>
                </td>
            {/if}
        </tr>
    {else}
        <tr class="{cycle values='r0,r1'}">
            {if $fact->completed == 1}
                <td class="c1 completed">
                    <img src="{$WWWROOT}theme/raw/static/images/success_small.png" alt="{str tag=completed section=artefact.milestones}" />
                </td>
            {else}
                <td class="c1">
                    <span class="accessible-hidden">{str tag=incomplete section=artefact.milestones}</span>
                </td>
            {/if}
            <td class="milestonefacttitledescript">
                <a class="fact-title" href="{$WWWROOT}artefact/calendar/index.php?fact_info={$fact->id}">{$fact->title}</a>
                <br><span class="fact-deadline"><i>(Deadline: <b>{date($facts.dateFormat,strtotime($fact->completiondate))}</b>)</i></span>
                {if $fact->description|clean_html|safe}
                    <div class="fact-description" id="fact-desc-{$fact->id}">{$fact->description|clean_html|safe}
                        {if $fact->tags}
                            <p class="tags">
                                <strong>{str tag=tags}:</strong> {list_tags owner=$fact->owner tags=$fact->tags}
                            </p>
                        {/if}
                    </div>
                {/if}
            </td>
        </tr>
    {/if}
{/foreach}
