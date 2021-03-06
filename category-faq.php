<?php
/**
 * The template for displaying faq category archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package McClelland Insurance
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<div class="content-box faq">
				<nav class="clearfix page-heading">
					<h2><a href="http://mcclellandinsurance.com/faq">FAQ</a></h2>
					<p><a href="#">Add A Question</a></p>
				</nav>
				<div class="faq-archive_container clearfix">
					
					<?include('template-parts/archive-faq/categories.php')?>

					<div class="faqs">
						<div class="next-posts-links">
							<span class="nav-previous"><?php next_posts_link('<i class="fa fa-arrow-left"></i> Older FAQs') ?></span>
							<span class="nav-next"><?php previous_posts_link('Newer FAQs <i class="fa fa-arrow-right"></i>') ?></span>
						</div>
						<?php while( have_posts() ): the_post(); ?>
							<div class="faq-single archive-list">
								<h2> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<img src=" <?php bloginfo(template_directory) ?>/img/faqthumbnail.png" alt="mcclelland insurance faq image">
								<p><?php the_excerpt(); ?></p>
							</div>
							<?php wp_reset_postdata(); ?>
						<?php endwhile ?>
					</div>
					
					<?include('template-parts/archive-faq/recent-faq.php')?>
					
				</div>
				<div id="content-cursor" data-post-parent="faq"></div>
			</div> <!-- /.content-box -->
		<?php else : ?>

			 <?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
