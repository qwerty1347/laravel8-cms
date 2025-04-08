<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>통합회원 전환</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
        }
        .container {
            width: 400px;
            padding: 40px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            text-align: center;
        }
        h1 {
            font-size: 24px;
        }
        p {
            margin: 20px 0;
            font-size: 18px;
        }
        .buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }
        button {
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            height: 40px;
        }
    </style>
    @include('layouts.user.common.links')
    @include('layouts.user.common.scripts')
</head>
<body>
    <div class="container" style="max-width: 480px;">
        <h1>통합회원 전환</h1>
        <p>기존에 회원가입한 아이디가 존재합니다.<br>지금 통합회원으로 전환 하시겠습니까?</p>
        <div class="buttons">
            <button type="button" class="btn btn-primary" id="link">전환하기</button>
            <button type="button" class="btn btn-light" id="cancel">취소</button>
        </div>
    </div>
    <script>
        $('#link').click(function (e) {
            e.preventDefault();
            const queryString = window.location.search + '&link=Y';
            test = "{{ route('social.google.callback') }}" + queryString;
            location.href = test;
        });

        $('#cancel').click(function (e) {
            e.preventDefault();
            location.href = "/login";
        });
    </script>
</body>
</html>
