<?php

//https://ug1lib.org/


class LBRYworm extends AJsToolBox{

    public $user;
    public $jscss_ver;

    public $user_settings;
    
    public $ChainQuery;

    public $rooms;
    public $shelves;
    public $books;
    
    public function __construct(){
    
        $this->jscss_ver='0.1.02';

        $this->ChainQuery = new ChainQuery($this);
        
        $this->rooms = new LBRYwormRooms($this);
        $this->shelves = new LBRYwormShelves($this);
        $this->books = new LBRYwormBooks($this);

        new LBRYwormWordpress($this);
        new LBRYwormUser($this);
    
    
        // user area
        add_shortcode('lbryworm-search',array($this,'shortcode_search'));
        add_shortcode('lbryworm-home',array($this,'shortcode_home'));
        add_shortcode('lbryworm-library',array($this,'shortcode_library'));
        
        add_shortcode('lbryworm-dashboard',array($this,'shortcode_dashboard'));
        add_shortcode('lbryworm-settings',array($this,'shortcode_settings'));
        
        add_shortcode('lbryworm-profile-widget',array($this,'shortcode_profile_widget'));
        
        // internal
        add_shortcode('lbryworm-changelog',array($this,'shortcode_changelog'));
        add_shortcode('lbryworm-about',array($this,'shortcode_about'));
        add_shortcode('lbryworm-info',array($this,'shortcode_info'));
        add_shortcode('lbryworm-news',array($this,'shortcode_news'));
        add_shortcode('lbryworm-help',array($this,'shortcode_help'));
        
        // ajax
        // logged in
        add_action( 'wp_ajax_lbryworm', array($this,'ajax_handler') );
        // not logged in
        add_action( 'wp_ajax_nopriv_lbryworm', array($this,'ajax_handler') );
        
        $this->user_settings=false;

        $this->friends=array();
        
    }
    
    function ajax_handler() {
        

        $method=$_REQUEST['method'];
        
        
        //var_dump($response);
        header('Content-Type: application/json; charset=utf-8');
        header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        if($method==='search'){
            $response=$this->ChainQuery->search($_POST['search_query']);
        }
        
        if($method==='get_stream_urls'){
            //$this->ChainQuery->get_claim_streaming_url($lbryURI);
            $response=array();
            
            $data=json_decode(stripslashes($_POST['data']),true);
            //var_dump($data);
            
            foreach($data as $d){
                $response[]=array(
                                    'claim_id'=>$d['claim_id'],
                                    'streaming_url'=>$this->ChainQuery->get_claim_streaming_url($d['lbry_url'])
                                  );
            }
            
        }
        
        if($method==='add_room'){
            if($_POST['room_name']!==''){
                $response=array('error'=>false,'data'=>$this->rooms->add_room($_POST['room_name']));
            }else{
                $response=array('error'=>true,'error_message'=>'You did not enter the room name...');
            }
        }
        
        if($method==='remove_room'){
            $response=array('error'=>false,'data'=>$this->rooms->remove_room($_POST['id']));
        }
        
        if($method==='add_shelf'){
            if($_POST['shelf_name']!==''){
                $response=array('error'=>false,'data'=>$this->shelves->add_shelf($_POST['room_id'],$_POST['shelf_name']));
            }else{
                $response=array('error'=>true,'error_message'=>'You did not enter the shelf name...');
            }
        }
        
        if($method==='remove_shelf'){
            $response=array('error'=>false,'data'=>$this->shelves->remove_shelf($_POST['id']));
        }
        
        if($method==='add_book_to_shelf'){
            $response=array('error'=>false,'data'=>$this->books->add_book($_POST['shelf_id'],$_POST['claim_id']));
        }
        
        if($method==='remove_book'){
            $response=array('error'=>false,'data'=>$this->books->remove_book($_POST['id']));
        }
        
        echo json_encode($response);
        
        wp_die();
        
    }
    
    public function logged_in_gate(){
        return $this->load_view('logged_in_gate',$data);
    }
    
    public function shortcode_search($atts){
        extract(shortcode_atts(array(
                'title' => 'Search',
                'page' => 'search',
            ), $atts));
        
        $data=array(
                    'results'=>array('data'=>null),
                );
        
        if(isset($_POST['search_query']) and $_POST['search_query']!==''){
            $data['results']=$this->ChainQuery->search($_POST['search_query']);
        }
        
        return $this->load_view('search',$data);
    }
    
    
    public function shortcode_home($atts){
        extract(shortcode_atts(array(
                'title' => 'Home',
                'page' => 'home',
            ), $atts));
        
        $data=array();
        
        return $this->load_view('home',$data);
    }
    
    public function shortcode_library($atts){
        extract(shortcode_atts(array(
                'title' => 'Library',
                'page' => 'library',
            ), $atts));
        
        $data=array();
        
        if(is_user_logged_in()){
            $data['rooms']=$this->rooms->get_rooms();
            return $this->load_view('library',$data);
        }else{
            return $this->logged_in_gate();
        }
    }
    
    public function shortcode_profile_widget($atts){
        extract(shortcode_atts(array(
                'title' => 'Profile widget',
                'page' => 'profile_widget',
            ), $atts));
        
        $data=array();
        
        return $this->load_view('profile_widget',$data);
    }
    
    public function shortcode_changelog($atts){
        extract(shortcode_atts(array(
                'title' => 'Changelog',
                'page' => 'changelog',
            ), $atts));
        
        $data=array();
        
        return $this->load_view('changelog',$data);
    }   
    
    public function shortcode_about($atts){
        extract(shortcode_atts(array(
                'title' => 'About',
                'page' => 'about',
            ), $atts));
        
        $data=array();
        
        return $this->load_view('about',$data);
    }
    
    public function shortcode_help($atts){
        extract(shortcode_atts(array(
                'title' => 'Help',
                'page' => 'help',
            ), $atts));
        
        $data=array();
        
        return $this->load_view('help',$data);
    }
    
    public function shortcode_info($atts){
        extract(shortcode_atts(array(
                'title' => 'Info',
                'page' => 'info',
            ), $atts));
        
        $data=array();
        
        return $this->load_view('info',$data);
    }
    
    public function shortcode_news($atts){
        extract(shortcode_atts(array(
                'title' => 'News',
                'page' => 'news',
            ), $atts));
        
        $data=array();
        
        return $this->load_view('news',$data);
    }
    
    public function shortcode_settings($atts){
        extract(shortcode_atts(array(
                'title' => 'Settings',
                'page' => 'settings',
            ), $atts));
        
        if(isset($_POST['saveUserSettings']) and check_admin_referer('save-user-settings')){
            $settings=$this->user_settings;
            $new_settings=$_POST;
//             if(!isset($settings['home_grid'])){
//                 $settings['home_grid']=array();
//             }
//             $new_settings['home_grid']=$settings['home_grid'];
            update_user_meta($this->user->ID,'lbryworm_settings',serialize($new_settings));
            $this->user_settings=$_POST;
            
//             $wallets=explode(',',$this->user_settings['wallet_address']);
//             $this->wallet=array();
//             foreach($wallets as $w){
//                 $w=trim($w);
//                 $this->wallet[]=$this->ChainQuery->get_wallet($w);
//             }
        }
        
        $data=array('settings'=>$this->user_settings);
        
        return $this->load_view('settings',$data);
    }
    
    public function shortcode_dashboard($atts){
        extract(shortcode_atts(array(
                'title' => 'Dashboard',
                'page' => 'dashboard',
            ), $atts));
        
        $data=array();
        
        
        return $this->load_view('dashboard',$data);
    }
    

    
}
