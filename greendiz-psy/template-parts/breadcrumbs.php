<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$items = $args['items'] ?? array();
if ( ! $items ) {
	return;
}
?>
<nav aria-label="Хлебные крошки"><ol itemscope itemtype="https://schema.org/BreadcrumbList">
<?php foreach ( array_values( $items ) as $position => $item ) : ?>
<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"<?php echo $item['url'] === '' ? ' aria-current="page"' : ''; ?>><?php if ( $item['url'] !== '' ) : ?><a itemprop="item" href="<?php echo esc_url( $item['url'] ); ?>"><span itemprop="name"><?php echo esc_html( $item['title'] ); ?></span></a><?php else : ?><span itemprop="name"><?php echo esc_html( $item['title'] ); ?></span><?php endif; ?><meta itemprop="position" content="<?php echo absint( $position + 1 ); ?>"></li>
<?php endforeach; ?>
</ol></nav>
