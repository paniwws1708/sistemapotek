<?php
require '../layout/header.php';
require '../layout/sidebar.php';

// Fetch medicines with positive stock
$q_obat = mysqli_query($conn, "SELECT id_obat, nama_obat, harga, stok FROM obat WHERE stok > 0 ORDER BY nama_obat ASC");
$obat_list = [];
while($row = mysqli_fetch_assoc($q_obat)) {
    $obat_list[] = $row;
}
?>

<style>
.pos-container {
    display: flex;
    gap: 25px;
    height: calc(100vh - 120px);
}
.pos-left {
    flex: 6;
    display: flex;
    flex-direction: column;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-soft);
    overflow: hidden;
}
.pos-right {
    flex: 4;
    display: flex;
    flex-direction: column;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-soft);
}
.pos-header {
    padding: 20px;
    border-bottom: 1px solid rgba(28, 43, 75, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.pos-header h2 {
    font-size: 18px;
    color: var(--primary-navy);
}
.med-grid {
    padding: 20px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 15px;
    overflow-y: auto;
}
.med-card {
    background: var(--white);
    padding: 15px;
    border-radius: var(--border-radius-md);
    cursor: pointer;
    border: 1px solid rgba(28, 43, 75, 0.05);
    transition: all 0.2s var(--transition-smooth);
}
.med-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(28, 43, 75, 0.08);
    border-color: var(--primary-navy);
}
.med-name {
    font-weight: 600;
    font-size: 14px;
    color: var(--primary-navy);
    margin-bottom: 4px;
}
.med-price {
    color: #10b981;
    font-weight: 700;
    font-size: 15px;
}
.med-stock {
    font-size: 11px;
    color: var(--text-light);
    margin-top: 5px;
}

/* Cart Section */
.cart-items {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}
.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px dashed rgba(28, 43, 75, 0.1);
}
.cart-item-info {
    flex: 1;
}
.cart-item-info h4 {
    font-size: 14px;
    color: var(--primary-navy);
    margin-bottom: 4px;
}
.cart-item-qty {
    display: flex;
    align-items: center;
    gap: 10px;
}
.qty-btn {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 1px solid rgba(28, 43, 75, 0.1);
    background: var(--white);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}
.qty-btn:hover {
    background: var(--primary-navy);
    color: var(--white);
}
.cart-item-price {
    font-weight: 700;
    color: var(--primary-navy);
    font-size: 15px;
}

.checkout-section {
    padding: 20px;
    background: rgba(255, 255, 255, 0.8);
    border-top: 1px solid rgba(28, 43, 75, 0.05);
}
.checkout-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
}
.checkout-total {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary-navy);
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid rgba(28, 43, 75, 0.1);
}
.payment-methods {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    margin-bottom: 15px;
}
.pay-method-btn {
    flex: 1;
    padding: 10px;
    text-align: center;
    border: 1px solid rgba(28, 43, 75, 0.1);
    background: var(--white);
    border-radius: var(--border-radius-sm);
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}
.pay-method-btn.active {
    background: var(--primary-navy);
    color: var(--white);
    border-color: var(--primary-navy);
}

.input-cash-group {
    margin-bottom: 15px;
}
.input-cash-group label {
    font-size: 12px;
    color: var(--text-light);
    margin-bottom: 5px;
    display: block;
}
.input-cash {
    width: 100%;
    padding: 12px;
    border: 1px solid rgba(28, 43, 75, 0.1);
    border-radius: var(--border-radius-sm);
    font-size: 16px;
    font-weight: 600;
    text-align: right;
}

.btn-checkout {
    width: 100%;
    padding: 15px;
    background: #10b981;
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
}
.btn-checkout:hover {
    background: #059669;
    transform: translateY(-2px);
}
.btn-checkout:disabled {
    background: #a7f3d0;
    cursor: not-allowed;
    transform: none;
}
</style>

<main class="main-content">
    <section class="dashboard-content" style="padding-top: 80px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; color: var(--primary-navy); font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Kasir</h1>
                <p style="color: var(--text-light); font-size: 14px;">Sistem Point of Sale Apotek.</p>
            </div>
            <a href="../data/transaksi_riwayat.php" class="btn-outline" style="padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: 20px;">
                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi
            </a>
        </div>

        <div class="pos-container">
            <!-- Left Side: Product Selection -->
            <div class="pos-left">
                <div class="pos-header">
                    <h2>Pilih Obat</h2>
                    <div class="search-bar" style="width: 250px; padding: 8px 15px;">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" id="searchObat" placeholder="Cari nama obat...">
                    </div>
                </div>
                <div class="med-grid" id="medGrid">
                    <?php foreach($obat_list as $obat): ?>
                        <div class="med-card" onclick="addToCart(<?= $obat['id_obat'] ?>, '<?= addslashes($obat['nama_obat']) ?>', <?= $obat['harga'] ?>, <?= $obat['stok'] ?>)">
                            <div class="med-name"><?= htmlspecialchars($obat['nama_obat']) ?></div>
                            <div class="med-price">Rp <?= number_format($obat['harga'], 0, ',', '.') ?></div>
                            <div class="med-stock">Stok: <?= $obat['stok'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right Side: Cart & Checkout -->
            <div class="pos-right">
                <div class="pos-header">
                    <h2>Keranjang</h2>
                    <button class="btn-outline" style="padding: 4px 10px; font-size: 11px;" onclick="clearCart()">Kosongkan</button>
                </div>
                <div class="cart-items" id="cartItems">
                    <!-- Cart items will be rendered here -->
                    <div style="text-align: center; color: var(--text-light); padding-top: 50px;">
                        <i class="fa-solid fa-cart-arrow-down" style="font-size: 40px; opacity: 0.3; margin-bottom: 10px;"></i>
                        <p>Keranjang masih kosong</p>
                    </div>
                </div>

                <div class="checkout-section">
                    <div class="checkout-row checkout-total">
                        <span>Total Pembayaran</span>
                        <span id="totalPriceText">Rp 0</span>
                    </div>

                    <div class="payment-methods">
                        <div class="pay-method-btn active" onclick="setPaymentMethod('Tunai', this)">Tunai</div>
                        <div class="pay-method-btn" onclick="setPaymentMethod('QRIS', this)">QRIS</div>
                        <div class="pay-method-btn" onclick="setPaymentMethod('Transfer', this)">Transfer</div>
                    </div>

                    <div class="input-cash-group" id="cashInputGroup">
                        <label>Tunai Diterima (Rp)</label>
                        <input type="number" id="cashReceived" class="input-cash" placeholder="0" onkeyup="calculateChange()">
                    </div>

                    <div class="checkout-row" id="changeRow" style="font-weight: 700; color: var(--primary-navy);">
                        <span>Kembalian</span>
                        <span id="changeText">Rp 0</span>
                    </div>

                    <form id="checkoutForm" action="../src/transaksi_proses.php" method="POST" style="margin-top: 15px;">
                        <input type="hidden" name="cart_data" id="cartDataInput">
                        <input type="hidden" name="total_harga" id="totalHargaInput">
                        <input type="hidden" name="metode_pembayaran" id="metodePembayaranInput" value="Tunai">
                        <input type="hidden" name="cash_received" id="cashReceivedInput" value="0">
                        <input type="hidden" name="kembalian" id="kembalianInput" value="0">
                        
                        <button type="button" class="btn-checkout" id="btnComplete" onclick="processCheckout()" disabled>
                            <i class="fa-solid fa-check-circle"></i> Selesaikan Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
let cart = {};
let totalPrice = 0;
let currentPaymentMethod = 'Tunai';

// Search Functionality
document.getElementById('searchObat').addEventListener('input', function(e) {
    let term = e.target.value.toLowerCase();
    let cards = document.querySelectorAll('.med-card');
    cards.forEach(card => {
        let name = card.querySelector('.med-name').innerText.toLowerCase();
        if(name.includes(term)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

function formatRupiah(number) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
}

function addToCart(id, name, price, stock) {
    if(cart[id]) {
        if(cart[id].qty < stock) {
            cart[id].qty++;
        } else {
            alert('Stok tidak mencukupi!');
        }
    } else {
        cart[id] = {
            id: id,
            name: name,
            price: price,
            qty: 1,
            maxStock: stock
        };
    }
    updateCartUI();
}

function updateQty(id, delta) {
    if(cart[id]) {
        let newQty = cart[id].qty + delta;
        if(newQty > 0 && newQty <= cart[id].maxStock) {
            cart[id].qty = newQty;
        } else if (newQty <= 0) {
            delete cart[id];
        } else {
            alert('Stok maksimal tercapai!');
        }
        updateCartUI();
    }
}

function clearCart() {
    cart = {};
    updateCartUI();
}

function updateCartUI() {
    let cartContainer = document.getElementById('cartItems');
    cartContainer.innerHTML = '';
    totalPrice = 0;

    let keys = Object.keys(cart);
    if(keys.length === 0) {
        cartContainer.innerHTML = `
            <div style="text-align: center; color: var(--text-light); padding-top: 50px;">
                <i class="fa-solid fa-cart-arrow-down" style="font-size: 40px; opacity: 0.3; margin-bottom: 10px;"></i>
                <p>Keranjang masih kosong</p>
            </div>
        `;
        document.getElementById('totalPriceText').innerText = 'Rp 0';
        document.getElementById('totalHargaInput').value = 0;
        document.getElementById('btnComplete').disabled = true;
        calculateChange();
        return;
    }

    keys.forEach(id => {
        let item = cart[id];
        let subtotal = item.qty * item.price;
        totalPrice += subtotal;

        cartContainer.innerHTML += `
            <div class="cart-item">
                <div class="cart-item-info">
                    <h4>${item.name}</h4>
                    <div class="cart-item-qty">
                        <button class="qty-btn" onclick="updateQty(${id}, -1)"><i class="fa-solid fa-minus"></i></button>
                        <span style="font-size: 14px; font-weight: 600; width: 20px; text-align: center;">${item.qty}</span>
                        <button class="qty-btn" onclick="updateQty(${id}, 1)"><i class="fa-solid fa-plus"></i></button>
                        <span style="font-size: 12px; color: var(--text-light); margin-left: 10px;">@ ${formatRupiah(item.price)}</span>
                    </div>
                </div>
                <div class="cart-item-price">
                    ${formatRupiah(subtotal)}
                </div>
            </div>
        `;
    });

    document.getElementById('totalPriceText').innerText = formatRupiah(totalPrice);
    document.getElementById('totalHargaInput').value = totalPrice;
    
    // Validate checkout button
    document.getElementById('btnComplete').disabled = false;
    calculateChange();
}

function setPaymentMethod(method, element) {
    currentPaymentMethod = method;
    document.getElementById('metodePembayaranInput').value = method;
    
    // Update active UI
    document.querySelectorAll('.pay-method-btn').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');

    // Show/hide cash input based on method
    let cashGroup = document.getElementById('cashInputGroup');
    let changeRow = document.getElementById('changeRow');
    if(method === 'Tunai') {
        cashGroup.style.display = 'block';
        changeRow.style.display = 'flex';
        calculateChange();
    } else {
        cashGroup.style.display = 'none';
        changeRow.style.display = 'none';
        document.getElementById('cashReceivedInput').value = totalPrice;
        document.getElementById('btnComplete').disabled = false; // Always enabled for non-cash if cart not empty
    }
}

function calculateChange() {
    if(currentPaymentMethod !== 'Tunai') return;
    
    let cashStr = document.getElementById('cashReceived').value;
    let cash = parseInt(cashStr) || 0;
    let change = cash - totalPrice;
    
    document.getElementById('cashReceivedInput').value = cash;
    document.getElementById('kembalianInput').value = change > 0 ? change : 0;

    if(change >= 0) {
        document.getElementById('changeText').innerText = formatRupiah(change);
        document.getElementById('changeText').style.color = '#10b981';
        if(Object.keys(cart).length > 0) {
            document.getElementById('btnComplete').disabled = false;
        }
    } else {
        document.getElementById('changeText').innerText = 'Kurang ' + formatRupiah(Math.abs(change));
        document.getElementById('changeText').style.color = '#ef4444';
        document.getElementById('btnComplete').disabled = true;
    }
}

function processCheckout() {
    if(Object.keys(cart).length === 0) return;
    
    // Prepare cart data as JSON
    document.getElementById('cartDataInput').value = JSON.stringify(cart);
    
    // Submit form
    document.getElementById('checkoutForm').submit();
}
</script>

<?php require '../layout/footer.php'; ?>
