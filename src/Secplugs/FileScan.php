<?php

/**
 * The Secplugs FileScan PHP client
 *
 * This gives an easy way to integrate security into your PHP application via
 * the Secplugs APIs.
 *
 * @package secplugs/filescan
 * @author secplugs
 * @version 0.1
 *
 */

class FileScan {
    public const SECPLUGS_DEFAULT_API_KEY = "ammzxNR0cm5HpIMwcC3rr72Ti8GWPXLo69EZAeyo";
    public const SECPLUGS_BASE_URL = "https://api.live.secplugs.com/security";
    public const SECPLUGS_CLEAN_MID_SCORE = 70;
    
    private $apiKey;
    private $filename;
    private $cksum;

    /**
     * The constructor for the Secplugs PHP client.
     *
     * @param string $apiKey - The confidential API key from the portal
     *
     */
    public function __construct(string $apiKey = self::SECPLUGS_DEFAULT_API_KEY) {
        $this->apiKey = $apiKey;
        $this->filename = "";
        $this->cksum = "";
    }

    /**
     * isClean is the simplest integration point into Secplugs. It takes a file
     * as parameter and returns true if it is clean, false otherwise.
     *
     * @param string $filename - The absolute path of the file to analyse.
     * @return bool - true if file is clean, false otherwise.
     *
     */
    public function isClean(string $filename) {
        $res = $this->scanFile($filename);
        $report = json_decode($res, true);
        if ($report["score"] < self::SECPLUGS_CLEAN_MID_SCORE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * scanFile is the end-to-end file scanning API. This is the lower level API for
     * isClean and should be used only for more specific tasks than check if a file
     * is clean or not.
     *
     * @param string $filename - The absolute path of the file to analyse.
     * @return string - The JSON response encoded as a string. This should be decoded for further use.
     *
     */
    public function scanFile(string $filename) {
        $pre_signed = $this->getPresignedUrl($filename);
        $this->uploadFile($pre_signed);
        $res = $this->quickScan();
        return $res;
    }

    /**
     * getPresignedUrl is an internal method that gets an unique upload url usually based on
     * the file's SHA256 sum. This is to securely upload a file to AWS S3.
     *
     * @param string $filename - The absolute path of the file to analyse.
     * @return string - The JSON response encoded as a string.
     *
     */
    public function getPresignedUrl(string $filename) {
        $this->filename = $filename;
        $sha256 = hash_file('sha256', $filename);
        $this->cksum = $sha256;
        $file_upload_url = self::SECPLUGS_BASE_URL . "/file/upload?sha256=" . $sha256;
        $curl_ctxt = curl_init();
        curl_setopt($curl_ctxt, CURLOPT_URL, $file_upload_url);
        curl_setopt($curl_ctxt, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_ctxt, CURLOPT_HTTPHEADER, array('x-api-key: ' . $this->apiKey));
        $output = curl_exec($curl_ctxt);
        curl_close($curl_ctxt);
        return $output;        
    }

    /**
     * uploadFile is used to upload the file to be analysed using the data returned by
     * getPresignedUrl method.
     *
     * @param string $pre_signed_data - The response from getPresignedUrl
     * @param string - The response from the HTTP upload.
     *
     */
    public function uploadFile($pre_signed_data) {
        $upload_info = json_decode($pre_signed_data, true);
        $fields = array();
        foreach ($upload_info["upload_post"]["fields"] as $k => $v) {
            $fields[$k] = $v;
        }
        $filename = $this->filename;
        $mime = mime_content_type($filename);
        $file_base_name = basename($filename);
        $curl_file_obj = new CURLFile($filename, $mime, $file_base_name);
        $fields['file'] = $curl_file_obj;

        $cc = curl_init();
        curl_setopt($cc, CURLOPT_URL, $upload_info["upload_post"]["url"]);
        curl_setopt($cc, CURLOPT_POST, true);
        curl_setopt($cc, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($cc, CURLOPT_FOLLOWLOCATION, true);
        $r = curl_exec($cc);
        curl_close($cc);
        return $r;
    }

    /**
     * quickScan triggers the actual scan of the file.
     *
     * @return string - JSON encoded scan result.
     *
     */
    public function quickScan() {
        $scan_context = json_encode(array('file_name' => basename($this->filename)));
        $sha256 = $this->cksum;
        $data = array('scancontext' => $scan_context, 'sha256' => $sha256);
        $qp = http_build_query($data);
        $url = self::SECPLUGS_BASE_URL . '/file/quickscan?' . $qp;
        $cc = curl_init();
        curl_setopt($cc, CURLOPT_URL, $url);
        curl_setopt($cc, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cc, CURLOPT_HTTPHEADER, array('x-api-key: ' . $this->apiKey));
        $output = curl_exec($cc);
        curl_close($cc);
        return $output;        
    }

    public function getApiKey() {
        return $this->apiKey;
    }
        

}

?>
