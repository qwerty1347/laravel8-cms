<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute는(은) 수락되어야 합니다.',
    'accepted_if' => ':attribute는 :other가 :value일 때 수락되어야 합니다.',
    'active_url' => ':attribute는 유효한 URL이 아닙니다.',
    'after' => ':attribute는 :date 이후의 날짜여야 합니다.',
    'after_or_equal' => ':attribute는 :date 이후이거나 같아야 합니다.',
    'alpha' => ':attribute는 문자만 포함할 수 있습니다.',
    'alpha_dash' => ':attribute는 문자, 숫자, 대시(-) 및 밑줄(_)만 포함할 수 있습니다.',
    'alpha_num' => ':attribute는 문자와 숫자만 포함할 수 있습니다.',
    'array' => ':attribute는 배열이어야 합니다.',
    'before' => ':attribute는 :date 이전의 날짜여야 합니다.',
    'before_or_equal' => ':attribute는 :date 이전이거나 같아야 합니다.',
    'between' => [
        'numeric' => ':attribute는 :min과 :max 사이여야 합니다.',
        'file' => ':attribute는 :min과 :max 킬로바이트 사이여야 합니다.',
        'string' => ':attribute는 :min과 :max 문자 사이여야 합니다.',
        'array' => ':attribute는 :min과 :max 항목을 포함해야 합니다.',
    ],
    'boolean' => ':attribute 은(는) true 또는 false여야 합니다.',
    'confirmed' => ':attribute 확인이 일치하지 않습니다.',
    'current_password' => '비밀번호가 올바르지 않습니다.',
    'date' => ':attribute는 유효한 날짜가 아닙니다.',
    'date_equals' => ':attribute는 :date와 같은 날짜여야 합니다.',
    'date_format' => ':attribute는 :format 형식과 일치하지 않습니다.',
    'declined' => ':attribute는 거부되어야 합니다.',
    'declined_if' => ':attribute는 :other가 :value일 때 거부되어야 합니다.',
    'different' => ':attribute와 :other는 달라야 합니다.',
    'digits' => ':attribute는 :digits 자릿수여야 합니다.',
    'digits_between' => ':attribute는 :min과 :max 자릿수 사이여야 합니다.',
    'dimensions' => ':attribute의 이미지 치수가 올바르지 않습니다.',
    'distinct' => ':attribute 은(는) 중복된 값이 있습니다.',
    'email' => ':attribute는 유효한 이메일 주소여야 합니다.',
    'ends_with' => ':attribute는 다음 중 하나로 끝나야 합니다: :values.',
    'enum' => '선택된 :attribute가 유효하지 않습니다.',
    'exists' => '선택된 :attribute가 유효하지 않습니다.',
    'file' => ':attribute는 파일이어야 합니다.',
    'filled' => ':attribute 은(는) 값을 가져야 합니다.',
    'gt' => [
        'numeric' => ':attribute는 :value보다 커야 합니다.',
        'file' => ':attribute는 :value 킬로바이트보다 커야 합니다.',
        'string' => ':attribute는 :value 문자보다 길어야 합니다.',
        'array' => ':attribute는 :value 개 이상의 항목을 포함해야 합니다.',
    ],
    'gte' => [
        'numeric' => ':attribute는 :value보다 크거나 같아야 합니다.',
        'file' => ':attribute는 :value 킬로바이트보다 크거나 같아야 합니다.',
        'string' => ':attribute는 :value 문자보다 길거나 같아야 합니다.',
        'array' => ':attribute는 :value 항목 이상이어야 합니다.',
    ],
    'image' => ':attribute는 이미지여야 합니다.',
    'in' => '선택된 :attribute가 유효하지 않습니다.',
    'in_array' => ':attribute 은(는) :other에 존재하지 않습니다.',
    'integer' => ':attribute는 정수여야 합니다.',
    'ip' => ':attribute는 유효한 IP 주소여야 합니다.',
    'ipv4' => ':attribute는 유효한 IPv4 주소여야 합니다.',
    'ipv6' => ':attribute는 유효한 IPv6 주소여야 합니다.',
    'json' => ':attribute는 유효한 JSON 문자열이어야 합니다.',
    'lt' => [
        'numeric' => ':attribute는 :value보다 작아야 합니다.',
        'file' => ':attribute는 :value 킬로바이트보다 작아야 합니다.',
        'string' => ':attribute는 :value 문자보다 짧아야 합니다.',
        'array' => ':attribute는 :value 개 이하의 항목을 포함해야 합니다.',
    ],
    'lte' => [
        'numeric' => ':attribute는 :value보다 작거나 같아야 합니다.',
        'file' => ':attribute는 :value 킬로바이트보다 작거나 같아야 합니다.',
        'string' => ':attribute는 :value 문자보다 짧거나 같아야 합니다.',
        'array' => ':attribute는 :value 개 이상의 항목을 포함해서는 안 됩니다.',
    ],
    'mac_address' => ':attribute는 유효한 MAC 주소여야 합니다.',
    'max' => [
        'numeric' => ':attribute는 :max보다 클 수 없습니다.',
        'file' => ':attribute는 :max 킬로바이트를 초과할 수 없습니다.',
        'string' => ':attribute는 :max 문자를 초과할 수 없습니다.',
        'array' => ':attribute는 :max 개를 초과할 수 없습니다.',
    ],
    'mimes' => ':attribute는 다음 형식의 파일이어야 합니다: :values.',
    'mimetypes' => ':attribute는 다음 형식의 파일이어야 합니다: :values.',
    'min' => [
        'numeric' => ':attribute는 최소 :min이어야 합니다.',
        'file' => ':attribute는 최소 :min 킬로바이트여야 합니다.',
        'string' => ':attribute는 최소 :min 문자여야 합니다.',
        'array' => ':attribute는 최소 :min 개의 항목을 포함해야 합니다.',
    ],
    'multiple_of' => ':attribute는 :value의 배수여야 합니다.',
    'not_in' => '선택된 :attribute가 유효하지 않습니다.',
    'not_regex' => ':attribute 형식이 유효하지 않습니다.',
    'numeric' => ':attribute는 숫자여야 합니다.',
    'password' => '비밀번호가 올바르지 않습니다.',
    'present' => ':attribute 은(는) 존재해야 합니다.',
    'prohibited' => ':attribute 은(는) 금지됩니다.',
    'prohibited_if' => ':attribute 은(는) :other가 :value일 때 금지됩니다.',
    'prohibited_unless' => ':attribute 은(는) :other가 :values 중 하나일 때 금지되지 않습니다.',
    'prohibits' => ':attribute 은(는) :other가 존재하는 것을 금지합니다.',
    'regex' => ':attribute 형식이 유효하지 않습니다.',
    'required' => ':attribute 은(는) 필수입니다.',
    'required_array_keys' => ':attribute 은(는) 다음 항목의 입력을 포함해야 합니다: :values.',
    'required_if' => ':attribute 은(는) :other가 :value일 때 필수입니다.',
    'required_unless' => ':attribute 은(는) :other가 :values 중 하나가 아닐 때 필수입니다.',
    'required_with' => ':attribute 은(는) :values가 있을 때 필수입니다.',
    'required_with_all' => ':attribute 은(는) :values가 있을 때 필수입니다.',
    'required_without' => ':attribute 은(는) :values가 없을 때 필수입니다.',
    'required_without_all' => ':attribute 은(는) :values가 없을 때 필수입니다.',
    'same' => ':attribute와 :other는 일치해야 합니다.',
    'size' => [
        'numeric' => ':attribute는 :size이어야 합니다.',
        'file' => ':attribute는 :size 킬로바이트여야 합니다.',
        'string' => ':attribute는 :size 문자여야 합니다.',
        'array' => ':attribute는 :size 개의 항목을 포함해야 합니다.',
    ],
    'starts_with' => ':attribute는 다음 중 하나로 시작해야 합니다: :values.',
    'string' => ':attribute는 문자열이어야 합니다.',
    'timezone' => ':attribute는 유효한 시간대여야 합니다.',
    'unique' => ':attribute는 이미 사용 중입니다.',
    'uploaded' => ':attribute 업로드에 실패했습니다.',
    'url' => ':attribute는 유효한 URL이어야 합니다.',
    'uuid' => ':attribute는 유효한 UUID여야 합니다.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
