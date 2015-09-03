<div class="search_box">
    <form onsubmit="function_search_group(); return false;" name="search_group">
        <span id="search_query_container" class="text">
            <label class="accessible-hidden" for="search_query">{$filter_elements["query"]["title"]}</label>
            <input type="text" class="text autofocus" id="search_query" name="query" tabindex="0" aria-describedby="" value="">
        </span>
        <span id="search_filter_container" class="select">
            <label class="accessible-hidden" for="search_filter">{$filter_elements["filter"]["title"]}</label>
            <select class="select" id="search_filter" name="filter" tabindex="0" aria-describedby="" style="">
                {foreach from=$filter_elements["filter"][options] key=key item=value}
                    <option value="{$key}" {if $key==$filter_elements['filter']['defaultvalue']}selected="selected"{/if}>{$value}</option>
                {/foreach}
            </select>
        </span>
        <span id="search_search_container" class="submit">
            <input type="submit" class="submit" id="search_search" name="search" tabindex="0" aria-describedby="" value="{$filter_elements['search']['value']}">
        </span>
    </form>
</div>

<div class="loading" style="text-align: center">
    <img src='/theme/raw/static/images/loading.gif' alt='loading'>
</div>

<div class="search_result">
</div>

<style type="text/css">
    .search_result, .search_box {
        margin-left: 25px;
    }
    .search_result, .loading {
        display:none;
        margin-top: 10px;
    }
    .search_result ul {
        list-style-type: none;
        font-size: 1.167em;
        line-height: 1.25em;
    }
    .search_result span {
        font-size: 1.167em;
        line-height: 1.25em;
    }
</style>

<script type="text/javascript">
    function function_search_group() {
        var group_filter = jQuery("#search_filter").val();
        var group_query = jQuery("#search_query").val();
        jQuery("div.loading").css('display', 'block');
        jQuery("div.search_result").css('display', 'none');
        jQuery.ajax({
            url: "{$WWWROOT}group/searchgroup.php",
            data: {
                'filter': group_filter,
                'query': group_query
            },
            success: function(result) {
                if (result) {
                    jQuery("div.search_result").html(result);
                } else {
                    jQuery("div.search_result").html('<span>No search results found</span>');
                }
                jQuery("div.search_result").css('display', 'block');
                jQuery("div.loading").css('display', 'none');
            },
        });
    }
</script>