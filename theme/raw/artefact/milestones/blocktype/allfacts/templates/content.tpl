<p class="detail">{$description}</p>
{if $tags}<p class="tags"><strong>{str tag=tags}:</strong> {list_tags owner=$owner tags=$tags}</p>{/if}
{if $facts.data}
<table id="facttable_{$blockid}" class="milestonesblocktable fullwidth">
    <tbody>
    {$facts.tablerows|safe}
    </tbody>
</table>
{if $facts.pagination}
<div id="allfacts_page_container_{$blockid}" class="nojs-hidden-block">{$facts.pagination|safe}</div>
{/if}
{else}
    <p class="message">{str tag='nofacts' section='artefact.milestones'}</p>
{/if}
