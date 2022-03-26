<div class="lbryworm_content">
    
    <?php
    global $LBRYworm;

    
    if(!isset($_GET['room']) and !isset($_GET['shelf'])){

    ?>
    
        <div class="breadcrumbs">
            <div class="f-left">
                <a href="<?php echo site_url(); ?>"><i class="fa fa-home"></i></a> &raquo; Rooms
            </div>
            <a class="f-right" title="Add Room" href="<?php echo site_url(); ?>/wp-content/plugins/lbryworm/views/modal/room_add.php" rel="modal:open"><i class="fa fa-plus"></i></a>
            <div class="clearfix"></div>
        </div>
    
        a future place for <b>your own library</b> filled with your own <b>rooms</b> filled with <b>bookshelves</b> filled with <b>books</b> (ones you added from the search)!<br/><br/>
        This page is under development...<br/><br/>
        You can stay here and watch it come into shape, or just leave this page and use it later when this notice disappears.
        <br/><br/>
        
        
        <div id="rooms">
            <?php
                if($rooms){
                    foreach($rooms as $r){
                        
                        $shelves_count = $LBRYworm->shelves->get_shelves_count($r->id);
                        $books_count = $LBRYworm->books->get_books_in_room_count($r->id);
                        
                        $room_style='';
                        if($r->room_data){
                            $room_data=json_decode($r->room_data);
                            if($room_data->bg_image!==''){
                                $room_style.='background-image:url('.get_site_url().'/wp-content/plugins/lbryworm/images/room_wallpapers/'.$room_data->bg_image.'.jpg);background-position:center center;';
                                if(stripos($room_data->bg_image,'-tile')>0){
                                    $room_style.='background-repeat:repeat;';
                                }else{
                                    $room_style.='background-repeat:no-repeat;background-size:cover;';
                                }
                            }
                        }
                        
                        ?>
                        <div class="library_item" id="room_<?php echo $r->id; ?>" style="<?php echo $room_style; ?>">
                            <a class="library_item_title" href="?room=<?php echo $r->id; ?>">
                                <?php echo stripslashes($r->room_name); ?>
                            </a>
                            <div class="library_details f-left">
                                <span>
                                    <i class="fa fa-bars"></i><?php echo $shelves_count; ?>
                                </span>
                                <span>
                                    <i class="fa fa-book"></i><?php echo $books_count; ?>
                                </span>
                            </div>
                            <div class="library_controls f-right">
                                <a href="<?php echo site_url(); ?>/wp-content/plugins/lbryworm/views/modal/room_remove.php?id=<?php echo $r->id; ?>" rel="modal:open"><i class="fa fa-trash"></i></a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <?php
                    }
                }
            ?>
        </div>
    <?php
    }
    

    if(isset($_GET['room'])){
        
        $shelves=$LBRYworm->shelves->get_shelves($_GET['room']);
        $room=$LBRYworm->rooms->get_room($_GET['room']);
        
        $room_style='';
        if($room->room_data){
            $room_data=json_decode($room->room_data);
            if($room_data->bg_image!==''){
                $room_style.='background-image:url('.get_site_url().'/wp-content/plugins/lbryworm/images/room_wallpapers/'.$room_data->bg_image.'.jpg);background-position:center center;';
                if(stripos($room_data->bg_image,'-tile')>0){
                    $room_style.='background-repeat:repeat;';
                }else{
                    $room_style.='background-repeat:no-repeat;background-size:cover;';
                }
            }
        }
        
        ?>
        <style>
            <?php
            if($room_style!==''){
                ?>body{<?php echo $room_style; ?>}<?php
            }
            ?>
        </style>
        
        <div class="breadcrumbs">
            <div class="f-left">
                <a href="<?php echo site_url(); ?>"><i class="fa fa-home"></i></a> &raquo; <a href="<?php echo site_url(); ?>/library/">Rooms</a> &raquo; <?php echo stripslashes($room->room_name); ?>
            </div>
            <a class="f-right" title="Add Shelf" href="<?php echo site_url(); ?>/wp-content/plugins/lbryworm/views/modal/shelf_add.php?room_id=<?php echo $room->id; ?>" rel="modal:open"><i class="fa fa-plus"></i></a>
            <div class="clearfix"></div>
        </div>
        
        <div id="shelves">
            <?php
                if($shelves){
                    foreach($shelves as $s){
                        
                        $books_count = $LBRYworm->books->get_books_in_shelf_count($s->id);
                        
                        $shelf_style='';
                        if($s->shelf_data){
                            $shelf_data=json_decode($s->shelf_data);
                            if($shelf_data->bg_image!==''){
                                $shelf_style.='background-image:url('.get_site_url().'/wp-content/plugins/lbryworm/images/shelf_textures/'.$shelf_data->bg_image.'.jpg);background-position:center center;background-repeat:repeat;';
                            }else{
                                $shelf_style.='background-image:url('.get_site_url().'/wp-content/plugins/lbryworm/images/shelf_textures/wood-texture-1.jpg);background-position:center center;background-repeat:repeat;';
                            }
                        }else{
                            $shelf_style.='background-image:url('.get_site_url().'/wp-content/plugins/lbryworm/images/shelf_textures/wood-texture-1.jpg);background-position:center center;background-repeat:repeat;';
                        }
                        ?>
                        <div class="library_item" id="shelf_<?php echo $s->id; ?>" style="<?php echo $shelf_style; ?>">
                            <a class="library_item_title" href="?shelf=<?php echo $s->id; ?>">
                                <?php echo stripslashes($s->shelf_name); ?>
                            </a>
                            
                            <div class="library_details f-left">
                                <span>
                                    <i class="fa fa-book"></i><?php echo $books_count; ?>
                                </span>
                            </div>
                            
                            <div class="library_controls f-right">
                                <a href="<?php echo site_url(); ?>/wp-content/plugins/lbryworm/views/modal/shelf_remove.php?id=<?php echo $s->id; ?>" rel="modal:open"><i class="fa fa-trash"></i></a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                                                
                        <?php
                    }
                }
            ?>
        </div>
        <?php
    }
    if(isset($_GET['shelf'])){

        $shelf=$LBRYworm->shelves->get_shelf($_GET['shelf']);
        $room=$LBRYworm->rooms->get_room($shelf->room_id);
        $books=$LBRYworm->books->get_books($shelf->id);
        
        
        $room_style='';
        if($room->room_data){
            $room_data=json_decode($room->room_data);
            if($room_data->bg_image!==''){
                $room_style.='background-image:url('.get_site_url().'/wp-content/plugins/lbryworm/images/room_wallpapers/'.$room_data->bg_image.'.jpg);background-position:center center;';
                if(stripos($room_data->bg_image,'-tile')>0){
                    $room_style.='background-repeat:repeat;';
                }else{
                    $room_style.='background-repeat:no-repeat;background-size:cover;';
                }
            }
        }
        
        $shelf_style='';
        if($shelf->shelf_data){
            $shelf_data=json_decode($shelf->shelf_data);
            if($shelf_data->bg_image!==''){
                $shelf_style.='background-image:url('.get_site_url().'/wp-content/plugins/lbryworm/images/shelf_textures/'.$shelf_data->bg_image.'.jpg)!important;';
            }
        }
        
        ?>
        <style>
            <?php
            if($room_style!==''){
                ?>body{<?php echo $room_style; ?>}<?php
            }
            
            if($shelf_style!==''){
                ?>  .bookshelf_break,
                    .bookshelf_break::before,
                    .bookshelf_break::after{<?php echo $shelf_style; ?>}<?php
            }
            
            ?>
        </style>
        
        <div class="breadcrumbs">
            <div class="f-left">
                <a href="<?php echo site_url(); ?>"><i class="fa fa-home"></i></a> &raquo; <a href="<?php echo site_url(); ?>/library/">Rooms</a> &raquo; <a href="<?php echo site_url(); ?>/library/?room=<?php echo $room->id; ?>"><?php echo stripslashes($room->room_name); ?></a> &raquo; <?php echo stripslashes($shelf->shelf_name); ?>
            </div>
            <div class="clearfix"></div>
        </div>
        
        <div id="books">
            <?php
            $i=0;    
            if($books){
                foreach($books as $b){
                    $book_data=json_decode($b->book_data);
                    
                    $thumbnail_css=' background-image:url('.site_url().'/wp-content/plugins/lbryworm/css/lbryworm-placeholder.png);
                                    background-color:#296c57;
                                    background-size:contain!important;
                                    background-color:#296c57;
                                    background-repeat:no-repeat;
                                ';
                    if($book_data->thumbnail_url!==''){
                        $thumbnail_css='background-image:url('.$book_data->thumbnail_url.');';
                    }
                    
                    ?>
                    <div class="book" id="book_<?php echo $b->id; ?>">
                    
                        <div class="spine" style="<?php echo $thumbnail_css; ?>">
                            <div class="spine_text">
                                <?php echo $book_data->title; ?> 
                            </div>
                        </div>
                    
                        <div class="front" style="<?php echo $thumbnail_css; ?>">
                        </div>
                        
                        
                        
                        <!--<?php echo $b->claim_id; ?>-->
                        
                        <div class="book_controls">
                            <a href="<?php echo site_url(); ?>/wp-content/plugins/lbryworm/views/modal/book_remove.php?id=<?php echo $b->id; ?>" rel="modal:open">
                                <i class="fa fa-trash"></i>
                            </a>
                            <a href="lbry://<?php echo $book_data->name; ?>#<?php echo $book_data->claim_id; ?>" target="_blank" title="Open via LBRY app">
                                <img class="external" src="/wp-content/plugins/lbryworm/css/open_on_lbrytv.png">
                            </a>
                            <a href="https://odysee.com/<?php echo $book_data->name; ?>:<?php echo $book_data->claim_id; ?>" target="_blank" title="Open on Odysee">
                                <img class="external" src="/wp-content/plugins/lbryworm/css/open_on_odysee.png">
                            </a>
                        </div>
                        
                    </div>
                    <?php
                    if($i==5){
                        $i=0;
                        ?>
                        <div class="shelf"></div>
                        <?php
                    }
                }
            }
            ?>
        </div>
    <?php
    }

    
    ?>
</div>
