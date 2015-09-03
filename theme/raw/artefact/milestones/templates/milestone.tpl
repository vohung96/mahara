{include file="header.tpl"}
<div id="milestoneswrap">
    <div class="rbuttons">
        <a class="btn" href="{$WWWROOT}artefact/milestones/new.php?id={$milestone}">{str section="artefact.milestones" tag="newfact"}</a>
    </div>
    {if $tags}<p class="tags s"><strong>{str tag=tags}:</strong> {list_tags owner=$owner tags=$tags}</p>{/if}
{if !$facts.data}
    <div>{$milestonesfactsdescription}</div>
    <div class="message">{$strnofactsaddone|safe}</div>
{else}
<table id="factslist" class="fullwidth listing">
    <thead>
        <tr>
            <th>{str tag='completiondate' section='artefact.milestones'}</th>
            <th>{str tag='title' section='artefact.milestones'}</th>
            <th>{str tag='description' section='artefact.milestones'}</th>
            <th>{str tag='tags'}</th>
            <th class="center">{str tag='completed' section='artefact.milestones'}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        {$facts.tablerows|safe}
    </tbody>
</table>
   {$facts.pagination|safe}
{/if}
</div>
{include file="footer.tpl"}
