{include file="header.tpl"}
<div id="milestoneswrap">
    <div class="rbuttons">
        <a class="btn" href="{$WWWROOT}artefact/milestones/new/fact.php">{str section="artefact.milestones" tag="newfact"}</a>
    </div>
{if !$facts.data}
    <div class="message">{$strnofactsaddone|safe}</div>
{else}
<table id="milestoneslist">
    <thead>
        <tr>
            <th class="completiondate">{str tag='completiondate' section='artefact.milestones'}</th>
            <th class="milestonetitle">{str tag='title' section='artefact.milestones'}</th>
            <th class="milestonedescription">{str tag='description' section='artefact.milestones'}</th>
            <th class="milestonescontrols"></th>
            <th class="milestonescontrols"></th>
            <th class="milestonescontrols"></th>
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
