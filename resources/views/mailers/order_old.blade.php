<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<h1>Listado de su Compra</h1>
	<table>
	<thead>
		<tr>
			<th>Producto</th>
			<th>Costo</th>
		</tr>
	</thead>
		
	</table>

	<tbody>

	@foreach($products as $product)

		<tr>
			<th>{{$product->title}}}</th>
			<th>{{$product->pricing}}}</th>
		</tr>	


	@endforeach

	<tr>
			<th>Total:</th>
			<th>{{$order->total}}}</th>
		</tr>	
		
	</tbody>
</body>
</html>