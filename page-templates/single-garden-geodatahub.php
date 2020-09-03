<?php
/**
 * The Template for displaying Garden Post Types
 *
 */

function checkRemoteFile($url){
	$request = wp_remote_get($url);
  if( is_wp_error( $request ) ) {	return false;}
  $body = wp_remote_retrieve_body( $request );
  $data = json_decode( $body );

	if ($data->error->code === 404){return false;}
	else{return true;}
 }




get_header(); ?>
<div id="main" class="wrapper contentPage">
	<?php if ( function_exists('yoast_breadcrumb') ){yoast_breadcrumb('<div id="top-meta"><p id="breadcrumbs">','</p></div>');} ?>
	<div id="primary" class="site-content <?php if ( GARDEN_FULL_WIDTH ){ echo 'full-width'; }?>">
		<div id="content" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php while ( have_posts() ) : the_post(); ?>

						<header class="entry-header">
							<h1 class="entry-title"><?php the_title(); ?></h1>
						</header>


						<div class="entry-content">

              <div class="bs-callout"><p>This page is generated using data from the <a href="https://cityofguelph.maps.arcgis.com/apps/opsdashboard/index.html#/b32c697c9cff4078b617afdac05189de">Community Gardens and Pollinator/Wildlife Gardens Map</a>, made available at the <a href="http://geodatahub-cityofguelph.opendata.arcgis.com">City of Guelph's GeoDataHub</a>.</div>

							<?php the_content(); ?>

							<?php if( get_field('garden_geodatahub_address') || get_field('garden_geodatahub_directions') ): ?>
								<h2>Location</h2>
								<p>
								<?php if( get_field('garden_geodatahub_address') ): ?>
										<?php the_field('garden_geodatahub_address'); ?>
								<?php endif; ?>
								<?php if( get_field('garden_geodatahub_directions') ): ?>
										  <strong>|</strong>   <?php the_field('garden_geodatahub_directions'); ?>
								<?php endif; ?>
								</p>
							<?php endif; ?>

							<?php if( get_field('garden_geodatahub_description') ): ?>
								<h2>About the garden</h2>
									<p><?php the_field('garden_geodatahub_description'); ?></p>
							<?php endif; ?>


							<ul>
								<?php $categories = get_the_terms(get_the_ID(), 'garden-type');
								if ( ! empty( $categories ) ) : ?>
									<li><strong>Garden Type: </strong><?php echo esc_html( $categories[0]->name) ?></li>
								<?php endif; ?>
								<?php if( get_field('garden_geodatahub_available_plots')) : ?>
									<li><strong>Available Plots: </strong><?php the_field('garden_geodatahub_available_plots'); ?></li>
								<?php endif; ?>
								<?php if( get_field('garden_geodatahub_accessible')) : ?>
									<li><strong>Accessible: </strong><?php the_field('garden_geodatahub_accessible'); ?></li>
								<?php endif; ?>
								<?php if( get_field('garden_geodatahub_ownership')) : ?>
									<li><strong>Ownership: </strong><?php the_field('garden_geodatahub_ownership'); ?></li>
								<?php endif; ?>
								<?php if( get_field('garden_geodatahub_maintained_by')) : ?>
									<li><strong>Maintained by: </strong><?php the_field('garden_geodatahub_maintained_by'); ?></li>
								<?php endif; ?>
							</ul>

							<?php if( get_field('garden_geodatahub_get_involved') ): ?>
								<h2>Get involved</h2>
									<p><?php the_field('garden_geodatahub_get_involved'); ?></p>
							<?php endif; ?>

							<?php if( get_field('garden_geodatahub_email') ): ?>
								<p>For more information, please email <a href="mailto:<?php the_field('garden_geodatahub_email'); ?>"> <?php the_field('garden_geodatahub_email'); ?></a></p>
							<?php endif; ?>


							<?php $images = get_field('garden_geodatahub_gallery', false, false);
							if( $images ):
								echo '<h2>Photo Gallery</h2>';
								echo do_shortcode('[gallery size="large" columns="3" ids="'.implode(',',$images).'"]');
							endif; ?>

						</div><!-- .entry-content -->

				<?php endwhile; // end of the loop. ?>
			</article><!-- #post -->
		</div><!-- #content -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
	<?php get_footer(); ?>
	<?php
