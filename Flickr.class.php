<?php

require_once dirname(__FILE__) . '/../cache/FileCache.class.php';

class Flickr {

	private $api_key  = null;
	private $secret   = null;
	private $cache    = null;

	public function __construct($api_key, $secret = null) {
		$this->api_key  = $api_key;
		$this->secret   = $secret;
		$this->cache    = new FileCache('flickr');
	}

	public function getPhotosByUser($user_id, $count = 20, array $extras = array()) {
		if (count($extras)) {
			$extra_string = implode(',', $extras);
		}
		$user_id = urlencode($user_id);
		
		$response = $this->fetchResponse("http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key={$this->api_key}&user_id={$user_id}&extras={$extra_string}&per_page={$count}&format=php_serial");
		
		return $response['photos']['photo'];
	}

	public function getSetsByUser($user_id, $count = 5) {
		$user_id = urlencode($user_id);
		$response = $this->fetchResponse("http://api.flickr.com/services/rest/?method=flickr.photosets.getList&api_key={$this->api_key}&user_id={$user_id}&format=php_serial");
			
		return array_slice($response['photosets']['photoset'], 0, $count);
	}

	private function fetchResponse($request) {
		$response = $this->cache->get($request);

		if (!$response) {
			$response = file_get_contents($request);
			if (!$response) {
				throw new FlickrException('Could not connect to the Flickr service.');
				return false;
			}
			
			$response = unserialize($response);
			if ($response['stat'] === 'fail') {
				throw new FlickrException($response['message']);
				return false;
			}
			
			$this->cache->set($request, $response);
		}

		return $response;
	}
	
}

class FlickrException extends Exception { }