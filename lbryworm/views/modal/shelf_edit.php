<?php
include(dirname(__FILE__).'/../../../../../wp-blog-header.php');

global $LBRYworm;

$shelf=$LBRYworm->shelves->get_shelf($_GET['id']);
$room=$LBRYworm->rooms->get_room($shelf->room_id);

?>
<form id="edit_shelf_form" data-shelf_id="<?php echo $_GET['id']; ?>">
    <div>
        <h4>Editing shelf</h4>
        <p><strong><?php echo stripslashes($shelf->shelf_name); ?></strong> in <strong><?php echo stripslashes($room->room_name); ?></strong></p>
        <p>
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
            <input type="text" id="shelf_name" name="shelf_name" placeholder="Enter the shelf name" value="<?php echo stripslashes($shelf->shelf_name); ?>">
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
                $shelf_data=json_decode($shelf->shelf_data);
                $cur_bg_image='';
                if($shelf_data->bg_image!==''){
                    $cur_bg_image=$shelf_data->bg_image;
                }
                
                foreach($bg_images as $s){
                    $bg_image=basename($s,'.jpg');
                    ?>
                        <option value="<?php echo $bg_image; ?>" data-img="<?php echo (get_site_url());?>/wp-content/plugins/lbryworm/images/shelf_textures/<?php echo $bg_image; ?>.jpg" <?php if($cur_bg_image==$bg_image) echo ' selected'; ?>>
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
                jQuery('#bg_image').trigger('change');
            </script>
        </p>
        
        <p>
            <input type="checkbox" id="shared" name="shared" value="shared" <?php if($shelf->shared){ echo ' checked';} ?>> Public (shareable)
        </p>
        
        <p class="error_message" id="error_message"></p>
        <p>
            <button type="submit" id="edit_shelf">Save</button> <a href="#close" class="f-right" rel="modal:close">cancel</a>
        <p>
    </div>
</form>

<script type="text/javascript">

edit_shelf_handler();

</script>
