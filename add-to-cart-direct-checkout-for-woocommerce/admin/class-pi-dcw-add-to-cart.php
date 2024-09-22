<?php

class Class_Pi_Dcw_AddToCart{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'add-to-cart';

    private $tab_name = "Add To Cart";

    private $setting_key = 'dcw_add_to_cart_settting';

    private $pages =array();

    public $tab;

   
    
    private $pro_version = false;

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

        $this->settings = array(
            array('field'=>'sunday', 'class'=> 'bg-secondary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Add to Cart button','pi-dcw'), 'type'=>'setting_category'),

            array('field'=>'pi_dcw_change_add_to_cart','desc'=>__('Do you want to change the button text of add to cart button','pi-dcw'), 'label'=>__('Change Add to cart button text','pi-dcw'),'type'=>'switch', 'default'=>0),

            array('field'=>'pi_dcw_add_to_cart_text','desc'=>__('This text will be shown inside add to cart button','pi-dcw'), 'label'=>__('Add to cart button text','pi-dcw'),'type'=>'text', 'default'=>__('Add to cart', 'woocommerce')),

            array('field'=>'pi_dcw_select_option_text','desc'=>__('This text will be shown on the archive page for the variable product, leave blank if you want to use default text','pi-dcw'), 'label'=>__('Select options button text','pi-dcw'),'type'=>'text', 'default'=>""),

            array('field'=>'pi_dcw_read_more_text','desc'=>'This text will be shown on archive page for the product when the product is out of stock, leave blank if you want to use default text', 'label'=>__('Read more button text','pi-dcw'),'type'=>'text', 'default'=>""),
            array('field'=>'pi_dcw_retain_utm_parameter','desc'=>'By default WooCommerce removes UTM parameters so by enabling this option you can Retain UTM parameter in the add-to-cart url', 'label'=>__('Retain UTM parameter in add-to-cart url','pi-dcw'),'type'=>'switch', 'default'=> 1),
            
           
        );
        $this->register_settings();
        
        /**
         * change add to cart text in single product page
         */
        add_filter('woocommerce_product_single_add_to_cart_text', array($this, 'add_product_single'), 10, 2);
        /**
         * change add to cart text in product loop
         */
        add_filter('woocommerce_product_add_to_cart_text', array($this, 'add_product_loop'), 10, 2);
    }

   
    

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $this->tab_name = __('Add To Cart','pi-dcw');
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo esC_url( admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ) ); ?>">
            <?php echo esc_html( $this->tab_name ); ?> 
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
        <input type="submit" class="mt-3 btn btn-primary btn-sm" value="Save Option" />
        </form>
       <?php
    }

    function add_product_single($text, $product){
        return $this->add_product_text($text, $product, 'single');
    }

    function add_product_loop($text, $product){
        return $this->add_product_text($text, $product, 'loop');
    }

    function add_product_text($text, $product, $caller='single') {
        $change = get_option( 'pi_dcw_change_add_to_cart', 0);
        if (1 == get_option( 'pi_dcw_change_add_to_cart', 0)){
            if( $caller == 'loop') {
                if($product->is_type('variable')){
                    $saved = esc_html(get_option( 'pi_dcw_select_option_text', __('Select Options','woocommerce')));
                    if($saved != ""){
                        $text = $saved;
                    }
                }else{
                    if($product->is_in_stock()){
                        $saved = esc_html(get_option( 'pi_dcw_add_to_cart_text', __('Add to cart','woocommerce')));
                        if($saved != ""){
                            $text = $saved;
                        }
                    }else{
                        $saved = esc_html(get_option( 'pi_dcw_read_more_text', __('Read More','woocommerce')));
                        if($saved != ""){
                            $text = $saved;
                        }
                    }
                }
            }else{
                $saved = esc_html(get_option( 'pi_dcw_add_to_cart_text', __('Add to cart','woocommerce')));
                if($saved != ""){
                    $text = $saved;
                }
            }
        }
  
        return $text;
      }
    
}

new Class_Pi_Dcw_AddToCart($this->plugin_name);