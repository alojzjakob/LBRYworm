<?php

class LBRYwormWordpress{

    public $LBRYworm;
    
    public function __construct(&$lw){
        $this->LBRYworm=$lw;
        
        // Add shortcode support for widgets
        add_filter('widget_text', 'do_shortcode');
        add_filter('wp_head', array($this,'head_js'),10);
        
        add_filter('wp_footer', array($this,'footer_bar'),10);
        
        
        
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts'),100000 );
        add_action( 'twentyfourteen_credits', array($this, 'credits'),100000 );
        
        add_filter( 'oembed_response_data', array($this, 'disable_embeds_filter_oembed_response_data_') );
        
        add_filter( 'page_template', array($this,'lbry_page_template') );
        
    }
    
    public function footer_bar(){
        echo '  <div id="lw_footer" class="footer">
                    <a href="https://www.alojzjakob.com" target="_blank">
                        Made with <span style="color:#f44336;">❤</span> by Alojz
                    </a>
                    <!--<a href="https://www.lbryworm.com/contact/?your-subject=Bug+report" title="Bug report">
                        🐞
                    </a>-->
                    <a href="https://odysee.com/@LBRYworm:8e4e01213bfa91b7a675aad20c0357d6999e4414" target="_blank">
                        LBRYworm on Odysee
                    </a>
                    <a href="https://github.com/alojzjakob/LBRYworm" target="_blank">
                        <i class="fa fa-github"></i>
                    </a>
                </div>
                ';
    }
    
    public function head_js(){
        echo '  <script>
                    var lbryworm_ajax_url="'.admin_url('admin-ajax.php?action=lbryworm').'";
                    var lbryworm_site_url="'.site_url().'";
                    var is_user_logged_in='.((is_user_logged_in()==1)?'true':'false').';
                </script>';
    }
    
    public function lbry_page_template(){
        if(isset($_COOKIE['lbry_dark']) and $_COOKIE['lbry_dark']==1){
            add_filter( 'body_class', function( $classes ) {
                return array_merge( $classes, array( 'dark','bootstrap-dark' ) );
            } );
        }
    }
    
    public function disable_embeds_filter_oembed_response_data_( $data ) {
        //pre_var_dump($data);
        unset($data['author_url']);
        unset($data['author_name']);
        return $data;
    }
    
    public function credits(){
        echo '<a href="https://www.alojzjakob.com" target="_blank">
                Made with <span style="color:#f44336;">❤</span> by Alojz
              </a>
              <span role="separator" aria-hidden="true"></span>';
        echo '<a href="https://www.lbryworm.com/contact/?your-subject=Bug+report" title="Bug report">
                🐞
              </a>
              <span role="separator" aria-hidden="true"></span>';
    }
    
    public function enqueue_scripts(){
        wp_register_style('lbryworm-css', ((get_site_url()) . '/wp-content/plugins/lbryworm/css/style.css'), array(), $this->LBRYworm->jscss_ver);
        wp_enqueue_style('lbryworm-css');
        
        wp_register_style('animate-css', ((get_site_url()) . '/wp-content/plugins/lbryworm/css/animate.min.css'), array(), $this->LBRYworm->jscss_ver);
        wp_enqueue_style('animate-css');
        
        //wp_enqueue_script( 'lbryworm-filesaver',((get_site_url()) . '/wp-content/plugins/lbryworm/js/FileSaver.min.js'), array(), $this->LBRYworm->jscss_ver, TRUE );    
        
        wp_enqueue_script( 'jquery-modal',((get_site_url()) . '/wp-content/plugins/lbryworm/js/jquery.modal.min.js'), array(), $this->LBRYworm->jscss_ver, TRUE );    
        wp_register_style('jquery-modal-css', ((get_site_url()) . '/wp-content/plugins/lbryworm/css/jquery.modal.min.css'), array(), $this->LBRYworm->jscss_ver);
        wp_enqueue_style('jquery-modal-css');
        
        wp_enqueue_script( 'lbryworm-js',((get_site_url()) . '/wp-content/plugins/lbryworm/js/scripts.js'), array(), $this->LBRYworm->jscss_ver, TRUE );    


        
        // clean login plugin fix!
        wp_enqueue_style( 'clean-login-css', site_url().'/wp-content/plugins/clean-login/content/style.css', $this->LBRYworm->jscss_ver );
        wp_enqueue_style( 'clean-login-bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css', array(), $this->LBRYworm->jscss_ver );
        
        global $post;
        //var_dump($post->post_title);
        if ($post and $post->post_title!=='Contact' ) {
            //if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
                //wpcf7_enqueue_scripts();
                wp_deregister_script( 'google-recaptcha' );
            //}
        }
    }
    
}
