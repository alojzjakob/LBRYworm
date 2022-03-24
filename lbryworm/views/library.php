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
                        ?>
                        <div class="room" id="room_<?php echo $r->id; ?>">
                            <a href="?room=<?php echo $r->id; ?>">
                                <?php echo $r->room_name; ?>
                            </a>
                            
                            <a href="<?php echo site_url(); ?>/wp-content/plugins/lbryworm/views/modal/room_remove.php?id=<?php echo $r->id; ?>" rel="modal:open"><i class="fa fa-trash"></i></a>
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
        ?>
        
        <div class="breadcrumbs">
            <div class="f-left">
                <a href="<?php echo site_url(); ?>"><i class="fa fa-home"></i></a> &raquo; <a href="<?php echo site_url(); ?>/library/">Rooms</a> &raquo; <?php echo $room->room_name; ?>
            </div>
            <a class="f-right" title="Add Shelf" href="<?php echo site_url(); ?>/wp-content/plugins/lbryworm/views/modal/shelf_add.php?room_id=<?php echo $room->id; ?>" rel="modal:open"><i class="fa fa-plus"></i></a>
            <div class="clearfix"></div>
        </div>
        
        <div id="shelves">
            <?php
                if($shelves){
                    foreach($shelves as $s){
                        ?>
                        <div class="shelf" id="shelf_<?php echo $s->id; ?>">
                            <a href="?shelf=<?php echo $s->id; ?>">
                                <?php echo $s->shelf_name; ?>
                            </a>
                            
                            <a href="<?php echo site_url(); ?>/wp-content/plugins/lbryworm/views/modal/shelf_remove.php?id=<?php echo $s->id; ?>" rel="modal:open"><i class="fa fa-trash"></i></a>
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
        
        ?>
        
        <div class="breadcrumbs">
            <div class="f-left">
                <a href="<?php echo site_url(); ?>"><i class="fa fa-home"></i></a> &raquo; <a href="<?php echo site_url(); ?>/library/">Rooms</a> &raquo; <a href="<?php echo site_url(); ?>/library/?room=<?php echo $room->id; ?>"><?php echo $room->room_name; ?></a> &raquo; <?php echo $shelf->shelf_name; ?>
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
