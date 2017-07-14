<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 * @package    Cbxwpsimpleaccounting
 * @subpackage Cbxwpsimpleaccounting/admin
 * @author     Codeboxr <info@codeboxr.com>
 */
class Cbxwpsimpleaccounting_Admin {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Cbxwpsimpleaccounting_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $cbxwpsimpleaccounting The ID of this plugin.
     */
    private $cbxwpsimpleaccounting;
    private $cbxwpsimpleaccounting_cat;
    private $cbxwpsimpleaccounting_addexpinc;
    private $cbxwpsimpleaccounting_settings;
    private $cbxwpsimpleaccounting_addons;
    private $cbxwpsimpleaccounting_accmanager;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;
    //for setting
    private $settings_api;

    /**
     * The plugin basename of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_basename The plugin basename of the plugin.
     */
    protected $plugin_basename;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $cbxwpsimpleaccounting The name of this plugin.
     * @param      string $version               The version of this plugin.
     */
    public function __construct($cbxwpsimpleaccounting, $version) {

        $this->cbxwpsimpleaccounting            = $cbxwpsimpleaccounting;
        $this->cbxwpsimpleaccounting_cat        = 'cbxwpsimpleaccounting_cat';
        $this->cbxwpsimpleaccounting_addexpinc  = 'cbxwpsimpleaccounting_addexpinc';
        $this->cbxwpsimpleaccounting_accmanager = 'cbxwpsimpleaccounting_accmanager';
        $this->cbxwpsimpleaccounting_settings   = 'cbxwpsimpleaccounting_settings';
        $this->cbxwpsimpleaccounting_addons     = 'cbxwpsimpleaccounting_addons';

        $this->version 			= $version;
        $this->plugin_basename 	= plugin_basename(plugin_dir_path(__DIR__) . $this->cbxwpsimpleaccounting . '.php');
        $this->settings_api 	= new Cbxwpsimpleaccounting_Settings_API($cbxwpsimpleaccounting, $version);
    }

    /**
     * Settings init
     */
    public function setting_init() {
        //set the settings
        $this->settings_api->set_sections($this->get_settings_sections());
        $this->settings_api->set_fields($this->get_settings_fields());
        //initialize settings
        $this->settings_api->admin_init();

        $role = get_role('administrator');

        //who can manage or manage accounting capability
        // Set 'manage_cbxaccounting','edit_cbxaccounting','delete_accounting' Capabilities To Administrator
        if (!$role->has_cap('manage_cbxaccounting')) {
            $role->add_cap('manage_cbxaccounting');
        }

        //who can edit or edit accounting log capability
        if (!$role->has_cap('edit_cbxaccounting')) {
            $role->add_cap('edit_cbxaccounting');
        }

        //who can delete or delete accounting log capability
        if (!$role->has_cap('delete_cbxaccounting')) {
            $role->add_cap('delete_cbxaccounting');
        }
    }

    /**
     * Callback for activation plugin error check action 'plugin_activated'
     * 
     */
    public function cbxaccounting_activation_error() {

        update_option('abxaccounting_activation_error', ob_get_contents());
    }

    /**
     * call back for filter 'cbxwpsimpleaccounting_title_link'
     * 
     */
    public function overview_title_link($title, $id) {

        if (!current_user_can('edit_cbxaccounting')) {
            return $title;
        }

        return '<a href="' . get_admin_url() . 'admin.php?page=cbxwpsimpleaccounting_addexpinc&id=' . $id . '">' . $title . '</a>';

    }

    /**
     * Register the stylesheets for Dashboard pages.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }


        //register choosen
        wp_register_style('cbx-chosen', plugin_dir_url(__FILE__) . 'css/chosen.min.css', array(), $this->version, 'all');

		wp_register_style('flatpickr.min', plugin_dir_url(__FILE__) . 'flatpickr/flatpickr.min.css', array(), $this->version, 'all');

        //wp_register_style('jquery-ui-timepicker-addon-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        //wp_register_style('jquery-ui-timepicker-addon-css', plugin_dir_url(__FILE__) . 'css/jquery-ui-timepicker-addon.css', array(), $this->version, 'all');


        $screen = get_current_screen();
        //for category page
        if ('cbx-accounting_page_' . $this->cbxwpsimpleaccounting_cat == $screen->id) {
            //register style
            wp_register_style($this->cbxwpsimpleaccounting, plugin_dir_url(__FILE__) . 'css/cbxwpsimpleaccounting-admin.css', array(), $this->version, 'all');

            //enqueue styles
            wp_enqueue_style($this->cbxwpsimpleaccounting);
            wp_enqueue_style('cbx-chosen');
            wp_enqueue_style('wp-color-picker');
        }
        //for overview page
        if ('toplevel_page_cbxwpsimpleaccounting' == $screen->id) {
            //register style
            wp_register_style($this->cbxwpsimpleaccounting, plugin_dir_url(__FILE__) . 'css/cbxwpsimpleaccounting-overview.css', array(), $this->version, 'all');

            //enqueue style
            wp_enqueue_style($this->cbxwpsimpleaccounting);
        }
        //for add expinc page
        if ('cbx-accounting_page_' . $this->cbxwpsimpleaccounting_addexpinc == $screen->id) {
            //register style
            wp_register_style($this->cbxwpsimpleaccounting, plugin_dir_url(__FILE__) . 'css/cbxwpsimpleaccounting-admin.css', array(), $this->version, 'all');

            //enqueue styles
            wp_enqueue_style($this->cbxwpsimpleaccounting);
            wp_enqueue_style('cbx-chosen');
            //wp_enqueue_style('jquery-ui-timepicker-addon-ui-css');
            //wp_enqueue_style('jquery-ui-timepicker-addon-css');
            wp_enqueue_style('flatpickr.min');
        }
        //for settings page
        if ('cbx-accounting_page_' . $this->cbxwpsimpleaccounting_settings == $screen->id) {
            //enqueue script
            wp_enqueue_style('cbx-chosen');
        }
        //for addon page
        if ('cbx-accounting_page_' . $this->cbxwpsimpleaccounting_addons == $screen->id) {
            //register script
            wp_register_style($this->cbxwpsimpleaccounting, plugin_dir_url(__FILE__) . 'css/cbxwpsimpleaccounting-addons.css', array(), $this->version, 'all');

            //enqueue script
            wp_enqueue_style($this->cbxwpsimpleaccounting);
        }
        //for account manager page
        if ('cbx-accounting_page_' . $this->cbxwpsimpleaccounting_accmanager == $screen->id) {
            //register style
            wp_register_style($this->cbxwpsimpleaccounting, plugin_dir_url(__FILE__) . 'css/cbxwpsimpleaccounting-admin.css', array(), $this->version, 'all');

            //enqueue styles
            wp_enqueue_style($this->cbxwpsimpleaccounting);
            wp_enqueue_style('cbx-chosen');
        }
    }

    /**
     * Register the JavaScript for Dashboard pages.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {


        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }


        //register choosen scripts and form validation
        wp_register_script('chosen.jquery.min', plugin_dir_url(__FILE__) . 'js/chosen.jquery.min.js', array('jquery'), $this->version, false);
        wp_register_script('jquery.validate.min', plugin_dir_url(__FILE__) . 'js/jquery.validate.min.js', array('jquery'));

        //flatpickr - Date time picker
		wp_register_script('flatpickr.min', plugin_dir_url(__FILE__) . 'flatpickr/flatpickr.min.js', array(), $this->version, array('jquery'));

		//js hook
		wp_register_script('event-manager.min', plugin_dir_url(__FILE__) . 'js/event-manager.min.js', array(), $this->version, array('jquery'));

        //ajax nonce
        $ajax_nonce = wp_create_nonce("cbxwpsimpleaccounting_nonce");

        $screen = get_current_screen();

        //expinc manager page
        if ($this->plugin_screen_hook_suffix_expinc == $screen->id) {
            global $wpdb;
            $cat_results_list = $wpdb->get_results('SELECT `id`, `title`, `type` FROM `' . $wpdb->prefix . 'cbaccounting_category`', ARRAY_A);

            $cats = array();
            if ($cat_results_list != null) {
                foreach ($cat_results_list as $item) {
                    $cats[$item['id']] = $item;
                }
            }

           // $all_exp_inc_list = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'cbaccounting_expinc`');


			wp_enqueue_script('jquery');
            //register scripts      
            wp_register_script($this->cbxwpsimpleaccounting, plugin_dir_url(__FILE__) . 'js/cbxwpsimpleaccounting-add.js', array('jquery', 'jquery.validate.min'), '1.1', true);

            // Localize the script with new data
            $translation_array = array(
                'edit'                  => esc_html__('Edit', 'cbxwpsimpleaccounting'),
                'category_update_label' => esc_html__('Add Category', 'cbxwpsimpleaccounting'),
                'category_add_label'    => esc_html__('Upadte Category', 'cbxwpsimpleaccounting'),
                'nonce'                 => $ajax_nonce,
                'cat_results_list'      => wp_json_encode($cats)
            );
            wp_localize_script($this->cbxwpsimpleaccounting, 'cbxwpsimpleaccounting', $translation_array);

            //enqueue script

            wp_enqueue_script('jquery.validate.min');
			wp_enqueue_script('chosen.jquery.min');
			wp_enqueue_script('flatpickr.min');
			wp_enqueue_script('event-manager.min');
			wp_enqueue_script($this->cbxwpsimpleaccounting);
		}

        //category manager page
        if ($this->plugin_screen_hook_suffix_cat == $screen->id) {
            global $wpdb;
            $cat_results_list = $wpdb->get_results('SELECT `id`, `title`, `type`, `color` FROM `' . $wpdb->prefix . 'cbaccounting_category`', ARRAY_A);
            $cats             = array();
            if ($cat_results_list != null) {
                foreach ($cat_results_list as $item) {
                    $cats[$item['id']] = $item;
                }
            }


           // $all_exp_inc_list = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'cbaccounting_expinc`');

			wp_enqueue_script('jquery');

            //register scripts
            wp_register_script($this->cbxwpsimpleaccounting, plugin_dir_url(__FILE__) . 'js/cbxwpsimpleaccounting-category.js', array('jquery', 'jquery.validate.min'), '1.1', true);

            // Localize the script with new data
            $translation_array = array(
                'edit'                  => esc_html__('Edit', 'cbxwpsimpleaccounting'),
                'category_update_label' => esc_html__('Add Category', 'cbxwpsimpleaccounting'),
                'category_add_label'    => esc_html__('Upadte Category', 'cbxwpsimpleaccounting'),
                'nonce'                 => $ajax_nonce,
                'cat_results_list'      => wp_json_encode($cats)
            );
            wp_localize_script($this->cbxwpsimpleaccounting, 'cbxwpsimpleaccounting', $translation_array);

            //enqueue scripts
            wp_enqueue_script('jquery.validate.min');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('chosen.jquery.min');
            
            wp_enqueue_script($this->cbxwpsimpleaccounting);
        }

        //overview page
        if ($this->plugin_screen_hook_suffix == $screen->id) {

			wp_enqueue_script('jquery');


            //register script
            wp_register_script('admin-overview', plugin_dir_url(__FILE__) . 'js/cbxwpsimpleaccounting-admin-overview.js', array('jquery'), $this->version, false);
            wp_register_script('cbxchartkickgjsapi', 'http://www.google.com/jsapi', array(), $this->version, false);
            wp_register_script('cbxchartkick', plugin_dir_url(__FILE__) . 'js/chartkick.js', array('jquery', 'cbxchartkickgjsapi'), $this->version, false);


            //Localize the script with new data
            $translation_array = array(
                'nonce'      => $ajax_nonce,
                'permission' => esc_html__('This action can not be undone. Are you sure to delete this entry ?', 'cbxwpsimpleaccounting'),
                'month'      => esc_html__('Month', 'cbxwpsimpleaccounting'),
                'day'        => esc_html__('Day', 'cbxwpsimpleaccounting'),
                'income'     => esc_html__('Income', 'cbxwpsimpleaccounting'),
                'expense'    => esc_html__('Expense', 'cbxwpsimpleaccounting')
            );
            wp_localize_script('admin-overview', $this->cbxwpsimpleaccounting, $translation_array);

            //enqueue script
            wp_enqueue_script('admin-overview');
            wp_enqueue_script('cbxchartkickgjsapi');
            wp_enqueue_script('cbxchartkick');

            //wp_enqueue_script('cbxchartlegend');
        }

        //account manager page
        if ($this->plugin_screen_hook_suffix_accmanager == $screen->id) {
            global $wpdb;
            $accs             = array();
            $acc_results_list = $wpdb->get_results('SELECT `id`, `title`, `type` FROM `' . $wpdb->prefix . 'cbaccounting_account_manager`', ARRAY_A);
            $cats             = array();
            if ($acc_results_list != null) {
                foreach ($acc_results_list as $item) {
                    $accs[$item['id']] = $item;
                }
            }

			wp_enqueue_script('jquery');

            //register scripts
            wp_register_script('cbxwpsacc-accountmanager', plugin_dir_url(__FILE__) . 'js/cbxwpsimpleaccounting-account-manager.js', array('jquery'), $this->version, false);

            // Localize the script with new data
            $translation_array = array(
                'edit'                 => esc_html__('Edit', 'cbxwpsimpleaccounting'),
                'account_update_label' => esc_html__('Add Account', 'cbxwpsimpleaccounting'),
                'account_add_label'    => esc_html__('Upadte Account', 'cbxwpsimpleaccounting'),
                'nonce'                => $ajax_nonce,
                'acc_results_list'     => wp_json_encode($accs)
            );
            wp_localize_script('cbxwpsacc-accountmanager', 'cbxwpsimpleaccounting', $translation_array);

            //enqueue scripts
            wp_enqueue_script('jquery.validate.min');            
            wp_enqueue_script('chosen.jquery.min');
            wp_enqueue_script('cbxwpsacc-accountmanager');
            
        }


        //setting
        if ($this->plugin_screen_hook_suffix_settings == $screen->id) {

			wp_enqueue_script('jquery');

            //enqueue scripts
            wp_enqueue_script('chosen.jquery.min');
        }
    }

    /**
     * Get plugin basename
     *
     * @since     1.0.0
     * @return    string    The basename of the plugin.
     */
    public function get_plugin_basename() {
        return $this->plugin_basename;
    }

    /**
     * To access all loader class property
     *
     * @param Loader class object $loader
     *
     * @since 1.0.0
     */
    public function set_loader($loader) {
        $this->loader = $loader;
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {
        //overview
        $this->plugin_screen_hook_suffix            = add_menu_page(
            __('CBX Accounting', 'cbxwpsimpleaccounting'), __('CBX Accounting', 'cbxwpsimpleaccounting'), 'manage_cbxaccounting', $this->cbxwpsimpleaccounting, array($this, 'display_plugin_admin_overview'), 'dashicons-chart-line', '58.16'
        );
        //add income expense menu
        $this->plugin_screen_hook_suffix_expinc     = add_submenu_page(
            $this->cbxwpsimpleaccounting, esc_html__('CBX Accounting: Manage Income/Expence', 'cbxwpsimpleaccounting'), esc_html__('Income/Expense', 'cbxwpsimpleaccounting'), 'edit_cbxaccounting', $this->cbxwpsimpleaccounting_addexpinc, array($this, 'display_plugin_admin_adddexpinc'), 'dashicons-chart-line', '58.16'
        );
        //category menu
        $this->plugin_screen_hook_suffix_cat        = add_submenu_page(
            $this->cbxwpsimpleaccounting, esc_html__('CBX Accounting: Manage Category', 'cbxwpsimpleaccounting'), esc_html__('Manage Category', 'cbxwpsimpleaccounting'), 'manage_options', $this->cbxwpsimpleaccounting_cat, array($this, 'display_plugin_admin_managecategory'), 'dashicons-chart-line', '58.16'
        );
        //account manager menu
        $this->plugin_screen_hook_suffix_accmanager = add_submenu_page(
            $this->cbxwpsimpleaccounting, esc_html__('CBX Account Manager: Manage Account', 'cbxwpsimpleaccounting'), esc_html__('Account Manager', 'cbxwpsimpleaccounting'), 'manage_options', $this->cbxwpsimpleaccounting_accmanager, array($this, 'display_plugin_admin_manage_account'), 'dashicons-chart-line', '58.16'
        );
        //setting menu
        $this->plugin_screen_hook_suffix_settings   = add_submenu_page(
            $this->cbxwpsimpleaccounting, esc_html__('CBX Accounting:', 'cbxwpsimpleaccounting'), esc_html__('Setting', 'cbxwpsimpleaccounting'), 'manage_options', $this->cbxwpsimpleaccounting_settings, array($this, 'display_plugin_admin_settings'), 'dashicons-chart-line', '58.16'
        );
        //addons menu
        $this->plugin_screen_hook_suffix_addons     = add_submenu_page(
            $this->cbxwpsimpleaccounting, esc_html__('CBX Accounting:', 'cbxwpsimpleaccounting'), esc_html__('Add-ons', 'cbxwpsimpleaccounting'), 'manage_options', $this->cbxwpsimpleaccounting_addons, array($this, 'display_plugin_admin_addons'), 'dashicons-chart-line', '58.16'
        );
    }

    /**
     * @param array $links Default settings links
     *
     * @return array
     */
    public function add_plugin_admin_page($links) {
        $new_links[] = '<a href="' . admin_url('admin.php?page=' . $this->cbxwpsimpleaccounting) . '">' . esc_html__('Settings', 'cbxwpsimpleaccounting') . '</a>';
        return array_merge($new_links, $links);
    }

    /**
     * Add support link to plugin description in /wp-admin/plugins.php
     *
     * @param  array  $plugin_meta
     * @param  string $plugin_file
     *
     * @return array
     */
    public function support_link($plugin_meta, $plugin_file) {

        if ($this->plugin_basename == $plugin_file) {
            $plugin_meta[] = sprintf(
                '<a href="%s">%s</a>', 'http://codeboxr.com/product/cbx-accounting/', esc_html__('Support','cbxwpsimpleaccounting')
            );
        }

        return $plugin_meta;
    }

    /**
     * Run all administrator program from here
     *
     * @since   1.0.0
     */
    public function run() {
        $this->loader->add_filter('plugin_action_links_' . $this->plugin_basename, $this, 'add_plugin_admin_page');
        //Add admin menu action hook
        $this->loader->add_action('admin_menu', $this, 'add_plugin_admin_menu');
        //load next prev year data
        $this->loader->add_action('wp_ajax_load_nextprev_year', $this, 'load_nextprev_year');
        //load next prev month data
        $this->loader->add_action('wp_ajax_load_nextprev_month', $this, 'load_nextprev_month');
        //add new expense or income
        $this->loader->add_action('wp_ajax_add_new_expinc', $this, 'add_new_expinc');
        //load/edit expense or income info by id
        $this->loader->add_action('wp_ajax_load_expinc', $this, 'load_expinc');
        //delete any expinc
        $this->loader->add_action('wp_ajax_delete_expinc', $this, 'delete_expinc');
        //add new category
        $this->loader->add_action('wp_ajax_add_new_expinc_cat', $this, 'add_new_expinc_cat');
        //load/edit category info by id
        $this->loader->add_action('wp_ajax_load_expinc_cat', $this, 'load_expinc_cat');
        //new account manager 
        $this->loader->add_action('wp_ajax_add_new_manager_acc', $this, 'add_new_manager_acc');
        //load/edit manager by id
        $this->loader->add_action('wp_ajax_load_account', $this, 'load_account');
    }

    /**
     * Add new Expense/Income
     *
     *
     * @since 1.0.0
     *
     * return string
     */
    public function add_new_expinc() {
        global $wpdb;

        $form_validation            = true;
        $cbacnt_validation['error'] = false;
        $cbacnt_validation['field'] = array();

        //verify nonce field
        if (wp_verify_nonce($_POST['new_expinc_verifier'], 'add_new_expinc')) {

            if (!current_user_can('edit_cbxaccounting')) {
                $cbacnt_validation['error'] = true;
                $cbacnt_validation['msg']   = esc_html__('You don\'t have enough permission to add/edit Income/Expense.', $this->cbxwpsimpleaccounting);

                echo json_encode($cbacnt_validation);
                wp_die();
            }

            if (isset($_POST['cbacnt-exinc-source-amount']) && !empty($_POST['cbacnt-exinc-source-amount'])) {
                $source_amount = abs(floatval($_POST['cbacnt-exinc-source-amount']));
            }
            else {
                $source_amount = null;
            }

            if (isset($_POST['cbacnt-exinc-currency']) && !empty($_POST['cbacnt-exinc-currency'])) {
                $source_currency = sanitize_text_field($_POST['cbacnt-exinc-currency']);
            }
            else {
                $source_currency = null;
            }

			$col_data = array(
				'title'           => sanitize_text_field($_POST['cbacnt-exinc-title']),
				'note'            => sanitize_text_field($_POST['cbacnt-exinc-note']),
				'amount'          => abs(floatval($_POST['cbacnt-exinc-amount'])),
				'type'            => absint($_POST['cbacnt-exinc-type']),
				'source_amount'   => abs(floatval($_POST['cbacnt-exinc-source-amount'])),
				'source_currency' => sanitize_text_field($_POST['cbacnt-exinc-currency']),
				'account'         => abs($_POST['cbacnt-exinc-acc']),
				'invoice'         => sanitize_text_field($_POST['cbacnt-exinc-invoice']),
				'istaxincluded'   => 0,
				'tax'             => 0
			);


            if (isset($_POST['cbacnt-exinc-include-tax']) && $_POST['cbacnt-exinc-include-tax'] == 1) {
				$col_data['istaxincluded']   = 1;
				$col_data['tax']             = abs(floatval($_POST['cbacnt-exinc-tax']));
            }





            $title_len = strlen($col_data['title']);
            $note_len  = strlen($col_data['note']);

            //check expense/income title length is not less than 5 or more than 200 char
            if ($title_len < 5 || $title_len > 200) {
                $form_validation              = false;
                $cbacnt_validation['error']   = true;
                $cbacnt_validation['field'][] = 'cbacnt-exinc-title';
                $cbacnt_validation['msg']     = esc_html__('The title field character limit must be between 5 to 200.', 'cbxwpsimpleaccounting');
            }

            //check expense/income note length is not less than 10 or more than 2000 char if provided
            if ((!empty($note_len) && $note_len < 10) || $note_len > 2000) {
                $form_validation              = false;
                $cbacnt_validation['error']   = true;
                $cbacnt_validation['field'][] = 'cbacnt-exinc-note';
                $cbacnt_validation['msg']     = esc_html__('The note field character limit must be between 10 to 2000.', 'cbxwpsimpleaccounting');
            }

            //check expense/income is not less than 1
            if ($col_data['amount'] < 1) {
                $form_validation              = false;
                $cbacnt_validation['error']   = true;
                $cbacnt_validation['field'][] = 'cbacnt-exinc-amount';
                $cbacnt_validation['msg']     = esc_html__('Amount must be greater than 0.00.', 'cbxwpsimpleaccounting');
            }
            //forcing user to chose one and only one category
            if (!isset($_POST['cbacnt-expinc-cat']) || (isset($_POST['cbacnt-expinc-cat']) && $_POST['cbacnt-expinc-cat'] == null ) || count($_POST['cbacnt-expinc-cat'], COUNT_RECURSIVE) > 2) {
                $form_validation              = false;
                $cbacnt_validation['error']   = true;
                $cbacnt_validation['field'][] = 'cbacnt-exinc-category';
                $cbacnt_validation['msg']     = esc_html__('Please select at least and only one category', 'cbxwpsimpleaccounting');
            }


            $exinc_id       = absint($_POST['cbacnt-exinc-id']);
            $exinc_cat_list = isset($_POST['cbacnt-expinc-cat']) ? $_POST['cbacnt-expinc-cat'] : array();


            //check form passes all validation rules
            if ($form_validation) {
                //edit mode
                if ($exinc_id > 0) {
                    //check the expense/income exist with provided id
                    if ($wpdb->get_row(
                            $wpdb->prepare("SELECT `title` FROM `" . $wpdb->prefix . "cbaccounting_expinc` WHERE `id` = %d", $exinc_id), ARRAY_A
                        ) != null
                    ) {

                        $col_data['mod_by']   = get_current_user_id();
                        $col_data['add_date'] = (isset($_POST['cbacnt-exinc-add-date']) && $_POST['cbacnt-exinc-add-date'] != NULL) ? $_POST['cbacnt-exinc-add-date'] : current_time('mysql');
                        $col_data['mod_date'] = current_time('mysql');


						$col_data			= apply_filters('cbxwpsimpleaccounting_incexp_post_process', $col_data);

                        $where = array(
                            'id' => $exinc_id
                        );


                        $col_data_format 	= array('%s', '%s', '%f', '%d', '%f', '%s', '%d', '%s', '%d', '%f', '%s', '%s', '%s');
						$col_data_format	= apply_filters('cbxwpsimpleaccounting_incexp_post_coldataformat', $col_data_format);

                        $where_format = array('%d');

                        //start transaction
                        $wpdb->query('START TRANSACTION');

                        //matching update function return is false, then update failed.
                        if ($wpdb->update($wpdb->prefix . 'cbaccounting_expinc', $col_data, $where, $col_data_format, $where_format) === false) {
                            //update failed
                            $cbacnt_validation['error']   = true;
                            $cbacnt_validation['field'][] = '';
                            $cbacnt_validation['msg']     = esc_html__('Update failed', 'cbxwpsimpleaccounting');
                        }
                        else {
                            //update successful. $item_insert
                            $item_del = $wpdb->delete($wpdb->prefix . 'cbaccounting_expcat_rel', array('expinc_id' => $exinc_id), $where_format);

                            $cat_list_value = array();
                            $cat_id_holder  = array();
                            $place_holders  = array();


                            foreach ($exinc_cat_list as $type_key => $type) {
                                foreach ($type as $cat_id) {
                                    //$cat_id_holder[] = $type_key . $cat_id;
                                    $cat_id_holder[] = $cat_id;
                                    array_push($cat_list_value, null, $exinc_id, $cat_id);
                                    $place_holders[] = "( %d, %d, %d )";
                                }
                            }

                            $query           = 'INSERT INTO `' . $wpdb->prefix . 'cbaccounting_expcat_rel` ( `id`, `expinc_id`, `category_id` ) VALUES ';
                            $query .= implode(', ', $place_holders);
                            $item_cat_insert = $wpdb->query($wpdb->prepare($query, $cat_list_value));

                            if ($item_del && $item_cat_insert) {
                                $wpdb->query('COMMIT');

                                $msg = esc_html__('Item updated.', $this->cbxwpsimpleaccounting);
                                $msg .= ' <a data-id="' . $exinc_id . '" href="javascript:void(0);" class="button cbacnt-edit-exinc">';
                                $msg .= esc_html__('Edit', $this->cbxwpsimpleaccounting);
                                $msg .= '</a>';
                                $msg .= ' <a href="javascript:void(0);" class="button cbacnt-new-exinc">';
                                $msg .= esc_html__('Add new', $this->cbxwpsimpleaccounting);
                                $msg .= '</a>';

                                $cbacnt_validation['error']                         = false;
                                $cbacnt_validation['msg']                           = $msg;
                                $cbacnt_validation['form_value']['id']              = $exinc_id;
                                $cbacnt_validation['form_value']['status']          = 'updated';
                                $cbacnt_validation['form_value']['title']           = stripslashes(esc_attr($col_data['title']));
                                $cbacnt_validation['form_value']['amount']          = $col_data['amount'];
                                $cbacnt_validation['form_value']['source_amount']   = $col_data['source_amount'];
                                $cbacnt_validation['form_value']['source_currency'] = $col_data['source_currency'];
                                $cbacnt_validation['form_value']['account']         = $col_data['account'];
                                $cbacnt_validation['form_value']['invoice']         = stripslashes(esc_attr($col_data['invoice']));
                                $cbacnt_validation['form_value']['istaxincluded']   = $col_data['istaxincluded'];
                                $cbacnt_validation['form_value']['tax']             = $col_data['tax'];
                                $cbacnt_validation['form_value']['type']            = $col_data['type'];
                                $cbacnt_validation['form_value']['note']            = stripslashes(esc_textarea($col_data['note']));
                                $cbacnt_validation['form_value']['add_date']        = $col_data['add_date'];
                                $cbacnt_validation['form_value']['cat_list']        = $cat_id_holder;

								$cbacnt_validation									= apply_filters('cbxwpsimpleaccounting_incexp_post_data', $cbacnt_validation, $col_data);
                            }
                            else { //new category insertion failed                               
                                $wpdb->query('ROLLBACK');
                                $cbacnt_validation['error']   = true;
                                $cbacnt_validation['field'][] = '';
                                $cbacnt_validation['msg']     = esc_html__('Error editing, please reload this page.', $this->cbxwpsimpleaccounting);
                            }
                        }
                    }
                    else { //if category doesn't exist with id
                        $cbacnt_validation['msg'] = esc_html__('You attempted to edit the category that doesn\'t exist.' . $exinc_id, $this->cbxwpsimpleaccounting);
                    }
                }
                else {
                    //add new

                    $col_data['add_by'] = get_current_user_id();

                    $col_data['add_date'] = (isset($_POST['cbacnt-exinc-add-date']) && $_POST['cbacnt-exinc-add-date'] != NULL) ? $_POST['cbacnt-exinc-add-date'] : current_time('mysql');

					$col_data			= apply_filters('cbxwpsimpleaccounting_incexp_post_process', $col_data);


                    $wpdb->query('START TRANSACTION');


                    $col_data_format = array('%s', '%s', '%f', '%d', '%f', '%s', '%d', '%s', '%d', '%f', '%d', '%s');
					$col_data_format	= apply_filters('cbxwpsimpleaccounting_incexp_post_coldataformat', $col_data_format);

                    $item_insert    = $wpdb->insert($wpdb->prefix . 'cbaccounting_expinc', $col_data, $col_data_format);
                    $item_insert_id = $wpdb->insert_id;

                    $cat_list_value = array();
                    $cat_id_holder  = array();
                    $place_holders  = array();

                    foreach ($exinc_cat_list as $type_key => $type) {
                        foreach ($type as $cat_id) {
                            $cat_id_holder[] = $cat_id;
                            array_push($cat_list_value, null, $wpdb->insert_id, $cat_id);
                            $place_holders[] = "( %d, %d, %d )";
                        }
                    }

                    $query           = 'INSERT INTO `' . $wpdb->prefix . 'cbaccounting_expcat_rel` ( `id`, `expinc_id`, `category_id` ) VALUES ';
                    $query .= implode(', ', $place_holders);
                    $item_cat_insert = $wpdb->query($wpdb->prepare($query, $cat_list_value));

                    //insert new expense/income
                    if ($item_insert && $item_cat_insert) {
                        $wpdb->query('COMMIT');
                        //new expense/income inserted successfully
                        $msg = esc_html__('A new item inserted.', $this->cbxwpsimpleaccounting);
                        $msg .= ' <a data-id="' . $item_insert_id . '" href="javascript:void(0);" class="button cbacnt-edit-exinc">';
                        $msg .= esc_html__('Edit item.', $this->cbxwpsimpleaccounting);
                        $msg .= '</a>';
                        $msg .= ' <a href="javascript:void(0);" class="button cbacnt-new-exinc">';
                        $msg .= esc_html__('Add new', $this->cbxwpsimpleaccounting);
                        $msg .= '</a>';

                        $cbacnt_validation['error']                         = false;
                        $cbacnt_validation['msg']                           = $msg;
                        $cbacnt_validation['form_value']['id']              = $item_insert_id;
                        $cbacnt_validation['form_value']['status']          = 'new';
                        $cbacnt_validation['form_value']['title']           = stripslashes(esc_attr($col_data['title']));
                        $cbacnt_validation['form_value']['amount']          = $col_data['amount'];
                        $cbacnt_validation['form_value']['source_amount']   = $col_data['source_amount'];
                        $cbacnt_validation['form_value']['source_currency'] = $col_data['source_currency'];
                        $cbacnt_validation['form_value']['account']         = $col_data['account'];
                        $cbacnt_validation['form_value']['invoice']         = stripslashes(esc_attr($col_data['invoice']));
                        $cbacnt_validation['form_value']['istaxincluded']   = $col_data['istaxincluded'];
                        $cbacnt_validation['form_value']['tax']             = $col_data['tax'];
                        $cbacnt_validation['form_value']['note']            = stripslashes(esc_textarea($col_data['note']));
                        $cbacnt_validation['form_value']['type']            = $col_data['type'];
                        $cbacnt_validation['form_value']['cat_list']        = $cat_id_holder;

						$cbacnt_validation									= apply_filters('cbxwpsimpleaccounting_incexp_post_data', $cbacnt_validation, $col_data);
                    }
                    else { //new category insertion failed
                        $wpdb->query('ROLLBACK');
                        $cbacnt_validation['error']   = true;
                        $cbacnt_validation['field'][] = '';
                        $cbacnt_validation['msg']     = esc_html__('Error adding, please reload this page.', $this->cbxwpsimpleaccounting);
                    }
                }
            }
        }
        else { //if wp_nonce not verified then entry here
            $cbacnt_validation['error']   = true;
            $cbacnt_validation['field'][] = 'wp_nonce';
            $cbacnt_validation['msg']     = esc_html__('Form is security validation error. Please reload page and try again.', $this->cbxwpsimpleaccounting);
        }

        echo json_encode($cbacnt_validation);
        wp_die();
    }

    /**
     * load income/expense via ajax request for edit
     *
     */
    public function load_expinc() {
        global $wpdb;
        $form_validation            = true;
        $cbacnt_validation['error'] = false;
        $cbacnt_validation['field'] = array();

        check_ajax_referer('cbxwpsimpleaccounting_nonce', 'nonce');

        if (!current_user_can('edit_cbxaccounting')) {
            $cbacnt_validation['error'] = true;
            $cbacnt_validation['msg']   = esc_html__('You don\'t have enough permission to edit Income/Expense. ', $this->cbxwpsimpleaccounting);
            echo json_encode($cbacnt_validation);
            wp_die();
        }

        $id = absint($_POST['id']);
        //if provide the  id
        if ($id > 0) {

            $incexp        = $wpdb->get_row($wpdb->prepare('SELECT *  FROM `' . $wpdb->prefix . 'cbaccounting_expinc` WHERE id = %d', $id), ARRAY_A);
            $incexpcatlist = $wpdb->get_results($wpdb->prepare('SELECT *  FROM `' . $wpdb->prefix . 'cbaccounting_expcat_rel` WHERE expinc_id = %d', $id), ARRAY_A);

            if ($incexpcatlist != null) {
                foreach ($incexpcatlist as $list) {
                    $catlist[] = $list['category_id'];
                }
            }

            if ($incexp != null) {

                $cbacnt_validation['error']                         = false;
                $cbacnt_validation['msg']                           = esc_html__('Data Loaded for edit', 'cbxwpsimpleaccounting');
                $cbacnt_validation['form_value']['id']              = $id;
                $cbacnt_validation['form_value']['status']          = 'loaded';
                $cbacnt_validation['form_value']['title']           = stripslashes(esc_attr($incexp['title']));
                $cbacnt_validation['form_value']['amount']          = $incexp['amount'];
                $cbacnt_validation['form_value']['source_amount']   = absint($incexp['source_amount']);
                $cbacnt_validation['form_value']['source_currency'] = $incexp['source_currency'];
                $cbacnt_validation['form_value']['account']         = absint($incexp['account']);
                $cbacnt_validation['form_value']['invoice']         = stripslashes(esc_attr($incexp['invoice']));
                $cbacnt_validation['form_value']['istaxincluded']   = $incexp['istaxincluded'];
                $cbacnt_validation['form_value']['tax']             = $incexp['tax'];
                $cbacnt_validation['form_value']['cat_list']        = $catlist;
                $cbacnt_validation['form_value']['type']            = absint($incexp['type']);
                $cbacnt_validation['form_value']['note']            = $incexp['add_date'];
                $cbacnt_validation['form_value']['add_date']        = stripslashes(esc_textarea($incexp['note']));

				$cbacnt_validation									= apply_filters('cbxwpsimpleaccounting_incexp_edit_data', $cbacnt_validation, $incexp);



            }
            else {
                $cbacnt_validation['error'] = true;
                $cbacnt_validation['msg']   = esc_html__('You attempted to edit item that doesn\'t exist. ', 'cbxwpsimpleaccounting');
            }
        }
        else { //if category is new then go here
            $cbacnt_validation['error'] = true;
            $cbacnt_validation['msg']   = esc_html__('You attempted to edit item that doesn\'t exist. ', 'cbxwpsimpleaccounting');
        }

        echo json_encode($cbacnt_validation);
        wp_die();
    }

    /**
     * Ajax call for deleting a single row.
     *
     */
    public function delete_expinc() {
        global $wpdb;

        check_ajax_referer('cbxwpsimpleaccounting_nonce', 'security');

        if (!current_user_can('delete_cbxaccounting')) {
            $cbacnt_validation['error'] = true;
            $cbacnt_validation['msg']   = esc_html__('You don\'t have enough permission to delete Income/Expense.', 'cbxwpsimpleaccounting');

            echo json_encode($cbacnt_validation);
            wp_die();
        }
        $cbxexpinc_table = $wpdb->prefix . 'cbaccounting_expinc';
        if (isset($_POST['id'])) {
            $deleted_id = $wpdb->delete($cbxexpinc_table, array('id' => $_POST['id']), array('%d'));
        }

        if ($deleted_id) {
            $expinc_deletion['error'] = false;
            $expinc_deletion['msg']   = esc_html__('Item Deleted. ', 'cbxwpsimpleaccounting');
        }
        else {
            $expinc_deletion['error'] = true;
            $expinc_deletion['msg']   = esc_html__('Cannot Delete Item. ', 'cbxwpsimpleaccounting');
        }

        echo json_encode($expinc_deletion);

        wp_die(); // this is required to terminate immediately and return a proper response
    }

    /**
     * Add new Expense or Income Category
     *
     * return string
     */
    public function add_new_expinc_cat() {
        global $wpdb;
        $form_validation            = true;
        $cbacnt_validation['error'] = false;
        $cbacnt_validation['field'] = array();
        $cbxacc_table_name          = $wpdb->prefix . 'cbaccounting_category';


        //verify nonce field
        if (wp_verify_nonce($_POST['new_cat_verifier'], 'add_new_expinc_cat')) {

            $col_data = array(
                'title' => sanitize_text_field($_POST['cbacnt-cat-title']),
                'type'  => absint($_POST['cbacnt-cat-type']),
                'color' => sanitize_text_field($_POST['cbacnt-cat-color']),
                'note'  => sanitize_text_field($_POST['cbacnt-cat-note'])
            );

            $cat_id    = absint($_POST['cbacnt-cat-id']);
            $title_len = strlen($col_data['title']);
            $note_len  = strlen($col_data['note']);
            $cat_title = $col_data['title'];
            $cat_color = $col_data['color'];

            //see if category name already saved/exist
            $query                 = $wpdb->prepare('SELECT COUNT(*) FROM ' . $cbxacc_table_name . ' WHERE id != %d AND title = %s ', $cat_id, $cat_title);
            $cbxacc_cattitle_check = $wpdb->get_var($query);

            //see if category color already saved/exist
            $query                 = $wpdb->prepare('SELECT COUNT(*) FROM ' . $cbxacc_table_name . ' WHERE id != %d AND color = %s ', $cat_id, $cat_color);
            $cbxacc_catcolor_check = $wpdb->get_var($query);

            //check same category title
            if ($cbxacc_cattitle_check != 0) {
                $form_validation              = false;
                $cbacnt_validation['error']   = true;
                $cbacnt_validation['field'][] = 'cbacnt-cat-color';
                $cbacnt_validation['msg']     = esc_html__('The Category title is already in use.', 'cbxwpsimpleaccounting');
            }
            //check same category color 
            if ($cbxacc_catcolor_check != 0) {
                $form_validation              = false;
                $cbacnt_validation['error']   = true;
                $cbacnt_validation['field'][] = 'cbacnt-cat-color';
                $cbacnt_validation['msg']     = esc_html__('The Category color is already in use.', 'cbxwpsimpleaccounting');
            }
            //check category title length is not less than 5 or more than 200 char
            if ($title_len < 5 || $title_len > 200) {
                $form_validation              = false;
                $cbacnt_validation['error']   = true;
                $cbacnt_validation['field'][] = 'cbacnt-cat-title';
                $cbacnt_validation['msg']     = esc_html__('The title field character limit must be between 5 to 200.', 'cbxwpsimpleaccounting');
            }
            //check category note length is not less than 10 or more than 2000 char if provided
            if ((!empty($note_len) && $note_len < 10) || $note_len > 2000) {
                $form_validation              = false;
                $cbacnt_validation['error']   = true;
                $cbacnt_validation['field'][] = 'cbacnt-cat-note';
                $cbacnt_validation['msg']     = esc_html__('The note field character limit must be between 10 to 2000.', 'cbxwpsimpleaccounting');
            }

            //check form passes all validation rules
            if ($form_validation) {
                //edit mode
                if ($cat_id > 0) {
                    //check the category exist with provided id
                    if ($wpdb->get_row(
                            $wpdb->prepare('SELECT title FROM `' . $wpdb->prefix . 'cbaccounting_category` WHERE id = %d', $cat_id), ARRAY_A
                        ) != null
                    ) {

                        $col_data['mod_by'] = get_current_user_id();
                        $col_data['mod_date'] = current_time('mysql');

                        //title, type, color, note, mod_by, mod_date
                        $col_data_format = array('%s', '%d', '%s', '%s', '%d', '%s');

                        $where = array(
                            'id' => $cat_id
                        );

                        $where_format = array('%d');

                        //matching update function return is false, then update failed.
                        if ($wpdb->update($wpdb->prefix . 'cbaccounting_category', $col_data, $where, $col_data_format, $where_format) === false) {
                            //update failed
                            $cbacnt_validation['msg'] = esc_html__('Sorry! you don\'t have enough permission to update category.', 'cbxwpsimpleaccounting');
                        }
                        else {
                            //update successful
                            $msg = __('Category updated.', 'cbxwpsimpleaccounting');
                            $msg .= ' <a  data-catid="' . $cat_id . '"  href="#" class="button cbacnt-edit-cat">';
                            $msg .= esc_html__('Edit', $this->cbxwpsimpleaccounting);
                            $msg .= '</a>';
                            $msg .= ' <a  href="javascript:void(0);" class="button cbacnt-new-cat">';
                            $msg .= esc_html__('Add new', 'cbxwpsimpleaccounting');
                            $msg .= '</a>';

                            $cbacnt_validation['error']                = false;
                            $cbacnt_validation['msg']                  = $msg;
                            $cbacnt_validation['form_value']['id']     = $cat_id;
                            $cbacnt_validation['form_value']['status'] = 'updated';
                            $cbacnt_validation['form_value']['title']  = stripslashes(esc_attr(($col_data['title'])));
                            $cbacnt_validation['form_value']['type']   = $col_data['type'];
                            $cbacnt_validation['form_value']['color']  = $col_data['color'];
                            $cbacnt_validation['form_value']['note']   = stripslashes(esc_textarea(($col_data['note'])));

                        }
                    }
                    else { //if category doesn't exist with id
                        $cbacnt_validation['error'] = true;
                        $cbacnt_validation['msg']   = esc_html__('You attempted to edit the category that doesn\'t exist. ', 'cbxwpsimpleaccounting');
                    }
                }
                else { //if category is new then go here
                    $col_data['add_by']   = get_current_user_id();
                    $col_data['add_date'] = current_time('mysql');

                    //title, type, color, note, add_by
                    $col_data_format = array('%s', '%d', '%s', '%s', '%d');
                    //insert new category
                    if ($wpdb->insert($wpdb->prefix . 'cbaccounting_category', $col_data, $col_data_format)) {
                        //new category inserted successfully

                        $cat_id = $wpdb->insert_id;
                        $msg    = esc_html__('Category created successfully.', 'cbxwpsimpleaccounting');
                        $msg .= ' <a  data-catid="' . $cat_id . '"  href="#" class="button cbacnt-edit-cat">';
                        $msg .= esc_html__('Edit', 'cbxwpsimpleaccounting');
                        $msg .= '</a>';

                        $cbacnt_validation['error']                = false;
                        $cbacnt_validation['msg']                  = $msg;
                        $cbacnt_validation['form_value']['id']     = $cat_id;
                        $cbacnt_validation['form_value']['status'] = 'new';
                        $cbacnt_validation['form_value']['title']  = stripslashes(esc_attr($col_data['title']));
                        $cbacnt_validation['form_value']['type']   = $col_data['type'];
                        $cbacnt_validation['form_value']['color']  = $col_data['color'];
                        $cbacnt_validation['form_value']['note']   = stripslashes(esc_textarea($col_data['note']));
                    }
                    else { //new category insertion failed
                        $cbacnt_validation['error'] = true;
                        $cbacnt_validation['msg']   = esc_html__('Sorry! you don\'t have enough permission to insert new category.', 'cbxwpsimpleaccounting');
                    }
                }
            }
        }
        else { //if wp_nonce not verified then entry here
            $cbacnt_validation['error']   = true;
            $cbacnt_validation['field'][] = 'wp_nonce';
            $cbacnt_validation['msg']     = esc_html__('Hacking attempt ?', 'cbxwpsimpleaccounting');
        }

        echo json_encode($cbacnt_validation);
        wp_die();
    }

    /**
     * load category via ajax request
     *
     */
    public function load_expinc_cat() {
        global $wpdb;
        $form_validation            = true;
        $cbacnt_validation['error'] = false;
        $cbacnt_validation['field'] = array();

        check_ajax_referer('cbxwpsimpleaccounting_nonce', 'nonce');

        $cat_id = absint($_POST['catid']);
        //if provide the category id
        if ($cat_id > 0) {
            //check the category exist with provided id
            $category = $wpdb->get_row($wpdb->prepare('SELECT *  FROM `' . $wpdb->prefix . 'cbaccounting_category` WHERE id = %d', $cat_id), ARRAY_A);
            if ($category != null) {
                $cbacnt_validation['error']                = false;
                $cbacnt_validation['msg']                  = esc_html__('Category loaded for edit', 'cbxwpsimpleaccounting');
                $cbacnt_validation['form_value']['id']     = $cat_id;
                $cbacnt_validation['form_value']['status'] = 'loaded';
                $cbacnt_validation['form_value']['title']  = stripslashes($category['title']);
                $cbacnt_validation['form_value']['type']   = $category['type'];
                $cbacnt_validation['form_value']['color']  = (($category['color']) != NULL) ? $category['color'] : '#333333';
                $cbacnt_validation['form_value']['note']   = stripslashes($category['note']);
            }
            else {
                $cbacnt_validation['error'] = true;
                $cbacnt_validation['msg']   = esc_html__('You attempted to load a category that doesn\'t exist. ', 'cbxwpsimpleaccounting');
            }
        }
        else { //if category is new then go here
            $cbacnt_validation['error'] = true;
            $cbacnt_validation['msg']   = esc_html__('You attempted to edit the category that doesn\'t exist. ','cbxwpsimpleaccounting');
        }

        echo json_encode($cbacnt_validation);
        wp_die();
    }

    /**
     * Add new Account manager
     *
     * return string
     */
    public function add_new_manager_acc() {
        global $wpdb;
        $form_validation            = true;
        $cbacnt_validation['error'] = false;
        $cbacnt_validation['field'] = array();
        $cbxacc_table_name          = $wpdb->prefix . 'cbaccounting_account_manager';

        //verify nonce field
        if (wp_verify_nonce($_POST['new_acc_verifier'], 'add_new_acc')) {

            if ($_POST['cbacnt-acc-type'] == 'bank') {
                $col_data = array(
                    'title'       => sanitize_text_field($_POST['cbacnt-acc-title']),
                    'type'        => sanitize_text_field($_POST['cbacnt-acc-type']),
                    'acc_no'      => sanitize_text_field($_POST['cbacnt-acc-acc-no']),
                    'acc_name'    => sanitize_text_field($_POST['cbacnt-acc-acc-name']),
                    'bank_name'   => sanitize_text_field($_POST['cbacnt-acc-bank-name']),
                    'branch_name' => sanitize_text_field($_POST['cbacnt-acc-branch-name'])
                );
            }

            if ($_POST['cbacnt-acc-type'] == 'cash') {
                $col_data = array(
                    'title' => sanitize_text_field($_POST['cbacnt-acc-title']),
                    'type'  => sanitize_text_field($_POST['cbacnt-acc-type'])
                );
            }

            $account_id    = absint($_POST['cbacnt-acc-id']);
            $title_len     = strlen($col_data['title']);
            $account_title = $col_data['title'];

            //see if account name already saved/exist
            $query                = $wpdb->prepare('SELECT COUNT(*) FROM ' . $cbxacc_table_name . ' WHERE id != %d AND title = %s ', $account_id, $account_title);
            $cbxacc_account_check = $wpdb->get_var($query);

            //check same category title
            if ($cbxacc_account_check != 0) {
                $form_validation              = false;
                $cbacnt_validation['error']   = true;
                $cbacnt_validation['field'][] = 'cbacnt-cat-color';
                $cbacnt_validation['msg']     = esc_html__('The Account title is already in use.', 'cbxwpsimpleaccounting');
            }

            //check category title length is not less than 5 or more than 200 char
            if ($title_len < 5 || $title_len > 200) {
                $form_validation              = false;
                $cbacnt_validation['error']   = true;
                $cbacnt_validation['field'][] = 'cbacnt-acc-title';
                $cbacnt_validation['msg']     = esc_html__('The title field character limit must be between 5 to 200.', 'cbxwpsimpleaccounting');
            }
            //check form passes all validation rules
            if ($form_validation) {
                //edit mode
                if ($account_id > 0) {
                    //check the category exist with provided id
                    if ($wpdb->get_row(
                            $wpdb->prepare('SELECT title FROM `' . $wpdb->prefix . 'cbaccounting_account_manager` WHERE id = %d', $account_id), ARRAY_A
                        ) != null
                    ) {

                        $col_data['mod_by']   = get_current_user_id();
                        $col_data['mod_date'] = current_time('mysql');


                        //title, type
                        $col_data_format    = array('%s', '%s', '%d', '%s');

                        $where = array(
                            'id' => $account_id
                        );

                        $where_format = array('%d');

                        //matching update function return is false, then update failed.
                        if ($wpdb->update($wpdb->prefix . 'cbaccounting_account_manager', $col_data, $where, $col_data_format, $where_format) === false) {
                            //update failed
                            $cbacnt_validation['msg'] = esc_html__('Sorry! you don\'t have enough permission to update account.', 'cbxwpsimpleaccounting');
                        }
                        else {
                            //update successful
                            $msg = esc_html__('Account updated.', 'cbxwpsimpleaccounting');
                            $msg .= ' <a  data-accid="' . $account_id . '"  href="#" class="button cbacnt-edit-cbxacc">';
                            $msg .= esc_html__('Edit', 'cbxwpsimpleaccounting');
                            $msg .= '</a>';
                            $msg .= ' <a  href="javascript:void(0);" class="button cbacnt-new-acc">';
                            $msg .= esc_html__('Add new', 'cbxwpsimpleaccounting');
                            $msg .= '</a>';

                            $cbacnt_validation['error']                = false;
                            $cbacnt_validation['msg']                  = $msg;
                            $cbacnt_validation['form_value']['id']     = $account_id;
                            $cbacnt_validation['form_value']['status'] = 'updated';
                            $cbacnt_validation['form_value']['title']  = stripslashes(esc_attr(($col_data['title'])));
                            $cbacnt_validation['form_value']['type']   = $col_data['type'];
                        }
                    }
                    else { //if category doesn't exist with id
                        $cbacnt_validation['error'] = true;
                        $cbacnt_validation['msg']   = esc_html__('You attempted to edit the account that doesn\'t exist. ', 'cbxwpsimpleaccounting');
                    }
                }
                else { //if category is new then go here
                    $col_data['add_by']   = get_current_user_id();
                    $col_data['add_date'] = current_time('mysql');

                    if ($col_data['type'] == 'bank') {
                        //title, type, acc_no,acc_name,bank_name,branch_name
                        $col_data_format = array('%s', '%s', '%s', '%s', '%s', '%s');
                    }

                    if ($col_data['type'] == 'cash') {
                        //title, type
                        $col_data_format = array('%s', '%s');
                    }

                    //insert new account
                    if ($wpdb->insert($wpdb->prefix . 'cbaccounting_account_manager', $col_data, $col_data_format)) {

                        //new account inserted successfully

                        $acc_id = $wpdb->insert_id;

                        $msg = esc_html__('Account created successfully.', 'cbxwpsimpleaccounting');
                        $msg .= ' <a  data-accid="' . $acc_id . '"  href="#" class="button cbacnt-edit-cbxacc">';
                        $msg .= esc_html__('Edit', 'cbxwpsimpleaccounting');
                        $msg .= '</a>';


                        $cbacnt_validation['error']                = false;
                        $cbacnt_validation['msg']                  = $msg;
                        $cbacnt_validation['form_value']['id']     = $acc_id;
                        $cbacnt_validation['form_value']['status'] = 'new';
                        $cbacnt_validation['form_value']['title']  = stripslashes(esc_attr($col_data['title']));
                        $cbacnt_validation['form_value']['type']   = $col_data['type'];
                    }
                    else { //new category insertion failed
                        $cbacnt_validation['error'] = true;
                        $cbacnt_validation['msg']   = esc_html__('Sorry! you don\'t have enough permission to insert new account.', 'cbxwpsimpleaccounting');
                    }
                }
            }
        }
        else { //if wp_nonce not verified then entry here
            $cbacnt_validation['error']   = true;
            $cbacnt_validation['field'][] = 'wp_nonce';
            $cbacnt_validation['msg']     = esc_html__('Hacking attempt ?', 'cbxwpsimpleaccounting');
        }

        echo json_encode($cbacnt_validation);
        wp_die();
    }

    /**
     * load account via ajax request
     *
     */
    public function load_account() {
        global $wpdb;
        $form_validation            = true;
        $cbacnt_validation['error'] = false;
        $cbacnt_validation['field'] = array();

        //check_ajax_referer('cbxwpsimpleaccounting_nonce', 'nonce');

        $acc_id = absint($_POST['accid']);

        //if provide the category id
        if ($acc_id > 0) {
            //check the category exist with provided id
            $account = $wpdb->get_row($wpdb->prepare('SELECT *  FROM `' . $wpdb->prefix . 'cbaccounting_account_manager` WHERE id = %d', $acc_id), ARRAY_A);
            if ($account != null) {

                $cbacnt_validation['error'] = false;
                $cbacnt_validation['msg']   = 'Account loaded for edit';

                $cbacnt_validation['form_value']['id']     = $acc_id;
                $cbacnt_validation['form_value']['status'] = 'loaded';
                $cbacnt_validation['form_value']['title']  = stripslashes($account['title']);
                $cbacnt_validation['form_value']['type']   = $account['type'];

                if ($account['type'] == 'bank') {
                    $cbacnt_validation['form_value']['acc_no']      = stripslashes($account['acc_no']);
                    $cbacnt_validation['form_value']['acc_name']    = stripslashes($account['acc_name']);
                    $cbacnt_validation['form_value']['bank_name']   = stripslashes($account['bank_name']);
                    $cbacnt_validation['form_value']['branch_name'] = stripslashes($account['branch_name']);
                }
            }
            else {
                $cbacnt_validation['error'] = true;
                $cbacnt_validation['msg']   = esc_html__('You attempted to load an account that doesn\'t exist. ', 'cbxwpsimpleaccounting');
            }
        }
        else { //if category is new then go here
            $cbacnt_validation['error'] = true;
            $cbacnt_validation['msg']   = esc_html__('You attempted to edit the account that doesn\'t exist. ', 'cbxwpsimpleaccounting');
        }

        echo json_encode($cbacnt_validation);
        wp_die();
    }

    /**
     * Get month data for chart
     * @param type $year
     */
    public function get_month_data($month, $year) {

        global $wpdb;

        $total_this_month_income    = $total_this_month_expense = $total_this_month_tax     = $total_one_month_income   = $total_one_month_expense  = $one_month_tax            = 0;
        $daywise_income1            = array();
        $daywise_expense1           = array();

        $month_days_array = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        $this_month_income = $wpdb->get_results('SELECT c . * , cat.id as catid , cat.title as cattitle, cat.color as catcolor
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 1 AND MONTH(c.add_date) = ' . $month . ' AND YEAR(c.add_date) = ' . $year);


        foreach ($this_month_income as $key => $value) {
            $timestamp = strtotime($value->add_date);

            if (array_key_exists(intval(date("d", $timestamp)), $daywise_income1)) {

                $daywise_income1[intval(date("d", $timestamp))] += floatval($value->amount);

                if ($value->istaxincluded) {
                    $tax = $value->amount * ($value->tax / 100);
                    $daywise_income1[intval(date("d", $timestamp))] += $tax;
                }
            }
            else {

                $daywise_income1[intval(date("d", $timestamp))] = floatval($value->amount);

                if ($value->istaxincluded) {
                    $tax = $value->amount * ($value->tax / 100);
                    $daywise_income1[intval(date("d", $timestamp))] += $tax;
                }
            }
        }

        for ($i = 1; $i <= $month_days_array[intval(date('m')) - 1]; $i++) {

            if (array_key_exists($i, $daywise_income1)) {
                $daywise_income2[$i] = $daywise_income1[$i];
            }
            else {
                $daywise_income2[$i] = 0;
            }
        }



        $this_month_expense = $wpdb->get_results('SELECT c . * , cat.id as catid, cat.title as cattitle, cat.color as catcolor
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 2 AND MONTH(c.add_date) = ' . $month . ' AND YEAR(c.add_date) = ' . $year);


        foreach ($this_month_expense as $key => $value) {
            $timestamp = strtotime($value->add_date);

            if (array_key_exists(intval(date("d", $timestamp)), $daywise_expense1)) {

                $daywise_expense1[intval(date("d", $timestamp))] += floatval($value->amount);

                if ($value->istaxincluded) {
                    $tax = $value->amount * ($value->tax / 100);
                    $daywise_expense1[intval(date("d", $timestamp))] += $tax;
                }
            }
            else {

                $daywise_expense1[intval(date("d", $timestamp))] = floatval($value->amount);

                if ($value->istaxincluded) {
                    $tax = $value->amount * ($value->tax / 100);
                    $daywise_expense1[intval(date("d", $timestamp))] += $tax;
                }
            }
        }

        for ($i = 1; $i <= $month_days_array[intval(date('m')) - 1]; $i++) {

            if (array_key_exists($i, $daywise_expense1)) {
                $daywise_expense2[$i] = $daywise_expense1[$i];
            }
            else {
                $daywise_expense2[$i] = 0;
            }
        }

        return array('daywise_income2' => $daywise_income2, 'daywise_expense2' => $daywise_expense2);
    }

    /**
     * Get any year total income or expense quickly
     *
     *
     * @param int $year
     *
     * return array
     */
    public function get_year_data_total_quick($year) {

        global $wpdb;

        $total_this_month_income  = $total_this_month_expense = $total_this_month_tax     = $total_one_month_income   = $total_one_month_expense  = $one_month_tax            = 0;


        for ($i = 1; $i <= 12; $i++) {

            //income
            $one_month_incomes = $wpdb->get_results('SELECT c . * , cat.title as cattitle
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 1 AND MONTH(c.add_date) = ' . $i . ' AND YEAR(c.add_date) = ' . $year);




            foreach ($one_month_incomes as $one_month_income) {

                $total_one_month_income += $one_month_income->amount;

                if ($one_month_income->istaxincluded) {
                    $one_month_tax = $one_month_income->amount * ($one_month_income->tax / 100);
                    $total_one_month_income += $one_month_tax;
                }
            }

            $year_income_by_month[$i] = $total_one_month_income;


            //expense
            $one_month_expenses = $wpdb->get_results('SELECT c . * , cat.title as cattitle
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 2 AND MONTH(c.add_date) = ' . $i . ' AND YEAR(c.add_date) = ' . $year);

            foreach ($one_month_expenses as $one_month_expense) {

                $total_one_month_expense += $one_month_expense->amount;

                if ($one_month_expense->istaxincluded) {
                    $one_month_tax = $one_month_expense->amount * ($one_month_expense->tax / 100);
                    $total_one_month_expense += $one_month_tax;
                }
            }
            $year_expense_by_month[$i] = $total_one_month_expense;

            $total_one_month_income  = 0;
            $total_one_month_expense = 0;
        }

        return array('year_income_by_month' => $year_income_by_month, 'year_expense_by_month' => $year_expense_by_month);
    }

    /**
     * Get Year Data for chart
     * @param type $year
     */
    public function get_year_data($year) {

        global $wpdb;

        $total_this_month_income  = $total_this_month_expense = $total_this_month_tax     = $total_one_month_income   = $total_one_month_expense  = $one_month_tax            = 0;


        for ($i = 1; $i <= 12; $i++) {

            //income
            $one_month_incomes = $wpdb->get_results('SELECT c . * , cat.title as cattitle
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 1 AND MONTH(c.add_date) = ' . $i . ' AND YEAR(c.add_date) = ' . $year);






            foreach ($one_month_incomes as $one_month_income) {

                $total_one_month_income += $one_month_income->amount;

                if ($one_month_income->istaxincluded) {
                    $one_month_tax = $one_month_income->amount * ($one_month_income->tax / 100);
                    $total_one_month_income += $one_month_tax;
                }
            }

            $year_income_by_month[$i] = $total_one_month_income;


            //expense
            $one_month_expenses = $wpdb->get_results('SELECT c . * , cat.title as cattitle
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 2 AND MONTH(c.add_date) = ' . $i . ' AND YEAR(c.add_date) = ' . $year);

            foreach ($one_month_expenses as $one_month_expense) {

                $total_one_month_expense += $one_month_expense->amount;

                if ($one_month_expense->istaxincluded) {
                    $one_month_tax = $one_month_expense->amount * ($one_month_expense->tax / 100);
                    $total_one_month_expense += $one_month_tax;
                }
            }
            $year_expense_by_month[$i] = $total_one_month_expense;

            $total_one_month_income  = 0;
            $total_one_month_expense = 0;
        }

        return array('year_income_by_month' => $year_income_by_month, 'year_expense_by_month' => $year_expense_by_month);
    }

    /**
     * Load year traversal data
     */
    public function load_nextprev_year() {

        check_ajax_referer('cbxwpsimpleaccounting_nonce', 'security');

        $year = intval($_POST['year']);

        echo json_encode($this->get_year_data($year));


        wp_die();
    }

    /**
     * Load month traversal data
     */
    public function load_nextprev_month() {


        check_ajax_referer('cbxwpsimpleaccounting_nonce', 'security');

        $year  = intval($_POST['year']);
        $month = intval($_POST['month']);

        echo json_encode($this->get_month_data($month, $year));

        wp_die();
    }

    /**
     * Show Overview page
     *
     */
    public function display_plugin_admin_overview() {
        global $wpdb;
        $latest_income_by_cat  = $latest_expense_by_cat = $daywise_income1       = $daywise_income2       = $daywise_expense1      = $daywise_expense2      = array();

        if (intval(date('Y')) % 4 == 0) {
            $month_days_array = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        }
        else {
            $month_days_array = array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        }

        $total_income             = $total_cost               = $latest_tax               = $latest_tax_expense       = $total_this_month_income  = $total_this_month_expense = $total_this_month_tax     = $total_one_month_income   = $total_one_month_expense  = $one_month_tax            = 0;

        $latest_income = $wpdb->get_results('SELECT c . * , cat.id as catid , cat.title as cattitle, cat.color as catcolor, act.acc_name as accountname 
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id 
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id 
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_account_manager` act ON act.id = c.account 
WHERE c.type = 1 AND MONTH(c.add_date) = MONTH(CURDATE()) AND YEAR(c.add_date) = YEAR(CURDATE()) order by add_date desc LIMIT 20');


        $latest_expense = $wpdb->get_results('SELECT c . * , cat.id as catid , cat.title as cattitle, cat.color as catcolor, act.acc_name as accountname 
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id 
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id 
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_account_manager` act ON act.id = c.account
WHERE c.type = 2 AND MONTH(c.add_date) = MONTH(CURDATE()) AND YEAR(c.add_date) = YEAR(CURDATE()) order by add_date desc LIMIT 20');





        $this_month_income = $wpdb->get_results('SELECT c . * , cat.id as catid , cat.title as cattitle, cat.color as catcolor
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 1 AND MONTH(c.add_date) = MONTH(CURDATE()) AND YEAR(c.add_date) = YEAR(CURDATE())');


        foreach ($this_month_income as $key => $value) {
            $timestamp = strtotime($value->add_date);

            if (array_key_exists(intval(date("d", $timestamp)), $daywise_income1)) {

                $daywise_income1[intval(date("d", $timestamp))] += floatval($value->amount);

                if ($value->istaxincluded) {
                    $tax = $value->amount * ($value->tax / 100);
                    $daywise_income1[intval(date("d", $timestamp))] += $tax;
                }
            }
            else {

                $daywise_income1[intval(date("d", $timestamp))] = floatval($value->amount);

                if ($value->istaxincluded) {
                    $tax = $value->amount * ($value->tax / 100);
                    $daywise_income1[intval(date("d", $timestamp))] += $tax;
                }
            }
        }

        for ($i = 1; $i <= $month_days_array[intval(date('m')) - 1]; $i++) {

            if (array_key_exists($i, $daywise_income1)) {
                $daywise_income2[$i] = $daywise_income1[$i];
            }
            else {
                $daywise_income2[$i] = 0;
            }
        }



        $this_month_expense = $wpdb->get_results('SELECT c . * , cat.id as catid, cat.title as cattitle, cat.color as catcolor
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id 
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id 
WHERE c.type = 2 AND MONTH(c.add_date) = MONTH(CURDATE()) AND YEAR(c.add_date) = YEAR(CURDATE())');


        foreach ($this_month_expense as $key => $value) {
            $timestamp = strtotime($value->add_date);

            if (array_key_exists(intval(date("d", $timestamp)), $daywise_expense1)) {

                $daywise_expense1[intval(date("d", $timestamp))] += floatval($value->amount);

                if ($value->istaxincluded) {
                    $tax = $value->amount * ($value->tax / 100);
                    $daywise_expense1[intval(date("d", $timestamp))] += $tax;
                }
            }
            else {

                $daywise_expense1[intval(date("d", $timestamp))] = floatval($value->amount);

                if ($value->istaxincluded) {
                    $tax = $value->amount * ($value->tax / 100);
                    $daywise_expense1[intval(date("d", $timestamp))] += $tax;
                }
            }
        }

        for ($i = 1; $i <= $month_days_array[intval(date('m')) - 1]; $i++) {

            if (array_key_exists($i, $daywise_expense1)) {
                $daywise_expense2[$i] = $daywise_expense1[$i];
            }
            else {
                $daywise_expense2[$i] = 0;
            }
        }


		$currency 						= $this->settings_api->get_option( 'cbxwpsimpleaccounting_currency', 'cbxwpsimpleaccounting_basics', 'USD' );
		$currency_position 				= $this->settings_api->get_option( 'cbxwpsimpleaccounting_currency_pos', 'cbxwpsimpleaccounting_basics', 'left' );
		$currency_symbol 				= $this->get_cbxwpsimpleaccounting_currency_symbol( $currency );
		$currency_thousand_separator 	= $this->settings_api->get_option( 'cbxwpsimpleaccounting_thousand_sep', 'cbxwpsimpleaccounting_basics', ',' );
		$currency_decimal_separator 	= $this->settings_api->get_option( 'cbxwpsimpleaccounting_decimal_sep', 'cbxwpsimpleaccounting_basics', '.' );
		$currency_number_decimal 		= $this->settings_api->get_option( 'cbxwpsimpleaccounting_num_decimals', 'cbxwpsimpleaccounting_basics', '2' );


        for ($i = 1; $i <= 12; $i++) {

            $one_month_incomes = $wpdb->get_results('SELECT c . * , cat.title as cattitle
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id 
WHERE c.type = 1 AND MONTH(c.add_date) = ' . $i . ' AND YEAR(c.add_date) = YEAR(CURDATE())');


            foreach ($one_month_incomes as $one_month_income) {

                $total_one_month_income += $one_month_income->amount;

                if ($one_month_income->istaxincluded) {
                    $one_month_tax = $one_month_income->amount * ($one_month_income->tax / 100);
                    $total_one_month_income += $one_month_tax;
                }
            }

            $year_income_by_month[$i] = $total_one_month_income;


            $one_month_expenses = $wpdb->get_results('SELECT c . * , cat.title as cattitle
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_expcat_rel` catrel ON c.id = catrel.expinc_id
LEFT JOIN  `' . $wpdb->prefix . 'cbaccounting_category` cat ON catrel.category_id = cat.id
WHERE c.type = 2 AND MONTH(c.add_date) = ' . $i . ' AND YEAR(c.add_date) = YEAR(CURDATE())');

            foreach ($one_month_expenses as $one_month_expense) {

                $total_one_month_expense += $one_month_expense->amount;

                if ($one_month_expense->istaxincluded) {
                    $one_month_tax = $one_month_expense->amount * ($one_month_expense->tax / 100);
                    $total_one_month_expense += $one_month_tax;
                }
            }
            $year_expense_by_month[$i] = $total_one_month_expense;

            $total_one_month_income  = 0;
            $total_one_month_expense = 0;
        }





        foreach ($this_month_income as $this_month_inc) {

            $total_this_month_income += $this_month_inc->amount;

            if ($this_month_inc->istaxincluded) {
                $total_this_month_tax = $this_month_inc->amount * ($this_month_inc->tax / 100);
                $total_this_month_income += $total_this_month_tax;
            }
            else{
				$total_this_month_tax = 0;
			}

            if (array_key_exists($this_month_inc->catid, $latest_income_by_cat)) {
                $latest_income_by_cat[$this_month_inc->catid]['value'] += $this_month_inc->amount + $total_this_month_tax;
                $latest_income_by_cat[$this_month_inc->catid]['color']         = $this_month_inc->catcolor;
                $latest_income_by_cat[$this_month_inc->catid]['label']         = $this_month_inc->cattitle;
                $latest_income_by_cat[$this_month_inc->catid]['labelColor']    = 'white';
                $latest_income_by_cat[$this_month_inc->catid]['labelFontSize'] = '16';
            }
            else {
                $latest_income_by_cat[$this_month_inc->catid]['value']         = $this_month_inc->amount + $total_this_month_tax;
                $latest_income_by_cat[$this_month_inc->catid]['color']         = $this_month_inc->catcolor;
                $latest_income_by_cat[$this_month_inc->catid]['label']         = $this_month_inc->cattitle;
                $latest_income_by_cat[$this_month_inc->catid]['labelColor']    = 'white';
                $latest_income_by_cat[$this_month_inc->catid]['labelFontSize'] = '16';
            }
        }


        foreach ($this_month_expense as $this_month_exp) {

            $total_this_month_expense = $total_this_month_expense + $this_month_exp->amount;

            if ($this_month_exp->istaxincluded) {
                $total_this_month_tax_expense = $this_month_exp->amount * ($this_month_exp->tax / 100);
                $total_this_month_expense += $total_this_month_tax_expense;
            }
            else{
				$total_this_month_tax_expense = 0;
			}

            if (array_key_exists($this_month_exp->catid, $latest_expense_by_cat)) {
                $latest_expense_by_cat[$this_month_exp->catid]['value'] += $this_month_exp->amount + $total_this_month_tax_expense;
                $latest_expense_by_cat[$this_month_exp->catid]['color']         = $this_month_exp->catcolor;
                $latest_expense_by_cat[$this_month_exp->catid]['label']         = $this_month_exp->cattitle;
                $latest_expense_by_cat[$this_month_exp->catid]['labelColor']    = 'white';
                $latest_expense_by_cat[$this_month_exp->catid]['labelFontSize'] = '16';
            }
            else {
                $latest_expense_by_cat[$this_month_exp->catid]['value']         = $this_month_exp->amount + $total_this_month_tax_expense;
                $latest_expense_by_cat[$this_month_exp->catid]['color']         = $this_month_exp->catcolor;
                $latest_expense_by_cat[$this_month_exp->catid]['label']         = $this_month_exp->cattitle;
                $latest_expense_by_cat[$this_month_exp->catid]['labelColor']    = 'white';
                $latest_expense_by_cat[$this_month_exp->catid]['labelFontSize'] = '16';
            }
        }



        //this year total income

        $get_total_formated_income             = $this->format_value($total_income, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator, $currency_position, $currency_symbol);
        $get_total_formated_expanse            = $this->format_value($total_cost, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator, $currency_position, $currency_symbol);
        $get_total_formated_this_month_income  = $this->format_value($total_this_month_income, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator, $currency_position, $currency_symbol);
        $get_total_formated_this_month_expanse = $this->format_value($total_this_month_expense, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator, $currency_position, $currency_symbol);


        $total_quick 		= $this->get_data_total_quick(true); //get total income/expense as formatted
        $total_year_quick 	= $this->get_data_year_total_quick(0, true); //get total income/expense as formatted for current year
        $total_month_quick 	= $this->get_data_month_total_quick(0, 0, true); //get total income/expense as formatted for current month




        $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

        include('partials/admin-overview-display.php');
    }




    public function format_value($total_number, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator, $currency_position, $currency_symbol) {

        $negative = $total_number < 0;

        $total_number = floatval( $negative ? $total_number * - 1 : $total_number);

        $formated_value = number_format($total_number, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator);

        switch ($currency_position) {
            case "left":
                $final_formatted_value = $currency_symbol . $formated_value;
                break;
            case "right":
                $final_formatted_value = $formated_value . $currency_symbol;
                break;
            case "left_space":
                $final_formatted_value = $currency_symbol . ' ' . $formated_value;
                break;
            case "right_space":
                $final_formatted_value = $formated_value . ' ' . $currency_symbol;
                break;
            default:
                $final_formatted_value = $currency_symbol . $formated_value;
        }

        $final_formatted_value = ( $negative ? '-' : '' ) . $final_formatted_value;

        return $final_formatted_value;
    }

    /**
     * Show a value as formatted
     *
     * @param $total_number
     *
     * @return string
     */
    public function format_value_quick($total_number) {

        $negative 						= $total_number < 0;
        $total_number 					= floatval( $negative ? $total_number * - 1 : $total_number);
        $currency 						= $this->settings_api->get_option('cbxwpsimpleaccounting_currency', 'cbxwpsimpleaccounting_basics', 'USD');
        $currency_position 				= $this->settings_api->get_option('cbxwpsimpleaccounting_currency_pos', 'cbxwpsimpleaccounting_basics', 'left');
        $currency_symbol 				= $this->get_cbxwpsimpleaccounting_currency_symbol($currency);
        $currency_thousand_separator 	= $this->settings_api->get_option('cbxwpsimpleaccounting_thousand_sep', 'cbxwpsimpleaccounting_basics', ',');
        $currency_decimal_separator 	= $this->settings_api->get_option('cbxwpsimpleaccounting_decimal_sep', 'cbxwpsimpleaccounting_basics', '.');
        $currency_number_decimal 		= $this->settings_api->get_option('cbxwpsimpleaccounting_num_decimals', 'cbxwpsimpleaccounting_basics', '2');
        $formated_value 				= number_format($total_number, $currency_number_decimal, $currency_decimal_separator, $currency_thousand_separator);

        switch ($currency_position) {
            case "left":
                $final_formatted_value = $currency_symbol . $formated_value;
                break;
            case "right":
                $final_formatted_value = $formated_value . $currency_symbol;
                break;
            case "left_space":
                $final_formatted_value = $currency_symbol . ' ' . $formated_value;
                break;
            case "right_space":
                $final_formatted_value = $formated_value . ' ' . $currency_symbol;
                break;
            default:
                $final_formatted_value = $currency_symbol . $formated_value;
        }

        $final_formatted_value = ( $negative ? '-' : '' ) . $final_formatted_value;
        return $final_formatted_value;
    }

    public function display_plugin_admin_manage_account() {
        global $wpdb;

        $acc_results_list = $wpdb->get_results('SELECT `id`, `title`, `type` FROM `' . $wpdb->prefix . 'cbaccounting_account_manager`', ARRAY_A);
        $acc              = array();


        if ($acc_results_list == null) {
            $acc_results_list = array();
        }

        foreach ($acc_results_list as $account) {
            $acc[$account['id']] = $account;
        }

        $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

        include('partials/admin-account-manager-display.php');
    }

    public function display_plugin_admin_managecategory() {
        global $wpdb;

        $cat_results_list = $wpdb->get_results('SELECT `id`, `title`, `type`, `color` FROM `' . $wpdb->prefix . 'cbaccounting_category`', ARRAY_A);
        $cats             = array();

        if ($cat_results_list == null) {
            $cat_results_list = array();
        }

        foreach ($cat_results_list as $category) {
            $cats[$category['id']] = $category;
        }

        $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

        include('partials/admin-managecategory-display.php');
    }

    public function display_plugin_admin_adddexpinc() {
        global $wpdb;
        $single_incomeexpense = array();

        $cat_results_list = $wpdb->get_results('SELECT `id`, `title`, `type`, `color` FROM `' . $wpdb->prefix . 'cbaccounting_category`', ARRAY_A);
       	//$all_exp_inc_list = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'cbaccounting_expinc`');
        $all_acc_list     = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'cbaccounting_account_manager`');

        if ($cat_results_list == null) {
            $cat_results_list = array();
        }

        if (isset($_GET['id']) && absint($_GET['id']) > 0) {

            $id            = absint($_GET['id']);
            $incexp        = $wpdb->get_row($wpdb->prepare('SELECT *  FROM `' . $wpdb->prefix . 'cbaccounting_expinc` WHERE id = %d', $id), ARRAY_A);
            $incexpcatlist = $wpdb->get_results($wpdb->prepare('SELECT *  FROM `' . $wpdb->prefix . 'cbaccounting_expcat_rel` WHERE expinc_id = %d', $id), ARRAY_A);

            if ($incexpcatlist != null) {
                foreach ($incexpcatlist as $list) {
                    $catlist[] = $list['category_id'];
                }
            }

            if ($incexp != null) {

                $single_incomeexpense['error'] = false;
                $single_incomeexpense['msg']   = esc_html__('Data Loaded for edit', 'cbxwpsimpleaccounting');

                $single_incomeexpense['id']              = $id;
                $single_incomeexpense['status']          = 'loaded';
                $single_incomeexpense['title']           = stripslashes(esc_attr($incexp['title']));
                $single_incomeexpense['amount']          = $incexp['amount'];
                $single_incomeexpense['source_amount']   = $incexp['source_amount'];
                $single_incomeexpense['source_currency'] = $incexp['source_currency'];
                $single_incomeexpense['account']         = $incexp['account'];
                $single_incomeexpense['invoice']         = stripslashes(esc_attr($incexp['invoice']));
                $single_incomeexpense['istaxincluded']   = $incexp['istaxincluded'];
                $single_incomeexpense['tax']             = $incexp['tax'];
                $single_incomeexpense['cat_list']        = $catlist;
                $single_incomeexpense['type']            = $incexp['type'];
                $single_incomeexpense['note']            = stripslashes(esc_textarea($incexp['note']));
                $single_incomeexpense['add_date']        = $incexp['add_date'];

                $single_incomeexpense					 = apply_filters('cbxwpsimpleaccounting_incexp_single_data', $single_incomeexpense, $incexp, $id);
            }
            else {
                $single_incomeexpense['error'] = true;
                $single_incomeexpense['msg']   = esc_html__('You attempted to edit item that doesn\'t exist. ', 'cbxwpsimpleaccounting');
            }
        }

        $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

        include('partials/admin-adddexpinc-display.php');
    }

    public function display_plugin_admin_settings() {
        global $wpdb;

        $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

        include('partials/admin-settings-display.php');
    }

    public function display_plugin_admin_addons() {
        global $wpdb;

        $cbxaccount_admin_url = plugin_dir_url(__FILE__);

        $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);

        include('partials/admin-addons-display.php');
    }

    /**
     * Check the status of a plugin. (https://katz.co/simple-plugin-status-wordpress/)
     *
     * @param string $location Base plugin path from plugins directory.
     * @return int 1 if active; 2 if inactive; 0 if not installed
     */
    function get_plugin_status($location = '') {

        if (is_plugin_active($location)) {
            return array(
                'status'   => 1,
                'msg'      => esc_html__('Active and Installed', 'cbxwpsimpleaccounting'),
                'btnclass' => 'button button-primary'
            );
        }

        if (!file_exists(trailingslashit(WP_PLUGIN_DIR) . $location)) {
            return array(
                'status'   => 0,
                'msg'      => esc_html__('Not Installed or Active', 'cbxwpsimpleaccounting'),
                'btnclass' => 'button'
            );
        }

        if (is_plugin_inactive($location)) {
            return array(
                'status'   => 2,
                'msg'      => esc_html__('Installed but Inactive', 'cbxwpsimpleaccounting'),
                'btnclass' => 'button'
            );
        }
    }

    public function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'cbxwpsimpleaccounting_basics',
                'title' => esc_html__('Basic Settings', 'cbxwpsimpleaccounting')
            ),
            array(
                'id'    => 'cbxwpsimpleaccounting_category',
                'title' => esc_html__('Category Settings', 'cbxwpsimpleaccounting')
            ),
            array(
                'id'    => 'cbxwpsimpleaccounting_tax',
                'title' => esc_html__('Tax Settings', 'cbxwpsimpleaccounting')
            ),
            array(
                'id'    => 'cbxwpsimpleaccounting_graph',
                'title' => esc_html__('Graph Settings', 'cbxwpsimpleaccounting')
            )
        );

        $sections = apply_filters('cbxaccountingsettingsections', $sections);

        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    public function get_settings_fields() {
        $currency_code_options = $this->get_cbxwpsimpleaccounting_currencies();

        foreach ($currency_code_options as $code => $name) {
            $currency_code_options[$code] = $name . ' (' . $this->get_cbxwpsimpleaccounting_currency_symbol($code) . ')';
        }

        $settings_fields = array(
            'cbxwpsimpleaccounting_basics'   => array(
                array(
                    'name'     => 'cbxwpsimpleaccounting_currency',
                    'label'    => esc_html__('Default Currency', 'cbxwpsimpleaccounting'),
                    'desc'     => esc_html__('This controls what currency is used for calculation.', 'cbxwpsimpleaccounting'),
                    'type'     => 'select',
                    'default'  => 'no',
                    'desc_tip' => true,
                    'options'  => $currency_code_options,
                    'default'  => 'USD',
                ),
                array(
                    'name'     => 'cbxwpsimpleaccounting_currency_pos',
                    'label'    => esc_html__('Currency Position', 'cbxwpsimpleaccounting'),
                    'desc'     => esc_html__('This controls the position of the currency symbol.', 'cbxwpsimpleaccounting'),
                    'type'     => 'select',
                    'default'  => 'left',
                    'options'  => array(
                        'left'        => esc_html__('Left', 'cbxwpsimpleaccounting') . ' (' . $this->get_cbxwpsimpleaccounting_currency_symbol($this->settings_api->get_option('cbxwpsimpleaccounting_currency', 'cbxwpsimpleaccounting_basics', 'USD')) . '99.99)',
                        'right'       => esc_html__('Right', 'cbxwpsimpleaccounting') . ' (99.99' . $this->get_cbxwpsimpleaccounting_currency_symbol($this->settings_api->get_option('cbxwpsimpleaccounting_currency', 'cbxwpsimpleaccounting_basics', 'USD')) . ')',
                        'left_space'  => esc_html__('Left with space', 'cbxwpsimpleaccounting') . ' (' . $this->get_cbxwpsimpleaccounting_currency_symbol($this->settings_api->get_option('cbxwpsimpleaccounting_currency', 'cbxwpsimpleaccounting_basics', 'USD')) . ' 99.99)',
                        'right_space' => esc_html__('Right with space', 'cbxwpsimpleaccounting') . ' (99.99 ' . $this->get_cbxwpsimpleaccounting_currency_symbol($this->settings_api->get_option('cbxwpsimpleaccounting_currency', 'cbxwpsimpleaccounting_basics', 'USD')) . ')'
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name'     => 'cbxwpsimpleaccounting_thousand_sep',
                    'label'    => esc_html__('Thousand Separator', 'cbxwpsimpleaccounting'),
                    'desc'     => esc_html__('This sets the thousand separator of displayed prices.', 'cbxwpsimpleaccounting'),
                    'type'     => 'text',
                    'default'  => ',',
                    'desc_tip' => true,
                ),
                array(
                    'name'     => 'cbxwpsimpleaccounting_decimal_sep',
                    'label'    => esc_html__('Decimal Separator', 'cbxwpsimpleaccounting'),
                    'desc'     => esc_html__('This sets the decimal separator of displayed prices.', 'cbxwpsimpleaccounting'),
                    'type'     => 'text',
                    'default'  => '.',
                    'desc_tip' => true,
                ),
                array(
                    'name'     => 'cbxwpsimpleaccounting_num_decimals',
                    'label'    => esc_html__('Number of Decimals', 'cbxwpsimpleaccounting'),
                    'desc'     => esc_html__('This sets the number of decimal points shown in displayed prices.', 'cbxwpsimpleaccounting'),
                    'type'     => 'number',
                    'default'  => '2',
                    'desc_tip' => true,
                )
            ),
            'cbxwpsimpleaccounting_category' => array(
                array(
                    'name'    => 'cbxacc_category_color',
                    'label'   => esc_html__('Category Color', 'cbxwpsimpleaccounting'),
                    'desc'    => esc_html__('If yes each category must have a unique color', 'cbxwpsimpleaccounting'),
                    'type'    => 'checkbox',
                    'default' => 'on'
                )
            ),
            'cbxwpsimpleaccounting_tax'      => array(
                array(
                    'name'     => 'cbxwpsimpleaccounting_sales_tax',
                    'label'    => esc_html__('Sales Tax (VAT)', 'cbxwpsimpleaccounting'),
                    'desc'     => esc_html__('Default Tax(Vat) %', 'cbxwpsimpleaccounting'),
                    'type'     => 'number',
                    'default'  => '0',
                    'desc_tip' => true,
					'step'	   => '.01'
                )
            ),
            'cbxwpsimpleaccounting_graph'    => array(
                array(
                    'name'    => 'legend_color_for_income',
                    'label'   => esc_html__('Legend Color for Income', 'cbxwpsimpleaccounting'),
                    'desc'    => esc_html__('Legend Color for Income', 'cbxwpsimpleaccounting'),
                    'type'    => 'color',
                    'default' => '#5cc488' //greenish
                ),
                array(
                    'name'    => 'legend_color_for_expense',
                    'label'   => esc_html__('Legend Color for Expense', 'cbxwpsimpleaccounting'),
                    'desc'    => esc_html__('Legend Color for Expense', 'cbxwpsimpleaccounting'),
                    'type'    => 'color',
                    'default' => '#e74c3c' //redish
                )
            )
        );

        $settings_fields = apply_filters('cbxaccountingsettingfields', $settings_fields);

        return $settings_fields;
    }

    /**
     * Get Base Currency Code.
     *
     * @return string
     */
    public function get_cbxwpsimpleaccounting_currency() {
        return apply_filters('cbxwpsimpleaccounting_currency', get_option('cbxwpsimpleaccounting_currency'));
    }

    /**
     * Get full list of currency codes.
     *
     * @return array
     */
    public function get_cbxwpsimpleaccounting_currencies() {
        return array_unique(
            apply_filters('cbxwpsimpleaccounting_currencies', array(
            'AED' => esc_html__('United Arab Emirates Dirham', 'cbxwpsimpleaccounting'),
            'ARS' => esc_html__('Argentine Peso', 'cbxwpsimpleaccounting'),
            'AUD' => esc_html__('Australian Dollars', 'cbxwpsimpleaccounting'),
            'BDT' => esc_html__('Bangladeshi Taka', 'cbxwpsimpleaccounting'),
            'BRL' => esc_html__('Brazilian Real', 'cbxwpsimpleaccounting'),
            'BGN' => esc_html__('Bulgarian Lev', 'cbxwpsimpleaccounting'),
            'CAD' => esc_html__('Canadian Dollars', 'cbxwpsimpleaccounting'),
            'CLP' => esc_html__('Chilean Peso', 'cbxwpsimpleaccounting'),
            'CNY' => esc_html__('Chinese Yuan', 'cbxwpsimpleaccounting'),
            'COP' => esc_html__('Colombian Peso', 'cbxwpsimpleaccounting'),
            'CZK' => esc_html__('Czech Koruna', 'cbxwpsimpleaccounting'),
            'DKK' => esc_html__('Danish Krone', 'cbxwpsimpleaccounting'),
            'DOP' => esc_html__('Dominican Peso', 'cbxwpsimpleaccounting'),
            'EUR' => esc_html__('Euros', 'cbxwpsimpleaccounting'),
            'HKD' => esc_html__('Hong Kong Dollar', 'cbxwpsimpleaccounting'),
            'HRK' => esc_html__('Croatia kuna', 'cbxwpsimpleaccounting'),
            'HUF' => esc_html__('Hungarian Forint', 'cbxwpsimpleaccounting'),
            'ISK' => esc_html__('Icelandic krona', 'cbxwpsimpleaccounting'),
            'IDR' => esc_html__('Indonesia Rupiah', 'cbxwpsimpleaccounting'),
            'INR' => esc_html__('Indian Rupee', 'cbxwpsimpleaccounting'),
            'NPR' => esc_html__('Nepali Rupee', 'cbxwpsimpleaccounting'),
            'ILS' => esc_html__('Israeli Shekel', 'cbxwpsimpleaccounting'),
            'JPY' => esc_html__('Japanese Yen', 'cbxwpsimpleaccounting'),
            'KIP' => esc_html__('Lao Kip', 'cbxwpsimpleaccounting'),
            'KRW' => esc_html__('South Korean Won', 'cbxwpsimpleaccounting'),
            'MYR' => esc_html__('Malaysian Ringgits', 'cbxwpsimpleaccounting'),
            'MXN' => esc_html__('Mexican Peso', 'cbxwpsimpleaccounting'),
            'NGN' => esc_html__('Nigerian Naira', 'cbxwpsimpleaccounting'),
            'NOK' => esc_html__('Norwegian Krone', 'cbxwpsimpleaccounting'),
            'NZD' => esc_html__('New Zealand Dollar', 'cbxwpsimpleaccounting'),
            'PYG' => esc_html__('Paraguayan Guaraní', 'cbxwpsimpleaccounting'),
            'PHP' => esc_html__('Philippine Pesos', 'cbxwpsimpleaccounting'),
            'PLN' => esc_html__('Polish Zloty', 'cbxwpsimpleaccounting'),
            'GBP' => esc_html__('Pounds Sterling', 'cbxwpsimpleaccounting'),
            'RON' => esc_html__('Romanian Leu', 'cbxwpsimpleaccounting'),
            'RUB' => esc_html__('Russian Ruble', 'cbxwpsimpleaccounting'),
            'SGD' => esc_html__('Singapore Dollar', 'cbxwpsimpleaccounting'),
            'ZAR' => esc_html__('South African rand', 'cbxwpsimpleaccounting'),
            'SEK' => esc_html__('Swedish Krona', 'cbxwpsimpleaccounting'),
            'CHF' => esc_html__('Swiss Franc', 'cbxwpsimpleaccounting'),
            'TWD' => esc_html__('Taiwan New Dollars', 'cbxwpsimpleaccounting'),
            'THB' => esc_html__('Thai Baht', 'cbxwpsimpleaccounting'),
            'TRY' => esc_html__('Turkish Lira', 'cbxwpsimpleaccounting'),
            'UAH' => esc_html__('Ukrainian Hryvnia', 'cbxwpsimpleaccounting'),
            'USD' => esc_html__('US Dollars', 'cbxwpsimpleaccounting'),
            'VND' => esc_html__('Vietnamese Dong', 'cbxwpsimpleaccounting'),
            'EGP' => esc_html__('Egyptian Pound', 'cbxwpsimpleaccounting'),
            'IRR' => esc_html__('Iranian rial', 'cbxwpsimpleaccounting') //from version
                )
            )
        );
    }

	/**
	 * Get Currency symbol.
	 *
	 * @param string $currency (default: '')
	 *
	 * @return string
	 */
	public function get_cbxwpsimpleaccounting_currency_symbol($currency_code = '') {
		if (!$currency_code) {
			$currency_code = $this->get_cbxwpsimpleaccounting_currency();
		}

		switch ($currency_code) {
			case 'AED' :
				$currency_symbol = 'د.إ';
				break;
			case 'AUD' :
			case 'ARS' :
			case 'CAD' :
			case 'CLP' :
			case 'COP' :
			case 'HKD' :
			case 'MXN' :
			case 'NZD' :
			case 'SGD' :
			case 'USD' :
				$currency_symbol = '&#36;';
				break;
			case 'BDT':
				$currency_symbol = '&#2547;&nbsp;';
				break;
			case 'BGN' :
				$currency_symbol = '&#1083;&#1074;.';
				break;
			case 'BRL' :
				$currency_symbol = '&#82;&#36;';
				break;
			case 'CHF' :
				$currency_symbol = '&#67;&#72;&#70;';
				break;
			case 'CNY' :
			case 'JPY' :
			case 'RMB' :
				$currency_symbol = '&yen;';
				break;
			case 'CZK' :
				$currency_symbol = '&#75;&#269;';
				break;
			case 'DKK' :
				$currency_symbol = 'DKK';
				break;
			case 'DOP' :
				$currency_symbol = 'RD&#36;';
				break;
			case 'EGP' :
				$currency_symbol = 'EGP';
				break;
			case 'EUR' :
				$currency_symbol = '&euro;';
				break;
			case 'GBP' :
				$currency_symbol = '&pound;';
				break;
			case 'HRK' :
				$currency_symbol = 'Kn';
				break;
			case 'HUF' :
				$currency_symbol = '&#70;&#116;';
				break;
			case 'IDR' :
				$currency_symbol = 'Rp';
				break;
			case 'ILS' :
				$currency_symbol = '&#8362;';
				break;
			case 'INR' :
				$currency_symbol = 'Rs.';
				break;
			case 'ISK' :
				$currency_symbol = 'Kr.';
				break;
			case 'KIP' :
				$currency_symbol = '&#8365;';
				break;
			case 'KRW' :
				$currency_symbol = '&#8361;';
				break;
			case 'MYR' :
				$currency_symbol = '&#82;&#77;';
				break;
			case 'NGN' :
				$currency_symbol = '&#8358;';
				break;
			case 'NOK' :
				$currency_symbol = '&#107;&#114;';
				break;
			case 'NPR' :
				$currency_symbol = 'Rs.';
				break;
			case 'PHP' :
				$currency_symbol = '&#8369;';
				break;
			case 'PLN' :
				$currency_symbol = '&#122;&#322;';
				break;
			case 'PYG' :
				$currency_symbol = '&#8370;';
				break;
			case 'RON' :
				$currency_symbol = 'lei';
				break;
			case 'RUB' :
				$currency_symbol = '&#1088;&#1091;&#1073;.';
				break;
			case 'SEK' :
				$currency_symbol = '&#107;&#114;';
				break;
			case 'THB' :
				$currency_symbol = '&#3647;';
				break;
			case 'TRY' :
				$currency_symbol = '&#8378;';
				break;
			case 'TWD' :
				$currency_symbol = '&#78;&#84;&#36;';
				break;
			case 'UAH' :
				$currency_symbol = '&#8372;';
				break;
			case 'VND' :
				$currency_symbol = '&#8363;';
				break;
			case 'ZAR' :
				$currency_symbol = '&#82;';
				break;
			case 'IRR' :
				$currency_symbol = '&#65020;';
				break;
			default :
				$currency_symbol = '';
				break;
		}
		return apply_filters('cbxwpsimpleaccounting_currency_symbol', $currency_symbol, $currency_code);
	}

    /**
     * All time Total income or expense
     *
     * @param bool|false $format
     *
     * @return array
     */
    public function get_data_total_quick($format = false) {

        global $wpdb;

        $total_income = $total_expense = 0;

        //income
        $total_income_without_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d ', 1));


        $total_income += $total_income_without_tax;

        $total_income_where_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount*c.tax/100)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND c.istaxincluded = %d ', 1, 1));

        $total_income += $total_income_where_tax;


        //expense
        $total_expense_without_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d ', 2));


        $total_expense += $total_expense_without_tax;

        $total_expense_where_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount*c.tax/100)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND c.istaxincluded = %d ', 2, 1));

        $total_expense += $total_expense_where_tax;


        $profit = $total_income - $total_expense;

        if($format){
            $total_income = $this->format_value_quick($total_income);
            $total_income_where_tax = $this->format_value_quick($total_income_where_tax);
            $total_expense = $this->format_value_quick($total_expense);
            $total_expense_where_tax = $this->format_value_quick($total_expense_where_tax);
            $profit = $this->format_value_quick($profit);
        }

        return (object)array('total_income' => $total_income, 'income_tax' => $total_income_where_tax, 'total_expense' => $total_expense, 'expense_tax' => $total_expense_where_tax, 'profit' => $profit);
    }

    /**
     * All time Total income or expense for a year
     *
     * @param bool|false $use_current
     * @param int        $year
     * @param bool|false     $format

     *
     * @return array
     */
    public function get_data_year_total_quick($year = 0, $format = false) {

        global $wpdb;

        if($year == 0) $year = intval(date('Y'));



        $year = intval($year);

        $total_income = $total_expense = 0;

        //income
        $total_income_without_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND YEAR(c.add_date) = %d', 1, $year));


        $total_income += $total_income_without_tax;

        $total_income_where_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount*c.tax/100)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND c.istaxincluded = %d AND YEAR(c.add_date) = %d', 1, 1, $year));

        $total_income += $total_income_where_tax;


        //expense
        $total_expense_without_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND YEAR(c.add_date) = %d', 2, $year));


        $total_expense += $total_expense_without_tax;

        $total_expense_where_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount*c.tax/100)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND c.istaxincluded = %d AND YEAR(c.add_date) = %d ', 2, 1, $year));

        $total_expense += $total_expense_where_tax;

        $profit = $total_income - $total_expense;

        if($format){
            $total_income = $this->format_value_quick($total_income);
            $total_income_where_tax = $this->format_value_quick($total_income_where_tax);
            $total_expense = $this->format_value_quick($total_expense);
            $total_expense_where_tax = $this->format_value_quick($total_expense_where_tax);
            $profit = $this->format_value_quick($profit);
        }



        return (object)array('total_income' => $total_income, 'income_tax' => $total_income_where_tax, 'total_expense' => $total_expense, 'expense_tax' => $total_expense_where_tax, 'profit' => $profit);
    }

    /**
     * All time Total income or expense for a month
     *
     * @param bool|false $use_current
     * @param int        $year
     * @param bool|false $format

     *
     * @return array
     */
    public function get_data_month_total_quick($year = 0, $month = 0, $format = false) {

        global $wpdb;

        if($year == 0) $year = intval(date('Y'));
        if($month == 0) $month = intval(date('m'));


        $year = intval($year);
        $month = intval($month);

        $total_income = $total_expense = 0;

        //income
        $total_income_without_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND MONTH(c.add_date) =%d  AND YEAR(c.add_date) = %d', 1, $month, $year));


        $total_income += $total_income_without_tax;

        $total_income_where_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount*c.tax/100)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND c.istaxincluded = %d AND MONTH(c.add_date) =%d AND YEAR(c.add_date) = %d', 1, 1, $month, $year));

        $total_income += $total_income_where_tax;


        //expense
        $total_expense_without_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND MONTH(c.add_date) =%d AND YEAR(c.add_date) = %d', 2, $month, $year));


        $total_expense += $total_expense_without_tax;

        $total_expense_where_tax = $wpdb->get_var($wpdb->prepare('SELECT SUM(c.amount*c.tax/100)
FROM  `' . $wpdb->prefix . 'cbaccounting_expinc` c
WHERE c.type = %d AND c.istaxincluded = %d AND MONTH(c.add_date) =%d AND YEAR(c.add_date) = %d ', 2, 1, $month, $year));

        $total_expense += $total_expense_where_tax;

        $profit = $total_income - $total_expense;

        if($format){
            $total_income = $this->format_value_quick($total_income);
            $total_income_where_tax = $this->format_value_quick($total_income_where_tax);
            $total_expense = $this->format_value_quick($total_expense);
            $total_expense_where_tax = $this->format_value_quick($total_expense_where_tax);
            $profit = $this->format_value_quick($profit);
        }



        return (object)array('total_income' => $total_income, 'income_tax' => $total_income_where_tax, 'total_expense' => $total_expense, 'expense_tax' => $total_expense_where_tax, 'profit' => $profit);
    }



}
