<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $admin = User::where('role', 'admin')->first();
        $staff = User::where('role', 'staff')->first();
        $createdBy = $admin ? $admin->id : ($staff ? $staff->id : 1);

        if ($patients->isEmpty()) {
            $this->command->warn('Skipping InvoiceSeeder: no patients found.');
            return;
        }

        // Create 10 sample invoices
        for ($i = 1; $i <= 10; $i++) {
            $patient = $patients->random();
            $invoiceDate = Carbon::now()->subDays(rand(1, 30));
            $dueDate = $invoiceDate->copy()->addDays(30);

            $status = ['pending', 'paid', 'cancelled'][rand(0, 2)];
            if ($invoiceDate->diffInDays(now()) > 15) {
                $status = 'paid'; // Older invoices are more likely to be paid
            }

            $invoice = Invoice::create([
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'patient_id' => $patient->id,
                'created_by' => $createdBy,
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'subtotal' => 0, // Will be calculated
                'tax' => 0,
                'discount' => 0,
                'total' => 0, // Will be calculated
                'status' => $status,
                'notes' => 'Invoice for medical services rendered',
                'created_at' => $invoiceDate,
                'updated_at' => now(),
            ]);

            // Add 2-5 items per invoice
            $itemCount = rand(2, 5);
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $services = [
                    ['description' => 'General Consultation', 'unit_price' => 100],
                    ['description' => 'Blood Test (CBC)', 'unit_price' => 50],
                    ['description' => 'X-Ray', 'unit_price' => 75],
                    ['description' => 'Paracetamol (10 tablets)', 'unit_price' => 5],
                    ['description' => 'Antibiotics Course', 'unit_price' => 15],
                    ['description' => 'ECG Test', 'unit_price' => 60],
                    ['description' => 'General Ward (per day)', 'unit_price' => 50],
                ];

                $service = $services[rand(0, count($services) - 1)];
                $quantity = rand(1, 3);
                $unitPrice = $service['unit_price'];
                $amount = $quantity * $unitPrice;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $service['description'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'amount' => $amount,
                ]);

                $subtotal += $amount;
            }

            // Calculate totals
            $tax = $subtotal * 0.1; // 10% tax
            $discount = rand(0, 1) ? rand(10, 50) : 0;
            $total = $subtotal + $tax - $discount;

            $invoice->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
            ]);
        }
    }
}
