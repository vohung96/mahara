{if $ownprofile or (!$ownprofile and $showinstance)}
    <style type="text/css">
        .user_info {
            margin-left: 35px;
            font-size: 1.167em;
            line-height: 1.25em;
        }
    </style>
    <div class="user_info" class="rbuttons userviewrbuttons">
        {if $ownprofile}
        <form method="POST" style="margin-bottom: 5px" action="/user/showinstance.php">
            <input type="checkbox" name="enable" value="1" {if $showinstance} checked="checked" {/if}>Public your contact
            <input type="hidden" name="instance_id" value="{$instance_id}">
            <input type="submit" class="btn" value="Save">
        </form>
        {/if}
        <div class="profileinfo">
            <b>{str tag='showemail' section=blocktype.internal/eselmaprofileinfo}:</b> {$eselma_email}<br>
            <b>{str tag='showmobilenumber' section=blocktype.internal/eselmaprofileinfo}:</b> {$eselma_mobilenumber}<br>
            <b>{str tag='showaddress' section=blocktype.internal/eselmaprofileinfo}:</b> {$eselma_address}<br>
        </div>

        {if $ownprofile}
            <div style="text-align: right; padding-right:5px">
                <a href="{$WWWROOT}artefact/internal/index.php?fs=contact" class="btn"style="padding: 2px;font-size: 15px;">{str tag='editcontact' section=blocktype.internal/eselmaprofileinfo}</a>
            </div>
        {/if}

        <div class="socialprofiles">
            <b>{str tag=socialprofiles section=artefact.internal}</b><br>
            {foreach from=$socialprofiles item=s_profile}
                <li>
                    <strong>{$s_profile->description}:</strong>
                    {if substr($s_profile->title, 0, 4 ) == 'http'}
                        <a href="{$s_profile->title}" title="{$s_profile->note}" target="_blank">{$s_profile->title}</a>
                    {else}
                        <a href="http://{$s_profile->title}" title="{$s_profile->note}" target="_blank">{$s_profile->title}</a>
                    {/if}
                </li>
            {/foreach}
        </div>

        {if $ownprofile}
            <div style="text-align: right; padding-right:5px">
                <a href="{$WWWROOT}artefact/internal/index.php?fs=social" class="btn"style="padding: 2px;font-size: 15px;">{str tag='editsocial' section=blocktype.internal/eselmaprofileinfo}</a>
            </div>
        {/if}
    </div>
{else}
    <style type="text/css">
        div.bt-eselmaprofileinfo {
            display: none;
        }
    </style>
{/if}