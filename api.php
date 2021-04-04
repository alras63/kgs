<?php
$cookie = '';
function requestApi($array, $cookieToggle = false){
	global $cookie;
		$ch = curl_init('https://www.gokgs.com/json/access');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array)); 
	 
	// Или предать массив строкой: 
	// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array, '', '&'));
	 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, $cookieToggle);
	
	if(!$cookieToggle){
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	}
	
	$html = curl_exec($ch);
	curl_close($ch);
	
	return $html;
}

function getDataApi(){
	global $cookie;
	$ch = curl_init('https://www.gokgs.com/json/access');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	$html = curl_exec($ch);
	curl_close($ch);
	return $html;
}

$src = file_get_contents("http://www.gokgs.com/top100.jsp");
		/* $tablesPreg = '/<table class="grid">(.*?)<\/table>/miu';
		
		preg_match_all($tablesPreg, $src, $tables, PREG_SET_ORDER, 0);
		
		foreach($tables as $table){
			echo "<pre>";
				print_r($table);
			echo "</pre>";
		} */
		
		$dom = new DOMDocument;
		$dom->loadHTML($src);
		$trs = $dom->getElementsByTagName('tr');
		
		
		
		foreach ($trs as $tr) {
			if($tr->textContent != 'PositionNameRank' AND strpos($tr->textContent, "PositionNameRank") === FALSE){
				$tdsInTr = $tr->getElementsByTagName('td');
				foreach($tdsInTr as $index => $td){
					if($index == 1){
						$users[]['nameUser'] = $td->textContent;
					}
				}
			}
		}
		
	/*Авторизация*/	
	$array = array(
		"type" => "LOGIN",
		"name" => "samgk",
		"password" => "raku4k",
		"locale" => "en_US"
	);	
	
	$result = requestApi($array, true);
	preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
	
	$cookie = $matches[1][0];

	
	getDataApi();
	foreach($users as $index => $user){
		//if($index < 10){
			//echo $user['nameUser'].'<br>';
			requestApi(array(
				"type" => "JOIN_ARCHIVE_REQUEST",
				"name"=> $user['nameUser']
			));


				$data = json_decode(getDataApi());
				foreach($data->messages as $arr){
					if(isset($arr->games)){
						$arr->games = clone (object)array_reverse((array)$arr->games);
						foreach($arr->games as $indexGame =>$game){
							if($indexGame <= 1){
								//print_r($game);
								$settings = $game->size.'x'.$game->size;
								if($game->handicap != 0){
									$settings .= " H".$game->handicap;
								} 
								
								$firstTime = explode(".",$game->timestamp);
								
								$firstTime = explode("T", $firstTime[0]);
								
								$dateRaw = explode("-", $firstTime[0]);
								$timeRaw = explode(":", $firstTime[1]);
								
								echo '<pre>';
									print_r($dateRaw);
									print_r($timeRaw);
									echo '<hr>';
								echo '</pre>';
								
								$date = $dateRaw[2].'.'.$dateRaw[1].'.'.$dateRaw[0].' '.$timeRaw[0].':'.$timeRaw[1];
								
								//$date = date("d.m.Y H:m", strtotime($goodTime));
								//http://files.gokgs.com/games/2021/1/26/larc-HiraBot44.sgf
								
								$dateRaw[0] = (int)$dateRaw[0];
								$dateRaw[1] = (int)$dateRaw[1];
								$dateRaw[2] = (int)$dateRaw[2];
								
								$hrefSGF = "http://files.gokgs.com/games/";
								$hrefSGF.= $dateRaw[0].'/';
								$hrefSGF.= $dateRaw[1].'/';
								$hrefSGF.= $dateRaw[2].'/';
								$hrefSGF.= $game->players->white->name.'-'.$game->players->black->name.'.sgf';
								
								//echo $firstTime.' => '. $date.'<br>';
								
								$users[$index]['games'][] = [
									'playerNameOne' => $game->players->white->name,
									'playerNameTwo' => $game->players->black->name,
									'colorPlayerOne' => 'white',
									'colorPlayerTwo' => 'black',
									'gameHistory' =>  $hrefSGF,
									'start' =>  $date,
									'settigns' =>  $settings,
									'result' =>  $game->score,
									/*
									счёт партии
									длительность партии
									дополнительная аналитика партии */
								];
							}
						}
					}
				}
			
		//}
	}
	
	
	
	
	
	function UTCdatestringToTime($utcdatestring)
	{
		$tz = date_default_timezone_get();
		date_default_timezone_set('UTC');

		$result = strtotime($utcdatestring);

		date_default_timezone_set($tz);
		return $result;
	}
	
	
	
	
	
	
	
	
	


file_put_contents(__DIR__.'/top.txt', serialize($users));
	
	
	
	
	
	
	
	
?>