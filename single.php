<?php
get_header();
?>

<main>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article class="single-article">
        <h1 class="single-article-title"><?php the_title(); ?></h1>
        <?php if (has_post_thumbnail()) : ?>
        <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>"
            class="single-article-image" />
        <?php endif; ?>
        <div class="single-article-content">
            <?php the_content(); ?>
        </div>
    </article>
    <?php endwhile; else : ?>
    <p>Το άρθρο δεν βρέθηκε.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>