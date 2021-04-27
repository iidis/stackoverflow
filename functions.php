<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

/**/



/*Alanavigaatio siirtyy alas*/

add_action( 'after_setup_theme', 'lh_move_secondary_nav' );
function lh_move_secondary_nav() {
    remove_action( 'generate_before_header', 'generate_add_secondary_navigation_before_header', 7 );
    add_action( 'generate_before_footer', 'generate_add_secondary_navigation_before_header', 7 );
}

/*Alanavigaatio säilyy riveinä, ei toggle*/

add_action( 'wp_enqueue_scripts', 'generate_dequeue_secondary_nav_mobile', 999 );
function generate_dequeue_secondary_nav_mobile() {
    wp_dequeue_style( 'generate-secondary-nav-mobile' );
}

/*MURUPOLKU
 * 
 * WordPress Breadcrumbs
 * author: Dimox
 * version: 2019.03.03
 * license: MIT
*/

function dimox_breadcrumbs() {

/* === OPTIONS === */
$text['home']     = 'Etusivu'; // text for the 'Home' link
$text['category'] = '%s'; // text for a category page
$text['search']   = ''; // text for a search results page
$text['tag']      = 'Artikkelit: "%s"'; // text for a tag page
$text['author']   = 'Artikkelit kirjoittajalta: %s'; // text for an author page
$text['404']      = 'Virheilmoitus 404'; // text for the 404 page
$text['page']     = '%s'; // text 'Page N'
$text['cpage']    = 'Kommentit %s'; // text 'Comment Page N'

$wrap_before    = '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList"> Olet tässä:'; // the opening wrapper tag
$wrap_after     = '</div><!-- .breadcrumbs -->'; // the closing wrapper tag
$sep            = '<span class="breadcrumbs__separator"> 

<svg class="murunuoli" aria-hidden="true" focusable="false" role="img" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve" height="2em" width="2em">
<style type="text/css">
.st0{fill:none;stroke:#1A1818;stroke-width:1.5;stroke-linecap:round;stroke-miterlimit:10;}
</style>
<g id="IKONI_nuoli-oikee" transform="translate(0.75 1.061)">
<path id="Path_62" class="st0" d="M23.62,16.25l7.33,7.33l-7.33,7.33"/>
<line id="Line_42" class="st0" x1="30.95" y1="23.58" x2="16.32" y2="23.58"/>
</g>
</svg></span>'; // separator between crumbs

$before         = '<span class="breadcrumbs__current">'; // tag before the current crumb
$after          = '</span>'; // tag after the current crumb



$show_on_home   = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
$show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
$show_current   = 1; // 1 - show current page title, 0 - don't show
$show_last_sep  = 1; // 1 - show last separator, when current page title is not displayed, 0 - don't show
/* === END OF OPTIONS === */

global $post;
$home_url       = home_url('/');
$link           = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
$link          .= '<a class="breadcrumbs__link" href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>';
$link          .= '<meta itemprop="position" content="%3$s" />';
$link          .= '</span>';
$parent_id      = ( $post ) ? $post->post_parent : '';
$home_link      = sprintf( $link, $home_url, $text['home'], 1 );

if ( is_home() || is_front_page() ) {
if ( $show_on_home ) echo $wrap_before . $home_link . $wrap_after;
} else {

$position = 0;

echo $wrap_before;

if ( $show_home_link ) {
$position += 1;
echo $home_link;
}

if ( is_category() ) {
$parents = get_ancestors( get_query_var('cat'), 'category' );
foreach ( array_reverse( $parents ) as $cat ) {
$position += 1;
if ( $position > 1 ) echo $sep;
echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
}
if ( get_query_var( 'paged' ) ) {
$position += 1;
$cat = get_query_var('cat');
echo $sep . sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
} else {
if ( $show_current ) {
if ( $position >= 1 ) echo $sep;
echo $before . sprintf( $text['category'], single_cat_title( '', false ) ) . $after;
} elseif ( $show_last_sep ) echo $sep;
}

} elseif ( is_search() ) {
if ( get_query_var( 'paged' ) ) {
$position += 1;
if ( $show_home_link ) echo $sep;
echo sprintf( $link, $home_url . '?s=' . get_search_query(), sprintf( $text['search'], get_search_query() ), $position );
echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
} else {
if ( $show_current ) {
if ( $position >= 1 ) echo $sep;
echo $before . sprintf( $text['search'], get_search_query() ) . $after;
} elseif ( $show_last_sep ) echo $sep;
}

} elseif ( is_year() ) {
if ( $show_home_link && $show_current ) echo $sep;
if ( $show_current ) echo $before . get_the_time('Y') . $after;
elseif ( $show_home_link && $show_last_sep ) echo $sep;

} elseif ( is_month() ) {
if ( $show_home_link ) echo $sep;
$position += 1;
echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position );
if ( $show_current ) echo $sep . $before . get_the_time('F') . $after;
elseif ( $show_last_sep ) echo $sep;

} elseif ( is_day() ) {
if ( $show_home_link ) echo $sep;
$position += 1;
echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position ) . $sep;
$position += 1;
echo sprintf( $link, get_month_link( get_the_time('Y'), get_the_time('m') ), get_the_time('F'), $position );
if ( $show_current ) echo $sep . $before . get_the_time('d') . $after;
elseif ( $show_last_sep ) echo $sep;

} elseif ( is_single() && ! is_attachment() ) {
if ( get_post_type() != 'post' ) {
$position += 1;
$post_type = get_post_type_object( get_post_type() );
if ( $position > 1 ) echo $sep;
echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->labels->name, $position );
if ( $show_current ) echo $sep . $before . get_the_title() . $after;
elseif ( $show_last_sep ) echo $sep;
} else {
$cat = get_the_category(); $catID = $cat[0]->cat_ID;
$parents = get_ancestors( $catID, 'category' );
$parents = array_reverse( $parents );
$parents[] = $catID;
foreach ( $parents as $cat ) {
$position += 1;
if ( $position > 1 ) echo $sep;
echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
}
if ( get_query_var( 'cpage' ) ) {
$position += 1;
echo $sep . sprintf( $link, get_permalink(), get_the_title(), $position );
echo $sep . $before . sprintf( $text['cpage'], get_query_var( 'cpage' ) ) . $after;
} else {
if ( $show_current ) echo $sep . $before . get_the_title() . $after;
elseif ( $show_last_sep ) echo $sep;
}
}

} elseif ( is_post_type_archive() ) {
$post_type = get_post_type_object( get_post_type() );
if ( get_query_var( 'paged' ) ) {
$position += 1;
if ( $position > 1 ) echo $sep;
echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->label, $position );
echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
} else {
if ( $show_home_link && $show_current ) echo $sep;
if ( $show_current ) echo $before . $post_type->label . $after;
elseif ( $show_home_link && $show_last_sep ) echo $sep;
}

} elseif ( is_attachment() ) {
$parent = get_post( $parent_id );
$cat = get_the_category( $parent->ID ); $catID = $cat[0]->cat_ID;
$parents = get_ancestors( $catID, 'category' );
$parents = array_reverse( $parents );
$parents[] = $catID;
foreach ( $parents as $cat ) {
$position += 1;
if ( $position > 1 ) echo $sep;
echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
}
$position += 1;
echo $sep . sprintf( $link, get_permalink( $parent ), $parent->post_title, $position );
if ( $show_current ) echo $sep . $before . get_the_title() . $after;
elseif ( $show_last_sep ) echo $sep;

} elseif ( is_page() && ! $parent_id ) {
if ( $show_home_link && $show_current ) echo $sep;
if ( $show_current ) echo $before . get_the_title() . $after;
elseif ( $show_home_link && $show_last_sep ) echo $sep;

} elseif ( is_page() && $parent_id ) {
$parents = get_post_ancestors( get_the_ID() );
foreach ( array_reverse( $parents ) as $pageID ) {
$position += 1;
if ( $position > 1 ) echo $sep;
echo sprintf( $link, get_page_link( $pageID ), get_the_title( $pageID ), $position );
}
if ( $show_current ) echo $sep . $before . get_the_title() . $after;
elseif ( $show_last_sep ) echo $sep;

} elseif ( is_tag() ) {
if ( get_query_var( 'paged' ) ) {
$position += 1;
$tagID = get_query_var( 'tag_id' );
echo $sep . sprintf( $link, get_tag_link( $tagID ), single_tag_title( '', false ), $position );
echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
} else {
if ( $show_home_link && $show_current ) echo $sep;
if ( $show_current ) echo $before . sprintf( $text['tag'], single_tag_title( '', false ) ) . $after;
elseif ( $show_home_link && $show_last_sep ) echo $sep;
}

} elseif ( is_author() ) {
$author = get_userdata( get_query_var( 'author' ) );
if ( get_query_var( 'paged' ) ) {
$position += 1;
echo $sep . sprintf( $link, get_author_posts_url( $author->ID ), sprintf( $text['author'], $author->display_name ), $position );
echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
} else {
if ( $show_home_link && $show_current ) echo $sep;
if ( $show_current ) echo $before . sprintf( $text['author'], $author->display_name ) . $after;
elseif ( $show_home_link && $show_last_sep ) echo $sep;
}

} elseif ( is_404() ) {
if ( $show_home_link && $show_current ) echo $sep;
if ( $show_current ) echo $before . $text['404'] . $after;
elseif ( $show_last_sep ) echo $sep;

} elseif ( has_post_format() && ! is_singular() ) {
if ( $show_home_link && $show_current ) echo $sep;
echo get_post_format_string( get_post_format() );
}

echo $wrap_after;

}
} // end of dimox_breadcrumbs()

add_shortcode( 'breadcrumbs', 'dimox_breadcrumbs' );

 /*Kommenttikenttä*/

/* Kommentti otsikon alle tuleva teksti */
add_action( 'comment_form_top', function() {
    ?>
        <p id="teksti">
			Voit kirjoittaa mielipiteesi uutisesta kommenttilaatikkoon. </p>
			<p id="teksti2">
Sinun pitää kirjoittaa myös nimesi tai keksiä nimimerkki.
</p> 
    <?php
} );

/* Muuta Jätä Kommentti -> Kommentoi */
add_filter( 'generate_leave_comment','tu_custom_leave_comment' );
function tu_custom_leave_comment() {
    return 'Kommentoi';
}

/* Siirrä kommentti laatikko alemmaksi */

add_filter( 'comment_form_fields', 'move_comment_field' );
function move_comment_field( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}

/* Muokkaa kommentti formin kentät ja placeholderia */

function tu_adjust_comment_form_fields( $fields ) {
    return array(
        'author' => '<div id="kommentti-nimi">Nimi tai nimimerkki:<label for="author" class="screen-reader-text" value="Nimi tai nimimerkki:">' . esc_html__( 'First Name and Location', 'generatepress' ) . '</label><input placeholder="Kirjoita nimi tai nimimerkki tähän" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" /></div>',
        
    );
}
/* Kill URL in comment form
Ehkä tarpeeton
*/
add_action( 'after_setup_theme', 'tu_add_comment_url_filter' );
function tu_add_comment_url_filter() {
    add_filter( 'comment_form_default_fields', 'tu_adjust_comment_form_fields', 20 );
}

/* Kommenttikentän placeholder */

add_filter( 'comment_form_defaults', function( $defaults ) {
    $defaults['comment_field'] = sprintf(
        '<div id="kommentti-laatikko"><p id="kommentti-teksti">Kommentti:</p><p class="comment-form-comment"><label for="comment" class="screen-reader-text">%1$s</label><textarea placeholder="Kirjoita kommentti tähän" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p></div>',
        esc_html__( 'Comment', 'generatepress' )
    );

    return $defaults;
}, 20 );

/* Kommentoi napin alle tuleva linkki */

add_action('comment_form', 'db_add_comment_content');
function db_add_comment_content() {
    echo '<a class="tietoturva" href="">Tietoturvaseloste</a>';
}

/* Clickable Excerpt = etusivulla uutisen teksti eli excerpt on klikattava ja se avaa uutisen */

function clickable_excerpt( $excerpt ) {
	return '<a href="'. get_the_permalink() .'">'. preg_replace( '|</?a.*?>|i', '', $excerpt ) .'</a>';
}
add_filter( 'get_the_excerpt', 'clickable_excerpt' );

function pagination_bar() {
    global $my_query;
 
    $total_pages = $my_query->max_num_pages;
 
    if ($total_pages > 1){
        $current_page = max(1, get_query_var('paged'));
 
        echo paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => '/page/%#%',
            'current' => $current_page,
            'total' => $total_pages,
        ));
    }
}



/* Lyhytkoodi Uutiskarkisto-sivulle */

if ( ! function_exists('vpsa_posts_shortcode') ) {
        function vpsa_posts_shortcode( $atts ){

            $atts = shortcode_atts( array(
                            'per_page'  =>      10,  
                            'order'     =>  'DESC',
                            'orderby'   =>  'date'
                    ), $atts );

            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

            $args = array(
                'post_type'         =>  'post',
                'posts_per_page'    =>  $atts["per_page"], 
                'order'             =>  $atts["order"],
                'orderby'           =>  $atts["orderby"],
                'paged'             =>  $paged
            );

            $query = new WP_Query($args);
			
/* Hakee, onko muuttujalla output postauksia*/
			
                    if($query->have_posts()) : $output;

/* Tehdään looppi outputille. Hakee tietokannasta kaikkea kivaa. */			
			
                        while ($query->have_posts()) : $query->the_post();

								$output .= '<div class="arkisto-uutiskuva"><a alt="' . the_title('','',false) . '" href="' . get_post_permalink() . '"><div class="arkisto-kuva">' . get_the_post_thumbnail() . '</div></a></div>';
                                $output .= '<h2 class="arkisto-title"><a href="' . get_permalink() . '" title="' . the_title('','',false) . '">' . the_title('','',false) . '<span class="arkisto-date"> (' . get_the_date() .')</span></a></h2>';
                                        $output .= '<p class="arkisto-teksti">' . get_the_excerpt() .'</p><br>';



              
                        endwhile;global $wp_query;
    $args_pagi = array(
            'base' => add_query_arg( 'paged', '%#%' ),
            'total' => $query->max_num_pages,
            'current' => $paged
            );
                        $output .= '<div class="post-nav">';
                            $output .= paginate_links( $args_pagi);


                        $output .= '</div>';

                    else:

                        $output .= '<p>Sorry, there are no posts to display</p>';

                    endif;

/* Pitää suorittaa, että sivupohjan tägit käyttävät pääkyselyn nykyisen postauksen uudelleen*/
			
                wp_reset_postdata();

                return $output;
        }
    }

    add_shortcode('vpsa_posts', 'vpsa_posts_shortcode');


/* shortcode for blank line */
function blankline() {
return 
	'<div style="background-color:#e5e5e5">&nbsp;</div> 
	<div style="">&nbsp;</div>';
}
add_shortcode('blank', 'blankline');

/* shortcode for tietoturvaseloste */
function tietoturva() {
    return '<a class="tietoturva" href="">Tietoturvaseloste</a>';
}
add_shortcode('tieto', 'tietoturva');


/* Javascript ReadSpeakeria varten */

wp_enqueue_script('custom-rs', '//f1-eu.readspeaker.com/script/7437/ReadSpeaker.js?pids=embhl', [], null, false);