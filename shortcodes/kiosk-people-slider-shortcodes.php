<?php

/**
 * Kiosk People Slider Shortcode functionality.
 *
 * Provides shortcodes for users to use in Wordpress
 *
 */
namespace Kiosk_WP;

// Avoid direct calls to this file
if ( ! defined( 'KIOSK_WP_VERSION' ) ) {
  header( 'Status: 403 Forbidden' );
  header( 'HTTP/1.1 403 Forbidden' );
  exit();
}

class Kiosk_People_Slider_Shortcodes extends Base_Registrar {
  protected $plugin_slug;
  protected $version;
  protected $gios_url;
  protected $people_slider_helper;

  public function __construct( $people_slider_helper ) {
    $this->plugin_slug                = 'kiosk-people-slider-shortcodes';
    $this->version                    = '0.1';
    $this->people_slider_helper       = $people_slider_helper;
    $this->load_dependencies();
    $this->define_hooks();
  }

  /**
   * @override
   */
  public function load_dependencies() {
  }

  public function define_hooks() {
    $this->add_shortcode( 'kiosk-people-slider', $this, 'kiosk_people_slider' );
  }

  /**
   * [kiosk_people_slider gios_url='url']
   *
   * @param $atts array
   * Generates a <div> tag with people in each category slides through all the
   * categories
   */
  public function kiosk_people_slider( $atts, $content = null ) {
    $atts                   = shortcode_atts(
        array(
          'gios_url'        => 'https://sustainability.asu.edu',
        ),
        $atts
    );
    $this->gios_url           = $atts['gios_url'];
    $persons_images_category  = $this->people_slider_helper
                                      ->get_persons_images_category_keywords();
    $keyword_slug_quote       = $this->parse_content( $content );
    $keyword_slug_quote_image = $this->people_slider_helper->get_content_images(
        $keyword_slug_quote
    );

    $carousel_slider          = $this->get_carousel_slider(
        People_Slider_Helper::get_sliders(
            $persons_images_category,
            $keyword_slug_quote_image,
            $this->gios_url
        )
    );
    $kiosk_people_slider_div = '<div class="kiosk-people-slider">' . $carousel_slider . '</div>';
    return $kiosk_people_slider_div;
  }

  /**
   * parse_content( $content )
   * @param string
   * @return array
   */
  public function parse_content( $content ) {
    $keyword_slug_quote = array();
    if ( empty( $content ) ) {
      return $keyword_slug_quote;
    }
    //split by delimit as <br> or <br/> or <br > or <br /> or <br / >  or < br / >
    $keyword_slug_quote = People_Slider_Helper::prepare_keyword_slug_quote_by_delimit(
        $content,
        '/<\s*br\s*\/?\s*>/'
    );
    return $keyword_slug_quote;
  }

  /**
   * get_carousel_slider( $list_items )
   * creates a carousel slider and returns it for the given items
   * @param array $list_items
   * @return string
   */
  private function get_carousel_slider( $list_items ) {
    $carousel_slider = '';
    $prefix          = 'kiosk-people-slider';
    $layout_template = '<div>%s</div>';
    if ( count( $list_items ) > 0 ) {
      $carousel_slider   = Carosuel_Slider_Helper::generate_carousel_slider( $prefix, $layout_template, $list_items );
    }
    return $carousel_slider;
  }

}