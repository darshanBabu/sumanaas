<!-- show.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <div class="container">
        <h1>Additional Authentication Required</h1>
        <p>Please complete the additional authentication step to proceed with the payment.</p>

        <div id="card-errors" class="alert alert-danger" role="alert"></div>

        <form id="additional-authentication-form">
            <div class="form-group">
                <label for="otp">One-Time Password (OTP)</label>
                <input type="text" id="otp" class="form-control" required>
            </div>
            
            <button id="authenticate-button" class="btn btn-primary">Authenticate</button>
        </form>
    </div>

<script>
    var stripe = Stripe('pk_test_51NE8YlSIS5dQ7W0Ttvyf9uYrWPAswJsG6mqxi3ZVIAuy8kzVKKfRXM83jDMiSEOY4fSGXXZ7kg2l1w3IPQMVoPfj00lF38Yqdf');
    var clientSecret = '{{ $clientSecret }}';

    var authenticateButton = document.getElementById('authenticate-button');
    var otpInput = document.getElementById('otp');
    var cardErrors = document.getElementById('card-errors');

    authenticateButton.addEventListener('click', function(event) {
        event.preventDefault();
        
        authenticateButton.disabled = true;

        stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                    type: 'card',
                    card: {
                        otp: otpInput.value
                    }
                }
        }).then(function(result) {
            if (result.error) {
                cardErrors.textContent = result.error.message;
                authenticateButton.disabled = false;
            } else {
                if (result.paymentIntent.status === 'succeeded') {
                    // Additional authentication succeeded and payment is confirmed
                    window.location.href = '/';
                } else {
                    // Handle other status as needed
                    console.log(result.paymentIntent.status);
                }
            }
        });
    });
</script>


</body>
</html>
