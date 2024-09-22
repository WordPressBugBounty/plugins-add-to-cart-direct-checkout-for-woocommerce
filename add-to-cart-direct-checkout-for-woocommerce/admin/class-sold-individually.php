<?php 

namespace PISOL\DCW\BACK;

class SoldIndividuallyBackend{

    static $instance = null;

    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct(){
        add_action( 'woocommerce_product_options_sold_individually', array( $this, 'add_field_to_variable_product' ) );

		add_action( 'woocommerce_process_product_meta', array( $this, 'save_field_to_product' ) );

		add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'add_field_to_variation_product' ), 10, 3 );

		add_action( 'woocommerce_save_product_variation', array( $this, 'save_field_to_variation' ), 10, 2 );
    }

	public function add_field_to_variable_product() {
		global $product_object;
		?>
		<div class="options_group show_if_variable" id="dcw_sold_individually_container" style="display:block; clear:all">
			<?php
			woocommerce_wp_checkbox(
				array(
					'id'            => '_dcw_sold_individually_variable',
					'wrapper_class' => 'show_if_variable',
					'label'         => __( 'Apply “Sold individually” to variations', 'pi-dcw' ),
					'description'   => __( 'Activate this option to permit the purchase of only one unit of this product, even if it\'s a distinct variation, in a single order.', 'pi-dcw' ),
				)
			);
			?>
		</div>
		<style type="text/css">
			.inventory_sold_individually{
				display: block !important;
			}
		</style>
		<script type="text/javascript">
			jQuery( document ).ready( function() {
				function sold_individually_apply_variations_show_hide() {
					if ( jQuery( '#_sold_individually' ).is(' :checked ' ) ) {
						jQuery( '#dcw_sold_individually_container' ).show();
					} else {
						jQuery( '#dcw_sold_individually_container' ).hide();
					}
				}
				jQuery( '#_sold_individually' ).change( function() {
					sold_individually_apply_variations_show_hide();
				} );
                jQuery( '#_sold_individually' ).trigger('change');
			});
		</script>
		<?php
	}

	public function save_field_to_product( $post_id ) {
		$sold_individually_apply_variations = ! empty( $_POST['_dcw_sold_individually_variable'] ) && ! empty( $_POST['_sold_individually'] ) ? 'yes' : 'no';
		$product = wc_get_product( $post_id );
		$product->update_meta_data( '_dcw_sold_individually_variable', $sold_individually_apply_variations );
		$product->save();
	}

	public function add_field_to_variation_product( $loop, $variation_data, $variation ) {
		$variation_object = wc_get_product( $variation->ID );
		?>
		<div>
			<?php
			woocommerce_wp_checkbox(
				array(
					'id'            => "_dcw_sold_individually_variation{$loop}",
					'name'          => "_dcw_sold_individually_variation[$loop]",
					'label'         => '&nbsp;'.__( 'Sold individually', 'woo-sold-individually-for-variations' ),
					'description'   => __( 'Activate this option to restrict the purchase of more than one unit of this variation in a single order.', 'pi-dcw' ),
					'desc_tip'      => true,
					'wrapper_class' => 'form-row form-row-full',
					'value'         => $variation_object->get_meta( '_dcw_sold_individually_variation' ),
					'cb_value'      => 'yes',
				)
			);
			?>
		</div>
		<?php
	}

	public function save_field_to_variation( $variation, $i ) {
		$sold_variation_individually = ! empty( $_POST['_dcw_sold_individually_variation'][$i] ) ? 'yes' : 'no';
		$variation = wc_get_product( $variation );
		$variation->update_meta_data( '_dcw_sold_individually_variation', $sold_variation_individually );
		$variation->save();
	}

}

SoldIndividuallyBackend::get_instance();