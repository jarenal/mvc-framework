<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<title>{title}</title>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-sm text-center">
			<h3>Quote reference: {quote.reference}</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-12 mt-4">
			<h3>User details</h3>
		</div>
		<div class="col-12 mt-4">
			<table class="table">
				<tbody>
				<tr>
					<th scope="row">Name</th>
					<td>{user.name}</td>
				</tr>
				<tr>
					<th scope="row">Email</th>
					<td>{user.email}</td>
				</tr>
				<tr>
					<th scope="row">Phone</th>
					<td>{user.phone}</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-12 mt-4">
			<h3>Quote lines</h3>
		</div>
		<div class="col-12 mt-4">
			<table class="table">
				<thead>
				<tr>
					<th scope="row">#</th>
					<th scope="col">Product</th>
					<th scope="col">Category</th>
					<th scope="col">Quantity</th>
					<th scope="col">Subtotal</th>
				</tr>
				</thead>
				<tbody>
                {% foreach(lines as line) %}
				<tr>
					<th scope="row">{line._index}</th>
					<td>{line.product}</td>
					<td>{line.metadata.category}</td>
					<td>{line.quantity}</td>
					<td>&pound;{line.subtotal}</td>
				</tr>
                <tr>
					<td colspan="5">
						{% switch line.metadata.category_id %}
							{% case 1 %}
								<div class="ml-5">
									Start date: {line.metadata.start_date}<br>
									End date: {line.metadata.end_date}
								</div>
							{% case 2 %}
								<div class="ml-5">
					                Day of week: {line.metadata.dayofweek}<br>
					                Start time: {line.metadata.start_time}:00<br>
					                End time: {line.metadata.end_time}:00<br>
					                Weeks: {line.metadata.weeks}
								</div>
							{% case 3 %}
								&nbsp;
						{% endswitch %}
					</td>
                </tr>
                {% endforeach %}
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-sm mt-4 text-right">
			<h3>TOTAL: <span>&pound;{total}</span></h3>
		</div>
	</div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>