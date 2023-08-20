<?php

namespace App\Services;

use App\DataTransferObjects\Customer as CustomerDataTransferObject;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Throwable;

class CustomerService
{
    /**
     * @throws Throwable
     */
    public function create(CustomerDataTransferObject $customerCreation): ?Customer
    {
        DB::beginTransaction();

        $customer = Customer::create($customerCreation->toArray());
        if (!$customer) {
            DB::rollBack();
            return null;
        }

        $address = $customer->address()->create($customerCreation->address->toArray());
        if (!$address) {
            DB::rollBack();
            return null;
        }

        DB::commit();

        return $customer;
    }

    /**
     * @throws Throwable
     */
    public function update(Customer $customer, CustomerDataTransferObject $customerDataTransferObject): ?Customer
    {
        DB::beginTransaction();

        if (!$customer->update($customerDataTransferObject->toArray())) {
            DB::rollBack();
            return null;
        }

        if (!$customer->address()->update($customerDataTransferObject->address->toArray())) {
            DB::rollBack();
            return null;
        }

        DB::commit();

        return $customer;
    }
}
