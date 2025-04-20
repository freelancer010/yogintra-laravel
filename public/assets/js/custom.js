$.widget.bridge('uibutton', $.ui.button)

function ajaxCall() {
    this.send = function (data, url, method, success, type) {
        type = 'json';
        var successRes = function (data) {
            success(data);
        }
        var errorRes = function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
        jQuery.ajax({
            url: url,
            type: method,
            data: data,
            success: successRes,
            error: errorRes,
            dataType: type,
            timeout: 60000
        });
    }
}

$(document).ready(function () {
    // Set CSRF token for all ajax requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Load countries on page load
    getCountries();

    // Event listener for country dropdown
    $('.countries').on('change', function () {
        var countryId = $(this).find(':selected').data('id');
        if (countryId) {
            getStates(countryId);
        }
    });

    // Event listener for state dropdown
    $('.states').on('change', function () {
        var stateId = $(this).find(':selected').data('id');
        if (stateId) {
            getCities(stateId);
        }
    });
});

// Get Countries
function getCountries() {
    $.post('/get-countries', {}, function (data) {
        $('.countries').html('<option value="">Select Country</option>');
        $.each(data.result, function (key, val) {
            $('.countries').append(`<option value="${val.name}" data-id="${val.id}">${val.name}</option>`);
        });
    });
}

// Get States
function getStates(countryId) {
    $('.states').html('<option value="">Please wait...</option>');
    $('.cities').html('<option value="">Select City</option>');
    $.post('/get-states', { countryId: countryId }, function (data) {
        $('.states').html('<option value="">Select State</option>');
        $.each(data.result, function (key, val) {
            $('.states').append(`<option value="${val.name}" data-id="${val.id}">${val.name}</option>`);
        });
    });
}

// Get Cities
function getCities(stateId) {
    $('.cities').html('<option value="">Please wait...</option>');
    $.post('/get-cities', { stateId: stateId }, function (data) {
        $('.cities').html('<option value="">Select City</option>');
        $.each(data.result, function (key, val) {
            $('.cities').append(`<option value="${val.name}">${val.name}</option>`);
        });
    });
}

$('button#DeleteRow').css('display', 'none');

$(function () {
    $('.text-editor').summernote({
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ],
        height: 200,
    });

    $('button#DeleteRow').hide();
});

function createDataTable(table_id, jsonData, cols, ord = 0) {
    $('#' + table_id).DataTable().clear().destroy();
    if (jsonData.length > 0) {
        $('#' + table_id).DataTable({
            data: jsonData,
            columns: cols,
            order: [
                [ord, 'desc']
            ],
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true,
            dom: 'Bfrtip',
            buttons: ['csv', 'pdf'],
            columnDefs: [{
                targets: 0,
                className: "text-left"
            }]
        });
    } else {
        $('#' + table_id).DataTable({
            paging: false,
            lengthChange: false,
            searching: false,
            ordering: false,
            info: false,
            autoWidth: false,
            responsive: true,
        });
    }
}

function ajaxCallData(url, param, method) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: method,
            url: url,
            data: param,
            beforeSend: function () {
                $('.overlay').removeClass('hidden');
            },
            complete: function () {
                $('.overlay').addClass('hidden');
            },
            success: resolve,
            error: reject
        });
    });
}

function notifyAlert(message, color) {
    $('#toastsContainerTopRight').html(`
    <div class="toast bg-${color} fade show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">${message}</div>
    </div>
`);
    setTimeout(() => {
        $('#toastsContainerTopRight').html('');
    }, 2000);
}

function isJSON(str) {
    if (typeof str === "boolean") return false;
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

const redirect = (location) => {
    window.location.href = "{{ url('/') }}/" + location;
};