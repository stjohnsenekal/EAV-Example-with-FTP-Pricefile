<?php

class FTPClient
{

  private $connectionId;
	private $loginOk = false;
	private $messageArray = array();
 
  public function __construct() { }

  public function __deconstruct()
	{
    if ($this->connectionId) {
        ftp_close($this->connectionId);
    }
	}

  public function getMessages()
	{
	  return $this->messageArray;
	}

	public function connect($server, $ftpUser, $ftpPassword, $isPassive = false)
	{
	  $this->connectionId = ftp_connect($server);
	 
	  $loginResult = ftp_login($this->connectionId, $ftpUser, $ftpPassword);
	 
	  ftp_pasv($this->connectionId, $isPassive);
	 
	 	if ((!$this->connectionId) || (!$loginResult)) {
	      $this->logMessage('FTP connection has failed!');
	      $this->logMessage('Attempted to connect to ' . $server . ' for user ' . $ftpUser, true);
	      return false;
	  } else {
	      $this->logMessage('Connected to ' . $server . ', for user ' . $ftpUser);
	      $this->loginOk = true;
	      return true;
	  	}
		}

	public function listFiles()
	{
		$contents = ftp_nlist($this->connectionId, ".");
		return $contents;
	}

	public function getLastModifiedTD($fileName)
	{
		$raw = ftp_mdtm($this->connectionId, $fileName);
		if($raw != -1) {
			echo "$fileName was last modified on : " .  date("F d Y  H:i:s.", $raw);		
		} 
		else {
    	echo "Couldn't get mdtime";
		}
	}

	public function makeDir($directory)
	{
    if (ftp_mkdir($this->connectionId, $directory)) {
	    $this->logMessage('Directory "' . $directory . '" created successfully');
	    return true;

    } 
    else {
      $this->logMessage('Failed creating directory "' . $directory . '"');
      return false;
    }
	}

	public function changeDir($directory)
	{
    if (ftp_chdir($this->connectionId, $directory)) {
        $this->logMessage('Current directory is now: ' . ftp_pwd($this->connectionId));
        return true;
    } 
    else { 
      $this->logMessage('Couldn\'t change directory');
      return false;
    }
	}

	public function getDirListing($directory = '.')
	{
    $contentsArray = ftp_nlist($this->connectionId, $directory);
    return $contentsArray;
	}

	public function downloadFile($fileFrom, $fileTo)
	{
    $asciiArray = array('txt', 'csv');
    $fileParts = explode('.', $fileFrom);
    $extension = end($fileParts);
    if (in_array($extension, $asciiArray)) {
      $mode = FTP_ASCII;      
    } 
    else {
      $mode = FTP_BINARY;
    }
 
    if (ftp_get($this->connectionId, $fileTo, $fileFrom, $mode, 0)) {
      return true;
      $this->logMessage(' file "' . $fileTo . '" successfully downloaded');
    } 
    else {
      return false;
      $this->logMessage('There was an error downloading file "' . $fileFrom . '" to "' . $fileTo . '"');
    }
	 
	}

	public function uploadFile($fileFrom, $fileTo)
	{
    $asciiArray = array('txt', 'csv');
    $fileParts = explode('.', $fileFrom);
    $extension = end($fileParts);
    if (in_array($extension, $asciiArray)) {
        $mode = FTP_ASCII;      
    } 
    else {
        $mode = FTP_BINARY;
    }
 
    $upload = ftp_put($this->connectionId, $fileTo, $fileFrom, $mode);
 
    if (!$upload) {
        $this->logMessage('FTP upload has failed!');
        return false;
      } 
      else {
        $this->logMessage('Uploaded "' . $fileFrom . '" as "' . $fileTo);
        return true;
      }
	}

  private function logMessage($message) 
	{
		$this->messageArray[] = $message;
	}


}

?>