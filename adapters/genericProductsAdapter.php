<?php 

require_once '../testprocurement/app/Mage.php'; //replace with '../app/Mage.php' if testing on server

/* problem definition
1. the script must be able to store new products - check
2. the script must be able to update price and stock (if stock out should delete item eventually)
*/

include('interfaces/iAdapter.php');

class GenericProductsAdapter
{
  private $log; 
  private $countUpdated;
  private $countInserted;

  public function __construct($logger) 
  { 
    $this->log = $logger;
    $this->countUpdated = 0;  
    $this->countInserted = 0;
  }

  public function __deconstruct() 
  { 
    $this->log->lclose();
  }

  public function updateProduct($product, $existingProduct) 
  {
    $this->countUpdated++;

    $existingProduct->setName((string)$product->StockItemDescription);
    $existingProduct->setPrice((real)$product->Price);
    $existingProduct->setShortDescription((string)$product->StockItemDescription);

    $existingProduct->save();

    $existingProductId = $existingProduct->getId();
    $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($existingProductId);
    $stockItemId = $stockItem->getId();

    $stockItem->setData('manage_stock', 1);
    $stockItem->setData('qty', (integer)$product->Availability);

    $stockItem->save();

    if(!empty($product->NAPCode)) {
      $existingProduct->setData('NAPPI', $product->NAPCode)->getResource()->saveAttribute($existingProduct, 'NAPPI');
    }
    if(!empty($product->EANCode)) {
      $existingProduct->setData('EAN', $product->EANCode)->getResource()->saveAttribute($existingProduct, 'EAN');
    } 
    if (!empty($product->Schedule)) { 
      $existingProduct->setData('Schedule', $product->Schedule)->getResource()->saveAttribute($existingProduct, 'Schedule');
    } 
  }

  public function insertProduct($product) 
  {
    $this->countInserted++;

    $insert = Mage::getModel('catalog/product');
    $insert
      ->setTypeId('simple') 
      ->setAttributeSetId(4) 
      ->setSku($product->StockItemCode) 
      ->setWebsiteIDs(array(1));

    $insert
      ->setCategoryIds(array(2,3))
      ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
      ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH); // visible in catalog and search

    if ($product->Availability > 0) $isInStock = 1; else $isInStock = 0;

    $insert->setStockData(array(
      'use_config_manage_stock' => 0, 
      'manage_stock'            => 1, 
      'is_in_stock'             => $isInStock,
      'qty'                     => $product->Availability,
    ));

    $insert
      ->setName($product->StockItemDescription) 
      ->setDescription($product->StockItemDescription) 
      ->setShortDescription($product->StockItemDescription) 

      ->setPrice($product->Price)
      ->setSpecialPrice($product->Price)
      ->setTaxClassId(2)   
      ->setWeight(0)
    ;

    $insert->save();

    if ($product->NAPCode) {
      $insert->setData('NAPPI', $product->NAPCode)->getResource()->saveAttribute($insert, 'NAPPI');
    }
    if ($product->EANCode) {
      $insert->setData('EAN', $product->EANCode)->getResource()->saveAttribute($insert, 'EAN');
    }
     if ($product->Schedule) { 
      $insert->setData('Schedule', $product->Schedule)->getResource()->saveAttribute($insert, 'Schedule');
    } 
  }

  public function process($xml)
  {

    Mage::setIsDeveloperMode(true);
    ini_set('display_errors', 1);

    umask(0);
    Mage::app();

    Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 

    foreach ($xml->Product as $product) 
    {
      $SKU = (string)$product->StockItemCode;
      $existingProduct = Mage::getModel('catalog/product')->loadByAttribute('sku',$SKU);

      if($existingProduct) 
      {
          $this->updateProduct($product, $existingProduct);   
      }
      else 
      {
          $this->insertProduct($product);
      }
    }

    $this->log->lwrite('Number of products UPDATED : ' . $this->countUpdated . ' and INSERTED : ' . $this->countInserted);

  }


}


?>