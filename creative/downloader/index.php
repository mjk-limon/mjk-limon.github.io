<?php

if (isset($_POST['url'])) {
    $remoteUrl = $_POST['url'];
    $headers = get_headers($remoteUrl, 1);

    // Extract the filename from the Content-Disposition header if available, otherwise, use basename of the URL
    if (isset($headers['Content-Disposition'])) {
        $filename = basename($headers['Content-Disposition']);
    } else {
        $filename = basename($remoteUrl);
    }

    // Open a connection to the remote server
    $remoteFile = fopen($remoteUrl, 'rb');

    if ($remoteFile) {
        // Send the appropriate headers to the client
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // Stream the remote content to the client
        while (!feof($remoteFile)) {
            echo fread($remoteFile, 8192); // Adjust buffer size as needed
            flush();
        }

        // Close the connection to the remote server
        fclose($remoteFile);
    } else {
        // If unable to open the remote file, return an error message
        header("HTTP/1.0 404 Not Found");
        die("Failed to open remote file.");
    }
} else {
?>

    <!DOCTYPE html>
    <html lang="">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP File downloader</title>

        <!-- Bootstrap CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.3/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
            <![endif]-->
    </head>

    <body>
        <div class="container">
            <div style="margin-top: 100px;">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="url">File download url</label>
                        <input id="url" class="form-control" type="text" name="url">
                    </div>

                    <button type="submit" class="btn btn-primary">Download</button>
                </form>
            </div>
        </div>
    </body>

    </html>
<?php } ?>