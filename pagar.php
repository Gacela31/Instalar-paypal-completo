<?php
//evitar entrar a la url de pagar.php vacia
if(!isset($_POST['producto'], $_POST['precio'])){
    exit('Hubo un error');
}

//namespace: se impotan las clases que estoy utilizando y no todas,
//si no uso el name space importa clases repetidas con el mismo nombre 
//y la pagina me da error

use PayPal\Api\Payer;  
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;


require'config.php';

//crear las variables y le agrego un poco de sanitizacion
$producto = htmlspecialchars($_POST['producto']);
$precio = htmlspecialchars($_POST['precio']);
$precio = (int) $precio; //me aseguro que sea entero, si quiero aceptar decimales le pongo float
$envio = 3;
$total = $precio + $envio;

$compra = new Payer();
$compra->setPaymentMethod('paypal'); //set agrega valor
//$compra->getPaymentMethod(); //get obtiene el valor agregado por el set    

$articulo = new Item();
$articulo->setName($producto);
$articulo->setCurrency('USD');
$articulo->setQuantity(1);
$articulo->setPrice($precio);

$listaArticulos = new ItemList();
$listaArticulos->setItems(array($articulo));//aqui deberia poner todos mis articulos disponibles

$detalles = new Details();
$detalles->setShipping($envio)
         ->setSubtotal($precio);

$cantidad = new Amount();
$cantidad->setCurrency('USD')
         ->setTotal($total)
         ->setDetails($detalles);

$transaccion = new Transaction();
$transaccion->setAmount($cantidad)
            ->setItemList($listaArticulos)
            ->setDescription('Pago')
            ->setInvoiceNumber(uniqid());


$redireccionar = new RedirectUrls();
$redireccionar->setReturnUrl(URL_SITIO . "/pago_finalizado.php?exito=true")
              ->setCancelUrl(URL_SITIO . "/pago_finalizado.php?exito=false");


$pago = new Payment();
$pago->setIntent("sale")
     ->setPayer($compra)
     ->setRedirectUrls($redireccionar)
     ->setTransactions(array($transaccion));

try {
    $pago->create($apiContext);

}catch (PayPal\Exception\PayPalConnectionException $pce) {
echo "<pre>";
print_r(json_decode($pce->getData()));
exit;
echo "</pre>";
}

$aprobado = $pago->getApprovalLink();

header("Location: {$aprobado}");







