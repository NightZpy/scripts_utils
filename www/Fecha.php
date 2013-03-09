<?php


class Fecha {

    private $diaSemana;
    private $mesAnno;
    private $dia;
    private $mes;
    private $year;
    private $segundo;
    private $minuto;
    private $hora;
    private $GMT;

    function __construct($GMT="0") {
        $this->GMT = $GMT;
    }

    //Métodos utiles par manejar fechas
    public function fechaToDia($fecha){
        if(!$this->esFormatoValido($fecha))
            return NULL;

        $fecha= empty($fecha)? date('d/m/Y') : $fecha;
        $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');

        $dd   = $this->fechaToArray($fecha);
        if(!$dd)
            return NULL;

        if($this->isMySqlFormat($fecha))
            $ts   = mktime(0,0,0,$dd[1],$dd[2],$dd[0]);
        else
            $ts   = mktime(0,0,0,$dd[1],$dd[0],$dd[2]);
        
        return $dias[date('w',$ts)];
    }

    public function isMySqlFormat($fecha) {
        return preg_match("/([0-9][0-9]){1,2}-[0-1][0-9]{1}-[0-3][0-9]{1}/",$fecha);
    }

    public function esFormatoValido($fecha) {
        return (preg_match("/([0-9][0-9]){1,2}-[0-1][0-9]{1}-[0-3][0-9]{1}/",   $fecha)  ||
                preg_match("/([0-9][0-9]){1,2}\/[0-1][0-9]{1}\/[0-3][0-9]{1}/", $fecha)  ||
                preg_match("/[0-3][0-9]{1}\/[0-1][0-9]{1}\/([0-9][0-9]){1,2}/", $fecha)  ||
                preg_match("/[0-3][0-9]{1}-[0-1][0-9]{1}-([0-9][0-9]){1,2}/",   $fecha));
    }

    public function fechaSiguiente($fecha) {

        //date("d-m-Y", strtotime("+1 day"));
        if($this->esFormatoValido($fecha))
        {
            $arrayDiasPorMesAsoc = array(
                "Enero"         => 31,
                "Febrero"       => 28,
                "Marzo"         => 31,
                "Abril"         => 30,
                "Mayo"          => 31,
                "Junio"         => 30,
                "Julio"         => 31,
                "Agosto"        => 31,
                "Septiembre"    => 30,
                "Octubre"       => 31,
                "Noviembre"     => 30,
                "Diciembre"     => 31
            );

            $arrayDiasPorMes = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30,31);

            //$fecha = $this->cambiarFormato("/", $fecha);
            $arrayFecha = $this->fechaToArray($fecha);
            if(!$arrayFecha)
                return NULL;

            $convertir = new Convertir();
            $anno   = $convertir->aEntero($arrayFecha[2]);
            $mes    = $convertir->aEntero($arrayFecha[1]);
            $dia    = $convertir->aEntero($arrayFecha[0]);
            $diasMes = $arrayDiasPorMes[$mes-1];

            if($dia+1 <= $diasMes)
            {
                $dia++;
            }
            else
            {
                $dia=1;
                ($mes+1 <= 12)       ? $mes++ : $mes=1;
                ($mes==1)            ? $anno++ : NULL;
            }
            $diaN  = NULL;
            $mesN  = NULL;
            $annoN = NULL;
            ($dia  < 10)  ? $diaN  = "0".$dia  : $diaN  = $dia;
            ($mes  < 10)  ? $mesN  = "0".$mes  : $mesN  = $mes;
            ($anno < 10)  ? $annoN = "0".$anno : $annoN = $anno;

            $newFecha = $diaN."/".$mesN."/".$annoN;
            return $newFecha;
        }
        return NULL;
    }

    public function fechaAnterior($fecha) {
        $arrayDiasPorMes = array(
            "Enero"         => 31,
            "Febrero"       => 28,
            "Marzo"         => 31,
            "Abril"         => 30,
            "Mayo"          => 31,
            "Junio"         => 30,
            "Julio"         => 31,
            "Agosto"        => 31,
            "Septiembre"    => 30,
            "Octubre"       => 31,
            "Noviembre"     => 30,
            "Diciembre"     => 31
        );

        $arrayFecha = $this->fechaToArray($fecha);

        if(!$arrayFecha)
            return NULL;

        $convertir = new Convertir();
        $anno   = $convertir->aEntero($arrayFecha[2]);
        $mes    = $convertir->aEntero($arrayFecha[1]);
        $dia    = $convertir->aEntero($arrayFecha[0]);
        $diasMes = $arrayDiasPorMes[$mes-1];

        ($dia+1 <= $diasMes) ? $dia++ : $dia=1;
        ($dia==1) ? (($mes+1 <= 12)?$mes++ : $mes=1) : NULL;
        ($mes==1) ? $anno++ : NULL;

        ($dia<10)  ? $diaN  = "0".$dia  : NULL;
        ($mes<10)  ? $mesN  = "0".$mes  : NULL;
        ($anno<10) ? $annoN = "0".$anno : NULL;

        if($this->isMySqlFormat($fecha))
            $newFecha = $annoN.'-'.$mesN.'-'.$diaN;
        else
            $newFecha = $diaN."/".$mesN."/".$annoN;
        return $newFecha;
    }

    public function cambiarFormato($fecha){
        if($this->esFormatoValido($fecha))
        {
            $fechaArray =   $this->fechaToArray($fecha);
            if($this->isMySqlFormat($fecha))
                return $fechaArray[2].'/'.$fechaArray[1].'/'.$fechaArray[0];
            else
                return $fechaArray[2].'-'.$fechaArray[1].'-'.$fechaArray[0];
        }
   	return NULL;
    }

    public function diasEntre($fecha1, $fecha2) {

        if(!$this->esFormatoValido($fecha1) || !$this->esFormatoValido($fecha2))
            return NULL;

        $arrayFecha1 = $this->fechaToArray($fecha1);
        $arrayFecha2 = $this->fechaToArray($fecha2);

        if(!$arrayFecha1 || !$arrayFecha2)
            return NULL;

        if($this->isMySqlFormat($fecha1))
            $timestamp1 = mktime(0,0,0,$arrayFecha1[1],$arrayFecha1[2],$arrayFecha1[0]);
        else
            $timestamp1 = mktime(0,0,0,$arrayFecha1[1],$arrayFecha1[0],$arrayFecha1[2]);

        if($this->isMySqlFormat($fecha2))
            $timestamp2 = mktime(0,0,0,$arrayFecha2[1],$arrayFecha2[2],$arrayFecha2[0]);
        else
            $timestamp2 = mktime(0,0,0,$arrayFecha2[1],$arrayFecha2[0],$arrayFecha2[2]);

        $segundosDif = $timestamp1 - $timestamp2;

        //convierto segundos en días
        $diasDiferencia = $segundosDif / (60 * 60 * 24);

        //obtengo el valor absoulto de los días (quito el posible signo negativo)
        return abs(floor($diasDiferencia));
    }

    public function compararFecha($fecha1, $fecha2) {

        if(!$this->esFormatoValido($fecha1) || !$this->esFormatoValido($fecha2))
                return NULL;

        if($this->isMySqlFormat($fecha1) && !$this->isMySqlFormat($fecha2))
            return NULL;
        
        if(!$this->isMySqlFormat($fecha1) && $this->isMySqlFormat($fecha2))
            return NULL;
        
        $fecha1Array = $this->fechaToArray($fecha1);
        $fecha2Array = $this->fechaToArray($fecha2);

        if(!$fecha1Array || !$fecha2Array)
            return NULL;

        if(!$this->isMySqlFormat($fecha1) && !$this->isMySqlFormat($fecha2))
        {
            if($fecha1Array[2] > $fecha2Array[2])
            {
                return TRUE;
            }
            else
            {
                if($fecha1Array[1] > $fecha2Array[1])
                {
                    return TRUE;
                }
                else
                {
                    if($fecha1Array[0] > $fecha2Array[0])
                        return TRUE;
                    else
                        return FALSE;
                }

            }
        }

        if($this->isMySqlFormat($fecha1) && $this->isMySqlFormat($fecha2))
        {
              if($fecha1Array[0] > $fecha2Array[0])
            {
                return TRUE;
            }
            else
            {
                if($fecha1Array[1] > $fecha2Array[1])
                {
                    return TRUE;
                }
                else
                {
                    if($fecha1Array[2] > $fecha2Array[2])
                        return TRUE;
                    else
                        return FALSE;
                }

            }
        }
    }

    public function diaValido($fecha) {
        return ($this->fechaToDia($fecha) != "Sabado" && $this->fechaToDia($fecha) != "Domingo");
    }

    public function fechaToArray($fecha) {
        if($this->esFormatoValido($fecha))
        {
                return ($this->isMySqlFormat($fecha) ? preg_split("[".'-'."]", $fecha, NULL, PREG_SPLIT_NO_EMPTY) : preg_split("[".'/'."]", $fecha, NULL, PREG_SPLIT_NO_EMPTY));
        }
        return NULL;
    }

    //Métodos utiles par manejar horas
    public function compararHora($tiempo1, $tiempo2) {
        $tiempo1Array = $this->horaToArray($tiempo1);
        $tiempo2Array = $this->horaToArray($tiempo2);

        //$convertir = new Convertir();

        /*$seg1   = $convertir->aEntero($tiempo1Array[2]);
        $min1   = $convertir->aEntero($tiempo1Array[1]);
        $hora1  = $convertir->aEntero($tiempo1Array[0]);

        $seg2   = $convertir->aEntero($tiempo2Array[2]);
        $min2   = $convertir->aEntero($tiempo2Array[1]);
        $hora2  = $convertir->aEntero($tiempo2Array[0]);
        //15:00:1 <-> 18:00:00

        $operacion = false;
        if($hora1 >= $hora2)
        {
            if($hora1 == $hora2)
            {
                if($min1 >= $min2)
                {
                    if($min1 == $min2)
                    {
                        if($seg1 >= $seg2)
                        {
                            $operacion = true;
                        }
                        else
                        {
                            $operacion = false;
                        }
                    }
                    else
                    {
                        $operacion = true;
                    }
                }
            }
            else
            {
                $operacion = true;
            }
        }
        else
        {
            $operacion = false;
        }*/

        $totMinHr1      = ($tiempo1Array[0]*60) + $tiempo1Array[1];
        $totMinHr2      = ($tiempo2Array[0]*60) + $tiempo2Array[1];

        $operacion = ($totMinHr1 > $totMinHr2) ? TRUE : FALSE;

        return $operacion;
    }

    public function horaEntre($tiempo, $tiempoInicio, $tiempoFin){
        $tiempoArray        = $this->horaToArray($tiempo);
        $tiempoInicioArray  = $this->horaToArray($tiempoInicio);
        $tiempoFinArray     = $this->horaToArray($tiempoFin);

        $totMinT         = ($tiempoArray[0]*60)         +   $tiempoArray[1];
        $totMinTIni      = ($tiempoInicioArray[0]*60)   +   $tiempoInicioArray[1];
        $totMinTFin      = ($tiempoFinArray[0]*60)      +   $tiempoFinArray[1];

        $operacion = ($totMinTIni <= $totMinT && $totMinTFin >= $totMinT) ? TRUE : FALSE;
        return $operacion;

    }

    public function difEntreHoras($hora1, $hora2) {
        $convertir = new Convertir();
        $tiempo1Array = $this->horaToArray($hora1);
        $tiempo2Array = $this->horaToArray($hora2);

        $totMinHr1      = ($tiempo1Array[0]*60) + $tiempo1Array[1];
        $totMinHr2      = ($tiempo2Array[0]*60) + $tiempo2Array[1];

        if($this->compararHora($hora1, $hora2))
            $minDif = $totMinHr1 - $totMinHr2;
        else
            $minDif = $totMinHr2 - $totMinHr1;

        if($minDif > 60)
        {
            $convertir = new Convertir();
            $horaC = $convertir->aEntero($minDif / 60);
            $minC  = $convertir->aEntero($minDif % 60);
            if($horaC < 10)
                $horaC='0'.$horaC;
            if($minC < 10)
                $minC='0'.$minC;
            $diferencia = "".$horaC.":".$minC;
        }
        else
        {
            if($minDif < 10)
                $minDif='0'.$minDif;
            $diferencia = "00:".$minDif;
        }
        return $diferencia;
    }

    public function minDifEntreHoras($hora1, $hora2) {
        $convertir = new Convertir();
        $tiempo1Array = $this->horaToArray($hora1);
        $tiempo2Array = $this->horaToArray($hora2);

        $totMinHr1      = ($tiempo1Array[0]*60) + $tiempo1Array[1];
        $totMinHr2      = ($tiempo2Array[0]*60) + $tiempo2Array[1];

        $minDif = ($this->compararHora($hora1, $hora2)) ? $totMinHr1 - $totMinHr2 : $totMinHr2 - $totMinHr1;

        return $minDif;
    }

    public function segDifEntreHoras($hora1, $hora2) {
        $convertir = new Convertir();
        $tiempo1Array = $this->horaToArray($hora1);
        $tiempo2Array = $this->horaToArray($hora2);

        $totMinHr1      = ($tiempo1Array[0]*60) + $tiempo1Array[1];
        $totMinHr2      = ($tiempo2Array[0]*60) + $tiempo2Array[1];

        $segDif = 60*($this->compararHora($hora1, $hora2)) ? $totMinHr1 - $totMinHr2 : $totMinHr2 - $totMinHr1;

        return $segDif;
    }



    public function horaToArray($hora) {
        return preg_split("/:/", $hora, NULL, PREG_SPLIT_NO_EMPTY);
    }

    public function diaSemana() {
        $diaSemana = strtoupper(date("l"));

        switch ($diaSemana) {
            case 'MONDAY':
                $this->diaSemana = 'Lunes';
            break;

            case 'TUESDAY':
                $this->diaSemana = 'Martes';
            break;

            case 'WEDNESDAY':
                $this->diaSemana = 'Miercoles';
            break;

            case 'THURSDAY':
                $this->diaSemana = 'Jueves';
            break;

            case 'FRIDAY':
                $this->diaSemana = 'Viernes';
            break;

            case 'SATURDAY':
                $this->diaSemana = 'Sabado';
            break;

            case 'SUNDAY':
                $this->diaSemana = 'Domingo';
            break;
            default:
                $this->diaSemana = NULL;
            break;
        }
        return $this->diaSemana;
    }

    public function mesAnno() {
        $mesAnno = strtoupper(date("F"));
        switch ($mesAnno) {
            
            case 'JANUARY':
                $this->mesAnno = 'Enero';
            break;

            case 'FEBRUARY':
                $this->mesAnno = 'Febrero';
            break;

            case 'MARCH':
                $this->mesAnno = 'Marzo';
            break;

            case 'APRIL':
                $this->mesAnno = 'Abril';
            break;

            case 'MAY':
                $this->mesAnno = 'Mayo';
            break;

            case 'JUNE':
                $this->mesAnno = 'Junio';
            break;

            case 'JULY':
                $this->mesAnno = 'Julio';
            break;

            case 'AUGUST':
                $this->mesAnno = 'Agosto';
            break;

            case 'SEPTEMBER':
                $this->mesAnno = 'Septiembre';
            break;

            case 'OCTUBER':
                $this->mesAnno = 'Octubre';
            break;

            case 'NOVEMBER':
                $this->mesAnno = 'Noviembre';
            break;

            case 'DECEMBER':
                $this->mesAnno = 'Diciembre';
            break;

            default:
                $this->mesAnno = NULL;
            break;
        }
        return $this->mesAnno;
    }

    public function hora24ToAmPm($hora)
    {
        return date("g:i a",strtotime($hora));
    }

    public function horaAmPmTo24($hora) {
        return date("H:i", $hora);
    }

    public function getDia() {
        return ($this->dia = date("d"));
    }

    public function getMes() {
        return ($this->mes = date("m"));
    }

    public function getYear() {
        return ($this->year = date("Y"));
    }

    public function getSegundo() {
        return ($this->segundo = date("s"));
    }

    public function getMinuto() {
        return ($this->minuto = date("i"));
    }

    public function getHora24() {
        return ($this->hora = date("H"));
    }

    public function getHora12() {
        return ($this->hora = date("h"));
    }

    public function getAmPm() {
        return ($this->UTC = date("A"));
    }

    public function fechaCompletaEs($s) {
        return date("d".$s."m".$s."Y");
    }

    public function fechaCompletaInt($s) {
        return date("Y".$s."m".$s."d");
    }

    public function horaCompleta24() {
        return gmdate("H:i:s", time()+($this->GMT*3600));
    }

    public function horaCompleta12() {
        return gmdate("H:i:s A", time()+($this->GMT*3600));
    }

}
?>

