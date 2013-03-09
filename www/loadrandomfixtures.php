<?php
	include_once 'Conexion.php';

	$db = 'mysql';
	$conexion = new Conexion('root', '18990567', $db, 'localhost');

	$tablas = $conexion->getTablesName();


?>

<h1>Base Datos: <?php echo $db; ?></h1>
<table>
	<tr>
		<th>Tablas</th>
	</tr>	
	<?php foreach ($tablas as $tabla): ?>
	<tr>
		<td><strong><em><?php echo $tabla; ?></em></strong></td>
		<?php $campos = $conexion->getColumnsForTable($tabla); ?>
		<?php foreach ($campos as $campo): ?>
		<td><?php echo $campo['Field']; ?></td>
		<?php endforeach; ?>
	</tr>
	<?php endforeach; ?>	
</table>

<?php
/**
* 
*/
class LoadRandomFixtures extends Conexion
{
	
	function __construct($usuario = 'root', $password = '', $bd = 'test', $host = 'localhost')
	{
		parent::__construct($usuario, $password, $bd, $host);
	}

	public function load($table = '')
	{
		$colums = $this->getColumnsForTable($table);
		$cf = 0;
		$strFields = "INSERT INTO $table (";
		$strValues = ' VALUES (';
		foreach ($field as $colums) {
			if($cf == 0) {
				$strFields .= $field['Field'];
				$strField .= $this->getDataFromType($field['Type']);
				$cf++;
			}
			else {
				$strFields .= ', ', $field['Field'];
				$strField .= ', ', $this->getDataFromType($field['Type']);
			}
		}
		return $colums;
	}

	public function getDataFromType($type = '')
	{
		$strData = '';
		if (strpos('varchar', $type)) {
			# code...
		} elseif (strpos('char', $type)) {
			# code...
		} elseif (strpos('tinytext', $type) || strpos('tinyblob', $type)) {
			# code...
		} elseif (strpos('text', $type) || strpos('blob', $type)) {
			# code...
		} elseif (strpos('mediumtext', $type) || strpos('mediumblob', $type)) {
			# code...
		} elseif (strpos('longtext', $type) || strpos('longblob', $type)) {
			# code...
		} elseif (strpos('tinyint', $type) || strpos('smallint', $type) || 
				  strpos('mediumint', $type) || strpos('integer', $type) || 
				  strpos('bigint', $type)) {
			
		} elseif (strpos('float', $type) || strpos('xreal', $type) || 
				  strpos('double', $type) || strpos('decimal', $type) || 
				  strpos('numeric', $type)) {
			# code...
		} elseif (strpos('date', $type)) {
			# code...
		} elseif (strpos('datetime', $type)) {
			# code...
		} elseif (strpos('timestamp', $type)) {
			# code...
		} elseif (strpos('time', $type)) {
			# code...
		} elseif (strpos('year', $type)) {
			# code...
		}
}
?>