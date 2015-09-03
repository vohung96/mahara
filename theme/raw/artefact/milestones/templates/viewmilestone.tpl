{if $tags}<p class="tags s"><strong>{str tag=tags}:</strong> {list_tags owner=$owner tags=$tags}</p>{/if}
<table id="facttable">
    <thead>
        <tr>
            <th class="c1">{str tag='completiondate' section='artefact.milestones'}</th>
            <th class="c2">{str tag='title' section='artefact.milestones'}</th>
            <th class="c3">{str tag='completed' section='artefact.milestones'}</th>
        </tr>
    </thead>
    <tbody>
    {$facts.tablerows|safe}
    </tbody>
</table>
<div id="milestones_page_container">{$facts.pagination|safe}</div>
{if $license}
<div class="resumelicense">
{$license|safe}
</div>
{/if}
<script>
{literal}
function rewriteFactTitles() {
    forEach(
        getElementsByTagAndClassName('a', 'fact-title','facttable'),
        function(element) {
            connect(element, 'onclick', function(e) {
                e.stop();
                var description = getFirstElementByTagAndClassName('div', 'fact-desc', element.parentNode);
                toggleElementClass('hidden', description);
            });
        }
    );
}

addLoadEvent(function() {
    {/literal}{$facts.pagination_js|safe}{literal}
    removeElementClass('milestones_page_container', 'hidden');
});

function FactPager() {
    var self = this;
    paginatorProxy.addObserver(self);
    connect(self, 'pagechanged', rewriteFactTitles);
}
var factPager = new FactPager();
addLoadEvent(rewriteFactTitles);
{/literal}
</script>
