<?php
include(dirname(__FILE__).'/../../../../../wp-blog-header.php');

global $LBRYworm;

$room=$LBRYworm->rooms->get_room($_GET['room_id']);
$shelves=$LBRYworm->shelves->get_shelves($_GET['room_id']);
$noshelves=true;
if(count($shelves)>0){
    $noshelves=false;
}
?>
<form id="add_book_form" data-room_id="<?php echo $_POST['room_id'] ?>">
    <div>
        <h4>Add book to shelf</h4>
        <?php
        if(!$noshelves){
        ?>
            <p>in <strong><?php echo $room->room_name; ?></strong></p>
            <p>
                <input type="hidden" id="room_id" name="room_id" value="<?php echo $_GET['room_id']; ?>">
                <input type="hidden" id="claim_id" name="claim_id" value="<?php echo $_GET['claim_id']; ?>">
                <select name="shelf_id" id="shelf_id">
                <?php
                foreach($shelves as $s){
                    ?>
                    <option value="<?php echo $s->id; ?>"><?php echo $s->shelf_name; ?></option>
                    <?php
                }
                ?>
                </select>
            <p>
            <p class="error_message" id="error_message"></p>
            <p>
                <button type="submit" id="add_book">Add</button> <a href="#close" class="f-right" rel="modal:close">cancel</a>
            <p>
            
        <?php
        }
        if($noshelves){
            ?>
            <p><strong>No shelves in this room!</strong></p>
            <p><a href="<?php echo site_url(); ?>/library/?room=<?php echo $room->id; ?>">Create a shelf here</a></p>
            <?php
        }?>
        
    </div>
</form>

<script type="text/javascript">

add_book_handler();

</script>
