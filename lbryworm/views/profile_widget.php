<!-- <div class="widget_clean_login_widget"> -->
<span class="cleanlogin-preview-lw">
    <?php
    $login_url = get_option( 'cl_login_url', '');
    $edit_url = get_option( 'cl_edit_url', '');
    $register_url = get_option( 'cl_register_url', '');
    $restore_url = get_option( 'cl_restore_url', '');
    // Output stars
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        
        //echo '<div class="avatar">';
        
        if ( $edit_url != '' ){
                echo "<a href='$edit_url' class='edit_profile_url' title='Profile'>";
                        echo get_avatar( $current_user->ID, 96 );
                        //if ( $current_user->user_firstname == '')
                                //echo "<h1 class='widget-title'>$current_user->user_login</h1>";
                        //else
                                //echo "<h1 class='widget-title'>$current_user->user_firstname $current_user->user_lastname</h1>";
                echo '</a>';
        }
        
        //if ( $edit_url != '' || $login_url != '' ) echo "<ul>";
        
        if ( $edit_url != '' )
                //echo "<li><a href='$edit_url'><i class='fa fa-pencil'></i></a></li>";

        if ( $login_url != '' )
                echo "<a href='$login_url?action=logout' class='logout_link'><i class='fa fa-sign-out' title='Sign Out'></i></a>";
                //echo "<li><a href='$login_url?action=logout'><i class='fa fa-sign-out' title='Sign Out'></i></a></li>";
            
        echo "<a href='".site_url()."/library/' class='books_link' title='Library'><i class='fa fa-book'></i></a>";
        //echo "<li><a href='".site_url()."/library/' class='books_link' title='Library'><i class='fa fa-book'></i></a></li>";
            
        //if ( $edit_url != '' || $login_url != '' ) echo "</ul>";
        
        if(current_user_can("administrator")){
        //        echo "<a href='".site_url()."/wp-admin/' class='admin_link' title='Admin'><i class='fa fa-users-cog'></i></a>";
        }

    } else {
        //echo "<ul>";
        if ( $login_url != '' ) echo "<a href='$login_url' title='Login/Register' class='user_login_link'><i class='fa fa-user'></i></a>";
        //if ( $login_url != '' ) echo "<li><a href='$login_url'>". __( 'Log in', 'clean-login') ."</a></li>";
        //if ( $register_url != '' ) echo "<li><a href='$register_url'>". __( 'Register', 'clean-login') ."</a></li>";
        //if ( $restore_url != '' )echo "<li><a href='$restore_url'>". __( 'Lost password?', 'clean-login') ."</a></li>";
        //echo "</ul>";
    }

    ?>
</span>
