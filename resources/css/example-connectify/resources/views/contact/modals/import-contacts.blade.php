<div class="modal fade" id="contacts-import" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Import contacts by spreadsheet</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('contacts.import') }}" method="post" class="ajax-form-submit" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="mb-1">
                            <div class="alert alert-danger mb-2" role="alert">
                                Uploaded sheet must be in the same format and same heading columns as in the sample file.
                            </div>
                            @php $index = 'file'; @endphp
                            <input type="file" id="{{ $index }}" name="{{ $index }}" class="form-control {{ $index }}">
                            <p class="input-definition-text mt-1 mb-0">Upload excel sheet only (required XLSX or CSV)</p>
                        </div>
                        <div class="mt-1 file-uploader d-none">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-inline-block w-100">
                    <a href="{{ asset('files/contacts.xlsx') }}" class="download-sample-file text-success d-inline-block" download="">Download sample file</a>
                    <button class="btn btn-custom btn-ajax-show-processing float-right" type="submit">
                        <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
                        <span class="processing-show d-none">Uploading...</span>
                        <span class="default-show">Upload</span>
                    </button>
                    <a href="javascript:void(0);" class="btn btn-light float-right" data-bs-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>