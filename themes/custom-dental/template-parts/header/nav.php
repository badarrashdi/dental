<?php
/**
 * Primary navigation (desktop) — DentalKart style horizontal category pills strip.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav class="dm-nav" aria-label="<?php esc_attr_e( 'Primary', 'dentomart' ); ?>">
	<div class="dm-container">
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_class'     => 'dm-nav__menu',
				'container'      => false,
				'depth'          => 2,
				'fallback_cb'    => false,
			) );
		} else {
			$product_cats = get_terms( array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'parent'     => 0,
				'number'     => 12,
			) );
			?>
			<ul class="dm-nav__menu">
				<li class="menu-item menu-item--featured menu-item-has-children">
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
						<span class="dm-nav__icon"><?php echo dentomart_icon( 'box', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span><?php esc_html_e( 'Shop By Category', 'dentomart' ); ?></span>
						<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-left: 3px;"><polyline points="6 9 12 15 18 9"/></svg>
					</a>
					<div class="dm-mega-menu">
						<div class="dm-mega-menu__header">
							<strong><?php esc_html_e( 'Popular Dental Categories', 'dentomart' ); ?></strong>
							<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'View All Categories →', 'dentomart' ); ?></a>
						</div>
						<div class="dm-mega-menu__grid">
							<?php if ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ) : ?>
								<?php foreach ( $product_cats as $cat ) : ?>
									<a class="dm-mega-menu__item" href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
										<span class="dm-mega-menu__item-name"><?php echo esc_html( $cat->name ); ?></span>
										<?php if ( $cat->count > 0 ) : ?>
											<span class="dm-mega-menu__item-count"><?php echo esc_html( sprintf( __( '%d products', 'dentomart' ), $cat->count ) ); ?></span>
										<?php endif; ?>
									</a>
								<?php endforeach; ?>
							<?php else : ?>
								<a class="dm-mega-menu__item" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><span>Endodontics & Rotary Files</span></a>
								<a class="dm-mega-menu__item" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><span>Orthodontics & Pliers</span></a>
								<a class="dm-mega-menu__item" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><span>Instruments & Handpieces</span></a>
								<a class="dm-mega-menu__item" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><span>Equipment & Machinery</span></a>
								<a class="dm-mega-menu__item" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><span>Prosthodontics & Cements</span></a>
								<a class="dm-mega-menu__item" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><span>Disposables & Consumables</span></a>
								<a class="dm-mega-menu__item" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><span>Infection Control</span></a>
								<a class="dm-mega-menu__item" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><span>Implants & Surgical</span></a>
							<?php endif; ?>
						</div>
					</div>
				</li>
				<li class="menu-item">
					<a href="<?php echo esc_url( home_url( '/brands/' ) ); ?>">
						<span class="dm-nav__icon"><?php echo dentomart_icon( 'badge-check', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span><?php esc_html_e( 'Brands', 'dentomart' ); ?></span>
					</a>
				</li>
				<li class="menu-item">
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) . '?orderby=popularity' ); ?>">
						<span class="dm-nav__icon"><?php echo dentomart_icon( 'star', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span><?php esc_html_e( 'Best Sellers', 'dentomart' ); ?></span>
					</a>
				</li>
				<li class="menu-item menu-item--deal">
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) . '?on_sale=1' ); ?>">
						<span class="dm-nav__icon"><?php echo dentomart_icon( 'fire', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span><?php esc_html_e( 'Offers & Deals', 'dentomart' ); ?></span>
					</a>
				</li>
				<li class="menu-item">
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) . '?orderby=date' ); ?>">
						<span class="dm-nav__icon"><?php echo dentomart_icon( 'tag', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span><?php esc_html_e( 'New Arrivals', 'dentomart' ); ?></span>
					</a>
				</li>
				<li class="menu-item">
					<a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">
						<span><?php esc_html_e( 'About Us', 'dentomart' ); ?></span>
					</a>
				</li>
				<li class="menu-item">
					<a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
						<span><?php esc_html_e( 'Contact Us', 'dentomart' ); ?></span>
					</a>
				</li>
			</ul>
			<?php
		}
		?>
	</div>
</nav>
