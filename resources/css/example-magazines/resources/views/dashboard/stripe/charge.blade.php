@extends('dashboard.index')
@section('title', Helper::getSiteTitle('Magazines'))

@section('css-lib')
    
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Magazines</li>
                    </ol>
                </div>
                <h4 class="page-title">Add Magazines</h4>
            </div>
        </div>
    </div>
  
    <div class="bg-white p-2">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <form role="form" class="require-validation pay_form" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form" action="{{ route('charge', $id) }}" method="post">
        @csrf

    <div class="border p-3 mb-3 rounded credit-card-box">
        
        <div class="float-end">
            <i class="far fa-credit-card font-24 text-primary"></i>
        </div>
        <div class="form-check"> 
            <input type="radio" id="BillingOptRadio1" name="payment_method" value="stripe" class="form-check-input" checked>
            <label class="form-check-label font-16 fw-bold" for="BillingOptRadio1">Credit / Debit Card</label>
        </div>
        <p class="mb-0 ps-3 pt-1">Safe money transfer using your bank account. We support Mastercard, Visa, Discover and Stripe.</p>
        
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="mb-3 card required">
                    <label for="card-number" class="form-label">Card Number</label>
                    <input type="text" id="card_number" name="card_number" class="form-control" data-toggle="input-mask" data-mask-format="0000 0000 0000 0000" placeholder="4242 4242 4242 4242">
                    <span class="text-danger">
                        @error('card_number')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
        </div> <!-- end row -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 required">
                    <label for="card-name-on" class="form-label">Name on card</label>
                    <input type="text" id="card_name" name="card_name" class="form-control" placeholder="Master Shreyu">
                    <span class="text-danger">
                        @error('card_name')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3 expiration required">
                    <label for="card-expiry-date" class="form-label">Expiry Month</label>
                    <input type="text" id="card_expiry_month" name="card_expiry_month" class="form-control" data-toggle="input-mask" data-mask-format="00/00" placeholder="MM">
                    <span class="text-danger">
                        @error('card_expiry_month')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3 expiration required">
                    <label for="card-expiry-date" class="form-label">Expiry Year</label>
                    <input type="text" id="card_expiry_year" name="card_expiry_year" class="form-control" data-toggle="input-mask" data-mask-format="00/00" placeholder="YYYY">
                    <span class="text-danger">
                        @error('card_expiry_year')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3 cvc required">
                    <label for="card-cvv" class="form-label">CVV code</label>
                    <input type="text" id="card_cvv" name="card_cvv" class="form-control" data-toggle="input-mask" data-mask-format="000" placeholder="012">
                    <span class="text-danger">
                        @error('card_cvv')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>
            {{-- <div class='form-row row'>
                <div class='col-md-12 error form-group hide'>
                    <div class='alert-danger alert'>Please correct the errors and try
                        again.</div>
                </div>
            </div> --}}
        </div> 
        <div class="col-sm-6">
            <div class="text-sm-end mt-2 mt-sm-0">
                <button class="btn btn-success" type="submit">
                    <i class="mdi mdi-cash-multiple me-1"></i> Complete Order </button>
            </div>
        </div> 
        </form>
    </div>

@endsection
@section('js-lib')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    
<script type="text/javascript">
  
$(function() {
  
    /*------------------------------------------
    --------------------------------------------
    Stripe Payment Code
    --------------------------------------------
    --------------------------------------------*/
    
    var $form = $(".pay_form");
     
    $('form.require-validation').bind('submit', function(e) {
        // var $form = $(".require-validation"),
        // inputSelector = ['input[type=email]', 'input[type=password]',
        //                  'input[type=text]', 'input[type=file]',
        //                  'textarea'].join(', '),
        // $inputs = $form.find('.required').find(inputSelector),
        // $errorMessage = $form.find('div.error'),
        // valid = true;
        // $errorMessage.addClass('hide');
    
        // $('.has-error').removeClass('has-error');
        // $inputs.each(function(i, el) {
        //   var $input = $(el);
        //   if ($input.val() === '') {
        //     $input.parent().addClass('has-error');
        //     $errorMessage.removeClass('hide');
        //     e.preventDefault();
        //   }
        // });
     
        if (!$form.data('cc-on-file')) {
          e.preventDefault();
          Stripe.setPublishableKey($form.data('stripe-publishable-key'));
          Stripe.createToken({
            number: $('#card_number').val(),
            name: $('#card_name').val(),
            cvc: $('#card_cvv').val(),
            exp_month: $('#card_expiry_month').val(),
            exp_year: $('#card_expiry_year').val()
          }, stripeResponseHandler);
        }
    
    });
      
    /*------------------------------------------
    --------------------------------------------
    Stripe Response Handler
    --------------------------------------------
    --------------------------------------------*/
    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            /* token contains id, last4, and card type */
            var token = response['id'];
                 
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
     
});
</script>
@endsection
