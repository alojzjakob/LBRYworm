<?php

class LBRYwormAdmin{

    public $LBRYworm;
    
    public function __construct(&$lw){
        $this->LBRYworm=$lw;
        
        add_filter( 'manage_users_columns', array($this,'lbryworm_modify_user_table') );
        add_filter( 'manage_users_custom_column', array($this,'lbryworm_modify_user_table_row'), 10, 3 );
        add_filter( 'manage_users_sortable_columns', array($this,'sortable_column') );
        add_action( 'admin_head', array($this,'admin_user_columns_width'));
        //set instructions on how to sort the new column
        if(is_admin()) {//prolly not necessary, but I do want to be sure this only runs within the admin
            add_action('pre_get_users', array($this,'lbry_channels_user_query'), 10 , 1);
            
            // wp admin menus and settings
            add_action('wp_dashboard_setup', array($this,'add_dashboard_widget'));
            add_action('admin_bar_menu', array($this,'add_toolbar_items'), 100);
            add_action('admin_init', array($this,'plugin_admin_init') );
            add_action('admin_menu',array($this,'plugin_adminmenu'),1);

        }
        
        add_action('after_setup_theme', array($this,'remove_admin_bar'),100);
        
    }
    
    public function remove_admin_bar() {
        //if (!current_user_can('administrator') && !is_admin()) {
            //add_filter('show_admin_bar', '__return_false');
            show_admin_bar(false);
        //}
    }


    public function lbryworm_modify_user_table( $column ) {
        $column['lbry_channels'] = 'LBRY channels';
        return $column;
    }

    public function lbryworm_modify_user_table_row( $val, $column_name, $user_id ) {
        switch ($column_name) {
            case 'lbry_channels' :
                return get_user_meta($user_id,'lbry_channels',true);
            default:
        }
        return $val;
    }
    public function sortable_column( $columns ) {
        $columns['lbry_channels'] = 'lbry_channels';
        //To make a column 'un-sortable' remove it from the array unset($columns['date']);
        return $columns;
    }
    public function lbry_channels_user_query($WP_User_Query){

        if (    isset($WP_User_Query->query_vars["orderby"])
            &&  ("lbry_channels" === $WP_User_Query->query_vars["orderby"])
        ) {
        
           // pre_var_dump($WP_User_Query);
            $WP_User_Query->query_vars["meta_key"] = "lbry_channels";
            $WP_User_Query->query_vars["orderby"] = "meta_value_num";
            //$WP_User_Query->set( 'meta_type', 'NUMERIC' );
//             $WP_User_Query->set["meta_type"] = "NUMERIC";
//             $WP_User_Query->query_vars["meta_type"] = "NUMERIC";
//             $WP_User_Query->meta_query["meta_type"] = 'NUMERIC';
            //pre_var_dump($WP_User_Query);
        }

    }
    
    public function admin_user_columns_width() {
        echo '<style type="text/css">';
        echo 'th.column-lbry_channels,td.column-lbry_channels {width:70px !important; overflow:hidden }';
        echo 'th.column-role,td.column-role { width:80px !important; overflow:hidden }';
        echo '</style>';
    }
    
    
    
    // wp-admin settings and menus
    public function plugin_admin_styles() {
//         wp_enqueue_style( 'fodPluginStylesheet' );

//         wp_enqueue_script( 'fodPluginScript' );
//         wp_enqueue_script( 'fodPluginScriptAdmin' );
        
//         wp_enqueue_script( 'jquery-ui',((get_site_url()) . '/wp-content/plugins/fydelia-ondemand/js/jquery-ui.min.js'), '', '', TRUE );    
//         wp_enqueue_style( 'jquery-ui',((get_site_url()) . '/wp-content/plugins/fydelia-ondemand/css/jquery-ui.min.css'), FALSE );
//         wp_enqueue_style( 'font-awesome',((get_site_url()) . '/wp-content/plugins/fydelia-ondemand/css/font-awesome/css/font-awesome.min.css'), FALSE );
    }
    public function plugin_admin_init() {
//         $fydVersion=1;
//         wp_register_style( 'fodPluginStylesheet', plugins_url('/css/style.css', __FILE__),array(),$fydVersion );
//         wp_register_script( 'fodPluginScript', plugins_url('/js/functions.js', __FILE__),array(),$fydVersion );
//         wp_register_script( 'fodPluginScriptAdmin', plugins_url('/../js/admin.js', __FILE__),array(),$fydVersion );
    }

    public function plugin_adminmenu(){
        add_menu_page('LBRYworm', 'LBRYworm', 'manage_options', 'lbryworm-settings', array($this,'lbryworm_settings'), 'dashicons-chart-area');
        add_submenu_page( 'lbryworm-settings', 'Settings', 'Settings', 'manage_options', 'lbryworm-settings', array($this,'lbryworm_settings') );
        add_submenu_page( 'lbryworm-settings', 'API', 'API', 'manage_options', 'lbryworm-api', array($this,'lbryworm_api') );
        add_submenu_page( 'lbryworm-settings', 'Cruncher', 'Cruncher', 'manage_options', 'lbryworm-cruncher', array($this,'lbryworm_cruncher') );
        add_action( 'admin_print_styles', array($this,'plugin_admin_styles') );
    }
    
    public function lbryworm_settings(){
        ?>
        <h1>LBRYworm settings</h1>
        
        <?php
        if(isset($_POST['ll_save_settings'])){
            update_option('ll_cruncher_items',$_POST['ll_cruncher_items']);
            update_option('ll_cruncher_enabled',isset($_POST['ll_cruncher_enabled']));
            echo '<h2>settings saved!</h2><br/>';
        }
        
        
        if(isset($_POST['ll_clear_cache'])){
            global $wpdb;
            $wpdb->query("DELETE FROM `lbry_cache` WHERE `cache_key` NOT LIKE 'top_channels_%'");
            echo '<h2>Cache cleared!</h2><br/>';
        }
        
        $ll_cruncher_items=get_option('ll_cruncher_items');
        $ll_cruncher_enabled=get_option('ll_cruncher_enabled');
        ?>
        
        <form method="post">
            <div style="padding:10px; border:1px solid #555; width:90%;">
                <h2>Cruncher settings</h2>
                <div style="margin-bottom:10px;">
                    <input type="checkbox" name="ll_cruncher_enabled" value="ll_cruncher_enabled" <?php if($ll_cruncher_enabled){echo 'checked';} ?>>
                    <label for="dm_per_page">
                        Enable displayed and random entry crunching <!--(overrides next setting)-->
                    </label>
                </div>
                <div style="margin-bottom:10px;">
                    <label for="dm_per_page">
<!--                         Daily processing end minute (resume entry crunching after the minute in crunch o-clock, if previous is set) -->
                        Crunch <b>N</b> number of publishes per load:
                    </label>
                    <input name="ll_cruncher_items" value="<?php echo $ll_cruncher_items; ?>" type="number" min="1">
                </div>
            </div>
            <br/>
            
            <div>
                <input type="submit" value="Save Settings" name="ll_save_settings">
            </div>
        </form>
        
        <br/><br/>
        
        <form method="post">
            <div style="padding:10px; border:1px solid #555; width:90%;">
                <h2>Cache</h2>
            </div>
            <br/>
            
            <div>
                <input type="submit" value="Clear Cache" name="ll_clear_cache">
            </div>
        </form>
        
        <?php
    }
    public function lbryworm_api(){
        ?>
        <h1>LBRYworm - API settings</h1>
        
        <?php
        if(isset($_POST['ll_api_save_settings'])){
            update_option('lbry_api_token',$_POST['lbry_api_token']);
            echo '<h2>settings saved!</h2><br/>';
        }
        if(isset($_POST['ll_api_new_token'])){
            //update_option('lbry_api_token',$_POST['lbry_api_token']);
            //$this->LBRYworm->LBRYstatsPublish->update_api_token();
            echo '<h2>settings saved!</h2><br/>';
        }
        $lbry_api_token=get_option('lbry_api_token');
        ?>
        
        <form method="post">
            <div style="padding:10px; border:1px solid #555; width:90%;">
                <h2>LBRY API</h2>
                <div style="margin-bottom:10px;">
                    <label for="dm_per_page">
                        Token:<br/>
                        <a href="https://api.lbry.com/user/new" target="_blank">https://api.lbry.com/user/new</a>
                    </label>
                    <input name="lbry_api_token" value="<?php echo $lbry_api_token; ?>" type="text">
                </div>
            </div>
            <br/>
            
            <div>
                <input type="submit" value="Save Settings" name="ll_api_save_settings">
            </div>
        </form>
        <br><hr/><br/>
        <form method="post">
            <div>
                <input type="submit" value="New token" name="ll_api_new_token">
            </div>
        </form>
        <?php
    }
    public function lbryworm_cruncher(){
        $datekey=date('Ymd');
        $prevdatekey=date('Ymd', strtotime('-1 day'));
        ?>
        <h1>Views and followers daily data processing</h1>
        <hr/>
        <p>
            <b>last run:</b> <?php echo date('Y.m.d. 00:00:00',time()).' UTC'; ?>
        </p>
        <p>
            <b>processed channels:</b> <?php echo get_option('ll_stats_'.$datekey.'_run_processed_channels'); ?>
        </p>
        <p>
            <b>processed publishes:</b> <?php echo get_option('ll_stats_'.$datekey.'_run_processed_publishes'); ?>
        </p>
        <p>
            <b>total processing time:</b> <?php echo get_option('ll_stats_'.$datekey.'_run_processing_time'); ?> s
        </p>
        <hr/>
        <p>
            <b>next run:</b> <?php echo date('Y.m.d. 00:00:00',time()+3600*24).' UTC'; ?>
        </p>
        <p>
            <b>next run expected duration:</b>  &lt; <?php echo intval(round( (get_option('ll_stats_'.$datekey.'_run_processing_time')*3)/60 )); ?> minutes
        </p>
        <h1>Previous day</h1>
        <hr>
        <p>
            <b>processed channels:</b> <?php echo get_option('ll_stats_'.$prevdatekey.'_run_processed_channels'); ?>
        </p>
        <p>
            <b>processed publishes:</b> <?php echo get_option('ll_stats_'.$prevdatekey.'_run_processed_publishes'); ?>
        </p>
        <p>
            <b>total processing time:</b> <?php echo get_option('ll_stats_'.$prevdatekey.'_run_processing_time'); ?> s
        </p>
        
        <hr/>
        
        <h1>Daily stats</h1>
        <a href="https://www.lbryworm.com/wp-content/plugins/lbryworm/cron/stats.php?browser=1" target="_blank">
            Autoreloading daily process - Manual run, remember to turn on cruncher!
        </a>
        
        
        <h1>Crunch views</h1>
        <a href="https://www.lbryworm.com/wp-content/plugins/lbryworm/cron/crunch_random_publish_views_channel_subs.php?reload&cruncher" target="_blank">
            Autoreloading Cruncher Start in new tab
        </a>
        
        
        <?php
        
        echo '<p>';
        echo 'linux time: '.exec('date').'<br/>';
        echo 'php time: '.date('Y m d G:i:s', time());
        echo '</p>';
        
    }
    
    
    //dashboard widget
    public function dashboard_widget_users() {
            ?>
            <h1>
                <b>
                <?php
                    $users=count_users();
                    echo $users['avail_roles']['subscriber'];
                ?> 
                </b>
                users
            </h1>
            
            <h1>
                <b>
                <?php
                    //echo $this->LBRYworm->LBRYstatsChannel->get_channels_count();
                ?>
                </b>
                unique channels
            </h1>
            
            <?php
            
            
    }
    
    //dashboard widget
    public function dashboard_widget_cruncher() {
            $this->lbryworm_cruncher();
            
    }
    
    public function dashboard_widget_links() {
        ?>
        <a href="/wp-admin/admin.php?page=lbryworm-settings">Settings</a><br/><br/>
        <a href="/wp-admin/admin.php?page=lbryworm-api">Api</a><br/><br/>
        <a href="/wp-admin/admin.php?page=lbryworm-cruncher">Cruncher</a><br/><br/>
        <?php
    }
    
    public function random_embed_locations() {
        $random_embed_locations = get_option('random_embed_locations');
        foreach($random_embed_locations as $location=>$impressions){
            echo '<a href="'.$location.'" target="_blank"><b>'.$location.'</b></a>: '.$impressions.' impressions <br/>';
        }
    }
    public function latest_embed_locations() {
        $latest_embed_locations = get_option('latest_embed_locations');
        foreach($latest_embed_locations as $location=>$impressions){
            echo '<a href="'.$location.'" target="_blank"><b>'.$location.'</b></a>: '.$impressions.' impressions <br/>';
        }
    }
    
    public function add_dashboard_widget() {
            add_meta_box( 'lbryworm_dashboard_meta_box_cruncher', 'LBRYworm Cruncher', array($this,'dashboard_widget_cruncher'), 'dashboard', 'side', 'high' );
            add_meta_box( 'lbryworm_dashboard_meta_box_users', 'LBRYworm Users', array($this,'dashboard_widget_users'), 'dashboard', 'side', 'high' );
            add_meta_box( 'lbryworm_dashboard_meta_box_links', 'LBRYworm', array($this,'dashboard_widget_links'), 'dashboard', 'side', 'high' );
            add_meta_box( 'lbryworm_dashboard_meta_box_random_embed_locations', 'LBRYworm Random Embed Locations', array($this,'random_embed_locations'), 'dashboard', 'side', 'high' );
            add_meta_box( 'lbryworm_dashboard_meta_box_latest_embed_locations', 'LBRYworm Latest Embed Locations', array($this,'latest_embed_locations'), 'dashboard', 'side', 'high' );
    }
    
    
    
    
    public function add_toolbar_items($admin_bar){
        $admin_bar->add_menu( array(
            'id'    => 'll-toolbar-holder',
            'title' => __('LBRYworm Administration'),
            'href'  => '#',
            'meta'  => array(
                'title' => __('LBRYworm Administration'),
            ),
        ));
        $admin_bar->add_menu( array(
            'id'    => 'll-toolbar-settings',
            'parent' => 'll-toolbar-holder',
            'title' => 'Settings',
            'href'  => site_url().'/wp-admin/admin.php?page=lbryworm-settings',
            'meta'  => array(
                'title' => __('Settings'),
                //'target' => '_blank',
                'class' => 'll-toolbar-settings'
            ),
        ));
        
    }
    
    
    

}
