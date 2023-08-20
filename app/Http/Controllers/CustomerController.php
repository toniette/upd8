<?php

namespace App\Http\Controllers;

use App\DataTables\CustomersDataTable;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CustomersDataTable $dataTable)
    {
        return $dataTable->render('customers.index', [
            'availableGenders' => Customer::AVAILABLE_GENDERS
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create', [
            'availableGenders' => Customer::AVAILABLE_GENDERS
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', [
            'availableGenders' => Customer::AVAILABLE_GENDERS,
            'customer' => $customer
        ]);
    }
}
