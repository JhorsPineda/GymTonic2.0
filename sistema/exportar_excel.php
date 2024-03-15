<?php

require 'vendor/autoload.php';
require '/conexion.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet,  
    PhpOffice\PhpSpreadsheet\Writer\Xlsx,
    PhpOffice\PhpSpreadsheet\IOFactory;


$sql = "SELECT codproducto, descripcion, proveedor, precio, existencia, usuario_id FROM producto";
$resultado = $mysqli->query($sql);

$excel = new Spreadsheet();
$hojaActiva = $excel->getActiveSheet();
$hojaActiva->setTitle("Productos");

$hojaActiva->setCellValue('A1', 'CODPRODUCTO');
$hojaActiva->setCellValue('B1', 'DESCRIPCION');
$hojaActiva->setCellValue('C1', 'PROVEEDOR');
$hojaActiva->setCellValue('D1', 'PRECIO');
$hojaActiva->setCellValue('E1', 'EXISTENCIA');
$hojaActiva->setCellValue('F1', 'USUARIO_ID');

while($rows = $resultado->fetch_assoc() ){
    $hojaActiva->setCellValue('A'.$fila, $rows['codproducto']);
    $hojaActiva->setCellValue('B'.$fila, $rows['descripcion']);
    $hojaActiva->setCellValue('C'.$fila, $rows['proveedor']);
    $hojaActiva->setCellValue('D'.$fila, $rows['precio']);
    $hojaActiva->setCellValue('E'.$fila, $rows['existencia']);
    $hojaActiva->setCellValue('F'.$fila, $rows['usuario_id']);
    $fila++;
} 

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Productos.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
?>