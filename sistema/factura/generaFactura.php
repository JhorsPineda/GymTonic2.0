<?php
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}
	include "../../conexion.php";
	if(empty($_REQUEST['cl']) || empty($_REQUEST['f']))
	{
		echo "No es posible generar la factura.";
	}else{
		$codCliente = $_REQUEST['cl'];
		$noFactura = $_REQUEST['f'];
		$consulta = mysqli_query($conexion, "SELECT * FROM configuracion");
		$resultado = mysqli_fetch_assoc($consulta);
		$ventas = mysqli_query($conexion, "SELECT * FROM factura WHERE nofactura = $noFactura");
		$result_venta = mysqli_fetch_assoc($ventas);
		$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $codCliente");
		$result_cliente = mysqli_fetch_assoc($clientes);
		$productos = mysqli_query($conexion, "SELECT d.nofactura, d.codproducto, d.cantidad, p.codproducto, p.descripcion, p.precio FROM detallefactura d INNER JOIN producto p ON d.nofactura = $noFactura WHERE d.codproducto = p.codproducto");
		require_once 'fpdf/fpdf.php';
		$pdf = new FPDF('L', 'mm', array(120, 170)); 
		$pdf->AddPage();
		$pdf->SetMargins(1, 0, 0);
		$pdf->SetTitle("Venta #" . $noFactura); 
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(150, 5, "GYM & TONIC", 0, 1, 'C'); 
		$pdf->SetFont('Arial', '', 9);
		$pdf->Ln();
		$pdf->image("img/logo.png", 100, 18, 30, 30, 'PNG'); 
		
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "NIT: ", 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);	
		$pdf->Cell(20, 5, $resultado['documento'], 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(15, 5, "Teléfono: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(20, 5, $resultado['telefono'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, "Dirección: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(20, 5, mb_convert_encoding($resultado['direccion'], 'UTF-8', 'ISO-8859-1'), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, "Ticked: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(20, 5, $noFactura, 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(16, 5, "Fecha: ", 0, 0, 'R');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(25, 5, $result_venta['fecha'], 0, 1, 'R');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(150, 5, "Datos del cliente", 0, 1, 'C'); 
$pdf->Cell(75, 5, "Nombre", 0, 0, 'C');
$pdf->Cell(37, 5, "Teléfono", 0, 0, 'C');
$pdf->Cell(38, 5, "Dirección", 0, 1, 'C');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(75, 5, $result_cliente['nombre'], 0, 0, 'C');
$pdf->Cell(37, 5, mb_convert_encoding($result_cliente['telefono'], 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
$pdf->Cell(38, 5, mb_convert_encoding($result_cliente['direccion'], 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(150, 5, "Detalle de Productos", 0, 1, 'C'); 
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(90, 5, 'Nombre', 0, 0, 'C'); 
$pdf->Cell(20, 5, 'Cant', 0, 0, 'C');
$pdf->Cell(20, 5, 'Precio', 0, 0, 'C');
$pdf->Cell(20, 5, 'Total', 0, 1, 'C');
$pdf->SetFont('Arial', '', 7);
while ($row = mysqli_fetch_assoc($productos)) {
    $pdf->Cell(90, 5, $row['descripcion'], 0, 0, 'C'); 
    $pdf->Cell(20, 5, $row['cantidad'], 0, 0, 'C');
    $pdf->Cell(20, 5, number_format($row['precio'], 2, '.', ','), 0, 0, 'C');
    $importe = number_format($row['cantidad'] * $row['precio'], 2, '.', ',');
    $pdf->Cell(20, 5, $importe, 0, 1, 'C');
}
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(150, 5, 'Total: ' . number_format($result_venta['totalfactura'], 2, '.', ','), 0, 1, 'C'); 
$pdf->Ln();
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(150, 5, "Gracias por su preferencia", 0, 1, 'C'); 
$pdf->Output("compra.pdf", "I");

	}

?>
