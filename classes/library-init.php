<?php

/**
 * Class Library_Init
 */
class Library_Init {

	private $wpdb;
	private $table_books;
	private $table_authors;
	private $table_relations;

	public function __construct() {

		global $wpdb;
		$this->wpdb = $wpdb;
		// Tables
		$this->table_books     = $wpdb->prefix . BOOKS_TABLE_NAME;
		$this->table_authors   = $wpdb->prefix . AUTHORS_TABLE_NAME;
		$this->table_relations = $wpdb->prefix . RELATIONS_TABLE_NAME;
		// Db store
		add_action( 'init', array( $this, 'library_store_db' ) );
		// Shortcode
		add_shortcode( 'library_data', array( $this, 'library_get_shortcode' ) );
		// Scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'library_scripts' ) );
	}

	public function library_store_db() {

		// Insert vars
		$add_book_name   = filter_input( INPUT_POST, 'add_book_name', FILTER_SANITIZE_STRING );
		$add_book_style  = filter_input( INPUT_POST, 'add_book_style', FILTER_SANITIZE_STRING );
		$add_book_author = filter_input( INPUT_POST, 'add_book_author', FILTER_SANITIZE_STRING );
		// Update vars
		$update_book_author = filter_input( INPUT_POST, 'update_book_author', FILTER_SANITIZE_STRING );
		$update_book_name   = filter_input( INPUT_POST, 'update_book_name', FILTER_SANITIZE_STRING );
		// Delete vars
		$delete_book_author = filter_input( INPUT_POST, 'delete_book_author', FILTER_SANITIZE_STRING );
		$delete_book_name   = filter_input( INPUT_POST, 'delete_book_name', FILTER_SANITIZE_STRING );

		if ( ! empty( $_POST['add_books'] ) ):
			// 2. Insert Books
			$this->wpdb->query(
				$this->wpdb->prepare(
					"INSERT INTO $this->table_books ( `book_name`, `book_style` )
						VALUES ( %s, %s )",
					trim( $add_book_name ),
					trim( $add_book_style )
				)
			);
			$last_books_table_id = $this->wpdb->insert_id;
			$this->wpdb->query(
				$this->wpdb->prepare(
					"INSERT INTO $this->table_authors ( `author_name` ) 
						VALUES ( %s )",
					trim( $add_book_author )
				)
			);
			$last_authors_table_id = $this->wpdb->insert_id;
			$this->wpdb->query(
				$this->wpdb->prepare(
					"INSERT INTO $this->table_relations ( `author_id`, `book_id` )
						VALUES ( %d, %d )",
					trim( $last_books_table_id ),
					trim( $last_authors_table_id )
				)
			);
		elseif ( ! empty( $_POST['update_books'] ) ):
			// 4. Update Book
			$this->wpdb->query(
				$this->wpdb->prepare( "
					UPDATE $this->table_books
					LEFT JOIN $this->table_relations ON $this->table_books.id_b = $this->table_relations.book_id
					LEFT JOIN $this->table_authors ON $this->table_authors.id_a = $this->table_relations.book_id
					SET $this->table_books.book_name = '%s'
					WHERE $this->table_authors.author_name = '%s'
					",
					trim( $update_book_name ),
					trim( $update_book_author )
				)
			);
		elseif ( ! empty( $_POST['delete_books'] ) ):
			// 6. Delete book by author
			$this->wpdb->query(
				$this->wpdb->prepare( "
					DELETE $this->table_books
					FROM $this->table_books
					LEFT JOIN $this->table_relations ON $this->table_books.id_b = $this->table_relations.book_id
					LEFT JOIN $this->table_authors ON $this->table_authors.id_a = $this->table_relations.book_id
					WHERE $this->table_authors.author_name = '%s'
					AND $this->table_books.book_name = '%s'
					",
					trim( $delete_book_author ),
					trim( $delete_book_name )
				)
			);
		endif;

		$this->wpdb->show_errors();
	}

	public function library_get_shortcode() {

		// Selected value
		$selected_style = filter_input( INPUT_POST, 'select_book_style', FILTER_SANITIZE_STRING ); ?>

		<table class="table">
			<thead class="thead-dark">
			<tr>
				<th scope="col">#</th>
				<th scope="col">
					<h3> <?php _e( 'Select Book Style', 'library-txt' ); ?> </h3>
				</th>
				<th scope="col">
					<h3> <?php _e( 'Select Authors by Book', 'library-txt' ); ?> </h3>
				</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<th scope="row">1</th>
				<td>
					<form method="POST" autocomplete="off">
						<select name="select_book_style">
							<option disabled selected>Select Style</option>
							<?php // 3. Return all Books and Authors by Style
							$all_book_styles = $this->wpdb->get_results( "(SELECT DISTINCT book_style FROM $this->table_books)" );
							foreach ( $all_book_styles as $book_style ):
								if ( ! empty( $a_style = $book_style->book_style ) ): ?>
									<option
										value="<?php echo $a_style; ?>" <?php if ( $selected_style == $a_style ) : echo 'selected="selected"'; endif; ?>>
										<?php echo $a_style; ?>
									</option>
								<?php endif;
							endforeach; ?>
						</select>
						<input type="submit" value="Submit" name="do_style">
					</form>
				</td>
				<td>
					<form method="POST" autocomplete="off">
						<input type="checkbox" id="equal_three" name="equal_3_authors" value="true">
						<label for="equal_three">Equal 3 authors</label>

						<input type="checkbox" id="limit_three" name="to_3_authors" value="true">
						<label for="limit_three">Limit to 3 authors</label>

						<input type="submit" value="Submit" name="do_book">
					</form>
				</td>
			</tr>
			<tr>
				<th scope="row">2</th>
				<td>
					<?php // 3. Return all books and authors by style
					if ( ! empty( filter_input( INPUT_POST, 'do_style', FILTER_SANITIZE_STRING ) ) ):

						$books_by_style       = $this->wpdb->get_results( "(
						SELECT * FROM $this->table_books
				        LEFT JOIN $this->table_relations ON $this->table_books.id_b = $this->table_relations.book_id
				        LEFT JOIN $this->table_authors ON $this->table_authors.id_a = $this->table_relations.book_id
						WHERE $this->table_books.book_style = '$selected_style'
						)" );
						$str_books_by_style   = '';
						$str_authors_by_style = '';
						foreach ( $books_by_style as $the_book_style ) {
							$str_books_by_style   .= $the_book_style->book_name . ', <br>';
							$str_authors_by_style .= $the_book_style->author_name . ', <br>';
						}
						if ( ! empty( $str_books_by_style ) || ! empty( $str_authors_by_style ) ):
							echo 'BOOKS: ' . '<br>' . $str_books_by_style;
							echo 'AUTHORS: ' . '<br>' . $str_authors_by_style;
						endif;

					endif;
					?>
				</td>

				<td>
					<?php // 5. Return authors by book
					if ( ! empty( filter_input( INPUT_POST, 'do_book', FILTER_SANITIZE_STRING ) ) ):

						$str_books_by_check = '';
						$num_of_books       = '';
						if ( isset( $_POST['equal_3_authors'] ) == 'true' ):
							$num_of_books = 'num_of_books = 3';
						elseif ( isset( $_POST['to_3_authors'] ) == 'true' ):
							$num_of_books = 'num_of_books < 3';
						endif;
						$books_by_check = $this->wpdb->get_results( "(
									SELECT $this->table_books.book_name,
									COUNT($this->table_books.id_b) AS 'num_of_books' 
									FROM $this->table_books
							        LEFT JOIN $this->table_relations ON $this->table_books.id_b = $this->table_relations.book_id
							        LEFT JOIN $this->table_authors ON $this->table_authors.id_a = $this->table_relations.book_id
									GROUP BY $this->table_books.book_name
									HAVING $num_of_books
									)" );
						//print_r($books_by_check);
						foreach ( $books_by_check as $the_book_check ) {
							$str_books_by_check .= $the_book_check->book_name . ' (' . $the_book_check->num_of_books . ')' . ', <br>';
						}
						if ( ! empty( $str_books_by_check ) ):
							echo 'BOOKS (Authors counter): ' . '<br>' . $str_books_by_check;
						endif;

					endif;
					?>
				</td>
			</tr>
			</tbody>
		</table>

		<?php $this->wpdb->show_errors();
	}

	public function library_scripts() {

		if ( ! is_admin() ) {
			wp_enqueue_style( 'bootstrap.min.css', plugins_url( '/assets/css/bootstrap.css', dirname( __FILE__ ) ) );
		}
	}

//Library_Init end
}


