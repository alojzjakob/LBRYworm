<?php

include(dirname(__FILE__).'/../../../../../wp-blog-header.php');

global $LBRYworm;

?>

<form id="add_room_form">
    <div>
        <h4>Add room</h4>
        <p>
            <input type="text" id="room_name" name="room_name" placeholder="Enter the room name">
        <p>
        
        <p>
            Room wallpaper: 
            <?php
            $bg_images = glob(dirname(__FILE__).'/../../images/room_wallpapers/*.jpg');
            //var_dump($bg_images);
            ?>
            <select name="bg_image" id="bg_image">
                <option value=""> - none - </option>
                <?php
                foreach($bg_images as $s){
                    $bg_image=basename($s,'.jpg');
                    ?>
                        <option value="<?php echo $bg_image; ?>" data-img="<?php echo (get_site_url());?>/wp-content/plugins/lbryworm/images/room_wallpapers/<?php echo $bg_image; ?>.jpg">
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
            <button type="submit" id="add_room">Add</button> <a href="#close" class="f-right" rel="modal:close">cancel</a>
        <p>
    </div>
</form>

<script type="text/javascript">

add_room_handler();

</script>
