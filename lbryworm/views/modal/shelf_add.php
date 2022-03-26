<?php
include(dirname(__FILE__).'/../../../../../wp-blog-header.php');

global $LBRYworm;

$room=$LBRYworm->rooms->get_room($_GET['room_id']);
?>
<form id="add_shelf_form">
    <div>
        <h4>Add shelf</h4>
        <p>to <strong><?php echo stripslashes($room->room_name); ?></strong></p>
        <p>
            <input type="hidden" id="room_id" name="room_id" value="<?php echo $_GET['room_id']; ?>">
            <input type="text" id="shelf_name" name="shelf_name" placeholder="Enter the shelf name">
        <p>
        
        <p>
            Shelf texture: 
            <?php
            $bg_images = glob(dirname(__FILE__).'/../../images/shelf_textures/*.jpg');
            //var_dump($bg_images);
            ?>
            <select name="bg_image" id="bg_image">
                <option value=""> - none - </option>
                <?php
                foreach($bg_images as $s){
                    $bg_image=basename($s,'.jpg');
                    ?>
                        <option value="<?php echo $bg_image; ?>" data-img="<?php echo (get_site_url());?>/wp-content/plugins/lbryworm/images/shelf_textures/<?php echo $bg_image; ?>.jpg">
                            <?php echo str_replace(array('_','-'),' ',ucfirst($bg_image)); ?>
                        </option>
                    <?php
                }
                ?>
            </select>
        </p>
        <p>
            <div id="bg_image_preview" style="width:100px;height:100px;background-size:contain; background-repeat:no-repeat; background-position:center center;;padding:10px;border-radius:5px;">
            </div>
            <script type="text/javascript">
                jQuery('#bg_image').on('change',function(){
                    jQuery('#bg_image_preview').css("background-image",'url("'+jQuery(this).find(':selected').data('img')+'")');
                });
            </script>
        </p>
        
        <p class="error_message" id="error_message"></p>
        <p>
            <button type="submit" id="add_shelf">Add</button> <a href="#close" class="f-right" rel="modal:close">cancel</a>
        <p>
    </div>
</form>

<script type="text/javascript">

add_shelf_handler();

</script>
