<?php session_start();
    
    ?>

<html>
  <head>
    <link type="text/css" rel="stylesheet" href="/stylesheets/main.css" />
  </head>
    
  <body>
    <p>Welcome to KRCS's test application! This app retrieves the list of products on a Vend account and allows you to create or update a product.</p>

<?php>

include 'vendor/vendapi/vendapi/src/VendAPI/VendAPI.php';
date_default_timezone_set("UTC"); 

$storeURL = array('krcstest1','krcstest2');
$maxIterations = Count($storeURL)-1;

if ($_GET['increase']=="y")
{
    $_SESSION['index'] = $_SESSION['index'] + 1;
}
elseif (!($_GET['code']))
{
    $_SESSION['index'] = 0; 
}


$i = $_SESSION['index'];

if (!($i > $maxIterations))
{

    if ($_GET['code']) {
        
    
        // If the variable 'code' is present in the URI then save the relevant values
        $data = [
                'code' => $_GET['code'],
                'client_id' => 'ey4JpTpc9V121K0KSRYHdmFnjuSvj7NU',
                'client_secret' => 'zhzGaiH2014yWVf8XbAqSjdPFUYt18jF',
                'grant_type' => 'authorization_code',
                'redirect_uri' => 'https://gentle-ridge-9144.herokuapp.com'
        ];
    
        // Prepare a request to POST to vend
        $data = http_build_query($data);
        $context = [
            'http' => [
                'method' => 'POST',
                'content' => $data
            ]
        ];
        $context = stream_context_create($context);
        
        // Post the request and save the value to $result
        $result = file_get_contents('https://'.$storeURL[$i].'.vendhq.com/api/1.0/token', false, $context);
    
        // Convert the JSON string $result to an object so we can call properties from it
        $result=json_decode($result);
          
        // Call the keys we need from the new object
        $vendToken = $result->access_token;
        $vendTokenType = $result->token_type;
        $vendExpires = $result->expires;
        $vendRefresh = $result->refresh_token;
        
        $_SESSION['storename'][$i] = $storeURL[$i];
        $_SESSION['token'][$i] = $vendToken;
        echo $vendToken.'bah<br/>';
        echo $_SESSION['token'][$i].'<br/>';
        $_SESSION['tokenType'][$i] = $vendTokenType;
        echo $_SESSION['tokenType'][$i].'<br/>';
        $_SESSION['tokenExpires'][$i] = $vendExpires;
        echo $_SESSION['tokenExpires'][$i].'<br/>';
        $_SESSION['tokenRefresh'][$i] = $vendRefresh;
        echo $_SESSION['tokenRefresh'][$i].'<br/>';
    
        echo('<a href ="https://gentle-ridge-9144.herokuapp.com/?increase=y">Continue validating after '.$i.'</a>');  
    }
    
    else
    
        //if (token at $i doesn't exist or has expired)
        if (!($_SESSION['token'][$i]) or (($_SESSION['tokenExpires'][$i])<(time())))
        
        {
            echo('<a href ="https://'. $storeURL[$i] .'.vendhq.com/connect?response_type=code&client_id=ey4JpTpc9V121K0KSRYHdmFnjuSvj7NU&redirect_uri=https://gentle-ridge-9144.herokuapp.com">Request Access for '.$i.'</a>');
        
        }
        
        else
        
        {
            echo('<a href ="https://gentle-ridge-9144.herokuapp.com/?increase=y">Token '.$i.' is valid. Continue checking</a>');  

        }
        
}
?>
    <p></p>
    <a href="https://gentle-ridge-9144.herokuapp.com/example">Click to get the product list!</a>
  </body>
</html>