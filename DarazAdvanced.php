<?php
namespace ExternalImporter\application\libs\pextractor\parser\parsers;

/*
 * URI: https://www.daraz.com.np
 */

class DarazAdvanced extends AdvancedParser
{
    protected $_product = null;

    // Method to parse product links
    public function parseLinks()
    {
        $urls = array();

        if (preg_match_all('/"productUrl":"(.+?)"/', $this->html, $matches))
            $urls = $matches[1];

        return $urls;
    }

    // Method to parse pagination information
    public function parsePagination()
    {
        if (preg_match('/"totalPages":(\d+)/', $this->html, $matches)) {
            $totalPages = (int)$matches[1];
            $pagination = [];
            for ($i = 2; $i <= $totalPages; $i++) {
                $pagination[] = \add_query_arg('page', $i, $this->getUrl());
            }
            return $pagination;
        }
        return [];
    }

    // Method to parse the product title
    public function parseTitle()
    {
        if (preg_match('/<title>(.+?)<\/title>/', $this->html, $matches)) {
            return trim($matches[1]);
        }
    }

    // Method to parse the product description
    public function parseDescription()
    {
        if (preg_match('/<meta name="description" content="(.+?)"/', $this->html, $matches)) {
            return trim($matches[1]);
        }
    }
    
    // Method to parse the current price
    public function parsePrice()
    {
    // First, check if the original price pattern works
    if (preg_match('/"price":"([\d\.]+)"/', $this->html, $matches)) {
        return (float)$matches[1];
    }

    // Alternative pattern to find price in script or inline JSON data
    if (preg_match('/priceCompare.*?price":(\d+)/', $this->html, $matches)) {
        return (float)$matches[1];
    }

    // Return null if no price found
    return null;
    }
    

    // Method to parse the old price
    public function parseOldPrice()
    {
        if (preg_match('/"originalPrice":"([\d\.]+)"/', $this->html, $matches)) {
            return (float)$matches[1];
        }
    }

    // Method to parse the main product image
    public function parseImage()
    {
        if (preg_match('/<meta property="og:image" content="(.+?)"/', $this->html, $matches)) {
            return $matches[1];
        }
    }

    // Method to parse additional images
    public function parseImages()
    {
        $images = [];
        if (preg_match_all('/"image":"(.+?)"/', $this->html, $matches)) {
            $images = $matches[1];
        }
        return $images;
    }

    // Method to parse the manufacturer
    public function parseManufacturer()
    {
        if (preg_match('/"brand":"(.+?)"/', $this->html, $matches)) {
            return trim($matches[1]);
        }
    }

    // Method to parse the stock status
    public function parseInStock()
    {
        if (preg_match('/"inStock":(true|false)/', $this->html, $matches)) {
            return $matches[1] === 'true';
        }
    }

    // Method to parse the category path
    public function parseCategoryPath()
    {
        $categories = [];
        if (preg_match_all('/"category":"(.+?)"/', $this->html, $matches)) {
            $categories = $matches[1];
        }
        return implode(' > ', $categories);
    }

    // Method to parse the currency code
    public function parseCurrencyCode()
    {
        if (preg_match('/"currency":"(.+?)"/', $this->html, $matches)) {
            return $matches[1];
        }
    }

    // Method to parse product features
    public function parseFeatures()
    {
        $features = [];
        if (preg_match_all('/"feature":"(.+?)"/', $this->html, $matches)) {
            $features = $matches[1];
        }
        return $features;
    }

    // Method to parse reviews
    public function parseReviews()
    {
        $reviews = [];
        if (preg_match_all('/"reviewContent":"(.+?)"/', $this->html, $matches)) {
            foreach ($matches[1] as $reviewContent) {
                $reviews[] = ['review' => $reviewContent];
            }
        }
        return $reviews;
    }
}
