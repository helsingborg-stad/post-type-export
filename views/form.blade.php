<div class="wrap">
    <h1><?php _e('Export post type', 'post-type-export' ); ?></h1>
    <div>
    <p><?php _e('Export post types as CSV.', 'post-type-export' ); ?></p>
        <form method="post" action="/wp-admin/admin-post.php">
            {!! wp_nonce_field('export', 'export-post-type') !!}
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="post_type"><?php _e('Post type', 'post-type-export'); ?></label>
                    </th>
                    <td>
                        <select name="post_type">
                            @foreach($postTypes as $postType)
                                <option value="{{ $postType }}">{{ ucfirst($postType) }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="action" value="export_csv">
            <p>
                <input name='submit' type='submit' class='button-primary' value='<?php _e('Export', 'post-type-export'); ?>' />
            </p>
        </form>
    </div>
</div>
