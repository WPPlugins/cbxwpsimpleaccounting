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
if (!current_user_can('manage_cbxaccounting') || !defined('WPINC')) {
    die;
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <div id='cbxaccountingloading' style='display:none'></div>
    <div class="msg updated" style='display:none'></div>

    <div id="icon-options-general" class="icon32"></div>
    <h2><?php esc_html_e('CBX Accounting: Overview', 'cbxwpsimpleaccounting'); ?></h2>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <h3><span><?php esc_html_e('Quick Summary of the Year : ', 'cbxwpsimpleaccounting'); ?><b class="cbxyear"></b></span></h3>
                        <hr />
                        <div class="inside">
                            <p class="accountingyear_traverse">
                                <a data-year="<?php esc_html_e(date("Y") - 1) ?>" data-busy="0"
                                   data-type="prev"
                                   class="button button-primary button-small btn btn-default btn-sm cbxaccounting_btn cbxaccounting_peryear "><?php esc_html_e("Prev", 'cbxwpsimpleaccounting') ?></a>
                                <a data-year="<?php esc_html_e(date("Y") + 1) ?>" data-busy="0"
                                   data-type="next"
                                   class="button button-primary button-small btn btn-default btn-sm cbx-next cbxaccounting_btn cbxaccounting_peryear hidden"><?php esc_html_e("Next", 'cbxwpsimpleaccounting'); ?></a>
                            </p>
                            <div id="cbxaccountchart" style="width: 100%; height: 500px;"></div>
                        </div> <!-- .inside -->
                    </div> <!-- .postbox -->
                    <div class="postbox">
                        <h3><span><?php esc_html_e('Quick Summary of the Month : ', 'cbxwpsimpleaccounting'); ?><b class="cbxmonthyear"></b></span></h3>
                        <hr />
                        <div class="inside">
                            <p class="accountingmonth_traverse">
                                <?php
                                $accounting_month = (int) date('m');
                                $accounting_year  = (int) date('Y');

                                if ($accounting_month == 12) {
                                    $accounting_prev_month = $accounting_month - 1;
                                    $accounting_next_month = 1;
                                    $accounting_prev_year  = $accounting_year;
                                    $accounting_next_year  = $accounting_year + 1;
                                }
                                elseif ($accounting_month == 1) {
                                    $accounting_prev_month = 12;
                                    $accounting_next_month = $accounting_month + 1;
                                    $accounting_prev_year  = $accounting_year - 1;
                                    $accounting_next_year  = $accounting_year;
                                }
                                else {
                                    $accounting_prev_month = $accounting_month - 1;
                                    $accounting_next_month = $accounting_month + 1;
                                    $accounting_prev_year  = $accounting_year;
                                    $accounting_next_year  = $accounting_year;
                                }

                                $accounting_display = ($accounting_next_month > $accounting_month) ? 'display:none;' : '';
                                ?>
                                <a data-year="<?php echo $accounting_prev_year; ?>"
                                   data-month="<?php echo $accounting_prev_month; ?>"
                                   data-busy="0"
                                   data-type="prev"
                                   class="button button-primary button-small btn btn-default btn-sm cbxaccounting_btn cbxaccounting_permonth"><?php esc_html_e("Prev", 'cbxwpsimpleaccounting') ?></a>
                                <a data-year="<?php echo $accounting_next_year; ?>"
                                   data-month="<?php echo $accounting_next_month; ?>"
                                   data-busy="0"
                                   data-target=""
                                   data-type="next"
                                   class="button button-primary button-small btn btn-default btn-sm  cbx-next cbxaccounting_btn cbxaccounting_permonth hidden"><?php esc_html_e("Next", 'cbxwpsimpleaccounting'); ?></a>

                            </p>
                            <div id="cbxaccountchartmonth" style="width: 100%; height: 500px;"></div>
                        </div> <!-- .inside -->
                    </div> <!-- .postbox -->
                    <div class="postbox">
                        <h3><span><?php esc_html_e('Pie Diagram of Latest Income and Expense by Category.', 'cbxwpsimpleaccounting'); ?></span></h3>
                        <hr />
                        <div class="inside">
                            <div id="cbxaccinc" style="height: 300px;"></div>
                            <h3 style="padding-left: 25%;"><?php esc_html_e('Latest Income stats of this month', 'cbxwpsimpleaccounting'); ?></h3>

                            <div id="cbxaccexp" style="height: 300px;"></div>
                            <h3 style="padding-left: 25%;"><?php esc_html_e('Latest Expense stats of this month', 'cbxwpsimpleaccounting'); ?></h3>
                        </div> <!-- .inside -->
                    </div>  <!-- .postbox -->
                    <div class="postbox">
                        <h3><span><?php esc_html_e('Quick Summary(Including Tax)', 'cbxwpsimpleaccounting'); ?></span></h3>
                        <hr />
                        <div class="inside">
                            <table class="form-table">
                                <tr class="alternate">
                                    <th class="row-title"></th>
                                    <th class="row-title"><?php esc_attr_e( 'Income', 'cbxwpsimpleaccounting' ); ?></th>
                                    <th class="row-title"><?php esc_attr_e( 'Expense', 'cbxwpsimpleaccounting' ); ?></th>
                                    <th class="row-title"><?php esc_attr_e( 'Profit', 'cbxwpsimpleaccounting' ); ?></th>
                                <tr>
                                    <th class="row-title"><?php esc_attr_e( 'All Time Total', 'cbxwpsimpleaccounting' ); ?></th>
                                    <th><?php echo $total_quick->total_income; ?></th>
                                    <th><?php echo $total_quick->total_expense; ?></th>
                                    <th><?php echo $total_quick->profit; ?></th>
                                </tr>
                                <tr class="alternate">
                                    <th class="row-title"><?php esc_attr_e( 'This Year', 'cbxwpsimpleaccounting' ); ?></th>
                                    <th><?php echo $total_year_quick->total_income; ?></th>
                                    <th><?php echo $total_year_quick->total_expense; ?></th>
                                    <th><?php echo $total_year_quick->profit; ?></th>
                                </tr>
                                <tr>
                                    <th class="row-title"><?php esc_attr_e( 'This Month', 'cbxwpsimpleaccounting' ); ?></th>
                                    <th><?php echo $total_month_quick->total_income; ?></th>
                                    <th><?php echo $total_month_quick->total_expense; ?></th>
                                    <th><?php echo $total_month_quick->profit; ?></th>
                                </tr>

                            </table>
                        </div> <!-- .inside -->
                    </div> <!-- .postbox -->
                    <div class="postbox">
                        <h3><span><?php _e('Current Month Latest Income(Showing last 20 entries)', 'cbxwpsimpleaccounting'); ?></span></h3>
                        <div class="inside">

                            <table class="widefat" width="100%">
                                <thead>
                                    <tr>
                                        <th class="row-title" width="14%" style="text-align: center;"><?php esc_attr_e('Title', 'cbxwpsimpleaccounting'); ?></th>
										<th width="10%" style="text-align: center;"><?php esc_attr_e('Amount', 'cbxwpsimpleaccounting'); ?></th>
										<th width="14%" style="text-align: center;"><?php esc_attr_e('Category', 'cbxwpsimpleaccounting'); ?></th>
										<th width="14%" style="text-align: center;"><?php esc_attr_e('Account', 'cbxwpsimpleaccounting'); ?></th>
										<th width="5%" style="text-align: center;"><?php esc_attr_e('Invoice', 'cbxwpsimpleaccounting'); ?></th>
										<th width="5%" style="text-align: center;"><?php esc_attr_e('Tax', 'cbxwpsimpleaccounting'); ?></th>
										<th width="10%" style="text-align: center;"><?php esc_attr_e('Final Amount', 'cbxwpsimpleaccounting'); ?></th>
										<th width="14%" style="text-align: center;"><?php esc_attr_e('Information', 'cbxwpsimpleaccounting'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cbxsettings        = new Cbxwpsimpleaccounting_Settings_API('cbxwpsimpleaccounting', '1');
                                    $cbxacc_cat_color   = $cbxsettings->get_option('cbxacc_category_color', 'cbxwpsimpleaccounting_category', 'on');

                                    if (!empty($latest_income)) {
                                        foreach ($latest_income as $key => $income) {
                                            $class       = ($key % 2 == 0) ? 'alternate' : '';
                                            ?>
                                            <tr class="<?php echo $class; ?>">
                                                <td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $income->catcolor : ''; ?>"; class="row-title cbx_center" width="14%">
                                                    <strong><?php echo apply_filters('cbxwpsimpleaccounting_title_link', stripslashes(esc_attr(($income->title))), $income->id); ?></strong>
													<?php
													if (current_user_can('edit_cbxaccounting'))
														echo sprintf('<a href="%s" class="cbxeditexpinc" title="%s"></a>', get_admin_url() . 'admin.php?page=cbxwpsimpleaccounting_addexpinc&id=' . $income->id, __('Edit', 'cbxwpsimpleaccounting'));
													?>
													<?php
													if (current_user_can('delete_cbxaccounting'))
														echo sprintf('|<a href="#" class = "cbxdelexpinc" id = "%d" title="%s"></a>', $income->id, __('Delete', 'cbxwpsimpleaccounting'));
													?>
                                                </td>

                                                <td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $income->catcolor : ''; ?>"; class="row-title cbx_center" width="10%"><?php esc_html_e($this->format_value($income->amount, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator, $currency_position, $currency_symbol), 'cbxwpsimpleaccounting'); ?></td>
                                                <td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $income->catcolor : ''; ?>"; class="row-title cbx_center" width="14%">

                                                    <?php
                                                    $inc_catname = stripslashes(esc_attr($income->cattitle));
                                                    $expinc_type = '1';
                                                    echo apply_filters('cbxwpsimpleaccounting_catlog_link', $inc_catname, $expinc_type, $income->catid);
                                                    ?>
                                                </td>
												<td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $income->catcolor : ''; ?>"; class="row-title cbx_center" width="14%">
													<?php
													$account_name =   ($income->accountname == '') ? esc_html__('N/A', 'cbxwpsimpleaccounting') : esc_html($income->accountname) ;
													echo apply_filters('cbxwpsimpleaccounting_accountlog_link', $account_name, $expinc_type, $income->account);
													?>
												</td>
												<td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $income->catcolor : ''; ?>"; class="row-title cbx_center" width="5%"><?php echo $income->invoice; ?></td>
                                                <td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $income->catcolor : ''; ?>"; class="row-title cbx_center" width="5%"><?php echo $income->tax; ?></td>
                                                <td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $income->catcolor : ''; ?>"; class="row-title cbx_center" width="10%"><?php echo ($income->tax != NULL && $income->istaxincluded == 1) ? $this->format_value($income->amount + ($income->amount * $income->tax) / 100, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator, $currency_position, $currency_symbol) : $this->format_value($income->amount, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator, $currency_position, $currency_symbol); ?></td>
                                                <td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $income->catcolor : ''; ?>"; class="row-title cbx_center" width="14%">
													<?php
													echo stripslashes(get_user_by('id', $income->add_by)->display_name).'('.$income->add_date.')';
													?>
												</td>
                                            </tr>

                                            <?php
                                        }
                                    } else { ?>
                                        <tr>
                                            <td colspan="8" scope="row">
												<div class="notice notice-info inline"><p><?php _e('No data found', 'cbxwpsimpleaccounting'); ?></p></div>
											</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="row-title" width="14%" style="text-align: center;"><?php esc_attr_e('Title', 'cbxwpsimpleaccounting'); ?></th>
                                        <th width="10%" style="text-align: center;"><?php esc_attr_e('Amount', 'cbxwpsimpleaccounting'); ?></th>
                                        <th width="14%" style="text-align: center;"><?php esc_attr_e('Category', 'cbxwpsimpleaccounting'); ?></th>
                                        <th width="14%" style="text-align: center;"><?php esc_attr_e('Account', 'cbxwpsimpleaccounting'); ?></th>
                                        <th width="5%" style="text-align: center;"><?php esc_attr_e('Invoice', 'cbxwpsimpleaccounting'); ?></th>
                                        <th width="5%" style="text-align: center;"><?php esc_attr_e('Tax', 'cbxwpsimpleaccounting'); ?></th>
                                        <th width="10%" style="text-align: center;"><?php esc_attr_e('Final Amount', 'cbxwpsimpleaccounting'); ?></th>
                                        <th width="14%" style="text-align: center;"><?php esc_attr_e('Information', 'cbxwpsimpleaccounting'); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div> <!-- .inside -->
                    </div> <!-- .postbox -->
                    <div class="postbox">
                        <h3><span><?php _e('Current Month Latest Expense(Showing last 20 entries)', 'cbxwpsimpleaccounting'); ?></span></h3>
                        <div class="inside">
                            <table class="widefat">
                                <thead>
                                    <tr>
                                        <th class="row-title" width="14%" style="text-align: center;"><?php esc_attr_e('Title', 'cbxwpsimpleaccounting'); ?></th>
										<th width="10%" style="text-align: center;"><?php esc_attr_e('Amount', 'cbxwpsimpleaccounting'); ?></th>
										<th width="14%" style="text-align: center;"><?php esc_attr_e('Category', 'cbxwpsimpleaccounting'); ?></th>
										<th width="14%" style="text-align: center;"><?php esc_attr_e('Account', 'cbxwpsimpleaccounting'); ?></th>
										<th width="5%" style="text-align: center;"><?php esc_attr_e('Invoice', 'cbxwpsimpleaccounting'); ?></th>
										<th width="5%" style="text-align: center;"><?php esc_attr_e('Tax', 'cbxwpsimpleaccounting'); ?></th>
										<th width="10%" style="text-align: center;"><?php esc_attr_e('Final Amount', 'cbxwpsimpleaccounting'); ?></th>
										<th width="14%" style="text-align: center;"><?php esc_attr_e('Information', 'cbxwpsimpleaccounting'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($latest_expense)) {
                                        foreach ($latest_expense as $key => $expense) {
                                            $class       = ($key % 2 == 0) ? 'alternate' : '';
                                            ?>
                                            <tr class="<?php echo $class; ?>">
                                                <td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $expense->catcolor : ''; ?>"; width="14%" class="row-title cbx_center">
                                                    <strong><?php echo apply_filters('cbxwpsimpleaccounting_title_link', stripslashes(esc_attr(($expense->title))), $expense->id); ?></strong>
													<?php
													if (current_user_can('edit_cbxaccounting'))
														echo sprintf('<a href="%s" class="cbxeditexpinc" title="%s"></a>', get_admin_url() . 'admin.php?page=cbxwpsimpleaccounting_addexpinc&id=' . $expense->id, __('Edit', 'cbxwpsimpleaccounting'));
													?>
													<?php
													if (current_user_can('delete_cbxaccounting'))
														echo sprintf('|<a href="#" class = "cbxdelexpinc" id = "%d" title="%s"></a>', $expense->id, esc_html__('Delete', 'cbxwpsimpleaccounting'));
													?>
                                                </td>

                                                <td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $expense->catcolor : ''; ?>"; width="10%" class="row-title cbx_center"><?php _e($this->format_value($expense->amount, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator, $currency_position, $currency_symbol), 'cbxwpsimpleaccounting'); ?></td>
                                                <td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $expense->catcolor : ''; ?>"; width="14%" class="row-title cbx_center">
                                                    <?php
                                                    $exp_catname = stripslashes(esc_attr($expense->cattitle));
                                                    $expinc_type = '2';
                                                    echo apply_filters('cbxwpsimpleaccounting_catlog_link', $exp_catname, $expinc_type, $expense->catid);
                                                    ?>
                                                </td>
												<td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $expense->catcolor : ''; ?>"; width="14%" class="row-title cbx_center">
													<?php
													 $account_name  = ($expense->accountname == '') ? esc_html__('N/A', 'cbxwpsimpleaccounting') : esc_html($expense->accountname) ;
													 echo apply_filters('cbxwpsimpleaccounting_accountlog_link', $account_name, $expinc_type, $expense->account);
													?>
												</td>
												<td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $income->catcolor : ''; ?>"; class="row-title cbx_center" width="5%"><?php echo $income->invoice; ?></td>
                                                <td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $expense->catcolor : ''; ?>"; class="row-title cbx_center" width="5%"><?php echo $expense->tax; ?></td>
                                                <td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $expense->catcolor : ''; ?>"; class="row-title cbx_center" width="10%"><?php echo ($expense->tax != NULL && $expense->istaxincluded == 1) ? __($this->format_value($expense->amount + ($expense->amount * $expense->tax) / 100, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator, $currency_position, $currency_symbol), 'cbxwpsimpleaccounting') : __($this->format_value($expense->amount, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator, $currency_position, $currency_symbol), 'cbxwpsimpleaccounting'); ?></td>
                                                <td style="color:<?php echo ($cbxacc_cat_color == 'on') ? $expense->catcolor : ''; ?>"; width="14%" class="row-title cbx_center">
													<?php
													echo stripslashes(get_user_by('id', $expense->add_by)->display_name).'('.$expense->add_date.')';
													?>
												</td>
                                            </tr>
                                            <?php
                                        }
                                    } else { ?>
                                        <tr>
                                            <td colspan="8" scope="row">
												<div class="notice notice-info inline"><p><?php _e('No data found', 'cbxwpsimpleaccounting'); ?></p></div>
											</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th width="14%" class="row-title" style="text-align: center;"><?php esc_attr_e('Title', 'cbxwpsimpleaccounting'); ?></th>
										<th width="10%" style="text-align: center;"><?php esc_attr_e('Amount', 'cbxwpsimpleaccounting'); ?></th>
										<th width="14%" style="text-align: center;"><?php esc_attr_e('Category', 'cbxwpsimpleaccounting'); ?></th>
										<th width="14%" style="text-align: center;"><?php esc_attr_e('Account', 'cbxwpsimpleaccounting'); ?></th>
										<th width="5%" style="text-align: center;"><?php esc_attr_e('Invoice', 'cbxwpsimpleaccounting'); ?></th>
										<th width="5%" style="text-align: center;"><?php esc_attr_e('Tax', 'cbxwpsimpleaccounting'); ?></th>
										<th width="10%" style="text-align: center;"><?php esc_attr_e('Final Amount', 'cbxwpsimpleaccounting'); ?></th>
										<th width="14%" style="text-align: center;"><?php esc_attr_e('Information', 'cbxwpsimpleaccounting'); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div> <!-- .inside -->
                    </div> <!-- .postbox -->
                </div> <!-- .meta-box-sortables .ui-sortable -->
            </div> <!-- post-body-content -->
            <?php
            include('sidebar.php');
            ?>
        </div> <!-- #post-body .metabox-holder .columns-2 -->
        <br class="clear">
    </div> <!-- #poststuff -->
</div> <!-- .wrap -->

<?php
$monthnames     = array();
$monthnames[0]  = _x('Jan', 'January abbreviation');
$monthnames[1]  = _x('Feb', 'February abbreviation');
$monthnames[2]  = _x('Mar', 'March abbreviation');
$monthnames[3]  = _x('Apr', 'April abbreviation');
$monthnames[4]  = _x('May', 'May abbreviation');
$monthnames[5]  = _x('Jun', 'June abbreviation');
$monthnames[6]  = _x('Jul', 'July abbreviation');
$monthnames[7]  = _x('Aug', 'August abbreviation');
$monthnames[8]  = _x('Sep', 'September abbreviation');
$monthnames[9]  = _x('Oct', 'October abbreviation');
$monthnames[10] = _x('Nov', 'November abbreviation');
$monthnames[11] = _x('Dec', 'December abbreviation');



?>

<script type="text/javascript">

    jQuery(document).ready(function ($) {

        google.load("visualization", "1", {packages: ["corechart"], "callback": drawChart});
        google.setOnLoadCallback(drawChart);

        var date  = new Date();//current date
        var month = date.getMonth();//current month exp.5
        var year  = date.getFullYear();//current year exp.2016
        var chart;

        //chart option for linechart
        var options = {
            curveType: 'function',
            legend: {position: 'bottom'},
            colors: ['<?php echo $this->settings_api->get_option('legend_color_for_income', 'cbxwpsimpleaccounting_graph', '#5cc488') ?>', '<?php echo $this->settings_api->get_option('legend_color_for_expense', 'cbxwpsimpleaccounting_graph', '#e74c3c') ?>'], //Line color
            backgroundColor: '#f7f7f9',
            random_id: true
        };

        // data holder array for expinc by month of this year
        var year_expinc_by_month = [[cbxwpsimpleaccounting.month, cbxwpsimpleaccounting.income, cbxwpsimpleaccounting.expense]];
        // data holder array for expinc by day of this month
        var month_expinc_by_day = [[cbxwpsimpleaccounting.day, cbxwpsimpleaccounting.income, cbxwpsimpleaccounting.expense]];
        // month names array
        var months = [<?php echo '"' . implode('","', $monthnames) . '"' ?>];
        // days of each month
        var all_month_days = [<?php echo '"' . implode('","', $month_days_array) . '"' ?>];
        //current month days
        var current_month_days = parseInt(all_month_days[month]);
        
        // arranging year income|expense data for chart
        var year_income_by_month = $.map(<?php echo json_encode($year_income_by_month); ?>, function (el) {
            return el
        });
        var year_expense_by_month = $.map(<?php echo json_encode($year_expense_by_month); ?>, function (el) {
            return el
        });

        $(months).each(function (index) {
            var data = [months[index], year_income_by_month[index], year_expense_by_month[index]];
            year_expinc_by_month.push(data);
        });
        //finish
        
        // arranging month income|expense data for chart
        var month_income_by_day = $.map(<?php echo json_encode($daywise_income2); ?>, function (el) {
            return el
        });
        var month_expense_by_day = $.map(<?php echo json_encode($daywise_expense2); ?>, function (el) {
            return el
        });

        for (var i = 0; i <= current_month_days; i++) {
            var data = [i + 1, month_income_by_day[i], month_expense_by_day[i]];
            month_expinc_by_day.push(data);
        }
        //finish

        $('.cbxyear').html(year);
        $('.cbxmonthyear').html(months[month] +' Year: '+year);
        
    
        //draw line charts for year and month
        function drawChart() {

            var year_data = google.visualization.arrayToDataTable(year_expinc_by_month);

            year_chart = new google.visualization.LineChart(document.getElementById('cbxaccountchart'));

            year_chart.draw(year_data, options);


//console.log(month_expinc_by_day);
            var month_data = google.visualization.arrayToDataTable(month_expinc_by_day);

            month_chart = new google.visualization.LineChart(document.getElementById('cbxaccountchartmonth'));

            month_chart.draw(month_data, options);
        }

        //peryear traversal of year data
        $(".cbxaccounting_peryear").on('click' , function (e) {

            e.preventDefault();
            $('#cbxaccountingloading').show();
            var $this = $(this);
            year = parseInt($this.attr('data-year'));

            var data = {
                'action': 'load_nextprev_year',
                'year': year,
                'security': cbxwpsimpleaccounting.nonce
            };
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: ajaxurl,
                data: data,
                success: function (data) {
                    if (data) {

                        var year_income_by_month = $.map(data.year_income_by_month, function (el) {
                            return el
                        });

                        var year_expense_by_month = $.map(data.year_expense_by_month, function (el) {
                            return el
                        });

                        var year_expinc_by_month = [[cbxwpsimpleaccounting.month, cbxwpsimpleaccounting.income, cbxwpsimpleaccounting.expense]];

                        $(months).each(function (index) {

                            var data = [months[index], year_income_by_month[index], year_expense_by_month[index]];

                            year_expinc_by_month.push(data);

                        });
                        
                        
                        jQuery(".cbxaccounting_peryear[data-type='next']").attr("data-year", (parseInt(year) + 1));
                        jQuery(".cbxaccounting_peryear[data-type='prev']").attr("data-year", (parseInt(year) - 1));
                        
                        var cyear = (new Date().getFullYear());

                        if(jQuery(".cbxaccounting_peryear[data-type='next']").attr("data-year") <= cyear){
                            jQuery(".cbxaccounting_peryear[data-type='next']").removeClass('hidden');
                        }else{
                            jQuery(".cbxaccounting_peryear[data-type='next']").addClass('hidden');
                        }
                        
                        $('.cbxyear').html(year);
                        

                        var year_data = google.visualization.arrayToDataTable(year_expinc_by_month);

                        $('#cbxaccountingloading').hide();
                        year_chart.draw(year_data, options);
                        
                    }
                },
            });
        });
        //finish peryear traversal of year data

        //permonth traversal of year data
        $(".cbxaccounting_permonth").click(function (e) {
            
            e.preventDefault();
            $('#cbxaccountingloading').show();
            var $this = $(this);
            year = parseInt($this.attr('data-year'));
            month = parseInt($this.attr('data-month'));

            var data = {
                'action': 'load_nextprev_month',
                'year': year,
                'month': month,
                'security': cbxwpsimpleaccounting.nonce
            };
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: ajaxurl,
                data: data,
                success: function (data) {
                    if (data) {

                        var month_income_by_day = $.map(data.daywise_income2, function (el) {
                            return el
                        });
                        var month_expense_by_day = $.map(data.daywise_expense2, function (el) {
                            return el
                        });

                        var month_expinc_by_day = [[cbxwpsimpleaccounting.day, cbxwpsimpleaccounting.income, cbxwpsimpleaccounting.expense]];
                        
                        for (var i = 0; i <= parseInt(all_month_days[month]); i++) {
                            var data = [i + 1, month_income_by_day[i], month_expense_by_day[i]];
                            month_expinc_by_day.push(data);
                        }

                        for (var i = 0; i <= current_month_days; i++) {
                            var data = [i + 1, month_income_by_day[i], month_expense_by_day[i]];
                            month_expinc_by_day.push(data);
                        }

                        var month_data = google.visualization.arrayToDataTable(month_expinc_by_day);

                        $('#cbxaccountingloading').hide();
                        month_chart.draw(month_data, options);
                        
                        if (month == 12) {
                         var cbxaccounting_prev_month = parseInt(month) - 1;
                         var cbxaccounting_next_month = 1;
                         var cbxaccounting_prev_year  = parseInt(year);
                         var cbxaccounting_next_year  = parseInt(year) + 1;
                         }else if (month == 1) {
                         var cbxaccounting_prev_month = 12;
                         var cbxaccounting_next_month = parseInt(month) + 1;
                         var cbxaccounting_prev_year  = parseInt(year) - 1;
                         var cbxaccounting_next_year  = parseInt(year);
                         }else {
                         var cbxaccounting_prev_month = parseInt(month) - 1;
                         var cbxaccounting_next_month = parseInt(month) + 1;
                         var cbxaccounting_prev_year  = parseInt(year);
                         var cbxaccounting_next_year  = parseInt(year);
                         }

                        var cmonth = (new Date().getMonth());
                        var cyear = (new Date().getFullYear());

                        jQuery(".cbxaccounting_permonth[data-type='next']").attr("data-year", (cbxaccounting_next_year));
                        jQuery(".cbxaccounting_permonth[data-type='next']").attr("data-month", (cbxaccounting_next_month));

                        jQuery(".cbxaccounting_permonth[data-type='prev']").attr("data-year", (cbxaccounting_prev_year));
                        jQuery(".cbxaccounting_permonth[data-type='prev']").attr("data-month", (cbxaccounting_prev_month));

                       
                        if(jQuery(".cbxaccounting_permonth[data-type='next']").attr("data-year") < cyear){
                            jQuery(".cbxaccounting_permonth[data-type='next']").removeClass('hidden');
                        }else{
                           
                            if(jQuery(".cbxaccounting_permonth[data-type='next']").attr("data-month") <= (cmonth + 1)){
                               
                                jQuery(".cbxaccounting_permonth[data-type='next']").removeClass('hidden');
                            }else{
                               
                                jQuery(".cbxaccounting_permonth[data-type='next']").addClass('hidden');
                            }

                        }

                    $('.cbxmonthyear').html(months[month-1] +' Year: '+ (year));
                    }
                },
            });
        });
        //finish permonth traversal of year data

        /**Start third chart of the overview page(overview chart by cat of income and expenses)**/

        // data holder array for income by cat. of this currnet month
        var month_income_by_cat = $.map(<?php echo json_encode($latest_income_by_cat); ?>, function (el) {
            return [[el.label, el.value]];
        });
        // data holder array for expense by cat. of this currnet month
        var month_expense_by_cat = $.map(<?php echo json_encode($latest_expense_by_cat); ?>, function (el) {
            return [[el.label, el.value]];
        });

        //pie chart for month income by cat
        new Chartkick.PieChart("cbxaccinc", month_income_by_cat);
        
        //pie chart for month expense by cat
        new Chartkick.PieChart("cbxaccexp", month_expense_by_cat);
        /**end chart**/

        /**for resposiveness of year overview chart**/
        $(window).resize(function () {
            drawChart();
        });
    });

</script>
