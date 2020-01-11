<?php

/**
 *  Author: Sagar Dash
 *  Website: sagardash.com
 *  License: MIT
 */

class SignedUrl
{

    /**
     * type name of the file
     * @return String
     */
    public $fileType;

    
    /**
     * give the url from where you get the signed url
     * @return String
     */
    public $url;


    /**
     *  Get Signed URL 
     *  @param String $from
     *  @param Array $fields
     *  @param Array $headers
     * 
     *  @return mixed
     */
    public function getUploadSignedUrl($from, $fields, $headers = [])
    {
        $this->url = $from;

        $data = [
            'fileName' => "test",
            'contentType' => "audio/mpeg",
            'path' => "audio/",
        ];

        $this->fileType = $data["contentType"];

        $data =  array_merge($data, $fields);

        $data_string = json_encode($data);

        $headers = array_merge([
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ], $headers);

        // post request to get the signed url

        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        return $result = json_decode(curl_exec($ch), true);

    }


    /**
     *  Upload File from signed URL
     *  @param String $url
     *  @param String $type
     *  @param Binary $file
     * 
     *  @return mixed
     */
    public function uploadFileBySigned($url, $file)
    {


        // put request for upload
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $file);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: '. $this->fileType
            )
        );

        return $result = curl_exec($ch);
    }

    /**
     *  Upload file 
     *  @param String $getSignedUrlfrom
     *  @param Array $fields
     *  @param Array $headers
     *  @param Buffer $file
     * 
     *  @return mixed
     */
    public function UploadToS3($getSignedUrlfrom, $fields = [], $headers = [], $file) {
        

        $signedURL = $this->getUploadSignedUrl($getSignedUrlfrom, $fields, $headers);

        if ($signedURL) {
            return $this->uploadFileBySigned($signedURL["data"][0]["signedLink"], file_get_contents($file));
        } else {
            throw new \Exception('Invalid access key');
        }
        
    }


}
