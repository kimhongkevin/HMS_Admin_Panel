<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; }
        .header { overflow: hidden; margin-bottom: 20px; }
        .logo { float: left; }
        .company-info { float: right; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; }
        th { text-align: left; background-color: #f8f9fa; }
        .totals { margin-top: 30px; float: right; width: 40%; }
        .totals-row { overflow: hidden; margin-bottom: 5px; }
        .label { float: left; font-weight: bold; }
        .value { float: right; }
        .status { 
            padding: 5px 10px; color: white; border-radius: 4px; display: inline-block;
            background-color: {{ $invoice->status == 'paid' ? '#10b981' : '#f59e0b' }};
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <h2>HOSPITAL NAME</h2>
            <div class="status">{{ ucfirst($invoice->status) }}</div>
        </div>
        <div class="company-info">
            <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
            <strong>Date:</strong> {{ $invoice->invoice_date->format('Y-m-d') }}<br>
            <strong>Due Date:</strong> {{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '-' }}
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Bill To:</strong><br>
        {{ $invoice->patient->name }}<br>
        {{ $invoice->patient->email }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Qty</th>
                <th style="text-align: right;">Price</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td style="text-align: right;">{{ $item->quantity }}</td>
                <td style="text-align: right;">${{ number_format($item->unit_price, 2) }}</td>
                <td style="text-align: right;">${{ number_format($item->amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="totals-row">
            <span class="label">Subtotal:</span>
            <span class="value">${{ number_format($invoice->subtotal, 2) }}</span>
        </div>
        <div class="totals-row">
            <span class="label">Tax:</span>
            <span class="value">${{ number_format($invoice->tax, 2) }}</span>
        </div>
        <div class="totals-row">
            <span class="label">Discount:</span>
            <span class="value">-${{ number_format($invoice->discount, 2) }}</span>
        </div>
        <div class="totals-row" style="font-size: 1.2em; margin-top: 10px;">
            <span class="label">Total:</span>
            <span class="value">${{ number_format($invoice->total, 2) }}</span>
        </div>
    </div>
</body>
</html>