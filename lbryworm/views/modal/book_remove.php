<?php

include(dirname(__FILE__).'/../../../../../wp-blog-header.php');

global $LBRYworm;

$book=$LBRYworm->books->get_book($_GET['id']);
$book_data=json_decode($book->book_data);
?>
<form id="remove_book_form" data-book_id="<?php echo $_GET['id']; ?>">
    <div>
        <h4>Remove book</h4>
        <p>Are you sure you want to remove <strong><?php echo $book_data->title; ?></strong>?</p>
        <p>
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
        <p>
        <p>
            <button type="submit" id="remove_book">Remove</button> <a href="#close" class="f-right" rel="modal:close">cancel</a>
        <p>
    </div>
</form>

<script type="text/javascript">

remove_book_handler();

</script>
