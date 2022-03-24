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

    }
    public function plugin_admin_init() {

    }

    public function plugin_adminmenu(){
        add_menu_page('LBRYworm', 'LBRYworm', 'manage_options', 'lbryworm-settings', array($this,'lbryworm_settings'), 'dashicons-chart-area');
        add_submenu_page( 'lbryworm-settings', 'Settings', 'Settings', 'manage_options', 'lbryworm-settings', array($this,'lbryworm_settings') );
        //add_submenu_page( 'lbryworm-settings', 'API', 'API', 'manage_options', 'lbryworm-api', array($this,'lbryworm_api') );
        add_action( 'admin_print_styles', array($this,'plugin_admin_styles') );
    }
    
    public function lbryworm_settings(){
        ?>
        <h1>LBRYworm settings</h1>
        
        <?php
        if(isset($_POST['lw_save_settings'])){
            //update_option('lw_setting_1',$_POST['lw_setting_1']);
            update_option('lw_setting_1',isset($_POST['lw_setting_1']));
            echo '<h2>settings saved!</h2><br/>';
        }
        
        $lw_setting_1=get_option('lw_setting_1');
        ?>
        
        <form method="post">
            <div style="padding:10px; border:1px solid #555; width:90%;">
                <h2>Cruncher settings</h2>
                <div style="margin-bottom:10px;">
                    <input type="checkbox" name="lw_setting_1" value="lw_setting_1" <?php if($lw_setting_1){echo 'checked';} ?>>
                    <label for="dm_per_page">
                        Setting 1
                    </label>
                </div>
            </div>
            <br/>
            
            <div>
                <input type="submit" value="Save Settings" name="lw_save_settings">
            </div>
        </form>
        
        
        <?php
    }
    public function lbryworm_api(){
        ?>
        <h1>LBRYworm - API settings</h1>
        
        
        <?php
    }

    
    
    
    public function dashboard_widget_links() {
        ?>
        <a href="/wp-admin/admin.php?page=lbryworm-settings">Settings</a><br/><br/>
        <a href="/wp-admin/admin.php?page=lbryworm-api">Api</a><br/><br/>
        <?php
    }
    
    
    
    public function add_dashboard_widget() {
            add_meta_box( 'lbryworm_dashboard_meta_box_links', 'LBRYworm', array($this,'dashboard_widget_links'), 'dashboard', 'side', 'high' );
            
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
