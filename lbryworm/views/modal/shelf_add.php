<?php
include(dirname(__FILE__).'/../../../../../wp-blog-header.php');

global $LBRYworm;

$room=$LBRYworm->rooms->get_room($_GET['room_id']);
?>
<form id="add_shelf_form">
    <div>
        <h4>Add shelf</h4>
        <p>to <strong><?php echo $room->room_name; ?></strong></p>
        <p>
            <input type="hidden" id="room_id" name="room_id" value="<?php echo $_GET['room_id']; ?>">
            <input type="text" id="shelf_name" name="shelf_name" placeholder="Enter the shelf name">
        <p>
        <p class="error_message" id="error_message"></p>
        <p>
            <button type="submit" id="add_shelf">Add</button> <a href="#close" class="f-right" rel="modal:close">cancel</a>
        <p>
    </div>
</form>

<script type="text/javascript">

add_shelf_handler();

</script>
