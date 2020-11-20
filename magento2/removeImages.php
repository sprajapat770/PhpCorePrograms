<?php
/** program to remove all images of product**/

require __DIR__ . '/app/bootstrap.php';

$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('adminhtml');
$registry = $objectManager->get('Magento\Framework\Registry');
$registry->register('isSecureArea', true);

$storeManager = $objectManager->get('\Magento\Store\Api\StoreRepositoryInterface');

$store_id = 0;
$dir = __DIR__ . '/var/export/products';
if (!is_dir($dir)) {
    mkdir($dir);
}

/**sku list**/
$fp = fopen($dir . "/sku.csv", "r");
$count=0;
$skulist = array();
while (($row = fgetcsv($fp)) !== false) {
    $count++;
    if ($count == 1) {
        continue;
    }
    $skulist[] = $row[0];
}
foreach ($skulist as $sku){
    $product = $objectManager->create('Magento\Catalog\Model\ProductFactory')->create();
    $product->load($product->getIdBySku($sku));
    $imageProcessor = $objectManager->create('\Magento\Catalog\Model\Product\Gallery\Processor');
    $images = $product->getMediaGalleryImages();
    foreach($images as $child){
        $imageProcessor->removeImage($product, $child->getFile());
        echo $sku;
    }

    try {
        $product->save();
        echo "Image Removed for ". $sku;
        echo "\n";
    }catch (\Exception $exception){
        echo $exception->getMessage();
    }
}

fclose($fp);
