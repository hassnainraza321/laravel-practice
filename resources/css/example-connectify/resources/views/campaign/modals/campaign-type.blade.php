<div id="campaign-type-modal" class="modal fade main-scope-to-close modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Select Campaign Type</h3>
            </div>
            <div class="modal-body p-4">
                <div class="row campaign-type-card">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <h3>Broadcast Campaign</h3>
                                    <p>Select and filter among your exisitng audience & Broadcast customized Template or Regular messages.</p>
                                </div>
                                <a href="{{ route('campaigns.add') }}" class="btn btn-soft-dark float-end">
                                    Next <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <h3>API Campaign</h3>
                                    <p>Select a template and connect your exisiting systems with our API to automate <br>messages.</p>
                                </div>
                                <a href="{{ route('campaigns.api') }}" class="btn btn-soft-dark float-end">
                                    Next <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <h3>CSV Broadcast</h3>
                                    <p>Upload your audience from CSV & Broadcast customized Template or Regular messages simultaneously.</p>
                                </div>
                                <a href="{{ route('campaigns.csv') }}" class="btn btn-soft-dark float-end">
                                    Next <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <h3>Meta Ads</h3>
                                    <p>Target Click To WhatsApp Ads to your Facebook and Instagram audience to generate leads for retargeting.</p>
                                </div>
                                <a href="#" class="btn btn-soft-dark float-end">
                                    Next <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>