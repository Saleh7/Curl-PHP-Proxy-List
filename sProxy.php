<?php
/**
* 
*/
require 'Curl.php';
class Anonymouse
{
	
	function __construct()
	{
		# code...
		$this->Curl = new Curl();
	}
    /**
     */
	public function validate_ip($Site) {
	    // generate ipv4 network address
	    $ip = $this->Curl->post($Site."https://api.ipify.org/");
	    $ip = ip2long($ip);
	    // if the ip is set and not equivalent to 255.255.255.255
	    if ($ip == false) {
			return false;
	    }
	    return true;
	}
    /**
     */
    public function Request($Proxy){
        $Response = $this->Curl->post($Proxy."https://api.ipify.org/");
        if(!$Response){
        	$this->Delete_sProxy($Proxy);
        	$this->Random_Proxy();
        }else{
            return $Response;
        }
    }
    /**
     * @param integer| Random
     * @param string | str
     */
    public function Add_sProxy($Site) {
    	$TestProxy = $this->Curl->post($Site."https://api.ipify.org/");
    	if($TestProxy){
	    	$Add_ProxyList = @fopen("ProxyList.txt", "a+");
	    	@fwrite($Add_ProxyList, "$Site\n");
	    	@fclose($Add_ProxyList);
	    	return true;
    	}
        return false;
    }
    /**
     * @param integer| Random
     * @param string | str
     */
    public function Delete_sProxy($Site) {
    	$TestProxy = $this->Curl->post($Site."https://api.ipify.org/");
    	if(!$TestProxy){
	    	$Data_ProxyList = file("ProxyList.txt");
	    	$out = array();
		    foreach($Data_ProxyList as $line) {
		        if(trim($line) != $Site) {
		            $out[] = $line;
		         }
		    }
		    $ProxyList = @fopen("ProxyList.txt", "w+");
		    flock($ProxyList, LOCK_EX);
		    foreach($out as $line) {
		        @fwrite($ProxyList, $line);
		    }
		    flock($ProxyList, LOCK_UN);
		    fclose($ProxyList);
		}
    }
    /**
     * @param integer| Random
     * @param string | str
     */
    public function Random_Proxy() {
    	$Data_ProxyList = file("ProxyList.txt");
        $Proxy = $Data_ProxyList[array_rand($Data_ProxyList)];
        return $this->Request(trim($Proxy));
    }


}//class

$go = new Anonymouse();

$yo = $go->Random_Proxy();
echo $yo;
?>
