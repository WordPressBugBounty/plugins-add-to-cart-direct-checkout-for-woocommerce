<?php

if ( ! defined( 'WPINC' ) ) die();

class Pisol_Dcw_checkout_Authentication {

	const URL_ARG = 'redirect_to_checkout';

	protected static $instance = null;

	protected function __construct () {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

    public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	protected function hasQueryParam() {
		return isset( $_GET[ self::URL_ARG ] );
	}

	protected function getLoginPageUrl() {
		return get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
	}

	protected function getCheckoutPageUrl() {
		return  wc_get_checkout_url();
	}

	public function init () {
		
        $force_login = get_option('pi_dcw_force_login', 0);

        if(empty($force_login)) return;

		add_action( 'template_redirect', [ $this, 'redirectToMyAccountPage' ] );
		add_action( 'wp_head', [ $this, 'notice' ] );

		add_filter( 'woocommerce_registration_redirect', [ $this, 'redirectToCheckout' ], 100 );
		add_filter( 'woocommerce_login_redirect', [ $this, 'redirectToCheckout' ], 100 );
		add_action( 'wp_head', [ $this, 'redirectToCheckoutHtml' ] );
	}

	public function redirectToMyAccountPage () {
		$condition = is_checkout() && ! is_user_logged_in();
		if( $condition ) {
			wp_safe_redirect( add_query_arg( self::URL_ARG, '', $this->getLoginPageUrl() ) );
			die;
		}
	}

	public function redirectToCheckoutHtml () {
		if ( $this->hasQueryParam() && is_user_logged_in() ) {
			?>
			<meta
				http-equiv="Refresh"
				content="0; url='<?php echo esc_attr( $this->getCheckoutPageUrl() ); ?>'"
			/>
			<?php
			exit();
		}
	}

	public function redirectToCheckout ( $redirect ) {
		if ( $this->hasQueryParam() ) {
			$redirect = $this->getCheckoutPageUrl();
		}
		return $redirect;
	}

	public function getMessage() {
		return  strip_tags(get_option( 'pi_dcw_force_login_msg', 'Please log in or register to complete your purchase.'));
	}

	public function notice () {
		if ( ! is_user_logged_in() && is_account_page() && $this->hasQueryParam() ) {
            $msg = $this->getMessage();
            if(!empty($msg)){
                wc_add_notice( $msg, 'error' );
            }
		}
	}
}

Pisol_Dcw_checkout_Authentication::get_instance();