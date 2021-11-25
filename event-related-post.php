<?php

/**
 * @package LB-EventRelatedPost
 * @version 1.2.5
 */
/*
Plugin Name: LB-EventRelatedPost
Plugin URI: https://localhost
Description: Plugin de mise en relation d'articles
Author: Louis
Version: 1.2.5
Author URI: https://louis-boulanger.fr
*/

// Function MAJ Github

require 'plugin-update-checker-4.11/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/Loubal70/LB-EventRelatedPost/blob/main/plugin.json',
	__FILE__,
	'LB-EventRelatedPost/event-related-post.php'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

// Fonction qui sert à voir les évènements qui ont la même catégorie
function EventRelatedPost($id = 0){

    // Si l'id n'est pas définie, je récupère l'id de l'article en cours sinon je prend l'id défini
    $id = $id === 0 ? get_the_ID() : $id;
    
    $terms = get_the_terms(get_the_ID(), 'event-category');

    if ( !$terms ) // Si je n'ai pas de terme, j'arrête la fonction
        return false;


    $terms = $terms[0]->slug;

    $args = [
        "post_type"         => "event",
        "posts_per_page"    => 3,
        'orderby'           => 'post_date',
        'order'             => 'DESC',
        'fields'            => 'id', // Sert à récupérer que les ID dans ma requête
        'post__not_in'      => array($id)  , // J'exclus le post que je le lis dans les posts conseillés
        "tax_query" => [
            [
                'taxonomy'  =>  'event-category',
                'field'     =>  'slug',
                'terms'      =>  $terms,
            ],
        ],
    ];

    return new WP_Query($args);
}

/*

    Comment appeler cette fonction ?


    <?php if ( function_exists( 'EventRelatedPost' ) ): $related_posts = EventRelatedPost(); ?>
    <?php if ( $related_posts && $related_posts->have_posts() ): ?>
            <div class="container">
                
                <h2>Évènements en relation</h2>
                <ul>
                    <?php 
                        while ( $related_posts->have_posts() ){
                            $related_posts->the_post();
                            the_title();
                        } 
                    ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>

*/

