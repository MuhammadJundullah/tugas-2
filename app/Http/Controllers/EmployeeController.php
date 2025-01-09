<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input dari body JSON
        $filters = $request->all();

        $query = Employee::with('division');

        // Filter berdasarkan nama
        if (isset($filters['name']) && $filters['name']) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        // Filter berdasarkan division_id
        if (isset($filters['division_id']) && $filters['division_id']) {
            $query->where('division_id', $filters['division_id']);
        }

        // Ambil data dengan pagination
        $employees = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil diambil.',
            'data' => [
                'employees' => $employees->items(),
            ],
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        // Validasi request menggunakan Validator
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'division' => 'required|uuid|exists:divisions,id',
            'position' => 'required|string|max:255',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Upload file gambar (jika ada)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('employee_images', 'public');
        }

        // Buat data karyawan
        $employee = Employee::create([
            'id' => Str::uuid(),
            'image' => $imagePath ? asset('storage/' . $imagePath) : null,
            'name' => $request->name,
            'phone' => $request->phone,
            'division_id' => $request->division,
            'position' => $request->position,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil ditambahkan.',
            'data' => $employee,
        ], 201);
    }

    public function update(Request $request, $uuid)
    {
        // Validasi request
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'division' => 'required|uuid|exists:divisions,id',
            'position' => 'required|string|max:255',
        ]);

        // Cari data karyawan
        $employee = Employee::findOrFail($uuid);

        // Upload file gambar baru (jika ada)
        if ($request->hasFile('image')) {
            // Hapus gambar lama (jika ada)
            if ($employee->image) {
                $oldImagePath = str_replace(asset('storage/'), '', $employee->image);
                Storage::disk('public')->delete($oldImagePath);
            }

            // Simpan gambar baru
            $imagePath = $request->file('image')->store('employee_images', 'public');
            $employee->image = asset('storage/' . $imagePath);
        }

        // Update data karyawan
        $employee->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'division_id' => $request->division,
            'position' => $request->position,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil diperbarui.',
            'data' => $employee,
        ]);
    }

    public function destroy($uuid)
    {
        // Cari data karyawan
        $employee = Employee::findOrFail($uuid);

        // Hapus gambar jika ada
        if ($employee->image) {
            $imagePath = str_replace(asset('storage/'), '', $employee->image);
            Storage::disk('public')->delete($imagePath);
        }

        // Hapus data karyawan
        $employee->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil dihapus.',
        ]);
    }
}
