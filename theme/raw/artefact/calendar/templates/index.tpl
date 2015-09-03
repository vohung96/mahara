{include file="header.tpl"}
{if $plans.count <= 0}
    {str section="artefact.calendar" tag="error_no_plan"}
    <a class="btn" onclick="document.getElementById('planoverlay').style.display='block';">{str section="artefact.plans" tag="newplan"}</a>
    {include file="artefact:calendar:new_plan.tpl"}
{else}
  <div id="planswrap">
    {$plans.tablerows|safe}
  </div>
{/if}
{include file="footer.tpl"}
