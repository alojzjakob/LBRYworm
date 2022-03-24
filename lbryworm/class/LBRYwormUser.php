<?php

class LBRYwormUser{

    public $LBRYworm;
    
    public function __construct(&$lw){
        $this->LBRYworm=$lw;
        
        add_action( 'plugins_loaded', array( $this, 'populate_user' ) );
        add_shortcode('lbryworm-resend-activation-link',array($this,'shortcode_resend_activation_link'));
        add_shortcode('lbryworm-delete-account',array($this,'shortcode_delete_user')); //[plugin_delete_me /]
        
    }
    
    public function populate_user(){
        $this->LBRYworm->user = wp_get_current_user();
        
        if($this->LBRYworm->user->ID){
            $this->LBRYworm->user_settings=unserialize(get_user_meta($this->LBRYworm->user->ID,'lbryworm_settings',true));
            
            if(!isset($this->LBRYworm->user_settings['max_tip_size'])){
                $this->LBRYworm->user_settings['max_tip_size']=1000;
            }
            
            new LBRYwormAdmin($this->LBRYworm);

        }else{
        
            if(isset($_GET['id']) and isset($_GET['token']) and isset($_GET['action']) and $_GET['action']=='lbryworm-activate'){
                $user = get_user_by( 'id', $_GET['id'] );
                if($user->ID){
                    //var_dump($user->roles);
                    if(count($user->roles)==0){
                        $token=get_user_meta($user->ID,'lbryworm_activation_token',true);
                        if($token==$_GET['token']){
                            
                            delete_user_meta($user->ID,'lbryworm_activation_token');
                            $u = new WP_User( $user->ID );
                            $u->add_role( 'subscriber' );
                            wp_set_current_user( $user->ID, $user->user_login );
                            wp_set_auth_cookie( $user->ID );
                            do_action( 'wp_login', $user->user_login );
                            
                            wp_redirect(site_url().'');
                            exit;
                        }
                    }
                }
            }
        }
        
    }
    
    public function shortcode_delete_user(){
        return $this->LBRYworm->load_view('delete_account',$data);
    }
    
    public function shortcode_resend_activation_link(){
        extract(shortcode_atts(array(
                'title' => 'Home',
                'page' => 'home',
            ), $atts));
        
        $data=array();
        
        if(isset($_POST['resendActivationLink']) and is_email($_POST['email'])){
            $user = get_user_by( 'email', $_POST['email'] );
            if($user->ID){
                //var_dump($user->roles);
                if(count($user->roles)==0){
                    $token=md5(time().'-lbryworm-actv@ti0n');
                    update_user_meta($user->ID,'lbryworm_activation_token',$token);
                    
                    $data['sent']=true;
                    
                    $link='https://www.lbryworm.com/resend-activation-link/?action=lbryworm-activate&id='.$user->ID.'&token='.$token;
                    
                    $to=$user->user_email;
                    $subject='[LBRYworm] Activate your account';
                    $message="Use the following link to activate your account: <a href='$link'>activate your account</a>.<br/><br/>LBRYworm<br/>";
                    wp_mail($to,$subject,$message,array( 'Content-Type: text/html; charset=UTF-8' ));
                    
                    //var_dump($token);
                    //var_dump($user->ID);
                }
            }
        }
        
        return $this->LBRYworm->load_view('resend-activation-link',$data);
    }
    
    
}
