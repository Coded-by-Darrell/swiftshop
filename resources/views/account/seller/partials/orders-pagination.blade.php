@if($orders->hasPages())
<div class="d-flex justify-content-center mt-4">
    <nav>
        <ul class="pagination">
            @if($orders->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
            @else
            <li class="page-item">
                <button class="page-link pagination-btn" data-page="{{ $orders->currentPage() - 1 }}">Previous</button>
            </li>
            @endif
            
            <li class="page-item active">
                <span class="page-link">{{ $orders->currentPage() }}</span>
            </li>
            
            @if($orders->hasMorePages())
            <li class="page-item">
                <button class="page-link pagination-btn" data-page="{{ $orders->currentPage() + 1 }}">Next</button>
            </li>
            @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
            @endif
        </ul>
    </nav>
</div>

<div class="text-center text-muted mt-2">
    <small>
        Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
    </small>
</div>
@endif