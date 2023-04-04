<?php
/**
 * @var int                     $page
 * @var string                  $search
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
        <div>
            <form action="" method="get">
                <label for="search">Search</label>:
                <input id="search" name="srch" class="text" type="search" value="<?php echo esc_attr( $search ); ?>"/>
                <button type="submit">Search</button>
            </form>
        </div>
        <table>
            <thead>
            <tr>
                <th>Artist</th>
                <th>Titile</th>
                <th>Length</th>
                <th>Started</th>
                <th>YouTube</th>
                <th>YT Music</th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ( $result->items as $item ) : ?>
                <tr>
                    <td><?php echo esc_html( $item->artist_name ); ?></td>
                    <td><?php echo esc_html( $item->title ); ?></td>
                    <td><?php echo esc_html( rapl_format_runtime( $item->length ) ); ?></td>
                    <td>
                        <time datetime="<?php echo esc_attr( $item->started ); ?>">
							<?php
							$time = explode( ' ', rapl_format_timestamp( $item->started ) );;
							echo esc_html( "$time[0] $time[1] $time[2]" ) .
							     '<br>' .
							     esc_html( "$time[3] $time[4] $time[5] $time[6]" );
							?>
                        </time>
                    </td>
                    <td>
                        <a href="<?php echo esc_url( RAPL_YouTube::get_direct_url( $item->track_id, 'video' ) ); ?>"
                           target="_blank">Direct</a>
                        <br>
                        <a href="<?php echo esc_url( RAPL_YouTube::get_search_query_url( $item->artist_name, $item->title, 'video' ) ); ?>"
                           target="_blank"
                           rel="nofollow noreferrer external">Search</a>
                    </td>
                    <td>
                        <a href="<?php echo esc_url( RAPL_YouTube::get_direct_url( $item->track_id, 'music' ) ); ?>"
                           target="_blank">Direct</a>
                        <br>
                        <a href="<?php echo esc_url( RAPL_YouTube::get_search_query_url( $item->artist_name, $item->title, 'music' ) ); ?>"
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
			$prev      = max( 1, $page - 1 );
			$next      = min( $result->total_pages, $page + 1 );
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
