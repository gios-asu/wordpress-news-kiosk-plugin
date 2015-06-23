<?php

/**
 * Kiosk Tweets Helper
 *
 */

namespace Kiosk_WP;


/**
 * Helper file to get tweets for kiosk-tweets shortcode
 * and lives out of wordpress
 */
class Kiosk_Tweets_Helper {
  protected $localsettings    = array();
  protected $limit;
  protected $query_string;
  protected $handle;
  public $request_not_from_wp = false;
  public function __construct() {
    $this->load_dependencies();
  }
  /**
   * @override
   */
  public function load_dependencies() {
    // file must be present to include account settings;
    if ( function_exists( 'plugin_dir_path' ) ) {
      if ( file_exists( plugin_dir_path( __FILE__ ) . '../localsettings.php' ) ) {
        require( plugin_dir_path( __FILE__ ) . '../localsettings.php' );
        $this->localsettings = $localsettings;
      }
    } else {
      //$plugin_path = $_SERVER['DOCUMENT_ROOT'] . dirname( $_SERVER['PHP_SELF'] ).'/../';
      $document_root  = filter_input( INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_STRING );
      $php_self       = filter_input( INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_STRING );
      $plugin_path    = $document_root . dirname( $php_self ) . '/../';
      if ( file_exists( $plugin_path . '../localsettings.php' ) ) {
        require( $plugin_path . '../localsettings.php' );
        $this->localsettings = $localsettings;
      }
      $this->request_not_from_wp = true ;
    }
  }

  /**
   * kiosk_parse_tweets( $decode, $limit )
   * Accepts the json formatted string and the limit on number of tweets
   * Creates a div block with all the tweets and by converting the hash tags and @ and urls to hyperlinks
   * returns html formated string. If retweeted by some one it displays the original tweet by the user and who retweeted.
   * @return array with profile_pic relative_date_time actual_date_time full_name screen_name
   * text retweet_link retweet_by
   */
  private function kiosk_parse_tweets( $decode, $limit ) {
    $kiosk_tweet_items = array();
    /* array will have 'statuses' as column name in case of search api to read actual
      tweets take data from statutses column
      For user timeline we do not have statuses column so read data as it comes form api */
    if ( array_key_exists( 'statuses', $decode ) ) {
      $decode = $decode['statuses'];
    }
    for ( $i = 0; $i < count( $decode ) && $i < $limit ; $i++ ) {
      $twitter_api_helper   = new \Kiosk_WP\Twitter_Api_Helper();
      $kiosk_tweet_items[]  = $twitter_api_helper->extract_tweet_details( $decode[ $i ], 'kiosk-tweets__tweet__link' );
    }
    return $kiosk_tweet_items;
  }
  /**
   * kiosk_tweets_block( $kiosk_tweet_items )
   * creates a div block with the tweets passed to it
   * @param array with profile_pic relative_date_time actual_date_time full_name screen_name
   * text retweet_link retweet_by
   * @return string
   */
  private function kiosk_tweets_block( $kiosk_tweet_items ) {
    $div_start   = <<<HTML
    <div class="kiosk-tweets__timeline__title">
       <b class="kiosk-tweets__timeline__title__text">Tweets</b>
       <p class="kiosk-tweets__timeline__title__logo"  title="Twitter" target="_blank">Twitter</p>
    </div>
    <div id="kiosk_tweets_scrollContainer" class="kiosk-tweets__container">
      <ul class="kiosk-tweets__tweets" id="kiosk-tweets__tweets">
HTML;
    $item_template     = <<<HTML
        <li class="kiosk-tweets__tweets__tweet">
          <div class="kiosk-tweets__tweet__avatar">
              <img src="%s" class="kiosk-tweets__tweet__avatar__image img-responsive center-block" alt="">
          </div>
          <div class="kiosk-tweets__tweet__details">
            <div class="kiosk-tweets__tweet__details__permalink">
              <div class="kiosk-tweets__tweet__details__tweet-time" data-actual-time="%s">%s</div>
            </div>
            <div class="kiosk-tweets__tweet__details__header">
               <div class="kiosk-tweets__tweet__details__header__full-name">%s</div>
               <div class="kiosk-tweets__tweet__details__header__user-name">@%s</div>
            </div>
            <div>
              <div class="kiosk-tweets__tweet__details__text"> %s </div> 
              <div>%s</div>
            </div>
          </div>
        </li>
HTML;
    $retweet_template  = <<<HTML
        <div class="kiosk-tweets__tweet__retweet">
          <i class="kiosk-tweets__tweet__retweet__icon"></i>
          Retweeted by
          <a target="_blank" href="%s" class="kiosk-tweets__tweet__link"> %s </a>
        </div>        
HTML;
    $div_end = '</ul></div>';
    $tweet_items  = '';
    foreach ( $kiosk_tweet_items as $item ) {
      $retweet  = '';
      if ( ! empty( $item['retweet_by'] ) ) {
        $retweet = sprintf(
            $retweet_template,
            $item['retweet_link'],
            $item['retweet_by']
        );
      }
      $tweet_items .= sprintf(
          $item_template,
          $item['profile_pic'],
          $item['actual_date_time'],
          $item['relative_date_time'],
          $item['full_name'],
          $item['screen_name'],
          $item['text'],
          $retweet
      );
    }

    return $div_start . $tweet_items . $div_end;
  }
  /**
   * kiosk_tweets( $atts, $content = null )
   * sets tweets limit, search string, handle  and request to tweets helper
   * to get the data and creates html to return
   * @param array
   * @return string
   */
  public function kiosk_tweets( $atts, $content = null ) {

    $this->limit          = array_key_exists( 'limit', $atts )
                                ? $atts['limit'] : '20';
    $this->query_string   = array_key_exists( 'query_string', $atts )
                                ? $atts['query_string'] : '@asugreen';
    $this->handle  = array_key_exists( 'handle', $atts )
                                ? $atts['handle'] : '';
    $json = $this->get_tweets_json();
    if ( empty( $json ) ){
      if ( $this->request_not_from_wp ){
        $kiosk_tweets_div = '';
      } else {
        $kiosk_tweets_div = '<div class="kiosk-tweets">Twitter API Errored</div>';
      }
    } else {
      $json = Json_Decode_Helper::remove_unwanted_chars( $json );
      $decode = json_decode( $json, true ); //getting the file content as array

      if ( $decode != null && json_last_error( ) === JSON_ERROR_NONE ) {
        if ( array_key_exists( 'errors' , $decode ) && array_key_exists( 0 , $decode['errors'] ) && array_key_exists( 'message' , $decode['errors'][0] ) ) {
          $kiosk_tweets_div   = '<div class="kiosk-tweets">' . $decode['errors'][0]['message']. '</div>';
        } else {
          $kiosk_tweet_items  = $this->kiosk_parse_tweets( $decode, $this->limit );
          $kiosk_tweets_div   = '<div class="kiosk-tweets">' .  $this->kiosk_tweets_block( $kiosk_tweet_items ). '</div>';
        }
      } else {
        $kiosk_tweets_div   = '';
        error_log( basename( __FILE__ ) .' Twitter API error: JSON ' . json_last_error_msg() . "\n" );
      }
    }
    return $kiosk_tweets_div;
  }
  /**
   * get_tweets_json() is being used as part of unit test cases to mock
   * up data it written separately.
   * @return JSON object
   */
  public function get_tweets_json() {
    $twitter_api_helper = new \Kiosk_WP\Twitter_Api_Helper();
    $json               = $twitter_api_helper->tweets_json(
        $this->localsettings['oauth_access_token'],
        $this->localsettings['oauth_access_token_secret'],
        $this->localsettings['consumer_key'],
        $this->localsettings['consumer_secret'],
        $this->query_string,
        $this->limit,
        $this->handle
    );

    return $json;
  }
}
