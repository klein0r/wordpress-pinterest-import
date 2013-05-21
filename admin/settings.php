<div id="amazonsimpleadmin-general" class="wrap">
    <form method="post">

        <h2><?php _e('Pinterest Import Setup') ?></h2>

        <br/>

        <?php
        if (!empty($_asa_error)) {
            echo '<p><strong>Error:</strong> '. $_asa_error . '</p>';
        }
        ?>

        <p><?php _e('Fields marked with * are mandatory:') ?></p>

        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="_pinimp_feed_urls"><?php _e('Pinterest Feed URLs*:') ?></label>
                    </th>
                    <td>
                        <p>Please enter one URL per line</p>
                        <p>
                            <textarea name="_pinimp_feed_urls" id="_pinimp_feed_urls" rows="10" cols="50" class="large-text code"><?php echo get_option('_pinimp_feed_urls'); ?></textarea>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit">
            <input type="submit" name="setup_update" class="button-primary" value="<?php _e('Update Options') ?> &raquo;" />
        </p>

    </form>
</div>