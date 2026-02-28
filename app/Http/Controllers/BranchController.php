<?php

namespace App\Http\Controllers;

use App\Models\BranchModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function select()
    {
        $branches = BranchModel::where('is_active', true)->get();

        return view('customer.branches.select', compact('branches'));
    }

    public function storeSelected(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        // Simpan ke session (utama untuk guest & sementara)
        session(['selected_branch_id' => $request->branch_id]);

        // Optional: simpan ke profile user kalau login permanen
        if (Auth::check()) {
            Auth::user()->update(['default_branch_id' => $request->branch_id]);
        }

        return redirect()->route('checkout')
            ->with('success', 'Cabang berhasil dipilih! Silakan lanjut checkout.');
    }
}
