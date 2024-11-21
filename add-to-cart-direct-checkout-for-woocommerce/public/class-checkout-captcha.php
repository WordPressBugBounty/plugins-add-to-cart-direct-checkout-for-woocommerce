<?php 

class Checkout_Captcha {

    static $instance = null;

    private $useGD = false;

    private $width = 200;

    private $height = 50;

    private $fontPath = '';

    private $captchaLength = 6;

    static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {


        if(! $this->is_captcha_enabled() ) return;

        if (extension_loaded('gd')) {
            $this->useGD = 'gd';
        } elseif (extension_loaded('imagick')) {
            $this->useGD = 'imagick';
        }

        if($this->useGD === false) {
            add_action( 'admin_notices', function(){
                ?>
                <div class="error notice">
                    <p><?php esc_html_e( 'Image generation module not installed in your server, make sure to install GD or Imagick library for PHP to use Captcha for checkout page', 'pi-dcw' ); ?></p>
                </div>
                <?php
            } );
            return;
        }

        $this->fontPath = __DIR__.'/ARIAL.TTF';

        $this->captchaLength = $this->get_captcha_length();

        $this->width = $this->captcha_width();

        $position = self::position();

        add_action($position, [$this, 'custom_checkout_captcha_field']);
        
        add_filter( 'woocommerce_checkout_posted_data', array($this, 'postedData') );
        add_action('woocommerce_after_checkout_validation', [$this, 'validate_checkout_captcha_field'],10,2);

        add_action('woocommerce_checkout_order_processed', [$this, 'clear_captcha_session_after_checkout']);

        add_action('wc_ajax_pi_dcw_generate_captcha', [$this, 'send_generated_captcha_image']);
        add_action('wc_ajax_pi_dcw_refresh_captcha', [$this, 'refreshCaptcha']);

        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        
    }

    static function position(){
        $position = get_option('pi_dcw_captcha_position', 'woocommerce_review_order_before_submit');

        return apply_filters('pi_dcw_captcha_position', $position);
    }

    public function custom_checkout_captcha_field($checkout) {

        $placeholder = $this->get_captcha_placeholder();
        $refresh_title = $this->get_refresh_captcha_title();

        echo '<div id="pi_dcw_captcha_container">';
        echo '<div id="pi_dcw_captcha">';
        echo '<input type="text" name="captcha_field" id="captcha_field" class="input-text" required placeholder="'.esc_attr($placeholder).'">';
        echo '<div class="captcha_image_container">';
        echo '<img src="' . esc_url( site_url('?wc-ajax=pi_dcw_generate_captcha') ) . '" alt="CAPTCHA" id="captcha_image">';
        echo '</div>';
        echo '<a href="#" id="refresh_captcha" title="'.esc_attr($refresh_title).'"><img src="'.esc_url(plugin_dir_url( __FILE__ ).'img/refresh.svg').'" id="captcha_refresh_icon">.</a>';
        echo '</div>';
        echo '</div>';
    }

    function postedData($data){
        $data['captcha_field'] = isset($_POST['captcha_field']) ? sanitize_text_field($_POST['captcha_field']) : '';
        return $data;
    }

    public function validate_checkout_captcha_field($data, $errors) {
        $captcha = WC()->session->get('pi_dcw_captcha');

        if ( empty($data['captcha_field'])) {
            $missing_error = $this->get_error_blank_captcha();
            $errors->add('error', $missing_error , ['id' => 'captcha-error']);
            return;
        }

        if (  $data['captcha_field'] !== $captcha ) {
            $mismatch_error = $this->get_error_captcha_mismatch();
            $errors->add('error', $mismatch_error , ['id' => 'captcha-error']);
        }
    }

    public function clear_captcha_session_after_checkout() {
        WC()->session->set('pi_dcw_captcha', null);
    }

    public function send_generated_captcha_image() {
        $this->generate_captcha_image();
        wp_die();
    }

    public function refreshCaptcha()
    {
        // Generate and return a new CAPTCHA image in base64 format
        ob_start();
        $this->generate_captcha_image();
        $imageData = ob_get_contents();
        ob_end_clean();
        echo 'data:image/png;base64,' . base64_encode($imageData);
        wp_die(); // Prevent further execution
    }

    private function generateCaptchaCode()
    {   
        // we removed capital I and small l as they look similar in Arial font
        $characters = $this->get_characters();
        $captchaCode = '';
        for ($i = 0; $i < $this->captchaLength; $i++) {
            $captchaCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $captchaCode;
    }

    private function generate_captcha_image() {
        header('Content-type: image/png');
        
        $captcha_string = $this->generateCaptchaCode();
        
        WC()->session->set('pi_dcw_captcha', $captcha_string);

        if($this->useGD === 'gd') {
            $this->generateCaptchaImageGD($captcha_string);
        } elseif($this->useGD === 'imagick') {
            $this->generateCaptchaImageImagick($captcha_string);
        }
    }

    private function generateCaptchaImageGD($captchaCode)
    {
        // GD-based image generation
        $image = imagecreatetruecolor($this->width, $this->height);
        $bgColor = imagecolorallocate($image, 255, 255, 255); // White background
        imagefill($image, 0, 0, $bgColor);

        // Add noise (random lines)
        for ($i = 0; $i < 10; $i++) {
            $lineColor = imagecolorallocate($image, rand(100, 200), rand(100, 200), rand(100, 200));
            imageline($image, rand(0, $this->width), rand(0, $this->height), rand(0, $this->width), rand(0, $this->height), $lineColor);
        }

        // Add the CAPTCHA text
        $textColor = imagecolorallocate($image, 0, 0, 0); // Black text
        $fontSize = 30;
        $x = 10; // Starting x position
        $character_spacing = 30;
        for ($i = 0; $i < strlen($captchaCode); $i++) {
            $angle = rand(-10, 10); // Random angle
            $y = rand(30, 40); // Random y position
            imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $this->fontPath, $captchaCode[$i]);
            $x += $character_spacing; // Increment x position
        }

        // Output image
        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
    }

    private function generateCaptchaImageImagick($captchaCode)
    {
        // Imagick-based image generation
        $image = new Imagick();
        $image->newImage($this->width, $this->height, new ImagickPixel('white'));

        $this->addNoise($image);
        $this->addCaptchaText($image, $captchaCode);
        $image->swirlImage(20);

        header('Content-Type: image/png');
        $image->setImageFormat('png');
        echo $image;

        $image->clear();
        $image->destroy();
    }

    private function addNoise(Imagick $image)
    {
        $draw = new ImagickDraw();
        for ($i = 0; $i < 10; $i++) {
            $draw->setStrokeColor(new ImagickPixel(sprintf('rgb(%d,%d,%d)', rand(100, 200), rand(100, 200), rand(100, 200))));
            $draw->setStrokeWidth(1);
            $draw->line(rand(0, $this->width), rand(0, $this->height), rand(0, $this->width), rand(0, $this->height));
        }
        $image->drawImage($draw);
    }

    private function addCaptchaText(Imagick $image, $captchaCode)
    {
        $draw = new ImagickDraw();
        $draw->setFillColor(new ImagickPixel('black'));
        $draw->setFont($this->fontPath);
        $draw->setFontSize(30);

        $x = 10;
        $characterSpacing = 30;
        for ($i = 0; $i < strlen($captchaCode); $i++) {
            $angle = rand(-10, 10);
            $y = rand(30, 40);
            $draw->annotation($x, $y, $captchaCode[$i]);
            $x += $characterSpacing;
        }

        $image->drawImage($draw);
    }

    public function enqueueScripts()
    {
        if(!is_checkout()) return;

        $script = "
        jQuery(document).ready(function ($) {
            $('body').on('click','#refresh_captcha', function (e) {
                e.preventDefault();
                jQuery('#pi_dcw_captcha').addClass('loading');
                $.get('" .home_url('?wc-ajax=pi_dcw_refresh_captcha'). "', function (data) {
                    $('#captcha_image').attr('src', data); // Update image src with new data URL
                    jQuery('#pi_dcw_captcha').removeClass('loading');
                    jQuery('#captcha_field').val(''); // Clear the input field
                }).fail(function () {
                    console.error('Error refreshing CAPTCHA');
                    jQuery('#pi_dcw_captcha').removeClass('loading');
                });
            });

            $(document.body).on('checkout_error',  function (event, error) {
                var captchaError = $('ul.woocommerce-error li').filter(function() {
                     return $(this).attr('data-id') === 'captcha-error'; // Check if the li has data-id=\"captcha-error\"
                });

                 if (captchaError.length) {
                    $('#pi_dcw_captcha').addClass('error');
                } else {
                    $('#pi_dcw_captcha').removeClass('error');
                }
            });
        });
        ";

        wp_add_inline_script('jquery', $script);

        // Inline CSS for the loading spinner
        $color_scheme = get_option('pi_dcw_captcha_field_color', '#cccccc');
        $color_scheme_error = get_option('pi_dcw_captcha_field_error_color', '#ff0000');
        $css = "
            :root {
                --captcha_color: $color_scheme;
                --captcha_error_color: $color_scheme_error;
                --captcha_border:5px;
            }

            #pi_dcw_captcha_container{
                display:block;
                width:100%;
                margin-bottom:20px;
                margin-top:20px;
            }

            #pi_dcw_captcha{
                display:grid;
                grid-template-columns: 1fr 200px 50px;
                border:var(--captcha_border, 5px) solid var(--captcha_color, #ccc);
                border-radius:6px;
                max-width:600px;
            }

            @media (max-width: 600px) {
                #pi_dcw_captcha{
                    grid-template-columns: 1fr;
                }
                
                #captcha_field{
                    border-bottom:1px solid var(--captcha_color, #ccc) !important;
                }
            }

            #pi_dcw_captcha.error{
                border:var(--captcha_border, 5px) solid var(--captcha_error_color, #ff0000);
            }

            #pi_dcw_captcha.loading{
                opacity:0.5;
            }

            .captcha_image_container{
                padding:3px;
                text-align:Center;
                border-left:1px solid var(--captcha_color, #ccc);
            }

            #captcha_image{
                margin:auto;
            }

            #captcha_refresh_icon{
                width:30px;
            }

            #refresh_captcha{
                cursor:pointer;
                display:flex;
                align-items:center;
                justify-content:center;
                background:var(--captcha_color, #ccc);
                font-size:0px;
                border-left:1px solid var(--captcha_color, #ccc);
            }

            #pi_dcw_captcha.error #refresh_captcha{
                background:var(--captcha_error_color, #ff0000);
            }


            #captcha_field, #captcha_field:focus-visible, #captcha_field:focus{
                outline: none;
                border:none;
                padding:10px;
            }
        ";
        // Add custom CSS to the checkout page use dummy dependency 
        wp_register_style('pi-dcw-captch-custom-inline-css', false);
        wp_enqueue_style('pi-dcw-captch-custom-inline-css');
        wp_add_inline_style('pi-dcw-captch-custom-inline-css', $css);
    }

    function is_captcha_enabled() {
        $enabled = get_option('pi_dcw_enable_checkout_captcha', 0);
        return !empty($enabled);
    }

    function get_error_blank_captcha() {
        return get_option('pi_dcw_captcha_blank_error', 'Captcha cant be left empty');
    }

    function get_error_captcha_mismatch() {
        return get_option('pi_dcw_captcha_mismatch_error', 'Captcha does not match, please try again.');
    }

    function get_captcha_placeholder() {
        return get_option('pi_dcw_captcha_placeholder', 'Enter the CAPTCHA');
    }

    function get_refresh_captcha_title() {
        return __('Refresh the CAPTCHA','pi-dcw');
    }

    function get_captcha_length() {
        $length = get_option('pi_dcw_captcha_length', 6);
        $length = absint( apply_filters('pi_dcw_captcha_length', $length));
        return $length > 6 || $length < 1 ? 6 : $length;
    }

    function captcha_width() {
        $character_length = $this->get_captcha_length();
        $width = $character_length * 40;
        return $width;
    }

    function get_characters() {
        $type_of_string = get_option('pi_dcw_captcha_characters', 'mix');
        if($type_of_string === 'mix') {
            $characters = 'ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz0123456789';
        } 

        if($type_of_string === 'numbers') {
            $characters = '0123456789';
        }

        if($type_of_string === 'capital_letter') {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        if($type_of_string === 'small_letter') {
            $characters = 'abcdefghijklmnopqrstuvwxyz';
        }


        return apply_filters('pi_dcw_captcha_characters', $characters);
    }
}

// Instantiate the CAPTCHA class
Checkout_Captcha::get_instance();
