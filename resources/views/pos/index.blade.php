@extends('layouts.app')

@section('title', 'Punto de Venta')

@push('styles')
<style>
.pos-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    height: calc(100vh - 60px);
    overflow: hidden;
}
/* LEFT PANEL */
.pos-left {
    display: flex; flex-direction: column;
    overflow: hidden;
    background: var(--bg-900);
}
.pos-toolbar {
    padding: 0.85rem 1.25rem;
    background: var(--bg-800);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;
}
.pos-search {
    position: relative; flex: 1; min-width: 180px;
}
.pos-search input {
    width: 100%; padding: 0.5rem 0.85rem 0.5rem 2.2rem;
    background: var(--bg-700); border: 1px solid var(--border);
    border-radius: 6px; color: var(--text-100);
    font-family: 'Nunito', sans-serif; font-size: 0.875rem;
}
.pos-search input:focus { outline: none; border-color: var(--red); }
.pos-search i {
    position: absolute; left: 0.7rem; top: 50%; transform: translateY(-50%);
    color: var(--text-400); font-size: 0.8rem;
}
.filter-btns { display: flex; gap: 0.35rem; }
.filter-btn {
    padding: 0.45rem 0.9rem; border-radius: 20px;
    font-weight: 700; font-size: 0.8rem;
    cursor: pointer; border: 2px solid var(--border);
    background: transparent; color: var(--text-400);
    transition: all 0.18s; white-space: nowrap;
    font-family: 'Nunito', sans-serif;
}
.filter-btn.active, .filter-btn:hover { border-color: var(--red); color: var(--red-light); background: rgba(230,48,18,0.12); }
.products-grid {
    flex: 1; overflow-y: auto;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(175px, 1fr));
    gap: 0.85rem;
    padding: 1rem 1.25rem;
    align-content: start;
}
.product-card {
    background: var(--bg-800);
    border: 2px solid var(--border);
    border-radius: 10px;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.18s;
    display: flex; flex-direction: column; gap: 0.4rem;
    user-select: none;
}
.product-card:hover {
    border-color: var(--red);
    background: var(--bg-700);
    transform: translateY(-2px);
}
.product-card:active { transform: scale(0.97); }
.product-card.inactive { opacity: 0.4; pointer-events: none; }
.product-emoji { font-size: 2rem; text-align: center; margin-bottom: 0.25rem; }
.product-name { font-weight: 700; font-size: 0.875rem; color: var(--text-100); line-height: 1.3; }
.product-cat {
    font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.5px;
}
.product-cat.empanada { color: var(--warning); }
.product-cat.papa_rellena { color: #a78bfa; }
.product-price { font-family: 'Bebas Neue', sans-serif; font-size: 1.2rem; color: var(--red-light); margin-top: auto; }

/* RIGHT PANEL */
.pos-right {
    background: var(--bg-800);
    border-left: 1px solid var(--border);
    display: flex; flex-direction: column;
    overflow: hidden;
}
.pos-client-bar {
    padding: 0.75rem 1rem;
    background: var(--bg-700);
    border-bottom: 1px solid var(--border);
}
.client-bar-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-400); margin-bottom: 0.4rem; }
.client-selector {
    display: flex; gap: 0.4rem;
}
.client-display {
    flex: 1; padding: 0.5rem 0.75rem;
    background: var(--bg-800); border: 1px solid var(--border);
    border-radius: 6px; font-size: 0.85rem; font-weight: 600;
    color: var(--text-100);
    display: flex; align-items: center; gap: 0.4rem;
    min-width: 0;
}
.client-display .name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.client-display i { color: var(--red); flex-shrink: 0; }

.order-list {
    flex: 1; overflow-y: auto;
    padding: 0.75rem;
    display: flex; flex-direction: column; gap: 0.5rem;
}
.order-empty {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    color: var(--text-400); gap: 0.5rem;
    padding: 2rem;
}
.order-empty i { font-size: 2.5rem; opacity: 0.3; }
.order-empty p { font-size: 0.85rem; }
.order-item {
    background: var(--bg-700); border: 1px solid var(--border);
    border-radius: 8px; padding: 0.6rem 0.75rem;
    display: flex; align-items: center; gap: 0.6rem;
}
.order-item-info { flex: 1; min-width: 0; }
.order-item-name { font-weight: 700; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.order-item-price { font-size: 0.8rem; color: var(--text-400); }
.qty-controls { display: flex; align-items: center; gap: 0.4rem; flex-shrink: 0; }
.qty-btn {
    width: 26px; height: 26px; border-radius: 6px;
    background: var(--bg-600); border: 1px solid var(--border);
    color: var(--text-100); cursor: pointer; font-size: 0.9rem;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.15s; font-family: 'Nunito', sans-serif;
}
.qty-btn:hover { background: var(--red); border-color: var(--red); }
.qty-val { font-weight: 700; font-size: 0.9rem; min-width: 20px; text-align: center; }
.order-item-subtotal { font-family: 'Bebas Neue', sans-serif; font-size: 1rem; color: var(--red-light); min-width: 60px; text-align: right; flex-shrink: 0; }
.remove-btn { background: none; border: none; color: var(--text-400); cursor: pointer; padding: 0.2rem; transition: color 0.15s; }
.remove-btn:hover { color: #f87171; }

/* CHECKOUT */
.pos-checkout {
    padding: 0.75rem 1rem;
    border-top: 2px solid var(--border);
    background: var(--bg-700);
}
.checkout-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 0.25rem 0;
    font-size: 0.875rem; color: var(--text-400);
}
.checkout-total {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.8rem;
    color: var(--text-100);
}
.checkout-total-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-400); }
.payment-select {
    display: flex; gap: 0.35rem; margin: 0.6rem 0;
}
.pay-opt {
    flex: 1; padding: 0.45rem; border-radius: 6px;
    background: var(--bg-600); border: 2px solid var(--border);
    color: var(--text-400); cursor: pointer; font-weight: 700;
    font-size: 0.75rem; text-align: center; transition: all 0.15s;
    font-family: 'Nunito', sans-serif;
}
.pay-opt.active { border-color: var(--red); color: var(--red-light); background: rgba(230,48,18,0.12); }
.pay-opt:hover { border-color: var(--red-dark); color: var(--text-100); }

/* CLIENT MODAL */
.search-results {
    background: var(--bg-700);
    border: 1px solid var(--border);
    border-radius: 6px;
    overflow: hidden;
    margin-top: 0.5rem;
    max-height: 240px;
    overflow-y: auto;
}
.search-result-item {
    padding: 0.7rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
}
.search-result-item:last-child { border-bottom: none; }
.search-result-item:hover { background: var(--bg-600); }
.search-result-name { font-weight: 700; font-size: 0.875rem; }
.search-result-doc { font-size: 0.75rem; color: var(--text-400); }

/* RECEIPT */
.receipt-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.85); z-index: 2000;
    align-items: center; justify-content: center;
}
.receipt-overlay.open { display: flex; }
.receipt-box {
    background: var(--bg-800); border: 1px solid var(--border);
    border-radius: 12px; padding: 2rem;
    width: 90%; max-width: 420px;
    text-align: center;
}
.receipt-icon { font-size: 3rem; margin-bottom: 0.75rem; }
.receipt-title { font-family: 'Bebas Neue', sans-serif; font-size: 1.8rem; color: var(--success); letter-spacing: 1px; }
.receipt-total { font-family: 'Bebas Neue', sans-serif; font-size: 2.5rem; color: var(--red-light); margin: 0.5rem 0; }
.receipt-detail { font-size: 0.875rem; color: var(--text-400); }

@media (max-width: 900px) {
    .pos-layout { grid-template-columns: 1fr; }
    .pos-right { border-left: none; border-top: 2px solid var(--red); max-height: 50vh; }
}
</style>
@endpush

@section('content')
<div class="pos-layout">
    <!-- LEFT: Product Grid -->
    <div class="pos-left">
        <div class="pos-toolbar">
            <div class="pos-search">
                <i class="fas fa-search"></i>
                <input type="text" id="productSearch" placeholder="Buscar producto...">
            </div>
            <div class="filter-btns">
                <button class="filter-btn active" data-filter="all">Todos</button>
                <button class="filter-btn" data-filter="empanada">🫓 Empanadas</button>
                <button class="filter-btn" data-filter="papa_rellena">🥔 Papas</button>
            </div>
        </div>
        <div class="products-grid" id="productsGrid">
            @foreach($products as $product)
            <div class="product-card {{ $product->active ? '' : 'inactive' }}"
                 data-id="{{ $product->id }}"
                 data-name="{{ $product->name }}"
                 data-price="{{ $product->price }}"
                 data-category="{{ $product->category }}"
                 onclick="addToOrder(this)">
                <div class="product-emoji">{{ $product->category === 'empanada' ? '🫓' : '🥔' }}</div>
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-cat {{ $product->category }}">{{ $product->category_label }}</div>
                <div class="product-price">$ {{ number_format($product->price, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- RIGHT: Order Panel -->
    <div class="pos-right">
        <!-- Client Bar -->
        <div class="pos-client-bar">
            <div class="client-bar-label"><i class="fas fa-user"></i> Cliente</div>
            <div class="client-selector">
                <div class="client-display" id="clientDisplay">
                    <i class="fas fa-store"></i>
                    <span class="name">Cliente de Mostrador</span>
                </div>
                <button class="btn btn-secondary btn-sm" onclick="openClientModal()" title="Cambiar cliente">
                    <i class="fas fa-exchange-alt"></i>
                </button>
            </div>
        </div>

        <!-- Order Items -->
        <div class="order-list" id="orderList">
            <div class="order-empty" id="orderEmpty">
                <i class="fas fa-shopping-basket"></i>
                <p>Selecciona productos del menú</p>
            </div>
        </div>

        <!-- Checkout -->
        <div class="pos-checkout">
            <div class="checkout-row">
                <span>Artículos</span>
                <span id="itemCount">0</span>
            </div>
            <div style="display:flex;align-items:flex-end;justify-content:space-between;margin:0.25rem 0 0.5rem">
                <span class="checkout-total-label">Total</span>
                <span class="checkout-total" id="orderTotal">$ 0</span>
            </div>
            <div class="payment-select">
                <button class="pay-opt active" data-method="cash" onclick="selectPayment(this)"><i class="fas fa-money-bill"></i><br>Efectivo</button>
                <button class="pay-opt" data-method="card" onclick="selectPayment(this)"><i class="fas fa-credit-card"></i><br>Tarjeta</button>
                <button class="pay-opt" data-method="transfer" onclick="selectPayment(this)"><i class="fas fa-university"></i><br>Transfer.</button>
            </div>
            <button class="btn btn-success btn-block btn-lg" onclick="processSale()" id="btnSell">
                <i class="fas fa-check-circle"></i> Registrar Venta
            </button>
            <button class="btn btn-secondary btn-block btn-sm mt-1" onclick="clearOrder()">
                <i class="fas fa-trash"></i> Limpiar
            </button>
        </div>
    </div>
</div>

<!-- CLIENT MODAL -->
<div class="modal-overlay" id="clientModal">
    <div class="modal" style="max-width:560px">
        <div class="modal-header">
            <span class="modal-title">Seleccionar Cliente</span>
            <button class="modal-close" onclick="closeClientModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <!-- Tabs -->
            <div style="display:flex;gap:0.5rem;margin-bottom:1rem">
                <button class="btn btn-secondary" id="tabCounter" onclick="setClientTab('counter')" style="flex:1">
                    <i class="fas fa-store"></i> Cliente Mostrador
                </button>
                <button class="btn btn-secondary" id="tabSearch" onclick="setClientTab('search')" style="flex:1">
                    <i class="fas fa-search"></i> Buscar Cliente
                </button>
                <button class="btn btn-secondary" id="tabNew" onclick="setClientTab('new')" style="flex:1">
                    <i class="fas fa-user-plus"></i> Nuevo Cliente
                </button>
            </div>

            <!-- Counter Client Tab -->
            <div id="tabCounterContent">
                <div style="text-align:center;padding:1.5rem">
                    <div style="font-size:3rem;margin-bottom:0.5rem">🏪</div>
                    <div style="font-weight:700;margin-bottom:0.5rem">Cliente de Mostrador</div>
                    <div style="color:var(--text-400);font-size:0.875rem;margin-bottom:1rem">Venta anónima sin datos de cliente</div>
                    <button class="btn btn-primary" onclick="selectCounterClient()">
                        <i class="fas fa-check"></i> Seleccionar
                    </button>
                </div>
            </div>

            <!-- Search Client Tab -->
            <div id="tabSearchContent" style="display:none">
                <div class="form-group">
                    <input type="text" class="form-control" id="clientSearchInput"
                           placeholder="Nombre o número de documento..."
                           oninput="searchClients(this.value)">
                </div>
                <div id="searchResults"></div>
            </div>

            <!-- New Client Tab -->
            <div id="tabNewContent" style="display:none">
                <div id="newClientError" class="alert alert-error" style="display:none"></div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tipo Doc *</label>
                        <select class="form-select" id="nc_doc_type">
                            <option value="CC">CC - Cédula</option>
                            <option value="CE">CE - Extranjería</option>
                            <option value="NIT">NIT</option>
                            <option value="PP">PP - Pasaporte</option>
                            <option value="TI">TI - Tarjeta Identidad</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Número Doc *</label>
                        <input type="text" class="form-control" id="nc_doc_num" placeholder="Ej: 1098765432">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Nombre completo *</label>
                    <input type="text" class="form-control" id="nc_name" placeholder="Nombre y apellidos">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="nc_city" placeholder="Ej: Bucaramanga">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="nc_phone" placeholder="Ej: 3001234567">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="nc_address" placeholder="Ej: Cra 10 # 15-20">
                </div>
                <button class="btn btn-primary btn-block" onclick="saveNewClient()">
                    <i class="fas fa-save"></i> Guardar y Seleccionar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SUCCESS RECEIPT MODAL -->
<div class="receipt-overlay" id="receiptOverlay">
    <div class="receipt-box">
        <div class="receipt-icon">✅</div>
        <div class="receipt-title">¡Venta Registrada!</div>
        <div class="receipt-total" id="receiptTotal"></div>
        <div class="receipt-detail" id="receiptDetail"></div>
        <hr class="divider">
        <div style="display:flex;gap:0.75rem;justify-content:center;margin-top:0.5rem">
            <a href="#" id="receiptLink" class="btn btn-secondary" target="_blank">
                <i class="fas fa-receipt"></i> Ver Recibo
            </a>
            <button class="btn btn-primary" onclick="closeReceipt()">
                <i class="fas fa-plus"></i> Nueva Venta
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const COUNTER_CLIENT_ID = {{ $counterClient->id ?? 1 }};
let selectedClientId = COUNTER_CLIENT_ID;
let selectedClientName = 'Cliente de Mostrador';
let selectedPayment = 'cash';
let orderItems = {};

function addToOrder(card) {
    const id = card.dataset.id;
    const name = card.dataset.name;
    const price = parseFloat(card.dataset.price);
    if (orderItems[id]) {
        orderItems[id].qty++;
    } else {
        orderItems[id] = { id, name, price, qty: 1 };
    }
    renderOrder();
}

function removeItem(id) {
    delete orderItems[id];
    renderOrder();
}

function changeQty(id, delta) {
    if (!orderItems[id]) return;
    orderItems[id].qty += delta;
    if (orderItems[id].qty <= 0) delete orderItems[id];
    renderOrder();
}

function renderOrder() {
    const list = document.getElementById('orderList');
    const empty = document.getElementById('orderEmpty');
    const keys = Object.keys(orderItems);

    if (keys.length === 0) {
        list.innerHTML = '';
        list.appendChild(empty);
        empty.style.display = 'flex';
        document.getElementById('itemCount').textContent = '0';
        document.getElementById('orderTotal').textContent = '$ 0';
        return;
    }

    let total = 0, count = 0;
    let html = '';
    keys.forEach(id => {
        const item = orderItems[id];
        const sub = item.price * item.qty;
        total += sub; count += item.qty;
        html += `
        <div class="order-item">
            <div class="order-item-info">
                <div class="order-item-name">${item.name}</div>
                <div class="order-item-price">$ ${fmt(item.price)} c/u</div>
            </div>
            <div class="qty-controls">
                <button class="qty-btn" onclick="changeQty('${id}', -1)">−</button>
                <span class="qty-val">${item.qty}</span>
                <button class="qty-btn" onclick="changeQty('${id}', 1)">+</button>
            </div>
            <span class="order-item-subtotal">$ ${fmt(sub)}</span>
            <button class="remove-btn" onclick="removeItem('${id}')"><i class="fas fa-times"></i></button>
        </div>`;
    });

    list.innerHTML = html;
    document.getElementById('itemCount').textContent = count;
    document.getElementById('orderTotal').textContent = '$ ' + fmt(total);
}

function fmt(n) {
    return new Intl.NumberFormat('es-CO').format(n);
}

function clearOrder() {
    orderItems = {};
    renderOrder();
}

function selectPayment(el) {
    document.querySelectorAll('.pay-opt').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    selectedPayment = el.dataset.method;
}

async function processSale() {
    const keys = Object.keys(orderItems);
    if (keys.length === 0) { alert('Agrega productos a la orden primero.'); return; }

    const btn = document.getElementById('btnSell');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

    const items = keys.map(id => ({ product_id: id, quantity: orderItems[id].qty }));
    const res = await fetch('/pos/sale', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ client_id: selectedClientId, items, payment_method: selectedPayment })
    });
    const data = await res.json();
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-check-circle"></i> Registrar Venta';

    if (data.success) {
        document.getElementById('receiptTotal').textContent = '$ ' + fmt(data.total);
        document.getElementById('receiptDetail').textContent = `Venta #${data.sale_id} · ${selectedClientName}`;
        document.getElementById('receiptLink').href = `/pos/receipt/${data.sale_id}`;
        document.getElementById('receiptOverlay').classList.add('open');
        clearOrder();
        selectedClientId = COUNTER_CLIENT_ID;
        selectedClientName = 'Cliente de Mostrador';
        updateClientDisplay();
    } else {
        alert('Error: ' + data.message);
    }
}

function closeReceipt() {
    document.getElementById('receiptOverlay').classList.remove('open');
}

// CLIENT MODAL
function openClientModal() { document.getElementById('clientModal').classList.add('open'); }
function closeClientModal() { document.getElementById('clientModal').classList.remove('open'); }

function setClientTab(tab) {
    ['counter','search','new'].forEach(t => {
        document.getElementById('tab'+t.charAt(0).toUpperCase()+t.slice(1)+'Content').style.display = t === tab ? 'block' : 'none';
        const btn = document.getElementById('tab'+t.charAt(0).toUpperCase()+t.slice(1));
        btn.classList.toggle('btn-primary', t === tab);
        btn.classList.toggle('btn-secondary', t !== tab);
    });
}

function selectCounterClient() {
    selectedClientId = COUNTER_CLIENT_ID;
    selectedClientName = 'Cliente de Mostrador';
    updateClientDisplay();
    closeClientModal();
}

function updateClientDisplay() {
    const icon = selectedClientId == COUNTER_CLIENT_ID ? 'fa-store' : 'fa-user';
    document.getElementById('clientDisplay').innerHTML = `<i class="fas ${icon}"></i><span class="name">${selectedClientName}</span>`;
}

let searchTimer;
function searchClients(q) {
    clearTimeout(searchTimer);
    if (q.length < 2) { document.getElementById('searchResults').innerHTML = ''; return; }
    searchTimer = setTimeout(async () => {
        const res = await fetch('/pos/clients/search?q=' + encodeURIComponent(q));
        const clients = await res.json();
        const div = document.getElementById('searchResults');
        if (clients.length === 0) {
            div.innerHTML = '<div style="padding:1rem;color:var(--text-400);text-align:center;font-size:0.875rem">No se encontraron clientes</div>';
            return;
        }
        div.innerHTML = '<div class="search-results">' + clients.map(c => `
            <div class="search-result-item" onclick="selectClient(${c.id}, '${c.name.replace(/'/g,"\\'")}')">
                <div class="search-result-name">${c.name}</div>
                <div class="search-result-doc">${c.document_type}: ${c.document_number} · ${c.city || 'Sin ciudad'}</div>
            </div>`).join('') + '</div>';
    }, 300);
}

function selectClient(id, name) {
    selectedClientId = id;
    selectedClientName = name;
    updateClientDisplay();
    closeClientModal();
}

async function saveNewClient() {
    const errDiv = document.getElementById('newClientError');
    errDiv.style.display = 'none';
    const payload = {
        document_type: document.getElementById('nc_doc_type').value,
        document_number: document.getElementById('nc_doc_num').value.trim(),
        name: document.getElementById('nc_name').value.trim(),
        city: document.getElementById('nc_city').value.trim(),
        phone: document.getElementById('nc_phone').value.trim(),
        address: document.getElementById('nc_address').value.trim(),
    };
    if (!payload.document_number || !payload.name) {
        errDiv.textContent = 'Número de documento y nombre son obligatorios.';
        errDiv.style.display = 'flex'; return;
    }
    const res = await fetch('/pos/client', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (data.success) {
        selectClient(data.client.id, data.client.name);
        ['nc_doc_num','nc_name','nc_city','nc_phone','nc_address'].forEach(id => document.getElementById(id).value = '');
    } else {
        const msgs = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Error al guardar.');
        errDiv.textContent = msgs;
        errDiv.style.display = 'flex';
    }
}

// FILTER + SEARCH
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        filterProducts();
    });
});

document.getElementById('productSearch').addEventListener('input', filterProducts);

function filterProducts() {
    const q = document.getElementById('productSearch').value.toLowerCase();
    const cat = document.querySelector('.filter-btn.active').dataset.filter;
    document.querySelectorAll('.product-card').forEach(card => {
        const matchCat = cat === 'all' || card.dataset.category === cat;
        const matchQ = card.dataset.name.toLowerCase().includes(q);
        card.style.display = (matchCat && matchQ) ? '' : 'none';
    });
}
</script>
@endpush
