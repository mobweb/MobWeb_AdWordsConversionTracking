<?php
class MobWeb_AdWordsConversionTracking_Block_Conversion extends Mage_Core_Block_Abstract
{
    
    protected function _toHtml()
    {
        // Get the static data from the configuration
        $googleConversionId = Mage::getStoreConfig('google/mobweb_adwordsconversiontracking/google_conversion_id');
        $googleConversionLanguage = Mage::getStoreConfig('google/mobweb_adwordsconversiontracking/google_conversion_language');
        $googleConversionFormat = Mage::getStoreConfig('google/mobweb_adwordsconversiontracking/google_conversion_format');
        $googleConversionColor = Mage::getStoreConfig('google/mobweb_adwordsconversiontracking/google_conversion_color');
        $googleConversionLabel = Mage::getStoreConfig('google/mobweb_adwordsconversiontracking/google_conversion_label');
        $googleRemarketingOnly = Mage::getStoreConfig('google/mobweb_adwordsconversiontracking/google_remarketing_only');

        // Load the order and get the order total and currency
        $googleConversionValue = '0.00';
        $googleConversionCurrency = '';
        if(($order = Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId())) && $order INSTANCEOF Mage_Sales_Model_Order) {
            $googleConversionValue = round($order->getSubtotal(), 2);
            $googleConversionCurrency = $order->getOrderCurrencyCode();
        }

        // Check if all the required values are available
        if($googleConversionId && $googleConversionLanguage && $googleConversionFormat && $googleConversionColor && $googleConversionLabel) {

            // Generate the Javascript to track the conversion
            $html = '<script type="text/javascript">/* <![CDATA[ */ ';
            $html .= sprintf('var google_conversion_id = %s;', $googleConversionId);
            $html .= sprintf('var google_conversion_language = "%s";', $googleConversionLanguage);
            $html .= sprintf('var google_conversion_format = "%s";', $googleConversionFormat);
            $html .= sprintf('var google_conversion_color = "%s";', $googleConversionColor);
            $html .= sprintf('var google_conversion_label = "%s";', $googleConversionLabel);
            $html .= sprintf('var google_conversion_value = %s;', $googleConversionValue);
            $html .= sprintf('var google_conversion_currency = "%s";', $googleConversionCurrency);
            $html .= sprintf('var google_remarketing_only = %s;', $googleRemarketingOnly);
            $html .= '/* ]]> */</script> <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>';
            $html .= sprintf('<noscript> <div style="display:inline;"><img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/%s/?value=%s&amp;currency_code=%s&amp;label=%s&amp;guid=ON&amp;script=0"/> </div> </noscript>', $googleConversionId, $googleConversionValue, $googleConversionCurrency, $googleConversionLabel);
            
            echo $html;
        }
    }
}