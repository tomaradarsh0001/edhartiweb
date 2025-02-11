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
                        @if (!empty($documents))
                        @foreach ($documents as $key => $docs)
                        @if(count($docs) > 0)
                        <tr>
                            <td colspan="5" class="address_data">{{ucfirst($key)}} Documents</td>
                        </tr>
                        @foreach($docs as $i => $doc)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $doc['title'] }}</td>
                            <td style="text-align:center;">
                                @if($doc['file_path'] )
                                <a href="{{ asset('storage/' . ($doc['file_path'] )) }}"
                                    target="_blank" class="text-danger view_docs"
                                    data-toggle="tooltip" title="View Uploaded Files">
                                    <i class="bx bxs-file-pdf"></i>
                                </a>
                                @endif
                            </td>
                            <td>
                                @if($doc['file_path'] )
                                <div class="form-check form-check-success">
                                    <input class="form-check-input property-document-approval-chk"
                                        type="checkbox" role="switch"
                                        @if ($checkList && $checkList->is_uploaded_doc_checked == 1) checked disabled @endif
                                    @if ($roles === 'deputy-lndo') disabled @endif>
                                    <label class="form-check-label">Checked</label>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>