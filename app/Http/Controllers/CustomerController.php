<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    // Customer list ebong page view
    public function index()
    {
        $customers = Customer::with(['area', 'salesman'])->latest()->paginate(10);
        $areas = Area::where('is_active', true)->get();
        $salesmen = User::role('Salesman')->get();

        return view('customers.index', compact('customers', 'areas', 'salesmen'));
    }

    // Notun Customer save kora (GPS & Photo soho)
    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'proprietor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'area_id' => 'required|exists:areas,id',
            'address' => 'required|string',
            'credit_limit' => 'required|numeric',
            'opening_due' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Auto Customer Code Generate (NSM-CUST-XXXX)
        $latestCustomer = Customer::latest('id')->first();
        $nextId = $latestCustomer ? $latestCustomer->id + 1 : 1;
        $code = 'NSM-CUST-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        // Photo upload handling
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('customers', 'public');
        }

        Customer::create([
            'code' => $code,
            'shop_name' => $request->shop_name,
            'proprietor_name' => $request->proprietor_name,
            'phone' => $request->phone,
            'area_id' => $request->area_id,
            'salesman_id' => $request->salesman_id,
            'address' => $request->address,
            'credit_limit' => $request->credit_limit,
            'opening_due' => $request->opening_due ?? 0,
            'current_due' => $request->opening_due ?? 0,
            'photo' => $photoPath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'ACTIVE',
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer successfully registered.');
    }

    // Customer update kora
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'proprietor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'area_id' => 'required|exists:areas,id',
            'address' => 'required|string',
            'credit_limit' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'shop_name' => $request->shop_name,
            'proprietor_name' => $request->proprietor_name,
            'phone' => $request->phone,
            'area_id' => $request->area_id,
            'salesman_id' => $request->salesman_id,
            'address' => $request->address,
            'credit_limit' => $request->credit_limit,
        ];

        // Jodi notun photo upload kore
        if ($request->hasFile('photo')) {
            if ($customer->photo && Storage::disk('public')->exists($customer->photo)) {
                Storage::disk('public')->delete($customer->photo);
            }
            $data['photo'] = $request->file('photo')->store('customers', 'public');
        }

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Customer successfully updated.');
    }

    // Customer delete kora
    public function destroy(Customer $customer)
    {
        if ($customer->photo && Storage::disk('public')->exists($customer->photo)) {
            Storage::disk('public')->delete($customer->photo);
        }

        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
