<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Employee;
use App\Models\Tenant\Payroll;
use App\Models\Tenant\Account;
use App\Models\Tenant\Journal;
use App\Models\Tenant\JournalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    /**
     * Tampilkan riwayat payroll
     */
    public function index()
    {
        $payrolls = Payroll::with('employee')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        return view('tenant.hr.payroll.index', compact('payrolls'));
    }

    /**
     * Form untuk generate payroll baru
     */
    public function create()
    {
        $employees = Employee::where('is_active', true)->get();
        return view('tenant.hr.payroll.create', compact('employees'));
    }

    /**
     * Simpan payroll (Draft)
     */
    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year'  => 'required|integer',
            'payrolls' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->payrolls as $employeeId => $data) {
                if (!isset($data['selected'])) continue;

                // Cek jika sudah ada payroll untuk bulan/tahun ini
                $exists = Payroll::where('employee_id', $employeeId)
                    ->where('month', $request->month)
                    ->where('year', $request->year)
                    ->exists();
                
                if ($exists) continue;

                Payroll::create([
                    'employee_id' => $employeeId,
                    'month'       => $request->month,
                    'year'        => $request->year,
                    'basic_salary'=> $data['basic_salary'],
                    'allowance'   => $data['allowance'] ?? 0,
                    'deduction'   => $data['deduction'] ?? 0,
                    'net_salary'  => ($data['basic_salary'] + ($data['allowance'] ?? 0)) - ($data['deduction'] ?? 0),
                    'status'      => 'draft',
                    'created_by'  => Auth::id(),
                ]);
            }
            DB::commit();
            return redirect()->route('tenant.hr.payroll.index', tenant('id'))
                ->with('success', 'Draft penggajian berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat payroll: ' . $e->getMessage());
        }
    }

    /**
     * Detail Payroll & Slip
     */
    public function show(Payroll $payroll)
    {
        return view('tenant.hr.payroll.show', compact('payroll'));
    }

    /**
     * Download Slip Gaji (PDF)
     */
    public function downloadSlip(Payroll $payroll)
    {
        $payroll->load('employee');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tenant.hr.payroll.pdf-slip', compact('payroll'))
                  ->setPaper('a5', 'landscape');
        
        return $pdf->download("Slip_Gaji_{$payroll->employee->name}_{$payroll->month}_{$payroll->year}.pdf");
    }

    /**
     * Proses Pembayaran & Penjurnalan Otomatis
     */
    public function processPayment(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return back()->with('error', 'Payroll ini sudah dibayar.');
        }

        DB::beginTransaction();
        try {
            // 1. Update status payroll
            $payroll->update([
                'status' => 'paid',
                'payment_date' => now(),
            ]);

            // 2. Integrasi Akuntansi (Auto-Journal)
            // Menggunakan kode standar: 5103 (Beban Gaji), 1101 (Kas)
            $expenseAccount = Account::where('code', '5103')->first();
            $cashAccount = Account::where('code', '1101')->first(); 

            if (!$expenseAccount || !$cashAccount) {
                // Fallback pencarian parsial jika kode eksak tidak ketemu
                $expenseAccount = $expenseAccount ?? Account::where('name', 'like', '%Beban Gaji%')->first();
                $cashAccount = $cashAccount ?? Account::where('name', 'like', '%Kas%')->first();
            }

            if ($expenseAccount && $cashAccount) {
                $journal = Journal::create([
                    'date' => now(),
                    'reference' => 'PAY-' . str_pad($payroll->id, 5, '0', STR_PAD_LEFT),
                    'description' => "Pembayaran Gaji: {$payroll->employee->name} ({$payroll->month}/{$payroll->year})",
                    'status' => 'posted',
                ]);

                // Debit: Beban Gaji
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $expenseAccount->id,
                    'debit' => $payroll->net_salary,
                    'credit' => 0,
                ]);

                // Kredit: Kas
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $cashAccount->id,
                    'debit' => 0,
                    'credit' => $payroll->net_salary,
                ]);

                $payroll->update(['journal_id' => $journal->id]);
            }

            DB::commit();
            return back()->with('success', 'Gaji berhasil dibayarkan dan jurnal otomatis telah dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
}
