<div id="register-{{ isset($user) && !empty($user) ? 'edit' : 'add' }}" class="modal fade main-scope-to-close" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-5">
            <div class="modal-header">
                <h5 class="modal-title">Register</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ isset($user) && !empty($user) ? route('user.get', $user->id) : route('register') }}" method="post" enctype="multipart/form-data" class="ajax-form-submit" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input class="form-control field_name" type="text" name="name" value="{{ isset($user) && !empty($user) ? $user->name : '' }}" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control field_email" type="email" name="email" value="{{ isset($user) && !empty($user) ? $user->email : '' }}" autocomplete="off">
                    </div>
                    @if (!isset($user))
                        <div>
                            <label class="form-label">Password</label>
                            <input class="form-control field_password" type="password" name="password" autocomplete="new-password">
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-dark btn-ajax-show-processing" type="submit">
                        <i class="ri-login-circle-fill me-1"></i>
                        <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                        <span class="processing-show d-none">Saving...</span>
                        <span class="default-show">Save</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
