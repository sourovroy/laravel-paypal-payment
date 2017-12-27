<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel Paypal Payment</title>

	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    
	<div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
				
				@if(request('error_message'))
				<div class="alert alert-info" role="alert">
					{{ request('error_message') }}
				</div>
				@endif

				@if(session('error_message'))
				<div class="alert alert-danger" role="alert">
					{{ session('error_message') }}
				</div>
				@endif

				@if(session('success_message'))
				<div class="alert alert-success" role="alert">
					{{ session('success_message') }}
				</div>
				@endif

                <div class="panel panel-default">
                    <div class="panel-heading">Simple PayPal payment</div>

                    <div class="panel-body">
                        <p>Just pay $5.00</p>

                        <form action="/purchase" method="post">
							{{ csrf_field() }}
							
                        	<button type="submit" class="btn btn-primary">Buy Now</button>	
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
