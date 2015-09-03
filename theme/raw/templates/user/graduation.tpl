{include file="header.tpl"}
	<div style="padding-top:40px">
	    <div class="rbuttons" id="editgraduation">
	        <a class="btn" href="{$WWWROOT}view/blocks.php?id={$viewid}"><span class="btn-edit">{str tag='editgraduation'}</span></a>
	    </div>

		<div id="view" class="cl">
		  	<div id="bottom-pane">
		  	    <div id="column-container">
	            	{$viewcontent|safe}
		      	</div>
		</div>
	</div>
{include file="footer.tpl"}

