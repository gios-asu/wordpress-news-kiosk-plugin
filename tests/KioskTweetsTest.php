<?php

class KioskTweetsTest extends WP_UnitTestCase {
  private $stub = null;
  // @codingStandardsIgnoreStart
  static function setUpBeforeClass() {
    WP_UnitTestCase::setUpBeforeClass();

  }
  function setUp() {
    // Mockup data
    $this->stub = $this->getMock(
        'Kiosk_WP\Kiosk_Tweets_Helper',
        array( 'get_tweets_json' )
    );
    $this->stub->expects( $this->any() )
         ->method( 'get_tweets_json' )
         ->will( $this->returnValue( $this->return_unit_test_data() ) );
  }
  // @codingStandardsIgnoreEnd

  /**
   * To Test Kiosk tweets
   * [kiosk-tweets]
   */
  function test_kiosk_tweets_shortcode() {
    $this->assertTrue( shortcode_exists( 'kiosk-tweets' ) );

    $content = $this->stub->kiosk_tweets( array() );
    $this->assertContains(
        'kiosk-tweets__tweet',
        $content,
        'Should return all current tweets item max default 20'
    );

    $number_of_items = substr_count( $content, '<li' );
    $this->assertLessThanOrEqual(
        8,
        $number_of_items,
        'There should be <= 8 news items'
    );
  }
  function test_kiosk_tweets_shortcode_limit_4() {

    $content = $this->stub->kiosk_tweets( array( 'limit' => 4 ) );
    $this->assertContains(
        'kiosk-tweets__tweet',
        $content,
        'Should return current tweets item'
    );
    $number_of_items = substr_count( $content, '<li' );
    $this->assertLessThanOrEqual(
        4,
        $number_of_items,
        'There should be <= 4 news items'
    );
  }
  /**
  * Creates a mock up data to be used as twitter streaming api response
  * @return string
  */
  function return_unit_test_data() {
    $sample_json = <<<JSON
[{"created_at":"Fri May 15 20:59:29 +0000 2015","id":599318204721205249,"id_str":"599318204721205249","text":"test1","source":"\u003ca href=\"http:\/\/twitter.com\" rel=\"nofollow\"\u003eTwitter Web Client\u003c\/a\u003e","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":100546785,"id_str":"100546785","name":"Nagarjuna","screen_name":"chasethenag420","location":"Bangalore","description":"","url":null,"entities":{"description":{"urls":[]}},"protected":false,"followers_count":27,"friends_count":96,"listed_count":0,"created_at":"Wed Dec 30 17:11:46 +0000 2009","favourites_count":0,"utc_offset":null,"time_zone":null,"geo_enabled":false,"verified":false,"statuses_count":22,"lang":"en","contributors_enabled":false,"is_translator":false,"is_translation_enabled":false,"profile_background_color":"C0DEED","profile_background_image_url":"http:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_image_url_https":"https:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_tile":false,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_link_color":"0084B4","profile_sidebar_border_color":"C0DEED","profile_sidebar_fill_color":"DDEEF6","profile_text_color":"333333","profile_use_background_image":true,"default_profile":true,"default_profile_image":false,"following":false,"follow_request_sent":false,"notifications":false},"geo":null,"coordinates":null,"place":null,"contributors":null,"retweet_count":0,"favorite_count":0,"entities":{"hashtags":[],"symbols":[],"user_mentions":[],"urls":[]},"favorited":false,"retweeted":false,"lang":"en"},{"created_at":"Thu Jun 17 06:27:40 +0000 2010","id":16368735150,"id_str":"16368735150","text":"@samantha_prabhu u have to believe this as pille (Iron tongue) said that BRAZIL AND SPAIN are the strongest teams for this world cup","source":"\u003ca href=\"http:\/\/twitter.com\" rel=\"nofollow\"\u003eTwitter Web Client\u003c\/a\u003e","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":100546785,"id_str":"100546785","name":"Nagarjuna","screen_name":"chasethenag420","location":"Bangalore","description":"","url":null,"entities":{"description":{"urls":[]}},"protected":false,"followers_count":27,"friends_count":96,"listed_count":0,"created_at":"Wed Dec 30 17:11:46 +0000 2009","favourites_count":0,"utc_offset":null,"time_zone":null,"geo_enabled":false,"verified":false,"statuses_count":22,"lang":"en","contributors_enabled":false,"is_translator":false,"is_translation_enabled":false,"profile_background_color":"C0DEED","profile_background_image_url":"http:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_image_url_https":"https:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_tile":false,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_link_color":"0084B4","profile_sidebar_border_color":"C0DEED","profile_sidebar_fill_color":"DDEEF6","profile_text_color":"333333","profile_use_background_image":true,"default_profile":true,"default_profile_image":false,"following":false,"follow_request_sent":false,"notifications":false},"geo":null,"coordinates":null,"place":null,"contributors":null,"retweet_count":0,"favorite_count":0,"entities":{"hashtags":[],"symbols":[],"user_mentions":[{"screen_name":"samantha_prabhu","name":"Samantha Ruth prabhu","id":578616675,"id_str":"578616675","indices":[0,16]}],"urls":[]},"favorited":false,"retweeted":false,"lang":"en"},{"created_at":"Thu Jun 17 06:27:40 +0000 2010","id":16368735150,"id_str":"16368735150","text":"@samantha_prabhu u have to believe this as pille (Iron tongue) said that BRAZIL AND SPAIN are the strongest teams for this world cup","source":"\u003ca href=\"http:\/\/twitter.com\" rel=\"nofollow\"\u003eTwitter Web Client\u003c\/a\u003e","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":100546785,"id_str":"100546785","name":"Nagarjuna","screen_name":"chasethenag420","location":"Bangalore","description":"","url":null,"entities":{"description":{"urls":[]}},"protected":false,"followers_count":27,"friends_count":96,"listed_count":0,"created_at":"Wed Dec 30 17:11:46 +0000 2009","favourites_count":0,"utc_offset":null,"time_zone":null,"geo_enabled":false,"verified":false,"statuses_count":22,"lang":"en","contributors_enabled":false,"is_translator":false,"is_translation_enabled":false,"profile_background_color":"C0DEED","profile_background_image_url":"http:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_image_url_https":"https:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_tile":false,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_link_color":"0084B4","profile_sidebar_border_color":"C0DEED","profile_sidebar_fill_color":"DDEEF6","profile_text_color":"333333","profile_use_background_image":true,"default_profile":true,"default_profile_image":false,"following":false,"follow_request_sent":false,"notifications":false},"geo":null,"coordinates":null,"place":null,"contributors":null,"retweet_count":0,"favorite_count":0,"entities":{"hashtags":[],"symbols":[],"user_mentions":[{"screen_name":"samantha_prabhu","name":"Samantha Ruth prabhu","id":578616675,"id_str":"578616675","indices":[0,16]}],"urls":[]},"favorited":false,"retweeted":false,"lang":"en"},{"created_at":"Thu Jun 17 06:27:40 +0000 2010","id":16368735150,"id_str":"16368735150","text":"@samantha_prabhu u have to believe this as pille (Iron tongue) said that BRAZIL AND SPAIN are the strongest teams for this world cup","source":"\u003ca href=\"http:\/\/twitter.com\" rel=\"nofollow\"\u003eTwitter Web Client\u003c\/a\u003e","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":100546785,"id_str":"100546785","name":"Nagarjuna","screen_name":"chasethenag420","location":"Bangalore","description":"","url":null,"entities":{"description":{"urls":[]}},"protected":false,"followers_count":27,"friends_count":96,"listed_count":0,"created_at":"Wed Dec 30 17:11:46 +0000 2009","favourites_count":0,"utc_offset":null,"time_zone":null,"geo_enabled":false,"verified":false,"statuses_count":22,"lang":"en","contributors_enabled":false,"is_translator":false,"is_translation_enabled":false,"profile_background_color":"C0DEED","profile_background_image_url":"http:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_image_url_https":"https:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_tile":false,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_link_color":"0084B4","profile_sidebar_border_color":"C0DEED","profile_sidebar_fill_color":"DDEEF6","profile_text_color":"333333","profile_use_background_image":true,"default_profile":true,"default_profile_image":false,"following":false,"follow_request_sent":false,"notifications":false},"geo":null,"coordinates":null,"place":null,"contributors":null,"retweet_count":0,"favorite_count":0,"entities":{"hashtags":[],"symbols":[],"user_mentions":[{"screen_name":"samantha_prabhu","name":"Samantha Ruth prabhu","id":578616675,"id_str":"578616675","indices":[0,16]}],"urls":[]},"favorited":false,"retweeted":false,"lang":"en"},{"created_at":"Thu Jun 17 06:27:40 +0000 2010","id":16368735150,"id_str":"16368735150","text":"@samantha_prabhu u have to believe this as pille (Iron tongue) said that BRAZIL AND SPAIN are the strongest teams for this world cup","source":"\u003ca href=\"http:\/\/twitter.com\" rel=\"nofollow\"\u003eTwitter Web Client\u003c\/a\u003e","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":100546785,"id_str":"100546785","name":"Nagarjuna","screen_name":"chasethenag420","location":"Bangalore","description":"","url":null,"entities":{"description":{"urls":[]}},"protected":false,"followers_count":27,"friends_count":96,"listed_count":0,"created_at":"Wed Dec 30 17:11:46 +0000 2009","favourites_count":0,"utc_offset":null,"time_zone":null,"geo_enabled":false,"verified":false,"statuses_count":22,"lang":"en","contributors_enabled":false,"is_translator":false,"is_translation_enabled":false,"profile_background_color":"C0DEED","profile_background_image_url":"http:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_image_url_https":"https:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_tile":false,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_link_color":"0084B4","profile_sidebar_border_color":"C0DEED","profile_sidebar_fill_color":"DDEEF6","profile_text_color":"333333","profile_use_background_image":true,"default_profile":true,"default_profile_image":false,"following":false,"follow_request_sent":false,"notifications":false},"geo":null,"coordinates":null,"place":null,"contributors":null,"retweet_count":0,"favorite_count":0,"entities":{"hashtags":[],"symbols":[],"user_mentions":[{"screen_name":"samantha_prabhu","name":"Samantha Ruth prabhu","id":578616675,"id_str":"578616675","indices":[0,16]}],"urls":[]},"favorited":false,"retweeted":false,"lang":"en"},{"created_at":"Thu Jun 17 06:27:40 +0000 2010","id":16368735150,"id_str":"16368735150","text":"@samantha_prabhu u have to believe this as pille (Iron tongue) said that BRAZIL AND SPAIN are the strongest teams for this world cup","source":"\u003ca href=\"http:\/\/twitter.com\" rel=\"nofollow\"\u003eTwitter Web Client\u003c\/a\u003e","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":100546785,"id_str":"100546785","name":"Nagarjuna","screen_name":"chasethenag420","location":"Bangalore","description":"","url":null,"entities":{"description":{"urls":[]}},"protected":false,"followers_count":27,"friends_count":96,"listed_count":0,"created_at":"Wed Dec 30 17:11:46 +0000 2009","favourites_count":0,"utc_offset":null,"time_zone":null,"geo_enabled":false,"verified":false,"statuses_count":22,"lang":"en","contributors_enabled":false,"is_translator":false,"is_translation_enabled":false,"profile_background_color":"C0DEED","profile_background_image_url":"http:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_image_url_https":"https:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_tile":false,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_link_color":"0084B4","profile_sidebar_border_color":"C0DEED","profile_sidebar_fill_color":"DDEEF6","profile_text_color":"333333","profile_use_background_image":true,"default_profile":true,"default_profile_image":false,"following":false,"follow_request_sent":false,"notifications":false},"geo":null,"coordinates":null,"place":null,"contributors":null,"retweet_count":0,"favorite_count":0,"entities":{"hashtags":[],"symbols":[],"user_mentions":[{"screen_name":"samantha_prabhu","name":"Samantha Ruth prabhu","id":578616675,"id_str":"578616675","indices":[0,16]}],"urls":[]},"favorited":false,"retweeted":false,"lang":"en"},{"created_at":"Thu Jun 17 06:27:40 +0000 2010","id":16368735150,"id_str":"16368735150","text":"@samantha_prabhu u have to believe this as pille (Iron tongue) said that BRAZIL AND SPAIN are the strongest teams for this world cup","source":"\u003ca href=\"http:\/\/twitter.com\" rel=\"nofollow\"\u003eTwitter Web Client\u003c\/a\u003e","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":100546785,"id_str":"100546785","name":"Nagarjuna","screen_name":"chasethenag420","location":"Bangalore","description":"","url":null,"entities":{"description":{"urls":[]}},"protected":false,"followers_count":27,"friends_count":96,"listed_count":0,"created_at":"Wed Dec 30 17:11:46 +0000 2009","favourites_count":0,"utc_offset":null,"time_zone":null,"geo_enabled":false,"verified":false,"statuses_count":22,"lang":"en","contributors_enabled":false,"is_translator":false,"is_translation_enabled":false,"profile_background_color":"C0DEED","profile_background_image_url":"http:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_image_url_https":"https:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_tile":false,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_link_color":"0084B4","profile_sidebar_border_color":"C0DEED","profile_sidebar_fill_color":"DDEEF6","profile_text_color":"333333","profile_use_background_image":true,"default_profile":true,"default_profile_image":false,"following":false,"follow_request_sent":false,"notifications":false},"geo":null,"coordinates":null,"place":null,"contributors":null,"retweet_count":0,"favorite_count":0,"entities":{"hashtags":[],"symbols":[],"user_mentions":[{"screen_name":"samantha_prabhu","name":"Samantha Ruth prabhu","id":578616675,"id_str":"578616675","indices":[0,16]}],"urls":[]},"favorited":false,"retweeted":false,"lang":"en"},{"created_at":"Thu Jun 17 06:27:40 +0000 2010","id":16368735150,"id_str":"16368735150","text":"@samantha_prabhu u have to believe this as pille (Iron tongue) said that BRAZIL AND SPAIN are the strongest teams for this world cup","source":"\u003ca href=\"http:\/\/twitter.com\" rel=\"nofollow\"\u003eTwitter Web Client\u003c\/a\u003e","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":100546785,"id_str":"100546785","name":"Nagarjuna","screen_name":"chasethenag420","location":"Bangalore","description":"","url":null,"entities":{"description":{"urls":[]}},"protected":false,"followers_count":27,"friends_count":96,"listed_count":0,"created_at":"Wed Dec 30 17:11:46 +0000 2009","favourites_count":0,"utc_offset":null,"time_zone":null,"geo_enabled":false,"verified":false,"statuses_count":22,"lang":"en","contributors_enabled":false,"is_translator":false,"is_translation_enabled":false,"profile_background_color":"C0DEED","profile_background_image_url":"http:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_image_url_https":"https:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png","profile_background_tile":false,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/1098848936\/nag_normal.jpg","profile_link_color":"0084B4","profile_sidebar_border_color":"C0DEED","profile_sidebar_fill_color":"DDEEF6","profile_text_color":"333333","profile_use_background_image":true,"default_profile":true,"default_profile_image":false,"following":false,"follow_request_sent":false,"notifications":false},"geo":null,"coordinates":null,"place":null,"contributors":null,"retweet_count":0,"favorite_count":0,"entities":{"hashtags":[],"symbols":[],"user_mentions":[{"screen_name":"samantha_prabhu","name":"Samantha Ruth prabhu","id":578616675,"id_str":"578616675","indices":[0,16]}],"urls":[]},"favorited":false,"retweeted":false,"lang":"en"}]
JSON;
    return $sample_json;
  }
}