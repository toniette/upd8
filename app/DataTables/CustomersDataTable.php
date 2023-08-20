<?php

namespace App\DataTables;

use App\Models\Customer;
use Date;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use JC\BrDocs\BrDoc;
use Yajra\DataTables\Services\DataTable;

class CustomersDataTable extends DataTable
{
    const GENDER_MAPPING = [
        Customer::MAS_GENDER => 'Masculino',
        Customer::FEM_GENDER => 'Feminino',
        Customer::OTH_GENDER => 'Outro',
    ];
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('edit', function ($customer) {
                return
                    '<a href="'
                    . route('customers.edit', $customer->id)
                    . '" class="btn btn-sm btn-primary w-100">Editar</a>';
            })
            ->editColumn('delete', function ($customer) {
                return
                    '<button type="button" class="btn btn-sm btn-danger delete-button w-100" data-id="'
                    . $customer->id
                    .'">'
                    . 'Excluir'
                    . '</button>';
            })
            ->editColumn('birthdate', function ($customer) {
                return Date::parse($customer->birthdate)->format('d/m/Y');
            })
            ->editColumn('gender', function ($customer) {
                return self::GENDER_MAPPING[$customer->gender];
            })
            ->editColumn('document', function ($customer) {
                return BrDoc::cpf($customer->document)->format()->get();
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Customer $model): QueryBuilder
    {
        $request = $this->request();
        return $model->newQuery()
            ->with('address')
            ->when($firstName = $request->input('first_name'), function ($query) use ($firstName) {
                $query->where('first_name', 'like', "%$firstName%");
            })
            ->when($lastName = $request->input('last_name'), function ($query) use ($lastName) {
                $query->where('last_name', 'like', "%$lastName%");
            })
            ->when($document = $request->input('document'), function ($query) use ($document) {
                $query->where('document', 'like', "%$document%");
            })
            ->when($birthdate = $request->input('birthdate'), function ($query) use ($birthdate) {
                $query->where('birthdate', $birthdate);
            })
            ->when($gender = $request->input('gender'), function ($query) use ($gender) {
                return $query->where('gender', $gender);
            })
            ->when($zipCode = $request->input('address.zip_code'), function ($query) use ($zipCode) {
                $query->whereHas('address', function ($query) use ($zipCode) {
                    $query->where('zip_code', $zipCode);
                });
            })
            ->when($street = $request->input('address.street'), function ($query) use ($street) {
                $query->whereHas('address', function ($query) use ($street) {
                    $query->where('street', 'like', "%$street%");
                });
            })
            ->when($number = $request->input('address.number'), function ($query) use ($number) {
                $query->whereHas('address', function ($query) use ($number) {
                    $query->where('number', 'like', "%$number%");
                });
            })
            ->when($complement = $request->input('address.complement'), function ($query) use ($complement) {
                $query->whereHas('address', function ($query) use ($complement) {
                    $query->where('complement', 'like', "%$complement%");
                });
            })
            ->when($district = $request->input('address.district'), function ($query) use ($district) {
                $query->whereHas('address', function ($query) use ($district) {
                    $query->where('district', 'like', "%$district%");
                });
            })
            ->when($state = $request->input('address.state'), function ($query) use ($state) {
                $query->whereHas('address', function ($query) use ($state) {
                    $query->where('state',$state);
                });
            })
            ->when($city = $request->input('address.city'), function ($query) use ($city) {
                $query->whereHas('address', function ($query) use ($city) {
                    $query->where('city', $city);
                });
            });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->searching(false)
                    ->setTableId('customers')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0)
                    ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('first_name')->title('Nome'),
            Column::make('last_name')->title('Sobrenome'),
            Column::make('document')->title('Documento')->orderable(false),
            Column::make('birthdate')->title('Data de Nascimento'),
            Column::make('address.state')->title('Estado')->orderable(false),
            Column::make('address.city')->title('Cidade')->orderable(false),
            Column::make('gender')->title('Sexo'),
            Column::make([])->name('edit')->title('Editar')->orderable(false)->searchable(false),
            Column::make([])->name('delete')->title('Excluir')->orderable(false)->searchable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return __('customer.customers') . '-' . date('YmdHis');
    }
}
