<?php

namespace Tabscanner;

use GuzzleHttp\Client;

class Api
{
	private $api_key;
	private $api_url;

	public function __construct($api_key, $api_url = 'https://api.tabscanner.com/')
    {
        $this->api_key = $api_key;
        $this->api_url = $api_url;
    }

    public function upload($file, $credit = 0)
    {
    	$client = new Client();
        $api_upload_url = $this->api_url . $this->api_key . '/process';
    	$file_type = gettype($file);
    	$validate = $this->validate($file);

        if ($credit) {   
            $api_upload_url = $this->api_url . $this->api_key . '/process/' . $credit;
        }

    	if ($validate['error']) {
    		$response = [
    			'message' => $validate['message'],
    			'status' => 'failed',
    		];

    		return $response;
    	}

    	switch ($file_type) {
    		//if from form upload
    		case 'array':
    			$filename = $file['name'];
    			$content = fopen($file['tmp_name'], 'r');
    			break;

			//filepath
			case 'string':
    			$filename = basename($file);
    			$content = fopen($file, 'r');
    			break;

            case 'object':
                $filename = $file->getClientOriginalName();
                $content = fopen($file->getPathname(), 'r');
                break;
    	}

    	$response = $client->request('POST', $api_upload_url, [
            'multipart' => [
                [
                    'name'     => 'file',
                    'filename' => $filename,
                    'contents' => $content
                ]
            ]
        ]);

    	$response_decoded = json_decode($response->getBody(), true);

    	return $response_decoded;
    }

    public function result($token)
    {
    	$client = new Client();

    	$api_result_url = $this->api_url . $this->api_key . '/result/' . $token;

    	$response = $client->get($api_result_url);
    	$response_decoded = json_decode($response->getBody(), true);

    	return $response_decoded;
    }

    public function validate($file)
    {
    	$file_type = gettype($file);
    	$allowed = ['gif', 'png', 'jpg', 'jpeg'];

    	switch ($file_type) {
    		//if from form upload
    		case 'array':
    			if (!isset($file['name']) || !isset($file['tmp_name']) || !isset($file['type'])) {
    				return [
    					'error' => true,
    					'message' => 'Missing key required from array',
    				];
    			}

				$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

				if (!in_array(strtolower($ext), $allowed)) {
					return [
    					'error' => true,
    					'message' => 'File type not supported',
    				];
				}

    			$content = fopen($file['tmp_name'], 'r');

    			if (!$content) {
    				return [
    					'error' => true,
    					'message' => 'Missing file content',
    				];
    			}

    			break;

			//filepath
			case 'string':
    			$ext = pathinfo(basename($file), PATHINFO_EXTENSION);

    			if (!in_array(strtolower($ext), $allowed)) {
					return [
    					'error' => true,
    					'message' => 'File type not supported',
    				];
				}

				$content = fopen($file, 'r');

    			if (!$content) {
    				return [
    					'error' => true,
    					'message' => 'Missing file content',
    				];
    			}

    			break;

            case 'object':
                break;
    		
    		default:
    			return [
					'error' => true,
					'message' => 'Input parameter not supported',
				];

    			break;

			return [
				'error' => false,
				'message' => 'File looks clean',
			];
    	}
    }
}