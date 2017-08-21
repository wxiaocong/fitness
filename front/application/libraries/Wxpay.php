<?php
class Wxpay {
	const TRADETYPE_JSAPI  = 'JSAPI',TRADETYPE_NATIVE = 'NATIVE',TRADETYPE_APP = 'APP';
	const URL_UNIFIEDORDER = "https://api.mch.weixin.qq.com/pay/unifiedorder";
	const URL_ORDERQUERY   = "https://api.mch.weixin.qq.com/pay/orderquery";
	const URL_CLOSEORDER   = 'https://api.mch.weixin.qq.com/pay/closeorder';
	const URL_REFUND       = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
	const URL_REFUNDQUERY  = 'https://api.mch.weixin.qq.com/pay/refundquery';
	const URL_DOWNLOADBILL = 'https://api.mch.weixin.qq.com/pay/downloadbill';
	const URL_REPORT       = 'https://api.mch.weixin.qq.com/payitil/report';
	const URL_SHORTURL     = 'https://api.mch.weixin.qq.com/tools/shorturl';
	const URL_MICROPAY     = 'https://api.mch.weixin.qq.com/pay/micropay';
	/**
	 * 错误信息
	 */
	public $error = null;
	/**
	 * 错误信息XML
	 */
	public $errorXML = null;
	/**
	 * 微信支付配置数组
	 * appid        公众账号appid
	 * mch_id       商户号
	 * apikey       加密key
	 * appsecret    公众号appsecret
	 * sslcertPath  证书路径(apiclient_cert.pem)
	 * sslkeyPath   密钥路径(apiclient_key.pem)
	 */
	private $_config;

	/**
	 * @param $config 微信支付配置数组
	 */
	public function __construct($config = NULL) {
		$this->_config = $config;
	}

	/**
	 * 统一下单接口
	 */
	public function unifiedOrder($params) {
		$data = array();
		$data["appid"]            = $this->_config["appid"];
		$data["mch_id"]           = $this->_config["mch_id"];
		$data["nonce_str"]        = $this->getNonceString();
		$data["body"]             = $params['body'];
		$data["total_fee"]        = $params['total_fee'];
		$data["notify_url"]       = $params['notify_url'];
		$data["spbill_create_ip"] = isset($params['spbill_create_ip'])?$params['spbill_create_ip']:$_SERVER['REMOTE_ADDR'];
		$data["device_info"]      = (isset($params['device_info'])&&trim($params['device_info'])!='')?$params['device_info']:null;
		$data["detail"]           = isset($params['detail'])?$params['detail']:null;
		$data["attach"]           = isset($params['attach'])?$params['attach']:null;
		$data["out_trade_no"]     = isset($params['out_trade_no'])?$params['out_trade_no']:null;
		$data["fee_type"]         = isset($params['fee_type'])?$params['fee_type']:'CNY';
		$data["time_start"]       = isset($params['time_start'])?$params['time_start']:null;
		$data["time_expire"]      = isset($params['time_expire'])?$params['time_expire']:null;
		$data["goods_tag"]        = isset($params['goods_tag'])?$params['goods_tag']:null;
		$data["trade_type"]       = isset($params['trade_type'])?$params['trade_type']:TRADETYPE_APP; #交易类型 JSAPI | NATIVE | APP | WAP 
		$data["product_id"]       = isset($params['product_id'])?$params['product_id']:null; #required when trade_type = NATIVE
		$data["openid"]           = isset($params['openid'])?$params['openid']:null;# required when trade_type = JSAPI
		$result = $this->postXmlCurl(self::URL_UNIFIEDORDER, $data);
		return $result;
	}

	/**
	 * 以post方式提交xml到对应的接口url
	 * 
	 * @param string $url  url
	 * @param string $xml  需要post的xml数据
	 * @param bool $useCert 是否需要证书，默认不需要
	 * @param int $second   url执行超时时间，默认30s
	 * @throws WxPayException
	 */
	private function postXmlCurl($url, $data, $cert = false, $second = 30) {
		$data["sign"] = $this->sign($data);
		$xml = $this->array2xml($data);
		$ch  = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); # SSL证书认证
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); # 严格认证
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);	# 设置超时
		curl_setopt($ch, CURLOPT_POST, 1);	# POST提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);	# POST数据
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); # 要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_URL, $url);
		if($cert == true){
			// 使用证书：cert 与 key 分别属于两个.pem文件
			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLCERT, $this->_config['sslcertPath']);
			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLKEY, $this->_config['sslkeyPath']);
		}
		$content = curl_exec($ch);	# 运行curl
		//返回结果
		if($content){
			curl_close($ch);
			return $this->xml2array($content);
		} else { 
			$error = curl_errno($ch);
			curl_close($ch);
			return false;
		}
	}

	/**
	 * 数据签名
	 * @param $params
	 * @return string
	 */
	public function sign($params) {
		//签名步骤一：按字典序排序数组参数
		ksort($params);
		$string = $this->arr2UrlParams($params);
		//签名步骤二：在string后加入KEY
		$string = $string . "&key=".$this->_config["key"];
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		return $result;
	}

	/**
	 * 输出xml字符
	 * @param	$params		参数名称
	 * return	string		返回组装的xml
	 **/
	public function array2xml($array) {
		$xml = "<xml>" . PHP_EOL;
		foreach ($array as $k => $v) {
			if($v && trim($v)!='')
				$xml .= "<$k><![CDATA[$v]]></$k>" . PHP_EOL;
		}
		$xml .= "</xml>";
		return $xml;
	}

	/**
     * 将xml转为array
     * @param string $xml
	 * return array
     */
	public function xml2array($xml) {
		$array = array();
		$tmp = null;
		try{
			$tmp = (array) simplexml_load_string($xml);
		}catch(Exception $e){}
		if($tmp && is_array($tmp)){
			foreach ( $tmp as $k => $v) {
				$array[$k] = (string) $v;
			}
		}
		return $array;
	}

	/**
	 * 扫码支付(模式二)获取支付二维码
	 * @param $body
	 * @param $out_trade_no
	 * @param $total_fee
	 * @param $notify_url
	 * @param $product_id
	 * @return null
	 */
	public function getCodeUrl($body,$out_trade_no,$total_fee,$notify_url,$product_id){
		$data = array();
		$data["nonce_str"]    = $this->getNonceString();
		$data["body"]         = $body;
		$data["out_trade_no"] = $out_trade_no;
		$data["total_fee"]    = $total_fee;
		$data["spbill_create_ip"] = $_SERVER["SERVER_ADDR"];
		$data["notify_url"]   = $notify_url;
		$data["trade_type"]   = self::TRADETYPE_NATIVE;
		$data["product_id"]   = $product_id;
		$result = $this->unifiedOrder($data);
		if ($result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
			return $result["code_url"];
		} else {
			$this->error = $result["return_code"] == "SUCCESS" ? $result["err_code_des"] : $result["return_msg"];
			return null;
		}
	}

	/**
	 * 查询订单
	 * @param $transaction_id
	 * @param $out_trade_no
	 * @return array
	 */
	public function orderQuery($out_trade_no){
		$data                 = array();
		$data["appid"]        = $this->_config["appid"];
		$data["mch_id"]       = $this->_config["mch_id"];
		$data["out_trade_no"] = $out_trade_no;
		$data["nonce_str"]    = $this->getNonceString();
		$result               = $this->postXmlCurl(self::URL_ORDERQUERY, $data);
		return $result;
	}

	/**
	 * 关闭订单
	 * @param $out_trade_no
	 * @return array
	 */
	public function closeOrder($out_trade_no){
		$data                 = array();
		$data["appid"]        = $this->_config["appid"];
		$data["mch_id"]       = $this->_config["mch_id"];
		$data["out_trade_no"] = $out_trade_no;
		$data["nonce_str"]    = $this->getNonceString();
		$result               = $this->postXmlCurl(self::URL_CLOSEORDER, $data);
		return $result;
	}

	/**
	 * 申请退款 - 使用商户订单号
	 * @param $out_trade_no 商户订单号
	 * @param $out_refund_no 退款单号
	 * @param $total_fee 总金额（单位：分）
	 * @param $refund_fee 退款金额（单位：分）
	 * @param $op_user_id 操作员账号
	 * @return array
	 */
	public function refund($out_trade_no,$out_refund_no,$total_fee,$refund_fee,$op_user_id){
		$data                  = array();
		$data["appid"]         = $this->_config["appid"];
		$data["mch_id"]        = $this->_config["mch_id"];
		$data["nonce_str"]     = $this->getNonceString();
		$data["out_trade_no"]  = $out_trade_no;
		$data["out_refund_no"] = $out_refund_no;
		$data["total_fee"]     = $total_fee;
		$data["refund_fee"]    = $refund_fee;
		$data["op_user_id"]    = $op_user_id;
		$result                = $this->postXmlCurl(self::URL_REFUND, $data,true);

		return $result;
	}

	/**
	 * 申请退款 - 使用微信订单号
	 * @param $out_trade_no 商户订单号
	 * @param $out_refund_no 退款单号
	 * @param $total_fee 总金额（单位：分）
	 * @param $refund_fee 退款金额（单位：分）
	 * @param $op_user_id 操作员账号
	 * @return array
	 */
	public function refundByTransId($transaction_id,$out_refund_no,$total_fee,$refund_fee,$op_user_id){
		$data                   = array();
		$data["appid"]          = $this->_config["appid"];
		$data["mch_id"]         = $this->_config["mch_id"];
		$data["nonce_str"]      = $this->getNonceString();
		$data["transaction_id"] = $transaction_id;
		$data["out_refund_no"]  = $out_refund_no;
		$data["total_fee"]      = $total_fee;
		$data["refund_fee"]     = $refund_fee;
		$data["op_user_id"]     = $op_user_id;
		$result                 = $this->postXmlCurl(self::URL_REFUND, $data,true);
		return $result;
	}

	/**
	 * 下载对账单
	 * @param $bill_date 下载对账单的日期，格式：20140603
	 * @param $bill_type 类型
	 * @return array
	 */
	public function downloadBill($bill_date,$bill_type = 'ALL'){
		$data              = array();
		$data["appid"]     = $this->_config["appid"];
		$data["mch_id"]    = $this->_config["mch_id"];
		$data["bill_date"] = $bill_date;
		$data["bill_type"] = $bill_type;
		$data["nonce_str"] = $this->getNonceString();
		$result            = $this->postXmlCurl(self::URL_DOWNLOADBILL, $data);
		return $result;
	}

	/**
	 * JSAPI获取prepay_id
	 * @param $body
	 * @param $out_trade_no
	 * @param $total_fee
	 * @param $notify_url
	 * @param $openid
	 * @return null
	 */
	public function getPrepayId($body,$out_trade_no,$total_fee,$notify_url,$openid) {
		$data = array();
		$data["nonce_str"]    = $this->getNonceString();
		$data["body"]         = $body;
		$data["out_trade_no"] = $out_trade_no;
		$data["total_fee"]    = $total_fee;
		$data["spbill_create_ip"] = $_SERVER["REMOTE_ADDR"];
		$data["notify_url"]   = $notify_url;
		$data["trade_type"]   = self::TRADETYPE_JSAPI;
		$data["openid"]   = $openid;
		$result = $this->unifiedOrder($data);
		if ($result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
			return $result["prepay_id"];
		} else {
			$this->error = $result["return_code"] == "SUCCESS" ? $result["err_code_des"] : $result["return_msg"];
			$this->errorXML = $this->array2xml($result);
			return null;
		}
	}

	/**
	 * 获取js支付使用的第二个参数
	 */
	public function getPackage($prepay_id) {
		$data = array();
		$data["appId"] = $this->_config["appid"];
		$data["timeStamp"] = time();
		$data["nonceStr"]  = $this->getNonceString();
		$data["package"]   = "prepay_id=$prepay_id";
		$data["signType"]  = "MD5";
		$data["paySign"]   = $this->sign($data);
		return $data;
	}

	/**
	 * 获取发送到通知地址的数据(在通知地址内使用)
	 * @return 结果数组，如果不是微信服务器发送的数据返回null
	 *          appid
	 *          bank_type
	 *          cash_fee
	 *          fee_type
	 *          is_subscribe
	 *          mch_id
	 *          nonce_str
	 *          openid
	 *          out_trade_no    商户订单号
	 *          result_code
	 *          return_code
	 *          sign
	 *          time_end
	 *          total_fee       总金额
	 *          trade_type
	 *          transaction_id  微信支付订单号
	 */
	public function getBackData() {
		$xml = file_get_contents("php://input");
		$data = $this->xml2array($xml);
		if ($this->validate($data)) {
			return $data;
		} else {
			return null;
		}
	}

	/**
	 * 验证数据签名
	 * @param $data 数据数组
	 * @return 数据校验结果
	 */
	public function validate($data) {
		if (!isset($data["sign"])) {
			return false;
		}
		$sign = $data["sign"];
		unset($data["sign"]);
		return $this->sign($data) == $sign;
	}

	/**
	 * 响应微信支付后台通知
	 * @param $return_code 返回状态码 SUCCESS/FAIL
	 * @param $return_msg  返回信息
	 */
	public function responseBack($return_code="SUCCESS", $return_msg=null) {
		$data = array();
		$data["return_code"] = $return_code;
		if ($return_msg) {
			$data["return_msg"] = $return_msg;
		}
		$xml = $this->array2xml($data);

		print $xml;
	}

	/**
	 * 产生一个指定32位的随机字符串
	 * @return string 随机字符串
	 */
	private function getNonceString() {
		return substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"),0,32);
	}

	/**
	 * 生成APP端支付参数
	 * @param	$prepayid	预支付id
	 */
	public function getAppPayParams( $prepayid ){
		$data = array();
		$data['timestamp'] = time();
		$data['appid']     = $this->_config["appid"];
		$data['partnerid'] = $this->_config["mch_id"];
		$data['package']   = 'Sign=WXPay';
		$data['prepayid']  = $prepayid;
		$data['noncestr']  = $this->getNonceString();
		$data['sign']      = $this->sign( $data );
		return $this->arr2UrlParams($data,1);
	}

	/**
	 * 将参数拼接为url: key=value&key=value
	 * @param	$params
	 * @return	string
	 */
	public function arr2UrlParams( $params, $isEncode = 0){
		$string = '';
		foreach ($params as $k => $v) {
			if (!is_array($v) && trim($v)!='') {
				if($isEncode){
					$string .= "{$k}=".urlencode($v)."&";
				}else{
					$string .= "{$k}={$v}&";
				}
			}
		}
		return rtrim($string,'&');
	}
}

