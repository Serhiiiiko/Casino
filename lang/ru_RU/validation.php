<?php

return [

    'accepted'             => 'Поле :attribute должно быть принято.',
    'accepted_if'          => 'Поле :attribute должно быть принято, когда :other равно :value.',
    'active_url'           => 'Поле :attribute не является валидным URL.',
    'after'                => 'Поле :attribute должно быть датой, идущей после :date.',
    'after_or_equal'       => 'Поле :attribute должно быть датой не раньше (после или равной) :date.',
    'alpha'                => 'Поле :attribute может содержать только буквы.',
    'alpha_dash'           => 'Поле :attribute может содержать только буквы, цифры и дефисы.',
    'alpha_num'            => 'Поле :attribute может содержать только буквы и цифры.',
    'array'                => 'Поле :attribute должно быть массивом.',
    'before'               => 'Поле :attribute должно быть датой, идущей до :date.',
    'before_or_equal'      => 'Поле :attribute должно быть датой не позже (до или равной) :date.',
    'between'              => [
        'numeric' => 'Поле :attribute должно быть между :min и :max.',
        'file'    => 'Размер файла в поле :attribute должен быть от :min до :max КБ.',
        'string'  => 'Длина текста в поле :attribute должна быть от :min до :max символов.',
        'array'   => 'Количество элементов в поле :attribute должно быть от :min до :max.',
    ],
    'boolean'              => 'Поле :attribute должно иметь значение true или false.',
    'confirmed'            => 'Поле :attribute не совпадает с подтверждением.',
    'current_password'     => 'Неверный пароль.',
    'date'                 => 'Поле :attribute не является валидной датой.',
    'date_equals'          => 'Поле :attribute должно быть датой, равной :date.',
    'date_format'          => 'Поле :attribute не соответствует формату :format.',
    'declined'             => 'Поле :attribute должно быть отклонено.',
    'declined_if'          => 'Поле :attribute должно быть отклонено, когда :other равно :value.',
    'different'            => 'Поля :attribute и :other должны отличаться.',
    'digits'               => 'Поле :attribute должно содержать :digits цифр.',
    'digits_between'       => 'Поле :attribute должно содержать от :min до :max цифр.',
    'dimensions'           => 'Поле :attribute имеет недопустимые размеры изображения.',
    'distinct'             => 'Поле :attribute содержит повторяющееся значение.',
    'doesnt_start_with'    => 'Поле :attribute не может начинаться с одного из следующих: :values.',
    'email'                => 'Поле :attribute должно содержать корректный адрес электронной почты.',
    'ends_with'            => 'Поле :attribute должно заканчиваться одним из следующих: :values',
    'enum'                 => 'Выбранное значение для :attribute некорректно.',
    'exists'               => 'Выбранное значение для :attribute некорректно.',
    'file'                 => 'Поле :attribute должно быть файлом.',
    'filled'               => 'Поле :attribute должно иметь значение.',
    'gt' => [
        'numeric' => 'Поле :attribute должно быть больше, чем :value.',
        'file'    => 'Размер файла :attribute должен быть больше, чем :value КБ.',
        'string'  => 'Длина текста в поле :attribute должна превышать :value символов.',
        'array'   => 'Количество элементов в поле :attribute должно быть больше :value.',
    ],
    'gte' => [
        'numeric' => 'Поле :attribute должно быть больше или равно :value.',
        'file'    => 'Размер файла :attribute должен быть больше или равен :value КБ.',
        'string'  => 'Длина текста в поле :attribute должна быть не короче :value символов.',
        'array'   => 'Количество элементов в поле :attribute должно быть :value или больше.',
    ],
    'image'                => 'Поле :attribute должно быть изображением.',
    'in'                   => 'Выбранное значение для :attribute некорректно.',
    'in_array'             => 'Поле :attribute не существует в :other.',
    'integer'              => 'Поле :attribute должно быть целым числом.',
    'ip'                   => 'Поле :attribute должно быть валидным IP-адресом.',
    'ipv4'                 => 'Поле :attribute должно быть валидным адресом IPv4.',
    'ipv6'                 => 'Поле :attribute должно быть валидным адресом IPv6.',
    'json'                 => 'Поле :attribute должно быть валидной JSON-строкой.',
    'lt' => [
        'numeric' => 'Поле :attribute должно быть меньше :value.',
        'file'    => 'Размер файла :attribute должен быть меньше :value КБ.',
        'string'  => 'Длина текста в поле :attribute должна быть меньше :value символов.',
        'array'   => 'Количество элементов в поле :attribute должно быть меньше :value.',
    ],
    'lte' => [
        'numeric' => 'Поле :attribute должно быть меньше или равно :value.',
        'file'    => 'Размер файла :attribute должен быть меньше или равен :value КБ.',
        'string'  => 'Длина текста в поле :attribute должна быть не длиннее :value символов.',
        'array'   => 'Количество элементов в поле :attribute не должно превышать :value.',
    ],
    'max' => [
        'numeric' => 'Поле :attribute не может быть больше :max.',
        'file'    => 'Размер файла :attribute не может превышать :max КБ.',
        'string'  => 'Поле :attribute не может содержать больше :max символов.',
        'array'   => 'Поле :attribute не может содержать больше :max элементов.',
    ],
    'max_digits'           => 'Поле :attribute не может содержать более :max цифр.',
    'mimes'                => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'mimetypes'            => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'min' => [
        'numeric' => 'Значение поля :attribute должно быть не меньше :min.',
        'file'    => 'Размер файла :attribute должен быть не меньше :min КБ.',
        'string'  => 'Поле :attribute должно содержать не менее :min символов.',
        'array'   => 'Поле :attribute должно содержать не менее :min элементов.',
    ],
    'missing_with'         => 'Поле :attribute не должно присутствовать, если есть :values.',
    'min_digits'           => 'Поле :attribute должно содержать как минимум :min цифр.',
    'multiple_of'          => 'Поле :attribute должно быть кратно :value.',
    'not_in'               => 'Выбранное значение для :attribute некорректно.',
    'not_regex'            => 'Формат поля :attribute неверен.',
    'numeric'              => 'Поле :attribute должно быть числом.',
    'password' => [
        'letters'       => 'Поле :attribute должно содержать хотя бы одну букву.',
        'mixed'         => 'Поле :attribute должно содержать хотя бы одну заглавную и одну строчную букву.',
        'numbers'       => 'Поле :attribute должно содержать хотя бы одну цифру.',
        'symbols'       => 'Поле :attribute должно содержать хотя бы один специальный символ.',
        'uncompromised' => 'Указанный :attribute был обнаружен в утечках данных. Пожалуйста, используйте другой пароль.',
    ],
    'present'              => 'Поле :attribute должно присутствовать.',
    'regex'                => 'Формат поля :attribute неверен.',
    'required'             => 'Поле :attribute обязательно для заполнения.',
    'required_array_keys'  => 'Поле :attribute должно содержать записи для: :values.',
    'required_if'          => 'Поле :attribute обязательно, когда :other равно :value.',
    'required_unless'      => 'Поле :attribute обязательно, если :other не равно :values.',
    'required_with'        => 'Поле :attribute обязательно, когда присутствует :values.',
    'required_with_all'    => 'Поле :attribute обязательно, когда присутствуют :values.',
    'required_without'     => 'Поле :attribute обязательно, когда :values отсутствует.',
    'required_without_all' => 'Поле :attribute обязательно, когда ни одно из :values не присутствует.',
    'prohibited'           => 'Поле :attribute запрещено.',
    'prohibited_if'        => 'Поле :attribute запрещено, когда :other равно :value.',
    'prohibited_unless'    => 'Поле :attribute запрещено, если :other не равно :values.',
    'prohibits'            => 'Поле :attribute запрещает наличие поля :other.',
    'same'                 => 'Поля :attribute и :other должны совпадать.',
    'size' => [
        'numeric' => 'Значение поля :attribute должно быть :size.',
        'file'    => 'Размер файла :attribute должен быть :size КБ.',
        'string'  => 'Поле :attribute должно содержать :size символов.',
        'array'   => 'Поле :attribute должно содержать :size элементов.',
    ],
    'starts_with'          => 'Поле :attribute должно начинаться с одного из следующих: :values',
    'string'               => 'Поле :attribute должно быть строкой.',
    'timezone'             => 'Поле :attribute должно быть валидным часовым поясом.',
    'unique'               => 'Такое значение поля :attribute уже используется.',
    'uploaded'             => 'Не удалось загрузить :attribute.',
    'url'                  => 'Поле :attribute имеет неверный формат URL.',
    'uuid'                 => 'Поле :attribute должно быть валидным UUID.',

    /*
    |--------------------------------------------------------------------------
    | Собственные сообщения валидации
    |--------------------------------------------------------------------------
    |
    | Здесь вы можете указать собственные сообщения для правил валидации,
    | используя соглашение "attribute.rule" для именования строк. Это помогает
    | быстро задать специальное сообщение для конкретного правила атрибута.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Собственные названия атрибутов
    |--------------------------------------------------------------------------
    |
    | Следующие строки используются для замены плейсхолдера атрибута
    | на что-то более удобочитаемое, например "E-Mail Address"
    | вместо "email". Это просто помогает сделать сообщения выразительнее.
    |
    */

    'attributes' => [
        'address'              => 'адрес',
        'age'                  => 'возраст',
        'body'                 => 'контент',
        'cell'                 => 'сотовый',
        'city'                 => 'город',
        'country'              => 'страна',
        'date'                 => 'дата',
        'day'                  => 'день',
        'excerpt'              => 'выдержка',
        'first_name'           => 'имя',
        'gender'               => 'пол',
        'marital_status'       => 'семейное положение',
        'profession'           => 'профессия',
        'nationality'          => 'национальность',
        'hour'                 => 'час',
        'last_name'            => 'фамилия',
        'message'              => 'сообщение',
        'minute'               => 'минута',
        'mobile'               => 'мобильный',
        'month'                => 'месяц',
        'name'                 => 'имя',
        'zipcode'              => 'индекс',
        'company_name'         => 'название компании',
        'neighborhood'         => 'район',
        'number'               => 'номер',
        'password'             => 'пароль',
        'phone'                => 'телефон',
        'second'               => 'секунда',
        'sex'                  => 'пол',
        'state'                => 'область',
        'street'               => 'улица',
        'subject'              => 'тема',
        'text'                 => 'текст',
        'time'                 => 'время',
        'title'                => 'заголовок',
        'username'             => 'имя пользователя',
        'year'                 => 'год',
        'description'          => 'описание',
        'password_confirmation'=> 'подтверждение пароля',
        'current_password'     => 'текущий пароль',
        'complement'           => 'дополнение',
        'modality'             => 'модальность',
        'category'             => 'категория',
        'blood_type'           => 'группа крови',
        'birth_date'           => 'дата рождения',
    ],

];
