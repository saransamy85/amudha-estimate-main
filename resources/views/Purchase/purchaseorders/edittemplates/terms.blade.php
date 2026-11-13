<div class="card mt-4">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <strong>Terms & Conditions</strong>
        <button type="button" class="btn btn-success btn-sm" id="addTerm">
            <i class="bi bi-plus-lg"></i> Add Term
        </button>
    </div>
    <div class="card-body">
        <div id="termsBody">
            @if($po->terms && count($po->terms) > 0)
                @foreach ($po->terms as $term)
                    <div class="row mb-2 termRow">
                        <div class="col-md-11">
                            <input type="hidden" name="term_id[]" value="{{ $term->id }}">
                            <input type="text" name="terms[]" class="form-control" value="{{ $term->term }}" placeholder="Enter Terms & Conditions">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger removeTerm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="row mb-2 termRow">
                    <div class="col-md-11">
                        <input type="hidden" name="term_id[]" value="">
                        <input type="text" name="terms[]" class="form-control" placeholder="Enter Terms & Conditions">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger removeTerm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).off('click.terms', '#addTerm').on('click.terms', '#addTerm', function() {
        let row = `
        <div class="row mb-2 termRow">
            <div class="col-md-11">
                <input type="hidden" name="term_id[]" value="">
                <input type="text" name="terms[]" class="form-control" placeholder="Enter Terms & Conditions">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger removeTerm">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
        $('#termsBody').append(row);
    });

    $(document).off('click.termsRem', '.removeTerm').on('click.termsRem', '.removeTerm', function() {
        if ($('#termsBody .termRow').length > 1) {
            $(this).closest('.termRow').remove();
        } else {
            $(this).closest('.termRow').find('input[type="text"]').val('');
            $(this).closest('.termRow').find('input[type="hidden"]').val('');
        }
    });
</script>
@endpush
