<?php

class LBRYwormBooks{

    public $LBRYworm;

    public function __construct(&$lw){
        $this->LBRYworm=$lw;
    }
    
    public function add_book($shelf_id,$claim_id){
        global $wpdb;

        $shelf=$this->LBRYworm->shelves->get_shelf($shelf_id);
        
        if($shelf){
        
            $book_data=$this->LBRYworm->ChainQuery->get_claim($claim_id);
            
            $wpdb->insert(
                    'lw_books',
                    array(
                        'user_id'=>$this->LBRYworm->user->ID,
                        'room_id'=>$shelf->room_id,
                        'shelf_id'=>$shelf_id,
                        'claim_id'=>$claim_id,
                        'book_data'=>json_encode($book_data),
                    ),
                    array(
                        '%d',
                        '%d',
                        '%d',
                        '%s',
                        '%s',
                    )
                );
            
            return $this->get_book($wpdb->insert_id);
        }else{
            return false;
        }
        
    }
    
    
    
    public function get_books($shelf_id){
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM lw_books WHERE shelf_id=$shelf_id AND user_id={$this->LBRYworm->user->ID} ORDER BY id ASC");
    }
    
    public function get_books_anon($shelf_id){
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM lw_books WHERE shelf_id=$shelf_id ORDER BY id ASC");
    }
    
    public function get_book($id){
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM lw_books WHERE user_id={$this->LBRYworm->user->ID} AND id=$id");
    }
    
    public function get_books_in_room_count($room_id){
        global $wpdb;
        $res = $wpdb->get_row("SELECT COUNT(id) AS books FROM lw_books WHERE room_id=$room_id");
        return $res->books;
    }
    
    public function get_books_in_shelf_count($shelf_id){
        global $wpdb;
        $res = $wpdb->get_row("SELECT COUNT(id) AS books FROM lw_books WHERE shelf_id=$shelf_id");
        return $res->books;
    }
    
    public function remove_book($id){
        //top_channels_...
        global $wpdb;
        $wpdb->delete(
            'lw_books',
            array(
                'user_id'=>$this->LBRYworm->user->ID,
                'id'=>$id,
            )
        );
    }


}
