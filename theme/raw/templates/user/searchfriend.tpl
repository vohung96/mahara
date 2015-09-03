{if $user_data}
    <style type="text/css">
        .img-message {
            float: right;
            margin-right: 5px;
            padding-top: 3px;
        }
        .img-remove, .img-friend, .img-group {
            float: right;
            margin-right: 10px;
            padding-top: 3px;
        }
    </style>

    <h3>{str tag='resultsearch' section=blocktype.internal/eselmamanagecontact}:</h3>
    <ul> 
        {foreach from=$user_data item=usr} 
            <li>
                <a href="{$usr.url}">{$usr.firstname} {$usr.lastname}</a>
                {if $usr.is_friend == true}
                    <a href="{$WWWROOT}user/removefriend.php?id={$usr.id}&returnto=view" class="img-remove">
                        <img src="/theme/raw/static/images/delete_small.png" title="Remove friend" alt="Remove friend">
                    </a>
                {else}
                    <a href="{$WWWROOT}user/requestfriendship.php?id={$usr.id}&returnto=view" class="img-friend">
                        <img src="/theme/raw/static/images/friend_small.png" title="Request friendship" alt="Request friendship">
                    </a>
                {/if}
                <a onclick="showGroupBox(event, {$usr.id})" class="img-group">
                    <img src="/theme/raw/static/images/edit_small.png" title="Invite to group" alt="Invite to group">
                </a>
                <a href="{$WWWROOT}user/sendmessage.php?id={$usr.id}&returnto=view" class="img-message">
                    <img src="/theme/raw/static/images/message_small.png" title="Send message" alt="Send message">
                </a>
            </li>
        {/foreach}
    </ul>
{/if}