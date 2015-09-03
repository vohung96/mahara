<div id="containerX">
    <div id="top-wrapper">
      <div class="viewheader top">
        <div class="fl">
          <a class="small-logo" href="{$WWWROOT}"><img src="{theme_url filename='images/site-logo-small.png'}" alt="{$sitename}"></a>
        </div>
      </div>
      <div class="viewheader">

{if $collection}
        <div class="left cb" id="collection"><strong>{$microheadertitle|safe}</strong> : {include file=collectionnav.tpl}</div>
{else}
        <div class="center cb title">{$microheadertitle|safe}</div>
{/if}
      </div>
    </div>
    <div id="main-wrapper">
        <div class="main-column">
            <div id="main-column-container">
