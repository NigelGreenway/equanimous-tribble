<?php session_start();
//phpinfo()

//This is a function that builds an html table out of an array.
    function build_table($array, $testindex){
    
    // start table

    $html = '<table>';

    // header row

    $html .= '<tr>';
        
    $arrayKeys = array_keys($array[0]);
    $noOfKeys = count($arrayKeys);
        
    for($loopy = 0 ; $loopy < $noOfKeys ; $loopy++){
        
            $html .= '<th align=left>' . $arrayKeys[$loopy] . '</th>';
        
    }
    $html .= '</tr>';

    // data rows

    foreach( $array as $key=>$value){
        $html .= '<tr>';
        foreach($value as $key2=>$value2){
            $html .= '<td>' . $value2 . '</td>';
        }
        $html .= '<td>'. '<a href="http://localhost:8000/products/?data=' . $key .'&store=' . $testindex .'"' .'>Change stock</a>'. '</td>';
        $html .= '</tr>';

    }

    // finish table and return it

    $html .= '</table>';

    return $html;

}
?>

<html>
    <head>
    <link type="text/css" rel="stylesheet" href="/stylesheets/example.css" />
  </head>
<body>
    
<?php
include 'vendor/vendapi/vendapi/src/VendAPI/VendAPI.php';

$noOfStores = count($_SESSION['token']);
//echo $noOfStores;

for ($a = 0 ; $a < $noOfStores ; $a++) {

        $token = $_SESSION['token'][$a];
        
        $tokentype = $_SESSION['tokenType'][$a];
        $url = 'https://'.$_SESSION['storename'][$a].'.vendhq.com';
        echo $url;
        
        $vend = new VendAPI\VendAPI($url,$tokentype,$token);
        //$vend->debug(true);
        
        $products = $vend->getProducts();
        
        $length = count($products);
        $prod_array = array();
        
        for($i = 0; $i < $length; $i++){
            $prod_array[$i]["ID"] = $products[$i]->__get('id');
            $prod_array[$i]["Name"] = $products[$i]->__get('name');
            $prod_array[$i]["sku"] = $products[$i]->__get('sku');
            $prod_array[$i]["Price"] = $products[$i]->__get('price');
            $prod_array[$i]["Stock"] = $products[$i]->getInventory(null);
        }
              //Show the info
              //echo '<pre>',print_r($prod_array,1),'</pre>';
        
              $_SESSION['list'] = $products;
              $_SESSION['array'][$a] = $prod_array;
              
              //Show the info in a table
              echo build_table($prod_array, $a) . "<br/>";
      
}
?>

<a href="http://localhost:8000/new/">Add a new product to all stores</a>

</body>
</html>
