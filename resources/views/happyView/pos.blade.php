@include('happyView.includes.head')

<style>
/* Modern Visual POS Styles */
:root {
    --pos-primary: #0A5F59;
    --pos-primary-hover: #084c47;
    --pos-accent: #FDCF6F;
    --pos-bg: #f8fafc;
    --pos-card-bg: #ffffff;
    --pos-border: #e2e8f0;
    --pos-text: #0f172a;
    --pos-muted: #64748b;
}

body {
    background-color: var(--pos-bg);
}

.pos-container {
    padding: 18px 24px 80px;
}

/* Header bar */
.pos-header {
    background: #ffffff;
    border: 1px solid var(--pos-border);
    border-radius: 12px;
    padding: 12px 20px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}

.pos-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}

.pos-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--pos-primary);
    margin: 0;
    letter-spacing: -0.3px;
}

.pos-live-clock {
    font-size: 13px;
    font-weight: 600;
    color: var(--pos-muted);
    background: #f1f5f9;
    padding: 4px 10px;
    border-radius: 20px;
}

/* Category chips */
.pos-categories-wrapper {
    margin-bottom: 16px;
    overflow-x: auto;
    white-space: nowrap;
    padding-bottom: 6px;
    -webkit-overflow-scrolling: touch;
}

.pos-categories-wrapper::-webkit-scrollbar {
    height: 5px;
}
.pos-categories-wrapper::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.pos-category-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 30px;
    border: 1.5px solid var(--pos-border);
    background: #ffffff;
    color: #334155;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
    margin-right: 8px;
}

.pos-category-chip:hover {
    border-color: var(--pos-primary);
    color: var(--pos-primary);
    transform: translateY(-1px);
}

.pos-category-chip.active {
    background: var(--pos-primary);
    border-color: var(--pos-primary);
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(10, 95, 89, 0.25);
}

.pos-category-chip .chip-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
}

.pos-category-chip .chip-count {
    background: rgba(0,0,0,0.06);
    padding: 2px 7px;
    border-radius: 12px;
    font-size: 11px;
}
.pos-category-chip.active .chip-count {
    background: rgba(255,255,255,0.25);
    color: #ffffff;
}

/* Search bar */
.pos-search-wrapper {
    position: relative;
    margin-bottom: 18px;
}

.pos-search-input {
    width: 100%;
    padding: 12px 42px 12px 42px;
    border-radius: 10px;
    border: 1.5px solid var(--pos-border);
    background: #ffffff;
    font-size: 14px;
    color: var(--pos-text);
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.pos-search-input:focus {
    outline: none;
    border-color: var(--pos-primary);
    box-shadow: 0 0 0 3px rgba(10, 95, 89, 0.12);
}

.pos-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
}

.pos-search-clear {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 16px;
    cursor: pointer;
    display: none;
}

/* Product Grid */
.pos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(185px, 1fr));
    gap: 16px;
}

.pos-card {
    background: #ffffff;
    border: 1.5px solid var(--pos-border);
    border-radius: 14px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: flex;
    flex-direction: column;
    user-select: none;
}

.pos-card:hover {
    border-color: var(--pos-primary);
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(10, 95, 89, 0.08);
}

.pos-card:active {
    transform: scale(0.97);
}

.pos-card-img-wrap {
    width: 100%;
    padding-top: 75%; /* 4:3 Aspect Ratio */
    position: relative;
    background: #f1f5f9;
    overflow: hidden;
}

.pos-card-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.2s ease;
}

.pos-card:hover .pos-card-img {
    transform: scale(1.04);
}

.pos-card-body {
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.pos-card-category {
    font-size: 11px;
    font-weight: 600;
    color: var(--pos-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.pos-card-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--pos-text);
    margin: 0 0 8px 0;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.pos-card-footer {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    margin-top: auto;
}

.pos-card-price {
    font-size: 16px;
    font-weight: 800;
    color: var(--pos-primary);
}

.pos-card-tax {
    font-size: 11px;
    font-weight: 600;
    color: #475569;
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 6px;
}

/* In-cart badge on card */
.pos-card-cart-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: var(--pos-primary);
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    display: none;
    z-index: 2;
}

/* Play pass card highlight */
.pos-card.play-pass-card {
    border-color: #fde68a;
    background: linear-gradient(180deg, #fffbeb 0%, #ffffff 40%);
}
.pos-card.play-pass-card:hover {
    border-color: #f59e0b;
}

/* Sticky Cart Panel (Right) */
.pos-cart-panel {
    background: #ffffff;
    border: 1.5px solid var(--pos-border);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    position: sticky;
    top: 20px;
    max-height: calc(100vh - 40px);
    display: flex;
    flex-direction: column;
}

.pos-cart-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 12px;
    border-bottom: 1.5px solid var(--pos-border);
    margin-bottom: 14px;
}

.pos-cart-title {
    font-size: 17px;
    font-weight: 700;
    color: var(--pos-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.pos-cart-clear-btn {
    background: none;
    border: none;
    color: #ef4444;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 6px;
}
.pos-cart-clear-btn:hover {
    background: #fef2f2;
}

/* Customer inputs in cart */
.pos-customer-box {
    background: #f8fafc;
    border: 1px solid var(--pos-border);
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 14px;
}

.pos-customer-box .form-label {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--pos-muted);
    margin-bottom: 4px;
}

.pos-customer-box .form-control,
.pos-customer-box .form-select {
    font-size: 13px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    padding: 6px 10px;
}

/* Phone autocomplete list */
.pos-phone-list-drop {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #ffffff;
    border: 1px solid var(--pos-border);
    border-radius: 8px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    max-height: 180px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}
.pos-phone-list-drop a {
    display: block;
    padding: 8px 12px;
    color: var(--pos-text);
    text-decoration: none;
    border-bottom: 1px solid #f1f5f9;
    font-size: 12.5px;
}
.pos-phone-list-drop a:hover {
    background: #f1f5f9;
    color: var(--pos-primary);
}

/* Cart Items Scroll Container */
.pos-cart-items {
    flex-grow: 1;
    overflow-y: auto;
    padding-right: 4px;
    margin-bottom: 14px;
    min-height: 120px;
    max-height: 280px;
}

.pos-cart-items::-webkit-scrollbar {
    width: 4px;
}
.pos-cart-items::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.pos-cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
    gap: 10px;
}

.pos-cart-item-details {
    flex-grow: 1;
    min-width: 0;
}

.pos-cart-item-name {
    font-size: 13px;
    font-weight: 700;
    color: var(--pos-text);
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.pos-cart-item-sub {
    font-size: 11px;
    color: var(--pos-muted);
}

.pos-stepper {
    display: flex;
    align-items: center;
    border: 1px solid var(--pos-border);
    border-radius: 6px;
    background: #ffffff;
    overflow: hidden;
}

.pos-stepper-btn {
    background: none;
    border: none;
    padding: 4px 9px;
    font-size: 14px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    transition: background 0.1s;
}
.pos-stepper-btn:hover {
    background: #f1f5f9;
    color: var(--pos-primary);
}

.pos-stepper-val {
    padding: 2px 8px;
    font-size: 13px;
    font-weight: 700;
    color: var(--pos-text);
    min-width: 24px;
    text-align: center;
}

.pos-cart-item-price {
    font-size: 14px;
    font-weight: 800;
    color: var(--pos-text);
    min-width: 65px;
    text-align: right;
}

.pos-cart-item-del {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 4px;
    font-size: 13px;
}
.pos-cart-item-del:hover {
    color: #ef4444;
}

/* Empty cart state */
.pos-empty-cart {
    text-align: center;
    padding: 30px 10px;
    color: #94a3b8;
}
.pos-empty-cart i {
    font-size: 40px;
    margin-bottom: 10px;
    color: #cbd5e1;
}

/* Financial breakdown */
.pos-summary-table {
    width: 100%;
    margin-bottom: 12px;
    font-size: 13px;
}
.pos-summary-table td {
    padding: 3px 0;
}
.pos-summary-table td:last-child {
    text-align: right;
    font-weight: 600;
}
.pos-summary-total {
    font-size: 18px;
    font-weight: 800;
    color: var(--pos-primary);
    border-top: 1.5px dashed var(--pos-border);
    padding-top: 8px !important;
}

/* Payment Mode buttons */
.pos-payment-modes {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 8px;
    margin-bottom: 14px;
}

.pos-pay-btn {
    border: 1.5px solid var(--pos-border);
    background: #ffffff;
    border-radius: 8px;
    padding: 8px 4px;
    text-align: center;
    font-size: 12.5px;
    font-weight: 700;
    color: #334155;
    cursor: pointer;
    transition: all 0.15s;
    user-select: none;
}

.pos-pay-btn:hover {
    border-color: var(--pos-primary);
}

.pos-pay-btn.active {
    background: #ecfdf5;
    border-color: #10b981;
    color: #065f46;
}

/* Big Checkout Button */
.pos-charge-btn {
    width: 100%;
    background: var(--pos-primary);
    color: #ffffff;
    border: none;
    border-radius: 10px;
    padding: 14px;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: background 0.15s, transform 0.1s;
    box-shadow: 0 4px 12px rgba(10, 95, 89, 0.25);
}

.pos-charge-btn:hover {
    background: var(--pos-primary-hover);
}

.pos-charge-btn:disabled {
    background: #cbd5e1;
    cursor: not-allowed;
    box-shadow: none;
}

/* Coupon input */
.pos-coupon-wrap {
    display: flex;
    gap: 6px;
    margin-bottom: 12px;
}
.pos-coupon-wrap input {
    font-size: 12.5px;
    border-radius: 6px;
}

/* Mobile Bottom Sheet & Bar */
.pos-mobile-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #ffffff;
    border-top: 1.5px solid var(--pos-border);
    padding: 12px 18px;
    display: none;
    align-items: center;
    justify-content: space-between;
    z-index: 999;
    box-shadow: 0 -4px 16px rgba(0,0,0,0.06);
}

/* Responsive adjustments */
@media (max-width: 991px) {
    .pos-cart-panel {
        display: none;
    }
    .pos-mobile-bar {
        display: flex;
    }
    /* When cart modal is open on mobile */
    .pos-mobile-cart-modal .pos-cart-panel {
        display: flex;
        position: static;
        max-height: 80vh;
    }
}

@media (min-width: 992px) and (max-width: 1200px) {
    .pos-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    }
}
</style>

<body>
    <!--wrapper-->
    <div class="wrapper">
        @include('happyView.includes.sidebar')

        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </div>
                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item">
                                <a href="{{ url('/tickets') }}" class="btn btn-sm btn-outline-secondary" style="font-size:12px;font-weight:600;border-radius:8px;">
                                    <i class="fa fa-exchange me-1"></i> Classic View
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!--end header -->

        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content pos-container">

                <!-- POS Header Bar -->
                <div class="pos-header">
                    <div class="pos-title-wrap">
                        <h1 class="pos-title"><i class="fa fa-th-large text-warning me-2"></i>Visual POS Billing</h1>
                        <span class="pos-live-clock" id="posClock"></span>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        @if (auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                            <form method="GET" action="{{ route('pos') }}" class="d-inline-flex align-items-center gap-2">
                                <label for="posBranchSelector" class="mb-0 text-muted" style="font-size:13px;font-weight:600;">Branch:</label>
                                <select name="branch_id" id="posBranchSelector" class="form-select form-select-sm" onchange="this.form.submit()" style="font-size:13px;border-radius:8px;font-weight:600;">
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch['id'] }}" {{ $branch['id'] == $branchId ? 'selected' : '' }}>
                                            {{ $branch['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="row g-3">
                    <!-- Left: Catalog & Product Grid (col-lg-8) -->
                    <div class="col-lg-8 col-md-12">

                        <!-- Search Box -->
                        <div class="pos-search-wrapper">
                            <i class="fa fa-search pos-search-icon"></i>
                            <input type="text" id="posSearchInput" class="pos-search-input" placeholder="Search product by name or SKU... (Press '/' to search, barcode friendly)" autocomplete="off">
                            <button type="button" id="posSearchClear" class="pos-search-clear">&times;</button>
                        </div>

                        <!-- Category Chips Bar -->
                        <div class="pos-categories-wrapper" id="posCategoriesBar">
                            <div class="pos-category-chip active" data-category-id="all">
                                <span>🏷️ All Items</span>
                                <span class="chip-count">{{ count($products) + count($dayPrices) }}</span>
                            </div>

                            <div class="pos-category-chip" data-category-id="passes">
                                <span class="chip-dot" style="background:#f59e0b;"></span>
                                <span>🎟️ Play Passes</span>
                                <span class="chip-count">{{ count($dayPrices) }}</span>
                            </div>

                            @foreach ($categories as $cat)
                                <div class="pos-category-chip" data-category-id="{{ $cat->id }}">
                                    <span class="chip-dot" style="background: {{ $cat->colour }};"></span>
                                    <i class="{{ $cat->icon ?? 'fa fa-tags' }}" style="font-size:12px;"></i>
                                    <span>{{ $cat->name }}</span>
                                    <span class="chip-count">{{ $cat->products->where('is_active', true)->count() }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Product Cards Grid -->
                        <div class="pos-grid" id="posProductGrid">
                            <!-- Play Pass Cards (Timed Walk-ins) -->
                            @foreach ($dayPrices as $dp)
                                <div class="pos-card play-pass-card"
                                     data-is-pass="true"
                                     data-pass-hours="{{ $dp['hour'] }}"
                                     data-pass-price="{{ $dp['price'] }}"
                                     data-pass-tax="18"
                                     data-category="passes"
                                     data-search-terms="play pass {{ $dp['hour'] }} hour unlimited play admission ticket">
                                    <span class="pos-card-cart-badge" id="badge-pass-{{ Str::slug($dp['hour']) }}"></span>
                                    <div class="pos-card-img-wrap" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); display:flex; align-items:center; justify-content:center;">
                                        <div style="font-size:38px;text-align:center;line-height:1;margin-top:20%;">🎟️</div>
                                    </div>
                                    <div class="pos-card-body">
                                        <div class="pos-card-category" style="color:#d97706;">Play Entry</div>
                                        <h3 class="pos-card-title">{{ $dp['hour'] }} {{ $dp['hour'] == 'Unlimited' ? 'Play Pass' : 'Hour Play' }}</h3>
                                        <div class="pos-card-footer">
                                            <span class="pos-card-price">₹{{ number_format($dp['price'], 0) }}</span>
                                            <span class="pos-card-tax" style="background:#fef3c7;color:#92400e;">18% GST</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Retail Products -->
                            @foreach ($products as $p)
                                <div class="pos-card"
                                     data-is-pass="false"
                                     data-product-id="{{ $p->id }}"
                                     data-product-name="{{ $p->product_name }}"
                                     data-product-price="{{ $p->product_price }}"
                                     data-product-tax="{{ $p->product_tax }}"
                                     data-category="{{ $p->category_id ?? 'uncategorized' }}"
                                     data-sku="{{ $p->sku }}"
                                     data-search-terms="{{ strtolower($p->product_name . ' ' . $p->sku . ' ' . optional($p->category)->name) }}">
                                    <span class="pos-card-cart-badge" id="badge-prod-{{ $p->id }}"></span>
                                    <div class="pos-card-img-wrap">
                                        <img src="{{ $p->image_url }}" alt="{{ $p->product_name }}" class="pos-card-img" loading="lazy">
                                    </div>
                                    <div class="pos-card-body">
                                        <div class="pos-card-category">{{ optional($p->category)->name ?? 'General' }}</div>
                                        <h3 class="pos-card-title">{{ $p->product_name }}</h3>
                                        <div class="pos-card-footer">
                                            <span class="pos-card-price">₹{{ number_format($p->product_price, 0) }}</span>
                                            <span class="pos-card-tax">{{ $p->product_tax }}% GST</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- No results message -->
                        <div id="posNoResults" style="display:none; text-align:center; padding: 40px 10px; color:#94a3b8;">
                            <i class="fa fa-search mb-2" style="font-size:32px;"></i>
                            <p style="font-size:15px; font-weight:600;">No products match your search</p>
                        </div>

                    </div>

                    <!-- Right: Sticky Cart Panel (col-lg-4) -->
                    <div class="col-lg-4 col-md-12">
                        <div class="pos-cart-panel" id="posCartPanel">
                            <div class="pos-cart-header">
                                <h2 class="pos-cart-title">
                                    <i class="fa fa-shopping-basket text-success"></i> Current Order
                                </h2>
                                <button type="button" class="pos-cart-clear-btn" id="posClearCartBtn">
                                    <i class="fa fa-trash-o me-1"></i> Clear
                                </button>
                            </div>

                            <!-- Customer Lookup & Info -->
                            <div class="pos-customer-box">
                                <div class="position-relative mb-2">
                                    <label class="form-label" for="posPhone">Customer Phone <span class="text-danger">*</span></label>
                                    <input type="tel" id="posPhone" class="form-control" placeholder="10-digit mobile number" maxlength="10" autocomplete="off">
                                    <div id="posPhoneLookupDrop" class="pos-phone-list-drop"></div>
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label" for="posGuardian">Guardian Name <span class="text-danger">*</span></label>
                                        <input type="text" id="posGuardian" class="form-control" placeholder="Parent name">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="posDob">Kid DOB <span class="text-danger">*</span></label>
                                        <input type="date" id="posDob" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <!-- Kids Play Section (Collapsible / Dynamic) -->
                                <div id="posKidsContainer" style="border-top: 1px dashed #cbd5e1; padding-top: 8px; margin-top: 6px;">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span style="font-size:11.5px;font-weight:700;color:var(--pos-primary);text-transform:uppercase;">
                                            <i class="fa fa-child me-1"></i> Kids Play Entry (<span id="posKidsCount">0</span>)
                                        </span>
                                        <button type="button" id="posAddKidBtn" class="btn btn-xs btn-outline-success" style="font-size:11px;padding:2px 8px;border-radius:4px;">
                                            + Add Kid
                                        </button>
                                    </div>
                                    <div id="posKidsList"></div>
                                </div>
                            </div>

                            <!-- Cart Items List -->
                            <div class="pos-cart-items" id="posCartItemsList">
                                <div class="pos-empty-cart" id="posEmptyCartNotice">
                                    <i class="fa fa-shopping-cart"></i>
                                    <p class="mb-0" style="font-size:13px;font-weight:600;">Your cart is empty</p>
                                    <span style="font-size:11.5px;">Tap items on the left to add them</span>
                                </div>
                            </div>

                            <!-- Coupon Box -->
                            <div class="pos-coupon-wrap">
                                <input type="text" id="posCouponInput" class="form-control" placeholder="Discount coupon code" autocomplete="off">
                                <button type="button" id="posApplyCouponBtn" class="btn btn-sm btn-outline-primary" style="font-weight:700;border-radius:6px;">Apply</button>
                            </div>
                            <div id="posCouponBadge" style="display:none; font-size:12px; margin-bottom:8px; display:none; align-items:center; justify-content:space-between; background:#ecfdf5; color:#065f46; padding:4px 8px; border-radius:6px;">
                                <span><i class="fa fa-check-circle me-1"></i> Coupon <strong id="posCouponNameText"></strong> applied</span>
                                <button type="button" id="posRemoveCouponBtn" style="background:none;border:none;color:#ef4444;font-weight:700;cursor:pointer;">&times;</button>
                            </div>

                            <!-- Financial Summary Table -->
                            <table class="pos-summary-table">
                                <tr>
                                    <td class="text-muted">Subtotal (Excl. Tax)</td>
                                    <td id="posSummarySubtotal">₹0.00</td>
                                </tr>
                                <tr id="posSummaryDiscountRow" style="display:none; color:#10b981;">
                                    <td>Coupon Discount</td>
                                    <td id="posSummaryDiscount">-₹0.00</td>
                                </tr>
                                <tr id="posSummaryGst18Row">
                                    <td class="text-muted">GST @ 18% (CGST 9% + SGST 9%)</td>
                                    <td id="posSummaryGst18">₹0.00</td>
                                </tr>
                                <tr id="posSummaryGst12Row" style="display:none;">
                                    <td class="text-muted">GST @ 12% (CGST 6% + SGST 6%)</td>
                                    <td id="posSummaryGst12">₹0.00</td>
                                </tr>
                                <tr id="posSummaryGst5Row" style="display:none;">
                                    <td class="text-muted">GST @ 5% (CGST 2.5% + SGST 2.5%)</td>
                                    <td id="posSummaryGst5">₹0.00</td>
                                </tr>
                                <tr>
                                    <td class="pos-summary-total">Total Amount</td>
                                    <td class="pos-summary-total" id="posSummaryGrandTotal">₹0.00</td>
                                </tr>
                            </table>

                            <!-- Payment Mode Toggle -->
                            <div class="pos-payment-modes">
                                <div class="pos-pay-btn active" data-mode="2">
                                    <div>💵 Cash</div>
                                </div>
                                <div class="pos-pay-btn" data-mode="1">
                                    <div>📱 UPI</div>
                                </div>
                                <div class="pos-pay-btn" data-mode="3">
                                    <div>💳 Card</div>
                                </div>
                            </div>

                            <!-- Charge Button -->
                            <button type="button" id="posChargeBtn" class="pos-charge-btn" disabled>
                                <span>Charge</span>
                                <span id="posChargeBtnAmount">₹0.00</span>
                                <i class="fa fa-arrow-right"></i>
                            </button>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->

        <!-- Mobile Floating Bottom Bar -->
        <div class="pos-mobile-bar" id="posMobileBar">
            <div>
                <div style="font-size:11px; color:#64748b; font-weight:600;"><span id="posMobileItemCount">0</span> items in cart</div>
                <div style="font-size:18px; font-weight:800; color:var(--pos-primary);" id="posMobileTotal">₹0.00</div>
            </div>
            <button type="button" class="btn btn-primary" id="posMobileOpenCartBtn" style="font-weight:700; border-radius:8px; padding:10px 18px; background:var(--pos-primary); border-color:var(--pos-primary);">
                Review & Charge <i class="fa fa-chevron-up ms-1"></i>
            </button>
        </div>

        <!-- Mobile Cart Modal / Sheet -->
        <div class="modal fade" id="posMobileCartModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                    <div class="modal-header" style="background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                        <h5 class="modal-title" style="font-weight:700;"><i class="fa fa-shopping-basket text-success me-2"></i>Checkout & Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0" id="posMobileModalBody">
                        <!-- Cloned cart panel on mobile injected here if needed -->
                    </div>
                </div>
            </div>
        </div>

        <!-- HIDDEN FORM THAT FEEDS /add-tickets TO PRESERVE EXISTING WORKFLOW -->
        <form id="posCheckoutForm" action="{{ url('/add-tickets') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="branch_id" id="formBranchId" value="{{ $branchId }}">
            <input type="hidden" name="date" id="formDate" value="{{ date('Y-m-d') }}">
            <input type="hidden" name="time" id="formTime" value="{{ date('H:i') }}">
            <input type="hidden" name="phone" id="formPhone">
            <input type="hidden" name="name_of_kid" id="formGuardian">
            <input type="hidden" name="dob" id="formDob">
            <input type="hidden" name="kids" id="formKidsQty" value="0">
            <input type="hidden" name="payment_mode" id="formPaymentMode" value="2">
            <input type="hidden" name="couponcode" id="formCouponCode">
            <input type="hidden" name="couponvalue" id="formCouponValue" value="0">
            <input type="hidden" name="subTotal" id="formSubtotal" value="0">
            <input type="hidden" name="totalAmount" id="formTotalAmount" value="0">
            <input type="hidden" name="gst18" id="formGst18" value="0">
            <input type="hidden" name="gst12" id="formGst12" value="0">
            <input type="hidden" name="gst5" id="formGst5" value="0">
            <input type="hidden" name="ba18" id="formBa18" value="0">
            <input type="hidden" name="ba12" id="formBa12" value="0">
            <input type="hidden" name="ba5" id="formBa5" value="0">
            <input type="hidden" name="kidsOnlyPrice" id="formKidsOnlyPrice" value="0">
            <input type="hidden" name="kidsPriceNoDis" id="formKidsPriceNoDis" value="0">
            <div id="formDynamicKidsInputs"></div>
            <div id="formDynamicProductsInputs"></div>
        </form>

    </div>
    <!--end wrapper-->

    <!-- Bootstrap JS + jQuery -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>

    <script>
    (function($) {
        'use strict';

        var baseUrl = "{{ url('/') }}";
        var currentBranchId = "{{ $branchId }}";

        // State
        var cart = {
            products: {}, // key: id, { id, name, price, tax, qty }
            kids: [],     // array of { name, hours, price }
            coupon: null, // { code, discount, discount_type }
            paymentMode: 2 // 2: Cash, 1: UPI, 3: Card
        };

        // Day prices lookup for hours
        var dayPriceMap = {};
        @foreach ($dayPrices as $dp)
            dayPriceMap["{{ $dp['hour'] }}"] = parseFloat("{{ $dp['price'] }}") || 0;
        @endforeach

        // 1. Live Clock
        function updateClock() {
            var now = new Date();
            var options = { weekday: 'short', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            $('#posClock').text(now.toLocaleDateString('en-IN', options));
        }
        setInterval(updateClock, 1000);
        updateClock();

        // 2. Keyboard shortcut: Press '/' to focus search
        $(document).on('keydown', function(e) {
            if (e.key === '/' && !$(e.target).is('input, textarea, select')) {
                e.preventDefault();
                $('#posSearchInput').focus().select();
            }
        });

        // 3. Search & Filter
        $('#posSearchInput').on('input', function() {
            var q = $(this).val().toLowerCase().trim();
            if (q.length > 0) {
                $('#posSearchClear').show();
            } else {
                $('#posSearchClear').hide();
            }
            filterGrid();
        });

        $('#posSearchClear').on('click', function() {
            $('#posSearchInput').val('').trigger('input').focus();
        });

        // Category chip click
        $('.pos-category-chip').on('click', function() {
            $('.pos-category-chip').removeClass('active');
            $(this).addClass('active');
            filterGrid();
        });

        function filterGrid() {
            var q = $('#posSearchInput').val().toLowerCase().trim();
            var cat = $('.pos-category-chip.active').data('category-id');
            var visibleCount = 0;

            $('#posProductGrid .pos-card').each(function() {
                var card = $(this);
                var cardCat = card.data('category');
                var searchTerms = (card.data('search-terms') || '').toString().toLowerCase();
                var sku = (card.data('sku') || '').toString().toLowerCase();

                var matchCat = (cat === 'all') ||
                               (cat === 'passes' && card.data('is-pass') === true) ||
                               (String(cardCat) === String(cat));

                var matchSearch = (q === '') || (searchTerms.indexOf(q) !== -1) || (sku === q);

                if (matchCat && matchSearch) {
                    card.show();
                    visibleCount++;
                } else {
                    card.hide();
                }
            });

            if (visibleCount === 0) {
                $('#posNoResults').show();
            } else {
                $('#posNoResults').hide();
            }
        }

        // Barcode scanning: if exact barcode / SKU scanned and Enter pressed
        $('#posSearchInput').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                var q = $(this).val().trim();
                var matched = null;
                $('#posProductGrid .pos-card').each(function() {
                    if ($(this).data('sku') && String($(this).data('sku')).toLowerCase() === q.toLowerCase()) {
                        matched = $(this);
                        return false;
                    }
                });
                if (matched) {
                    matched.trigger('click');
                    toastr.success('Added ' + matched.data('product-name') + ' to cart');
                    $(this).val('').trigger('input');
                }
            }
        });

        // 4. Tap Product Card -> Add to Cart
        $('#posProductGrid').on('click', '.pos-card', function() {
            var card = $(this);
            var isPass = card.data('is-pass');

            if (isPass) {
                var hour = card.data('pass-hours');
                var price = parseFloat(card.data('pass-price')) || 0;
                addKidPass(hour, price);
            } else {
                var id = card.data('product-id');
                var name = card.data('product-name');
                var price = parseFloat(card.data('product-price')) || 0;
                var tax = parseInt(card.data('product-tax'), 10) || 0;

                if (!cart.products[id]) {
                    cart.products[id] = { id: id, name: name, price: price, tax: tax, qty: 1 };
                } else {
                    cart.products[id].qty += 1;
                }
            }

            renderCart();
        });

        // 5. Play Passes Logic
        function addKidPass(defaultHours, defaultPrice) {
            var kidIndex = cart.kids.length + 1;
            var hours = defaultHours || '1';
            var price = defaultPrice !== undefined ? defaultPrice : (dayPriceMap[hours] || 0);

            cart.kids.push({
                name: 'Kid ' + kidIndex,
                hours: hours,
                price: price
            });
            renderKidsList();
            renderCart();
        }

        $('#posAddKidBtn').on('click', function() {
            addKidPass('1');
        });

        function renderKidsList() {
            var $list = $('#posKidsList');
            $list.empty();
            $('#posKidsCount').text(cart.kids.length);

            cart.kids.forEach(function(kid, idx) {
                var html = `
                    <div class="d-flex align-items-center gap-2 mb-2 p-2" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:6px;">
                        <input type="text" class="form-control form-control-sm kid-name-input" data-idx="${idx}" value="${kid.name}" placeholder="Kid name" style="font-size:12px;">
                        <select class="form-select form-select-sm kid-hours-select" data-idx="${idx}" style="font-size:12px;width:110px;">
                            <option value="1" ${kid.hours == '1' ? 'selected' : ''}>1 Hour</option>
                            <option value="2" ${kid.hours == '2' ? 'selected' : ''}>2 Hours</option>
                            <option value="Unlimited" ${kid.hours == 'Unlimited' ? 'selected' : ''}>Unlimited</option>
                        </select>
                        <span style="font-size:12.5px;font-weight:700;color:var(--pos-primary);min-width:60px;text-align:right;">₹${kid.price.toFixed(0)}</span>
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 kid-remove-btn" data-idx="${idx}" style="font-size:14px;">&times;</button>
                    </div>
                `;
                $list.append(html);
            });
        }

        $(document).on('input', '.kid-name-input', function() {
            var idx = $(this).data('idx');
            cart.kids[idx].name = $(this).val();
        });

        $(document).on('change', '.kid-hours-select', function() {
            var idx = $(this).data('idx');
            var hrs = $(this).val();
            cart.kids[idx].hours = hrs;
            var price = dayPriceMap[hrs] || 0;
            cart.kids[idx].price = price;
            renderKidsList();
            renderCart();
        });

        $(document).on('click', '.kid-remove-btn', function() {
            var idx = $(this).data('idx');
            cart.kids.splice(idx, 1);
            renderKidsList();
            renderCart();
        });

        // 6. Stepper: + / - / remove products in cart
        $('#posCartItemsList').on('click', '.pos-stepper-plus', function() {
            var id = $(this).data('id');
            if (cart.products[id]) {
                cart.products[id].qty += 1;
                renderCart();
            }
        });

        $('#posCartItemsList').on('click', '.pos-stepper-minus', function() {
            var id = $(this).data('id');
            if (cart.products[id]) {
                cart.products[id].qty -= 1;
                if (cart.products[id].qty <= 0) {
                    delete cart.products[id];
                }
                renderCart();
            }
        });

        $('#posCartItemsList').on('click', '.pos-cart-item-del', function() {
            var id = $(this).data('id');
            delete cart.products[id];
            renderCart();
        });

        // Clear Cart
        $('#posClearCartBtn').on('click', function() {
            if (confirm('Are you sure you want to clear this order?')) {
                cart.products = {};
                cart.kids = [];
                cart.coupon = null;
                $('#posCouponInput').val('');
                $('#posCouponBadge').hide();
                renderKidsList();
                renderCart();
            }
        });

        // 7. Render Cart & Calculate Subtotal, GST, Coupons
        function renderCart() {
            var $container = $('#posCartItemsList');
            $container.find('.pos-cart-item').remove();

            var totalItemsCount = 0;
            var hasItems = false;

            // Product lines
            Object.keys(cart.products).forEach(function(id) {
                hasItems = true;
                var item = cart.products[id];
                totalItemsCount += item.qty;

                var lineTotal = item.price * item.qty;
                var html = `
                    <div class="pos-cart-item">
                        <div class="pos-cart-item-details">
                            <div class="pos-cart-item-name">${item.name}</div>
                            <div class="pos-cart-item-sub">₹${item.price.toFixed(2)} &bull; ${item.tax}% GST</div>
                        </div>
                        <div class="pos-stepper">
                            <button type="button" class="pos-stepper-btn pos-stepper-minus" data-id="${item.id}">-</button>
                            <span class="pos-stepper-val">${item.qty}</span>
                            <button type="button" class="pos-stepper-btn pos-stepper-plus" data-id="${item.id}">+</button>
                        </div>
                        <div class="pos-cart-item-price">₹${lineTotal.toFixed(2)}</div>
                        <button type="button" class="pos-cart-item-del" data-id="${item.id}" title="Remove">&times;</button>
                    </div>
                `;
                $container.append(html);
            });

            // Kids pass count
            totalItemsCount += cart.kids.length;
            if (cart.kids.length > 0) {
                hasItems = true;
            }

            if (hasItems) {
                $('#posEmptyCartNotice').hide();
            } else {
                $('#posEmptyCartNotice').show();
            }

            // Update card badges in product grid
            $('#posProductGrid .pos-card').each(function() {
                var card = $(this);
                if (!card.data('is-pass')) {
                    var pid = card.data('product-id');
                    var badge = $('#badge-prod-' + pid);
                    if (cart.products[pid] && cart.products[pid].qty > 0) {
                        badge.text('× ' + cart.products[pid].qty).show();
                    } else {
                        badge.hide();
                    }
                }
            });

            // Calculate GST & Totals
            // Tax inclusive formula used in the CRM: basic = price / (1 + rate/100)
            var ba18 = 0;
            var ba12 = 0;
            var ba5  = 0;
            var gst18 = 0;
            var gst12 = 0;
            var gst5  = 0;

            // Kids play passes are taxable at 18% inclusive
            var kidsTotalGross = 0;
            cart.kids.forEach(function(k) {
                var basic = k.price / 1.18;
                ba18 += basic;
                gst18 += (k.price - basic);
                kidsTotalGross += k.price;
            });

            // Products
            Object.keys(cart.products).forEach(function(id) {
                var item = cart.products[id];
                var lineGross = item.price * item.qty;
                var rate = item.tax;
                var divisor = 1 + (rate / 100);
                var basic = lineGross / divisor;
                var taxAmt = lineGross - basic;

                if (rate === 18) {
                    ba18 += basic;
                    gst18 += taxAmt;
                } else if (rate === 12) {
                    ba12 += basic;
                    gst12 += taxAmt;
                } else if (rate === 5) {
                    ba5 += basic;
                    gst5 += taxAmt;
                } else {
                    ba18 += basic;
                }
            });

            var subTotalBeforeDis = ba18 + ba12 + ba5;
            var discount = 0;

            if (cart.coupon) {
                if (cart.coupon.discount_type === 'flat') {
                    discount = parseFloat(cart.coupon.discount) || 0;
                } else if (cart.coupon.discount_type === 'percent') {
                    var pct = parseFloat(cart.coupon.discount) || 0;
                    discount = (subTotalBeforeDis * pct) / 100;
                }
                // Cap discount
                if (discount > subTotalBeforeDis) {
                    discount = subTotalBeforeDis;
                }
            }

            var subTotalAfterDis = subTotalBeforeDis - discount;
            if (discount > 0 && cart.coupon && cart.coupon.discount_type === 'percent') {
                // Adjust GST accordingly on 18%
                var ratio = subTotalAfterDis / (subTotalBeforeDis || 1);
                ba18 = ba18 * ratio;
                gst18 = (ba18 * 18) / 100;
            }

            var grandTotal = subTotalAfterDis + gst18 + gst12 + gst5;

            // UI updates
            $('#posSummarySubtotal').text('₹' + subTotalBeforeDis.toFixed(2));
            if (discount > 0) {
                $('#posSummaryDiscountRow').show();
                $('#posSummaryDiscount').text('-₹' + discount.toFixed(2));
            } else {
                $('#posSummaryDiscountRow').hide();
            }

            $('#posSummaryGst18').text('₹' + gst18.toFixed(2));
            if (gst12 > 0) {
                $('#posSummaryGst12Row').show();
                $('#posSummaryGst12').text('₹' + gst12.toFixed(2));
            } else {
                $('#posSummaryGst12Row').hide();
            }

            if (gst5 > 0) {
                $('#posSummaryGst5Row').show();
                $('#posSummaryGst5').text('₹' + gst5.toFixed(2));
            } else {
                $('#posSummaryGst5Row').hide();
            }

            var grandFormatted = '₹' + grandTotal.toFixed(2);
            $('#posSummaryGrandTotal').text(grandFormatted);
            $('#posChargeBtnAmount').text(grandFormatted);

            // Enable / Disable Charge button
            var phone = $('#posPhone').val().trim();
            var guardian = $('#posGuardian').val().trim();
            var canCharge = hasItems && (phone.length === 10) && (guardian.length > 0);
            $('#posChargeBtn').prop('disabled', !canCharge);

            // Mobile bar
            $('#posMobileItemCount').text(totalItemsCount);
            $('#posMobileTotal').text(grandFormatted);

            // Populate form hidden fields
            $('#formSubtotal').val(subTotalAfterDis.toFixed(2));
            $('#formTotalAmount').val(grandTotal.toFixed(2));
            $('#formGst18').val(gst18.toFixed(2));
            $('#formGst12').val(gst12.toFixed(2));
            $('#formGst5').val(gst5.toFixed(2));
            $('#formBa18').val(ba18.toFixed(2));
            $('#formBa12').val(ba12.toFixed(2));
            $('#formBa5').val(ba5.toFixed(2));
            $('#formKidsOnlyPrice').val(kidsTotalGross > 0 ? (kidsTotalGross / 1.18).toFixed(2) : '0');
            $('#formKidsPriceNoDis').val(kidsTotalGross > 0 ? (kidsTotalGross / 1.18).toFixed(2) : '0');
            $('#formCouponValue').val(discount.toFixed(2));
            $('#formCouponCode').val(cart.coupon ? cart.coupon.code : '');
        }

        // 8. Payment mode buttons
        $('.pos-pay-btn').on('click', function() {
            $('.pos-pay-btn').removeClass('active');
            $(this).addClass('active');
            var mode = $(this).data('mode');
            cart.paymentMode = mode;
            $('#formPaymentMode').val(mode);
        });

        // 9. Customer Phone autocomplete
        $('#posPhone').on('keyup', function() {
            var search = $(this).val().trim();
            renderCart(); // re-evaluates canCharge
            if (search.length >= 3) {
                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/get-ticket-phone-list',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { query: search },
                    success: function(data) {
                        $('#posPhoneLookupDrop').html(data).show();
                    }
                });
            } else {
                $('#posPhoneLookupDrop').hide();
            }
        });

        $('#posGuardian').on('input', function() {
            renderCart();
        });

        // Window-level fetchuserDetail override for our autocomplete
        window.fetchuserDetail = function(user) {
            if (user) {
                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/get-user-detail',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { query: user },
                    success: function(resp) {
                        try {
                            var arr = typeof resp === 'string' ? JSON.parse(resp) : resp;
                            $('#posGuardian').val(arr.name || '');
                            $('#posPhone').val(arr.phone_no || '');
                            if (arr.dob) $('#posDob').val(arr.dob);
                            $('#posPhoneLookupDrop').hide();

                            // If kids detail present and no kid yet, prefill 1 kid
                            if (arr.kiddetail && cart.kids.length === 0) {
                                cart.kids.push({
                                    name: arr.kiddetail,
                                    hours: '1',
                                    price: dayPriceMap['1'] || 0
                                });
                                renderKidsList();
                            }
                            renderCart();
                        } catch (e) {
                            console.error(e);
                        }
                    }
                });
            }
        };

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#posPhoneLookupDrop, #posPhone').length) {
                $('#posPhoneLookupDrop').hide();
            }
        });

        // 10. Coupons
        $('#posApplyCouponBtn').on('click', function() {
            var code = $('#posCouponInput').val().trim();
            if (!code) {
                toastr.warning('Please enter a coupon code');
                return;
            }

            $.ajax({
                url: baseUrl + '/get-coupon',
                type: 'GET',
                data: {
                    option: code,
                    branch: currentBranchId
                },
                success: function(data) {
                    if (data && data.status === 400) {
                        toastr.error(data.msg || 'Invalid coupon');
                        return;
                    }
                    if (data && data.discount) {
                        cart.coupon = {
                            code: data.coupon_name || code,
                            discount: parseFloat(data.discount),
                            discount_type: data.discount_type || 'flat'
                        };
                        $('#posCouponNameText').text(cart.coupon.code);
                        $('#posCouponBadge').show();
                        toastr.success('Coupon ' + cart.coupon.code + ' applied!');
                        renderCart();
                    } else {
                        toastr.error('Invalid or expired coupon');
                    }
                },
                error: function() {
                    toastr.error('Error validating coupon');
                }
            });
        });

        $('#posRemoveCouponBtn').on('click', function() {
            cart.coupon = null;
            $('#posCouponInput').val('');
            $('#posCouponBadge').hide();
            renderCart();
            toastr.info('Coupon removed');
        });

        // 11. Final Checkout & Submit to /add-tickets
        $('#posChargeBtn').on('click', function() {
            var phone = $('#posPhone').val().trim();
            var guardian = $('#posGuardian').val().trim();
            var dob = $('#posDob').val().trim();

            if (!phone || phone.length !== 10) {
                toastr.error('Please enter a valid 10-digit customer phone number.');
                $('#posPhone').focus();
                return;
            }
            if (!guardian) {
                toastr.error('Please enter Guardian / Customer Name.');
                $('#posGuardian').focus();
                return;
            }

            // Populate form
            $('#formPhone').val(phone);
            $('#formGuardian').val(guardian);
            $('#formDob').val(dob || "{{ date('Y-m-d') }}");
            $('#formKidsQty').val(cart.kids.length);

            // Populate Kids inputs
            var $kidsWrap = $('#formDynamicKidsInputs');
            $kidsWrap.empty();
            cart.kids.forEach(function(k) {
                $kidsWrap.append('<input type="hidden" name="kidName[]" value="' + k.name + '">');
                $kidsWrap.append('<input type="hidden" name="kid_price[]" value="' + k.price + '">');
                $kidsWrap.append('<input type="hidden" name="hours[]" value="' + k.hours + '">');
            });

            // Populate Product inputs
            var $prodsWrap = $('#formDynamicProductsInputs');
            $prodsWrap.empty();
            Object.keys(cart.products).forEach(function(id) {
                var p = cart.products[id];
                var lineGross = p.price * p.qty;
                var rate = p.tax;
                var basic = lineGross / (1 + rate / 100);

                $prodsWrap.append('<input type="hidden" name="product_name[]" value="' + p.name + '">');
                $prodsWrap.append('<input type="hidden" name="product_quantity[]" value="' + p.qty + '">');
                $prodsWrap.append('<input type="hidden" name="final_price[]" value="' + lineGross.toFixed(2) + '">');
                $prodsWrap.append('<input type="hidden" name="price[]" value="' + basic.toFixed(2) + '">');
                $prodsWrap.append('<input type="hidden" name="tax[]" value="' + p.tax + '">');
            });

            // Submit form to render invoice & send SMS as existing tickets screen does
            toastr.info('Processing order...');
            $('#posCheckoutForm').submit();
        });

        // 12. Mobile Bottom Sheet
        $('#posMobileOpenCartBtn').on('click', function() {
            var $panel = $('#posCartPanel');
            $('#posMobileModalBody').empty().append($panel.clone(true));
            $('#posMobileCartModal').modal('show');
        });

    })(jQuery);
    </script>
</body>
</html>
