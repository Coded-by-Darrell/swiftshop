@if($products->hasPages())
<div class="products-pagination">
    <div class="pagination-info">
        <span class="pagination-text">
            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
        </span>
    </div>
    
    <div class="pagination-controls">
        @if($products->onFirstPage())
        <button class="pagination-btn disabled" disabled>
            <i class="fas fa-chevron-left"></i> Previous
        </button>
        @else
        <button class="pagination-btn" data-page="{{ $products->currentPage() - 1 }}">
            <i class="fas fa-chevron-left"></i> Previous
        </button>
        @endif
        
        @if($products->hasMorePages())
        <button class="pagination-btn" data-page="{{ $products->currentPage() + 1 }}">
            Next <i class="fas fa-chevron-right"></i>
        </button>
        @else
        <button class="pagination-btn disabled" disabled>
            Next <i class="fas fa-chevron-right"></i>
        </button>
        @endif
    </div>
</div>
@endif