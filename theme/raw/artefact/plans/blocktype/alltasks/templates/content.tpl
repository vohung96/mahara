<p class="detail">{$description}</p>
{if $tags}<p class="tags"><strong>{str tag=tags}:</strong> {list_tags owner=$owner tags=$tags}</p>{/if}
{if $tasks.data}
<table id="tasktable_{$blockid}" class="plansblocktable fullwidth">
    <tbody>
    {$tasks.tablerows|safe}
    </tbody>
</table>
{if $tasks.pagination}
<div id="alltasks_page_container_{$blockid}" class="nojs-hidden-block">{$tasks.pagination|safe}</div>
{/if}
{else}
    <p class="message">{str tag='notasks' section='artefact.plans'}</p>
{/if}
