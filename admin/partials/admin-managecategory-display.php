<?php
/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxwpsimpleaccounting
 * @subpackage Cbxwpsimpleaccounting/admin/partials
 */
if (!defined('WPINC')) {
    die;
}
?>
    
<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <div id='cbxaccountingloading' style='display:none'></div>
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php _e('CBX Accounting: Manage Category', $this->cbxwpsimpleaccounting); ?></h2>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <!-- main content -->
            <div id="post-body-content">
                <div id="cbxaccounting_catmanager" class="meta-box-sortables ui-sortable">

                    <div class="postbox">
                        <h3><span><?php _e('Add/Edit Category', $this->cbxwpsimpleaccounting); ?></span></h3>
                        <div class="inside">
                            <form id="cbacnt-cat-form" action="" method="post">

                                <div class="cbacnt-msg-box below-h2 hidden"><p class="cbacnt-msg-text"></p></div>
                                <input name="cbacnt-cat-id" id="cbacnt-cat-id" type="hidden" value="0" />
                                <?php wp_nonce_field('add_new_expinc_cat', 'new_cat_verifier'); ?>

                                <table class="form-table">

                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label class="cbacnt-cat cbacnt-label" for="cbacnt-cat-title"><?php _e('Title', $this->cbxwpsimpleaccounting); ?></label>
                                        </th>
                                        <td><input name="cbacnt-cat-title" id="cbacnt-cat-title" type="text" value="" class="cbacnt-cat regular-text" required/></td>
                                    </tr>

                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label class="cbacnt-cat cbacnt-label" for="cbacnt-cat-type"><?php _e('Type', $this->cbxwpsimpleaccounting); ?></label>
                                        </th>
                                        <td>
                                            <input type="radio" name="cbacnt-cat-type" class="cbacnt-cat-type" value="1" required checked/><?php _e('Income', $this->cbxwpsimpleaccounting); ?>
                                            <input type="radio" name="cbacnt-cat-type" class="cbacnt-cat-type" value="2" required /><?php _e('Expense', $this->cbxwpsimpleaccounting); ?>

                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label class="cbacnt-cat cbacnt-label" for="cbacnt-cat-color"><?php _e('Color', $this->cbxwpsimpleaccounting); ?></label>
                                        </th>
                                        <td>
                                            <input  name="cbacnt-cat-color" id="cbacnt-cat-color" type="text" value="#333333" class="cbacnt-cat regular-text cbacnt-cat-color-picker" />
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label class="cbacnt-cat cbacnt-label" for="cbacnt-cat-note"><?php _e('Note', $this->cbxwpsimpleaccounting); ?></label>
                                        </th>
                                        <td><textarea id="cbacnt-cat-note" name="cbacnt-cat-note" cols="50" rows="6" class="cbacnt-cat"></textarea></td>
                                    </tr>

                                    <tr valign="top">
                                        <th class="row-title" scope="row"></th>
                                        <td>
                                            <input id="cbacnt-new-cat" class="button-primary" type="submit" name="cbacnt-new-cat" data-add-value="<?php _e('Add new category', $this->cbxwpsimpleaccounting); ?>" data-update-value="<?php _e('Update category', $this->cbxwpsimpleaccounting); ?>" value="<?php _e('Add new category', $this->cbxwpsimpleaccounting); ?>" />
                                            <input id="cbacnt-edit-cat-cancel" class="button-secondary hidden" type="button" name="cbacnt-edit-cat-cancel" value="<?php _e('Cancel', $this->cbxwpsimpleaccounting); ?>" disabled="disabled" />
                                        </td>
                                    </tr>
                                </table>

                            </form>
                        </div> <!-- .inside -->
                    </div> <!-- .postbox -->
                    <div class="postbox">
                        <h3><span><?php _e('Categories', $this->cbxwpsimpleaccounting); ?></span></h3>
                        <div class="inside">
                            <?php _e('Type : ', $this->cbxwpsimpleaccounting); ?>
                            <input type="radio" class="cbacnt-exinc-type" name="cbacnt-exinc-type" value="1" checked/><?php _e('Income', $this->cbxwpsimpleaccounting); ?>
                            <input type="radio" class="cbacnt-exinc-type" name="cbacnt-exinc-type" value="2" /><?php _e('Expense', $this->cbxwpsimpleaccounting); ?>

                            <ul id="cbacnt-expinc-cat-list" class="cbacnt-cat-list">

                            </ul>
                        </div>
                    </div>

                </div> <!-- .meta-box-sortables .ui-sortable -->
            </div> <!-- post-body-content -->
            <?php
            include('sidebar.php');
            ?>

        </div> <!-- #post-body .metabox-holder .columns-2 -->

        <br class="clear">
    </div> <!-- #poststuff -->

</div> <!-- .wrap -->

<script type="text/javascript">

    jQuery(document).ready(function($) {


    });

</script>
