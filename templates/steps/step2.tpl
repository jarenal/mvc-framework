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
	<h1>{title}</h1>
	<div class="row">
        {% foreach(products as product) %}
		<div class="col-sm">
			<div class="card mt-3" style="width: 18rem;">
				<img src="images/dummy-product.png" class="card-img-top" alt="...">
				<div class="card-body">
					<h5 class="card-title">{product.name}</h5>
					<p class="card-text">{product.description}</p>
					<div class="text-right">
						<a href="#" class="btn btn-primary btn-add" data-id="{product.id}" data-name="{product.name}" data-category="{product.category}">Add</a>
					</div>
				</div>
			</div>
		</div>
        {% endforeach %}
	</div>
	<form action="/step3" method="post">
		<button type="submit">Next step</button>
	</form>
</div>
<div class="modal fade" id="subscription" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Select the dates</p>
					<form method="post" action="/step2">
						<div class="form-group">
							<label for="start_date">Start date</label>
							<input id="start_date" type="text" class="form-control" placeholder="dd/mm/yyyy" name="start_date">
						</div>
						<div class="form-group">
							<label for="end_date">End date</label>
							<input id="end_date" type="text" class="form-control" placeholder="dd/mm/yyyy" name="end_date">
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Add to cart</button>
				</div>
		</div>
	</div>
</div>
<div class="modal fade" id="service" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" action="/step2">
						<div class="form-group">
							<label for="dayofweek">Day of week</label>
							<select id="dayofweek" name="dayofweek" class="form-control">
								<option value="1">Monday</option>
								<option value="2">Tuesday</option>
								<option value="3">Wednesday</option>
								<option value="4">Thursday</option>
								<option value="5">Friday</option>
								<option value="6">Saturday</option>
							</select>
						</div>
						<div class="form-group">
							<label for="start_time">From</label>
							<select id="start_time" name="start_time" class="form-control">
								<option value="">--</option>
								<option value="9">9:00</option>
								<option value="10">10:00</option>
								<option value="11">11:00</option>
								<option value="12">12:00</option>
								<option value="13">13:00</option>
								<option value="14">14:00</option>
								<option value="15">15:00</option>
								<option value="16">16:00</option>
								<option value="17">17:00</option>
								<option value="18">18:00</option>
							</select>
						</div>
						<div class="form-group">
							<label for="end_time">To</label>
							<select id="end_time" name="end_time" class="form-control" disabled>
								<option value="10">10:00</option>
								<option value="11">11:00</option>
								<option value="12">12:00</option>
								<option value="13">13:00</option>
								<option value="14">14:00</option>
								<option value="15">15:00</option>
								<option value="16">16:00</option>
								<option value="17">17:00</option>
								<option value="18">18:00</option>
								<option value="19">19:00</option>
							</select>
						</div>
						<div class="form-group">
							<label for="weeks">Weeks</label>
							<input id="weeks" type="text" class="form-control" name="weeks">
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Add to cart</button>
				</div>
		</div>
	</div>
</div>
<div class="modal fade" id="goods" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="/step2">
					<div class="form-group">
						<label for="quantity">Quantity</label>
						<input id="quantity" type="text" class="form-control" name="quantity">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Add to cart</button>
			</div>
		</div>
	</div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="js/app.js"></script>
</body>
</html>