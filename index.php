<?php
/**----------------------------------------------------------------------------------
* Microsoft Developer & Platform Evangelism
*
* Copyright (c) Microsoft Corporation. All rights reserved.
*
* THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY KIND, 
* EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE IMPLIED WARRANTIES 
* OF MERCHANTABILITY AND/OR FITNESS FOR A PARTICULAR PURPOSE.
*----------------------------------------------------------------------------------
* The example companies, organizations, products, domain names,
* e-mail addresses, logos, people, places, and events depicted
* herein are fictitious.  No association with any real company,
* organization, product, domain name, email address, logo, person,
* places, or events is intended or should be inferred.
*----------------------------------------------------------------------------------
**/

/** -------------------------------------------------------------
# Azure Storage Blob Sample - Demonstrate how to use the Blob Storage service. 
# Blob storage stores unstructured data such as text, binary data, documents or media files. 
# Blobs can be accessed from anywhere in the world via HTTP or HTTPS. 
#
# Documentation References: 
#  - Associated Article - https://docs.microsoft.com/en-us/azure/storage/blobs/storage-quickstart-blobs-php 
#  - What is a Storage Account - http://azure.microsoft.com/en-us/documentation/articles/storage-whatis-account/ 
#  - Getting Started with Blobs - https://azure.microsoft.com/en-us/documentation/articles/storage-php-how-to-use-blobs/
#  - Blob Service Concepts - http://msdn.microsoft.com/en-us/library/dd179376.aspx 
#  - Blob Service REST API - http://msdn.microsoft.com/en-us/library/dd135733.aspx 
#  - Blob Service PHP API - https://github.com/Azure/azure-storage-php
#  - Storage Emulator - http://azure.microsoft.com/en-us/documentation/articles/storage-use-emulator/ 
#
**/

require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;



if (isset($_GET["upload"])) {
    $connectionString = "DefaultEndpointsProtocol=https;AccountName=gamerlistenerapp;AccountKey=9HBvgsWyPqN+afxqSuELsdHJQPcI/kDfpd/amikDzmFReuw8CtDnm4klnp87ys5u9pQpcyHvl6+BZyF5yVY0BQ==;EndpointSuffix=core.windows.net";

    // Create blob client.
    $blobClient = BlobRestProxy::createBlobService($connectionString);

    $fileToUpload = "HelloWorld.txt";
    $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

    // Set container metadata.
    $createContainerOptions->addMetaData("key1", "value1");
    $createContainerOptions->addMetaData("key2", "value2");

      $containerName = "Submission";

     $fileName = $_FILES['file']['name'];
        $imageFileType = pathinfo($fileName,PATHINFO_EXTENSION);
        $targetDir = "upload/";
        $targetFile = $targetDir.basename($_FILES['image']['name']);
        $file = $_FILES['image']['name'];
        move_uploaded_file($file,$targetFile);
        $fileToUpload = "upload/".$files.".".$imageFileType;
        $content = fopen($fileToUpload,"r") or die("Error");
        $blobClient->createBlockBlob($container, $file, $content);
        $listBlobsOptions = new ListBlobsOptions();
        $listBlobsOptions->setPrefix($fileName);
} 
?>

<p>Pilih gambar yang akan di Analisa</p>
    <form action="" method="POST">
        <input type="file" name="image" id="image"><br><br>
        <input type="submit" value="Upload" name="upload" class="btn btn-primary">
    </form>

<form method="post" action="index.php?Cleanup&containerName=<?php echo $containerName; ?>">
    <button type="submit">Press to clean up all resources created by this sample</button>
</form>
