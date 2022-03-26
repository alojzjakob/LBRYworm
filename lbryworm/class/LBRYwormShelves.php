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
                    ),
                    array(
                        '%d',
                        '%d',
                        '%s',
                        '%s',
                    )
                );
            return $this->get_shelf($wpdb->insert_id);
        }else{
            return false;
        }
        
    }
    
    
    
    public function get_shelves($room_id){
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM lw_shelves WHERE room_id=$room_id AND user_id={$this->LBRYworm->user->ID} ORDER BY id ASC");
    }
    
    public function get_shelf($id){
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM lw_shelves WHERE user_id={$this->LBRYworm->user->ID} AND id=$id");
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
