<?php 

namespace PISOL\DCW\FRONT;

class SoldIndividuallyFront{

    static $instance = null;

    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct(){
        add_action( 'woocommerce_add_to_cart_sold_individually_found_in_cart', array( $this, 'add_to_cart_sold_individually' ), 99, 5 );
		add_filter( 'woocommerce_is_sold_individually', array( $this, 'variation_is_sold_individually' ), 10, 2 );
    }

    public function add_to_cart_sold_individually( $found_in_cart, $product_id, $variation_id, $cart_item_data, $cart_id ) {

        if ( intval( $variation_id ) > 0 && !$found_in_cart ) {

           $cart_items = WC()->cart->get_cart();
           foreach($cart_items as $cart_item_key => $cart_item){

                if($cart_item === $cart_id) continue;

                if($cart_item['product_id'] == $product_id){
                    $product = wc_get_product($product_id);
                    $sold_individually_for_variation =  $product->get_meta('_dcw_sold_individually_variable', true);

                    if($sold_individually_for_variation == 'yes'){
                        add_filter( 'woocommerce_cart_product_cannot_add_another_message', array( $this, 'cannot_add_another_message' ), 10, 2 );
                        return true;
                    }
                }
           }
		}
		return $found_in_cart;
	}

    public function variation_is_sold_individually( $bool, $product ) {
		if ( $product->is_type( 'variation' ) && ! $bool ) {
			return $product->get_meta( '_dcw_sold_individually_variation' ) == 'yes';
		}
		return $bool;
	}

    public function cannot_add_another_message( $message, $product_data ) {
		if ( $product_data->get_type() == 'variation' ) {
			if ( $parent_product = wc_get_product( $product_data->get_parent_id() ) ) {
				$message = sprintf( __( 'You cannot add another "%s" to your cart.', 'woocommerce' ), $parent_product->get_name() );
			}
		}
		return $message;
	}
}

SoldIndividuallyFront::get_instance();