<?php

namespace App\Http\Controllers\Api\Customer;

use App\DataTransferObjects\Customer as CustomerDataTransferObject;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\CustomerService;
use Throwable;

class CustomerController extends Controller
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CustomerResource::collection(Customer::paginate());
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(StoreCustomerRequest $request)
    {
        $customerDataTransferObject = CustomerDataTransferObject::from($request->validated());
        $customer = $this->customerService->create($customerDataTransferObject);
        return CustomerResource::make($customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return CustomerResource::make($customer);
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customerDataTransferObject = CustomerDataTransferObject::from($request->validated());
        $customer = $this->customerService->update($customer, $customerDataTransferObject);
        return CustomerResource::make($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->noContent();
    }
}
