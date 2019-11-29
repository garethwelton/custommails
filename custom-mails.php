<?php

/**
  * Plugin Name: Custom Mails
  * Plugin URI: https://welton.no
  * Description: Edit automated emails in WooCommerce.
  * Version: 0.0.1
  * Author: Welton
  * Author URI: https://welton.no
  * Developer: Mike Quade
  * Developer URI: https://welton.no
  * Text Domain: custommails
  * Domain Path: /languages
  *
  */

if (!defined('ABSPATH') || !in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	exit;
}

/* begin activation hook */

// has files
// version test greater than

// if both then ok

function custom_mails_getTemplateDir() {
	return __DIR__ . '/../woocommerce/templates/emails/';
}

function custom_mails_getBackupDir() {
	return __DIR__ . '/backup/';
}

function custom_mails_getReplaceDir() {
	return __DIR__ . '/replace/';
}

function custom_mails_getFileNames() {
	$aFileNames = array('admin-cancelled-order.php',
						'admin-failed-order.php',
						'admin-new-order.php',
						'customer-completed-order.php',
						'customer-invoice.php',
						'customer-new-account.php',
						'customer-note.php',
						'customer-on-hold-order.php',
						'customer-processing-order.php',
						'customer-refunded-order.php',
						'customer-reset-password.php',
						'email-addresses.php',
						'email-customer-details.php',
						'email-downloads.php',
						'email-order-details.php',
						'email-order-items.php');
	/*
	foreach ($aFileNames as $sFileName) {
		$aFileNames[] = 'plain/' . $sFileName;
	}*/
	$aFileNames[] = 'email-footer.php';
	$aFileNames[] = 'email-header.php';
	$aFileNames[] = 'email-styles.php';
	return $aFileNames;
}

function custom_mails_createBackup() {
	$aBackupDir = custom_mails_getBackupDir();
	$aTemplateDir = custom_mails_getTemplateDir();
	$aFileNames = custom_mails_getFileNames();
	foreach ($aFileNames as $sFileName) {
		copy($aTemplateDir . $sFileName, $aBackupDir . $sFileName);
	}
}

function custom_mails_replaceTemplates() {
	$aReplaceDir = custom_mails_getReplaceDir();
	$aTemplateDir = custom_mails_getTemplateDir();
	$aFileNames = custom_mails_getFileNames();
	foreach ($aFileNames as $sFileName) {
		copy($aReplaceDir . $sFileName, $aTemplateDir . $sFileName);
	}
}

function custom_mails_prepareTemplates() {
	custom_mails_createBackup();
	custom_mails_replaceTemplates();
}

register_activation_hook(__FILE__, 'custom_mails_prepareTemplates');

/* end activation hook */

/* begin submenu registration */

function custom_mails_registerSubMenuPage() {
	add_submenu_page(	'woocommerce', 
						'Custom Mails', 
						'Custom Mails', 
						'manage_options', 
						'custom-mails-submenu-page', 
						'custom_mails_submenu_page_callback');
}

function custom_mails_submenu_page_callback() {
	echo '<h3>Custom Mails</h3>';
	$sLink = menu_page_url('custom-mails-submenu-page', false);
	$aFileNames = custom_mails_getFileNames();
	if (isset($_REQUEST['t']) && is_numeric(($_REQUEST['t']))) {
		$sKey = htmlspecialchars($_REQUEST['t']);
		$sFileName = $aFileNames[$sKey];
		if (isset($_REQUEST['format']) && $_REQUEST['format'] == 'text') {
			$sFileName = 'plain/' . $sFileName;
		}
		if (isset($_POST['custom_mails_editor'])) {
			$sContent = $_POST['custom_mails_editor'];
			$hFile = fopen(custom_mails_getTemplateDir() . $sFileName, "w+");
			fwrite($hFile, $sContent);
			fclose($hFile);
			echo '<p>File updated successfully!</p>';
		}
		echo '<p><strong>' . $sFileName . '</strong></p>';
		$sContent = file_get_contents(custom_mails_getTemplateDir() . $sFileName);
		echo '<div id="custom_mails_content_left">';
		echo '<form method="post" action="' . $sLink . '&t=' . $sKey . '">';
		$aSettings = array(
			'wpautop' => true,
			'media_buttons' => false,
			'textarea_name' => 'custom_mails_editor',
			'textarea_rows' => 20,
			'tabindex' => '',
			'tabfocus_elements' => ':prev,:next', 
			'editor_css' => '', 
			'editor_class' => '',
			'teeny' => false,
			'dfw' => false,
			'tinymce' => false,
			'quicktags' => true
		);
		wp_editor($sContent, 'custom_mails_editor', $aSettings);
		echo '</div>';
		require_once(ABSPATH . 'wp-content/plugins/custom-mails/inc/content-right.inc.php');
		echo '<div style="clear:both;">';
		echo '</div>';
	} else {
		echo '<p>Please choose the template that you want to edit.</p>';
		foreach ($aFileNames as $sKey => $sFileName) {
			echo '<a href="' . $sLink . '&t=' . $sKey . '">' . $sFileName . '</a><br>';
			if ($sKey == 15) {
				echo '<h4>Additional Template Files</h4>';
			}
		}
	}
	echo '<h4>Factory Reset</h4>';
	echo 'Click <a id="custom-mails-reset" href="javascript:void(0)">here</a> to revert your changes.';
}

add_action('admin_menu', 'custom_mails_registerSubMenuPage',99);

/* end submenu registration */

/* begin register style sheets */

function custom_mails_registerPluginStyles() {
	wp_register_style('style_css', plugins_url('/css/style.css', __FILE__));
	wp_enqueue_style('style_css');
}

add_action('admin_enqueue_scripts', 'custom_mails_registerPluginStyles');

/* end register style sheets */

/* begin register javascript */

function custom_mails_registerPluginJS() {
	wp_register_script('app_js', plugins_url('/js/app.js', __FILE__), array('jquery'));
	wp_enqueue_script('app_js');
}

add_action('admin_enqueue_scripts', 'custom_mails_registerPluginJS');

/* end register javascript */

function custom_mails_saveOutputBuffer() {
	file_put_contents(ABSPATH . 'wp-content/plugins/custom-mails/log.html', ob_get_contents());
}

add_action('activated_plugin','custom_mails_saveOutputBuffer');

/* end log */

?>