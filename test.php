<?php 
//https://wshub.tracktec.cl/ws/wspos.wsdl
// CURLOPT_URL => 'http://wspos.samtech.cl/WSP.asmx',
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://wshub.tracktec.cl/ws/wspos.wsdl',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'<?xml version="1.0" encoding="utf-8"?><soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope"><soap12:Body><Post_XML xmlns="samtechpos"><xmldoc>
<![CDATA[
<datos>
  <movil>
  <empresa>Servicio controlsellos</empresa>
  <pat>Patente</pat>
  <fn>Fecha y Hora en hora Local o UTC</fn>
  <lat>Latitud</lat>
  <lon>Longitud</lon>
  <ori>Orientacion</ori>
  <vel>Velocidad</vel>
  <mot>Estado de Motor</mot>
  <hdop>Hdop o valor 0</hdop>
  <odo>Odometro</odo>
  <eve>Codigo de Evento</eve>
  <conductor>Nombre de Conductor o valor No Asignado</conductor>
  <numSAT>Cantidad de Satelites</numSAT>
  <sens1>Horometro</sens1>
  <sens2>Sensor adicional o valor 0</sens2>
  </movil>
  <usuario xmlns="user">
    <login>witla</login>
    <clave>wit@gps-45ba</clave>
  </usuario>
</datos>
]]>
</xmldoc>
</Post_XML>
</soap12:Body>
</soap12:Envelope>
',
  CURLOPT_HTTPHEADER => array(
    'content-type: application/soap+xml; charset=utf-8'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo " $response";
echo "<br>";
 