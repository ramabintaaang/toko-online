@extends('layouts.base')
@extends('layouts.sidebar')
@section('title','Produk Saya')

@section('content')

<div class="container-fluid">
    <h1 class="p-1">Data Produk</h1>
    <button class="btn btn-primary" id="btnTambah">tambah</button>
        <div id="dxgrid" style="height: 400px;" >
            
        </div>
</div>


{{-- modal  --}}
 <div class="modal fade" id="modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 id="judulModal"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                    <form id="form">
                    <div class="form-group">
                        <label for="kode" id="label"></label>
                        <input type="text" class="form-control" id="id" name="id" readonly>
                        <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{Auth::user()->id}}">
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Produk</label>
                        <input type="text" class="form-control" id="nama"  placeholder="Nama Produk Kamu">
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label label for="">Harga</label>
                                <input type="number" class="form-control" id="harga"  placeholder="Harga Produk Kamu">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nama">Stok</label>
                                <input type="number" class="form-control" id="stok" placeholder="Stok barang">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" rows="3" placeholder="Keterangan barang" id="keterangan" ></textarea>
                    </div>
                    </form>
                    
                {{-- <div class="form-group">
                <label for="exampleInputFile">File input</label>
                <div class="input-group">
                    <div class="custom-file">
                    <input type="file" class="custom-file-input" id="exampleInputFile">
                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                    </div>
                    <div class="input-group-append">
                    <span class="input-group-text" id="">Upload</span>
                    </div>
                </div>
                </div> --}}
                {{-- <div class="form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div> --}}
            
            </div>
            <div class="modal-footer">
              {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
              <button type="button" class="btn btn-danger" style="display:none;" id="btnHapus">Hapus</button>
              <button type="button" class="btn btn-primary" id="btnAdd"></button>
            </div>
          </div>
        </div>
      </div>

@endsection

@push('js')
<script>
    $(document).ready(function () {
    dxgrid();
});

var status = null

function dxgrid()
{
    const data = new DevExpress.data.DataSource({
        load: function(e){
        var deferred = $.Deferred();
        var args = {};
        $.ajax({
            method: "get",
            url: "{{route('allProduk')}}",
            data:{} ,
            dataType: "json",
            success: function (res) {
                    deferred.resolve(res,{
                    totalCount: res.length,
                });
            }
        });
        return deferred.promise();
        }
    })

        
    $("#dxgrid").dxDataGrid({
        dataSource:data,
        showBorders: true,
        showRowLines: true,
        columnAutoWidth: true,
        showColumnLines: true,
        hoverStateEnabled: true,
        rowAlternationEnabled: true,
        // remoteOperations: true,
        paging: {
        pageSize: 10,
                },
        pager: {    
        showPageSizeSelector: true,
        allowedPageSizes: [10, 20, 50],
        showPageSizeSelector: true,
        showInfo: true,
        showNavigationButtons: true,
            },
        columns: [
        {
        dataField: 'id',
        caption:'Kode produk',
        // dataType: 'string',
        }, 
        {
        dataField: 'nama',
        caption:'Nama Produk',
        // dataType: 'string',
        }, 
        {
        dataField: 'harga',
        caption:'Harga',
        // dataType: 'string',
        },
        {
        dataField: 'keterangan',
        caption:'Keterangan',
        // dataType: 'string',
        },
        {
        dataField: 'stok',
        caption:'Stok',
        // dataType: 'string',
        }
        ],
        onRowClick: function (e) {
            $('#modal').modal('show');
            $('#judulModal').html('Edit Produk');
            $('#btnAdd').html('Edit');
            $('#label').html('Kode Produk');
            $("#id").prop("type", "text");
            $('#btnHapus').removeAttr('style');
            $('#id').val(e.data.id);
            $('#nama').val(e.data.nama);
            $('#harga').val(e.data.harga);
            $('#stok').val(e.data.stok);
            $('#keterangan').val(e.data.keterangan);
            status = 'e';
        }
})
}

$('#btnHapus').click(function(e){
    $.ajax({
        type: "post",
        url: "{{route('deleteProduk')}}",
        data: {
            id : $('#id').val(),
            nama : $('#nama').val(),
            harga : $('#harga').val(),
            stok : $('#stok').val(),
            keterangan : $('#keterangan').val(),
        },
        dataType: "json",
        success: function (res) {
            $('#modal').modal('hide') 
            dxgrid()
        }
    });
});



$('#btnTambah').click(function (e) { 
    $('#modal').modal('show')
    $('#judulModal').html('Tambah Produk')
    $('#btnAdd').html('Tambah')
    $('#label').html('');
    $('#form')[0].reset()
    $("#id").prop("type", "hidden");
    status = 'c' ;
    console.log(status);
});

$('#btnAdd').click(function (e) { 
    if(status == 'c'){

        var id_user = $('#id_user').val()
        var nama = $('#nama').val();
        $.ajax({
        type: "post",
        url: "{{route('addProduk')}}",
        data: {
            id : nama.substring(0,3),
            id_user : $('#id_user').val(),
            nama : nama,
            harga : $('#harga').val(),
            stok : $('#stok').val(),
            keterangan : $('#keterangan').val(),
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content'),
        },
        dataType: "json",
        success: function (res) {
            $('#modal').modal('hide') 
            dxgrid()
        }
    });
    } else if (status == 'e') {
        
        $.ajax({
            type: "post",
            url:  "{{route('updt')}}",
            data: {
            id : $('#id').val(),
            nama : $('#nama').val(),
            harga : $('#harga').val(),
            stok : $('#stok').val(),
            keterangan : $('#keterangan').val(),
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content'),
        },
            dataType: "json",
            success: function (res) {
                $('#modal').modal('hide') 
                dxgrid()
                
            }
        });
    }
});




</script>

    
@endpush
