<?php

$oObject=new RelatedProducts();
$sPreffix='related_products_';

switch (Base::$aRequest['action'])
{
    case $sPreffix."import":
        $oObject->ImportProduct();
        break;

    case $sPreffix."add":
    case $sPreffix."edit":
        $oObject->AddProduct();
        break;

    case $sPreffix."del":
        $oObject->DeleteProduct();
        break;

    default:
        $oObject->Index();
        break;

}
?>