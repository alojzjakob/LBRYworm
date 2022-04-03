<?php

class LBRYwormShelves{

    public $LBRYworm;

    public function __construct(&$lw){
        $this->LBRYworm=$lw;
    }
    
    public function add_shelf($room_id,$name){
        global $wpdb;

        $room=$this->LBRYworm->rooms->get_room($room_id);
        
        if($room){
        
            $wpdb->insert(
                    'lw_shelves',
                    array(
                        'user_id'=>$this->LBRYworm->user->ID,
                        'room_id'=>$room_id,
                        'shelf_name'=>$name,
                        'shelf_data'=>json_encode($_POST),
                        'shared'=>isset($_POST['shared'])?1:0,
                    ),
                    array(
                        '%d',
                        '%d',
                        '%s',
                        '%s',
                        '%d',
                    )
                );
            return $this->get_shelf($wpdb->insert_id);
        }else{
            return false;
        }
        
    }
    
    
    public function update_shelf($id,$name){
        global $wpdb;

        $wpdb->update(
                'lw_shelves',
                array(
                    'shelf_name'=>$name,
                    'shelf_data'=>json_encode($_POST),
                    'shared'=>isset($_POST['shared'])?1:0,
                ),
                array(
                    'user_id'=>$this->LBRYworm->user->ID,
                    'id'=>$id,
                )
            );
        return $this->get_shelf($id);
    }
    
    
    public function get_shelves($room_id){
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM lw_shelves WHERE room_id=$room_id AND user_id={$this->LBRYworm->user->ID} ORDER BY id ASC");
    }
    
    public function get_shelves_anon($room_id,$shared=1){
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM lw_shelves WHERE room_id=$room_id AND shared=$shared ORDER BY id ASC");
    }
    
    public function get_shelf($id){
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM lw_shelves WHERE user_id={$this->LBRYworm->user->ID} AND id=$id");
    }
    
    public function get_shelf_anon($id){
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM lw_shelves WHERE id=$id");
    }
    
    public function get_shelves_count($room_id){
        global $wpdb;
        $res = $wpdb->get_row("SELECT COUNT(id) AS shelves FROM lw_shelves WHERE room_id=$room_id");
        return $res->shelves;
    }
    
    public function remove_shelf($id){
        //top_channels_...
        global $wpdb;
        $wpdb->delete(
            'lw_shelves',
            array(
                'user_id'=>$this->LBRYworm->user->ID,
                'id'=>$id,
            )
        );
                
        $wpdb->delete(
            'lw_books',
            array(
                'user_id'=>$this->LBRYworm->user->ID,
                'shelf_id'=>$id,
            )
        );
        
    }


}
