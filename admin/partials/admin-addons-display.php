<?php
/**
 * Provide a dashboard view for the all the addons of this plugin
 *
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
<?php
//check log addon activation status
$cbxwpsimpleaccountinglog       = $this->get_plugin_status('cbxwpsimpleaccountinglog/cbxwpsimpleaccountinglog.php');

//check statement addon activation status
$cbxwpsimpleaccountingstatement = $this->get_plugin_status('cbxwpsimpleaccountingstatement/cbxwpsimpleaccountingstatement.php');

//check vendor and client addon activation status
$cbxwpsimpleaccountingvc = $this->get_plugin_status('cbxwpsimpleaccountingvc/cbxwpsimpleaccountingvc.php');
?>
<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php _e('CBX Accounting: Add-ons', $this->cbxwpsimpleaccounting); ?></h2>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                            <!--<h3><span><?php _e('Add-ons', $this->cbxwpsimpleaccounting); ?></span></h3>-->
                        <div class="inside">
                            <?php
                            $log_addon_thumb                = $cbxaccount_admin_url . '/images/addon-log.png';
                            $income_statement_thumb         = $cbxaccount_admin_url . '/images/addon-statement.png';
                            $vendor_thumb         			= $cbxaccount_admin_url . '/images/addon-vendor-client.png';
                            $idea_thumb                     = $cbxaccount_admin_url . '/images/idea.png';
                            ?>
                            <div class="cbxwpaccounting_addon">
                                <a href="https://codeboxr.com/product/log-manager-addon-for-cbx-accounting" target="_blank"><img src="<?php echo $log_addon_thumb; ?>" alt="log" ></a>
                                <p class="cbxwpaccounting_addonstatus"><a href="https://codeboxr.com/product/log-manager-addon-for-cbx-accounting" class="<?php echo $cbxwpsimpleaccountinglog['btnclass'] ?>"><?php echo $cbxwpsimpleaccountinglog['msg']; ?></a></p>
                            </div>   
                            <div class="cbxwpaccounting_addon">
                                <a href="https://codeboxr.com/product/statement-addon-for-cbx-accounting" target="_blank"><img src="<?php echo $income_statement_thumb; ?>" alt="income-statement" ></a>
                                <p class="cbxwpaccounting_addonstatus"><a href="https://codeboxr.com/product/statement-addon-for-cbx-accounting" class="<?php echo $cbxwpsimpleaccountingstatement['btnclass'] ?>"><?php echo $cbxwpsimpleaccountingstatement['msg']; ?></a></p>
                            </div>
							<div class="cbxwpaccounting_addon">
								<a href="https://codeboxr.com/product/vendor-and-client-addon-for-cbx-accounting/" target="_blank"><img src="<?php echo $vendor_thumb; ?>" alt="income-vendor-client" ></a>
								<p class="cbxwpaccounting_addonstatus"><a href="https://codeboxr.com/product/vendor-and-client-addon-for-cbx-accounting/" class="<?php echo $cbxwpsimpleaccountingvc['btnclass'] ?>"><?php echo $cbxwpsimpleaccountingvc['msg']; ?></a></p>
							</div>
							<div class="cbxwpaccounting_addon">
                                <a href="http://codeboxr.com/contact-us" target="_blank"><img src="<?php echo $idea_thumb; ?>" alt="income-statement" ></a>
                            </div>    
                            <div class="cbxclearfix"></div>

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


<script type="text/javascript">

    jQuery(document).ready(function($) {
        //if need any js code here
       
    });

</script>
