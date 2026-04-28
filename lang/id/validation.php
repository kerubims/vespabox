<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines (Bahasa Indonesia)
    |--------------------------------------------------------------------------
    */

    'accepted'             => ':attribute harus diterima.',
    'accepted_if'          => ':attribute harus diterima ketika :other bernilai :value.',
    'active_url'           => ':attribute bukan URL yang valid.',
    'after'                => ':attribute harus berisi tanggal setelah :date.',
    'after_or_equal'       => ':attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus berupa array.',
    'ascii'                => ':attribute hanya boleh berisi karakter alfanumerik satu byte dan simbol.',
    'before'               => ':attribute harus berisi tanggal sebelum :date.',
    'before_or_equal'      => ':attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'array'   => ':attribute harus memiliki antara :min dan :max item.',
        'file'    => ':attribute harus berukuran antara :min dan :max kilobyte.',
        'numeric' => ':attribute harus bernilai antara :min dan :max.',
        'string'  => ':attribute harus memiliki panjang antara :min dan :max karakter.',
    ],
    'boolean'              => ':attribute harus bernilai benar atau salah.',
    'can'                  => ':attribute berisi nilai yang tidak diizinkan.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'contains'             => ':attribute tidak berisi nilai yang diperlukan.',
    'current_password'     => 'Kata sandi saat ini salah.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'date_equals'          => ':attribute harus berisi tanggal yang sama dengan :date.',
    'date_format'          => ':attribute tidak sesuai dengan format :format.',
    'decimal'              => ':attribute harus memiliki :decimal angka desimal.',
    'declined'             => ':attribute harus ditolak.',
    'declined_if'          => ':attribute harus ditolak ketika :other bernilai :value.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus terdiri dari :digits digit.',
    'digits_between'       => ':attribute harus terdiri dari :min sampai :max digit.',
    'dimensions'           => ':attribute memiliki dimensi gambar yang tidak valid.',
    'distinct'             => ':attribute memiliki nilai duplikat.',
    'doesnt_end_with'      => ':attribute tidak boleh diakhiri dengan salah satu dari berikut: :values.',
    'doesnt_start_with'    => ':attribute tidak boleh diawali dengan salah satu dari berikut: :values.',
    'email'                => ':attribute harus berupa alamat email yang valid.',
    'ends_with'            => ':attribute harus diakhiri dengan salah satu dari berikut: :values.',
    'enum'                 => ':attribute yang dipilih tidak valid.',
    'exists'               => ':attribute yang dipilih tidak valid.',
    'extensions'           => ':attribute harus memiliki salah satu ekstensi berikut: :values.',
    'file'                 => ':attribute harus berupa file.',
    'filled'               => ':attribute harus diisi.',
    'gt'                   => [
        'array'   => ':attribute harus memiliki lebih dari :value item.',
        'file'    => ':attribute harus berukuran lebih dari :value kilobyte.',
        'numeric' => ':attribute harus bernilai lebih dari :value.',
        'string'  => ':attribute harus lebih dari :value karakter.',
    ],
    'gte'                  => [
        'array'   => ':attribute harus memiliki :value item atau lebih.',
        'file'    => ':attribute harus berukuran :value kilobyte atau lebih.',
        'numeric' => ':attribute harus bernilai :value atau lebih.',
        'string'  => ':attribute harus :value karakter atau lebih.',
    ],
    'hex_color'            => ':attribute harus berupa warna heksadesimal yang valid.',
    'image'                => ':attribute harus berupa gambar.',
    'in'                   => ':attribute yang dipilih tidak valid.',
    'in_array'             => ':attribute tidak ada di dalam :other.',
    'integer'              => ':attribute harus berupa bilangan bulat.',
    'ip'                   => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => ':attribute harus berupa string JSON yang valid.',
    'list'                 => ':attribute harus berupa daftar.',
    'lowercase'            => ':attribute harus berupa huruf kecil.',
    'lt'                   => [
        'array'   => ':attribute harus memiliki kurang dari :value item.',
        'file'    => ':attribute harus berukuran kurang dari :value kilobyte.',
        'numeric' => ':attribute harus bernilai kurang dari :value.',
        'string'  => ':attribute harus kurang dari :value karakter.',
    ],
    'lte'                  => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :value item.',
        'file'    => ':attribute harus berukuran :value kilobyte atau kurang.',
        'numeric' => ':attribute harus bernilai :value atau kurang.',
        'string'  => ':attribute harus :value karakter atau kurang.',
    ],
    'mac_address'          => ':attribute harus berupa alamat MAC yang valid.',
    'max'                  => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :max item.',
        'file'    => ':attribute tidak boleh lebih dari :max kilobyte.',
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'string'  => ':attribute tidak boleh lebih dari :max karakter.',
    ],
    'max_digits'           => ':attribute tidak boleh memiliki lebih dari :max digit.',
    'mimes'                => ':attribute harus berupa file bertipe: :values.',
    'mimetypes'            => ':attribute harus berupa file bertipe: :values.',
    'min'                  => [
        'array'   => ':attribute harus memiliki minimal :min item.',
        'file'    => ':attribute minimal harus :min kilobyte.',
        'numeric' => ':attribute minimal harus :min.',
        'string'  => ':attribute minimal harus :min karakter.',
    ],
    'min_digits'           => ':attribute harus memiliki minimal :min digit.',
    'missing'              => ':attribute tidak boleh ada.',
    'missing_if'           => ':attribute tidak boleh ada ketika :other bernilai :value.',
    'missing_unless'       => ':attribute tidak boleh ada kecuali :other bernilai :value.',
    'missing_with'         => ':attribute tidak boleh ada ketika :values ada.',
    'missing_with_all'     => ':attribute tidak boleh ada ketika :values ada.',
    'multiple_of'          => ':attribute harus kelipatan dari :value.',
    'not_in'               => ':attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => ':attribute harus berupa angka.',
    'password'             => [
        'letters'       => ':attribute harus mengandung minimal satu huruf.',
        'mixed'         => ':attribute harus mengandung minimal satu huruf besar dan satu huruf kecil.',
        'numbers'       => ':attribute harus mengandung minimal satu angka.',
        'symbols'       => ':attribute harus mengandung minimal satu simbol.',
        'uncompromised' => ':attribute yang diberikan ditemukan dalam kebocoran data. Silakan pilih :attribute yang berbeda.',
    ],
    'present'              => ':attribute harus ada.',
    'present_if'           => ':attribute harus ada ketika :other bernilai :value.',
    'present_unless'       => ':attribute harus ada kecuali :other bernilai :value.',
    'present_with'         => ':attribute harus ada ketika :values ada.',
    'present_with_all'     => ':attribute harus ada ketika :values ada.',
    'prohibited'           => ':attribute tidak diperbolehkan.',
    'prohibited_if'        => ':attribute tidak diperbolehkan ketika :other bernilai :value.',
    'prohibited_unless'    => ':attribute tidak diperbolehkan kecuali :other ada di :values.',
    'prohibits'            => ':attribute melarang :other untuk ada.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => ':attribute wajib diisi.',
    'required_array_keys'  => ':attribute harus berisi entri untuk: :values.',
    'required_if'          => ':attribute wajib diisi ketika :other bernilai :value.',
    'required_if_accepted' => ':attribute wajib diisi ketika :other diterima.',
    'required_if_declined' => ':attribute wajib diisi ketika :other ditolak.',
    'required_unless'      => ':attribute wajib diisi kecuali :other ada di :values.',
    'required_with'        => ':attribute wajib diisi ketika :values ada.',
    'required_with_all'    => ':attribute wajib diisi ketika :values ada.',
    'required_without'     => ':attribute wajib diisi ketika :values tidak ada.',
    'required_without_all' => ':attribute wajib diisi ketika tidak ada satupun dari :values yang ada.',
    'same'                 => ':attribute dan :other harus sama.',
    'size'                 => [
        'array'   => ':attribute harus memiliki :size item.',
        'file'    => ':attribute harus berukuran :size kilobyte.',
        'numeric' => ':attribute harus bernilai :size.',
        'string'  => ':attribute harus :size karakter.',
    ],
    'starts_with'          => ':attribute harus diawali dengan salah satu dari berikut: :values.',
    'string'               => ':attribute harus berupa string.',
    'timezone'             => ':attribute harus berupa zona waktu yang valid.',
    'unique'               => ':attribute sudah digunakan.',
    'uploaded'             => ':attribute gagal diunggah.',
    'uppercase'            => ':attribute harus berupa huruf besar.',
    'url'                  => ':attribute harus berupa URL yang valid.',
    'ulid'                 => ':attribute harus berupa ULID yang valid.',
    'uuid'                 => ':attribute harus berupa UUID yang valid.',

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
        'email' => [
            'unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain.',
        ],
        'phone' => [
            'unique' => 'Nomor WhatsApp ini sudah terdaftar. Silakan gunakan nomor lain.',
        ],
        'password' => [
            'confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'min'       => 'Kata sandi minimal harus :min karakter.',
        ],
        'current_password' => [
            'current_password' => 'Kata sandi saat ini yang Anda masukkan salah.',
        ],
        'kode' => [
            'unique' => 'Kode sparepart ini sudah ada. Gunakan kode yang berbeda.',
        ],
        'image' => [
            'image' => 'File yang diunggah harus berupa gambar.',
            'mimes' => 'Gambar harus berformat JPG, JPEG, PNG, atau WebP.',
            'max'   => 'Ukuran gambar tidak boleh lebih dari 2 MB.',
        ],
        'rating' => [
            'required' => 'Silakan berikan rating terlebih dahulu.',
            'min'      => 'Rating minimal adalah 1 bintang.',
            'max'      => 'Rating maksimal adalah 5 bintang.',
        ],
        'items' => [
            'required' => 'Tambahkan minimal satu item terlebih dahulu.',
            'min'      => 'Tambahkan minimal satu item terlebih dahulu.',
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

    'attributes' => [
        'name'              => 'Nama',
        'email'             => 'Email',
        'password'          => 'Kata Sandi',
        'password_confirmation' => 'Konfirmasi Kata Sandi',
        'current_password'  => 'Kata Sandi Saat Ini',
        'phone'             => 'Nomor WhatsApp',
        'plat_nomor'        => 'Plat Nomor',
        'kendaraan'         => 'Kendaraan',
        'keluhan'           => 'Keluhan',
        'tanggal'           => 'Tanggal',
        'jam'               => 'Jam',
        'kode'              => 'Kode Sparepart',
        'nama'              => 'Nama',
        'kategori'          => 'Kategori',
        'harga_beli'        => 'Harga Beli',
        'harga_jual'        => 'Harga Jual',
        'stok'              => 'Stok',
        'stok_minimum'      => 'Stok Minimum',
        'image'             => 'Gambar',
        'rating'            => 'Rating',
        'comment'           => 'Komentar',
        'admin_reply'       => 'Balasan',
        'booking_id'        => 'Booking',
        'customer_name'     => 'Nama Pelanggan',
        'catatan'           => 'Catatan',
        'status'            => 'Status',
        'items'             => 'Item',
        'slots'             => 'Slot',
    ],

];
