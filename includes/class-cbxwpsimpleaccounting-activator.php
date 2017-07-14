<?php

    /**
     * Fired during plugin activation
     *
     * @link       http://codeboxr.com
     * @since      1.0.0
     *
     * @package    Cbxwpsimpleaccounting
     * @subpackage Cbxwpsimpleaccounting/includes
     * @author     Codeboxr <info@codeboxr.com>
     */
    class Cbxwpsimpleaccounting_Activator {

        /**
         * Short Description. (use period)
         *
         * Long Description.
         *
         * @since    1.0.0
         */
        public static function activate() {
            global $wpdb;

            $charset_collate = '';
            if( $wpdb->has_cap( 'collation' ) ) {
                if(!empty($wpdb->charset)) {
                    $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
                }
                if(!empty($wpdb->collate)) {
                    $charset_collate .= " COLLATE $wpdb->collate";
                }
            }



            if (!current_user_can('activate_plugins'))
                return;

            $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
            check_admin_referer("activate-plugin_{$plugin}");

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            $table_expinc = $wpdb->prefix . 'cbaccounting_expinc';

            $sql = "CREATE TABLE $table_expinc (
                          id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                          title varchar(500) DEFAULT NULL COMMENT 'title for list item',
                          amount float NOT NULL DEFAULT '0' COMMENT 'The amount of expense or income.',
                          source_amount float NULL DEFAULT NULL COMMENT 'The source amount of expense or income.',
                          source_currency varchar(50) NULL DEFAULT NULL COMMENT 'The source currency',
                          type enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 for income and 2 for expense.',
                          note varchar(5000) DEFAULT NULL COMMENT 'a short description about income or expense.',
                          account int(11) DEFAULT NULL,
                          invoice varchar(100) DEFAULT NULL,
                          istaxincluded tinyint(1) NOT NULL DEFAULT '0',
                          tax float DEFAULT NULL,
                          add_by bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'foreign key of user table. who add this list.',
                          mod_by bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'foreign key of user table. who last modify this list.',
                          add_date datetime DEFAULT NULL COMMENT 'add date',
                          mod_date datetime DEFAULT NULL COMMENT 'last modified date',
                          vc_id int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'foreign key of vc table. vc id association',
                          UNIQUE KEY id (id)
                        ) $charset_collate; ";

            dbDelta($sql);


            $table_category = $wpdb->prefix . 'cbaccounting_category';
            
            $sql = "CREATE TABLE $table_category (
                          id int(11) unsigned NOT NULL AUTO_INCREMENT,
                          title varchar(200) NOT NULL COMMENT 'category title',
                          note varchar(2000) DEFAULT NULL COMMENT 'short description about category',
                          type enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 for income and 2 for expense.',
                          color varchar(7) NOT NULL,
                          publish enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 for unpublished and 1 for published',
                          add_by bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'foreign key of user table. who add this category',
                          mod_by bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'foreign key of user table. who modified this category',
                          add_date datetime DEFAULT NULL COMMENT 'created date',
                          mod_date datetime DEFAULT NULL COMMENT 'modified date',
                          UNIQUE KEY id (id)
                        ) $charset_collate; ";


            dbDelta($sql);


            $table_expat_rel = $wpdb->prefix . 'cbaccounting_expcat_rel';

            $sql = "CREATE TABLE $table_expat_rel (
				  id bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'relation id',
                  expinc_id bigint(20) NOT NULL COMMENT 'foreign key of expense/income id',
                  category_id int(11) NOT NULL COMMENT 'foreign key of category table. id of expense or income category. default is un-categorized.',
                  UNIQUE KEY id (id)
                ) $charset_collate;";

            dbDelta($sql);


            $table_account = $wpdb->prefix . 'cbaccounting_account_manager';

            $sql = "CREATE TABLE $table_account (
                          id int(11) unsigned NOT NULL AUTO_INCREMENT,
                          title varchar(200) NOT NULL COMMENT 'account title',
                          type varchar(5) NOT NULL COMMENT 'account type',
                          acc_no varchar(200) DEFAULT NULL COMMENT 'account number',
                          acc_name varchar(200) DEFAULT NULL COMMENT 'account name',
                          bank_name varchar(200) DEFAULT NULL COMMENT 'bank name',
                          branch_name varchar(200) DEFAULT NULL COMMENT 'branch name',
                          add_by bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'foreign key of user table. who add this category',
                          mod_by bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'foreign key of user table. who modified this category',
                          add_date datetime DEFAULT NULL COMMENT 'created date',
                          mod_date datetime DEFAULT NULL COMMENT 'modified date',
                          UNIQUE KEY id (id)
                        ) $charset_collate; ";

            dbDelta($sql);
        }

    }
