@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-2">
                <a class="btn btn-success w-100" href="{{ route('customers.create') }}">
                    Cadastrar Cliente
                </a>
            </div>
            <div class="col-8"></div>
            <div class="col-2">
                <button class="btn btn-primary mb-4 w-100"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#filters"
                        aria-expanded="false"
                        id="filters-collapse-trigger"
                        data-collapsed="1"
                        aria-controls="filters">
                    Exibir Filtros
                </button>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">{{ __('customer.customers') }}</div>
                    <div class="card-body bg-white">
                        <div class="row g-3 collapse multi-collapse mb-5" id="filters">
                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-3">
                                        <label for="first_name" class="form-label">Nome</label>
                                        <input type="text" name="first_name" class="form-control" id="first_name">
                                    </div>
                                    <div class="col-3">
                                        <label for="last_name" class="form-label">Sobrenome</label>
                                        <input type="text" name="last_name" class="form-control" id="last_name">
                                    </div>
                                    <div class="col-2">
                                        <label for="document" class="form-label">CPF</label>
                                        <input type="text"
                                               name="document"
                                               class="form-control"
                                               data-mask="000.000.000-00"
                                               id="document">
                                    </div>
                                    <div class="col-2">
                                        <label for="birthdate" class="form-label">Data de Nascimento</label>
                                        <input type="date" name="birthdate" class="form-control" id="birthdate">
                                    </div>
                                    <div class="col-2">
                                        <label for="gender" class="form-label">Sexo</label>
                                        <select class="form-select"
                                                name="gender"
                                                id="gender"
                                                aria-label="Default select example">
                                            <option value="">Selecione</option>
                                            @foreach($availableGenders as $label => $value)
                                                <option value="{{ $value }}">{{ __('customer.' . $label) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-2">
                                        <label for="zip_code" class="form-label">CEP</label>
                                        <input type="text"
                                               name="address.zip_code"
                                               class="form-control"
                                               data-mask="00000-000"
                                               id="zip_code">
                                    </div>
                                    <div class="col-4">
                                        <label for="street" class="form-label">Logradouro</label>
                                        <input type="text"
                                               name="address.street"
                                               class="form-control"
                                               id="street">
                                    </div>
                                    <div class="col-2">
                                        <label for="number" class="form-label">Número</label>
                                        <input type="text" name="address.number" class="form-control" id="number">
                                    </div>
                                    <div class="col-4">
                                        <label for="complement" class="form-label">Complemento</label>
                                        <input type="text"
                                               name="address.complement"
                                               class="form-control"
                                               id="complement">
                                    </div>
                                    <div class="col-6">
                                        <label for="district" class="form-label">Bairro/Distrito</label>
                                        <input type="text"
                                               name="address.district"
                                               class="form-control"
                                               id="district">
                                    </div>
                                    <div class="col-2">
                                        <label for="state" class="form-label">Estado</label>
                                        <select name="address.state" class="form-select" id="state"></select>
                                    </div>
                                    <div class="col-4">
                                        <label for="city" class="form-label">Cidade</label>
                                        <select name="address.city" class="form-select" id="city"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-2">
                        <div class="row">
                            {{ $dataTable->table() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function () {
            window.LaravelDataTables = window.LaravelDataTables || {};
            let $customers = $("#customers");
            window.LaravelDataTables["customers"] = $customers.DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "http://localhost:3000/customers",
                    "type": "GET",
                    "data": function(data) {
                        data.first_name = $("#first_name").val();
                        data.last_name = $("#last_name").val();
                        data.document = $("#document").val();
                        data.birthdate = $("#birthdate").val();
                        data.gender = $("#gender").val();
                        data.address = {
                            zip_code: $("#zip_code").val(),
                            street: $("#street").val(),
                            number: $("#number").val(),
                            complement: $("#complement").val(),
                            district: $("#district").val(),
                            state: $("#state").val(),
                            city: $("#city").val(),
                        }
                        for (let i = 0, len = data.columns.length; i < len; i++) {
                            if (!data.columns[i].search.value) delete data.columns[i].search;
                            if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                            if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                            if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                        }
                        delete data.search.regex;
                    }
                },
                "columns": [{
                    "data": "first_name",
                    "name": "first_name",
                    "title": "Nome",
                    "orderable": true,
                    "searchable": false
                }, {
                    "data": "last_name",
                    "name": "last_name",
                    "title": "Sobrenome",
                    "orderable": true,
                    "searchable": false
                }, {
                    "data": "document",
                    "name": "document",
                    "title": "Documento",
                    "orderable": false,
                    "searchable": false
                }, {
                    "data": "birthdate",
                    "name": "birthdate",
                    "title": "Data de Nascimento",
                    "orderable": true,
                    "searchable": false
                }, {
                    "data": "address.state",
                    "name": "address.state",
                    "title": "Estado",
                    "class": "text-center",
                    "orderable": false,
                    "searchable": false
                }, {
                    "data": "address.city",
                    "name": "address.city",
                    "title": "Cidade",
                    "orderable": false,
                    "searchable": false
                }, {
                    "data": "gender",
                    "name": "gender",
                    "title": "Sexo",
                    "orderable": true,
                    "searchable": false
                }, {
                    "data": "edit",
                    "name": "edit",
                    "title": "Editar",
                    "orderable": false,
                    "searchable": false,
                    "class": "text-center",
                    "createdCell": function (td, cellData, rowData, row, col) {
                        let element = new DOMParser()
                            .parseFromString(cellData, "text/html")
                            .documentElement
                            .textContent
                            .trim()
                        $(td).html(element);
                    }
                }, {
                    "data": "delete",
                    "name": "delete",
                    "title": "Excluir",
                    "orderable": false,
                    "searchable": false,
                    "class": "text-center",
                    "createdCell": function (td, cellData, rowData, row, col) {
                        let element = new DOMParser()
                            .parseFromString(cellData, "text/html")
                            .documentElement
                            .textContent
                            .trim()
                        $(td).html(element);
                    }
                }],
                "searching": false,
                "order": [
                    [0, "desc"]
                ],
                "select": {
                    "style": "single"
                },
                "pageLength": 50,
                "pagingType": "full_numbers",
                "language": {
                    "select": {
                        "rows": ""
                    },
                    "paginate": {
                        "first": "Primeira",
                        "previous": "Anterior",
                        "next": "Próxima",
                        "last": "Última"
                    },
                    "lengthMenu":
                        "<div class=\"d-inline-flex mb-3\" style=\"height: 2em;\">\n"
                        + "<div class=\"mt-1\">Exibir<\/div>\n"
                        + "<div class=\"mx-2\">_MENU_<\/div>\n"
                        + "<div class=\"mt-1\">registros por p\u00e1gina<\/div>\n"
                        + "<\/div>",
                    "info":
                        "<div class=\"d-inline-flex mb-3\" style=\"height: 2em;\">\n"
                        + "Exibindo _START_ at\u00e9 _END_ de _TOTAL_ registros\n"
                        + "<\/div>",
                    "infoEmpty": "Nenhum registro encontrado",
                    "emptyTable": "Nenhum registro encontrado",
                },
                "buttons": []
            });

            $('input, select').on('input', function() {
                window.LaravelDataTables["customers"].ajax.reload();
            });

            $.ajax({
                url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    let options = '<option value="">Selecione</option>';
                    data.forEach(function (item) {
                        options += '<option value="' + item.sigla + '">' + item.nome + '</option>';
                    });
                    $('#state').append(options);
                }
            });

            $("#state").on('change', function () {
                let state = $(this).val();
                $.ajax({
                    url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + state + '/municipios',
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        let options = '<option value="">Selecione</option>';
                        data.forEach(function (item) {
                            options += '<option value="' + item.nome + '">' + item.nome + '</option>';
                        });
                        $('#city').html(options);
                    }
                });
            });

            $customers.on('draw.dt', function () {
                $(".delete-button").on('click', function () {
                    console.log('teste');
                    let id = $(this).data('id');
                    let url = "{{ route('api.customers.destroy', ':id') }}";
                    url = url.replace(':id', id);
                    if (confirm('Deseja realmente excluir este cliente?')) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            dataType: 'json',
                            headers: {
                                Authorization: 'Bearer {{ session()->get('api.token') }}'
                            },
                            success: function (data) {
                                window.LaravelDataTables["customers"].ajax.reload();
                            }
                        });
                    }
                });
            });

            $("#filters-collapse-trigger").on('click', function () {
                let $this = $(this);
                if ($this.data('collapsed') === '1') {
                    $this.text('Exibir filtros');
                    $this.data('collapsed', '0');
                    return;
                }
                $this.text('Ocultar filtros');
                $this.data('collapsed', '1');
            });
        }
    </script>
    <style>
        div.col-sm-12.col-md-7:has(.dataTables_paginate) {
            display: flex;
            justify-content: end;
        }
        .dataTables_empty {
            text-align: center;
        }
    </style>
@endsection
