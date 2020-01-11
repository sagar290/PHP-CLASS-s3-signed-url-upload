#[PHP-CLASS-s3-signed-url-upload](https://github.com/sagar290/PHP-CLASS-s3-signed-url-upload)

Signed URL in s3 is great system for secure upload and download file. But when it comes uploading file in some point its very hard. 
By this class you can easily.  

## Example

``` php 
<?php

require "PHP-CLASS-s3-signed-url-upload.php";

$signedUrl = new SignedUrl;

if (isset($_FILES["file"])) {

    $file = $_FILES["file"]["tmp_name"];

    // put your url from where you can get the signed url
    $url = "https://thisisurl.com/getSignedkey";
    $data = [
        'fileName' => "test.png", // set the file name with extension
        'contentType' => "audio/mpeg", // content type must be audio/mpeg for audio, image/png for image, video/mp4 for video
        'path' => "audio/", // give the path where you want to upload
    ];
    // if any token based auth required for getting signed url (optional)
    $headers = [
        'publickey: my-key', // this parameter is basically optional dont be confused
    ];
    $signedUrl - > UploadToS3($url, $data, $headers, $file);



    echo "success";

}
?>
```
In HTML
``` html
<!-- example form for uploading file -->
<form  action="example.php"  method="POST"  enctype="multipart/form-data">
	<input  type="file"  name="file"  accept="image/x-png,image/gif,image/jpeg">
	<input  type="submit"  value="submit">
</form>
```
