<?php

$curl = curl_init();


curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://hub.unisolutions.com.ar/hub/unigis/MAPI/SOAP/gps/service.asmx',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ns1="http://unisolutions.com.ar/">
    <soap:Body>
        <ns1:LoginYInsertarEvento>
            <ns1:SystemUser>WIT</ns1:SystemUser>
            <ns1:Password>BLK208neg</ns1:Password>
            <ns1:Dominio>'.$plate.'</ns1:Dominio>
            <ns1:NroSerie>-1</ns1:NroSerie>
            <ns1:Codigo>'.$codigo.'</ns1:Codigo>
            <ns1:Latitud>'.$lat.'</ns1:Latitud>
            <ns1:Longitud>'.$lng.'</ns1:Longitud>
            <ns1:Altitud>'.$alt.'</ns1:Altitud>
            <ns1:Velocidad>'.$speed.'</ns1:Velocidad>
            <ns1:FechaHoraEvento>'.$fecha_UC.'</ns1:FechaHoraEvento>
            <ns1:FechaHoraRecepcion>'.$hoy.'</ns1:FechaHoraRecepcion>
         </ns1:LoginYInsertarEvento>
    </soap:Body>
</soap:Envelope>',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: text/xml'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

echo "$response  $plate <br>";

