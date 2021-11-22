@extends('admin.layout')

@section('title')
    Pengaturan Akun
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <a href="{{ route('admin.trans.index') }}" class="btn btn-default mb-2">Kembali</a>
        <div class="row">
            <div class="col-12 col-sm-6 mb-2">
                <div class="card">
                    <div class="card-header">
                        Pengaturan Akun
                    </div>
                    <div class="card-body">
                        <div class="">
                            <form action="{{ route('admin.setting.update') }}" method="POST">
                                @csrf
                                @method("PUT")
                                <div class="form-group boxed">
                                    <label class="label">Telp</label>
                                    <input type="text" name="telp" class="form-control" value="{{ $akun['telp'] ?? '' }}">
                                </div>
                                <div class="form-group boxed">
                                    <label class="label">Password (password boleh kosong kalau tidak diubah)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="form-group boxed">
                                    <button type="submit" name="submit" class="btn btn-primary btn-block btn-lg">SIMPAN PERUBAHAN</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<br>
<br>
<br>
@endsection



@push('js')
<script>
$(document).ready( function () {
    $('table').DataTable();
});
</script>    
@endpush