<div id="login" class="modal fade main-scope-to-close" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-5">
            <div class="modal-header">
                <h5 class="modal-title">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('login') }}" method="post" enctype="multipart/form-data" class="ajax-form-submit" autocomplete="off">
                @csrf
                @include('auth.show-message', ['extra_class' => 'mb-3'])
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control field_email" type="email" name="email" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input class="form-control field_password" type="password" name="password" autocomplete="new-password">
                    </div>
                    <div class="form-check form-checkbox-dark">
                        <input type="checkbox" class="form-check-input" id="checkbox-signin" name="remember" value="1">
                        <label class="form-check-label" for="checkbox-signin">Remember me</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-dark btn-ajax-show-processing" type="submit">
                        <i class="ri-login-circle-fill me-1"></i>
                        <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                        <span class="processing-show d-none">Logging In...</span>
                        <span class="default-show">Log In</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
