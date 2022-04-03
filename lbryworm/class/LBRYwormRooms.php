<?php

class LBRYwormRooms{

    public $LBRYworm;

    public function __construct(&$lw){
        $this->LBRYworm=$lw;
    }
    
    public function add_room($name){
        global $wpdb;

        $wpdb->insert(
                'lw_rooms',
                array(
                    'user_id'=>$this->LBRYworm->user->ID,
                    'room_name'=>$name,
                    'room_data'=>json_encode($_POST),
                    'shared'=>isset($_POST['shared'])?1:0,
                ),
                array(
                    '%d',
                    '%s',
                    '%s',
                    '%d',
                )
            );
        return $this->get_room($wpdb->insert_id);
    }
    
    public function update_room($id,$name){
        global $wpdb;

        $wpdb->update(
                'lw_rooms',
                array(
                    'room_name'=>$name,
                    'room_data'=>json_encode($_POST),
                    'shared'=>isset($_POST['shared'])?1:0,
                ),
                array(
                    'user_id'=>$this->LBRYworm->user->ID,
                    'id'=>$id,
                )
            );
        return $this->get_room($id);
    }
    
    
    public function get_rooms(){
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM lw_rooms WHERE user_id={$this->LBRYworm->user->ID} ORDER BY id ASC");
    }
    
    public function get_room($id){
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM lw_rooms WHERE user_id={$this->LBRYworm->user->ID} AND id=$id");
    }
    
    public function get_room_anon($id){
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM lw_rooms WHERE id=$id");
    }
    
    public function remove_room($id){
        //top_channels_...
        global $wpdb;
        $wpdb->delete(
            'lw_rooms',
            array(
                'user_id'=>$this->LBRYworm->user->ID,
                'id'=>$id,
            )
        );
        
        $wpdb->delete(
            'lw_shelves',
            array(
                'user_id'=>$this->LBRYworm->user->ID,
                'room_id'=>$id,
            )
        );
        
        $wpdb->delete(
            'lw_books',
            array(
                'user_id'=>$this->LBRYworm->user->ID,
                'room_id'=>$id,
            )
        );
        
    }


}
