<?php

/**
 * Class Library_Hooks
 */
class Library_Hooks {

	// Activation hook
	public static function on_activation() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
		check_admin_referer( "activate-plugin_{$plugin}" );
		//exit( var_dump( $_GET ) );
		global $wpdb;
		$table_books     = esc_sql( $wpdb->prefix . BOOKS_TABLE_NAME );
		$table_authors   = esc_sql( $wpdb->prefix . AUTHORS_TABLE_NAME );
		$table_relations = esc_sql( $wpdb->prefix . RELATIONS_TABLE_NAME );
		//$charset_collate = $wpdb->get_charset_collate();
		// WARNING! Must have two spaces between words PRIMARY KEY and primary key!
		$sql_books = "CREATE TABLE IF NOT EXISTS $table_books (
		  id_b integer NOT NULL AUTO_INCREMENT,
		  book_name varchar(100) NOT NULL,
		  book_style varchar(100) NOT NULL,
		  PRIMARY KEY  (id_b)
		)";

		$sql_authors = "CREATE TABLE IF NOT EXISTS $table_authors (
		  id_a integer NOT NULL AUTO_INCREMENT,
		  author_name varchar(100) NOT NULL,
		  PRIMARY KEY  (id_a)
		)";

		$sql_relations = "CREATE TABLE IF NOT EXISTS $table_relations (
		  id_r_ab integer NOT NULL AUTO_INCREMENT,
		  author_id integer NOT NULL,
  		  book_id integer NOT NULL,
  		  FOREIGN KEY(author_id) REFERENCES $table_authors(id_a) ON DELETE CASCADE,
  		  FOREIGN KEY(book_id) REFERENCES $table_books(id_b) ON DELETE CASCADE,
  		  PRIMARY KEY  (id_r_ab)
		)"; // ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql_books );
		dbDelta( $sql_authors );
		dbDelta( $sql_relations );

		$wpdb->show_errors();
	}

	// NEED TO flush_rewrite_rules();
	// Deactivation hook
	public static function on_deactivation() {
		$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
		check_admin_referer( "deactivate-plugin_{$plugin}" );
		//exit( var_dump( $_GET ) );
		global $wpdb;
		/**
		 * WARNING!
		 * First must delete parent tables because if delete parent table first,
		 * child tables cant find parent by foreign key
		 */
		// Drop relations
		$table_relations = $wpdb->prefix . RELATIONS_TABLE_NAME;
		$sql_relations   = "DROP TABLE IF EXISTS $table_relations";
		$wpdb->query( $sql_relations );
		// Drop books
		$table_books = $wpdb->prefix . BOOKS_TABLE_NAME;
		$sql_books   = "DROP TABLE IF EXISTS $table_books";
		$wpdb->query( $sql_books );
		// Drop authors
		$table_authors = $wpdb->prefix . AUTHORS_TABLE_NAME;
		$sql_authors   = "DROP TABLE IF EXISTS $table_authors";
		$wpdb->query( $sql_authors );

		//flush_rewrite_rules();

	}

	// Uninstall hook
	public static function on_uninstall() {
	global $wpdb;
	/**
	 * WARNING!
	 * First must delete parent tables because if delete parent table first,
	 * child tables cant find parent by foreign key
	 * (or use SET FOREIGN_KEY_CHECKS=0; DROP TABLE 'name'; SET FOREIGN_KEY_CHECKS=1;)
	 */
	// Drop relations
	$table_relations = $wpdb->prefix . RELATIONS_TABLE_NAME;
	$sql_relations   = "DROP TABLE IF EXISTS $table_relations";
	$wpdb->query( $sql_relations );
	// Drop books
	$table_books = $wpdb->prefix . BOOKS_TABLE_NAME;
	$sql_books   = "DROP TABLE IF EXISTS $table_books";
	$wpdb->query( $sql_books );
	// Drop authors
	$table_authors = $wpdb->prefix . AUTHORS_TABLE_NAME;
	$sql_authors   = "DROP TABLE IF EXISTS $table_authors";
	$wpdb->query( $sql_authors );
}

}