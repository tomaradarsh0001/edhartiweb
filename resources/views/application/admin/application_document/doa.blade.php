<div class="part-title mt-2">
    <h5>Property Document Details</h5>
</div>
<div class="part-details">
    <div class="container-fluid">
        <div class="row g-2">
            <div class="col-lg-12">
                <table class="table table-bordered property-table-info">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Document Name</th>
                            <th style="text-align:center;">View Docs</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="address_data">Required Documents</td>
                        </tr>
                        @if (!empty($documents['required']))
                            @foreach ($documents['required'] as $key => $document)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $document['title'] }}</td>
                                    <td style="text-align:center;">
                                        <a href="{{ asset('storage/' . ($document['file_path'] ?? '')) }}"
                                            target="_blank" class="text-danger view_docs"
                                            data-toggle="tooltip" title="View Uploaded Files">
                                            <i class="bx bxs-file-pdf"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-success">
                                            <input class="form-check-input property-document-approval-chk"
                                                type="checkbox" role="switch"
                                                @if ($checkList && $checkList->is_uploaded_doc_checked == 1) checked disabled @endif
                                                @if ($roles === 'deputy-lndo') disabled @endif>
                                            <label class="form-check-label">Checked</label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>