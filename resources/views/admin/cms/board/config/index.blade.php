@extends('layouts.admin.main')

@section('title', config('app.name').' - 게시판 관리')

@section('contents')
<div class="container-fluid border-bottom px-4 py-2 bg-white mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-0 py-0 mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><span>게시판 관리</span></li>
                <li class="breadcrumb-item active" aria-current="page"><span>게시판 설정</span></li>
            </ol>
        </nav>

        <div class="d-flex gap-2">
            <a href="#" id="create" class="btn btn-success">생성</a>
            <a href="#" id="modify" class="btn btn-warning">수정</a>
            <a href="#" id="delete" class="btn btn-danger">삭제</a>
        </div>
    </div>
</div>

<div class="container-fluid px-4 py-2 bg-white mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <select class="form-select" style="width: 100px;">
                <option value="">이름</option>
            </select>
            <input type="text" class="form-control ms-2" style="width: 340px;" placeholder="검색어 입력">
        </div>

        <div class="d-flex gap-2">
            <a href="#" id="search" class="btn btn-info">검색</a>
        </div>
    </div>
</div>

<div class="body flex-grow-1">
    <div class="container-lg px-4">
        <div class="card g-4 mb-4">
            <table class="table table-hover table-borderless">
                <thead>
                    <tr class="table-secondary">
                        <th scope="col" class="text-center">
                            <input type="checkbox" name="" id="">
                        </th>
                        <th scope="col" class="text-center">게시판 이름</th>
                        <th scope="col" class="text-center">게시판 권한</th>
                        <th scope="col" class="text-center">생성일</th>
                        <th scope="col" class="text-center">수정일</th>
                        <th scope="col" class="text-center">삭제일</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($list as $row)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" name[]="id" id="{{ $row->_id }}">
                        </td>
                        <td class="text-center">{{ $row->name }}</td>
                        <td class="text-center">
                            {{ collect((array) $row->access_control)->filter()->keys()->map(fn($key) => config('board.access_control')[$key] ?? $key)->implode(', ') }}
                        </td>
                        <td class="text-center">{{ $row->created_at }}</td>
                        <td class="text-center">{{ $row->updated_at }}</td>
                        <td class="text-center">{{ $row->deleted_at }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="5">데이터가 존재하지 않습니다.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>
            {{ $list->links() }}
        </div>
    </div>

    <!-- 생성 Modal -->
    <div class="modal" id="create-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">게시판 생성</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create-form">
                        <label for="create-member-id">게시판 이름</label>
                        <input type="text" class="form-control mb-4" id="create-member-id" name="name" value="">

                        <label class="form-label d-block">게시판 권한</label>
                        @foreach (config('board.access_control') as $key => $item)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="accessControl[]" value="{{ $key }}" id="{{ $key }}">
                            <label class="form-check-label" for="{{ $key }}">{{ $item }}</label>
                        </div>
                        @endforeach
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-success" id="create-btn">생성</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-scripts')
<script>
    $(document).ready(function () {
        $('#create').click(function (e) {
            e.preventDefault();
            showCreateModal();
        });
    });

    $(document).on('click', '#create-btn', function () {
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            type: 'post',
            url: '{{ route('admin.board.config.store') }}',
            data: $('#create-form').serialize(),
            success: function (response) {
                if (response.result) {
                    toastr.success('게시판 생성 완료');
                    location.reload();
                }
            },
            error: function (xhr, error, status) {
                Swal.fire({
                    title: '오류',
                    text: '요청 처리에 실패했습니다.\n' + (xhr.responseJSON?.message ?? '관리자에게 문의해 주세요.'),
                    icon: 'error',
                    confirmButtonText: '확인'
                });
            },
        });
    });

    function showCreateModal() {
        new bootstrap.Modal(document.getElementById('create-modal')).show();
    }
</script>
@endsection
