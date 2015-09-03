{if count($entrymilestones)}
<div class="section fullwidth">
    <h2>{str tag=milestone section=artefact.milestones}</h2>
</div>
{foreach from=$entrymilestones item=milestone}
<div class="{cycle name=rows values='r0,r1'} listrow">
    <div id="entrymilestone" class="indent1">
        <div class="importcolumn importcolumn1">
            <h3 class="title">
            {if $milestone.description}<a class="milestonetitle" href="" id="{$milestone.id}">{/if}
            {$milestone.title|str_shorten_text:80:true}
            {if $milestone.description}</a>{/if}
            </h3>
            <div id="{$milestone.id}_desc" class="detail hidden">{$milestone.description|clean_html|safe}</div>
            {if $milestone.tags}
            <div class="tags">
                <strong>{str tag=tags}:</strong> {list_tags owner=0 tags=$milestone.tags}
            </div>
            {/if}
            <div class="facts">
                <strong>{str tag=facts section=artefact.milestones}:</strong>
                {if count($milestone.entryfacts)}<a class="showfacts" href="" id="{$milestone.id}">{/if}
                {str tag=nfacts section=artefact.milestones arg1=count($milestone.entryfacts)}
                {if count($milestone.entryfacts)}</a>{/if}
            </div>
        </div>
        <div class="importcolumn importcolumn2">
            {if $milestone.duplicateditem}
            <div class="duplicatedmilestone">
                <strong>{str tag=duplicatedmilestone section=artefact.milestones}:</strong> <a class="showduplicatedmilestone" href="" id="{$milestone.duplicateditem.id}">{$milestone.duplicateditem.title|str_shorten_text:80:true}</a>
                <div id="{$milestone.duplicateditem.id}_duplicatedmilestone" class="detail hidden">{$milestone.duplicateditem.html|clean_html|safe}</div>
            </div>
            {/if}
            {if $milestone.existingitems}
            <div class="existingmilestones">
                <strong>{str tag=existingmilestones section=artefact.milestones}:</strong>
                   {foreach from=$milestone.existingitems item=existingitem}
                   <a class="showexistingmilestone" href="" id="{$existingitem.id}">{$existingitem.title|str_shorten_text:80:true}</a><br>
                   <div id="{$existingitem.id}_existingmilestone" class="detail hidden">{$existingitem.html|clean_html|safe}</div>
                   {/foreach}
            </div>
            {/if}
        </div>
        <div class="importcolumn importcolumn3">
            {foreach from=$displaydecisions key=opt item=displayopt}
                {if !$milestone.disabled[$opt]}
                <input id="decision_{$milestone.id}_{$opt}" class="milestonedecision" id="{$milestone.id}" type="radio" name="decision_{$milestone.id}" value="{$opt}"{if $milestone.decision == $opt} checked="checked"{/if}>
                <label for="decision_{$milestone.id}_{$opt}">{$displayopt}<span class="accessible-hidden">({$milestone.title})</span></label><br>
                {/if}
            {/foreach}
        </div>
        <div class="cb"></div>
    </div>
    <div id="{$milestone.id}_facts" class="indent2 hidden">
    {foreach from=$milestone.entryfacts item=fact}
        <div id="facttitle_{$fact.id}" class="{cycle name=rows values='r0,r1'} listrow">
            <div class="importcolumn importcolumn1">
                <h4 class="title"><a class="facttitle" href="" id="{$fact.id}">{$fact.title|str_shorten_text:80:true}</a></h4>
                <div id="{$fact.id}_desc" class="detail hidden">
                    {$fact.description|clean_html|safe}
                </div>
                <div class="completiondate"><strong>{str tag='completiondate' section='artefact.milestones'}:</strong> {$fact.completiondate}</div>
                {if $fact.completed == 1}<div class="completed">{str tag=completed section=artefact.milestones}</div>{/if}
            </div>
            <div class="importcolumn importcolumn2">
            &nbsp;
            </div>
            <div class="importcolumn importcolumn3">
                {foreach from=$displaydecisions key=opt item=displayopt}
                    {if !$fact.disabled[$opt]}
                    <input id="decision_{$fact.id}_{$opt}" class="factdecision" type="radio" name="decision_{$fact.id}" value="{$opt}"{if $fact.decision == $opt} checked="checked"{/if}>
                    <label for="decision_{$fact.id}_{$opt}">{$displayopt}<span class="accessible-hidden">({$fact.title})</span></label><br>
                    {/if}
                {/foreach}
            </div>
            <div class="cb"></div>
        </div>
    {/foreach}
    </div>
    <div class="cb"></div>
</div>
{/foreach}
<script type="application/javascript">
    jQuery(function() {
        jQuery("a.milestonetitle").click(function(e) {
            e.preventDefault();
            jQuery("#" + this.id + "_desc").toggleClass("hidden");
        });
        jQuery("a.facttitle").click(function(e) {
            e.preventDefault();
            jQuery("#" + this.id + "_desc").toggleClass("hidden");
        });
        jQuery("a.showduplicatedmilestone").click(function(e) {
            e.preventDefault();
            jQuery("#" + this.id + "_duplicatedmilestone").toggleClass("hidden");
        });
        jQuery("a.showexistingmilestone").click(function(e) {
            e.preventDefault();
            jQuery("#" + this.id + "_existingmilestone").toggleClass("hidden");
        });
       jQuery("a.showfacts").click(function(e) {
            e.preventDefault();
            jQuery("#" + this.id + "_facts").toggleClass("hidden");
        });
        jQuery("input.milestonedecision").change(function(e) {
            e.preventDefault();
            if (this.value == '1') {
            // The import decision for the milestone is IGNORE
            // Set decision for its facts to be IGNORE as well
                jQuery("#" + this.id + "_facts input.factdecision[value=1]").prop('checked', true);
            }
        });
    });
</script>
{/if}
