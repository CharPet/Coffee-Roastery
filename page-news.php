<?php
/* Template Name: News */
get_header();
?>

<h2 class="our-news-title">Τα Νέα μας</h2>
<section class="collage">
    <div>
        <img src="<?php echo get_template_directory_uri(); ?>/images/xecprq7kb9w1t7bkgivs.webp" alt="" />
    </div>

    <div>
        <img src="<?php echo get_template_directory_uri(); ?>/images/ru5fdis1oqjorf0hieyq.webp" alt="" />
    </div>

    <div>
        <img src="<?php echo get_template_directory_uri(); ?>/images/coffee-7897414_1280.webp" alt="" />
    </div>

    <div aria-hidden="true"></div>
    <div>
        <img class="collage-logo" src="<?php echo get_template_directory_uri(); ?>/icons/terra coffee logo2 1.webp"
            alt="" />
    </div>

    <div aria-hidden="true"></div>

    <div>
        <img src="<?php echo get_template_directory_uri(); ?>/images/nn1ktpsumunyd1unhhfz.webp" alt="" />
    </div>

    <div aria-hidden="true"></div>

    <div aria-hidden="true"></div>

    <div>
        <img src="<?php echo get_template_directory_uri(); ?>/images/nathan-dumlao-KixfBEdyp64-unsplash.webp" alt="" />
    </div>

    <div>
        <img src="<?php echo get_template_directory_uri(); ?>/images/zvrikkavccn3xs390k0e.webp" alt="" />
    </div>

    <div>
        <img src="<?php echo get_template_directory_uri(); ?>/images/jrbogbrrdbb0je25zdhv.webp" alt="" />
    </div>
</section>

<main>

    <?php
    $news_query = new WP_Query([
        'posts_per_page' => 3,
        // 'category_name' => 'news',
    ]);
    if ($news_query->have_posts()) :
        while ($news_query->have_posts()) : $news_query->the_post(); ?>
    <div class="our-news-text">
        <h2 class="news-article-title"><?php the_title(); ?></h2>
        <p>
            <?php
                    // Get the post content and extract the first sentence
                    $content = get_the_content();
                    $content = strip_tags($content);
                    $sentences = preg_split('/(\.|\!|\?)\s/', $content, 2, PREG_SPLIT_DELIM_CAPTURE);
                    echo isset($sentences[0]) ? $sentences[0] . (isset($sentences[1]) ? $sentences[1] : '') . '..' : '';
                    ?>
            <a href="<?php the_permalink(); ?>">Συνέχεια άρθρου &rarr;</a>
        </p>
        <?php if (has_post_thumbnail()) : ?>
        <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>"
            class="our-news-images" />
        <?php endif; ?>
    </div>
    <?php endwhile;
        wp_reset_postdata();
    else : ?>
    <p>Δεν υπάρχουν νέα αυτή τη στιγμή.</p>
    <?php endif; ?>


</main>

<?php get_footer(); ?>