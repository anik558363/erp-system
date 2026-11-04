// Initialize Lucide Icons
lucide.createIcons();

// Mobile Sidebar Toggle Logic (Common for all pages)
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('sidebar');
const sidebarBackdrop = document.getElementById('sidebarBackdrop');

if (sidebarToggle) {
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        sidebarBackdrop.classList.toggle('show');
    });

    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', () => {
            sidebar.classList.remove('show');
            sidebarBackdrop.classList.remove('show');
        });
    }
}


// Fast Order Modal Logic and Step Navigation

let currentStep = 2; // Starting directly at Step 2 (Products) like in your screenshot

function changeStep(direction) {
    currentStep += direction;
    if (currentStep < 1) currentStep = 1;
    if (currentStep > 3) currentStep = 3;

    updateStepView();
}

function updateStepView() {
    document.getElementById('orderStep1').classList.add('d-none');
    document.getElementById('orderStep2').classList.add('d-none');
    document.getElementById('orderStep3').classList.add('d-none');

    document.getElementById(`orderStep${currentStep}`).classList.remove('d-none');

    // Indicators
    document.getElementById('stepIndicator1').className = currentStep === 1 ? 'badge bg-emerald px-2 py-1' : 'badge bg-secondary px-2 py-1';
    document.getElementById('stepIndicator2').className = currentStep === 2 ? 'badge bg-emerald px-2 py-1' : 'badge bg-secondary px-2 py-1';
    document.getElementById('stepIndicator3').className = currentStep === 3 ? 'badge bg-emerald px-2 py-1' : 'badge bg-secondary px-2 py-1';

    // Buttons
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    if (currentStep === 1) {
        prevBtn.classList.add('d-none');
        nextBtn.classList.remove('d-none');
        nextBtn.innerText = 'Next: Products →';
        submitBtn.classList.add('d-none');
    } else if (currentStep === 2) {
        prevBtn.classList.remove('d-none');
        nextBtn.classList.remove('d-none');
        nextBtn.innerText = 'Next: Review Order (1) →';
        submitBtn.classList.add('d-none');
    } else if (currentStep === 3) {
        prevBtn.classList.remove('d-none');
        nextBtn.classList.add('d-none');
        submitBtn.classList.remove('d-none');
    }
}

function submitOrder() {
    // Hide steps and show success alert
    document.getElementById('orderStep3').classList.add('d-none');
    document.getElementById('orderSuccessAlert').classList.remove('d-none');
    document.getElementById('prevBtn').classList.add('d-none');
    document.getElementById('submitBtn').classList.add('d-none');
    document.getElementById('modalTotalText').classList.add('d-none');

    // Auto close modal after 2 seconds
    setTimeout(() => {
        let modalEl = document.getElementById('fastOrderModal');
        let modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
        location.reload(); // Refresh to reset state
    }, 2000);
}



// Mock Database for Salesmen
const salesmenData = [
    {
        name: "Rafiqul Islam",
        id: "user-sales-1",
        route: "Dhanmondi & Mohammadpur",
        phone: "01811-223344",
        joined: "2021-05-10",
        email: "rafiqul@noorsupermart.com",
        avatar: "R",
        avatarColor: "bg-emerald",
        stores: 0,
        orders: 0,
        ordersAmount: "৳0",
        collection: "৳0",
        sales: "৳0",
        salesCount: "0 orders total",
        returns: "৳0",
        netSales: "৳0",
        due: "৳0",
        dueShops: "0 shops due",
        tabsData: [
            { name: "Due Summary by Shop", count: 0, type: "due", rows: [] },
            { name: "Assigned Customer Directory", count: 0, type: "customers", rows: [] },
            { name: "Order History", count: 0, type: "orders", rows: [] },
            { name: "Collection History", count: 0, type: "collections", rows: [] },
            { name: "Return History", count: 0, type: "returns", rows: [] }
        ]
    },
    {
        name: "Tariqul Alam",
        id: "user-sales-2",
        route: "Mirpur & Uttara",
        phone: "01622-445566",
        joined: "2022-09-10",
        email: "tariqul@noorsupermart.com",
        avatar: "T",
        avatarColor: "bg-primary",
        stores: 1,
        orders: 8,
        ordersAmount: "৳20",
        collection: "৳0",
        sales: "৳32",
        salesCount: "9 orders total",
        returns: "৳0",
        netSales: "৳32",
        due: "৳0",
        dueShops: "1 shops due",
        tabsData: [
            {
                name: "Due Summary by Shop",
                count: 1,
                type: "due",
                rows: [
                    { code: "NSM-CUST-1001", shop: "test", proprietor: "test", area: "Dhanmondi", limit: "৳50,000", lastOrder: "2026-11-03", due: "৳0" }
                ]
            },
            {
                name: "Assigned Customer Directory",
                count: 1,
                type: "customers",
                rows: [
                    { code: "NSM-CUST-1001", shop: "test", proprietor: "test", phone: "01931654590", area: "Dhanmondi", status: "ACTIVE" }
                ]
            },
            {
                name: "Order History",
                count: 9,
                type: "orders",
                rows: [
                    { orderNo: "ORD-9021", date: "2026-11-03", shop: "test", items: "test (1 Bag)", total: "৳2", status: "DELIVERED" },
                    { orderNo: "ORD-9020", date: "2026-11-02", shop: "test", items: "fasdf (5 Box)", total: "৳30", status: "DELIVERED" }
                ]
            },
            { name: "Collection History", count: 0, type: "collections", rows: [] },
            { name: "Return History", count: 0, type: "returns", rows: [] }
        ]
    },
    {
        name: "Md. Mizanur Rahman (মিজান)",
        id: "user-sales-mizan",
        route: "Gulshan & Badda Route",
        phone: "01712-445588",
        joined: "2022-01-15",
        email: "mizan@noorsupermart.com",
        avatar: "M",
        avatarColor: "bg-warning",
        stores: 0,
        orders: 0,
        ordersAmount: "৳0",
        collection: "৳0",
        sales: "৳0",
        salesCount: "0 orders total",
        returns: "৳0",
        netSales: "৳0",
        due: "৳0",
        dueShops: "0 shops due",
        tabsData: [
            { name: "Due Summary by Shop", count: 0, type: "due", rows: [] },
            { name: "Assigned Customer Directory", count: 0, type: "customers", rows: [] },
            { name: "Order History", count: 0, type: "orders", rows: [] },
            { name: "Collection History", count: 0, type: "collections", rows: [] },
            { name: "Return History", count: 0, type: "returns", rows: [] }
        ]
    }
];

let currentSalesmanIndex = 1; // Default to Tariqul Alam
let currentActiveTab = 0;

function selectSalesman(index, btnElement) {
    currentSalesmanIndex = index;

    // Update Salesman Buttons Active State
    let btns = document.querySelectorAll('.salesman-btn');
    btns.forEach(b => {
        b.classList.remove('btn-dark', 'text-white', 'shadow-xs', 'active-salesman');
        b.classList.add('btn-light', 'border', 'text-secondary');
    });
    btnElement.classList.remove('btn-light', 'border', 'text-secondary');
    btnElement.classList.add('btn-dark', 'text-white', 'shadow-xs', 'active-salesman');

    renderSalesmanData();
}

function renderSalesmanData() {
    const data = salesmenData[currentSalesmanIndex];

    // Render Profile Card
    document.getElementById('salesmanAvatar').innerText = data.name.charAt(0);
    document.getElementById('salesmanAvatar').className = `${data.avatarColor} text-white fw-bold rounded d-flex align-items-center justify-content-center font-mono`;
    document.getElementById('salesmanName').innerText = data.name;
    document.getElementById('salesmanId').innerText = `ID: ${data.id}`;
    document.getElementById('salesmanPhone').innerText = data.phone;
    document.getElementById('salesmanRoute').innerText = data.route;
    document.getElementById('salesmanJoined').innerText = `Joined: ${data.joined} | Email: ${data.email}`;
    document.getElementById('salesmanStores').innerHTML = `${data.stores} <span class="fw-normal text-muted fs-6">Shops</span>`;

    // Render Metrics
    document.getElementById('metricOrders').innerText = data.orders;
    document.getElementById('metricOrdersAmount').innerText = data.ordersAmount;
    document.getElementById('metricCollection').innerText = data.collection;
    document.getElementById('metricSales').innerText = data.sales;
    document.getElementById('metricSalesCount').innerText = data.salesCount;
    document.getElementById('metricReturns').innerText = data.returns;
    document.getElementById('metricNetSales').innerText = data.netSales;
    document.getElementById('metricDue').innerText = data.due;
    document.getElementById('metricDueShops').innerText = data.dueShops;

    // Render Tabs & Content
    renderTabs();
    renderTableContent();
}

function renderTabs() {
    const data = salesmenData[currentSalesmanIndex];
    const container = document.getElementById('tabContainer');
    container.innerHTML = '';

    data.tabsData.forEach((tab, idx) => {
        let isActive = idx === currentActiveTab;
        let btnClass = isActive ? 'btn-dark text-white fw-semibold bg-dark shadow-xs' : 'btn-light border text-secondary fw-medium';

        container.innerHTML += `
                    <button class="salesman-tab btn btn-sm ${btnClass} px-3 py-2" onclick="switchTab(${idx}, this)">
                        ${tab.name} (${tab.count})
                    </button>
                `;
    });
}

function switchTab(index, btnElement) {
    currentActiveTab = index;

    let tabs = document.querySelectorAll('.salesman-tab');
    tabs.forEach(t => {
        t.classList.remove('btn-dark', 'text-white', 'fw-semibold', 'bg-dark', 'shadow-xs');
        t.classList.add('btn-light', 'border', 'text-secondary', 'fw-medium');
    });

    btnElement.classList.remove('btn-light', 'border', 'text-secondary', 'fw-medium');
    btnElement.classList.add('btn-dark', 'text-white', 'fw-semibold', 'bg-dark', 'shadow-xs');

    renderTableContent();
}

function renderTableContent() {
    const data = salesmenData[currentSalesmanIndex];
    const tabObj = data.tabsData[currentActiveTab];
    const container = document.getElementById('tableContainer');

    if (tabObj.rows.length === 0) {
        container.innerHTML = `
                    <div class="card-body p-5 text-center text-muted small">
                        No data found.
                    </div>
                `;
        return;
    }

    // Render based on tab type
    if (tabObj.type === 'due') {
        let rowsHtml = tabObj.rows.map(r => `
                    <tr>
                        <td class="fw-semibold font-mono text-dark">${r.code}</td>
                        <td class="fw-medium text-dark">${r.shop}</td>
                        <td>${r.proprietor}</td>
                        <td>${r.area}</td>
                        <td class="font-mono">${r.limit}</td>
                        <td class="text-muted">${r.lastOrder}</td>
                        <td class="text-end fw-bold text-danger font-mono">${r.due}</td>
                    </tr>
                `).join('');

        container.innerHTML = `
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Customer Code</th>
                                    <th>Shop Name</th>
                                    <th>Proprietor</th>
                                    <th>Area</th>
                                    <th>Credit Limit</th>
                                    <th>Last Order</th>
                                    <th class="text-end">Current Due (৳)</th>
                                </tr>
                            </thead>
                            <tbody>${rowsHtml}</tbody>
                        </table>
                    </div>
                `;
    } else if (tabObj.type === 'customers') {
        let rowsHtml = tabObj.rows.map(r => `
                    <tr>
                        <td class="fw-semibold font-mono text-dark">${r.code}</td>
                        <td class="fw-medium text-dark">${r.shop}</td>
                        <td>${r.proprietor}</td>
                        <td>${r.phone}</td>
                        <td>${r.area}</td>
                        <td class="text-center"><span class="badge bg-emerald-subtle text-emerald border">${r.status}</span></td>
                    </tr>
                `).join('');

        container.innerHTML = `
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Customer Code</th>
                                    <th>Shop Name</th>
                                    <th>Proprietor</th>
                                    <th>Mobile Phone</th>
                                    <th>Area</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>${rowsHtml}</tbody>
                        </table>
                    </div>
                `;
    } else if (tabObj.type === 'orders') {
        let rowsHtml = tabObj.rows.map(r => `
                    <tr>
                        <td class="fw-semibold font-mono text-dark">${r.orderNo}</td>
                        <td class="text-muted">${r.date}</td>
                        <td class="fw-medium text-dark">${r.shop}</td>
                        <td><span class="badge bg-light text-dark border">${r.items}</span></td>
                        <td class="text-end fw-bold font-mono">${r.total}</td>
                        <td class="text-center"><span class="badge bg-success-subtle text-success border">${r.status}</span></td>
                    </tr>
                `).join('');

        container.innerHTML = `
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 custom-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Shop Name</th>
                                    <th>Items Summary</th>
                                    <th class="text-end">Total (৳)</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>${rowsHtml}</tbody>
                        </table>
                    </div>
                `;
    } else {
        container.innerHTML = `
                    <div class="card-body p-5 text-center text-muted small">
                        No data found.
                    </div>
                `;
    }
}

// Initial Load
window.onload = function () {
    renderSalesmanData();
};


// Approval Action Highlight Logic


document.addEventListener("DOMContentLoaded", function () {
    const actionApprove = document.getElementById('actionApprove');
    const actionHold = document.getElementById('actionHold');
    const actionCancel = document.getElementById('actionCancel');

    function updateApprovalAction() {
        // Remove border highlights from all cards
        actionApprove.closest('.col-md-4').querySelector('.p-3').className = "p-3 border rounded bg-white";
        actionHold.closest('.col-md-4').querySelector('.p-3').className = "p-3 border rounded bg-white";
        actionCancel.closest('.col-md-4').querySelector('.p-3').className = "p-3 border rounded bg-white";

        // Add active border highlight to selected card
        if (actionApprove.checked) {
            actionApprove.closest('.col-md-4').querySelector('.p-3').className = "p-3 border rounded bg-white border-success border-2";
        } else if (actionHold.checked) {
            actionHold.closest('.col-md-4').querySelector('.p-3').className = "p-3 border rounded bg-white border-warning border-2";
        } else if (actionCancel.checked) {
            actionCancel.closest('.col-md-4').querySelector('.p-3').className = "p-3 border rounded bg-white border-danger border-2";
        }
    }

    if (actionApprove && actionHold && actionCancel) {
        actionApprove.addEventListener('change', updateApprovalAction);
        actionHold.addEventListener('change', updateApprovalAction);
        actionCancel.addEventListener('change', updateApprovalAction);
    }
});


// Delivery Page Logic

const pendingDeliveries = [
    {
        orderId: "NSM-ORD-261103-660",
        custId: "NSM-CUST-1001",
        status: "DISPATCHED",
        shopName: "test",
        proprietor: "test",
        phone: "01931654590",
        address: "test",
        area: "Dhanmondi",
        hasGps: false,
        items: [
            { name: "test", sku: "AD", rate: "৳2/Bag", qty: "1 Bag", total: "৳2" }
        ],
        grandTotal: "৳2",
        prevDue: "৳0",
        cashToCollect: "৳2",
        paymentStatus: "Cash on Delivery (বকেয়া সংগ্রহ)",
        isCompleted: false
    }
];

const completedDeliveries = [
    {
        orderId: "NSM-ORD-261103-658",
        custId: "NSM-CUST-1001",
        status: "COMPLETED",
        shopName: "test",
        proprietor: "test",
        phone: "01931654590",
        address: "test",
        area: "Dhanmondi",
        hasGps: false,
        items: [
            { name: "test", sku: "AD", rate: "৳2/Bag", qty: "1 Bag", total: "৳2" }
        ],
        grandTotal: "৳2",
        prevDue: "৳0",
        totalCollected: "৳2",
        paymentBadge: "Fully Paid (পরিশোধিত)",
        isCompleted: true
    },
    {
        orderId: "NSM-ORD-261103-178",
        custId: "NSM-CUST-1001",
        status: "COMPLETED",
        shopName: "test",
        proprietor: "test",
        phone: "01931654590",
        address: "test",
        area: "Dhanmondi",
        hasGps: false,
        items: [
            { name: "test", sku: "AD", rate: "৳2/Bag", qty: "2 Bag", total: "৳4" }
        ],
        grandTotal: "৳4",
        prevDue: "৳0",
        totalCollected: "৳4",
        paymentBadge: "Fully Paid (পরিশোধিত)",
        isCompleted: true
    }
];

function switchDeliveryTab(type, btnElement) {
    document.getElementById('tabPending').className = "btn btn-light border text-secondary btn-sm px-3 py-2 fw-medium";
    document.getElementById('tabCompleted').className = "btn btn-light border text-secondary btn-sm px-3 py-2 fw-medium";

    btnElement.className = "btn btn-dark btn-sm px-3 py-2 fw-medium shadow-xs";

    renderDeliveries(type === 'pending' ? pendingDeliveries : completedDeliveries);
}

function renderDeliveries(list) {
    const container = document.getElementById('deliveryContentContainer');

    if (list.length === 0) {
        container.innerHTML = `
                    <div class="col-12">
                        <div class="card border-0 shadow-xs">
                            <div class="card-body p-5 text-center text-muted small">
                                No delivery records found.
                            </div>
                        </div>
                    </div>
                `;
        return;
    }

    container.innerHTML = list.map(item => `
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border-0 shadow-xs h-100">
                        <div class="card-body p-3 p-md-4 d-flex flex-column justify-content-between">
                           <div>
                                <!-- Order Header Badge -->
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <span class="font-mono fw-bold text-dark" style="font-size: 11px;">${item.orderId} <small class="text-muted fw-normal">ID: ${item.custId}</small></span>
                                    <span class="badge ${item.isCompleted ? 'bg-emerald-subtle text-emerald border-emerald-subtle' : 'bg-warning-subtle text-warning-emphasis'} border font-mono px-2 py-1" style="font-size: 10px;">${item.status}</span>
                                </div>

                                <!-- Shop Profile Row -->
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=100&auto=format&fit=crop&q=60" alt="Shop" class="rounded border" style="width: 45px; height: 45px; object-fit: cover;">
                                        <div>
                                            <h4 class="h6 fw-bold text-dark mb-0">${item.shopName}</h4>
                                            <small class="text-muted" style="font-size: 10px;">Prop: ${item.proprietor}</small>
                                        </div>
                                    </div>
                                    <a href="tel:${item.phone}" class="btn btn-emerald btn-sm px-2 py-1 text-white bg-emerald d-flex align-items-center gap-1" style="font-size: 10px;">
                                        <i data-lucide="phone" class="icon-xs"></i> কল করুন
                                    </a>
                                </div>

                                <!-- Address Box -->
                                <div class="bg-light p-2 rounded border mb-2 small" style="font-size: 11px;">
                                    <div class="text-dark mb-1"><strong class="text-secondary">ঠিকানা:</strong> ${item.address}</div>
                                    <div class="text-muted">গ্রাম/এলাকা: ${item.area}</div>
                                </div>

                                <!-- GPS Warning Box -->
                                <div class="p-2 rounded border border-warning bg-warning-subtle mb-2 text-warning-emphasis small d-flex align-items-center justify-content-between" style="font-size: 10px;">
                                    <div class="d-flex align-items-center gap-1">
                                        <i data-lucide="alert-circle" class="icon-xs"></i>
                                        <span>${item.hasGps ? 'GPS সংরক্ষিত আছে' : 'GPS সংরক্ষিত নেই'}</span>
                                    </div>
                                    <a href="#" class="text-decoration-none fw-semibold text-primary" style="font-size: 10px;">Maps &rarr;</a>
                                </div>

                                <!-- Ordered Items List Box -->
                                <div class="border rounded p-2 mb-2 bg-white" style="font-size: 11px;">
                                    <div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom">
                                        <span class="fw-bold text-muted">অর্ডারকৃত মালামাল (${item.items.length})</span>
                                        <span class="text-muted font-mono">qty</span>
                                    </div>
                                    ${item.items.map(i => `
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <div class="fw-bold text-dark">${i.name}</div>
                                                <small class="text-muted font-mono" style="font-size: 9px;">SKU: ${i.sku} &bull; ${i.rate}</small>
                                            </div>
                                            <div class="text-end font-mono">
                                                <span class="fw-bold text-dark">${i.qty}</span><br>
                                                <span class="text-muted" style="font-size: 10px;">${i.total}</span>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>

                                <!-- Financial Totals Box -->
                                <div class="row g-1 text-center mb-2 font-mono" style="font-size: 10px;">
                                    <div class="col-6 bg-light p-1.5 rounded border">
                                        <span class="text-muted d-block" style="font-size: 8px;">Order Grand Total</span>
                                        <span class="fw-bold text-dark">${item.grandTotal}</span>
                                    </div>
                                    <div class="col-6 bg-light p-1.5 rounded border">
                                        <span class="text-muted d-block" style="font-size: 8px;">Previous Due (পূর্বের বকেয়া)</span>
                                        <span class="fw-bold text-danger">${item.prevDue}</span>
                                    </div>
                                </div>

                                <!-- Total Payable Exposure / Cash Collected Box -->
                                <div class="p-2.5 ${item.isCompleted ? 'bg-light border' : 'bg-emerald-subtle border-emerald-subtle'} rounded mb-2 d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fw-bold text-dark d-block" style="font-size: 10px;">${item.isCompleted ? 'Total Payable Exposure' : 'Cash to Collect'}</span>
                                        <small class="text-muted" style="font-size: 9px;">${item.isCompleted ? 'Current Order + Previous Due' : 'Order + Due'}</small>
                                    </div>
                                    <span class="h5 fw-bold ${item.isCompleted ? 'text-dark' : 'text-emerald'} font-mono mb-0">${item.isCompleted ? item.totalCollected : item.cashToCollect}</span>
                                </div>

                                <!-- Payment Status Row -->
                                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 11px;">
                                    <span class="text-muted">Payment Status:</span>
                                    <span class="badge ${item.isCompleted ? 'bg-success-subtle text-success border-success-subtle' : 'bg-warning-subtle text-warning-emphasis'} border px-2 py-0.5 font-mono" style="font-size: 9px;">${item.isCompleted ? item.paymentBadge : item.paymentStatus}</span>
                                </div>
                           </div>

                            <!-- Action Buttons Footer -->
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                               <button class="btn btn-sm btn-light border text-secondary p-1"
                                                    data-bs-toggle="modal" data-bs-target="#cashMemoModal"> <svg
                                                        xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-file-text w-4 h-4" aria-hidden="true">
                                                        <path
                                                            d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z">
                                                        </path>
                                                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                        <path d="M10 9H8"></path>
                                                        <path d="M16 13H8"></path>
                                                        <path d="M16 17H8"></path>
                                                    </svg></button>
                                ${item.isCompleted ? `
                                    <span class="text-muted font-mono" style="font-size: 10px;">Total Collected: <strong class="text-success">${item.totalCollected}</strong></span>
                                ` : `
                                   <!-- Deliver Button triggers Delivery Modal -->
                                <button class="btn btn-emerald btn-sm px-3 py-1 text-white bg-emerald ms-auto d-flex align-items-center gap-1 fw-semibold" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#deliveryModal">
                                    <i data-lucide="check-circle" class="icon-xs"></i> Deliver
                                </button>
                                `}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');

    lucide.createIcons();
}

window.onload = function () {
    renderDeliveries(completedDeliveries); // Default viewing completed tab to match your screenshot
    document.getElementById('tabCompleted').className = "btn btn-dark btn-sm px-3 py-2 fw-medium shadow-xs";
    document.getElementById('tabPending').className = "btn btn-light border text-secondary btn-sm px-3 py-2 fw-medium";
};
