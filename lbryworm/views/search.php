<?php
global $LBRYworm;
?>
<div class="lbryworm_content">
    <form method="post" action="/" class="search" id="search_form">
        <div>
            <input type="text" autocomplete="off" name="search_query" id="search_query" value="<?php echo $_POST['search_query']??''; ?>" placeholder="Enter your search terms..."><button type="submit" name="do_search">
                <i class="fa fa-search looking_glass"></i>
                <i class="fas fa-spinner fa-pulse search_loading" style="display:none;"></i>
            </button>
    <!--         <input type="checkbox" id="ajax_search" name="ajax_search"> -->
        </div>
    </form>

    <div id="search_results"></div>

    <?php

    include dirname(__FILE__).'/home.php';

    ?>

    <div id="infinite_scroll_loader" style="display:none;">
        <i class="fas fa-spinner fa-pulse"></i>
    </div>
    <div id="nothing_found" style="display:none;">
        <h3>Nothing found for "<span id="nothing_found_term"></span>"...</h3>
        <h4 id="nothing_found_error"></h4>
        <p>Try searching with different keywords or exact title or author</p>
    </div>
</div>
