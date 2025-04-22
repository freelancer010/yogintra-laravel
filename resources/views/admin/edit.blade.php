@extends('layouts.layout')

@section('content')
<style>
  .avatar-upload {
    position: relative;
    max-width: 205px;
    margin: auto;
  }

  .avatar-upload .avatar-edit {
    position: absolute;
    right: 12px;
    z-index: 1;
    top: 10px;
  }

  .avatar-upload .avatar-edit input {
    display: none;
  }

  .avatar-upload .avatar-edit input+label {
    display: inline-block;
    width: 34px;
    height: 34px;
    margin-bottom: 0;
    border-radius: 100%;
    background: #FFFFFF;
    border: 1px solid transparent;
    box-shadow: 0px 2px 4px 5px rgb(0 0 0 / 12%);
    cursor: pointer;
    font-weight: normal;
    transition: all 0.2s ease-in-out;
    padding: 4px 8px;
  }

  .avatar-upload .avatar-edit input+label:hover {
    background: #f1f1f1;
    border-color: #d6d6d6;
  }

  .avatar-upload .avatar-preview {
    width: 192px;
    height: 192px;
    position: relative;
    border-radius: 100%;
    border: 6px solid #F8F8F8;
    box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
  }

  .avatar-upload .avatar-preview>div {
    width: 100%;
    height: 100%;
    border-radius: 100%;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
  }
</style>

<div class="content-wrapper">
  <section class="content">
    <div class="card card-default color-palette-bo">
      <div class="card-header">
        <div class="d-inline-block">
          <h3 class="card-title">Edit Admin</h3>
        </div>
        <div class="d-inline-block float-right">
          <a href="{{ route('admin.view') }}" class="btn btn-success"><i class="fa fa-list"></i>&nbsp;&nbsp;Admin List</a>
        </div>
      </div>

      <div class="card-body">
        <form action="{{ route('admin.edit', $admin->admin_id) }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
          @csrf
          @method('POST')

          <div class="container">
            <div class="avatar-upload">
              <div class="avatar-edit">
                <input type='file' name="profileImage" id="imageUpload" accept=".png, .jpg, .jpeg" />
                <label for="imageUpload"><i class="fas fa-plus"></i></label>
              </div>
              <div class="avatar-preview">
                <div id="imagePreview" style="background-image: url('{{ asset($admin->profile_image) }}');"></div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" value="{{ old('username', $admin->username) }}" class="form-control" id="username">
          </div>

          <div class="form-group">
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" value="{{ old('firstname', $admin->firstname) }}" class="form-control" id="firstname">
          </div>

          <div class="form-group">
            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" value="{{ old('lastname', $admin->lastname) }}" class="form-control" id="lastname">
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="form-control" id="email">
          </div>

          <div class="form-group">
            <label for="mobile_no">Mobile No</label>
            <input type="number" name="mobile_no" value="{{ old('mobile_no', $admin->mobile_no) }}" class="form-control" id="mobile_no">
          </div>

          <div class="form-group">
            <label for="status">Select Status</label>
            <select name="status" class="form-control">
              <option value="">Select Status</option>
              <option value="1" {{ $admin->is_active == 1 ? 'selected' : '' }}>Active</option>
              <option value="0" {{ $admin->is_active == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
          </div>

          <div class="form-group">
            <label for="password">Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control" id="password">
          </div>

          <div class="form-group">
            <label for="role">Select Admin Role*</label>
            <select name="role" class="form-control">
              <option value="">Select Role</option>
              @foreach($admin_roles as $role)
              <option value="{{ $role->admin_role_id }}" {{ $role->admin_role_id == $admin->admin_role_id ? 'selected' : '' }}>
                {{ $role->admin_role_title }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <input type="submit" name="submit" value="Update Admin" class="btn btn-primary float-end">
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
@endsection

@section('scripts')
<script>
  $("#imageUpload").change(function() {
    readURL(this);
  });

  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
        $('#imagePreview').hide().fadeIn(650);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
@endsection