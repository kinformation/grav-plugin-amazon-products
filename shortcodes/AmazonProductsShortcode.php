<?php
/**
 * Short description for AmazonProductsShortcode.php
 *
 * @package amazon-products
 * @author Kazuya Kanatani
 * @version 0.1
 * @copyright (C) 2017 kinformation<kanatani.social@gmail.com>
 * @license MIT
 */

namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class AmazonProductsShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('amazon', function (ShortcodeInterface $sc) {

            $current_date = date("Y/m/d H:i");

            $asin = $sc->getParameter('asin');
            $asin = $this->extract_asin($asin);
            $item = $this->lookup_item($asin);
            if (empty($item)) {
                return '';
            }

            $item_title = $item->ItemAttributes->Title;
            $item_image  = $item->MediumImage->URL;
            $item_url = $item->DetailPageURL;
            $item_price = @$item->Offers->Offer->OfferListing->Price->FormattedPrice
                       ?: @$item_price = $item->OfferSummary->LowestNewPrice->FormattedPrice
                       ?: '';
            $item_productgroup = $item->ItemAttributes->ProductGroup;
            switch ($item_productgroup) {
                case 'Book':
                case 'eBooks':
                    $item_author = $item->ItemAttributes->Author;
                    $item_publicationdate = $item->ItemAttributes->PublicationDate;
                    $item_publisher = $item->ItemAttributes->Publisher;
                    $output = $this->twig->processTemplate('partials/amazon_book.html.twig', [
                        'amazon_date' => $current_date,
                        'amazon_title' => $item_title,
                        'amazon_url' => $item_url,
                        'amazon_price' => $item_price,
                        'amazon_image' => $item_image,
                        'amazon_author' => $item_author,
                        'amazon_publicationDate' => $item_publicationdate,
                        'amazon_publisher' => $item_publisher,
                    ]);
                    break;

                case 'Music':
                case 'Digital Music Album':
                case 'Digital Music Track':
                    $item_artist = $item->ItemAttributes->Artist;
                    if (empty($item_artist)) {
                        $item_artist = $item->ItemAttributes->Creator;
                    }
                    $item_label =  $item->ItemAttributes->Label;
                    $item_releaseDate = $item->ItemAttributes->ReleaseDate;
                    $item_runningTime =  $item->ItemAttributes->RunningTime;
                    $item_timeUnit =  $item->ItemAttributes->RunningTime['Units'];
                    $output = $this->twig->processTemplate('partials/amazon_music.html.twig', [
                        'amazon_date' => $current_date,
                        'amazon_title' => $item_title,
                        'amazon_url' => $item_url,
                        'amazon_price' => $item_price,
                        'amazon_image' => $item_image,
                        'amazon_artist' => $item_artist,
                        'amazon_label' => $item_label,
                        'amazon_releaseDate' => $item_releaseDate,
                        'amazon_runningTime' => $item_runningTime,
                        'amazon_timeUnit' => $item_timeUnit,
                    ]);
                    break;

                case 'DVD':
                case 'Movie':
                    $item_director = $item->ItemAttributes->Director;
                    $item_actor = $item->ItemAttributes->Actor;
                    $item_label = $item->ItemAttributes->Label;
                    $item_releaseDate = $item->ItemAttributes->ReleaseDate;
                    $item_runningTime =  $item->ItemAttributes->RunningTime;
                    $item_timeUnit =  $item->ItemAttributes->RunningTime['Units'];
                    $output = $this->twig->processTemplate('partials/amazon_video.html.twig', [
                        'amazon_date' => $current_date,
                        'amazon_title' => $item_title,
                        'amazon_url' => $item_url,
                        'amazon_price' => $item_price,
                        'amazon_image' => $item_image,
                        'amazon_director' => $item_director,
                        'amazon_actor' => $item_actor,
                        'amazon_label' => $item_label,
                        'amazon_releaseDate' => $item_releaseDate,
                        'amazon_runningTime' => $item_runningTime,
                        'amazon_timeUnit' => $item_timeUnit,
                    ]);
                    break;

                default:
                    $item_brand = $item->ItemAttributes->Brand;
                    $output = $this->twig->processTemplate('partials/amazon_default.html.twig', [
                        'amazon_date' => $current_date,
                        'amazon_title' => $item_title,
                        'amazon_url' => $item_url,
                        'amazon_price' => $item_price,
                        'amazon_image' => $item_image,
                        'amazon_brand' => $item_brand,
                    ]);
            }

            return $output;
        });
    }

    private function lookup_item($ItemId)
    {
        if (!defined('User_Locale')) {
            define("User_Locale", strtoupper($this->config->get('plugins.amazon-products.locale')));
        }
        if (!defined('Associate_Tag')) {
            $atag = trim($this->config->get('plugins.amazon-products.keys.associateTag'));
            !empty($atag)
                ? define("Associate_Tag", $atag)
                : define("Associate_Tag", 'XXXXX');
        }
        if (!defined('Access_Key_ID')) {
            define("Access_Key_ID", trim($this->config->get('plugins.amazon-products.keys.accessKeyId')));
        }
        if (!defined('Secret_Access_Key')) {
            define("Secret_Access_Key", trim($this->config->get('plugins.amazon-products.keys.secretAccessKey')));
        }
        include("plugin://amazon-products/amazon_request.php");

        $amazon_xml = '';
        $ret = '';
        for ($i = 0; $i < 2; $i++) {
            $context = stream_context_create(array(
                'http' => array('ignore_errors' => true)
            ));
            $response = file_get_contents($request, false, $context);

            $pos = strpos($http_response_header[0], '200');
            if ($pos === false) {
                continue;
            }
            $amazon_xml = simplexml_load_string($response);
            $ret = $amazon_xml === false ? '' : $amazon_xml->Items->Item;
            break;
        }
        return $ret;
    }

    private function extract_asin($asin)
    {
        if (empty($asin)) {
            return '';
        }

        // Only ASIN/ISBN
        if (preg_match('/^[a-zA-Z0-9]+$/', $asin) === 1) {
            return $asin;
        }

        // Extract ASIN by raw amazonURL
        if (preg_match('#/dp/([a-zA-Z0-9]+)/#', $asin, $matches) === 1) {
            return $matches[1];
        }
        if (preg_match('#/gp/product/([a-zA-Z0-9]+)/#', $asin, $matches) === 1) {
            return $matches[1];
        }

        return '';
    }
}
