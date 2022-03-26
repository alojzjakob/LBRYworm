<?php
include(dirname(__FILE__).'/../../../../../wp-blog-header.php');

global $LBRYworm;

if(is_user_logged_in()){

$rooms=$LBRYworm->rooms->get_rooms();
?>
<div>
    <h4>Choose a room to list shelves</h4>
    <?php
    $norooms=true;
    foreach($rooms as $r){
        $norooms=false;
        ?>
            <p>
                <a href="<?php echo site_url(); ?>/wp-content/plugins/lbryworm/views/modal/book_add_shelf.php?claim_id=<?php echo $_GET['claim_id']; ?>&room_id=<?php echo $r->id; ?>" rel="modal:open">
                    <?php echo stripslashes($r->room_name); ?>
                </a>
            </p>
        <?php
    }
    if($norooms){
        ?>
        <p><strong>No rooms created!</strong></p>
        <p><a href="<?php echo site_url(); ?>/library/">Create a room and a shelf here</a></p>
        <?php
    }
    ?>
    <p class="error_message" id="error_message"></p>
    <p>
        <a href="#close" class="f-right" rel="modal:close">cancel</a>
    <p>
</div>

<?php
}else{
    ?>
    <div>
        <h4>Please login to manage your library</h4>
        <p><a href="<?php echo site_url(); ?>/login/">Login here</a></p>
    </div>
    <?php
}
?>
