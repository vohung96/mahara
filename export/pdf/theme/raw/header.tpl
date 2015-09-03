<div id="container">
    <div id="top-wrapper"><h1 id="site-logo"><a href="{$WWWROOT}"><img src="{theme_url filename='images/site-logo.png'}" alt="{$sitename}"></a></h1>
		<div class="cb"></div>
    </div>
    <table id="main-wrapper">
        <tbody>
            <tr>
{if $SIDEBARS && $SIDEBLOCKS.left}
                <td id="left-column" class="sidebar">
{include file="sidebar.tpl" blocks=$SIDEBLOCKS.left}
                </td>
{/if}
                <td id="main-column" class="main-column">
                    <div id="main-column-container">

{if isset($PAGEHEADING)}                    <h1>{$PAGEHEADING}{if $PAGEHELPNAME}<span class="page-help-icon">{$PAGEHELPICON|safe}</span>{/if}</h1>
{/if}
{if $SUBPAGENAV}{* Tabs and beginning of page container for group info pages *}                        <ul class="in-page-tabs">
{foreach from=$SUBPAGENAV item=item}
                            <li><a {if $item.selected}class="current-tab" {/if}href="{$WWWROOT}{$item.url}">{$item.title}</a></li>
{/foreach}
                        </ul>
                        <div class="subpage rel">
{/if}
