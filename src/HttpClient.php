<?php

namespace BasicHttpClient;

class HttpClient
{

	/**
	 * Request methods
	 */
	const REQUEST_METHOD_GET = 0;
	const REQUEST_METHOD_POST = 1;
	const REQUEST_METHOD_PUT = 2;
	const REQUEST_METHOD_PATCH = 3;
	const REQUEST_METHOD_DELETE = 4;

	/**
	 * The user agent name
	 *
	 * @var string
	 */
	protected $userAgent;

	/**
	 * The url to call
	 *
	 * @var string
	 */
	protected $requestEndpoint;

	/**
	 * Whether to use SSL
	 *
	 * @var bool
	 */
	protected $requestSecureEndpoint = false;

	/**
	 * Whether to verify the peer SSL certificate
	 *
	 * @var bool
	 */
	protected $requestVerifyPeer = true;

	/**
	 * The CA certificate to trust as PKCS12
	 *
	 * Has to be an absolute path.
	 *
	 * @var string
	 */
	protected $requestCaCertificatePath = '/';

	/**
	 * The reuqest method
	 *
	 * @var int
	 */
	protected $requestMethod = self::REQUEST_METHOD_GET;

	/**
	 * Array of request header objects
	 *
	 * @var \Propeller\Lib\HttpClient\Base\HttpRequestHeader[]
	 */
	protected $requestHeader = array();

	/**
	 * Array of the effective request header
	 *
	 * @var array
	 */
	protected $requestHeaderEffective = array();

	/**
	 * The reuqest body data as array
	 *
	 * @var array
	 */
	protected $requestBody = array();

	/**
	 * Whether caching is allowed
	 *
	 * @var bool
	 */
	protected $allowCaching = true;

	/**
	 * Whether to follow redirects
	 *
	 * @var bool
	 */
	protected $followRedirects = false;

	/**
	 * The session cookie to be used for the request
	 *
	 * @var \Propeller\Lib\HttpClient\Base\HttpRequestCookie
	 */
	protected $sessionCookie;

	/**
	 * The response header
	 *
	 * @var array
	 */
	protected $responseHeader;

	/**
	 * The response HTTP status
	 *
	 * @var int
	 */
	protected $responseStatus;

	/**
	 * The response body
	 *
	 * @var
	 */
	protected $responseBody;

	public function __construct()
	{
		$this->userAgent = 'PHP Basic HTTP Client';
	}

	/**
	 * Builds the request body data as array
	 *
	 * @param array $body
	 * @return $this
	 */
	public function setRequestBodyValues(array $body)
	{
		$this->requestBody = $body;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getRequestBody()
	{
		return $this->requestBody;
	}

	/**
	 * Fires the request
	 *
	 * @throws \Propeller\Lib\Exceptions\RequestException
	 * @return void
	 */
	public function request()
	{
		set_time_limit(5);
		// Setup curl
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->getUrl());
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
		// Add basic auth
		if ($this->basicAuth) {
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curl, CURLOPT_USERPWD, $this->basicAuthUsername . ':' . $this->basicAuthPassword);
		}
		// Caching
		if (!$this->allowCaching) {
			curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		}
		// SSL cert stuff
		if ($this->requestSecureEndpoint) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->requestVerifyPeer);
			if ($this->requestVerifyClient) {
				curl_setopt($curl, CURLOPT_CAINFO, $this->requestCaCertificatePath);
				curl_setopt($curl, CURLOPT_SSLCERT, $this->requestClientCertificatePath);
				curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $this->requestClientCertificatePassword);
			}
		}
		// Setup accept- and content-type-header array and build request body
		$requestHeaders = array();
		switch ($this->requestType) {
			case self::REQUEST_TYPE_HTML:
				$requestHeaders[] = 'Accept: text/html';
				$requestHeaders[] = 'Content-Type: text/html';
				$requestBody = http_build_query($this->requestBody);
				break;
			case self::REQUEST_TYPE_RESTFUL_JSON:
				$requestHeaders[] = 'Accept: application/json';
				$requestHeaders[] = 'Content-Type: application/json';
				$requestBody = Lib\Util\Parser\Json::serialize($this->requestBody);
				break;
			case self::REQUEST_TYPE_JSON_RPC:
				$requestHeaders[] = 'Accept: application/json-rpc';
				$requestHeaders[] = 'Content-Type: application/json-rpc';
				/** @noinspection PhpMethodParametersCountMismatchInspection */
				/** @noinspection PhpVoidFunctionResultUsedInspection */
				$requestBody = Lib\Util\Parser\JsonRpc::serialize($this->requestBody);
				break;
			case self::REQUEST_TYPE_XML_RPC:
				$requestHeaders[] = 'Accept: application/xml-rpc';
				$requestHeaders[] = 'Content-Type: application/xml-rpc';
				$xmlRpcParser = new Lib\Util\Parser\XmlRpc();
				$xmlRpcParser->serialize($this->requestBody);
				$requestBody = $xmlRpcParser->response;
				break;
			case self::REQUEST_TYPE_SOAP:
				$requestHeaders[] = 'Accept: application/soap+xml';
				$requestHeaders[] = 'Content-Type: application/soap+xml';
				/** @noinspection PhpMethodParametersCountMismatchInspection */
				/** @noinspection PhpVoidFunctionResultUsedInspection */
				$requestBody = Lib\Util\Parser\Soap::serialize($this->requestBody);
				break;
			default:
				$requestHeaders[] = 'Accept: */*';
				$requestHeaders[] = 'Content-Type: */*';
				$requestBody = '';
				break;
		}
		// Add custom header
		if (count($this->requestHeader) > 0) {
			foreach ($this->requestHeader as $requestHeader) {
				$requestHeaders[] = $requestHeader->getName() . ': ' . $requestHeader->getValue();
			}
		}
		// Set content-length
		if ($this->requestMethod != self::REQUEST_METHOD_GET && $this->requestMethod != self::REQUEST_METHOD_DELETE) {
			$requestHeaders[] = 'Content-Length: ' . strlen($requestBody);
		}
		// Configure different request methods
		switch ($this->requestMethod) {
			case self::REQUEST_METHOD_GET:
				if (count($this->requestBody) > 0) {
					$requestQuery = $requestBody;
					if (strpos($this->getUrl(), '?') !== false) {
						$url = $this->getUrl() . '&' . $requestQuery;
					} else {
						$url = $this->getUrl() . '?' . $requestQuery;
					}
					curl_setopt($curl, CURLOPT_URL, $url);
				}
				curl_setopt($curl, CURLOPT_HTTPGET, true);
				break;
			case self::REQUEST_METHOD_POST:
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
				break;
			case self::REQUEST_METHOD_PUT:
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
				break;
			case self::REQUEST_METHOD_PATCH:
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
				curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
				break;
			case self::REQUEST_METHOD_DELETE:
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
				break;
		}
		// Set http header to curl
		curl_setopt($curl, CURLOPT_HTTPHEADER, $requestHeaders);
		// Prepare cookies
		$requestCookies = array();
		if (isset($this->sessionCookie)) {
			$requestCookies[] = $this->sessionCookie->getName() . '=' . $this->sessionCookie->getValue();
		}
		if (count($this->requestCookies) > 0) {
			foreach ($this->requestCookies as $requestCookie) {
				$requestCookies[] = $requestCookie->getName() . '=' . $requestCookie->getValue();
			}
		}
		if (count($requestCookies) > 0) {
			curl_setopt($curl, CURLOPT_COOKIE, implode(';', $requestCookies));
		}

		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

		// Execute request
		$this->responseBody = curl_exec($curl);

		$curlErrorCode = curl_errno($curl);
		$curlError = curl_error($curl);
		$this->responseStatus = intval(curl_getinfo($curl, CURLINFO_HTTP_CODE));

		// Parse response
		$this->requestHeaderEffective = preg_split(
			'/\r\n/',
			curl_getinfo($curl, CURLINFO_HEADER_OUT),
			null,
			PREG_SPLIT_NO_EMPTY
		);
		$this->responseHeader = array();
		if (strpos($this->responseBody, "\r\n\r\n") !== false) {
			do {
				list($responseHeader, $this->responseBody) = explode("\r\n\r\n", $this->responseBody, 2);
				$responseHeaderLines = explode("\r\n", $responseHeader);
				$responseHeaderHttpStatus = $responseHeaderLines[0];
				$responseHeaderHttpStatusCode = (int)substr(
					trim($responseHeaderHttpStatus),
					strpos($responseHeaderHttpStatus, ' ') + 1,
					3
				);
			} while (
				strpos($this->responseBody, "\r\n\r\n") !== false
				&& (
					!($responseHeaderHttpStatusCode >= 200 && $responseHeaderHttpStatusCode < 300)
					|| !$responseHeaderHttpStatusCode >= 400
				)
			);
			$this->responseHeader = preg_split('/\r\n/', $responseHeader, null, PREG_SPLIT_NO_EMPTY);
		}

		// Close connection
		curl_close($curl);
		session_write_close();
		// Check for errors and throw exception
		if ($curlErrorCode > 0) {
			$exception = new Exceptions\RequestException(
				'HTTP_CLIENT_REQUEST_FAILED',
				25300,
				null,
				Exceptions\RequestException::LEVEL_NOTICE
			);
			$exception->setDetails(
				array(
					'CURL_ERROR' => $curlError,
					'CURL_ERROR_CODE' => $curlErrorCode,
				)
			);
			throw $exception;
		}
	}

	/**
	 * Builds the URL from endpoint settings
	 */
	private function getUrl()
	{
		$scheme = ($this->requestSecureEndpoint) ? 'https://' : 'http://';
		return $scheme . Lib\Util\StringUtil::urlRemoveScheme($this->requestEndpoint);
	}

	/**
	 * Setter for the reuqest endpoint
	 *
	 * @param string $requestEndpoint
	 * @return $this
	 */
	public function setRequestEndpoint($requestEndpoint)
	{
		$this->requestEndpoint = $requestEndpoint;
		return $this;
	}

	/**
	 * Getter for the reuqest endpoint
	 *
	 * @return string
	 */
	public function getRequestEndpoint()
	{
		return $this->requestEndpoint;
	}

	/**
	 * Adds a request header object
	 *
	 * @param \Propeller\Lib\HttpClient\Base\HttpRequestHeader $requestHeader
	 * @return $this
	 */
	public function addRequestHeader($requestHeader)
	{
		$this->requestHeader[] = $requestHeader;
		return $this;
	}

	/**
	 * Getter for the request header objects
	 *
	 * @return \Propeller\Lib\HttpClient\Base\HttpRequestHeader[]
	 */
	public function getRequestHeader()
	{
		return $this->requestHeader;
	}

	/**
	 * Getter for the effective submitted request headers.
	 *
	 * @return array
	 */
	public function getRequestHeaderEffective()
	{
		return $this->requestHeaderEffective;
	}

	/**
	 * Setter for for the request method
	 *
	 * @param int $requestMethod
	 * @return $this
	 */
	public function setRequestMethod($requestMethod)
	{
		$this->requestMethod = $requestMethod;
		return $this;
	}

	/**
	 * Getter for the request method
	 *
	 * @return int
	 */
	public function getRequestMethod()
	{
		return $this->requestMethod;
	}

	/**
	 * Setter for the secure endpoint
	 *
	 * @param bool $requestSecureEndpoint
	 * @return $this
	 */
	public function setRequestSecureEndpoint($requestSecureEndpoint)
	{
		$this->requestSecureEndpoint = $requestSecureEndpoint;
		return $this;
	}

	/**
	 * Getter for the secure endpoint
	 *
	 * @return bool
	 */
	public function getRequestSecureEndpoint()
	{
		return $this->requestSecureEndpoint;
	}

	/**
	 * Setter for the user agent name
	 *
	 * @param string $userAgent
	 * @return $this
	 */
	public function setUserAgent($userAgent)
	{
		$this->userAgent = $userAgent;
		return $this;
	}

	/**
	 * Getter for the user agent name
	 *
	 * @return string
	 */
	public function getUserAgent()
	{
		return $this->userAgent;
	}

	/**
	 * Setter for allowing chaching
	 *
	 * @param bool $allowCaching
	 * @return $this
	 */
	public function setAllowCaching($allowCaching)
	{
		$this->allowCaching = $allowCaching;
		return $this;
	}

	/**
	 * Getter for allowing chaching
	 *
	 * @return bool
	 */
	public function getAllowCaching()
	{
		return $this->allowCaching;
	}

	/**
	 * @return boolean
	 */
	public function getFollowRedirects()
	{
		return $this->followRedirects;
	}

	/**
	 * @param boolean $followRedirects
	 * @return $this
	 */
	public function setFollowRedirects($followRedirects)
	{
		$this->followRedirects = $followRedirects;
		return $this;
	}

	/**
	 * @param bool $requestVerifyPeer
	 * @return $this
	 */
	public function setRequestVerifyPeer($requestVerifyPeer)
	{
		$this->requestVerifyPeer = $requestVerifyPeer;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getRequestVerifyPeer()
	{
		return $this->requestVerifyPeer;
	}

	/**
	 * Getter for the response body
	 *
	 * @return mixed
	 */
	public function getResponseBody()
	{
		return $this->responseBody;
	}

	/**
	 * Getter for the response status
	 *
	 * @return int
	 */
	public function getResponseStatus()
	{
		return $this->responseStatus;
	}

	/**
	 * Gets the response status as text e.g. `HTTP/1.0 401 Unauthorized`
	 *
	 * @return string
	 */
	public function getResponseStatusText()
	{
		foreach ($this->responseHeader as $header) {
			if (strpos($header, ':') === false && strpos(strtoupper($header), 'HTTP/') === 0) {
				return $header;
			}
		}
		return '';
	}

	/**
	 * Getter for the response header
	 *
	 * @return array
	 */
	public function getResponseHeader()
	{
		return $this->responseHeader;
	}

	/**
	 * Sets the sesison cookie for the request
	 *
	 * @param \Propeller\Lib\HttpClient\Base\HttpRequestCookie $sessionCookie
	 * @return $this
	 */
	public function setSessionCookie($sessionCookie)
	{
		$this->sessionCookie = $sessionCookie;
		return $this;
	}

	/**
	 * Whether the response contains session cookie information
	 *
	 * @return bool
	 */
	public function hasSessionCookie()
	{
		foreach ($this->responseHeader as $header) {
			if (strpos(strtoupper($header), 'SET-COOKIE: ') === 0) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Gets the session cookie for the next request
	 *
	 * @return \Propeller\Lib\HttpClient\Base\HttpRequestCookie
	 */
	public function getSessionCookie()
	{
		$sessionCookie = null;
		foreach ($this->responseHeader as $header) {
			if (strpos(strtoupper($header), 'SET-COOKIE: ') === 0) {
				$value = trim(substr($header, 11));
				if (strpos($value, ';') !== false) {
					$value = trim(substr($value, 0, strpos($value, ';')));
				}
				if (strpos($value, '=') !== false) {
					$key = trim(substr($value, 0, strpos($value, '=')));
					$value = trim(substr($value, strpos($value, '=') + 1));
					$cookie = new Base\HttpRequestCookie($key, $value);
					$sessionCookie = $cookie;
				}
			}
		}
		return $sessionCookie;
	}

}
