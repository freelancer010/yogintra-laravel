<div class="datalist">
    <table id="example1" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>{{ __('User') }}</th>
                <th>{{ __('Username') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Role') }}</th>
                <th width="100">{{ __('Status') }}</th>
                <th width="120">{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($info as $row)
            <tr>
                <td>
                    <h4 class="m0 mb5">{{ $row->firstname }} {{ $row->lastname }}</h4>
                    <small class="text-muted">{{ $row->admin_role_title }}</small>
                </td>
                <td>{{ $row->username }}</td>
                <td>{{ $row->email }}</td>
                <td>
                    <button class="btn btn-xs btn-success">{{ $row->admin_role_title }}</button>
                </td>
                <td>
                    <input class='tgl tgl-ios tgl_checkbox'
                        data-id="{{ $row->admin_id }}"
                        id='cb_{{ $row->admin_id }}'
                        type='checkbox' {{ $row->is_active == 1 ? 'checked' : '' }} />
                    <label class='tgl-btn' for='cb_{{ $row->admin_id }}'></label>
                </td>
                <td>
                    <a href="{{ url('admin/edit/'.$row->admin_id) }}" class="btn btn-warning btn-xs mr5">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="{{ url('admin/delete/'.$row->admin_id) }}" onclick="return confirm('Are you sure to delete?')" class="btn btn-danger btn-xs">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>