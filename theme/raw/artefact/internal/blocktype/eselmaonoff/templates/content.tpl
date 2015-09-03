<style type="text/css">
    .user_info {
        margin-left: 25px;
        font-size: 1.167em;
        line-height: 1.25em;
    }
    .contacts {
        display: none;
    }
    .contacts ul {
        list-style-type: none;
    }
    .img-message, .img-group {
        float: right;
        margin-right: 5px;
        padding-top: 3px;
    }
    .img-remove {
        float: right;
        margin-right: 10px;
        padding-top: 3px;
    }
</style>

<div class="user_info">
    <div class="online_contact">
        <a href="javascript:void(0)" class="show_list_contacts"><b>{str tag="contactsonline"}</b> ({$eselma_count_online})</a>
        <div class="contacts">
            <ul>
            {if $eselma_get_online}
                {foreach from=$eselma_get_online item=usr_online} 
                    <li>
                        <a href="{$WWWROOT}user/view.php?id={$usr_online->id}">{$usr_online->firstname} {$usr_online->lastname}</a>
                        <a href="{$WWWROOT}user/removefriend.php?id={$usr_online->id}&returnto=view" class="img-remove">
                            <img src="/theme/raw/static/images/delete_small.png" title="Remove friend" alt="Remove friend">
                        </a>
                        <a onclick="showGroupBox(event, {$usr_online->id})" class="img-group">
                            <img src="/theme/raw/static/images/edit_small.png" title="Invite to group" alt="Invite to group">
                        </a>
                        <a href="{$WWWROOT}user/sendmessage.php?id={$usr_online->id}&returnto=view" class="img-message">
                            <img src="/theme/raw/static/images/message_small.png" title="Send message" alt="Send message">
                        </a>
                    </li>
                {/foreach}
            {/if}
            </ul>
        </div>
    </div>
    <div class="offline_contact">
        <a href="javascript:void(0)" class="show_list_contacts"><b>{str tag="contactsoffline"}</b> ({$eselma_count_offline})</a>
        <div class="contacts">
            <ul>
            {if $eselma_get_offline}
                {foreach from=$eselma_get_offline item=usr_offline} 
                    <li>
                        <a href="{$WWWROOT}user/view.php?id={$usr_offline->id}">{$usr_offline->firstname} {$usr_offline->lastname}</a>
                        <a href="{$WWWROOT}user/removefriend.php?id={$usr_offline->id}&returnto=view" class="img-remove">
                            <img src="/theme/raw/static/images/delete_small.png" title="Remove friend" alt="Remove friend">
                        </a>
                        <a onclick="showGroupBox(event, {$usr_offline->id})" class="img-group">
                            <img src="/theme/raw/static/images/edit_small.png" title="Invite to group" alt="Invite to group">
                        </a>
                        <a href="{$WWWROOT}user/sendmessage.php?id={$usr_offline->id}&returnto=view" class="img-message">
                            <img src="/theme/raw/static/images/message_small.png" title="Send message" alt="Send message">
                        </a>
                    </li>
                {/foreach}
            {/if}
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(".show_list_contacts").click(function() {
            jQuery(this).next().toggle();
        });
    });
</script>