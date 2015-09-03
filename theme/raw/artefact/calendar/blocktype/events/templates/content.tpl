<p class="detail">{$description}</p>
{if $tags}<p class="tags"><strong>{str tag=tags}:</strong> {list_tags owner=$owner tags=$tags}</p>{/if}
{if $events.data}
<table id="eventtable_{$blockid}" class="eventsblocktable fullwidth">
    <tbody>
    {$events.tablerows|safe}
    </tbody>
</table>
{if $events.pagination}
<div id="events_page_container_{$blockid}" class="nojs-hidden-block">{$events.pagination|safe}</div>
{/if}
{else}
    <p class="message">{str tag='noevents' section='artefact.calendar'}</p>
{/if}