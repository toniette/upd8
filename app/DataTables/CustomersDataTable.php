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
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('birthdate', function ($customer) {
                return Date::parse($customer->birthdate)->format('d/m/Y');
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
        return $model->newQuery()->with('address');
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
                    ->selectStyleSingle()
                    ->pageLength(50)
                    ->pagingType('full_numbers')
                    ->languagePaginate([
                        'first' => 'Primeira',
                        'previous' => 'Anterior',
                        'next' => 'Próxima',
                        'last' => 'Última',
                    ])
                    ->languageLengthMenu(
                        '<div class="d-inline-flex mb-3" style="height: 2em;">
                                    <div class="mt-1">Exibir</div>
                                    <div class="mx-2">_MENU_</div>
                                    <div class="mt-1">registros por página</div>
                              </div>'
                    )
                    ->languageInfo(
                        '<div class="d-inline-flex mb-3" style="height: 2em;">
                                    Exibindo _START_ até _END_ de _TOTAL_ registros
                              </div>'
                    )
                    ->buttons([]);
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
