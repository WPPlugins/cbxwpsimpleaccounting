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
if (!current_user_can('edit_cbxaccounting') || !defined('WPINC')) {
    die;
}

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <div id='cbxaccountingloading' style='display:none'></div>
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php _e('CBX Accounting: Add Expense/Income', 'cbxwpsimpleaccounting'); ?></h2>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <!-- main content -->
            <div id="post-body-content">
                <div id="cbxaccounting_addexpinc" class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <?php
                        if (sizeof($cat_results_list) == 0) {
                            echo '<div class="error"><p>' . sprintf(__('No category created yet, please <a href="%s">create</a> one !', 'cbxwpsimpleaccounting'), admin_url('admin.php?page=cbxwpsimpleaccounting_cat')) . '</p></div>';
                        }
                        if (isset($single_incomeexpense) && sizeof($single_incomeexpense) > 0 && isset($single_incomeexpense['error']) && $single_incomeexpense['error'] == true) {
                            echo '<div class="error"><p>' . $single_incomeexpense['msg'] . '</p></div>';
                        }
                        ?>
                        <h3><span><?php _e('Add/Edit Expense/Income', 'cbxwpsimpleaccounting'); ?></span></h3>

                        <div class="inside">
                            <form id="cbacnt-expinc-form" action="" method="post" name="cbacnt-expinc-form">

                                <div class="cbacnt-msg-box below-h2 hidden"><p class="cbacnt-msg-text"></p></div>
                                <input name="cbacnt-exinc-id" id="cbacnt-exinc-id" type="hidden"
                                       value="<?php echo isset($single_incomeexpense['id']) ? absint($single_incomeexpense['id']) : 0; ?>"/>
                                       <?php wp_nonce_field('add_new_expinc', 'new_expinc_verifier'); ?>

                                <table class="form-table">
									<?php
									do_action('cbxwpsimpleaccounting_form_start', $single_incomeexpense);
									?>
                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label
                                                for="cbacnt-exinc-title"><?php _e('Title', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td><input name="cbacnt-exinc-title" id="cbacnt-exinc-title" type="text"
                                                   value="<?php echo isset($single_incomeexpense['title']) ? stripslashes(esc_attr($single_incomeexpense['title'])) : ''; ?>"
                                                   class="regular-text" required/>
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label
                                                for="cbacnt-exinc-amount"><?php _e('Amount', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td><input name="cbacnt-exinc-amount" id="cbacnt-exinc-amount" type="number" step="0.01"
                                                   value="<?php echo isset($single_incomeexpense['amount']) ? abs(floatval($single_incomeexpense['amount'])) : ''; ?>"
                                                   class="regular-text" required/><?php echo $this->settings_api->get_option('cbxwpsimpleaccounting_currency', 'cbxwpsimpleaccounting_basics', 'USD'); ?>
                                        </td>

                                    </tr>

                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label
                                                for="cbacnt-exinc-source-amount"><?php _e('Source Amount', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td>
                                            <input name="cbacnt-exinc-source-amount" id="cbacnt-exinc-source-amount"
                                                   type="number" step="0.01"
                                                   value="<?php echo isset($single_incomeexpense['source_amount']) ? abs(floatval($single_incomeexpense['source_amount'])) : ''; ?>"
                                                   class="regular-text"
                                                   style="width:110px; margin-right:10px;"/>

                                            <select name="cbacnt-exinc-currency" id="cbacnt-exinc-currency"
                                                    class="chosen-select">
                                                <option
                                                    value="none"><?php _e('Select Currency', 'cbxwpsimpleaccounting'); ?></option>
                                                    <?php foreach ($this->get_cbxwpsimpleaccounting_currencies() as $currencyoption => $currencyvalue) {
                                                        ?>
                                                    <option
                                                        value="<?php echo $currencyoption; ?>"  <?php echo isset($single_incomeexpense['source_currency']) ? (($single_incomeexpense['source_currency'] == $currencyoption) ? ' selected="selected" ' : '') : '  '; ?>   > <?php
                                                            echo $currencyoption;

                                                            echo $this->get_cbxwpsimpleaccounting_currency_symbol($currencyoption);
                                                            ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </td>

                                    </tr>

                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label
                                                for="cbacnt-exinc-type"><?php _e('Type', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td>
                                            <input type="radio" class="cbacnt-exinc-type" name="cbacnt-exinc-type" value="1" required <?php isset($single_incomeexpense['type']) ? checked($single_incomeexpense['type'], 1, TRUE) : ''; ?> checked/><?php _e('Income', 'cbxwpsimpleaccounting'); ?>
                                            <input type="radio" class="cbacnt-exinc-type" name="cbacnt-exinc-type" value="2" required <?php isset($single_incomeexpense['type']) ? checked($single_incomeexpense['type'], 2, TRUE) : ''; ?> /><?php _e('Expense', 'cbxwpsimpleaccounting'); ?>
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label><?php _e('Category', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td>
                                            <ul id="cbacnt-expinc-cat-list"
                                                class="cbacnt-cat-list cat-checklist category-checklist"
                                                data-catlist='<?php echo json_encode($cat_results_list); ?>'>
                                                    <?php foreach ($cat_results_list as $list): ?>
                                                        <?php if (absint($list['type']) == 1): ?>
                                                        <li style="color:<?php echo stripslashes(esc_attr($list['color'])); ?>;" class="cbacnt-cat-exinc cbacnt-cat-inc cbacnt-cat-type-<?php echo $list['type']; ?> <?php echo isset($single_incomeexpense['type']) ? (($single_incomeexpense['type'] == 1) ? '' : 'hidden') : '' ?>">
                                                            <label class="selectit">
                                                                <input data-value="<?php echo $list['id']; ?>"
                                                                       value="<?php echo $list['id']; ?>"
                                                                       type="checkbox" <?php echo (isset($single_incomeexpense['type']) && ($single_incomeexpense['type'] == 1) && in_array($list['id'], $single_incomeexpense['cat_list'])) ? ' checked="checked" ' : ((isset($single_incomeexpense['type']) && !in_array($list['id'], $single_incomeexpense['cat_list']) && count($single_incomeexpense['cat_list']) > 0) ? ' disabled' : ''); ?>
                                                                       name="cbacnt-expinc-cat[<?php echo $list['type']; ?>][<?php echo $list['id']; ?>]"
                                                                       id="cbacnt-expinc-cat-<?php echo $list['id']; ?>"
                                                                       class="cbacnt-cat-exinciteminput single-checkbox" required> <?php echo stripslashes(esc_attr($list['title'])); ?>
                                                            </label>
                                                        </li>
                                                    <?php elseif (absint($list['type']) == 2): ?>
                                                        <li style="color:<?php echo stripslashes(esc_attr($list['color'])); ?>" class="cbacnt-cat-exinc cbacnt-cat-exp cbacnt-cat-type-<?php echo $list['type']; ?> <?php echo isset($single_incomeexpense['type']) ? (($single_incomeexpense['type'] == 2) ? '' : 'hidden') : 'hidden' ?>">
                                                            <label class="selectit">
                                                                <input data-value="<?php echo $list['id']; ?>"
                                                                       value="<?php echo $list['id']; ?>"
                                                                       type="checkbox"  <?php echo (isset($single_incomeexpense['type']) && ($single_incomeexpense['type'] == 2) && in_array($list['id'], $single_incomeexpense['cat_list'])) ? ' checked="checked " ' : ((isset($single_incomeexpense['type']) && !in_array($list['id'], $single_incomeexpense['cat_list']) && count($single_incomeexpense['cat_list']) > 0) ? ' disabled' : ''); ?>
                                                                       name="cbacnt-expinc-cat[<?php echo $list['type']; ?>][<?php echo $list['id']; ?>]"
                                                                       id="cbacnt-expinc-cat-<?php echo $list['id']; ?>"
                                                                       class="cbacnt-cat-exinciteminput single-checkbox" required> <?php echo stripslashes(esc_attr($list['title'])); ?>
                                                            </label>
                                                        </li>
                                                        <?php
                                                    endif;
                                                    ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </td>
                                    </tr>						
                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label
                                                for="cbacnt-exinc-note"><?php esc_html_e('Note', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td><textarea id="cbacnt-exinc-note" name="cbacnt-exinc-note" cols="50"
                                                      rows="6"><?php echo isset($single_incomeexpense['note']) ? $single_incomeexpense['note'] : ''; ?></textarea>
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label for="cbacnt-exinc-account"><?php esc_html_e('Account', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td>
                                            <select name="cbacnt-exinc-acc" id="cbacnt-exinc-acc" class="chosen-select">
                                                <option value ><?php esc_html_e('Select Account', 'cbxwpsimpleaccounting') ?></option>

                                                <?php foreach ($all_acc_list as $acc) { ?>

                                                    <option value = <?php echo $acc->id; ?> <?php echo isset($single_incomeexpense['account']) ? (($single_incomeexpense['account'] == $acc->id) ? ' selected="selected" ' : '') : ''; ?>><?php echo $acc->title; ?></option>

                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label
                                                for="cbacnt-exinc-invoice"><?php esc_html_e('Invoice No.', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td>
                                            <input name="cbacnt-exinc-invoice" id="cbacnt-exinc-invoice" type="text"
                                                   value="<?php echo isset($single_incomeexpense['invoice']) ? stripslashes(esc_attr($single_incomeexpense['invoice'])) : ''; ?>"
                                                   class="regular-text"/>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label for="cbacnt-exinc-tax-include"><?php esc_html_e('Add Tax:', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="cbacnt-exinc-include-tax" id="cbacnt-exinc-include-tax"  value="1" <?php echo (isset($single_incomeexpense['istaxincluded']) && $single_incomeexpense['istaxincluded'] == 1) ? 'checked' : ''; ?>/>
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label for="cbacnt-exinc-tax"><?php _e('Vat(%)', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td>
                                            <input name="cbacnt-exinc-tax" id="cbacnt-exinc-tax" type="number" step="0.01"
                                                   value="<?php echo isset($single_incomeexpense['tax']) ? stripslashes(esc_attr($single_incomeexpense['tax'])) : $this->settings_api->get_option('cbxwpsimpleaccounting_sales_tax', 'cbxwpsimpleaccounting_tax', '0'); ?>"
                                                   class="regular-text"/>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="row-title" scope="row">
                                            <label for="cbacnt-exinc-date"><?php _e('Added Date', 'cbxwpsimpleaccounting'); ?></label>
                                        </th>
                                        <td>
                                            
                                            <input type="text" id="cbacnt-exinc-add-date" name="cbacnt-exinc-add-date" value="<?php echo isset($single_incomeexpense['add_date']) ? $single_incomeexpense['add_date'] : '';?>" />
                                            
                                        </td>
                                    </tr>
                                    <?php
									do_action('cbxwpsimpleaccounting_form_end', $single_incomeexpense);
									?>

                                    <tr valign="top">
                                        <th class="row-title" scope="row"></th>
                                        <td>
                                            <input id="cbacnt-new-exinc" class="button-primary" type="submit"
                                                   name="cbacnt-new-exinc"
                                                   data-add-value="<?php _e('Add new expense/income', 'cbxwpsimpleaccounting'); ?>"
                                                   data-update-value="<?php _e('Update expense/income', 'cbxwpsimpleaccounting'); ?>"
                                                   value="<?php echo isset($single_incomeexpense['id']) ? __('Update expense/income', 'cbxwpsimpleaccounting') : __('Add new expense/income', 'cbxwpsimpleaccounting'); ?>"/>
                                        </td>
                                    </tr>

                                </table>

                            </form>
                        </div>
                        <!-- .inside -->
                    </div>
                    <!-- .postbox -->
                </div>
                <!-- .meta-box-sortables .ui-sortable -->
            </div>
            <!-- post-body-content -->
            <?php
            include('sidebar.php');
            ?>
        </div>
        <!-- #post-body .metabox-holder .columns-2 -->

        <br class="clear">
    </div>
    <!-- #poststuff -->

</div> <!-- .wrap -->

