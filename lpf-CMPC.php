<?php

set_time_limit(1200);
date_default_timezone_set('America/Santiago');

$user = "CMPC";


$pasw = "123";

include "login/conexion.php";

$consulta = "SELECT hash FROM masgps.hash where user='$user' and pasw='$pasw'";

$resutaldo = mysqli_query($mysqli, $consulta);

$data = mysqli_fetch_array($resutaldo);

$hash = $data['hash'];

$cap = $hash;


//header("refresh:2");
$listado = '';
$i = 0;
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://www.trackermasgps.com/api-v2/tracker/list',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => '{"hash":"' . $cap . '"}',
  CURLOPT_HTTPHEADER => array(
    'Accept: application/json, text/plain, */*',
    'Accept-Language: es-419,es;q=0.9,en;q=0.8',
    'Connection: keep-alive',
    'Content-Type: application/json',
    'Cookie: _ga=GA1.2.728367267.1665672802; locale=es; _gid=GA1.2.967319985.1673009696; _gat=1; session_key=5d7875e2bf96b5966225688ddea8f098',
    'Origin: http://www.trackermasgps.com',
    'Referer: http://www.trackermasgps.com/',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'
  ),
));

$response2 = curl_exec($curl);

$json = json_decode($response2);

$array = $json->list;


//echo '[';
foreach ($array as $item) {


  $id = $item->id;
  $imei = $item->source->device_id;
  //echo " , &nbsp";


  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://www.trackermasgps.com/api-v2/tracker/get_state',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{"hash": "' . $cap . '", "tracker_id": ' . $id . '}',
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));


  $response2 = curl_exec($curl);

  curl_close($curl);

  $json2 = json_decode($response2);
  //$.state.gps.location.lat


  $lat = $array2 = $json2->state->gps->location->lat;


  $lng = $array2 = $json2->state->gps->location->lng;


  $last_u = $array2 = $json2->state->last_update;

  $fechaOriginal = $last_u;
  $fecha = new DateTime($fechaOriginal);

 $fecha_UC=$fecha->format('Y-m-d\TH:i:s');
 

  $plate = substr($item->label, 0, 7);
 // $plate=str_replace('-', '', $plate);

  $speed = $json2->state->gps->speed;
  $alt= $json2->state->gps->alt;

  $direccion = $json2->state->gps->heading;

  $connection_status = $json2->state->connection_status;

  $movement_status = $json2->state->movement_status;

  $signal_level = $json2->state->gps->signal_level;

  $ignicion = $json2->state->inputs[0]; 

  if ($ignicion) {
    $motor = 1;
  } else {
    $motor = 0;
  }

  $hoy=date('Y-m-d\TH:i:s');


$codigo =generarCodigoEvento($ignicion, $movement_status);


  $json = array(


    'id' => $id,
    'imei' => $imei,
    'patente' => $plate,
    'lat' => $lat,
    'lng' => $lng,
    'speed' => $speed,
    'altura'=>$alt,
    'direccion' => $direccion,
    'connection_status' => $connection_status,
    'signal_level' => $signal_level,
    'movement_status' => $movement_status,
    'ignicion' => $ignicion,
    'motor' => $motor,
    'ultima-conexion' =>$fecha_UC,
    'fecha'=>$hoy,
    'codigo'=>$codigo

  );

  $total[$i] = $json;
 
 //echo json_encode($json);
  $i++;
    include __DIR__."/xmlCMP.php";



}

function generarCodigoEvento($ignicion, $estadoMovimiento) {
  // Convertimos los valores a booleanos para facilitar la comparación
  $ignicion = ($ignicion == 'TRUE') ? true : false;
  $estadoMovimiento = ($estadoMovimiento == 'moving') ? true : false;

  // Lógica para determinar el código de evento
  if ($ignicion && $estadoMovimiento) {
      return 101; // Vehículo en movimiento con motor encendido
  } elseif ($ignicion && !$estadoMovimiento) {
      return 102; // Vehículo estacionado con motor encendido
  } elseif ($ignicion && !$estadoMovimiento) {
      return 103; // Vehículo detenido con motor encendido
  } else {
      return 104; // Vehículo con motor apagado
  }
}

//echo json_encode($total, http_response_code(200));

Fin:
