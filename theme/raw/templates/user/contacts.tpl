{include file="header.tpl"}
<div id="view" class="cl">
    <div id="searchfriend">
        
    </div>
    <div id="bottom-pane">
        <div id="column-container">
            {$viewcontent|safe}
            <div class="cb"></div>
        </div>

        <div id="chat-container">
            <div id="chat-row">
                <div id="list-chat">
                    <h2>{str tag="chat"}</h2>
                    <a href="javascript:void(0)" class="show_list_chat">{str tag='startchat'}</a>
                    <div class="conversation">
                        <ul>
                            {foreach from=$list_friend item=friend}
                                <li>
                                    <a href="javascript:void(0)" onclick="javascript:chatWith('{$friend->username}')">Chat with {$friend->firstname} {$friend->lastname}</a>
                                </li>
                            {/foreach}
                        </ul>
                    </div>	
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    #chat-row {
        width: 98.2%;
        vertical-align: top;
        float: left;
        margin: 0;
        padding: 10px 0.9% 10px 0.9%;
        position: relative;
    }
    #list-chat {
        margin: 0;
        padding: 10px 0;
        position: relative;
        border: 2px solid #696969;
        margin-bottom: 5px;
    }
    #list-chat h2 {
        margin: 0;
        font-size: 1.5em;
        padding-bottom: 3px;
        color: #211C00;
        margin-bottom: 3px;
        margin-left: 5px;
    }
    .show_list_chat, .conversation {
        margin-left: 15px;
    }
    .conversation ul {
        list-style-type: none;
    }
    .conversation {
        display: none;
    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".show_list_chat").click(function () {
            jQuery(this).next().toggle();
        });
    });
</script>
{include file="footer.tpl"}

