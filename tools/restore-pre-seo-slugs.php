<?php
/**
 * Restore every slug saved before the 2026-10-02 SEO architecture migration.
 * SEO titles, descriptions and focus keywords are intentionally preserved.
 *
 * Dry-run by default. Pass --apply to write changes.
 */

if ( PHP_SAPI !== 'cli' ) { exit( "CLI only.\n" ); }
require getcwd() . '/wp-load.php';

global $wpdb;
$apply = in_array( '--apply', $argv, true );
$safe_only = in_array( '--safe-only', $argv, true );
$backup_key = '_uc_pre_seo_architecture_20261002_post_name';
$rows = $wpdb->get_results( $wpdb->prepare(
	"SELECT p.ID, p.post_type, p.post_status, p.post_name, pm.meta_value AS original_slug
	 FROM {$wpdb->posts} p
	 INNER JOIN {$wpdb->postmeta} pm ON pm.post_id=p.ID
	 WHERE pm.meta_key=%s AND pm.meta_value<>''
	 ORDER BY p.ID",
	$backup_key
) );

$stats = array( 'backed_up' => count( $rows ), 'changed' => 0, 'restored' => 0, 'failed' => 0 );
foreach ( $rows as $row ) {
	$original = sanitize_title( $row->original_slug );
	if ( ! $original || $original === $row->post_name ) { continue; }
	$stats['changed']++;
	echo sprintf( "%s #%d [%s] %s => %s\n", $apply ? 'RESTORE' : 'DRY', $row->ID, $row->post_type, $row->post_name, $original );
	if ( ! $apply ) { continue; }
	// Remove only a temporary old-slug reservation for this exact target.
	delete_post_meta( $row->ID, '_wp_old_slug', $original );
	$result = wp_update_post( array( 'ID' => (int) $row->ID, 'post_name' => $original ), true );
	$actual = get_post_field( 'post_name', $row->ID );
	if ( ! $safe_only && ( is_wp_error( $result ) || $actual !== $original ) ) {
		// The imported multilingual database intentionally had identical slugs
		// in separate language taxonomies. WordPress's generic uniqueness check
		// appends another suffix, so restore the known-good backed-up value exactly.
		$wpdb->update( $wpdb->posts, array( 'post_name' => $original ), array( 'ID' => (int) $row->ID ), array( '%s' ), array( '%d' ) );
		clean_post_cache( $row->ID );
		$actual = get_post_field( 'post_name', $row->ID );
	}
	if ( $actual !== $original ) { $stats['failed']++; echo '  FAILED actual=' . $actual . "\n"; }
	else { $stats['restored']++; }
}

if ( $apply ) { flush_rewrite_rules( false ); }

echo wp_json_encode( array( 'mode' => $apply ? 'apply' : 'dry-run', 'stats' => $stats ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . PHP_EOL;
