{if $MAINNAV}
    <div id="main-nav" class="{if $ADMIN || $INSTITUTIONALADMIN || $STAFF || $INSTITUTIONALSTAFF}{if $DROPDOWNMENU}dropdown-adminnav {else}adminnav {/if}{/if}main-nav">

        <h3 class="rd-nav-title">
            <a href="#">
                {if $ADMIN || $INSTITUTIONALADMIN || $STAFF || $INSTITUTIONALSTAFF}{str tag=admin} {/if}
                {str tag=menu}
                <span class="rd-arrow"></span>
            </a>
        </h3>

        <ul id="{if $DROPDOWNMENU}dropdown-nav{else}nav{/if}">
            {strip}

                {foreach from=$MAINNAV item=item}

                    <li class="{if $item.path}{$item.path}{else}dashboard{/if}{if $item.selected} selected{/if}{if $DROPDOWNMENU} dropdown-nav-home{/if}">
                        <span>
                            <a href="{$WWWROOT}{$item.url}"{if $item.accesskey} accesskey="{$item.accesskey}"{/if} class="{if $item.path}{$item.path}{else}dashboard{/if}"{if $item.path=="MeineKontakte"}style="width:120px;padding-top: 3px;"{/if}>
                                {if $item.accessibletitle && !$DROPDOWNMENU}
                                    <span aria-hidden="true" role="presentation"></span>
                                {/if }
                                {$item.title}

                                {if $item.accessibletitle && !$DROPDOWNMENU}
                                    <span class="accessible-hidden">({$item.accessibletitle})</span>
                                {/if}
                                {if $DROPDOWNMENU && $item.submenu}
                                    <span class="accessible-hidden">({str tag=dropdownmenu})</span>
                                {/if}
                            </a>
                        </span>
                        {if $item.submenu}
                            <ul class="{if $DROPDOWNMENU}dropdown-sub {/if}rd-subnav">
                                {strip}
                                    {foreach from=$item.submenu item=subitem}
                                        <li class="{if $subitem.selected}selected {/if}{if $subitem.submenu}has-sub {/if}">
                                            <span>
                                                <a href="{$WWWROOT}{$subitem.url}"{if $subitem.accesskey} accesskey="{$subitem.accesskey}"{/if}>{$subitem.title}</a>
                                            </span>
                                            {if $subitem.submenu}
                                                <ul class="dropdown-tertiary">
                                                    {foreach from=$subitem.submenu item=tertiaryitem}
                                                        <li{if $tertiaryitem.selected} class="selected"{/if}>
                                                            <span>
                                                                <a href="{$WWWROOT}{$tertiaryitem.url}"{if $tertiaryitem.accesskey} accesskey="{$tertiaryitem.accesskey}"{/if}>{$tertiaryitem.title}</a>
                                                            </span>
                                                        </li>
                                                    {/foreach}
                                                </ul>
                                            {/if}
                                        </li>
                                    {/foreach}
                                {/strip}
                                <div class="cl"></div>
                            </ul>
                        {/if}
                    </li>
                {/foreach}

                {if $ADMIN || $INSTITUTIONALADMIN || $STAFF || $INSTITUTIONALSTAFF}
                    <li class="returntosite"><span><a href="{$WWWROOT}" accesskey="h" class="return-site">{str tag="returntosite"}</a></span></li>
                            {elseif $USER->get('admin')}
                    <li class="siteadmin"><span><a href="{$WWWROOT}admin/" accesskey="a" class="admin-site">{str tag="administration"}</a></span></li>
                            {elseif $USER->is_institutional_admin()}
                    <li class="instituteadmin"><span><a href="{$WWWROOT}admin/users/search.php" accesskey="a" class="admin-user">{str tag="administration"}</a></span></li>
                            {elseif $USER->get('staff')}
                    <li class="siteinfo"><span><a href="{$WWWROOT}admin/users/search.php" accesskey="a" class="admin-user">{str tag="siteinformation"}</a></span></li>
                            {elseif $USER->is_institutional_staff()}
                    <li class="instituteinfo"><span><a href="{$WWWROOT}admin/users/search.php" accesskey="a" class="admin-user">{str tag="institutioninformation"}</a></span></li>
                            {/if}
                        {/strip}
        </ul>
    </div>
    {if $DROPDOWNMENU}
    {else }

        <div id="sub-nav">
            {if $SELECTEDSUBNAV}
                {strip}                                          
                    <ul class="{if $eselma_breadcrum!=''}{$eselma_breadcrum}{/if}">
                        {foreach from=$SELECTEDSUBNAV item=item}
                            <li{if $item.selected} class="selected"{/if}>
                                <span>
                                    <a href="{$WWWROOT}{$item.url}"{if $item.accesskey} accesskey="{$item.accesskey}"{/if}>{$item.title}</a>
                                </span>
                                {if $item.submenu && $item.selected}
                                    {assign var=tertiarymenu value=$item.submenu}
                                {/if}
                            </li>
                        {/foreach}
                    </ul>
                {/strip}
            {/if}
            <div class="cb"></div>
        </div>
        {if $tertiarymenu}
            <div id="tertiary-nav">
                <ul>
                    {strip}
                        {foreach from=$tertiarymenu item=tertiaryitem}
                            <li{if $tertiaryitem.selected} class="selected"{/if}>
                                <span>
                                    <a href="{$WWWROOT}{$tertiaryitem.url}"{if $tertiaryitem.accesskey} accesskey="{$tertiaryitem.accesskey}"{/if}>{$tertiaryitem.title}</a>
                                </span>
                            </li>
                        {/foreach}
                    {/strip}
                </ul>
                <div class="cb"></div>
            </div>
        {/if}
    {/if}

    <!-- Breadcrum for homepage -->
    {if $eselma_breadcrum=="home"}
        <div class="eselma_breadcrum home">
            <div class="eselma_breadcrum_date">
                <script type="text/javascript">
                    var lang = document.getElementsByTagName("html")[0].getAttribute("lang");
                    function date_time(id) {
                        date = new Date;
                        year = date.getFullYear();
                        month = date.getMonth();
                        months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
                        d = date.getDate();
                        day = date.getDay();
                        days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
                        h = date.getHours();
                        if (h < 10)
                        {
                            h = "0" + h;
                        }
                        m = date.getMinutes();
                        if (m < 10)
                        {
                            m = "0" + m;
                        }
                        s = date.getSeconds();
                        if (s < 10)
                        {
                            s = "0" + s;
                        }
                        if (lang == "de") {
                            result = d + '.' + month + '.' + year + ', ' + h + ':' + m + ':' + s;
                        } else if (lang == "en") {
                            result = months[month] + ' ' + d + ' ' + year + ', ' + h + ':' + m + ':' + s;
                        }
                        document.getElementById(id).innerHTML = result;
                        setTimeout('date_time("' + id + '");', '1000');
                        return true;
                    }
                </script>
                <span id="date_time"></span>
                <script type="text/javascript">window.onload = date_time('date_time');</script>
            </div>
            <div class="eselma_breadcrum_lastlogin">
                {if $eselma_lastlogin} 
                    <b>{str tag="lastlogin"}</b>: {$eselma_lastlogin}
                {/if}
            </div>
            <div class="eselma_breadcrum_main">
                <b>{str tag="home"}</b>
            </div>
        </div>      
    {/if}
    <!-- Breadcrum for myprofile -->
    {if $eselma_breadcrum=="myprofile"}
        <div class="eselma_breadcrum myprofile">
            <div class="eselma_breadcrum_main">
                <b>{str tag="myprofile"}</b>
            </div>        
        </div>  
    {/if}

    <!-- Breadcrum for graduation -->
    {if $eselma_breadcrum=="mygraduation"}
        <div class="eselma_breadcrum mygraduation">
            <div class="eselma_breadcrum_main">
                <b>{str tag="mygraduation"}</b>
            </div>        
        </div>  
    {/if}

    <!-- Breadcrum for calendar -->
    {if $eselma_breadcrum=="calendar"}
        <div class="eselma_breadcrum calendar">
            <div class="eselma_breadcrum_main">
                <b>{str tag="mycalendar"}</b>
            </div>        
        </div>  
    {/if}
    <!-- Breadcrum for portfolio -->
    {if $eselma_breadcrum=="portfolio"}
        <div class="eselma_breadcrum portfolio">
            <div class="eselma_breadcrum_main">
                <div><b>{str tag="portfolio"}</b></div>
            </div>        
        </div>  
    {/if}
{/if}
