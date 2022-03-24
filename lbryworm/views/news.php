<div class="news">
    <?php
        $args = array(
            'post_type' => 'news',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ){
            
            $loop->the_post();
            
            ?>
            <div class="news_item">
                <?php
                    the_date();
                    echo ' - ';
                    the_title();
                ?>
                <?php
                    the_content();
                ?>
            </div>
            <?php
        }
        wp_reset_query();
    ?>
</div>
