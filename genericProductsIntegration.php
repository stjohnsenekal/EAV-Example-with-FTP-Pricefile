<?php
 
/* Includes */

include('engine/genericFTPEngine.php');
include('adapters/genericProductsAdapter.php');
include('core/logging.php');

/* turn off the maximum execution time */
ini_set('MAX_EXECUTION_TIME', -1);

/* file name construction preamble */

$priceFile = 'pricefile.xml';
$remotePriceFile = $priceFile . '.zip';
$localPriceFile = 'downloads/' . $priceFile;

/* instantiate logger */

$logger = new Logging();
$logger->lwrite('Integration Job at ' .date("Ymd") . ' BEGIN');

/* connect and fetch file logic */

$engine = new GenericFTPEngine($logger);

$engine->connect();
$engine->fetch($remotePriceFile, null);

$xml = $engine->load($localPriceFile);

$integration = new GenericProductsAdapter($logger);
$integration->process($xml);

/* clean up */

$logger->lwrite('Integration Job END');
$logger->lclose();


?>