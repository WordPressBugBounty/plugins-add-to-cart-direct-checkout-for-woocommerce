<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Pi_Dcw_Checkout{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'checkout';

    private $tab_name = "Checkout setting";

    private $setting_key = 'checkout_setting';

    private $pages =array();

    public $tab;
   
    
    private $pro_version = false;

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;


        $this->pages = $this->get_pages();
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        $billing_fields = array(
            'billing_first_name'=>'Billing First Name',
            'billing_last_name'=>'Billing Last Name',
            'billing_address_1'=>"Billing Address 1",
            'billing_address_2'=>"Billing Address 2",
            'billing_country'=>"Billing Country",
            'billing_city'=>"Billing City",
            'billing_state'=>"Billing State",
            'billing_postcode'=>"Billing Postcode",
            'billing_company'=>"Billing Company",
            'billing_phone'=>"Billing Phone"
        );

        $shipping_fields = array(
            'shipping_first_name'=>'Shipping First Name',
            'shipping_last_name'=>'Shipping Last Name',
            'shipping_address_1'=>"Shipping Address 1",
            'shipping_address_2'=>"Shipping Address 2",
            'shipping_country'=>"Shipping Country",
            'shipping_city'=>"Shipping City",
            'shipping_state'=>"Shipping State",
            'shipping_postcode'=>"Shipping Postcode",
            'shipping_company'=>"Shipping Company",
        );


        add_action($this->plugin_name.'_tab', array($this,'tab'),6);

        $single_page_checkout_enabled = get_option('pi_dcw_single_page_checkout',1);

        if(empty($single_page_checkout_enabled)){
            $warning = 'd-none';
            $disable = '';
        }else{
            $warning = '';
            $disable = 'pi-disable-field';
            update_option('pi_dcw_remove_product_from_checkout_page',0);
            update_option('pi_dcw_change_quantity_from_checkout_page',0);
        }

        $url = admin_url( 'admin.php?page=pi-dcw&tab=default#row_pi_dcw_single_page_checkout' );

        $this->settings = array(
            array('field'=>'option_to_remove_qty_on_checkout', 'class'=> 'bg-dark opacity-75 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Change and Remove Quantity on checkout page <div class="pi-checkout-qty-edit-conflict-warning '.$warning.'">This options can\'t be used along with "<a href="'.$url.'" target="_blank">Enable single page checkout</a>" so disable the "Enable single page checkout" option in Basic Setting tab first</div>','pi-dcw'), 'type'=>'setting_category'),

            array('field'=>'pi_dcw_remove_product_from_checkout_page','desc'=>__('It will show remove button before each product in the checkout page so customer can remove it without leaving checkout page','pi-dcw'), 'label'=>__('Remove product option on checkout page','pi-dcw'),'type'=>'switch','default'=>0, 'class'=>$disable),

            array('field'=>'pi_dcw_change_quantity_from_checkout_page','desc'=>__('It will allow customer to change the quantity of product in the checkout page itself','pi-dcw'), 'label'=>__('Change quantity of product','pi-dcw'),'type'=>'switch','default'=>0, 'class'=>$disable),

            array('field'=>'sunday', 'class'=> 'bg-dark opacity-75 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Force login before checkout','pi-dcw'), 'type'=>'setting_category'),

            array('field'=>'pi_dcw_force_login','desc'=>'Once enabled customer will not be able to checkout without login', 'label'=>__('Customer can\'t checkout without login','pi-dcw'),'type'=>'switch','default'=>0),

            array('field'=>'pi_dcw_force_login_msg','desc'=>'This message will be shown on the login page when user tries to checkout without login', 'label'=>__('Message shown on login page','pi-dcw'),'type'=>'text', 'default'=>'Please log in or register to complete your purchase.'),

            array('field'=>'sunday', 'class'=> 'bg-dark opacity-75 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Checkout field setting','pi-dcw'), 'type'=>'setting_category'),

            array('field'=>'pi_dcw_remove_order_comment','desc'=>'Remove order comment from the checkout field', 'label'=>__('Remove order comment','pi-dcw'),'type'=>'switch','default'=>0),
            array('field'=>'pi_dcw_remove_have_coupon','desc'=>'Remove Have a Coupon field from the checkout page', 'label'=>__('Remove coupon field from checkout page','pi-dcw'),'type'=>'switch','default'=>0),
            array('field'=>'pi_dcw_remove_shipping_option','desc'=>'Remove "Ship to a different address?" option', 'label'=>__('Remove all the option of shipping field "Ship to a different address?"','pi-dcw'),'type'=>'switch','default'=>0,'pro'=>true),
            array('field'=>'pi_dcw_add_link_to_checkout_product_name','desc'=>'Link product name in checkout page, to product page', 'label'=>__('Link product name in checkout page','pi-dcw'),'type'=>'switch','default'=>0,'pro'=>true),
            array('field'=>'pi_dcw_remove_billing_field','desc'=>'Select fields you want to remove from checkout form billing field, Press Ctrl and click to select more than one field<br><strong>This feature will only work if your checkout page is made using WooCommerce shortcode [woocommerce_checkout], It will not work if your checkout page is made of WooCommerce checkout Block,</strong>', 'label'=>__('Remove billing fields','pi-dcw'),'type'=>'multiselect','default'=>"", 'value'=>$billing_fields,'pro'=>true),
            array('field'=>'pi_dcw_remove_shipping_field','desc'=>'Select fields you want to remove from checkout form billing field, Press Ctrl and click to select more than one field<br><strong>This feature will only work if your checkout page is made using WooCommerce shortcode [woocommerce_checkout], It will not work if your checkout page is made of WooCommerce checkout Block,</strong>', 'label'=>__('Remove shipping fields','pi-dcw'),'type'=>'multiselect','default'=>"", 'value'=>$shipping_fields,'pro'=>true),

            array('field'=>'sunday', 'class'=> 'bg-dark opacity-75 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Checkout redirect setting / Custom thankyou page','pi-dcw'), 'type'=>'setting_category'),

            array('field'=>'pi_dcw_enable_checkout_redirect','desc'=>'', 'label'=>__('Set custom thank you page','pi-dcw'),'type'=>'switch','default'=>0,'pro'=>true),

            array('field'=>'pi_dcw_checkout_redirect_to_page1','desc'=>'User will be redirected to this page after returning from the payment gateway, set this to "set custom url" and then you can even set custom url', 'label'=>__('Set this page as thank you page','pi-dcw'),'type'=>'select', 'value'=>$this->pages,'pro'=>true),

            array('field'=>'pi_dcw_custom_checkout_redirect_url1','desc'=>'Redirect to this url on checkout e.g.: http://yourwebsite.com', 'label'=>__('Use this custom url as thank you page','pi-dcw'),'type'=>'text', 'pro'=>true),

            array('field'=>'sunday', 'class'=> 'bg-dark opacity-75 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Checkout spam protection','pi-dcw'), 'type'=>'setting_category'),

            array('field'=>'pi_dcw_enable_checkout_captcha','desc'=>'Enable captcha on checkout page', 'label'=>__('Enable captcha on checkout page','pi-dcw'),'type'=>'switch','default'=>0),

            array('field'=>'pi_dcw_captcha_position','desc'=>'Position of the captcha on the checkout page (We have given the hook names)', 'label'=>__('Position of the captcha','pi-dcw'),'type'=>'select', 'value'=>$this->position(), 'default'=>'woocommerce_review_order_before_submit'),

            array('field'=>'pi_dcw_captcha_field_color','desc'=>'color scheme of the captcha field ', 'label'=>__('Captcha field color scheme','pi-dcw'),'type'=>'color', 'default'=>'#cccccc'),

            array('field'=>'pi_dcw_captcha_field_error_color','desc'=>'color scheme of the captcha field ', 'label'=>__('Captcha field error color scheme','pi-dcw'),'type'=>'color', 'default'=>'#ff0000'),

            array('field'=>'pi_dcw_captcha_characters','desc'=>'Type of string used in captcha', 'label'=>__('Select type of string to use in the captcha','pi-dcw'),'type'=>'select', 'default'=>'mix', 'value'=>array('capital_letter'=>'Capital letter','small_letter'=>'Small letter','numbers'=>'Numbers','mix'=>'Mix')),

            array('field'=>'pi_dcw_captcha_length','desc'=>'', 'label'=>__('Captcha string length','pi-dcw'),'type'=>'select', 'default'=>'6', 'value'=>array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6)),

            array('field'=>'pi_dcw_captcha_placeholder','desc'=>'', 'label'=>__('Captcha field placeholder','pi-dcw'),'type'=>'text', 'default'=>'Enter the CAPTCHA'),
            array('field'=>'pi_dcw_captcha_blank_error','desc'=>'', 'label'=>__('Captcha can\'t be left empty','pi-dcw'),'type'=>'text', 'default'=>'Captcha cant be left empty'),
            array('field'=>'pi_dcw_captcha_mismatch_error','desc'=>'', 'label'=>__('Captcha can\'t be left empty','pi-dcw'),'type'=>'text', 'default'=>'Captcha does not match, please try again.'),

           
        );
        $this->register_settings();
        
        
    }

    function get_pages(){
        $pages = get_pages( );
        $pages_array = array(""=>__("Select page","pi-dcw"));
        if($pages){
            foreach ( $pages as $page ) {
                $pages_array[$page->ID] = $page->post_title;
            }
        }
        return $pages_array;
    }

    

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $this->tab_name = __('Checkout setting','pi-dcw');
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ) ); ?>">
            <span class="dashicons dashicons-forms"></span> <?php echo esc_html( $this->tab_name ); ?> 
        </a>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  bg-secondary" href="https://www.piwebsolution.com/user-documentation-direct-checkout-plugin/" target="_blank">
           <span class="dashicons dashicons-book"></span> Documentation
        </a>
        <?php
    }

    function tab_content(){
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_dcw($setting, $this->setting_key);
            }
        ?>
        <input type="submit" class="my-3 btn btn-primary btn-md" value="Save Option" />
        </form>
       <?php
    }

    function position(){
        $positions = ['woocommerce_review_order_before_submit', 'woocommerce_review_order_after_submit','woocommerce_review_order_before_payment','woocommerce_checkout_before_order_review','woocommerce_checkout_after_customer_details','woocommerce_after_order_notes','woocommerce_after_checkout_billing_form','woocommerce_before_checkout_billing_form'];

        $position_array = array();

        foreach($positions as $position){
            $position_array[$position] = $position;
        }
        return $position_array;
    }
}

add_action('init', function(){
    new Class_Pi_Dcw_Checkout($this->plugin_name);
});