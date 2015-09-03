{foreach from=$milestones.data item=milestone}
    <div class="{cycle values='r0,r1'} listrow">
            <h3 class="title"><a href="{$WWWROOT}artefact/milestones/milestone.php?id={$milestone->id}">{$milestone->title}</a></h3>

            <div class="fr milestonestatus">
                <a href="{$WWWROOT}artefact/milestones/edit/index.php?id={$milestone->id}" title="{str tag=edit}" >
                    <img src="{theme_url filename='images/btn_edit.png'}" alt="{str(tag=editspecific arg1=$milestone->title)|escape:html|safe}"></a>
                <a href="{$WWWROOT}artefact/milestones/milestone.php?id={$milestone->id}" title="{str tag=managefacts section=artefact.milestones}">
                    <img src="{theme_url filename='images/btn_configure.png'}" alt="{str(tag=managefactsspecific section=artefact.milestones arg1=$milestone->title)|escape:html|safe}"></a>
                <a href="{$WWWROOT}artefact/milestones/delete/index.php?id={$milestone->id}" title="{str tag=delete}">
                    <img src="{theme_url filename='images/btn_deleteremove.png'}" alt="{str(tag=deletespecific arg1=$milestone->title)|escape:html|safe}"></a>
            </div>

            <div class="detail">{$milestone->description|clean_html|safe}</div>
            {if $milestone->tags}
            <div>{str tag=tags}: {list_tags tags=$milestone->tags owner=$milestone->owner}</div>
            {/if}
            <div class="cb"></div>
    </div>
{/foreach}
