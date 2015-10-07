<?php session_start();

include 'vendor/vendapi/vendapi/src/VendAPI/VendAPI.php';

$index  = $_GET['data'];
$storeid = $_GET['store'];
echo $index . "   and   " . $storeid;

if (!(array_key_exists('content', $_POST))) {
echo "You clicked on: " . $_SESSION['array'][$storeid][$index]["Name"] . ". Current stock level is: " . $_SESSION['array'][$storeid][$index]["Stock"];
}
if (array_key_exists('content', $_POST)) {
$token = $_SESSION['token'][$storeid];
$tokentype = $_SESSION['tokenType'][$storeid];
$url = 'https://'.$_SESSION['storename'][$storeid].'.vendhq.com';
echo $token . $tokentype . $url;

$vend = new VendAPI\VendAPI($url,$tokentype,$token);
$vend->debug(true);

    $donut = new \VendAPI\VendProduct(null, $vend);
    $donut->id = $_SESSION['array'][$storeid][$index]["ID"];
    $donut->setInventory($_POST['content'], null);
    $donut->save();
    echo 'New stock is '.$donut->getInventory();
    }
?>

<form action="?data=<?php echo $index; ?>&store=<?php echo $storeid; ?>" method="post">
<div><textarea name="content" rows="1" cols="5"></textarea></div>
<input type="submit" value="Update stock"/>
</form>