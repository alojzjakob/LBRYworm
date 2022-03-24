<?php

include(dirname(__FILE__).'/../../../../../wp-blog-header.php');

global $LBRYworm;

$room=$LBRYworm->rooms->get_room($_GET['id']);
?>
<form id="remove_room_form" data-room_id="<?php echo $_GET['id']; ?>">
    <div>
        <h4>Remove room</h4>
        <p>Are you sure you want to remove <strong><?php echo $room->room_name; ?></strong>?</p>
        <p>
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
        <p>
        <p>
            <button type="submit" id="remove_room">Remove</button> <a href="#close" class="f-right" rel="modal:close">cancel</a>
        <p>
    </div>
</form>

<script type="text/javascript">

remove_room_handler();

</script>
