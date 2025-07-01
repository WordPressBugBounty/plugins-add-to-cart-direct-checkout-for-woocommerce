<?php

class Pi_Dcw_Menu{

    public $plugin_name;
    public $version;
    public $menu;
    
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
        add_action($this->plugin_name.'_promotion', array($this,'promotion'));

        
    }

    function plugin_menu(){
        
        $this->menu = add_submenu_page(
            'woocommerce',
            __( 'Direct Checkout', 'pi-dcw' ),
            __( 'Direct Checkout', 'pi-dcw' ),
            'manage_options',
            'pi-dcw',
            array($this, 'menu_option_page'),
            6
        );

        add_action("load-".$this->menu, array($this,"bootstrap_style"));
 
    }

    public function bootstrap_style() {
        wp_enqueue_script( $this->plugin_name."_quick_save", plugin_dir_url( __FILE__ ) . 'js/pisol-quick-save.js', array('jquery'), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name."_bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );

	}

    function menu_option_page(){
        ?>
        <div class="bootstrap-wrapper">
        <div class="pisol-container mt-2">
            <div class="pisol-row">
                    <div class="col-12">
                        <div class='bg-dark'>
                        <div class="pisol-row">
                            <div class="col-12 col-sm-2 py-2 d-flex align-items-center justify-content-center ">
                                    <a href="https://www.piwebsolution.com/" target="_blank"><img  id="pi-logo" class="img-fluid ml-2" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>img/pi-web-solution.png"></a>
                            </div>
                            <div class="col-12 col-sm-10 d-flex text-center small">
                                <nav id="pisol-navbar" class="navbar navbar-expand-lg navbar-light mr-0 ml-auto">
                                    <div>
                                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                            <?php do_action($this->plugin_name.'_tab'); ?>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
            <div class="pisol-row">
                <div class="col-12">
                <div class="bg-light border pl-3 pr-3 pt-0">
                    <div class="pisol-row">
                        <div class="col">
                        <?php do_action($this->plugin_name.'_tab_content'); ?>
                        </div>
                        <?php do_action($this->plugin_name.'_promotion'); ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </div>
        <?php
    }

    function promotion(){
        ?>
        <?php if(  !pi_dcw_pro_check() ) : ?>
        <div class="col-12 col-sm-12 col-md-4 pt-3 border-left">
            
           <div class="pi-shadow px-3 py-3 ">
                <h2 id="pi-banner-tagline" class="mb-0 mt-3" style="color:#ccc !important;">
                        <span class="d-block mb-4">⭐️⭐️⭐️⭐️⭐️</span>
                        <span class="d-block mb-2">🚀 Trusted by <span style="color:#fff;">10,000+</span> WooCommerce Stores</span>
                        <span class="d-block mb-2">Rated <span style="color:#fff;">4.9/5</span> – Users love it</span>
                </h2>
                <div class="inside mt-4">
                    <ul class="text-left pisol-pro-feature-list mb-4">
                    <li>✓ Works with Ajax Add to Cart button</li>
                    <li>✓ Amazon-style Buy Now button behavior</li>
                    <li>✓ Set custom redirects per product and variation</li>
                    <li>✓ Product-level override for redirect rules</li>
                    <li>✓ Disable redirect for specific products</li>
                    <li>✓ Enable redirect only for selected products</li>
                    <li>✓ Set different redirect pages per product</li>
                    <li>✓ Customize Buy Now button label</li>
                    <li>✓ Reposition Buy Now / Quick Purchase button</li>
                    <li>✓ Auto-remove other cart items when using Buy Now</li>
                    <li>✓ Change redirect page for Buy Now / Quick Purchase</li>
                    <li>✓ Disable Buy Now button per product</li>
                    <li>✓ Customize size & color of Quick View popup</li>
                    <li>✓ Disable "Ship to a different address?" option</li>
                    <li>✓ Remove fields from billing address</li>
                    <li>✓ Remove fields from shipping address</li>
                    <li>✓ Set any page as order success page</li>
                    <li>✓ Set unique success page per product</li>
                    </ul>

                    <h4 class="pi-bottom-banner">💰 Just <?php echo esc_html(PI_DCW_PRICE); ?></h4>
                    <h4 class="pi-bottom-banner">🔥 Unlock all features and grow your sales!</h4>

                    <div class="text-center pb-3">
                    <a class="btn btn-primary btn-lg mt-2 mb-2" href="<?php echo esc_url( PI_DCW_BUY_URL ); ?>" target="_blank">🔓 Unlock Pro Now – Limited Time Price!</a>
                    </div>
                </div>
            </div>

            <div class="text-light text-center my-3">
                <a href="<?php echo esc_url( PI_DCW_BUY_URL ); ?>" target="_blank">
                    <?php new pisol_promotion('add_to_cart_installation_date'); ?>
                </a>
            </div>

        </div>
        <?php endif; ?>
        <?php
    }

    function isWeekend() {
        return (date('N', strtotime(date('Y/m/d'))) >= 6);
    }

}