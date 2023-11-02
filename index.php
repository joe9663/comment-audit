<?php
/**
 * OmniDash
 * 
 *          @wordpress-plugin
 *          Plugin Name: Comment Auditor
 *          Plugin URI: https://www.imh.com/
 *          Description: A plugin designed to get information about your Post comments.
 *          Version: 1.0.0
 *          Author: IMH
 *          Author URI: https://www.imh.com/
 *          License: GPL-2.0+
 *          License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *          Text Domain: comment-auditor
 *          Domain Path: /languages
 *
 **/

// Dashboard
	add_action( 'admin_menu', 'imh_ca_admin_menu' );

	function imh_ca_admin_menu() {
		add_menu_page( 'Comment Auditor', 'Comment Auditor', 'manage_options', 'imh-ca-admin-page.php', 'imh_ca_admin_page', 'dashicons-info-outline', 6  );
	}

	function imh_ca_admin_page() {
		
		$table_content = '';

		$all_comments = get_comments();
		$posts_for_review = [];
		
		foreach ( $all_comments as $comment ) {
			$parent_comment = get_comment( $comment->comment_parent );
			if ( ( $comment->comment_post_ID !== $parent_comment->comment_post_ID ) && ( $comment->comment_parent !== 0 ) ) {
				array_push( $posts_for_review, $comment->comment_post_ID );
			}
		}
		
		$post_count = array_count_values( $posts_for_review );

		?>
		<h1>Posts with Mismatched Comment Parents</h1>
		<table>
		<?php
		arsort( $post_count );
		foreach ( $post_count as $key => $value ) {
			echo '<tr><td><a href=' . get_permalink( $key ) . ' target="_blank">' . get_permalink( $key ) . '</a></td><td>' . $value . '</td></tr>';
		}
		?>
		</table>
		<?php
	}