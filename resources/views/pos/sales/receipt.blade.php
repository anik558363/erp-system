@extends('layouts.app')

@section('title', 'Invoice - ' . $sale->sale_no)

@section('content')
<div class="container py-4 d-flex flex-column align-items-center">

    <!-- Paper Size Selector Bar (Non-Printable) -->
    <div class="card border-0 shadow-sm p-2 mb-3 bg-white d-print-none font-mono" style="width: 100%; max-width: 320px; font-size: 11px;">
        <div class="d-flex align-items-center justify-content-between">
            <label class="fw-bold text-dark m-0"><i data-lucide="settings" class="icon-xs me-1"></i> Paper Size:</label>
            <select id="paperSizeSelect" class="form-select form-select-sm w-auto font-mono" style="font-size: 11px;" onchange="changePaperSize(this.value)">
                <option value="thermal">80mm Thermal Receipt</option>
                <option value="a4">A4 / Standard Page</option>
            </select>
        </div>
    </div>

    <!-- Card (screen look) -->
    <div class="card border shadow-sm p-3 bg-white font-mono" id="invoiceCard" style="width: 100%; max-width: 320px;">

        <!-- ================= PRINTABLE AREA (exact same design) ================= -->
        <div id="receiptContent" class="font-mono bg-white" style="font-size: 11px; line-height: 1.4;">

            <!-- Shop Header -->
            <div class="text-center mb-2 border-bottom pb-2">
                <h5 class="fw-bold m-0 text-dark" style="font-size: 15px; letter-spacing: 0.5px;">NOOR SUPER MART</h5>
                <span class="text-muted d-block" style="font-size: 9px;">B2B Wholesale & Distribution ERP</span>
                <p class="mb-0 text-secondary" style="font-size: 9px;">Mirpur-02, Dhaka, Bangladesh</p>
                <p class="mb-0 text-secondary" style="font-size: 9px;">Phone: 01700000000</p>
            </div>

            <!-- Sale Meta Info -->
            <div class="mb-2 border-bottom pb-2" style="font-size: 10px;">
                <div class="d-flex justify-content-between">
                    <span><strong>Invoice:</strong> {{ $sale->sale_no }}</span>
                    <span><strong>Date:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span><strong>Customer:</strong> {{ $sale->customer->shop_name ?? 'Walk-in Customer' }}</span>
                    <span><strong>Cashier:</strong> {{ $sale->cashier->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between text-muted" style="font-size: 9px;">
                    <span>Phone: {{ $sale->customer->phone ?? 'N/A' }}</span>
                    <span>Counter: {{ $sale->register->name ?? 'Main' }}</span>
                </div>
            </div>

            <!-- Items Table -->
            <table class="table table-sm table-borderless mb-2" style="font-size: 10px;">
                <thead class="border-bottom border-top">
                    <tr>
                        <th class="ps-0">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end pe-0">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                        <tr class="border-bottom border-light">
                            <td class="ps-0" style="max-width: 110px; word-break: break-word;">
                                {{ $item->product->name ?? 'Product' }}
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">৳{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-end pe-0 fw-bold">৳{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Calculations Summary -->
            <div class="border-top pt-2 mb-2" style="font-size: 10px;">
                <div class="d-flex justify-content-between mb-1">
                    <span>Subtotal:</span>
                    <span>৳{{ number_format($sale->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Discount:</span>
                    <span>৳{{ number_format($sale->discount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold text-dark fs-6 border-top border-bottom py-1 my-1" style="font-size: 12px;">
                    <span>TOTAL:</span>
                    <span class="text-success">৳{{ number_format($sale->total_amount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between text-muted" style="font-size: 9px;">
                    <span>Payment Method:</span>
                    <span class="fw-bold text-dark">{{ strtoupper($sale->payments->first()->method ?? 'CASH') }}</span>
                </div>
                <div class="d-flex justify-content-between text-muted" style="font-size: 9px;">
                    <span>Paid Amount:</span>
                    <span>৳{{ number_format($sale->paid_amount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between text-muted" style="font-size: 9px;">
                    <span>Change Return:</span>
                    <span>৳{{ number_format($sale->change_amount ?? ($sale->paid_amount - $sale->total_amount), 2) }}</span>
                </div>
            </div>

            <!-- Footer Thank you note -->
            <div class="text-center border-top pt-2 mb-3 text-muted" style="font-size: 9px;">
                <p class="mb-1">*** THANK YOU FOR YOUR BUSINESS ***</p>
                <p class="mb-0">Software by Noor Super Mart ERP</p>
            </div>
        </div>
        <!-- ================= END PRINTABLE AREA ================= -->

        <!-- Action Buttons (not printed) -->
        <div class="d-flex gap-2 justify-content-center d-print-none">
            <button type="button" onclick="printReceipt()" class="btn btn-dark btn-sm px-3 fw-bold" style="font-size: 11px;">
                <i data-lucide="printer" class="icon-xs me-1" style="width: 14px; height: 14px;"></i> Print Receipt
            </button>
            <a href="{{ route('pos.sales.index') }}" class="btn btn-outline-secondary btn-sm px-3" style="font-size: 11px;">
                Back to List
            </a>
        </div>
    </div>
</div>

<script>
function changePaperSize(type) {
    const card = document.getElementById('invoiceCard');
    card.style.maxWidth = (type === 'a4') ? '700px' : '320px';
    card.style.margin = '0 auto';
}

function printReceipt() {
    const type = document.getElementById('paperSizeSelect').value;
    const content = document.getElementById('receiptContent').outerHTML;

    // Copy ALL stylesheets of this page (Bootstrap, fonts, font-mono...) so print looks exactly like the screen
    const links = Array.from(document.querySelectorAll('link[rel="stylesheet"]'))
        .map(l => '<link rel="stylesheet" href="' + l.href + '">').join('');
    const styles = Array.from(document.querySelectorAll('style'))
        .map(s => '<style>' + s.textContent + '</style>').join('');

    const pageCss = (type === 'a4')
        ? '@page { size: A4; margin: 12mm; } .print-root { width: 100%; max-width: 530px; margin: 0 auto; zoom: 1.3; }'
        : '@page { size: 80mm auto; margin: 2mm; } .print-root { width: 76mm; margin: 0 auto; }';

    const overrides =
        '<style>' +
        'html, body { margin: 0 !important; padding: 0 !important; background: #fff !important; height: auto !important; overflow: visible !important; }' +
        '* { -webkit-print-color-adjust: exact; print-color-adjust: exact; }' +
        '@media print { body * { visibility: visible !important; } }' +
        pageCss +
        '</style>';

    // Hidden iframe: sidebar/layout can never make the print page blank
    const iframe = document.createElement('iframe');
    iframe.style.cssText = 'position:fixed; right:0; bottom:0; width:0; height:0; border:0; visibility:hidden;';
    document.body.appendChild(iframe);

    const d = iframe.contentWindow.document;
    d.open();
    d.write(
        '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Invoice {{ $sale->sale_no }}</title>' +
        links + styles + overrides +
        '</head><body><div class="print-root">' + content + '</div></body></html>'
    );
    d.close();

    // Wait for CSS + fonts, then print
    const waits = Array.from(d.querySelectorAll('link[rel="stylesheet"]')).map(function (l) {
        return new Promise(function (resolve) {
            if (l.sheet) { resolve(); return; }
            l.addEventListener('load', resolve);
            l.addEventListener('error', resolve);
            setTimeout(resolve, 4000);
        });
    });

    Promise.all(waits)
        .then(function () { return d.fonts ? d.fonts.ready : null; })
        .then(function () {
            setTimeout(function () {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                setTimeout(function () { document.body.removeChild(iframe); }, 1500);
            }, 250);
        });
}
</script>
@endsection
