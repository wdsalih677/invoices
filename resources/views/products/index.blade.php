@extends('layouts.master')
@section('title')
قائمة المنتجات
@endsection
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الإعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة المنتجات</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
                    @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session()->get('success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    @if (session()->has('edit'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session()->get('edit') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    @if (session()->has('delete'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session()->get('delete') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
				<!-- row -->
                    <div class="row row-sm">
                        <!--div-->
                        <div class="col-xl-12">
                            <div class="card mg-b-20">
                                <div class="card-header pb-0">
                                    <div class="d-flex justify-content-between">
                                        <div class="col-sm-6 col-md-4 col-xl-3">
                                            <a class="modal-effect btn btn-outline-primary " data-effect="effect-scale" data-toggle="modal" href="#modaldemo8">إضافة منتج</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example1" class="table key-buttons text-md-nowrap">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0">#</th>
                                                    <th class="border-bottom-0">إسم المنتج</th>
                                                    <th class="border-bottom-0">القسم</th>
                                                    <th class="border-bottom-0">الملاحظات</th>
                                                    <th class="border-bottom-0">العمليات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ( $products as $product )
                                                <tr>
                                                    <td>{{ $product->id }}</td>
                                                    <td>{{ $product->product_name }}</td>
                                                    <td>{{ $product->section->section_name }}</td>
                                                    <td>{{ $product->description }}</td>
                                                    <td>
                                                        <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale" data-toggle="modal"
                                                        href="#exampleModal2{{ $product->id }}" title="تعديل"><i class="las la-pen"></i></a>

                                                        <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" data-toggle="modal"
                                                        href="#modaldemo8{{ $product->id }}"><i class="las la-trash"></i></a>

                                                    </td>
                                                </tr>
                                                {{--start edit modal --}}
                                                <div class="modal fade" id="exampleModal2{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">تعديل المنتج</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('products.update',$product->id) }}" method="POST" autocomplete="off">
                                                                    {{ method_field('patch') }}
                                                                    {{ csrf_field() }}
                                                                    <input type="hidden" name="id"  value="{{ $product->id }}">
                                                                    <div class="form-group">
                                                                        <label for="exampleInputEmail1">اسم المنتج</label>
                                                                        <input type="text" class="form-control" value="{{ $product->product_name }}" autocomplete="off" name="product_name">
                                                                    </div>
                                                                    <label class="my-1 mr-2" for="inlineFormCustomSelectPref">القسم</label>
                                                                    <select name="section_id" id="section_id" class="form-control" >
                                                                        <option value="" selected disabled> --حدد القسم--</option>
                                                                        @foreach ($sections as $section)
                                                                            <option value="{{ $section->id }}" {{  $section->id == $product->section_id ? 'selected' : '' }}>{{ $section->section_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlTextarea1">ملاحظات</label>
                                                                        <textarea class="form-control" rows="3" autocomplete="off" name="description">{{ $product->description }}</textarea>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-success">تاكيد</button>
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                                                                    </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{--end edit modal --}}
                                                {{-- start delete modal --}}
                                                {{-- start delete modal --}}
                                                <div class="modal" id="modaldemo8{{ $product->id }}">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content modal-content-demo">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title">حذف المنتج</h6><button aria-label="Close" class="close" data-dismiss="modal"
                                                                    type="button"><span aria-hidden="true">&times;</span></button>
                                                            </div>
                                                            <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                                                                {{ method_field('delete') }}
                                                                {{ csrf_field() }}
                                                                <div class="modal-body">
                                                                    <p>هل انت متاكد من عملية الحذف ؟</p><br>
                                                                    <input type="hidden" name="id"  value="{{ $product->id }}">
                                                                    <input class="form-control" name="product_name" value="{{ $product->product_name }}" type="text" readonly>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                                                                    <button type="submit" class="btn btn-danger">تاكيد</button>
                                                                </div>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                {{-- end delete modal --}}
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/div-->
                        {{--start Add modal --}}
                        <div class="modal" id="modaldemo8">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content modal-content-demo">
                                    <div class="modal-header">
                                        <h6 class="modal-title">إضافة منتج</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('products.store') }}" method="POST">
                                            {{ csrf_field() }}

                                            <div class="form-group">
                                                <label for="exampleInputEmail1">اسم المنتج</label>
                                                <input type="text" class="form-control" autocomplete="off" name="product_name">
                                            </div>
                                            <label class="my-1 mr-2" for="inlineFormCustomSelectPref">القسم</label>
                                            <select name="section_id" id="section_id" class="form-control" >
                                                <option value="" selected disabled> --حدد القسم--</option>
                                                @foreach ($sections as $section)
                                                    <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">ملاحظات</label>
                                                <textarea class="form-control" rows="3" autocomplete="off" name="description"></textarea>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">تاكيد</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--end Add modal --}}
                    </div>
				<!-- row closed -->
				<!-- /row -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>

<!--Internal  Datepicker js -->
<script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
<!-- Internal Select2 js-->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<!-- Internal Modal js-->
<script src="{{URL::asset('assets/js/modal.js')}}"></script>
@endsection
