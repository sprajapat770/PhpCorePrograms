<?php 

use \Magento\Framework\App\Bootstrap;

include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);


$objectManager = $bootstrap->getObjectManager();
$url = \Magento\Framework\App\ObjectManager::getInstance();

$storeManager = $url->get('\Magento\Store\Model\StoreManagerInterface');
$mediaurl= $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

$state = $objectManager->get('\Magento\Framework\App\State');
$state->setAreaCode('frontend');

/// Get Website ID
$websiteId = $storeManager->getWebsite()->getWebsiteId();

/// Get Store ID
$store = $storeManager->getStore();
$storeId = $store->getStoreId();

/// Get Root Category ID
$rootNodeId = $store->getRootCategoryId();

/// Get Root Category
$rootCat = $objectManager->get('Magento\Catalog\Model\Category');
$cat_info = $rootCat->load($rootNodeId);


$file1 = fopen("categoryList.csv","r");

$count = 0;

while (($row = fgetcsv($file1)) !== FALSE) {
	$count++;
	if ($count==1) {
		continue;
	}
	
	$array = explode("/",$row[0]);

	foreach ($array as $cat) {		
		$categoryFactory=$objectManager->get('\Magento\Catalog\Model\CategoryFactory');
		$collection = $categoryFactory->create()->getCollection()->addAttributeToFilter('name',$cat);
		
       	if ($collection->getSize()) {
			$categoryId = $collection->getFirstItem()->getId();
			categoryLoop($categoryId);
		}
	}
}

function categoryLoop($id){
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $categories = $objectManager->create('Magento\Catalog\Model\Category')->load($id);

    if($categories->hasChildren()){
        $subcategories = explode(',', $categories->getChildren());
        foreach ($subcategories as $category) {
        	echo $category;
            $subcategory = $objectManager->create('Magento\Catalog\Model\Category')->load($category);
            $subcategory->getName();
            if($subcategory->hasChildren()){ categoryLoop($category); }
        }
    }
}

fclose($file1);