<?php

$url="http://meijer.shoplocal.com/Meijer/BrowseByListing/ByCategory/?StoreID=2465917&CategoryID=402668#PageNumber=1";	

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url); 
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");		
curl_setopt($ch, CURLOPT_COOKIEFILE, "new.cook");
curl_setopt($ch, CURLOPT_COOKIEJAR, "new.cook"); 	
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result2=curl_exec($ch);		
//echo $result2;

preg_match_all('<option value="(.*?)">',$result2,$catid);

file_put_contents("shoplocal.csv","Product Name~Price~Image\r\n",FILE_APPEND);
/*echo "<pre>";
print_r($catid);
echo "</pre>";*/
for($i=1;$i<49;$i++){

$url2="http://meijer.shoplocal.com/Meijer/BrowseByListing/ByAllListings/?StoreID=2465917&CategoryID=".$catid[1][$i]."#PageNumber=1";	
echo $url2."<br>";
//$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url2); 
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");		
curl_setopt($ch, CURLOPT_COOKIEFILE, "new.cook");
curl_setopt($ch, CURLOPT_COOKIEJAR, "new.cook"); 	
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result=curl_exec($ch);	
//echo $result;	
$newlines = array("\t","\n","\r","\x20\x20","\0","\x0B");
$result = str_replace($newlines, "", html_entity_decode($result));
$result=str_replace("<br />","<br>",$result);
//echo $result;

preg_match_all('/<div class="skinPageXofYItem positionXofYItem action-pageXofY">Page(.*?)<\/div>/i',$result,$data);
/*echo "<pre>";
print_r($data);
echo "</pre>";*/

$page=explode("of",$data[1][0]);
//echo $page[0];
//die();

for($k=2;$k<trim($page[0])+1;$k++){
	$url1="http://meijer.shoplocal.com/Meijer/BrowseByListing/ByAllListings/?StoreID=2465917&CategoryID=".$catid[1][$i]."&PageNumber=".$k;
	echo $url1."<br>";	
	curl_setopt($ch, CURLOPT_URL,$url1); 
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");		
	curl_setopt($ch, CURLOPT_COOKIEFILE, "new.cook");
	curl_setopt($ch, CURLOPT_COOKIEJAR, "new.cook"); 	
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result1=curl_exec($ch);	
	//echo $result1;
	//die();
	preg_match_all('<div class="listingimg" style="background-repeat:no-repeat; background-image:url(.*?)">',$result1,$images);
	/*echo "<pre>";
	print_r($images);
	echo "</pre>";*/
	
	preg_match_all('/<span class="dealtitle" >(.*?)<\/span>/i',$result1,$title);
	/*echo "<pre>";
	print_r($title);
	echo "</pre>";*/
	
	preg_match_all('/<span class="deal">(.*?)<\/span>/i',$result1,$price);
	/*echo "<pre>";
	print_r($price);
	echo "</pre>";*/
	
	for($m=0;$m<count($title[1]);$m++){
		
		$til=$title[1][$m];
		$pri=$price[1][$m];
		$img=$images[1][$m];
		/*echo $til."<br>";
		echo $pri."<br>";
		echo $img."<br>";*/
		file_put_contents("shoplocal.csv",$til."~".$pri."~".$img."\r\n",FILE_APPEND);
			
		}
		
	}
	
	
}

?>
