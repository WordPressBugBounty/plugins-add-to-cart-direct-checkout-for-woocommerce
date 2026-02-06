<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Pi_Dcw_Quick_View{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'quickview';

    private $tab_name = "Quick View";

    private $setting_key = 'dcw_quick_view_setting';

    private $pages =array();
    
    private $pro_version = false;

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;
        
        $this->active_tab = (isset($_GET['tab'])) ? sanitize_text_field($_GET['tab']) : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

        $this->settings = array(
            array('field'=>'quickview', 'class'=> 'bg-dark opacity-75 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Basic Setting Quick View','pi-dcw'), 'type'=>'setting_category'),

            array('field'=>'pi_dcw_enable_quick_view_button','desc'=>'This will show a quick view button on the product archive page, or category page', 'label'=>__('Enable Quick View button','pi-dcw'),'type'=>'switch', 'default'=>0),
            array('field'=>'pi_dcw_quick_view_text','desc'=>__('Quick view button text','pi-dcw'), 'label'=>__('Text shown inside the quick view button','pi-dcw'),'type'=>'text','default'=>'Quick View'),

            array('field'=>'quickview', 'class'=> 'bg-dark opacity-75 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Quick View box size','pi-dcw'), 'type'=>'setting_category'),

            array('field'=>'pi_dcw_quick_view_box_width','desc'=>__('Popup box size','pi-dcw'), 'label'=>__('Quick view popup box size','pi-dcw'),'type'=>'select','value'=> array('50'=>'50%', '55'=>'55%', '60'=>'60%', '65'=>'65%', '70'=>'70%', '75'=>'75%', '80'=>'80%', '85'=>'85%', '90'=>'90%', '100'=>'100%'), 'default'=>'70', 'pro'=>true),
            array('field'=>'pi_dcw_quick_view_box_image_width','desc'=>__('Product image width in the popup box ','pi-dcw'), 'label'=>__('Product image width','pi-dcw'),'type'=>'select','value'=> array('0'=>'0%', '20'=>'20%', '25'=>'25%', '30'=>'30%', '35'=>'35%', '40'=>'45%', '50'=>'50%', '55'=>'55%', '60'=>'65%'), 'default'=>'30', 'pro'=>true),

            array('field'=>'quickview', 'class'=> 'bg-dark opacity-75 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Button Design','pi-dcw'), 'type'=>'setting_category'),

            array('field'=>'pi_dcw_quick_view_bg_color','desc'=>__('Background color of Quick View button','pi-dcw'), 'label'=>__('Background color','pi-dcw'),'type'=>'color', 'default'=>'#ee6443'),
            array('field'=>'pi_dcw_quick_view_text_color','desc'=>__('Text color of Quick View button','pi-dcw'), 'label'=>__('Text color','pi-dcw'),'type'=>'color', 'default'=>'#ffffff'),

            array('field'=>'quickview', 'class'=> 'bg-dark opacity-75 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Quick view popup box design','pi-dcw'), 'type'=>'setting_category'),

            array('field'=>'pi_dcw_quick_view_modal_bg_color','desc'=>__('Background color of Quick View popup box','pi-dcw'), 'label'=>__('Background color','pi-dcw'),'type'=>'color', 'default'=>'#FFFFFF', 'pro'=>true),
            array('field'=>'pi_dcw_quick_view_modal_text_color','desc'=>__('Text color of Quick View box content, this affect the color of product title and paragraph content','pi-dcw'), 'label'=>__('Text color','pi-dcw'),'type'=>'color', 'default'=>'#000000', 'pro'=>true),
            array('field'=>'pi_dcw_quick_view_modal_close_bg_color','desc'=>__('Close popup background color','pi-dcw'), 'label'=>__('Close button background color','pi-dcw'),'type'=>'color', 'default'=>'#000000', 'pro'=>true),
            array('field'=>'pi_dcw_quick_view_modal_close_color','desc'=>__('Close popup icon color','pi-dcw'), 'label'=>__('Close button icon color','pi-dcw'),'type'=>'color', 'default'=>'#ffffff', 'pro'=>true),
            array('field'=>'pi_dcw_quick_view_light_box','desc'=>__('Enable light box for the product image in quick view','pi-dcw'), 'label'=>__('Enable light box','pi-dcw'),'type'=>'switch', 'default'=>0, 'pro'=>true),
            array('field'=>'pi_dcw_quick_view_modal_padding','desc'=>__('Popup box padding','pi-dcw'), 'label'=>__('Popup box padding in terms of (px)','pi-dcw'),'type'=>'number','min'=>0, 'default'=>10, 'pro'=>true),
           
            array('field'=>'pi_dcw_quick_view_modal_open_animation','desc'=>__('Popup box Open animation','pi-dcw'), 'label'=>__('Animation when opening popup','pi-dcw'),'type'=>'select','value'=> array('fadeInDown'), 'default'=>'fadeInDown','pro'=>true),
        );
        $this->register_settings();

        if(PISOL_DCW_DELETE_SETTING){
            $this->delete_settings();
        }
    }

   
    

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function delete_settings(){
        foreach($this->settings as $setting){
            delete_option( $setting['field'] );
        }
    }

    function tab(){
        $this->tab_name = __('Quick View','pi-dcw');
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ) ); ?>">
           <span class="dashicons dashicons-visibility"></span> <?php echo esc_html( $this->tab_name ); ?> 
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

   
}

add_action('init', function(){
    new Class_Pi_Dcw_Quick_View($this->plugin_name);
});