<?php
/**
 * Short description for amazon_request.php
 *
 * @package amazon
 * @author Kazuya Kanatani
 * @version 0.1
 * @copyright (C) 2017 kinformation<kanatani.social@gmail.com>
 * @license MIT
 */

$locales = array(
    'US' => array(
        'domain'             => 'Amazon.com',
        'baseUri'            => 'https://webservices.amazon.com/onca/xml',
        'linkTemplate'       => '<iframe src="https://rcm.amazon.com/e/cm?t=${t}&o=1&p=8&l=as1&asins=${asins}&fc1=${fc1}&IS2=${IS2}&lt1=${lt1}&m=amazon&lc1=${lc1}&bc1=${bc1}&bg1=${bg1}&f=ifr" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0"></iframe>',
    ),
    'UK' => array(
        'domain'             => 'Amazon.co.uk',
        'baseUri'            => 'https://webservices.amazon.co.uk/onca/xml',
        'linkTemplate'       => '<iframe src="https://rcm-uk.amazon.co.uk/e/cm?t=${t}&o=2&p=8&l=as1&asins=${asins}&fc1=${fc1}&IS2=${IS2}&lt1=${lt1}&m=amazon&lc1=${lc1}&bc1=${bc1}&bg1=${bg1}&f=ifr" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0"></iframe>',
    ),
    'DE' => array(
        'domain'             => 'Amazon.de',
        'baseUri'            => 'https://webservices.amazon.de/onca/xml',
        'linkTemplate'       => '<iframe src="https://rcm-de.amazon.de/e/cm?t=${t}&o=3&p=8&l=as1&asins=${asins}&fc1=${fc1}&IS2=${IS2}&lt1=${lt1}&m=amazon&lc1=${lc1}&bc1=${bc1}&bg1=${bg1}&f=ifr" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0"></iframe>',
    ),
    'FR' => array(
        'domain'             => 'Amazon.fr',
        'baseUri'            => 'https://webservices.amazon.fr/onca/xml',
        'linkTemplate'       => '<iframe src="https://rcm-fr.amazon.fr/e/cm?t=${t}&o=8&p=8&l=as1&asins=${asins}&fc1=${fc1}&IS2=${IS2}&lt1=${lt1}&m=amazon&lc1=${lc1}&bc1=${bc1}&bg1=${bg1}&f=ifr" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0"></iframe>',
    ),
    'JP' => array(
        'domain'             => 'Amazon.co.jp',
        'baseUri'            => 'https://webservices.amazon.co.jp/onca/xml',
        'linkTemplate'       => '<iframe src="https://rcm-jp.amazon.co.jp/e/cm?t=${t}&o=9&p=8&l=as1&asins=${asins}&fc1=${fc1}&IS2=${IS2}&lt1=${lt1}&m=amazon&lc1=${lc1}&bc1=${bc1}&bg1=${bg1}&f=ifr" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0"></iframe>',
    ),
    'CA' => array(
        'domain'             => 'Amazon.ca',
        'baseUri'            => 'https://webservices.amazon.ca/onca/xml',
        'linkTemplate'       => '<iframe src="https://rcm-ca.amazon.ca/e/cm?t=${t}&o=15&p=8&l=as1&asins=${asins}&fc1=${fc1}&IS2=${IS2}&lt1=${lt1}&m=amazon&lc1=${lc1}&bc1=${bc1}&bg1=${bg1}&f=ifr" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0"></iframe>',
    ),
    'CN' => array(
        'domain'             => 'Amazon.cn',
        'baseUri'            => 'https://webservices.amazon.cn/onca/xml',
        'linkTemplate'       => '<iframe src="https://rcm-cn.amazon.cn/e/cm?t=${t}&o=28&p=8&l=as1&asins=${asins}&fc1=${fc1}&IS2=${IS2}&lt1=${lt1}&m=amazon&lc1=${lc1}&bc1=${bc1}&bg1=${bg1}&f=ifr" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0"></iframe>',
    ),
    'IT' => array(
        'domain'             => 'Amazon.it',
        'baseUri'            => 'https://webservices.amazon.it/onca/xml',
        'linkTemplate'       => '<iframe src="https://rcm-it.amazon.it/e/cm?t=${t}&o=29&p=8&l=as1&asins=${asins}&fc1=${fc1}&IS2=${IS2}&lt1=${lt1}&m=amazon&lc1=${lc1}&bc1=${bc1}&bg1=${bg1}&f=ifr" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0"></iframe>',
    ),
    'ES' => array(
        'domain'             => 'Amazon.es',
        'baseUri'            => 'https://webservices.amazon.es/onca/xml',
        'linkTemplate'       => '<iframe src="https://rcm-es.amazon.es/e/cm?t=${t}&o=30&p=8&l=as1&asins=${asins}&fc1=${fc1}&IS2=${IS2}&lt1=${lt1}&m=amazon&lc1=${lc1}&bc1=${bc1}&bg1=${bg1}&f=ifr" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0"></iframe>',
    ),
);

$locale = array_key_exists ( User_Locale, $locales ) ? $locales[User_Locale] : $locales['US'];
$baseurl = $locale['baseUri'];
$params = array();
$params["Service"]          = "AWSECommerceService";
$params["AWSAccessKeyId"]   = Access_Key_ID;
$params["Version"]          = "2013-08-01";
$params["Operation"]        = "ItemLookup";
$params["ItemId"]           = $ItemId;
$params["AssociateTag"]     = Associate_Tag;
$params["ResponseGroup"]    = "ItemAttributes,Offers,Images";

$request = "";
foreach ($params as $k => $v) {
    $request .= "&" . $k . "=" . $v;
}
$request = $baseurl . "?" . substr($request, 1);
$params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
$request .= "&Timestamp=" . $params['Timestamp'];
$request = "";
foreach ($params as $k => $v) {
    $request .= '&' . $k . '=' . rawurlencode($v);
    $params[$k] = rawurlencode($v);
}
$request = $baseurl . "?" . substr($request, 1);
$request = preg_replace("/.*\?/", "", $request);
$request = str_replace("&", "\n", $request);
ksort($params);
$request = "";
foreach ($params as $k => $v) {
    $request .= "&" . $k . "=" . $v;
}
$request = substr($request, 1);
$request = str_replace("&", "\n", $request);
$request = str_replace("\n", "&", $request);
$parsed_url = parse_url($baseurl);
$request = "GET\n" . $parsed_url['host'] . "\n" . $parsed_url['path'] . "\n" . $request;
$signature = base64_encode(hash_hmac('sha256', $request, Secret_Access_Key, true)); // secret
$signature = rawurlencode($signature);
$request = "";
foreach ($params as $k => $v) {
    $request .= "&" . $k . "=" . $v;
}
$request = $baseurl . "?" . substr($request, 1) . "&Signature=" . $signature;
// echo '<a href="'.$request.'">link</a>';
