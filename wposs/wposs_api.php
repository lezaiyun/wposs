<?php
/**
 * Created by PhpStorm.
 * User: zdl25
 * Date: 2019/1/15
 * Time: 16:42
 */
namespace WPOSS;

if (is_file(__DIR__ . '/sdk/aliyun-oss-php-sdk/autoload.php')) {
	require_once __DIR__ . '/sdk/aliyun-oss-php-sdk/autoload.php';
}

use OSS\OssClient;
use OSS\Core\OssException;


class Api {

	private $options = array();
	private $client;
	private $errors = array();

	public function __construct($options = array()) {
		$this->options = $options;
		
		try {
			$this->client = new OssClient($this->options['accessKeyId'], $this->options['accessKeySecret'], $this->options['endpoint'], $this->options['cname']);
			if (!$this->client->doesBucketExist($this->options['bucket'])) {
				$this->client =  Null;
				$this->errors[] = "Bucket 不存在！";
			};
		} catch (OssException $e) {
			$this->errors[] = $e->getMessage();
		}
	}

	/**
	 * 判断bucket是否存在
	 * @param $accessKeyId : $accessKeyId
	 * @param $accessKeySecret :
	 * @param $endpoint :
	 * @param $bucket : bucket name
	 * @return array, 1 (存在), 0 (不存在), -1 (异常)
	 */
	static public function does_bucket_exist($accessKeyId, $accessKeySecret, $endpoint, $bucket) {
		try {
			$client = new OssClient($accessKeyId, $accessKeySecret, $endpoint, False);
			if ($client->doesBucketExist($bucket)) {
				return array(
					"status" => 1,
					"msg" => "Bucket 存在!",
				);
			} else {
				return array(
					"status" => 0,
					"msg" => "Bucket 不存在!",
				);
			}
		} catch (OssException $e) {
			return array("status" => -1, "msg" => $e->getMessage(),);
		}
	}

	/**
	 * 上传文件到OSS
	 * @param $object : 文件名称
	 * @param $filePath : 需要上传的文件路径，例如/users/local/my_file.txt
	 */
	public function upload_file($object, $filePath) {
		try{
			$exist = $this->client->doesObjectExist($this->options['bucket'], $object);
			if (!$exist) {
				$this->client->uploadFile($this->options['bucket'], $object, $filePath);
			} else {
				$this->errors[] = "该object或object name已存在！";
			}
		} catch(OssException $e) {
			$this->errors[] = $e->getMessage();
			return;
		}
	}

	/**
	 * 删除远程OSS对象
	 * @param $object
	 */
	public function delete_file($object) {
		$this->client->deleteObject($this->options['bucket'], $object);
	}
}