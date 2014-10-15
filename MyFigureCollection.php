<?php
/**
 * Class for accesing MyFigureCollection.net API.
 *   
 *  @package    app.Vendor
 *  @category   vendors
 *  @author     Rafael F. Silva <rafael@omochi.com.br>
 *  @copyright  Omochi
 *  @since      1.0.0
**/

define("MFC_ITEM_FIGURES", 0);
define("MFC_ITEM_GOODS", 1);
define("MFC_ITEM_MEDIAS", 2);

define("MFC_STATUS_WISHED", 0);
define("MFC_STATUS_ORDERED", 1);
define("MFC_STATUS_OWNED", 2);

define("MFC_COIN_YEN", 0);
define("MFC_COIN_USD", 1);
define("MFC_COIN_EUR", 2);
define("MFC_COIN_BRL", 3);
define("MFC_COIN_AUD", 4);
define("MFC_COIN_CND", 5);
define("MFC_COIN_GBP", 6);
define("MFC_COIN_NZD", 7);

class MyFigureCollection
{
	private $_useCache = true;
	private $_cacheTime = 60;
	private $_username = null;
	private $_returnType = "json";
	private $_mediaType = MFC_ITEM_FIGURES;
	private $_status = MFC_STATUS_OWNED;
	private $_priceCurrency = MFC_COIN_YEN;

	public $items = array(
		'data' => array(),
		'total' => 0
	);
	public $values = array(
		'currency' => MFC_COIN_YEN,
		'total' => 0
	);

	public function __construct($username = null, $mediaType = null, $status = null, $returnType = null)
	{
		if( !is_null($username) )
		{
			$this->_username = $username;
		}

		if( !is_null($mediaType) )
		{
			$this->_mediaType = $mediaType;
		}

		if( !is_null($status) )
		{
			$this->_status = $status;
		}

		if( !is_null($returnType) )
		{
			$this->_returnType = $returnType;
		}
	}

	public function setCurrency($currency = null)
	{
		if( is_null($currency) || is_numeric($currency) || $currency < 0 || $currency > 7 )
		{
			throw new Exception("MFC API: Invalid currency.");
		}

		$this->_priceCurrency = $currency;
	}

	public function query()
	{
		if( is_null($this->_username) )
		{
			throw new Exception("MFC API: Username is not defined.");
		}

		$currentPage = 0;

		// TODO: cache control
		// if( $this->_useCache && isset($_COOKIE['mfcapi_items']) )
		// {
		// 	$this->items = json_decode($_COOKIE['mfcapi_items']);
		// 	echo "unsing cookies!";
		// }
		// else
		// {
			do
			{
				$url = sprintf(
					"http://myfigurecollection.net/api.php?mode=collection&type=%s&username=%s&root=%d&status=%d&page=%d",
					$this->_returnType,
					$this->_username,
					$this->_mediaType,
					$this->_status,
					$currentPage
				);

				if( function_exists("curl_init") )
				{
					$cURL = curl_init($url);
					curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
					$output = curl_exec($cURL);
					curl_close($cURL);
				}
				elseif( !($output = file_get_contents($url)) )
				{
					throw new Exception("MFC API: Could not open the URL {$url}.");
				}

				// TODO: check json
				$output = json_decode($output, true);

				$this->items['total'] = $output['collection']['owned']['num_items'];
				$this->items['data'] = array_merge($this->items['data'], $output['collection']['owned']['item']);

				$currentPage++;
			} while( $currentPage < $output['collection']['owned']['num_pages'] );

			// $cookieExpiration = 0;

			// if( !is_null($this->_cacheTime) && $this->_cacheTime > 0 )
			// {
			// 	$cookieExpiration = time() + $this->_cacheTime;
			// }

			// if( !setcookie("mfcapi_items", json_encode($this->items), $cookieExpiration) )
			// {
			// 	throw new Exception("MFC API: Could not set the cookies.");
			// }
		// }

		foreach($this->items['data'] as $item)
		{
			$this->values['total'] += $item['data']['price'];
		}
	}
}