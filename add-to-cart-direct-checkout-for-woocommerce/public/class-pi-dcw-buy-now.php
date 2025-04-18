<?php
/**
 * functionality of the plugin.
 *
 * @link       @TODO
 * @since      1.0
 *
 * @package    @TODO
 * @subpackage @TODO
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Pi_WooCommerce_Quick_Buy_Auto_Add {

	public $show_on_product;
	public $show_on_archive;
	public $label_product;
	public $label_loop;
	public $product_position;
	public $loop_position;
	public $product_redirect;
	public $loop_redirect;
	

	/**
	 * Class Constructor
	 */
	public function __construct() {
		$this->show_on_product = $this->showOnProduct();
		$this->show_on_archive = $this->showOnArchive();
		$this->label_product = __('Buy Now');
		$this->label_loop = __('Buy Now');

		$this->product_position = 'after_button';
		$this->loop_position = 'after_button';

		$this->product_redirect  = 'checkout';
		$this->loop_redirect  = 'checkout';

		$this->setup_single_product_quick_buy();
		$this->setup_shop_loop_quick_buy();
	}

	function showOnProduct(){
		$setting = get_option('pi_dcw_enable_buy_now_button',0);
		$return = $setting == 0 || $setting == "" ? false : true;
		return $return;
	}

	function showOnArchive(){
		$setting = get_option('pi_dcw_enable_buy_now_button_loop',0);
		$return = $setting == 0 || $setting == "" ? false : true;
		return $return;
	}

	public function setup_single_product_quick_buy() {
		$single_pos  = $this->product_position;

		/**
		 * we want to force the buy now button to be after the add to cart button in quick view box
		 */
		if(isset($_GET['action']) && $_GET['action'] == 'pisol_get_product'){
			$single_pos = 'after_button';
		}


		if ( $this->show_on_product == true ) {
			if ( ! empty( $single_pos ) && ! $single_pos == null ) {
				$pos = '';
				if ( $single_pos == 'before_form' ) {
					$pos = 'woocommerce_before_add_to_cart_button';
				}
				if ( $single_pos == 'after_form' ) {
					$pos = 'woocommerce_after_add_to_cart_button';
				}
				if ( $single_pos == 'after_button' ) {
					$pos = 'woocommerce_after_add_to_cart_button';
				}
				if ( $single_pos == 'before_button' ) {
					$pos = 'woocommerce_before_add_to_cart_button';
				}
				add_action( $pos, array( $this, 'add_quick_buy_button' ), 99 );
			}
		}
	}

	public function setup_shop_loop_quick_buy() {
		$single_pos  = $this->loop_position;

		if ( $this->show_on_archive == true ) {
			if ( ! empty( $single_pos ) && ! $single_pos == null ) {
				$pos = 'woocommerce_after_shop_loop_item';
				$p   = 5;
				if ( $single_pos == 'after_button' ) {
					$p = 11;
				}
				if ( $single_pos == 'before_button' ) {
					$p = 9;
				}
				add_action( $pos, array( $this, 'add_shop_quick_buy_button' ), $p );
			}
		}
	}

	public function add_quick_buy_button() {
		global $product;
		$product_id = $product->get_id();
		$class = 'pisol_type_'.$product->get_type();

		$this->label_loop = __('Buy Now','pi-dcw');


		echo '<button class="button pisol_single_buy_now pisol_buy_now_button '.esc_attr( $class ).'" type="submit" name="pi_quick_checkout" value="'.esc_attr($product_id).'">'.esc_html( $this->label_loop ).'</button>';

		if($product->is_type('simple')){
			echo '<input  type="hidden" name="add-to-cart" value="'.esc_attr($product_id).'">';
		}
		/*
		if ( $product->get_type() == 'variable'){
			echo '<input class="button pisol_single_buy_now pisol_buy_now_button '.$class.'" type="submit" name="pi_quick_checkout" value="'.esc_attr($this->label_loop).'">';
		}else{
			echo '<button class="button pisol_single_buy_now pisol_buy_now_button '.$class.'" type="submit" name="add-to-cart" value="'.esc_attr($product_id).'">'.$this->label_loop.'</button>
			
			';
		}	
		*/
	}
	
	public function add_shop_quick_buy_button() {
		global $product;
		$this->label_loop = __('Buy Now','pi-dcw');
		if ( $product->get_type() == 'simple' ) {
			$link  = $this->get_product_addtocartLink($product, 1, $this->loop_redirect);
			if($link !== false && $product->is_in_stock()){
				echo wp_kses_post( $this->buttonHtml($link, $this->label_loop) );
			}
		}
	}

	function buttonHtml($link, $label){
		return '<a class="pisol_buy_now_button" href="'.esc_url( $link ).'">'.esc_html( $label ).'</a>';
	}

	public function get_product_addtocartLink( $product, $qty = 1 , $page= 'checkout') {
		if ( $product->get_type() == 'simple' ) {
			if($page == 'checkout'){
				$checkout = wc_get_checkout_url();
			}else{
				$checkout = wc_get_cart_url();
			}
			$link = $checkout.'?pi_quick_checkout=1&add-to-cart='.$product->get_id();
			return $link;
		}
		return false;
	}

	static function get_redirect_url(){
		$loop_redirect = 'checkout';
		if($loop_redirect == 'checkout'){
			$checkout = wc_get_checkout_url();
		}else{
			$checkout = wc_get_cart_url();
		}
		$link = $checkout;
		return $link;
	}

}

new Pi_WooCommerce_Quick_Buy_Auto_Add();