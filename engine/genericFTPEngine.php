<?php

include('interfaces/iEngine.php');
include('core/ftp.php');

class GenericFTPEngine implements IEngine
{
	private $ftp;
	private $config;
	private $log; 
 
  public function __construct($logger) 
  { 
  	$this->ftp = new FTPClient();
  	$this->config = parse_ini_file("config/generic_config.ini");
  	$this->log = $logger;
  }

  public function __deconstruct()
	{

	}

	public function connect() 
	{
		$this->log->lwrite('FTP Engine Connect to ' . $this->config['FTP_HOST'] . ' ');
		
		$this->ftp->connect($this->config['FTP_HOST'], $this->config['FTP_USER'], $this->config['FTP_PASS']);
	}

	public function fetch($remotePriceFile, $relativeDirectory) 
	{
		$this->log->lwrite('FTP Engine Fetch ' . $remotePriceFile . ' to local downloads/' . $remotePriceFile);

		if($relativeDirectory) {
			$this->ftp->changeDir($dir);
		}
 
		$this->ftp->downloadFile($remotePriceFile, 'downloads/' . $remotePriceFile);
	}

	public function printResults()
	{
		if (file_exists($this->config['UPDLocalPriceFile'])) {
		  $xml = simplexml_load_file($this->config['UPDLocalPriceFile']); 
		} 
		else {
		    exit('Failed to open local pricefile.');
		}

		foreach ($xml->Product as $product) {
			echo $product->StockItemDescription . "<br/>";
		}
	}

	public function load($localPriceFile) 
	{
		$zippedFile = $localPriceFile . '.zip';

		$this->log->lwrite('FTP Engine Load XML - ' . $zippedFile);

		$path = pathinfo(realpath($zippedFile), PATHINFO_DIRNAME);

		$zip = new ZipArchive;
		$res = $zip->open($zippedFile);
		if ($res === TRUE) {
		  $zip->extractTo($path);
		  $zip->close();
		  $this->log->lwrite('Successfully unzipped - ' . $localPriceFile);
		} else {
		  $this->log->lwrite('Failed to unzip - ' . $localPriceFile);
		}

		if (file_exists($localPriceFile)) {
		    $xml = simplexml_load_file($localPriceFile); 
		    $this->log->lwrite('Successfully loaded local pricefile.');
		} 
		else {
		    $this->log->lwrite('Failed to load local pricefile.');
		    exit('Failed to load local pricefile.');
		}

		return $xml;
	}

}

?>