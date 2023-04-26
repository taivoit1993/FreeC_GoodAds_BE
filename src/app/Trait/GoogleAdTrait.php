<?php
namespace App\Trait;
use Google\Ads\GoogleAds\V13\Common\AdTextAsset;
use Google\Protobuf\Internal\RepeatedField;

trait GoogleAdTrait{
    private static function createAdTextAsset(string $text, int $pinField = null): AdTextAsset
    {
        $adTextAsset = new AdTextAsset(['text' => $text]);
        if (!is_null($pinField)) {
            $adTextAsset->setPinnedField($pinField);
        }
        return $adTextAsset;
    }

    private static function getListTest(RepeatedField $data){
        $text = [];
        foreach ($data->getIterator() as $item){
            $text [] = $item->getText();
        }
        return $text;
    }
}
