<?php
/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.7
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
    <h2><?php _e('CBX Accounting: Account Manager', 'cbxwpsimpleaccounting'); ?></h2>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <!-- main content -->
            <div id="post-body-content">
                <div id="cbxaccounting_accmanager" class="meta-box-sortables ui-sortable">

                    <div class="postbox">
                        <h3><span><?php esc_html_e('Add/Edit Account', 'cbxwpsimpleaccounting'); ?></span></h3>
                        <div class="inside">
                            <form id="cbacnt-account-form" action="" method="post">
                                <div class="cbacnt-msg-box below-h2 hidden"><p class="cbacnt-msg-text"></p></div>
                                <input name="cbacnt-acc-id" id="cbacnt-acc-id" type="hidden" value="0" />
                                <?php wp_nonce_field('add_new_acc', 'new_acc_verifier'); ?>
                                <table class="form-table">

                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label class="cbacnt-acc cbacnt-label" for="cbacnt-acc-title"><?php esc_html_e('Title', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td><input name="cbacnt-acc-title" id="cbacnt-acc-title" type="text" value="" class="cbacnt-acc regular-text" required/></td>
                                    </tr>

                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label class="cbacnt-acc cbacnt-label" for="cbacnt-acc-type"><?php esc_html_e('Type', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td>
											<input type="radio" name="cbacnt-acc-type" class="cbacnt-acc-type" required value="cash" checked/><span class="cbacnt-acc-type-cash-color"><?php esc_html_e('Cash', 'cbxwpsimpleaccounting'); ?></span>
											<input type="radio" name="cbacnt-acc-type" class="cbacnt-acc-type" required value="bank" /><span class="cbacnt-acc-type-bank-color"><?php esc_html_e('Bank', 'cbxwpsimpleaccounting'); ?></span>
                                        
                                        </td>
                                    </tr>
                                    <tr valign="top" class="cbxacc_bankdetails">
                                        <th class="row-title" scope="row">
                                            <label class="cbacnt-acc cbacnt-label" for="cbacnt-acc-acc-no"><?php esc_html_e('Acount No.', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td>
                                            <input  name="cbacnt-acc-acc-no" id="cbacnt-acc-acc-no" type="text" value="" class="cbacnt-acc-acc-no regular-text" />
                                        </td>
                                    </tr>

                                    <tr valign="top" class="cbxacc_bankdetails">
                                        <th class="row-title" scope="row">
                                            <label class="cbacnt-acc cbacnt-label" for="cbacnt-acc-acc-name"><?php esc_html_e('Acount Name.', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td>
                                            <input  name="cbacnt-acc-acc-name" id="cbacnt-acc-acc-name" type="text" value="" class="cbacnt-acc-acc-name regular-text" />
                                        </td>
                                    </tr>

                                    <tr valign="top" class="cbxacc_bankdetails">
                                        <th class="row-title" scope="row">
                                            <label class="cbacnt-acc cbacnt-label" for="cbacnt-acc-bank-name"><?php esc_html_e('Bank Name.', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td>
                                            <input  name="cbacnt-acc-bank-name" id="cbacnt-acc-bank-name" type="text" value="" class="cbacnt-acc-bank-name regular-text" />
                                        </td>
                                    </tr>

                                    <tr valign="top" class="cbxacc_bankdetails">
                                        <th class="row-title" scope="row">
                                            <label class="cbacnt-acc cbacnt-label" for="cbacnt-acc-branch-name"><?php esc_html_e('Branch Name.', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td>
                                            <input  name="cbacnt-acc-branch-name" id="cbacnt-acc-branch-name" type="text" value="" class="cbacnt-acc-branch-name regular-text" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="row-title" scope="row"></th>
                                        <td>
                                            <input id="cbacnt-new-acc" class="button-primary" type="submit" name="cbacnt-new-acc" data-add-value="<?php esc_html_e('Add new account', 'cbxwpsimpleaccounting'); ?>" data-update-value="<?php esc_html_e('Update account', 'cbxwpsimpleaccounting'); ?>" value="<?php esc_html_e('Add new account', 'cbxwpsimpleaccounting'); ?>" />
                                            <input id="cbacnt-edit-acc-cancel" class="button-secondary hidden" type="button" name="cbacnt-edit-acc-cancel" value="<?php esc_html_e('Cancel', 'cbxwpsimpleaccounting'); ?>" disabled="disabled" />
                                        </td>
                                    </tr>
                                </table>

                            </form>

                        </div> <!-- .inside -->
                    </div> <!-- .postbox -->
                    
                    <div class="postbox">
                        <h3><span><?php esc_html_e('Accounts', 'cbxwpsimpleaccounting'); ?></span></h3>
                        <div class="inside">
                            <?php esc_html_e('Type : ', 'cbxwpsimpleaccounting'); ?>
                            <input type="radio" name="cbacnt-acc-type-bottom" class="cbacnt-acc-type-bottom " value="all" checked/><?php esc_html_e('All Accounts', 'cbxwpsimpleaccounting'); ?>
							<input type="radio" name="cbacnt-acc-type-bottom" class="cbacnt-acc-type-bottom" value="cash" /><span class="cbacnt-acc-type-cash-color"><?php esc_html_e('Cash', 'cbxwpsimpleaccounting'); ?></span>
							<input type="radio" name="cbacnt-acc-type-bottom" class="cbacnt-acc-type-bottom" value="bank"/><span class="cbacnt-acc-type-bank-color"> <?php esc_html_e('Bank', 'cbxwpsimpleaccounting'); ?></span>
                            
                            <ul id="cbacnt-expinc-acc-list" class="cbacnt-acc-list">
                                
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


<!--script type="text/javascript">

    jQuery(document).ready(function($) {

       
    });

</script-->
