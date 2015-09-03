function rewriteEventTitles(blockid) {
    forEach(
        getElementsByTagAndClassName('a', 'event-title', 'eventtable_' + blockid),
        function(element) {
            disconnectAll(element);
            connect(element, 'onclick', function(e) {
                e.stop();
                var description = getFirstElementByTagAndClassName('div', 'event-desc', element.parentNode);
                toggleElementClass('hidden', description);
            });
        }
    );
}
function EventPager(blockid) {
    var self = this;
    paginatorProxy.addObserver(self);
    connect(self, 'pagechanged', partial(rewriteEventTitles, blockid));
}

var eventPagers = [];

function initNewEventsBlock(blockid) {
    if ($('events_page_container_' + blockid)) {
        new Paginator('block' + blockid + '_pagination', 'eventtable_' + blockid, null, 'artefact/calendar/viewevents.json.php', null);
        eventPagers.push(new EventPager(blockid));
    }
    rewriteEventTitles(blockid);
}
