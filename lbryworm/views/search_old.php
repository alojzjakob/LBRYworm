<?php
global $LBRYworm;
?>
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
if($results['data']!==null){
    if(count($results['data'])>0){
        foreach($results['data'] as $r){
            $lbryURI='lbry://'.$r['name'].':'.$r['claim_id'];
            $url_resp = $LBRYworm->ChainQuery->get_claim_streaming_url($lbryURI);
            ?>
            <div class="search_result_item">
                <div class="search_result_item_thumb" style="<?php
                    if($r['thumbnail_url']){
                        echo 'background-image:url('.$r['thumbnail_url'].');';
                    }else{
                        echo 'background-image:url('.site_url().'/wp-content/plugins/lbryworm/css/lbryworm-placeholder.png);';
                        echo 'background-color:#296c57;';
                        echo 'background-size:contain!important;';
                    }
                ?>">
                </div>
                <div class="search_result_item_link">
                    <span class="filetype"><?php echo explode('/',$r['content_type'])[1]; ?></span>
                    <a href="<?php echo $url_resp; ?>" target="_blank">
                        <?php echo $r['title']; ?>
                    </a>
                    <a href="https://odysee.com/<?php echo $r['name']; ?>:<?php echo $r['claim_id']; ?>" target="_blank" title="Open on Odysee" class="f-right">
                        <img class="external" src="/wp-content/plugins/lbryworm/css/open_on_odysee.png" style="height:25px;vertical-align:middle;margin-left:20px;">
                    </a>
                    <a href="lbry://<?php echo $results['channels'][$r['publisher_id']]['name']; ?>#<?php echo $r['publisher_id']; ?>/<?php echo $r['name']; ?>#<?php echo $r['claim_id']; ?>" target="_blank" title="Open via LBRY app" class="f-right">
                        <img class="external" src="/wp-content/plugins/lbryworm/css/open_on_lbrytv.png" style="height:25px;vertical-align:middle;margin-left:20px;">
                    </a>
                </div>
                <div class="search_result_item_description">
                    <?php
                        echo wpautop($r['description']);
                    ?>
                </div>
                <div class="clearfix"></div>
            </div>
            <?php
        }
    }
}else{
    if(isset($results['error']) and $results['error']!==null){
        echo $results['error'];
    }else{
        if(isset($_POST['search_query']) and $_POST['search_query']!==''){
            echo 'No books found for "'.$_POST['search_query'].'"...';
        }else{
            // nothing searched
            include dirname(__FILE__).'/home.php';
        }
    }
}
//pre_var_dump($results);
?>

