<?php
include_once 'Conexion.php';
include_once 'Fecha.php';

class LoadRandomFixtures extends Conexion
{
	private $text;
	function __construct($usuario = 'root', $password = '', $bd = 'test', $host = 'localhost', $text = '')
	{
		parent::__construct($usuario, $password, $bd, $host);
		$this->text = $text;
	}

	public function load($table = '', $count)
	{
		$colums = $this->getColumnsForTable($table);
		$cf = 0;
		$strFields = "INSERT INTO $table (";
		$strValues = ' VALUES (';
		foreach ($colums as $field) {
			$fieldName = $field['Field'];
			$fieldType = $field['Type'];
			$extra = $field['Extra'];
			//echo "<br />Column #$cf";
			$fieldLen = $this->getTypeLen($fieldType); 
			//echo "<br />FieldName: $fieldName - FielType: $fieldType - FieldLen: $fieldLen";
			if($cf == 0) {
				$strFields .= $fieldName;
				$strValues .= $this->getDataFromType($fieldType, $fieldLen, $extra);
			}
			else {
				$strFields .= ', '.$fieldName;
				$strValues .= ', '.$this->getDataFromType($fieldType, $fieldLen, $extra, $table, $count);
			}
			$cf++;
		}
		$strValues .= ')';
		$strFields .= ')';
		return $strFields.$strValues;
	}

	public function getTypeLen($type = '')
	{
		return preg_replace("/([^0-9])/", "", $type); 
	}

	public function getDataFromType($type = '', $size = 0, $extra = '', $table = '', $count = 0)
	{
		//$strData = '';
		//echo "<br /><b>Tipo: $type</b>";
		if (strpos($type, 'varchar') !== FALSE || strpos($type, 'char') !== FALSE) {
			return "'".substr(substr($table, 0, $size - 1) + $count, 0)."'";
			//return "'".substr($this->text, 0, $size)."'";
		} elseif (strpos($type, 'tinytext') !== FALSE || strpos($type, 'tinyblob') !== FALSE || 
				  strpos($type, 'text') !== FALSE || strpos($type, 'blob') !== FALSE ||
				  strpos($type, 'mediumtext') !== FALSE || strpos($type, 'mediumblob') !== FALSE ||
				  strpos($type, 'longtext') !== FALSE || strpos($type, 'longblob') !== FALSE) {			
			return "'".substr($this->text, 0, 512)."'";
		} elseif (strpos($type, 'tinyint') !== FALSE || strpos($type, 'smallint') !== FALSE || 
				  strpos($type, 'mediumint') !== FALSE || strpos($type, 'integer') !== FALSE || 
				  strpos($type, 'int') !== FALSE || strpos($type, 'bigint') !== FALSE) {
			if($extra == 'auto_increment') return 'default';
			else return rand(1, $size);
		} elseif (strpos($type, 'float') !== FALSE || strpos($type, 'xreal') !== FALSE || 
				  strpos($type, 'double') !== FALSE || strpos($type, 'decimal') !== FALSE || 
				  strpos($type, 'numeric') !== FALSE) {
			if($extra == 'auto_increment') {
				return 'default';
			} else {
				$half = $size / 2;
				$int = rand(1, $half);
				$dec = rand(1, $size - $half);
				return floatval(strval($int).'.'.strval($dec));
			}
		} elseif (strpos($type, 'datetime') !== FALSE || strpos($type, 'timestamp') !== FALSE) {
			$fecha = new Fecha('-4.5');
			return "'".$fecha->fechaCompletaInt('/')." ".$fecha->horaCompleta24()."'";		 			
		} elseif (strpos($type, 'date') !== FALSE) {
			$fecha = new Fecha('-4.5');
			return "'".$fecha->fechaCompletaInt('-')."'";
		} elseif (strpos($type, 'time') !== FALSE) {
			$fecha = new Fecha('-4.5');
			return "'".$fecha->horaCompleta24()."'";
		} elseif (strpos($type, 'year') !== FALSE) {
			return "'".$fecha->getYear()."'";
		}
	}
}

?>
<h1>Cargando Base Datos: <?php echo $db; ?></h1>
<?php

$text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae feugiat lacus. Nam euismod quam vitae ligula pretium ac malesuada mauris mollis. Fusce ornare sollicitudin aliquam. Sed mauris nisi, eleifend vitae iaculis id, ultricies sed metus. Proin sit amet velit vitae est pellentesque tristique ut eget lectus. Phasellus molestie rhoncus nisl et sodales. Maecenas elit velit, feugiat eget blandit at, sagittis vel velit. Curabitur ut sem vitae arcu gravida facilisis et ut erat. Aenean gravida pretium nisi eu mollis. Curabitur nisi ante, tempor et convallis eget, gravida nec eros. Nam placerat euismod sapien, ac consectetur diam consequat ut.
Mauris eleifend molestie leo pellentesque pharetra. Curabitur sem quam, adipiscing quis condimentum id, porttitor id lectus. Aliquam erat volutpat. Aliquam placerat dignissim erat id semper. Aliquam erat volutpat. In augue justo, elementum ut tincidunt id, lobortis eu elit. Aliquam dui lacus, molestie id hendrerit non, tincidunt quis felis. Nunc tempus sem aliquet eros egestas vitae lacinia dolor egestas.
Aenean suscipit, felis quis pharetra aliquet, urna massa facilisis augue, vel lacinia enim risus eget magna. Phasellus rhoncus lacinia purus, non dignissim lorem iaculis ac. Praesent vitae nibh nunc. Etiam eget hendrerit mauris. Donec orci felis, auctor ut bibendum eget, lacinia a nisi. Aenean lorem velit, posuere eu ultrices ut, faucibus a tellus. Donec elit felis, pulvinar at tincidunt nec, vehicula porta libero. Nulla gravida erat id arcu mollis fermentum. Aliquam posuere tempus metus, at lacinia mi fermentum quis. Quisque rutrum ultricies magna, lacinia bibendum enim cursus nec. Proin libero neque, vestibulum ac ornare pulvinar, posuere id purus.
Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In a enim ipsum, quis pellentesque orci. Vestibulum rhoncus volutpat lorem, et elementum orci egestas at. Fusce commodo ipsum nec tellus porttitor commodo. Donec venenatis, ipsum sit amet porttitor tempor, diam arcu feugiat nunc, nec pretium neque neque quis sem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nam tincidunt urna vel leo laoreet eu lacinia mauris sollicitudin. Suspendisse interdum, nisl congue posuere volutpat, mi nunc pretium erat, a bibendum erat nibh id lorem.
Pellentesque porttitor placerat dapibus. Cras cursus facilisis nisl, mattis tristique justo iaculis vel. Donec leo quam, interdum sit amet porta et, elementum ac arcu. Etiam fermentum posuere nisi vitae porta. Aenean quis purus lacinia purus egestas adipiscing. Quisque iaculis tellus eu quam ullamcorper at dignissim arcu lobortis. Etiam aliquam adipiscing lorem, non faucibus turpis pellentesque eu. Nunc cursus massa quis enim mattis et porttitor orci pretium. Duis malesuada tortor quis mauris bibendum vitae interdum ante imperdiet. Sed gravida sagittis est non suscipit. Aenean sed sem at risus condimentum interdum. Donec turpis risus, faucibus sit amet dignissim quis, egestas at urna. Suspendisse aliquet sapien sit amet odio dignissim at lacinia neque consequat. Vestibulum id arcu ac tellus lobortis fringilla in quis elit. Curabitur nec lacus lacus. Maecenas risus enim, volutpat at tincidunt vitae, vehicula a tellus.
Proin urna nibh, euismod vel rutrum in, ultricies at tortor. Phasellus vestibulum tincidunt sagittis. Etiam suscipit accumsan tristique. Integer ut erat nisi, in sagittis nisi. Etiam feugiat semper suscipit. Mauris lacinia elit vel nulla ultrices in dictum turpis consectetur. Mauris adipiscing consequat velit id condimentum. Mauris aliquet adipiscing fringilla. Praesent pulvinar eleifend elit, id malesuada urna gravida ut. Morbi et mi dui, sit amet molestie felis.
Aenean pharetra consequat ligula, sed ullamcorper tellus porta vel. Morbi cursus lacus at nulla elementum commodo. Suspendisse potenti. Ut venenatis augue eget nisl laoreet feugiat. Mauris ornare sem eget felis ultricies vestibulum at vitae magna. Nunc id massa velit. Sed augue dolor, imperdiet ut tincidunt eget, sagittis et ligula. Fusce auctor orci quis lectus euismod fermentum. Quisque vel diam leo. Nam eget nibh ligula, cursus tempus nisi.
Mauris nisl dui, iaculis vestibulum congue id, pellentesque vel enim. Integer consequat lobortis pharetra. Curabitur molestie sollicitudin mollis. Praesent vel nisl sapien. Etiam dictum justo euismod enim auctor lobortis. Nam imperdiet elit ut lectus facilisis tempor. Nullam erat metus, volutpat a fermentum ut, viverra id diam. Nam viverra felis in eros sagittis tincidunt. Sed eleifend elit in diam egestas fringilla. Curabitur a ante in velit facilisis mollis.
Suspendisse posuere consequat leo sed vestibulum. In hac habitasse platea dictumst. Aliquam ornare cursus lacus, a elementum nisi rhoncus nec. Mauris mattis augue vitae arcu ullamcorper in fringilla elit porta. Pellentesque ac lectus non elit dignissim facilisis. Sed et auctor tortor. Nunc vel facilisis orci. Nulla facilisi. Donec commodo nullam.';

$db = 'mercosur';
//$conexion = new Conexion('bashman', '123456', $db, 'mercosur.localhost');
//$tablas = $conexion->getTablesName();
$tablas = array('paises', 'estados', 'ramos', 'rubros', 'tipo_instituciones', 'localidades', 'direcciones', 'empresas',
				'personas', 'mesas', 'horarios', 'encuentros', 'productos', 'demandas', 'ofertas',
				'reuniones', 'ofertantes', 'demandantes', 'empresas_rubros');


$load = new LoadRandomFixtures('bashman', '123456', $db, 'mercosur.localhost', $text);
$nRegs = 10;
foreach ($tablas as $tabla){
?>
	<h2>Cargando Tabla: <?php echo $tabla; ?></h2>
<?php
	for ($i=1; $i < $nRegs; $i++)
		if($conexion->agregarRegistro($load->load($tabla), $i)) echo "<br />Agregado!";
}
?>

