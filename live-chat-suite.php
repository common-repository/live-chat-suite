

<?php
/**
 * Plugin Name:       Live Chat Suite 
 * Description:       Live Chat For Wordpress!
 * Version:           1.0.0
 * Author:            livechatsuite
 * Author URI:        https://www.livechatsuite.com
 * Text Domain:       livechatsuite
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * License:           GPL-2.0+
*/
 
if(!defined('LIVECHATSUITE_PLUGIN_VERSION'))
	define('LIVECHATSUITE_PLUGIN_VERSION', '1.0.0');
if(!defined('LIVECHATSUITE_URL'))
	define('LIVECHATSUITE_URL', plugin_dir_url( __FILE__ ));
	
if(!defined('LIVECHATSUITE_PATH'))
	define('LIVECHATSUITE_PATH', plugin_dir_path( __FILE__ ));
 

class Livechatsuite
{

	/**
	 *
	 * @var string
	 */
	private $_nonce = 'livechatsuite_admin';

	/**
	 *
	 * @var string
	 */
	private $option_name = 'livechatsuite_data';

	public function __construct()
    {
        add_action('wp_footer',                 array($this,'addFooterCode'));
        add_action('admin_menu',                array($this,'addAdminMenu'));
		add_action('wp_ajax_store_admin_data',  array($this,'storeAdminData'));
		add_action('admin_enqueue_scripts',     array($this,'addAdminScripts'));

	}

	

	/**
	 * Returns the saved options data as an array
     *
     * @return array
	 */
	private function getData()
    {
	    return get_option($this->option_name, array());
    }

	/**
	 * Callback for the Ajax request
	 *
	 * Updates the options data
     *
     * @return void
	 */
	public function storeAdminData()
    {
        $san_nonce = sanitize_text_field($_POST['_nonce']);
		if (wp_verify_nonce($san_nonce, $this->_nonce ) === false)
			die('oops, Something went wrong, please try again!');

		$data = $this->getData();

		 $san_lcs_site_id = sanitize_text_field($_POST['lcs_site_id']);
         update_option($this->option_name, $san_lcs_site_id);

		echo __('Saved Site ID! Navigate to a wordpress page to see your chat widget.', 'livechatsuite');
		die();

	}

	/**
	 * Adds Admin Scripts for the Ajax call
	 */
	public function addAdminScripts()
    {

	    wp_enqueue_style('livechatsuite-admin', LIVECHATSUITE_URL. 'assets/css/admin.css', false, 1.0);

		wp_enqueue_script('livechatsuite-admin', LIVECHATSUITE_URL. 'assets/js/admin.js', array(), 1.0);

		$admin_options = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'_nonce'   => wp_create_nonce( $this->_nonce ),
		);

		wp_localize_script('livechatsuite-admin', 'livechatsuite_exchanger', $admin_options);

	}
	public function addAdminMenu()
    {
		add_menu_page( __( 'Live Chat Suite', 'livechatsuite' ), __( 'Live Chat Suite', 'livechatsuite' ),
		'manage_options','livechatsuite',array($this, 'adminLayout'),
			'dashicons-format-status'
		);
	}

	public function adminLayout()
    { 
	
	?>

		<div class="wrap">
        <form id="lcs_add_siteid" class="postbox">

                <div class="form-group inside">

            <h1><?php _e('LiveChat Suite - Give Your Users The Live Chat Experience They Deserve!', 'livechatsuite'); ?></h1>

			
				<?php $this->addFooterCode(true); ?>
         
                <h3><?php _e('Setup LiveChat Suite', 'livechatsuite'); ?></h3>
                
       <?php _e('Congrats! Set up is quick and easy, simple enter your Site ID in the box below and your ready to go!', 'livechatsuite'); ?>
 <br> <br>
           <br>
          <?php _e('Don`t have a Site ID yet? Create an account and <a href="https://www.livechatsuite.com/start" target="_blank">get one here</a> - It only takes 1 minute!', 'livechatsuite'); ?>
          <br>
          <?php _e('Already have an account? Login and customize your widget <a href="https://www.livechatsuite.com/login" target="_blank">here</a>', 'livechatsuite'); ?>
         
              <table class="form-table">
              <tbody><tr><td scope="row">
              <label><?php _e( 'Your Site ID:', 'livechatsuite' ); ?></label></td><td>
              <input name="lcs_site_id"id="lcs_site_id" class="regular-text"type="text"value="<?php echo get_option('livechatsuite_data');?>"/>
              </td></tr></tbody></table> </div>

              <div class="inside"><button class="button button-primary" id="save_lcs_id" type="submit">
              <?php _e( 'Save', 'livechatsuite' ); ?></button></div></form></div>

		<?php

	}

	/**
     * @param $force boolean
     * @return void
     */
	public function addFooterCode($force = false)
    { 
	
   $lcs_id=get_option('livechatsuite_data'); if(!empty($lcs_id)){ 
	
        ?>
  <!--Start Live Chat Suite Code-->
  <script type="text/javascript">(function() {var s = document.createElement('script');s.type = 'text/javascript';s.async = true;s.src = 'https://chat.livechatsuite.com/clientloader.js';var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);})();lcs_data = ({uid: '<?php echo $lcs_id ?>'});</script>
  <!--End Live Chat Suite Analytics Code-->

        <?php    }

    }

}

new Livechatsuite();