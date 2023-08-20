@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" id="success-message" style="display: none">
            <div class="col">
                <div class="alert alert-success">
                    Cliente atualizado com sucesso!
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card bg-white">
                    <div class="card-header">{{ __('customer.customer') }}</div>
                    <div class="card-body">
                        <form class="row g-3" id="customer-form">
                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-3">
                                        <label for="first_name" class="form-label">Nome</label>
                                        <input type="text"
                                               name="first_name"
                                               class="form-control"
                                               value="{{ $customer->first_name }}"
                                               id="first_name">
                                    </div>
                                    <div class="col-3">
                                        <label for="last_name" class="form-label">Sobrenome</label>
                                        <input type="text"
                                               name="last_name"
                                               value="{{ $customer->last_name }}"
                                               class="form-control"
                                               id="last_name">
                                    </div>
                                    <div class="col-2">
                                        <label for="document" class="form-label">CPF</label>
                                        <input type="text"
                                               name="document"
                                               value="{{ $customer->document }}"
                                               class="form-control"
                                               data-mask="000.000.000-00"
                                               id="document">
                                    </div>
                                    <div class="col-2">
                                        <label for="birthdate" class="form-label">Data de Nascimento</label>
                                        <input type="date"
                                               name="birthdate"
                                               value="{{ $customer->birthdate }}"
                                               class="form-control"
                                               id="birthdate">
                                    </div>
                                    <div class="col-2">
                                        <label for="gender" class="form-label">Sexo</label>
                                        <select class="form-select"
                                                name="gender"
                                                id="gender"
                                                aria-label="Default select example">
                                            @foreach($availableGenders as $label => $value)
                                                <option
                                                    value="{{ $value }}"
                                                    @selected($value == $customer->gender)>
                                                    {{ __('customer.' . $label) }}
                                                </option>
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
                                               value="{{ $customer->address->zip_code }}"
                                               data-mask="00000-000"
                                               id="zip_code">
                                    </div>
                                    <div class="col-4">
                                        <label for="street" class="form-label">Logradouro</label>
                                        <input type="text"
                                               name="address.street"
                                               class="form-control"
                                               value="{{ $customer->address->street }}"
                                               id="street">
                                    </div>
                                    <div class="col-2">
                                        <label for="number" class="form-label">Número</label>
                                        <input type="text"
                                               name="address.number"
                                               class="form-control"
                                               value="{{ $customer->address->number }}"
                                               id="number">
                                    </div>
                                    <div class="col-4">
                                        <label for="complement" class="form-label">Complemento</label>
                                        <input type="text"
                                               name="address.complement"
                                               class="form-control"
                                               value="{{ $customer->address->complement }}"
                                               id="complement">
                                    </div>
                                    <div class="col-6">
                                        <label for="district" class="form-label">Bairro/Distrito</label>
                                        <input type="text"
                                               name="address.district"
                                               class="form-control"
                                               value="{{ $customer->address->district }}"
                                               id="district">
                                    </div>
                                    <div class="col-2">
                                        <label for="state" class="form-label">Estado</label>
                                        <input type="text"
                                               name="address.state"
                                               class="form-control"
                                               value="{{ $customer->address->state }}"
                                               id="state" disabled>
                                    </div>
                                    <div class="col-4">
                                        <label for="city" class="form-label">Cidade</label>
                                        <input type="text"
                                               name="address.city"
                                               class="form-control"
                                               value="{{ $customer->address->city }}"
                                               id="city" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <button type="submit"
                                                id="submit"
                                                class="btn btn-primary">
                                            Salvar
                                        </button>
                                        <a href="{{ route('customers.index') }}"
                                                class="btn btn-secondary">
                                            Voltar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function () {
            $('input').on('input', function () {
                $(this).removeClass('is-invalid');
                $(this).parent().find('.invalid-feedback').remove();
            });

            $("#zip_code").on('input', function () {
                let zip_code = $(this).val().replace(/\D/g, '');
                if (zip_code.length === 8) {
                    $.ajax({
                        url: `https://viacep.com.br/ws/${zip_code}/json/`,
                        dataType: 'json',
                        success: function (data) {
                            if (!data.erro) {
                                $("#street").val(data.logradouro);
                                $("#district").val(data.bairro);
                                $("#state").val(data.uf);
                                $("#city").val(data.localidade);

                                if (data.logradouro) {
                                    $("#number").focus();
                                }
                            }
                        }
                    });
                }
            });

            $("#customer-form").on('submit', function (e) {
                e.preventDefault();
                $(this).removeClass('is-invalid');
                $(this).parent().find('.invalid-feedback').remove();
                $.ajax({
                    url: '{{ route('api.customers.update', ['customer' => $customer->id]) }}',
                    method: 'PUT',
                    headers: {
                        Authorization: "Bearer {{ session()->get('api.token') }}"
                    },
                    data: {
                        first_name: $("#first_name").val(),
                        last_name: $("#last_name").val(),
                        document: $("#document").val(),
                        birthdate: $("#birthdate").val(),
                        gender: $("#gender").val(),
                        address: {
                            zip_code: $("#zip_code").val(),
                            street: $("#street").val(),
                            number: $("#number").val(),
                            complement: $("#complement").val(),
                            district: $("#district").val(),
                            state: $("#state").val(),
                            city: $("#city").val(),
                        }
                    },
                    beforeSend: function () {
                        $("#submit").attr('disabled', true);
                    },
                    success: function () {
                        $("#submit").attr('disabled', false);
                        $("#reset").trigger('click');
                        $("#success-message").show();
                        setTimeout(function () {
                            $("#success-message").hide('slow');
                        }, 5000);
                    },
                    error: function (data) {
                        $("#submit").attr('disabled', false);
                        let errors = Object.keys(data.responseJSON.errors);
                        $(errors).each(function (index, error) {
                            let $element = $(`[name="${error}"]`);
                            $element.addClass("is-invalid");
                            $element.parent().append(
                                `<div class="invalid-feedback">${data.responseJSON.errors[error][0]}</div>`
                            );
                        });
                    },
                });
            })
        }
    </script>
@endsection
