@extends('layouts.base')
@extends('layouts.sidebar')
@section('title','Keranjang saya')

@section('content')

<div class="container-fluid">
    <h1 class="p-1">Keranjang</h1>
    {{-- <button class="btn btn-primary" id="btnTambah">tambah</button> --}}
        <div id="dxgrid" style="height: 200px;">
            
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
                        <div class="row">
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label label for="">Kode Produk</label>
                                <input type="hidden" class="form-control" id="id" >
                                <input type="text" class="form-control" id="id_produk" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label label for="">Nama Produk</label>
                                <input type="text" class="form-control" id="nama" readonly>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label label for="">Harga satuan</label>
                                <input type="number" class="form-control" id="harga" readonly>
                            </div>
                            </div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label label for="">Jumlah stok</label>
                                <input type="number" class="form-control" id="stok" readonly>
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                                <label label for="">Estimasi harga</label>
                                <input type="number" class="form-control" id="subtotal" readonly>
                            </div>
                        <div class="row">
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label label for="">jumlah beli</label>
                                <input type="number" class="form-control" id="jumlah_beli">
                            </div>
                            </div>
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
            url: "{{route('getKeranjang')}}",
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
        // pager: {    
        // showPageSizeSelector: true,
        // allowedPageSizes: [10, 20, 50],
        // showPageSizeSelector: true,
        // showInfo: true,
        // showNavigationButtons: true,
        //     },
        columns: [
        {
        dataField: 'id_produk',
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
        dataField: 'jumlah',
        caption:'jumlah',
        // dataType: 'string',
        },
        {
        dataField: 'subtotal',  
        caption:'Subtotal',
        // dataType: 'string',
        }
        ],
        onRowClick: function (e) {
            $('#modal').modal('show');
            $('#judulModal').html('Edit Jumlah');
            $('#btnAdd').html('Edit');
            $('#id').val(e.data.id);
            $('#id_produk').val(e.data.id_produk);
            $('#jumlah_beli').val(e.data.jumlah);
            $('#nama').val(e.data.nama);
            $('#harga').val(e.data.harga);
            $('#stok').val(e.data.stok);
            $('#subtotal').val(e.data.subtotal);
            status = 'e';
        }
})
}


$('#jumlah_beli').keyup(function (e) { 
    var harga_satuan = $('#harga').val()
    var jumlah_beli = $('#jumlah_beli').val()
    var hasil = (harga_satuan * jumlah_beli)
    $('#subtotal').val(hasil);

});

var value1 = parseFloat($('#value1').val()) || 0;
            var value2 = parseFloat($('#value2').val()) || 0;
            $('#sum').val(value1 + value2);

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



// $('#btnTambah').click(function (e) { 
//     $('#modal').modal('show')
//     $('#judulModal').html('Tambah Produk')
//     $('#btnAdd').html('Tambah')
//     $('#label').html('');
//     $('#form')[0].reset()
//     $("#id").prop("type", "hidden");
//     status = 'c' ;
//     console.log(status);
// });

// $('#btnAdd').click(function (e) { 
//     $.ajax({
//         type: "post",
//         url: "{{route('updateKeranjang')}}",
//         data: {
//             subtotal : $('#subtotal'),
//             jumlah_beli : $('#jumlah_beli'),
//         },
//         dataType: "json",
//         success: function (response) {
            
//         }
//     });
    
// });

$('#btnAdd').click(function (e) { 
    $.ajax({
        type: "post",
        url:  "{{route('updateKeranjang')}}",
        data: {
            id : $('#id').val(),
            // jumlah_beli : $('#jumlah_beli'),
            subtotal : $('#subtotal').val(),
            jumlah_beli : $('#jumlah_beli').val(),
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content'),
    },
        dataType: "json",
        success: function (res) {
            $('#modal').modal('hide') 
            dxgrid()
            
        }
    });
});

// $('#btnAdd').click(function (e) { 
//     if(status == 'c'){

//         var id_user = $('#id_user').val()
//         var nama = $('#nama').val();
//         $.ajax({
//         type: "post",
//         url: "{{route('addProduk')}}",
//         data: {
//             id : nama.substring(0,3),
//             id_user : $('#id_user').val(),
//             nama : nama,
//             harga : $('#harga').val(),
//             stok : $('#stok').val(),
//             keterangan : $('#keterangan').val(),
//             'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content'),
//         },
//         dataType: "json",
//         success: function (res) {
//             $('#modal').modal('hide') 
//             dxgrid()
//         }
//     });
//     } else if (status == 'e') {
        
//         $.ajax({
//             type: "post",
//             url:  "{{route('updateKeranjang')}}",
//             data: {
//                 // jumlah_beli : $('#jumlah_beli'),
//                 subtotal : $('#subtotal'),
//                 jumlah_beli : $('#jumlah_beli'),
//             'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content'),
//         },
//             dataType: "json",
//             success: function (res) {
//                 $('#modal').modal('hide') 
//                 dxgrid()
                
//             }
//         });
//     }
// });




</script>

    
@endpush
