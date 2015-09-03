<style type="text/css">
    img.profile_icon {
        width: 90%;
        margin-left: 5%;
    }
</style>
{if $ownprofile}
<a href="/artefact/file/profileicons.php" title="{str tag="editprofileicon" section="artefact.file"}">
{/if}
	<img class="profile_icon" src="{profile_icon_url user=$user maxheight=1024 maxwidth=1024}" alt="{str tag="editprofileicon" section="artefact.file"}">
{if $ownprofile}
</a>
{/if}