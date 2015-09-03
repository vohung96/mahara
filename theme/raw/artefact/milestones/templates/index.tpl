{include file="header.tpl"}
<div id="milestoneswrap">
    <div class="rbuttons">
        <a class="btn" href="{$WWWROOT}artefact/milestones/new.php">{str section="artefact.milestones" tag="newmilestone"}</a>
    </div>
{if !$milestones.data}
    <div class="message">{$strnomilestonesaddone|safe}</div>
{else}
<div id="milestoneslist" class="fullwidth listing">
        {$milestones.tablerows|safe}
</div>
   {$milestones.pagination|safe}
{/if}
</div>
{include file="footer.tpl"}
