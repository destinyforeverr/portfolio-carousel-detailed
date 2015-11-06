<?php
/* Template Name: Custom Portfolio Template */
// Get site header
get_header(); ?>
<div id="content-wrap" class="container clr">
    <?php wpex_hook_primary_before(); ?>
    <div id="primary" class="content-area clr">
        <?php wpex_hook_content_before(); ?>
        <div id="content" class="clr site-content">
            <?php wpex_hook_content_top(); ?>
            <?php
            // Main page loop
            while ( have_posts() ) : the_post(); ?>
            <article class="clr">
                <?php
                // Check if page should display featured image
                if ( has_post_thumbnail() && wpex_get_mod( 'page_featured_image' ) ) : ?>
                <div id="page-featured-img" class="clr">
                    <?php
                    // Dislpay full featured image
                    wpex_post_thumbnail( array(
                    'size'  => 'full',
                    'alt'   => wpex_get_esc_title(),
                    ) ); ?>
                    
                </div><!-- #page-featured-img -->
                <?php endif; ?>
                <div class="entry-content entry clr">
                    <?php the_content(); ?>
                </div><!-- .entry-content -->
                <?php get_template_part( 'partials/social', 'share' ); ?>
            </article><!-- #post -->
            <?php endwhile; wp_reset_query();?>
            <?php 
            /* Our Template starts from here */
            $portfolio = new WP_Query('post_type=portfolio');
            if($portfolio->have_posts()){
                $count = 0;
                while ($portfolio->have_posts()) {                    
                    $portfolio->the_post();
                    if($count == 0){$firstPostID = get_the_ID(); $count++;}
                    if (has_post_thumbnail() ) {
                        $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' );
                        $itemImage = '<img src="'.$large_image_url[0].'" alt="'.get_the_title(get_the_ID()).'"/>';
                    }else{
                        $itemImage = 'No Image Found';
                    }
                    $itemWrapper .= '
                        <div class="itemWrapper swiper-slide">
                            <form action="" method="POST">
                                <div class="itemImage">
                                    '.$itemImage.'
                                </div>
                                <div class="itemContents">
                                    <input type="hidden" value="'.get_the_ID().'" name="keyid"/>
                                    <input type="submit" value="'.get_the_title(get_the_ID()).'"/>
                                    <div class="itemInnerContents">'.get_the_excerpt().'</div>    
                                </div>
                            </form>
                        </div>
                    ';
                }
                wp_reset_query();
            }
            echo '<div class="custom-portfolio">';
                echo '
                    <div class="custom-portfolio-items swiper-container">
                        <div class="swiper-wrapper">'.$itemWrapper.'</div>
                        <div class="swiper-scrollbar"></div>
                    </div>';
                echo '<div class="custom-portfolio-details">';
                    if($_POST['keyid'] != ''){
                        $itemDetailWrapper = get_post($_POST['keyid']);
                        //echo '<h2>'.$itemDetailWrapper->post_title.'</h2>';
                        echo '<div class="itemDetails">'.do_shortcode($itemDetailWrapper->post_content).'</div>';
                    }else{
                        if($firstPostID != ''){
                            $itemDetailWrapper = get_post($firstPostID);                            
                            //echo '<h2>'.$itemDetailWrapper->post_title.'</h2>';
                            echo '<div class="itemDetails">'.do_shortcode($itemDetailWrapper->post_content ).'</div>';  
                        }else{
                            echo "There is no post to show";
                        }
                    }
                echo '</div>';
            echo '</div>';
            ?>
            <?php wpex_hook_content_bottom(); ?>
        </div><!-- #content -->
        <?php wpex_hook_content_after(); ?>
    </div><!-- #primary -->
    <?php wpex_hook_primary_after(); ?>
</div><!-- #content-wrap -->
<?php get_footer(); ?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/custom/swiper.min.css">
<style>
.swiper-container {
    width: 100%;
    height: 350px;
    margin: 20px auto;
}
.swiper-container-horizontal>.swiper-scrollbar {
    position: absolute;
    left: 16% !important;
    bottom: 1px;
    z-index: 50;
    height: 10px;
    width: 60% !important;
}
.swiper-slide {    
    background: #fff;
    width: 40%;

    /* Center slide text vertically */
    display: -webkit-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    -webkit-justify-content: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
    align-items: center;
}
.itemImage{float:left; margin: 0px 15px 0 0; width: 40%;}
.itemImage img {width: 100%;}
.itemContents{float: left; width: 55%; padding: 0 35px 0 20px; text-align: justify; border-right: 1px solid #ccc; }
.itemContents input[type="submit"]{background: none; color: #444; width: 100%; text-align: center; font-size: 22px; padding-top: 0px;}
/*.custom-portfolio-details > h2{background: #444; text-align: center; color: #fff; padding: 10px 0; font-size: 30px;}*/
</style>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/custom/swiper.min.js"></script>
<script>
var swiper = new Swiper('.swiper-container', {
    scrollbar: '.swiper-scrollbar',
    scrollbarHide: false,
    slidesPerView: 2,
    centeredSlides: false,
    spaceBetween: 30,
    grabCursor: true
});
</script>