<style type="text/css">
    td.c1 {
        width: 25px;
    }
</style>
{foreach from=$tasks.data item=task}

    {if $task->completed == -1}
        <tr class="plan_incomplete">
            <td class="c1 incomplete">
                <img src="{$WWWROOT}theme/raw/static/images/failure_small.png" alt="{str tag=overdue section=artefact.plans}" />
            </td>
            {if $task->description and $task->description != "<p></p>"}
                <td class="plantasktitledescript">
                    <a class="task-title" href="">{$task->title}</a>
                    <br><span class="task-deadline"><i>(Deadline: <b>{date($tasks.dateFormat,strtotime($task->completiondate))}</b>)</i></span>
                    <div class="task-desc hidden">{$task->description|clean_html|safe}
                        {if $task->tags}
                            <p class="tags">
                                <strong>{str tag=tags}:</strong> {list_tags owner=$task->owner tags=$task->tags}
                            </p>
                        {/if}
                    </div>
                </td>
            {else}
                <td class="plantasktitle">
                    <span class="task-title">{$task->title}</span>
                    <br><span class="task-deadline"><i>(Deadline: <b>{date($tasks.dateFormat,strtotime($task->completiondate))}</b>)</i></span>
                </td>
            {/if}
        </tr>
    {else}
        <tr class="{cycle values='r0,r1'}">
            {if $task->completed == 1}
                <td class="c1 completed">
                    <img src="{$WWWROOT}theme/raw/static/images/success_small.png" alt="{str tag=completed section=artefact.plans}" />
                </td>
            {else}
                <td class="c1">
                    <span class="accessible-hidden">{str tag=incomplete section=artefact.plans}</span>
                </td>
            {/if}
            <td class="plantasktitledescript">
                <a class="task-title" href="{$WWWROOT}artefact/calendar/index.php?task_info={$task->id}">{$task->title}</a>
                <br><span class="task-deadline"><i>(Deadline: <b>{date($tasks.dateFormat,strtotime($task->completiondate))}</b>)</i></span>
                {if $task->description|clean_html|safe}
                    <div class="task-description" id="task-desc-{$task->id}">{$task->description|clean_html|safe}
                        {if $task->tags}
                            <p class="tags">
                                <strong>{str tag=tags}:</strong> {list_tags owner=$task->owner tags=$task->tags}
                            </p>
                        {/if}
                    </div>
                {/if}
            </td>
        </tr>
    {/if}
{/foreach}
