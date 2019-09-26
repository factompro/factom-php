<?php
/**
 * PHP SDK for Factom PRO
 * @author Factom PRO <team@factom.pro>
 */
class FactomAPI
{
    const ENDPOINT = 'https://api.factom.pro';
    const VERSION = 'v1';
    protected
        $api_key = null,
        $response = null;
        
    /**
    * API declaration
    */
    public function __construct($api_key)
    {
        $this->endpoint = self::ENDPOINT."/".self::VERSION;
	    $this->api_key = $api_key;
    }
    /**
    * Create chain
    * $extIds, array — One or many external ids identifying new chain. Should be sent as array of strings.
    * $content, string — (optional) The content of the first entry of the chain.
    * $callbackURL — (optional) URL where to send status updates.
    */
    public function createChain($extIds, $content="", $callbackURL=NULL)
    {	
	    $chain["extIds"] = $this->helper_base64_encode($extIds);
        $chain["content"] = $this->helper_base64_encode($content);

        $path = '/chains';
        if (isset($callbackURL)) {
            $path += "?callback_url=".$callbackURL;
        }

        $res = $this->make_request($path, $chain, 'POST');
        if (isset($res["result"])) {
            $res["result"]["extIds"] = $this->helper_base64_decode($res["result"]["extIds"]);
        }
        return $res;
    }

    /**
    * Create entry into chain
    * $chainId, string — Chain ID of the Factom chain, where to add new entry.
    * $extIds, array — (optional) One or many external ids identifying new chain. Should be sent as array of strings.
    * $content, string — (optional) The content of the new entry.
    * $callbackURL — (optional) URL where to send status updates.
    */
    public function createEntry($chainId, $extIds=NULL, $content="", $callbackURL=NULL)
    {
        $entry["chainId"] = $chainId;
        $entry["content"] = $this->helper_base64_encode($content);

        if (isset($extIds)) {
            $entry["extIds"] = $this->helper_base64_encode($extIds);	    
        }

        $path = '/entries';
        if (isset($callbackURL)) {
            $path += "?callback_url=".$callbackURL;
        }

        $res = $this->make_request($path, $entry, 'POST');
        if (isset($res["result"])) {
            if (isset($res["result"]["content"])) {
                $res["result"]["content"] = $this->helper_base64_decode($res["result"]["content"]);
            }
            if (isset($res["result"]["extIds"])) {
                $res["result"]["extIds"] = $this->helper_base64_decode($res["result"]["extIds"]);
            }
        }
        return $res;
    }

    /**
    * Search user's chains by extIds
    * $extIds, array — One or many external IDs, that used for search. Should be sent as array of strings.
    */
    public function searchChains($extIds, $start=0, $limit=0, $status=NULL, $sort=NULL)
    {
        $queryParams = "";
        if (($start > 0) || ($limit>0) || $sort) {
            $queryParams .= "?";
            if ($start > 0) {
                $queryParams .= "start=";
                $queryParams .= $start;
                $queryParams .= "&";
            }
            if ($limit > 0) {
                $queryParams .= "limit=";
                $queryParams .= $limit;
                $queryParams .= "&";
            }
            if ($status) {
                $queryParams .= "status=";
                $queryParams .= $status;
                $queryParams .= "&";
            }
            if ($sort) {
                $queryParams .= "sort=";
                $queryParams .= $sort;
                $queryParams .= "&";
            }
        }
        $chain["extIds"] = $this->helper_base64_encode($extIds);
        $res = $this->make_request('/chains/search'.$queryParams, $chain, 'POST');
        if (isset($res["result"])) {
            if (sizeof($res["result"])>0) {
                foreach ($res["result"] as &$i) {
                    $i["extIds"] = $this->helper_base64_decode($i["extIds"]);
                }
            }
        }
	    return $res;
    }

    /**
    * Search chain entries
    * $extIds, array — One or many external IDs, that used for search. Should be sent as array of strings.
    */
    public function searchChainEntries($chainId, $extIds, $start=0, $limit=0, $status=NULL, $sort=NULL)
    {
        $queryParams = "";
        if (($start > 0) || ($limit>0) || $sort) {
            $queryParams .= "?";
            if ($start > 0) {
                $queryParams .= "start=";
                $queryParams .= $start;
                $queryParams .= "&";
            }
            if ($limit > 0) {
                $queryParams .= "limit=";
                $queryParams .= $limit;
                $queryParams .= "&";
            }
            if ($status) {
                $queryParams .= "status=";
                $queryParams .= $status;
                $queryParams .= "&";
            }
            if ($sort) {
                $queryParams .= "sort=";
                $queryParams .= $sort;
                $queryParams .= "&";
            }
        }
        $entry["chainId"] = $chainId;
        $entry["extIds"] = $this->helper_base64_encode($extIds);
        $res = $this->make_request('/chains/'.$chainId.'/entries/search'.$queryParams, $entry, 'POST');
        if (isset($res["result"])) {
            if (sizeof($res["result"])>0) {
                foreach ($res["result"] as &$i) {
                    if (isset($i["content"])) {
                        $i["content"] = $this->helper_base64_decode($i["content"]);
                    }
                    if (isset($i["extIds"])) {
                        $i["extIds"] = $this->helper_base64_decode($i["extIds"]);
                    }
                }
            }
        }
	    return $res;
    }

    /**
    * Get user's chains
    */
    public function getChains($start=0, $limit=0, $status=NULL, $sort=NULL)
    {	    
        $queryParams = "";
        if (($start > 0) || ($limit>0) || $sort) {
            $queryParams .= "?";
            if ($start > 0) {
                $queryParams .= "start=";
                $queryParams .= $start;
                $queryParams .= "&";
            }
            if ($limit > 0) {
                $queryParams .= "limit=";
                $queryParams .= $limit;
                $queryParams .= "&";
            }
            if ($status) {
                $queryParams .= "status=";
                $queryParams .= $status;
                $queryParams .= "&";
            }
            if ($sort) {
                $queryParams .= "sort=";
                $queryParams .= $sort;
                $queryParams .= "&";
            }
        }
        $res = $this->make_request('/chains'.$queryParams);
        if (isset($res["result"])) {
            if (sizeof($res["result"])>0) {
                foreach ($res["result"] as &$i) {
                    $i["extIds"] = $this->helper_base64_decode($i["extIds"]);
                }
            }
        }
        return $res;
    }

    /**
    * Get chain
    * $chainId, string — Chain ID of the Factom chain.
    */
    public function getChain($chainId)
    {	    
        $res = $this->make_request('/chains/'.$chainId);
        if (isset($res["result"])) {
            $res["result"]["extIds"] = $this->helper_base64_decode($res["result"]["extIds"]);
        }
        return $res;
    }

    /**
    * Get chain entries
    * $chainId, string — Chain ID of the Factom chain.
    */
    public function getChainEntries($chainId, $start=0, $limit=0, $status=NULL, $sort=NULL)
    {	    
        $queryParams = "";
        if (($start > 0) || ($limit>0) || $sort) {
            $queryParams .= "?";
            if ($start > 0) {
                $queryParams .= "start=";
                $queryParams .= $start;
                $queryParams .= "&";
            }
            if ($limit > 0) {
                $queryParams .= "limit=";
                $queryParams .= $limit;
                $queryParams .= "&";
            }
            if ($status) {
                $queryParams .= "status=";
                $queryParams .= $status;
                $queryParams .= "&";
            }
            if ($sort) {
                $queryParams .= "sort=";
                $queryParams .= $sort;
                $queryParams .= "&";
            }
        }
        $res = $this->make_request('/chains/'.$chainId.'/entries'.$queryParams);
        if (isset($res["result"])) {
            if (sizeof($res["result"])>0) {
                foreach ($res["result"] as &$i) {
                    if (isset($i["content"])) {
                        $i["content"] = $this->helper_base64_decode($i["content"]);
                    }
                    if (isset($i["extIds"])) {
                        $i["extIds"] = $this->helper_base64_decode($i["extIds"]);
                    }
                }
            }
        }
        return $res;
    }

    /**
    * Get entry
    * $entryHash, string — EntryHash of the Factom entry
    */
    public function getEntry($entryHash)
    {	    
        $res = $this->make_request('/entries/'.$entryHash);
        if (isset($res["result"])) {
            if (isset($res["result"]["content"])) {
                $res["result"]["content"] = $this->helper_base64_decode($res["result"]["content"]);
            }
            if (isset($res["result"]["extIds"])) {
                $res["result"]["extIds"] = $this->helper_base64_decode($res["result"]["extIds"]);
            }
        }
        return $res;
    }

    /**
    * Get chain first entry
    * $chainId, string — Chain ID of the Factom chain.
    */
    public function getChainFirstEntry($chainId)
    {	    
        $res = $this->make_request('/chains/'.$chainId.'/entries/first');
        if (isset($res["result"])) {
            if (isset($res["result"]["content"])) {
                $res["result"]["content"] = $this->helper_base64_decode($res["result"]["content"]);
            }
            if (isset($res["result"]["extIds"])) {
                $res["result"]["extIds"] = $this->helper_base64_decode($res["result"]["extIds"]);
            }
        }
        return $res;
    }


    /**
    * Get chain last entry
    * $chainId, string — Chain ID of the Factom chain.
    */
    public function getChainLastEntry($chainId)
    {	    
        $res = $this->make_request('/chains/'.$chainId.'/entries/last');
        if (isset($res["result"])) {
            if (isset($res["result"]["content"])) {
                $res["result"]["content"] = $this->helper_base64_decode($res["result"]["content"]);
            }
            if (isset($res["result"]["extIds"])) {
                $res["result"]["extIds"] = $this->helper_base64_decode($res["result"]["extIds"]);
            }
        }
        return $res;
    }

    /**
    * Get API user
    */
    public function getUser()
    {	    
        return $this->make_request('/user');
    }

    /**
    * Get API version
    */
    public function getAPIInfo()
    {	    
        return $this->make_request('/');
    }
	
    /**
    * Make the request to Factom PRO API
    */
    protected function make_request($path, $data = NULL, $type = "GET")
    {
        $data = json_encode($data);
        
        if (function_exists('curl_init')) {
            $ch = curl_init($this->endpoint.$path);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $this->api_key,                                                                          
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
            );
            $response = curl_exec($ch);
            curl_close($ch);
        }
	    else {
		    throw new Exception("CURL PHP extension required for this script");
	    }
        
        return json_decode($response, true);
    }

    /**
     * Helper base64_encode
     */
    protected function helper_base64_encode($input)
    {

        if (is_array($input)) {
            foreach ($input as &$i) {
                $i = base64_encode($i);
            }
        } else {
            $input = base64_encode($input);
        }

        return $input;

    }

    /**
     * Helper base64_decode
     */
    protected function helper_base64_decode($input)
    {

        if (is_array($input)) {
            foreach ($input as &$i) {
                $i = base64_decode($i);
            }
        } else {
            $input = base64_decode($input);
        }
        
        return $input;

    }
}
