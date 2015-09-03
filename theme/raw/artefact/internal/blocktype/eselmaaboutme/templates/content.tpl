<style type="text/css">
    .user_info {
        list-style-type: none;
        margin-left: 35px;
        font-size: 1.167em;
        line-height: 1.25em;
    }
</style>
<div class="user_info" "rbuttons userviewrbuttons">
    <div>
        <b>{str tag='showlastname' section=blocktype.internal/eselmaaboutme}:</b> {$eselma_firstname}<br>
        <b>{str tag='showfirstname' section=blocktype.internal/eselmaaboutme}:</b> {$eselma_lastname}<br>
        <b>{str tag='studentid' section=blocktype.internal/eselmaaboutme}:</b> {$eselma_studentid}<br>
        <b>{str tag='showpreferredname' section=blocktype.internal/eselmaaboutme}:</b> {$eselma_preferredname}<br>
        <b>{str tag='showintroduction' section=blocktype.internal/eselmaaboutme}:</b> {$eselma_introduction|clean_html|safe}
    </div>
    {if $ownprofile}
        <div style="text-align: right; padding-right:5px">
            <a href="{$WWWROOT}artefact/internal/index.php?fs=aboutme" class="btn">{str tag='update' section=blocktype.internal/eselmaaboutme}</a>
        </div>
    {/if}
</div>
