<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('table_number')->paginate(10);
        return view('tables.index', compact('tables'));
    }

    /**
     * Show the form for creating a new table.
     */
    public function create()
    {
        return view('tables.create');
    }

    /**
     * Store a newly created table in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|unique:tables,table_number',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,reserved'
        ]);

        $table = Table::create($request->all());

        /** @var \App\Models\User $user */
        $user = auth()->user();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'create table',
            'description' => "Menambahkan meja nomor {$table->table_number}",
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('tables.index')
            ->with('success', 'Meja berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified table.
     */
    public function edit(Table $table)
    {
        return view('tables.edit', compact('table'));
    }

    /**
     * Update the specified table in storage.
     */
    public function update(Request $request, Table $table)
    {
        // FIX: Validasi disamakan menggunakan bahasa Inggris sesuai isi values select HTML
        $request->validate([
            'table_number' => 'required|string|unique:tables,table_number,' . $table->id,
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,reserved'
        ]);

        $oldNumber = $table->table_number;
        $table->update($request->all());

        /** @var \App\Models\User $user */
        $user = auth()->user();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'update table',
            'description' => "Mengubah meja {$oldNumber} menjadi {$table->table_number}",
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('tables.index')
            ->with('success', 'Meja berhasil diperbarui.');
    }

    /**
     * Remove the specified table from storage.
     */
    public function destroy(Table $table, Request $request)
    {
        $tableNumber = $table->table_number;
        $table->delete();

        /** @var \App\Models\User $user */
        $user = auth()->user();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'delete table',
            'description' => "Menghapus meja nomor {$tableNumber}",
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('tables.index')
            ->with('success', 'Meja berhasil dihapus.');
    }
}
