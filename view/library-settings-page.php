<div class="wrap">
	<h2> Funny Library </h2>
	<h3> <?php _e( 'For front use shortcode: [library_data /]', 'library-txt' ); ?> </h3>

	<!-- INSERT -->
	<form method="POST" action="">
		<table class="form-table">
			<tbody>
			<tr>
				<th colspan="2">
					<h3> <?php _e( 'Add new Book', 'library-txt' ); ?> </h3>
				</th>
			</tr>
			<tr>
				<th scope="row"> <?php _e( 'Add Book Name', 'library-txt' ); ?> *</th>
				<td><input type="text" name="add_book_name" value="" required
				           placeholder="<?php _e( 'Name', 'library-txt' ) ?>"/></td>
			</tr>
			<tr>
				<th scope="row"> <?php _e( 'Add Book Style', 'library-txt' ); ?> </th>
				<td><input type="text" name="add_book_style" value=""
				           placeholder="<?php _e( 'Style', 'library-txt' ) ?>"/></td>
			</tr>
			<tr>
				<th scope="row"> <?php _e( 'Add Book Author', 'library-txt' ); ?> *</th>
				<td><input type="text" name="add_book_author" value="" required
				           placeholder="<?php _e( 'Author', 'library-txt' ) ?>"/></td>
			</tr>
			</tbody>
		</table>
		<input type="submit" value="Save book" class="button-primary" name="add_books">
	</form>

	<!-- UPDATE	-->
	<form method="POST" action="">
		<table class="form-table">
			<tbody>
			<tr>
				<th colspan="2">
					<h3> <?php _e( 'Update name Book by Author', 'library-txt' ); ?> </h3>
				</th>
			</tr>
			<tr>
				<th scope="row"> <?php _e( 'Insert author', 'library-txt' ); ?> *</th>
				<td><input type="text" name="update_book_author" value="" required
				           placeholder="<?php _e( 'Insert author', 'library-txt' ) ?>"/></td>
			</tr>
			<tr>
				<th scope="row"> <?php _e( 'Update book name', 'library-txt' ); ?> *</th>
				<td><input type="text" name="update_book_name" value="" required
				           placeholder="<?php _e( 'Update book name', 'library-txt' ) ?>"/></td>
			</tr>
			</tbody>
		</table>
		<input type="submit" value="Update book" class="button-primary" name="update_books">
	</form>

	<!-- DELETE -->
	<form method="POST" action="">
		<table class="form-table">
			<tbody>
			<tr>
				<th colspan="2">
					<h3> <?php _e( 'Delete Book by Author', 'library-txt' ); ?> </h3>
				</th>
			</tr>
			<tr>
				<th scope="row"> <?php _e( 'Insert author', 'library-txt' ); ?> *</th>
				<td><input type="text" name="delete_book_author" value="" required
				           placeholder="<?php _e( 'Insert author for del', 'library-txt' ) ?>"/></td>
			</tr>
			<tr>
				<th scope="row"> <?php _e( 'DELETE book name', 'library-txt' ); ?> *</th>
				<td><input type="text" name="delete_book_name" value="" required
				           placeholder="<?php _e( 'Delete book name', 'library-txt' ) ?>"/></td>
			</tr>
			</tbody>
		</table>
		<input type="submit" value="Drop book" class="button-primary" name="delete_books">
	</form>

</div>