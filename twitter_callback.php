<?php

function getTweet(){
    $tweet = 'Hot Take: ';
    //set of perameters
    //
    $options = array(
        $noun = array(
            $politician = array(
                "Donald J Trump",
                "Hillary Clinton",
                "Ted Cruz",
                "David Cameron",
                "Davey Cameron",
                "Nicola Sturgeon",
                "Boris Johnson",
                "Micheal Gove",
                "Nick Clegg",
                "Paul Nuttel",
                "Nigel Farage",
                "Vladimir Putin",
                "Francioise Holland",
                "Damian Hinds",
                "Angela Merkel",
                "Malcolm Turnbull",
                "Jeremy Corbyn",
                "Bernie Sanders"
            ),

            $things = array(
                "Jaffa cakes",
                "Baseball bats",
                "Video Games",
                "Computers",
                "Frogs",
                "Music songs that are too loud",
                "???",
                "Pens"

            )
        ),

        $is_a_thing = array(
            "unfit for office",
            "a liberal cuck ball",
            "the zodiac killer",
            "going to kill us all",
            "secretly a lizard",
            "bad",
            "a fruit",
            "???",
            "coming",
            "a sandwich",
            "the Minecraft of sex",
            "good actually",
            "actually hozier",
            "the real president"
        )
        
    );

    $polit_or_thing = rand(0,1);
    $first_part_index = $options[0][$polit_or_thing];
    $first_part_text = $first_part_index[rand(0,sizeof($first_part_index) - 1)];

    $second_part_text = $options[1][rand(0,sizeof($options[1]) - 1)];

    $connective = ' is ';
    if($polit_or_thing == 1){
        $connective = ' are ';
    }

    $tweet = print_r("Hot Take: ", true) . print_r($first_part_text, true) . print_r($connective, true) . print_r($second_part_text, true) . print_r(" #HotTakeBot http://dev.colinroitt.uk/spicytake", true);

    return print_r($tweet, true);
}

require 'twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

session_start();

$config = require_once 'config.php';

// get and filter oauth verifier
$oauth_verifier = filter_input(INPUT_GET, 'oauth_verifier');

// check tokens
if (empty($oauth_verifier) || empty($_SESSION['oauth_token']) || empty($_SESSION['oauth_token_secret'])) {
    // something's missing, go and login again
    header('Location: ' . $config['url_login']);
}

// connect with application token
$connection = new TwitterOAuth(
    $config['consumer_key'],
    $config['consumer_secret'],
    $_SESSION['oauth_token'],
    $_SESSION['oauth_token_secret']
);

// request user token
$token = $connection->oauth(
    'oauth/access_token', [
        'oauth_verifier' => $oauth_verifier
    ]
);

// connect with user token
$twitter = new TwitterOAuth(
    $config['consumer_key'],
    $config['consumer_secret'],
    $token['oauth_token'],
    $token['oauth_token_secret']
);

$user = $twitter->get('account/verify_credentials');

// if something's wrong, go and log in again
if(isset($user->error)) {
    header('Location: ' . $config['url_login']);
}

$tweet_to_post = getTweet();

// post a tweet
$status = $twitter->post(
    "statuses/update", [
        "status" => $tweet_to_post
    ]
);

echo ('Created new status successfully <br/>' . $tweet_to_post);
echo("<br/><a href = 'http://dev.colinroitt.uk/spicytake'><h3>Link Home</h3></a>");

?>