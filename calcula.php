<?php
$content = '';
if(isset($_POST['value_json'] )&& $_POST['value_json']  != ''){
    $json_string = $_POST['value_json'];
    $json_validated = json_validate($json_string);
    if($json_validated){
        $json_array = json_decode($json_string, true);
        $error = validate($json_array);
        //se validan llaves y valores del json
        if( $error == "" ){
            //Se obtienen los goles de cada jugador por equipo
            $goles_obtenidos = obtenerGoles($json_array);
            $goles_alcance = $goles_obtenidos['goles_alcance'];
            $goles_meta = $goles_obtenidos['goles_meta'];
            //se suman los goles de todos los jugadores por equipo
            $goles_equipo = golesByTeam($goles_alcance, $goles_meta);
            $equipo_alcance = $goles_equipo['equipo_alcance'];
            $equipo_meta = $goles_equipo['equipo_meta'];
            $equipo_jugadores = $goles_equipo['equipo_jugadores'];
            //Se reune la informacion. Para ontener el sueldo total
            $json_array = getInfo($json_array, $equipo_alcance, $equipo_meta, $equipo_jugadores);
            //Se genera la vista del resultado final
            $content = result($json_array);
        }
    }else{
        $error = "JSON no valido";
    }
}else{
    $error = "JSON no valido";
}
/**
 * getMetaIndividual funcion que recibe el Nivel de cada uno de los jugadores y 
 * devuelve la cantidad de goles que debe obtener un jugador deacuerdo a su nivel
 **/
function getMetaIndividual($nivel){
    $nivel = strtoupper($nivel);
    switch($nivel){
        case 'A':
            $meta = 5;
        break;
        case 'B':
            $meta = 10;
        break;
        case 'C':
            $meta = 15;
        break;
        case 'CUAUH':
            $meta = 20;
        break;
        default:
            $meta = 20;
        break;        
    }
    return $meta;
}
/**
 * obtenerGoles funcion que recibe el array con la información de los jugadores
 * la cual devuelve un array con el cual agrupa a los jugadores por equipo con sus
 * goles obtenidos y su objetivo de goles
 **/
function obtenerGoles($json_array){
    $goles_alcance = array();
    $goles_meta = array();
    foreach($json_array as $key => $value){
        $goles_alcance[$value['equipo']][] = $value['goles'];
        if( array_key_exists('nivel', $value) ){
            $goles_meta[$value['equipo']][] = getMetaIndividual($value['nivel']);
        }else{
            $goles_meta[$value['equipo']][] = $value['goles_minimos'];
        }
    }
    return $result = array('goles_alcance' => $goles_alcance, 'goles_meta' => $goles_meta);
}
/** golesByTeam función que recibe los goles obtenidos y los objetivos en goles
 * por equipo. Y devuelve el total de goles obtenidos por equipo, la meta en goles 
 * a obtener por equipo y a cantidad de jugadores totales por equipo
 **/
function golesByTeam($goles_alcance, $goles_meta){
    $equipo_alcance = array();
    $equipo_meta = array();
    $equipo_jugadores = array();
    foreach($goles_alcance as $equipo => $gol){
        $a = 0;
        $m = 0;
        //se obtienen los jugadores totales por equipo
        $jugadores = count($goles_alcance[$equipo]);
        $a = array_sum($goles_alcance[$equipo]);
        $m = array_sum($goles_meta[$equipo]);
        $equipo_alcance[$equipo] = $a;
        $equipo_meta[$equipo] = $m;
        $equipo_jugadores[$equipo] =  $jugadores;
    }
    return $result = array('equipo_alcance' => $equipo_alcance, 'equipo_meta' => $equipo_meta, 'equipo_jugadores' => $equipo_jugadores);
}
/** formatCoin función que recibe una cantidad dada y la devuelve en formato de moneda americano **/
function formatCoin($amount){
	$amount = number_format($amount, 2, '.', ',');
	return $amount;
}
/** json_validate función que recibe un JSON y devuelve un mensaje de error en caso
 *  de que exista un error con el JSON  **/
function json_validate($string){
    // decode the JSON data
    $result = json_decode($string);

    // switch muestra los posibles errores
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            $error = true; // JSON válido // sin errores
            break;
        case JSON_ERROR_DEPTH:
            $error = 'The maximum stack depth has been exceeded.';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            $error = 'Invalid or malformed JSON.';
            break;
        case JSON_ERROR_CTRL_CHAR:
            $error = 'Control character error, possibly incorrectly encoded.';
            break;
        case JSON_ERROR_SYNTAX:
            $error = 'Syntax error, malformed JSON.';
            break;
        // PHP >= 5.3.3
        case JSON_ERROR_UTF8:
            $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
            break;
        // PHP >= 5.5.0
        case JSON_ERROR_RECURSION:
            $error = 'One or more recursive references in the value to be encoded.';
            break;
        // PHP >= 5.5.0
        case JSON_ERROR_INF_OR_NAN:
            $error = 'One or more NAN or INF values in the value to be encoded.';
            break;
        case JSON_ERROR_UNSUPPORTED_TYPE:
            $error = 'A value of a type that cannot be encoded was given.';
            break;
        default:
            $error = 'Unknown JSON error occured.';
            break;
    }
    return $error;
}
/** validate función que valida cada uno de los campos y valores (key, value) del JSON recibido
 * por el cliente, previamente convertido en array, en cado de existir error lo devuelve de lo contrario 
 * devuelve un valor vacío*/
function validate($json_array){
    $error = "";
    foreach($json_array as $key => $value){
        // validando la llave nivel
        if( array_key_exists('nivel', $value) ){
            if( !ctype_alpha($value['nivel']) ){
                $error =  "El campo nivel no es válido";
                break;
            }
        }elseif( array_key_exists('goles_minimos', $value) ){
            if(!is_numeric($value['goles_minimos']) || $value['goles_minimos'] <= 0){
                $error = "El campo goles mínimos no es válido";
                break;
            }    
        }else{
            $error =  "Se requiere el campo nivel o el campo goles mínimos";
            break;
        }
        
        //validando la llave nombre
        if( array_key_exists('nombre', $value) ){
            if(!is_string($value['nombre'])){
                $error =  "El campo nombre no es válido";
                break;
            }
        }else{
            $error =  "Se requiere el campo nombre";
            break;
        }
        //validando la llave goles
        if( array_key_exists('goles', $value) ){
            if(!is_numeric($value['goles']) || $value['goles'] <= 0){
                $error =  "El campo goles no es válido";
                break;
            }
        }else{
            $error =  "Se requiere el campo goles";
            break;
        }
        //validando la llave sueldo
        if( array_key_exists('sueldo', $value) ){
            if(!is_numeric($value['sueldo']) || $value['sueldo'] <= 0){
                $error =  "El campo sueldo no es válido";
                break;
            }
        }else{
            $error =  "Se requiere el campo sueldo";
            break;
        }
        //validando la llave bono
        if( array_key_exists('bono', $value) ){
            if(!is_numeric($value['bono']) || $value['bono'] <= 0){
                $error =  "El campo bono no es válido";
                break;
            }
        }else{
            $error =  "Se requiere el campo bono";
            break;
        }
        //validando la llave equipo
        if( array_key_exists('equipo', $value) ){
            if(!is_string($value['equipo'])){
                $error =  "El campo equipo no es válido";
                break;
            }
        }else{
            $error =  "Se requiere el campo equipo";
            break;
        }
    }
    return $error;
}
/** alcances función que recibe los goles obtenidos y los goles requeridos y devuelve el alcance
 * ya sea individual o por equipo funciona para ambos casos y valida que el alcance no sea
 * mayor a 100% ni menor a 0%.
*/
function alcances($meta_lograda, $meta){
    //Si la meta es negativa no gana el bono
    if($meta < 0){
        $x = 0;
    //Si la meta es cero gana el 100% del bono correspondiente a la meta individual
    }elseif($meta == 0){
        $x = 100;
    //Si la meta lograda es negativa no gana el bono
    }elseif(  $meta_lograda < 0){
        $x = 0;
    //Si la meta lograda es mayor que la meta se gana el 100% correspondiente a la meta individual
    }elseif($meta_lograda >= $meta){
        $x = 100;
    }else{
        $x = $meta_lograda * 100 / $meta;
    }
    return $x;
}
/**calculaSueldo función que recibe el alcance individual, el alcance en equipo, el numero de jugadores 
 * el monto máximo en bono y el sueldo base. La cual devuelve el sueldo total por jugador.
*/
function calculaSueldo($alcance_individual, $alcance_equipo, $jugadores, $bono, $sueldo_base){
    $alcance_compuesto = ($alcance_individual + $alcance_equipo) / $jugadores;
    $bono_lacanzado = ($alcance_compuesto / 100) * $bono;
    $sueldo_total = $sueldo_base + $bono_lacanzado;
    return $sueldo_total;
}
/** getInfo función que recibe el JSON original convertido en array, el alcance por equipo, el objetivo por
 * equipo y número de jugadores y devuelve un array con la información anexada.
 */
function getInfo($json_array, $equipo_alcance, $equipo_meta, $equipo_jugadores){
    foreach($json_array as $key => $value){
        // se obtiene el número de goles requeridos de acuerdo al nivel
        if( array_key_exists('nivel', $value) ){
            $meta_individual = getMetaIndividual($value['nivel']);
        }else{
            $meta_individual = $value['goles_minimos'];
        }
        $json_array[$key]['nivel_goles'] = $meta_individual;
        //Se obtiene el porcentaje de alcance de manera individual.
        $alcance_individual = alcances($value['goles'], $meta_individual);
        $json_array[$key]['porcentaje_individual'] = $alcance_individual; 
        //se obtiene el porcentaje de alcance en equipo
        $alcance_grupal = alcances($equipo_alcance[ $value['equipo'] ], $equipo_meta[ $value['equipo'] ]);
        $json_array[$key]['porcentaje_equipo'] = $alcance_grupal;
        $json_array[$key]['sueldo_completo'] = calculaSueldo($alcance_individual, $alcance_grupal, $equipo_jugadores[ $value['equipo']],  $value['bono'], $value['sueldo'] );
    }
    return $json_array;
}
/** result función que recibe el array con la información anexada y  genera la vista final 
 * a devolver al cliente con los resultados requeridos 
 **/
function result($json_array){
    $result='
        <div class="col-md-12 show_response">
            <h2 class="titles">Resultado</h2>
            <table class="table table-bordered table-dark table-responsive-lg">
                <tbody>
                    <tr>
                        <th>Nombre</th>
                        <th>Equipo</th>
                        <th>Goles</th>
                        <th>Alcance individual</th>
                        <th>Alcance en equipo</th>
                        <th>Sueldo</th>
                    </tr>';
                    foreach($json_array as $value){
        $result.='<tr>
                        <td>'.$value['nombre'].'</td>
                        <td>'.$value['equipo'].'</td>
                        <td>'.$value['goles'].'</td>
                        <td style="width:200px;"><div class="myChart" style="width:'.round($value['porcentaje_individual'], 2).'px;" >'.round($value['porcentaje_individual'], 2).'% </div></td>
                        <td style="width:200px;"><div class="myChart" style="width:'.round($value['porcentaje_equipo'], 2).'px;" >'.round($value['porcentaje_equipo'], 2).'% </div></td>
                        <td>$'.formatCoin($value['sueldo_completo']).'</td>
                    </tr>';
                    }
    $result.= '</tbody>
            </table>
        </div>';
    return $result;
}
/**Si existe algún error durante la lectura, análisis y/o procesamiento de los datos del array
*se devuelve $error con una vista para el cliente
*/
$show_error = '<div class="show_error alert alert-danger alert-dismissible fade show" role="alert">
                <strong>¡Error! </strong> '.$error.'
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
/**Se regresa un array en formato JSON al cliente* */
$data = array('error'=> $error, 'show_error' => $show_error, 'content' => $content);
echo $data = json_encode($data);
