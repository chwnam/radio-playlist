<?php
/**
 * @var int                     $page
 * @var RAPL_Object_Track_Query $result
 */
?>
<div>
	<?php if ( $result->items ) : ?>
        <div>
            Page: <?php echo esc_html( $page ); ?><br/>
            Total: <?php echo esc_html( $result->total ); ?><br/>
            Query time: <?php echo esc_html( sprintf( '%.5f', $result->time_spent ) ); ?>s
        </div>
        <table>
            <thead>
            <tr>
                <th>Track ID</th>
                <th>Artist</th>
                <th>Titile</th>
                <th>Length</th>
                <th>Started</th>
                <th>YouTube</th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ( $result->items as $item ) : ?>
                <tr>
                    <td><?php echo esc_html( $item->track_id ); ?></td>
                    <td><?php echo esc_html( $item->artist ); ?></td>
                    <td><?php echo esc_html( $item->title ); ?></td>
                    <td><?php echo esc_html( rapl_format_runtime( $item->length ) ); ?></td>
                    <td><?php echo esc_html( rapl_format_timestamp( $item->started ) ); ?></td>
                    <td>
						<?php
						$url = add_query_arg(
							'search_query',
							urlencode( "$item->artist $item->title" ),
							'https://www.youtube.com/results'
						);
						?>
                        <a href="<?php echo esc_url( $url ); ?>"
                           target="_blank"
                           rel="nofollow noreferrer external">Search</a>
                    </td>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
        <p>
			<?php
            $page_link = remove_query_arg( 'pg' );
			$prev = max( 1, $page - 1 );
			$next = min( $result->total_pages, $page + 1 );
			?>
            <a href="<?php echo esc_url( add_query_arg( 'pg', 1, $page_link ) ); ?>">&laquo;</a>
            <a href="<?php echo esc_url( add_query_arg( 'pg', $prev, $page_link ) ); ?>">&lsaquo;</a>
			<?php echo esc_html( $page ); ?> / <?php echo esc_html( $result->total_pages ); ?>
            <a href="<?php echo esc_url( add_query_arg( 'pg', $next, $page_link ) ); ?>">&rsaquo;</a>
            <a href="<?php echo esc_url( add_query_arg( 'pg', $result->total_pages, $page_link ) ); ?>">&raquo;</a>
        </p>
	<?php else: ?>
        <p>No items.</p>
	<?php endif; ?>

</div>
