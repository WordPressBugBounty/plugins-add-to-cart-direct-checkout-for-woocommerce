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
                            <div class="col-12 col-sm-2 py-2">
                                    <a href="https://www.piwebsolution.com/" target="_blank"><img class="img-fluid ml-2" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>img/pi-web-solution.png"></a>
                            </div>
                            <div class="col-12 col-sm-10 d-flex text-center small">
                                <?php do_action($this->plugin_name.'_tab'); ?>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
            <div class="pisol-row">
                <div class="col-12">
                <div class="bg-light border pl-3 pr-3 pb-3 pt-0">
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
        <div class="col-12 col-sm-12 col-md-4 pt-3">

            <div class="bg-dark text-light text-center mb-3">
                <a href="<?php echo esc_url( PI_DCW_BUY_URL ); ?>" target="_blank">
                    <?php new pisol_promotion('add_to_cart_installation_date'); ?>
                </a>
            </div>
            
           <div class="pi-shadow">
                <div class="pisol-row justify-content-center">
                    <div class="col-md-7 col-sm-12">
                        <div class="p-2  text-center">
                            <img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ )); ?>img/bg.svg">
                        </div>
                    </div>
                </div>
                <div class="text-center py-2">
                    <a class="btn btn-success btn-sm text-uppercase mb-2 " href="<?php echo esc_url(PI_DCW_BUY_URL); ?>&utm_ref=top_link" target="_blank">Buy Now !!</a>
                    <a class="btn btn-sm mb-2 btn-secondary text-uppercase" href="https://websitemaintenanceservice.in/dcw_demo/" target="_blank">Try Demo</a>
                </div>
                <h2 id="pi-banner-tagline" class="mb-0">Get Pro for <?php echo esc_html(PI_DCW_PRICE); ?> Only</h2>
                <div class="inside mt-2">
                    <ul class="text-left pisol-pro-feature-list">
                    <li >Our Redirect also works with <span class="font-weight-bold text-primary">Ajax add to cart</span> button</li>
                    <li class="border-top  mb-0">
                    <span class="font-weight-bold text-primary">Make Buy now button work like Amazon.com buy now button</span>
                    </li>
                    <li class="border-top  mb-0">Set <span class="font-weight-bold text-primary">custom redirect on each product</span>, you can set different redirect for each <span class="font-weight-bold text-primary">variation product</span> as well</li>
                    <li class="border-top  mb-0"><span class="font-weight-bold text-primary">Product Overwrite</span> for redirect setting</li>
                    <li class="border-top  mb-0"><span class="font-weight-bold text-primary">Disable redirect</span> for an specific product</li>
                    <li class="border-top  mb-0"><span class="font-weight-bold text-primary">Enable redirect</span> for an only specific product</li>
                    <li class="border-top border-top">Set different <span class="font-weight-bold text-primary">Redirect page</span> for a specific product</li>
                    <li class="border-top border-top"><span class="font-weight-bold text-primary">Modify the label</span> of Buy now / quick purchase button</li>
                    <li class="border-top border-top"><span class="font-weight-bold text-primary">Change the position</span> of the buy now / quick purchase button</li>
                    <li class="border-top border-top"><span class="font-weight-bold text-primary">Remove other product from cart</span> when product is added to cart by Buy now button</li>
                    <li class="border-top border-top"><span class="font-weight-bold text-primary">Change redirect page</span> for buy now / quick purchase button</li>
                    <li class="border-top border-top"><span class="font-weight-bold text-primary">Disable buy now button</span> for a specific product</li>
                    <li class="border-top border-top"><span class="font-weight-bold text-primary">Customize the color/size</span> of the Quick view module box</li>
                    <li class="border-top border-top">Disable <span class="font-weight-bold text-primary">"Ship to a different address?"</span></li>
                    <li class="border-top border-top">Remove field from the <span class="font-weight-bold text-primary">billing address</span></li>
                    <li class="border-top border-top">Remove field from the <span class="font-weight-bold text-primary">shipping address</span></li>
                    <li class="border-top border-top">Set any page as <span class="font-weight-bold text-primary">order success page</span></li>
                    <li class="border-top border-top">Set different <span class="font-weight-bold text-primary">order success page for each product</span></li>
                    </ul>
                    <div class="text-center pb-3">
                    <a class="btn btn-primary btn-md mt-2 mb-2" href="<?php echo esc_url( PI_DCW_BUY_URL ); ?>" target="_blank">BUY PRO VERSION</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php
    }

    function isWeekend() {
        return (date('N', strtotime(date('Y/m/d'))) >= 6);
    }

}