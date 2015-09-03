<style type="text/css">
	td.c1.event {
		width: 25px;
	}
	td.eventtitledescript{
		font-size: 1.167em;
		line-height: 1.25em;
	}

</style>
{foreach from=$events.data item=event}
<tr class="{cycle values='r0,r1'}">
    <td class="c1 event">
    </td>
    <td class="eventtitledescript">
        <a class="event-title" href="artefact/calendar/index.php?event_info={$event->id}"><b>{$event->title}</b></a>
        <br>
        <span class="event-deadline">
        	({if $event->authorname}{$event->authorname}, {/if}{if $event->whole_day}{date($events.whole_dayFormat, $event->begin)}{else}{date($events.dateFormat, $event->begin)}{/if}{if $event->description}, {$event->description}{/if})
        </span>
        <div class="event-desc hidden" id="event-desc-{$event->id}">{$event->description|clean_html|safe}
            {if $event->tags}
                <p class="tags">
                    <strong>{str tag=tags}:</strong> {list_tags owner=$event->owner tags=$event->tags}
                </p>
            {/if}
        </div>
    </td>
</tr>
{/foreach}