@extends('layouts.app', ['activePage' => 'configuration', 'title' => 'GLA Admin', 'navName' => 'Configurations', 'activeButton' => 'laravel'])

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Configuration</h1>
                    </div>
                    <div class="col-sm-6 d-flex justify-content-end">
                        {{-- @can('configuration-create') --}}
                        <button type="button" class="btn btn-success btn-sm pull-right" data-toggle="modal"
                            data-target="#configCreateModal">Add New Config</button>
                        {{-- @endcan --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                @can('configuration')
                    @include('configurations.create')
                @endcan

                <div class="card card-outline card-lightblue">
                    {{-- <div class="card-header d-flex justify-content-between">
                        <p><b>Configs</b></p>
                        <button class="btn btn-sm btn-success" id="saveAllConfigBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <i class="fa fa-save"></i>
                        </button>
                    </div> --}}
                    <table id="bill-table" class="table table-sm table-bordered table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Key</th>
                                <th>Value</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($configurations as $item)
                                @if (
                                    !Str::startsWith($item->key, 'mail_') &&
                                        !Str::startsWith($item->key, 'pusher_') &&
                                        !Str::startsWith($item->key, 'broadcast_'))
                                    <tr>
                                        <td>
                                            <input required value="{{ ucwords(str_replace('_', ' ', $item->key)) }}"
                                                class="form-control form-control-sm" type="text"
                                                id="key-{{ $item->id }}" name="key">
                                        </td>
                                        <td>
                                            <input value="{{ $item->value }}" class="form-control form-control-sm"
                                                type="text" id="value-{{ $item->id }}" name="value">
                                        </td>
                                        <td>
                                            {{-- <form action="">
                                                @csrf
                                                @method('PUT') --}}
                                            <button data-id="{{ $item->id }}" data-key="{{ $item->key }}"
                                                data-value="{{ $item->value }}"
                                                class="btn btn-warning btn-sm updateConfig">
                                                <i class="fa fa-save"></i>
                                            </button>
                                            {{-- </form> --}}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </section>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {


            var configurations = @json($configurations);
            // $('.selectpicker').selectpicker({
            //     actionsBox: true,
            //     deselectAllText: 'Deselect All',
            //     selectAllText: 'Select All',
            //     countSelectedText: function(e, t) {
            //         return 1 == e ? "{0} item selected" : "{0} items selected"
            //     },
            //     selectedTextFormat: 'count'
            // });

            $(".datepicker").datepicker({
                dateFormat: 'yy-mm-dd',
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true,
                currentText: "Today",

                beforeShow: function(input, inst) {
                    setTimeout(function() {
                        var buttonPane = $(inst.dpDiv).find('.ui-datepicker-buttonpane');

                        buttonPane.find('.ui-datepicker-current').off('click').on('click',
                            function() {
                                var today = new Date();
                                $(input).datepicker('setDate', today);
                                $.datepicker._hideDatepicker(input); //close after selecting
                                $(input).blur(); //prevent auto-focus/reopen
                            });
                    }, 1);
                }
            });


            $('#configCreateForm').on('submit', function(e) {
                e.preventDefault();

                var url = '{{ route('configurations.store') }}';
                var formData = new FormData(this);
                console.log(formData);

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    enctype: 'multipart/form-data',
                    success: function(response) {
                        toastr.success(response.success);
                        setTimeout(() => {
                            window.location.href =
                                "{{ route('configurations.index') }}";
                        }, 1000);
                    },
                    error: function(response) {
                        if (response.responseJSON.error) {
                            toastr.error(response.responseJSON.error);
                        }
                        for (let field in response.responseJSON.errors) {
                            for (let i = 0; i < response.responseJSON.errors[field]
                                .length; i++) {
                                toastr.error(response.responseJSON.errors[field][i]);
                            }
                        }
                    }
                });
            });

            $('.updateConfig').on('click', function(e) {
                e.preventDefault();

                const configId = $(this).data('id');

                switch (configId) {
                    case 'mail_edit_log_activation':
                        processConfigUpdate(configId, '#editLogMailActivationKey',
                            '#editLogMailActivationValue', 'mail_edit_log_activation');
                        break;

                    case 'mail_super_admins':
                        processConfigUpdate(configId, '#superAdminsKey', '#superAdminsValue',
                            'mail_super_admins');
                        break;

                    default:
                        processConfigUpdate(configId, `#key-${configId}`, `#value-${configId}`);
                }
                setTimeout(() => window.location.reload(), 1000);
            });




            // var selectedKeys = new Set();

            // $('#additional_all_charge').on('change', function() {
            //     var selectedOption = $(this).find('option:selected');
            //     var departmentName = selectedOption.attr('data-department-name');
            //     var chargeKey = 'mail_' + departmentName.split(' ')[0].toLowerCase();

            //     if (selectedKeys.has(chargeKey)) {
            //         return;
            //     }

            //     selectedKeys.add(chargeKey);

            //     var newRow = `
        //         <tr>
        //             <td>
        //                 <input readonly class="form-control form-control-sm" type="text" value="${chargeKey}" name="key" readonly>
        //             </td>
        //             <td>
        //                 <input class="form-control form-control-sm" type="text" value="" name="value">
        //             </td>
        //             <td>
        //                 <button type="submit" class="btn btn-sm btn-warning addConfig"><i class="fa fa-save"></i></button>
        //             </td>
        //         </tr>`;

            //     $('#additional_selected_charge').before(newRow);
            //     $(this).val(null);
            // });

            // $('#additional-charges-table').on('click', '.additional-remove-row', function() {
            //     var keyName = $(this).closest('tr').find('td:first-child').text();
            //     selectedKeys.delete(keyName);
            //     $(this).closest('tr').remove();
            // });

            // $(document).on('click', '.addConfig', function(e) {
            //     e.preventDefault();

            //     var $row = $(this).closest('tr');

            //     var key = $row.find('input[name="key"]').val();
            //     var value = $row.find('input[name="value"]').val();

            //     if (!value) {
            //         alert('Please enter a value!');
            //         return;
            //     }

            //     createConfig(key, value);
            //     setTimeout(() => window.location.reload(), 1000);
            // });

            // $('#saveAllConfigBtn').on('click', function(e) {
            //     e.preventDefault();

            //     let $btn = $(this);
            //     $btn.prop('disabled', true);
            //     $btn.find('.spinner-border').removeClass('d-none');

            //     const promises = [];

            //     $('button.updateConfig[data-id]').each(function() {
            //         const $btn = $(this);
            //         const configId = $btn.data('id');
            //         console.log(configId);

            //         switch (configId) {
            //             case 'mail_edit_log_activation':
            //                 promises.push(processConfigUpdate(configId, '#editLogMailActivationKey',
            //                     '#editLogMailActivationValue', 'mail_edit_log_activation',
            //                     true));
            //                 break;

            //             case 'mail_super_admins':
            //                 promises.push(processConfigUpdate(configId, '#superAdminsKey',
            //                     '#superAdminsValue', 'mail_super_admins', true));
            //                 break;

            //             default:
            //                 promises.push(processConfigUpdate(configId, `#key-${configId}`,
            //                     `#value-${configId}`, null, true));
            //         }
            //     });

            //     Promise.allSettled(promises).then(results => {
            //         const successCount = results.filter(r => r.status === 'fulfilled').length;
            //         const failCount = results.length - successCount;

            //         if (successCount > 0) {
            //             demo.customShowNotification('success',
            //                 `${successCount} configuration value${successCount > 1 ? 's' : ''} updated successfully.`
            //                 );
            //         }

            //         if (failCount > 0) {
            //             demo.customShowNotification('danger',
            //                 `${failCount} configuration update${failCount > 1 ? 's were' : ' was'} failed.`
            //                 );
            //         }

            //         if (successCount > 0) {
            //             $btn.prop('disabled', false);
            //             $btn.find('.spinner-border').addClass('d-none');
            //             setTimeout(() => window.location.reload(), 1000);
            //         }
            //     });
            // });

            // function createConfig(key, value) {
            //     $.ajax({
            //         url: '{{ route('configurations.store') }}',
            //         type: 'POST',
            //         data: {
            //             key: key,
            //             value: value,
            //             _token: '{{ csrf_token() }}',
            //         },
            //         success: function(data) {
            //             demo.customShowNotification('success', data.success);
            //         },
            //         error: function(data) {
            //             if (data.responseJSON.error) {
            //                 demo.customShowNotification('danger', data.responseJSON.error);
            //             }
            //             for (let field in data.responseJSON.errors) {
            //                 for (let i = 0; i < data.responseJSON.errors[field].length; i++) {
            //                     demo.customShowNotification('danger', data.responseJSON.errors[field][
            //                         i
            //                     ]);
            //                 }
            //             }
            //         }
            //     });
            // }

            // function updateConfig(configId, configKey, configValue) {
            //     if (!configId || !configKey) {
            //         console.error('Missing config ID or Key');
            //         return;
            //     }

            //     $.ajax({
            //         url: `{{ route('configurations.update', ':id') }}`.replace(':id', configId),
            //         type: 'PUT',
            //         data: {
            //             _token: '{{ csrf_token() }}',
            //             key: configKey,
            //             value: configValue
            //         },
            //         success: function(data) {
            //             demo.customShowNotification('success', data.success);
            //         },
            //         error: function(data) {
            //             const response = data.responseJSON;
            //             if (response?.error) {
            //                 demo.customShowNotification('danger', response.error);
            //             }

            //             if (response?.errors) {
            //                 Object.values(response.errors).flat().forEach(msg => {
            //                     demo.customShowNotification('danger', msg);
            //                 });
            //             }
            //         }
            //     });
            // }

            function processConfigUpdate(configId, configKeySelector, configValueSelector, specialKey = null,
                silent = false) {
                return new Promise((resolve, reject) => {
                    let configKey = $(configKeySelector).val();
                    let configValue = $(configValueSelector).val();

                    // Handle missing or invalid selectors
                    if (typeof configKey === 'undefined' || typeof configValue === 'undefined') {
                        // return reject();
                        console.log(configKey, configValue);
                        // continue;
                        return;
                    }

                    // Normalize arrays
                    if (Array.isArray(configValue)) {
                        configValue = configValue.join(',');
                    }

                    // Find existing config if specialKey used
                    const existingConfig = specialKey ? configurations.find(config => config.key ===
                        specialKey) : null;
                    const updateId = specialKey && existingConfig ? existingConfig.id : configId;

                    // If creating new config
                    if (specialKey && !existingConfig) {
                        createConfig(configKey,
                            configValue); // Optional enhancement: return Promise from createConfig
                        return resolve(); // resolve immediately to continue others
                    }

                    // Update via AJAX
                    $.ajax({
                        url: `{{ route('configurations.update', ':id') }}`.replace(':id',
                            updateId),
                        type: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            key: configKey,
                            value: configValue
                        },
                        success: function(data) {
                            if (!silent) {
                                toastr.success(data.success);
                                setTimeout(() => window.location.reload(), 1000);
                            }
                            resolve(data);
                        },
                        error: function(data) {
                            const response = data.responseJSON;
                            if (response?.error) toastr.error(response.error);

                            if (response?.errors) {
                                Object.values(response.errors).flat().forEach(msg => {
                                    toastr.error(msg);
                                });
                            }
                            reject(data);
                        }
                    });
                });
            }

        });
    </script>
@endpush
