@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mb-2">
                <a class="btn btn-primary" href="{{ route('customers.create') }}">
                    Cadastrar Cliente
                </a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">{{ __('customer.customers') }}</div>
                    <div class="card-body bg-white">
                        <div class="row g-3">
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
                                        <input type="text"
                                               name="address.state"
                                               class="form-control"
                                               id="state">
                                    </div>
                                    <div class="col-4">
                                        <label for="city" class="form-label">Cidade</label>
                                        <input type="text"
                                               name="address.city"
                                               class="form-control"
                                               id="city">
                                    </div>
                                </div>
                            </div>
                        </div>
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
            $(function() {
                window.LaravelDataTables = window.LaravelDataTables || {};
                window.LaravelDataTables["customers"] = $("#customers").DataTable({
                    "serverSide": true,
                    "processing": true,
                    "ajax": {
                        "url": "http:\/\/localhost:3000\/customers",
                        "type": "GET",
                        "data": function(data) {
                            data.first_name = $("#first_name").val();
                            data.last_name = $("#last_name").val();
                            data.document = $("#document").val();
                            data.birthdate = $("#birthdate").val();
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
                        "searchable": true
                    }, {
                        "data": "last_name",
                        "name": "last_name",
                        "title": "Sobrenome",
                        "orderable": true,
                        "searchable": true
                    }, {
                        "data": "document",
                        "name": "document",
                        "title": "Documento",
                        "orderable": false,
                        "searchable": true
                    }, {
                        "data": "birthdate",
                        "name": "birthdate",
                        "title": "Data de Nascimento",
                        "orderable": true,
                        "searchable": true
                    }, {
                        "data": "address.state",
                        "name": "address.state",
                        "title": "Estado",
                        "orderable": false,
                        "searchable": true
                    }, {
                        "data": "address.city",
                        "name": "address.city",
                        "title": "Cidade",
                        "orderable": false,
                        "searchable": true
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
                        "paginate": {
                            "first": "Primeira",
                            "previous": "Anterior",
                            "next": "Pr\u00f3xima",
                            "last": "\u00daltima"
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
                            + "<\/div>"
                    },
                    "buttons": []
                });
            });
        }
    </script>
@endsection
