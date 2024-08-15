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

    'accepted' => 'Field :attribute harus diterima.',
    'active_url' => 'Field :attribute bukan URL yang sah.',
    'after' => 'Field :attribute harus tanggal setelah :date.',
    'after_or_equal' => 'Field :attribute harus tanggal sesudah atau sama dengan :date.',
    'alpha' => 'Field :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'Field :attribute hanya boleh berisi huruf, angka, dan strip.',
    'alpha_num' => 'Field :attribute hanya boleh berisi huruf dan angka.',
    'array' => 'Field :attribute harus berupa sebuah array.',
    'before' => 'Field :attribute harus tanggal sebelum :date.',
    'before_or_equal' => 'Field :attribute harus tanggal sebelum atau sama dengan :date.',
    'between' => [
        'numeric' => 'Field :attribute harus antara :min dan :max.',
        'file' => 'Field :attribute harus antara :min dan :max kilobytes.',
        'string' => 'Field :attribute harus antara :min dan :max karakter.',
        'array' => 'Field :attribute harus antara :min dan :max item.',
    ],
    'boolean' => 'Field :attribute harus berupa true atau false',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'date' => 'Field :attribute bukan tanggal yang valid.',
    'date_format' => 'Field :attribute tidak cocok dengan format :format.',
    'different' => 'Field :attribute dan :other harus berbeda.',
    'digits' => 'Field :attribute harus berupa angka :digits.',
    'digits_between' => 'Field :attribute harus antara angka :min dan :max.',
    'dimensions' => 'Field :attribute harus merupakan dimensi gambar yang sah.',
    'distinct' => 'Field :attribute memiliki nilai yang duplikat.',
    'email' => 'Field :attribute harus berupa alamat surel yang valid.',
    'exists' => 'Field :attribute yang dipilih tidak valid.',
    'file' => 'Field :attribute harus berupa file.',
    'filled' => 'Field :attribute wajib diisi.',
    'gt' => [
        'numeric' => 'Field :attribute harus lebih besar dari :value.',
        'file' => 'Field :attribute harus lebih besar dari :value kilobytes.',
        'string' => 'Field :attribute harus lebih besar dari :value karakter.',
        'array' => 'Field :attribute harus lebih besar dari :value items.',
    ],
    'gte' => [
        'numeric' => 'Field :attribute harus lebih besar dari atau sama dengan :value.',
        'file' => 'Field :attribute harus lebih besar dari atau sama dengan :value kilobytes.',
        'string' => 'Field :attribute harus lebih besar dari atau sama dengan :value karakter.',
        'array' => 'Field :attribute harus memiliki :value item atau lebih.',
    ],
    'image' => 'Field :attribute harus berupa gambar.',
    'in' => 'Field :attribute yang dipilih tidak valid.',
    'in_array' => 'Field :attribute tidak terdapat dalam :other.',
    'integer' => 'Field :attribute harus merupakan bilangan bulat.',
    'ip' => 'Field :attribute harus berupa alamat IP yang valid.',
    'ipv4' => 'Field :attribute harus berupa alamat IPv4.',
    'ipv6' => 'Field :attribute harus berupa alamat IPv6.',
    'json' => 'Field :attribute harus berupa JSON string yang valid.',
    'lt' => [
        'numeric' => 'Field :attribute harus lebih kecil dari :value.',
        'file' => 'Field :attribute harus lebih kecil dari :value kilobytes.',
        'string' => 'Field :attribute harus lebih kecil dari :value karakter.',
        'array' => 'Field :attribute harus memiliki kurang dari :value item.',
    ],
    'lte' => [
        'numeric' => 'Field :attribute harus lebih kecil dari atau sama dengan :value.',
        'file' => 'Field :attribute harus lebih kecil dari atau sama dengan :value kilobytes.',
        'string' => 'Field :attribute harus lebih kecil dari atau sama dengan:value karakter.',
        'array' => 'Field :attribute tidak boleh lebih dari :value item.',
    ],
    'max' => [
        'numeric' => 'Field :attribute seharusnya tidak lebih dari :max.',
        'file' => 'Field :attribute seharusnya tidak lebih dari :max kilobytes.',
        'string' => 'Field :attribute seharusnya tidak lebih dari :max karakter.',
        'array' => 'Field :attribute seharusnya tidak lebih dari :max item.',
    ],
    'mimes' => 'Field :attribute harus dokumen berjenis : :values.',
    'mimetypes' => 'Field :attribute harus berupa tipe file : :values.',
    'min' => [
        'numeric' => 'Field :attribute harus minimal :min.',
        'file' => 'Field :attribute harus minimal :min kilobytes.',
        'string' => 'Field :attribute harus minimal :min karakter.',
        'array' => 'Field :attribute harus minimal :min item.',
    ],
    'not_in' => 'Field :attribute yang dipilih tidak valid.',
    'not_regex' => 'Format Field :attribute tidak valid.',
    'numeric' => 'Field :attribute harus berupa angka.',
    'present' => 'Field :attribute wajib ada.',
    'regex' => 'Format Field :attribute tidak valid.',
    'required' => 'Field :attribute wajib diisi.',
    'required_if' => 'Field :attribute wajib diisi bila :other adalah :value.',
    'required_unless' => 'Field :attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with' => 'Field :attribute wajib diisi bila terdapat :values.',
    'required_with_all' => 'Field :attribute wajib diisi bila terdapat :values.',
    'required_without' => 'Field :attribute wajib diisi bila tidak terdapat :values.',
    'required_without_all' => 'Field :attribute wajib diisi bila tidak terdapat ada :values.',
    'same' => 'Field :attribute dan :other harus sama.',
    'size' => [
        'numeric' => 'Field :attribute harus berukuran :size.',
        'file' => 'Field :attribute harus berukuran :size kilobyte.',
        'string' => 'Field :attribute harus berukuran :size karakter.',
        'array' => 'Field :attribute harus mengandung :size item.',
    ],
    'string' => 'Field :attribute harus berupa string.',
    'timezone' => 'Field :attribute harus berupa zona waktu yang valid.',
    'unique' => 'Field :attribute sudah ada sebelumnya.',
    'uploaded' => ':attribute gagal untuk di upload.',
    'url' => 'Format Field :attribute tidak valid.',
    'alpha_spaces' => 'Field :attribute hanya boleh berisi huruf dan spasi.',
    'alpha_specified_symbols' => 'Field :attribute hanya boleh berisi huruf, spasi, dan simbol tertentu seperti ? . , ! & (){}[]:; "" \'\' / - = +',
    'alpha_dash_only' => 'Field :attribute hanya boleh berisi huruf, strip, dan garis bawah.',

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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
