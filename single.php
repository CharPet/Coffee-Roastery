<?php
get_header();
?>

<main class="single-main">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article class="single-article">
        <header class="article-header">
            <h1 class="single-article-title"><?php the_title(); ?></h1>
            <div class="article-meta">
                <time datetime="<?php echo get_the_date('c'); ?>" class="article-date">
                    <?php echo get_the_date(); ?>
                </time>
                <?php if (get_the_author()) : ?>
                <span class="article-author">Από <?php the_author(); ?></span>
                <?php endif; ?>
            </div>
        </header>

        <?php if (has_post_thumbnail()) : ?>
        <div class="article-image-container">
            <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>"
                class="single-article-image" />
        </div>
        <?php endif; ?>

        <div class="single-article-content">
            <?php the_content(); ?>
        </div>

        <footer class="article-footer">
            <?php if (has_category()) : ?>
            <div class="article-categories">
                <span class="categories-label">Κατηγορίες:</span>
                <?php the_category(', '); ?>
            </div>
            <?php endif; ?>

            <?php if (has_tag()) : ?>
            <div class="article-tags">
                <span class="tags-label">Ετικέτες:</span>
                <?php the_tags('', ', ', ''); ?>
            </div>
            <?php endif; ?>
        </footer>
    </article>
    <?php endwhile; else : ?>
    <div class="no-content">
        <h2>Το άρθρο δεν βρέθηκε</h2>
        <p>Λυπούμαστε, αλλά το περιεχόμενο που ψάχνετε δεν είναι διαθέσιμο.</p>
    </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>