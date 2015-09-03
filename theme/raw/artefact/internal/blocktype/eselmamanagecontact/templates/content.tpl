<a href="javascript:void(0)" class="list_title">
    <span class="minus_title">-</span><span class="plus_title">+</span> {str tag="search_friend" section="blocktype.internal/eselmamanagecontact"}
</a>
<div class="search_friend" >
    <div class="friend_search_box">
        <form onsubmit="function_search_friend();
                return false;" name="search_group">
            <span id="search_query_friend_container" class="text">
                <label class="accessible-hidden" for="search_query">{$search_friend_title}</label>
                <input type="text" class="text autofocus" id="search_friend_text" name="query" tabindex="0" aria-describedby="" value="">
            </span>
            <span id="search_search_friend_container" class="submit">
                <input type="submit" class="submit" id="search_search" name="search" tabindex="0" aria-describedby="" value="{$search_friend_title}">
            </span>
        </form>
    </div>

    <div class="friend_loading" style="text-align: center">
        <img src='/theme/raw/static/images/loading.gif' alt='loading'>
    </div>

    <div class="search_friend_result">
    </div>
</div>

<br>

<a href="javascript:void(0)" class="account_manage_title">
    <span class="minus_title">-</span><span class="plus_title">+</span> {str tag="contact_settings" section="blocktype.internal/eselmamanagecontact"}
</a>
<div class="success_message">
    <span>Updated friends control</span>
</div>
<div class="contact_settings">
    <form onsubmit="function_user_control();
            return false;" id="user_control">
        <table cellspacing="0">
            <tbody>
                <tr id="friendscontrol_friendscontrol_container" class="required radio">
                    <th></th>
                    <td>
                        {foreach from=$user_control_options key=option_key item=option_value}
                            <div class="radio">
                                <input type="radio" class="required radio friendscontrol" id="friendscontrol_{$option_key}" name="friendscontrol" tabindex="0" aria-describedby="" value="{$option_key}" {if $option_key == $current_user_control_options}checked="checked"{/if}>
                                <label for="friendscontrol_{$option_key}">{$option_value}</label>
                            </div>
                            <br>
                        {/foreach}
                    </td>
                </tr>
                <tr id="friendscontrol_submit_container" class="submit">
                    <th></th>
                    <td>
                        <input type="submit" class="submit" id="friendscontrol_submit" name="submit" tabindex="0" aria-describedby="" value="Save">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
<style type="text/css">
    a.list_title, a.account_manage_title {
        margin-left: 25px;
        font-size: 1.167em;
        line-height: 1.25em;
    }
    .search_friend, .minus_title{
        display: none;
    }
    .search_friend_result, .friend_search_box, .contact_settings, .success_message {
        margin-left: 35px;
    }
    .success_message, .contact_settings {
        display:none;
    }
    .search_friend_result, .friend_loading {
        display:none;
        margin-top: 10px;
    }
    .search_friend_result {
        font-size: 1.167em;
        line-height: 1.25em;
    }
    .search_friend_result ul {
        list-style-type: none;
    }
</style>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".list_title").click(function () {
            jQuery(this).next().toggle();
            jQuery(this).children("span").toggle();
        });
        jQuery(".account_manage_title").click(function () {
            jQuery(this).next().next().toggle();
            jQuery(this).children("span").toggle();
        });
    });


    function function_search_friend() {
        var friend_query = jQuery("#search_friend_text").val();
        jQuery("div.friend_loading").css('display', 'block');
        jQuery("div.search_friend_result").css('display', 'none');
        jQuery.ajax({
            url: "{$WWWROOT}user/searchfriend.php",
            data: {
                'query': friend_query
            },
            success: function (result) {
                if (result) {
                    jQuery("div.search_friend_result").html(result);
                } else {
                    jQuery("div.search_friend_result").html('<span>{str tag='searchnotfound'}</span>');
                }
                jQuery("div.search_friend_result").css('display', 'block');
                jQuery("div.friend_loading").css('display', 'none');
            },
        });
    }

    function function_user_control() {
        var user_control_value = jQuery("input[name=friendscontrol]:checked", "#user_control").val();
        jQuery.ajax({
            url: "{$WWWROOT}user/updateusercontrol.php",
            data: {
                'user_control_value': user_control_value
            },
            success: function (result) {
                jQuery("div.success_message").css('display', 'block');
                setTimeout(function () {
                    jQuery("div.success_message").css('display', 'none');
                }, 2500);
            },
        });
    }
</script>