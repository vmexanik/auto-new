<?php

function ListCatalogs($api_key,$type){
    $out="";
    if( $curl = curl_init() ) {
        $url = API_URL."/oem/v1/CatalogsListGet?api_key=$api_key&type=$type";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT,"Levam Demo");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $out = curl_exec($curl);
        curl_close($curl);
    };
    return $out;
}

function FindModels($api_key,$brandCode,$type){
    $out="";
    if( $curl = curl_init() ) {
        $urlTmp = API_URL."/oem/v1/ModelsListGet?api_key=$api_key&catalog_code=$brandCode&type=$type";
        curl_setopt($curl, CURLOPT_URL, $urlTmp);
        curl_setopt($curl, CURLOPT_USERAGENT,"Levam Demo");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $out = curl_exec($curl);
        curl_close($curl);
    };
    return $out;
}

function FindParams($api_key,$brandCode,$model,$ssd,$param,$lang,$family = ''){
    $model = urlencode($model);
    $param = urlencode($param);
    $family = urlencode($family);
    $out="";
    if( $curl = curl_init() ) {
        $url = API_URL."/oem/v1/VehicleParamsSet?api_key=$api_key&model=$model&catalog_code=$brandCode&ssd=$ssd&param=$param&lang=$lang&family=$family";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT,"Levam Demo");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $out = curl_exec($curl);
        curl_close($curl);
    };
    return $out;
}

function FindModifications($api_key,$ssd,$lang){
    $out="";
    if( $curl = curl_init() ) {
        $url = API_URL."/oem/v1/VehicleModificationsGet?api_key=$api_key&ssd=$ssd&lang=$lang";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT,"Levam Demo");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $out = curl_exec($curl);
        curl_close($curl);
    };
    return $out;
}

function FindGroups($api_key,$lang,$ssd,$link,$group){
    $out="";
    if( $curl = curl_init() ) {
        $url = API_URL."/oem/v1/PartGroupsGet?api_key=$api_key&ssd=$ssd&link=$link&lang=$lang&group=$group";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT,"Levam Demo");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $out = curl_exec($curl);
        curl_close($curl);
    };
    return $out;
}

function FindParts($api_key,$lang,$ssd,$link,$group){
    $out="";
    if( $curl = curl_init() ) {
        $url = API_URL."/oem/v1/PartsGet?api_key=$api_key&ssd=$ssd&link=$link&group=$group&lang=$lang";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT,"Levam Demo");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $out = curl_exec($curl);
        curl_close($curl);
    };
    return $out;
}

function FindVin($api_key,$lang,$vin){
    $out="";
    if ($curl = curl_init()) {
        $url = API_URL."/oem/v1/VinFind?api_key=$api_key&vin=$vin&lang=$lang";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT,"Levam Demo");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($curl);
        curl_close($curl);
    };
    return $out;
}

function FindFrame($api_key,$lang,$frame){
    $out="";
    if ($curl = curl_init()) {
        $url = API_URL."/oem/v1/FrameFind?api_key=$api_key&frame=$frame&lang=$lang";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT,"Levam Demo");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($curl);
        curl_close($curl);
    };
    return $out;
}

function FindCode($api_key,$lang,$code,$brand){
    $out="";
    if ($curl = curl_init()) {
        $url = API_URL."/oem/v1/PartInfoGet?api_key=$api_key&brand=$brand&number=$code&lang=$lang";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT,"Levam Demo");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($curl);
        curl_close($curl);
    };
    return $out;
}