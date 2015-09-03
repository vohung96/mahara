{foreach from=$facts.data item=fact}
    {if $fact->completed == -1}
        <tr class="incomplete">
            <td class="completiondate">{$fact->completiondate}</td>
            <td class="milestonefacttitle">{$fact->title}</td>
            <td class="milestonefactdescription">{$fact->description|clean_html|safe}</td>
            <td class="milestonefacttags">{if $fact->tags}{list_tags owner=$fact->owner tags=$fact->tags}{/if}</td>
            <td class="incomplete"><img src="{$WWWROOT}theme/raw/static/images/failure_small.png" alt="{str tag=overdue section=artefact.milestones}" /></td>
    {else}
        <tr class="{cycle values='r0,r1'}">
            <td class="completiondate">{$fact->completiondate}</td>
            <td class="milestonefacttitle">{$fact->title}</td>
            <td class="milestonefactdescription">{$fact->description|clean_html|safe}</td>
            <td class="milestonefacttags">{if $fact->tags}{list_tags owner=$fact->owner tags=$fact->tags}{/if}</td>
            {if $fact->completed == 1}
                <td class="completed"><img src="{$WWWROOT}theme/raw/static/images/success_small.png" alt="{str tag=completed section=artefact.milestones}" /></td>
            {else}
                <td><span class="accessible-hidden">{str tag=incomplete section=artefact.milestones}</span></td>
            {/if}

    {/if}
            <td class="buttonscell btns2 milestonescontrols">
                <a href="{$WWWROOT}artefact/milestones/edit/fact.php?id={$fact->fact}" title="{str tag=edit}">
                    <img src="{theme_url filename='images/btn_edit.png'}" alt="{str(tag=editspecific arg1=$fact->title)|escape:html|safe}">
                </a>
                <a href="{$WWWROOT}artefact/milestones/delete/fact.php?id={$fact->fact}" title="{str tag=delete}">
                    <img src="{theme_url filename='images/btn_deleteremove.png'}" alt="{str(tag=deletespecific arg1=$fact->title)|escape:html|safe}">
                </a>
            </td>
        </tr>
{/foreach}
