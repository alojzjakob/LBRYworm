<?php

include(dirname(__FILE__).'/../../../../../wp-blog-header.php');

global $LBRYworm;

$shelf=$LBRYworm->shelves->get_shelf($_GET['id']);
?>
<form id="remove_shelf_form" data-shelf_id="<?php echo $_GET['id']; ?>">
    <div>
        <h4>Remove shelf</h4>
        <p>Are you sure you want to remove <strong><?php echo stripslashes($shelf->shelf_name); ?></strong>?</p>
        <p>
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
        <p>
        <p>
            <button type="submit" id="remove_shelf">Remove</button> <a href="#close" class="f-right" rel="modal:close">cancel</a>
        <p>
    </div>
</form>

<script type="text/javascript">

remove_shelf_handler();

</script>
