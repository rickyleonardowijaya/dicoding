<?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
$connectionString = "DefaultEndpointsProtocol=https;AccountName=gamerlistenerapp;AccountKey=9HBvgsWyPqN+afxqSuELsdHJQPcI/kDfpd/amikDzmFReuw8CtDnm4klnp87ys5u9pQpcyHvl6+BZyF5yVY0BQ==;EndpointSuffix=core.windows.net";

$blobClient = BlobRestProxy::createBlobService($connectionString);
$fileToUpload = "tes.png";
if (isset($_GET["Cleanup"])) {
    $createContainerOptions = new CreateContainerOptions();
    
    $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
    $createContainerOptions->addMetaData("key1", "value1");
    $createContainerOptions->addMetaData("key2", "value2");
    $containerName = "submission";
    try {
	   $fileToUpload = $_FILES['image']['name'];
	   $targetdir = "img/";
           $targetFile = basename($_FILES["image"]["name"]);
           if(file_put_contents($targetFile, file_get_contents($_FILES['image']["tmp_name"]))){
		//echo "Sukses";
           }
	    
	    $myfile = fopen($fileToUpload, "w") or die("Unable to open file!");
            fclose($myfile);
        
        # Upload file as a block blob
//             echo "Uploading BlockBlob: ".PHP_EOL;
//             echo $fileToUpload;
//             echo "<br />";
        
            $content = file_get_contents($_FILES['image']["tmp_name"]);
            $blobClient->createBlockBlob($containerName, $fileToUpload, $content);
	    //echo $fileToUpload;
        $listBlobsOptions = new ListBlobsOptions();
        $listBlobsOptions->setPrefix($fileToUpload);
        //echo "These are the blobs present in the container: ";
        do{
            $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
            foreach ($result->getBlobs() as $blob)
            {
                echo $blob->getName().": ".$blob->getUrl()."<br />";
		echo "<input type='text' name='imageA' id='imageA'
                        value='".$blob->getUrl()."' />";
		echo "<br/>";
		echo "<img name='gambaranalisis' src='".$blob->getUrl()."' width='100' height='100'/>";
            }
        
            $listBlobsOptions->setContinuationToken($result->getContinuationToken());
        } while($result->getContinuationToken());
        echo "<br />";
    }
    catch(ServiceException $e){
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
    catch(InvalidArgumentTypeException $e){
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Analyze Sample</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>
 
<script type="text/javascript">
    function processImage() {
       
        var subscriptionKey = "a8b47712688c4154818536360975762b";
 
       
        var uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
 
        // Request parameters.
        var params = {
            "visualFeatures": "Categories,Description,Color",
            "details": "",
            "language": "en",
        };
 
        // Display the image.
        var sourceImageUrl = document.getElementById("imageA").value;
        document.querySelector("#sourceImage").src = sourceImageUrl;
 
        // Make the REST API call.
        $.ajax({
            url: uriBase + "?" + $.param(params),
 
            // Request headers.
            beforeSend: function(xhrObj){
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader(
                    "Ocp-Apim-Subscription-Key", subscriptionKey);
            },
 
            type: "POST",
 
            // Request body.
            data: '{"url": ' + '"' + sourceImageUrl + '"}',
	  
        })
 
        .done(function(data) {
            // Show formatted JSON on webpage.
            $("#responseTextArea").val(JSON.stringify(data, null, 2)[2].description);
// 	    $("#responseTextArea").val(data[2].description[1].text);
        })
 
        .fail(function(jqXHR, textStatus, errorThrown) {
            // Display error message.
            var errorString = (errorThrown === "") ? "Error. " :
                errorThrown + " (" + jqXHR.status + "): ";
            errorString += (jqXHR.responseText === "") ? "" :
                jQuery.parseJSON(jqXHR.responseText).message;
            alert(errorString);
        });
    };
</script>
 
<h1>Analyze image:</h1>
Masukkan foto yang ingin dianalisis <strong>Analyze image</strong> button.
<br><br>
Image to analyze:
 <form method="post" action="index.php?Cleanup&containerName=<?php echo $containerName; ?>" enctype="multipart/form-data">
    <input type="file" name="image" />
    <button type="submit">Press to clean up all resources created by this sample</button>
</form>
<button onclick="processImage()">Analyze image</button>
<br><br>
<div id="wrapper" style="width:1020px; display:table;">
    <div id="jsonOutput" style="width:600px; display:table-cell;">
        Response:
        <br><br>
        <textarea id="responseTextArea" class="UIInput"
                  style="width:580px; height:400px;"></textarea>
    </div>
    <div id="imageDiv" style="width:420px; display:table-cell;">
        Source image:
        <br><br>
        <img id="sourceImage" width="400" />
    </div>
</div>
</body>
</html>


