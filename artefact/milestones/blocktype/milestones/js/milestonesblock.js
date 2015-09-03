function rewriteFactTitles(blockid) {
    forEach(
        getElementsByTagAndClassName('a', 'fact-title', 'facttable_' + blockid),
        function(element) {
            disconnectAll(element);
            connect(element, 'onclick', function(e) {
                e.stop();
                var description = getFirstElementByTagAndClassName('div', 'fact-desc', element.parentNode);
                toggleElementClass('hidden', description);
            });
        }
    );
}
function FactPager(blockid) {
    var self = this;
    paginatorProxy.addObserver(self);
    connect(self, 'pagechanged', partial(rewriteFactTitles, blockid));
}

var factPagers = [];

function initNewMilestonesBlock(blockid) {
    if ($('milestones_page_container_' + blockid)) {
        new Paginator('block' + blockid + '_pagination', 'facttable_' + blockid, null, 'artefact/milestones/viewfacts.json.php', null);
        factPagers.push(new FactPager(blockid));
    }
    rewriteFactTitles(blockid);
}
