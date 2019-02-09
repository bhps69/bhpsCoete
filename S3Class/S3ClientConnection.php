<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require(__DIR__ .'/../aws/aws-autoloader.php');

use Aws\S3\S3Client;

Class S3 {
    public function S3Connection() 
    {
        $s3 = new S3Client([
			'region'  => S3_REGION,
			'version' => 'latest',
			'credentials' => [
				'key'    => S3_KEY,
				'secret' => S3_SECRET,
			]
		]);
        
        return $s3;
    }
}