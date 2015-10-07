<?php session_start();

//include 'vendor/vendapi/vendapi/src/VendAPI/VendAPI.php';
require __DIR__ . '/../vendor/autoload.php';

//$index = $_GET['data'];

$noOfStores = count($_SESSION['token']);


for ($a = 0 ; $a < $noOfStores ; $a++) {
    $token = $_SESSION['token'][$a];
    $tokentype = $_SESSION['tokenType'][$a];
    $url = 'https://'.$_SESSION['storename'][$a].'.vendhq.com';
    
    $vend = new VendAPI\VendAPI($url,$tokentype,$token);
    $vend->debug(true);

    if (array_key_exists('productname', $_POST)) {

        $donut = new \VendAPI\VendProduct(null, $vend);
        $donut->handle = $_POST['handle'];
        $donut->sku = $_POST['sku'];
        $donut->name = $_POST['productname'];
        $donut->retail_price = $_POST['price'];
        $donut->save();
        echo $donut->name . ' has been added to the store: '. $_SESSION['storename'][$a] . '. Its ID is ' . $donut->id;
        }
}
?>

<form action="?data=<?php echo $index; ?>" method="post">
<div>Please enter the information for the new product below</div>
<div><textarea name="productname" rows="1" cols="10" placeholder="Name"></textarea></div>
<div><textarea name="handle" rows="1" cols="10" placeholder="Handle"></textarea></div>
<div><textarea name="sku" rows="1" cols="10" placeholder="SKU"></textarea></div>
<div><textarea name="price" rows="1" cols="10" placeholder="Price incl. VAT"></textarea></div>
<input type="submit" value="New product"/>
</form>