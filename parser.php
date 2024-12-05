<?php

class XMLParser
{
    private $xmlFile;

    public function __construct($xmlFile)
    {
        if (!file_exists($xmlFile)) {
            throw new Exception("File not found: $xmlFile");
        }
        $this->xmlFile = $xmlFile;
    }

    public function parse()
    {
        $xml = simplexml_load_file($this->xmlFile);

        // Parse product-level data
        $product = [
            'product_id' => (string) $xml->productID,
            'bleaching_code' => (int) $xml->bleachingCode,
            'default_language' => (string) $xml->defaultLanguage,
            'sap_packet' => (int) $xml->sapPacket,
            'update_images' => filter_var($xml->updateImages, FILTER_VALIDATE_BOOLEAN),
            'dry_cleaning_code' => (int) $xml->dryCleaningCode,
            'drying_code' => (int) $xml->dryingCode,
            'fastening_type_code' => (int) $xml->fasteningTypeCode,
            'ironing_code' => (int) $xml->ironingCode,
            'pullout_type_code' => (int) $xml->pulloutTypeCode,
            'waistline_code' => (int) $xml->waistlineCode,
            'washability_code' => (int) $xml->washabilityCode
        ];

        $header = [
            'id_product' => (string) $xml->productID,
            'bag' => filter_var($xml->definitions->headerData->bag, FILTER_VALIDATE_BOOLEAN),
            'bleaching_description' => (string) $xml->definitions->headerData->bleachingDescription,
            'brand' => (string) $xml->definitions->headerData->brand,
            'brand_code' => (int) $xml->definitions->headerData->brandCode,
            'catalog' => (string) $xml->definitions->headerData->catalog,
            'composition' => (string) $xml->definitions->headerData->composition,
            'creation_date' => (string) $xml->definitions->headerData->creationDateInDatabase,
            'drink_holder' => filter_var($xml->definitions->headerData->drinkHolder, FILTER_VALIDATE_BOOLEAN),
            'dry_cleaning_description' => (string) $xml->definitions->headerData->dryCleaningDescription,
            'drying_description' => (string) $xml->definitions->headerData->dryingDescription,
            'e_shop_display_name' => (string) $xml->definitions->headerData->EShopDisplayName,
            'e_shop_long_description' => (string) $xml->definitions->headerData->EShopLongDescription,
            'free_delivery' => filter_var($xml->definitions->headerData->freeDelivery, FILTER_VALIDATE_BOOLEAN),
            'gender' => (string) $xml->definitions->headerData->gender,
            'last_date_changed' => (string) $xml->definitions->headerData->lastDateChanged,
            'last_user_changed' => (string) $xml->definitions->headerData->lastUserChanged,
            'sap_category_id' => (string) $xml->definitions->headerData->sapCategoryID,
            'sap_category_name' => (string) $xml->definitions->headerData->sapCategoryName,
            'sap_division_id' => (string) $xml->definitions->headerData->sapDivisionID,
            'sap_division_name' => (string) $xml->definitions->headerData->sapDivisionName,
            'sap_family_description' => (string) $xml->definitions->headerData->sapFamilyDescription,
            'sap_family_id' => (string) $xml->definitions->headerData->sapFamilyID,
            'sap_family_name' => (string) $xml->definitions->headerData->sapFamilyName,
            'sap_macrocategory_id' => (string) $xml->definitions->headerData->sapMacrocategoryID,
            'sap_macrocategory_name' => (string) $xml->definitions->headerData->sapMacrocategoryName,
            'sap_name' => (string) $xml->definitions->headerData->sapName,
            'sap_universe_id' => (string) $xml->definitions->headerData->sapUniverseID,
            'sap_universe_name' => (string) $xml->definitions->headerData->sapUniverseName,
            'show_online' => filter_var($xml->definitions->headerData->showOnLine, FILTER_VALIDATE_BOOLEAN),
            'size_guide' => (string) $xml->definitions->headerData->sizeGuide,
            'user_of_creation' => (string) $xml->definitions->headerData->userOfCreation,
            'waistline_description' => (string) $xml->definitions->headerData->waistlineDescription,
            'washability_description' => (string) $xml->definitions->headerData->washabilityDescription,
            'zip_stopper' => filter_var($xml->definitions->headerData->zipStopper, FILTER_VALIDATE_BOOLEAN)
        ];


        // Parse details data
        $details = [];
        foreach ($xml->definitions->detailsData as $detailsData) {
            $details[] = [
                'id_product' => (string) $xml->productID,
                'cedi' => (string) $detailsData->cedi,
                'child_weight_from' => (float) $detailsData->childWeightFrom,
                'child_weight_to' => (float) $detailsData->childWeightTo,
                'color_code' => (string) $detailsData->color_code,
                'color_description' => (string) $detailsData->color_description,
                'country_images' => filter_var($detailsData->countryImages, FILTER_VALIDATE_BOOLEAN),
                'default_sku' => filter_var($detailsData->defaultSku, FILTER_VALIDATE_BOOLEAN),
                'preferred_ean' => (string) $detailsData->preferredEan,
                'sap_assortment_level' => (string) $detailsData->sapAssortmentLevel,
                'sap_price' => (float) $detailsData->sapPrice,
                'season' => (string) $detailsData->season,
                'show_online_sku' => filter_var($detailsData->showOnLineSku, FILTER_VALIDATE_BOOLEAN),
                'size_code' => (string) $detailsData->size_code,
                'size_description' => (string) $detailsData->size_description,
                'sku_id' => (string) $detailsData->skuID,
                'sku_name' => (string) $detailsData->skuName,
                'state_of_article' => filter_var($detailsData->stateOfArticle, FILTER_VALIDATE_BOOLEAN),
                'um_sap_price' => (string) $detailsData->umSAPprice,
                'volume' => (float) $detailsData->volume,
                'weight' => (float) $detailsData->weight,
            ];
        }

        return ['product' => $product, 'header' => $header, 'details' => $details];
    }
}
?>
