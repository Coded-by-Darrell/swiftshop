@if($orders->hasPages())
    <div class="simple-pagination">
        <div class="pagination-info">
            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
        </div>
        
        <div class="pagination-nav">
            @if($orders->onFirstPage())
                <span class="btn btn-outline-secondary disabled">Previous</span>
            @else
                <a href="{{ $orders->previousPageUrl() }}" class="btn btn-outline-primary">Previous</a>
            @endif
            
            <span class="btn btn-primary">{{ $orders->currentPage() }}</span>
            
            @if($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}" class="btn btn-outline-primary">Next</a>
            @else
                <span class="btn btn-outline-secondary disabled">Next</span>
            @endif
        </div>
    </div>
@endif