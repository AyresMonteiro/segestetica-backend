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

    'accepted' => 'O campo ":attribute" precisa ser aceito.',
    'active_url' => 'O campo ":attribute" não é uma URL válida.',
    'after' => 'A data ":attribute" precisa ser posterior à data ":date".',
    'after_or_equal' => 'A data ":attribute" precisa ser posterior ou igual à data ":date".',
    'alpha' => 'O campo ":attribute" só pode conter letras.',
    'alpha_dash' => 'O campo ":attribute" só pode conter letras, números, traços e sublinhados.',
    'alpha_num' => 'O campo ":attribute" só pode conter letras e números.',
    'array' => 'O campo ":attribute" precisa ser um vetor.',
    'before' => 'A data ":attribute" precisa ser anterior à data ":date".',
    'before_or_equal' => 'A data ":attribute" precisa ser anterior ou igual à data ":date".',
    'between' => [
        'numeric' => 'O número ":attribute" precisa estar entre :min e :max.',
        'file' => 'O tamanho do arquivo ":attribute" precisa estar entre :min e :max kilobytes.',
        'string' => 'O tamanho da string ":attribute" precisa estar entre :min e :max caracteres.',
        'array' => 'O vetor ":attribute" precisa conter de :min à :max itens.',
    ],
    'boolean' => 'O campo ":attribute" precisa ser verdadeiro ou falso.',
    'confirmed' => 'O confirmação do ":attribute" é inválida.',
    'current_password' => 'Senha inválida.',
    'date' => 'O campo ":attribute" não é uma data válida.',
    'date_equals' => 'A data ":attribute" precisa ser igual à :date.',
    'date_format' => 'A data ":attribute" não é do formato :format.',
    'different' => 'O campo ":attribute" e o campo ":other" precisam ser diferentes.',
    'digits' => 'O campo ":attribute" precisa ter :digits digitos.',
    'digits_between' => 'O campo ":attribute" precisa ter entre :min e :max digitos.',
    'dimensions' => 'O campo ":attribute" tem dimensões de imagem inválidas.',
    'distinct' => 'O campo ":attribute" tem valores duplicados.',
    'email' => 'O campo ":attribute" precisa ser um endereço de e-mail válido.',
    'ends_with' => 'O campo ":attribute" precisa terminar com um dos seguintes valores: :values.',
    'exists' => 'O campo ":attribute" selecionado é inválido.',
    'file' => 'O campo ":attribute" precisa ser um arquivo.',
    'filled' => 'O campo ":attribute" precisa ser preenchido.',
    'gt' => [
        'numeric' => 'O valor ":attribute" precisa ser maior que :value.',
        'file' => 'O arquivo ":attribute" precisa ter mais que :value kilobytes.',
        'string' => 'A string ":attribute" precisa ter um tamanho maior que :value caracteres.',
        'array' => 'O vetor ":attribute" precisa ter mais que :value itens.',
    ],
    'gte' => [
        'numeric' => 'O valor ":attribute" precisa ser maior ou igual à :value.',
        'file' => 'O arquivo ":attribute" precisa ter :value kilobytes ou mais.',
        'string' => 'A string ":attribute" precisa ter :value caracteres ou mais.',
        'array' => 'O vetor ":attribute" precisa ter :value itens ou mais.',
    ],
    'image' => 'O campo ":attribute" precisa ser uma imagem.',
    'in' => 'O campo ":attribute" selecionado é inválido.',
    'in_array' => 'O campo ":attribute" não existe no vetor ":other".',
    'integer' => 'O campo ":attribute" precisa ser um inteiro.',
    'ip' => 'O campo ":attribute" precisa ser um endereço IP válido.',
    'ipv4' => 'O campo ":attribute" precisa ser um endereço IPv4 válido.',
    'ipv6' => 'O campo ":attribute" precisa ser um endereço IPv6 válido.',
    'json' => 'O campo ":attribute" precisa ser uma string em formato JSON',
    'lt' => [
        'numeric' => 'O valor ":attribute" precisa ser menor que :value.',
        'file' => 'O arquivo ":attribute" precisa ter menos que :value kilobytes.',
        'string' => 'A string ":attribute" precisa ter um tamanho menor que :value caracteres.',
        'array' => 'O vetor ":attribute" precisa ter menos que :value itens.',
    ],
    'lte' => [
        'numeric' => 'O valor ":attribute" precisa ser menor ou igual à :value.',
        'file' => 'O arquivo ":attribute" precisa ter :value kilobytes ou menos.',
        'string' => 'A string ":attribute" precisa ter :value caracteres ou menos.',
        'array' => 'O vetor ":attribute" precisa ter :value itens ou menos.',
    ],
    'max' => [
        'numeric' => 'O valor ":attribute" precisa ser no máximo :value.',
        'file' => 'O arquivo ":attribute" precisa ter no máximo :value kilobytes.',
        'string' => 'A string ":attribute" precisa ter um tamanho máximo de :value caracteres.',
        'array' => 'O vetor ":attribute" precisa ter no máximo :value itens.',
    ],
    'mimes' => 'O campo ":attribute" precisa ser um arquivo de um dos seguintes formatos: :values.',
    'mimetypes' => 'O campo ":attribute" precisa ser um arquivo de um dos seguintes formatos: :values.',
    'min' => [
        'numeric' => 'O valor ":attribute" precisa ser no mínimo :value.',
        'file' => 'O arquivo ":attribute" precisa ter no mínimo :value kilobytes.',
        'string' => 'A string ":attribute" precisa ter um tamanho mínimo de :value caracteres.',
        'array' => 'O vetor ":attribute" precisa ter no mínimo :value itens.',
    ],
    'multiple_of' => 'O campo ":attribute" precisa ser um múltiplo de :value.',
    'not_in' => 'O campo ":attribute" selecionado é inválido.',
    'not_regex' => 'O formato do campo ":attribute" é inválido.',
    'numeric' => 'O campo ":attribute" precisa ser um número.',
    'password' => 'Senha incorreta.',
    'present' => 'O campo ":attribute" precisa ser enviado.',
    'regex' => 'O formato do campo ":attribute" é inválido.',
    'required' => 'O campo ":attribute" é obrigatório.',
    'required_if' => 'O campo ":attribute" é obrigatório quando o campo ":other" é ":value".',
    'required_unless' => 'O campo ":attribute" é obrigatório a não ser que o campo ":other" esteja contido em ":values".',
    'required_with' => 'O campo ":attribute" é obrigatório quando o(s) campo(s) ":values" estão presentes.',
    'required_with_all' => 'O campo ":attribute" é obrigatório quando todos os campos ":values" estão presentes.',
    'required_without' => 'O campo ":attribute" é obrigatório quando o(s) campo(s) ":values" não estão presentes.',
    'required_without_all' => 'O campo ":attribute" é obrigatório quando todos dos campos ":values" não estão presentes.',
    'prohibited' => 'O campo ":attribute" é proibido.',
    'prohibited_if' => 'O campo ":attribute" é proibido quando ":other" é :value.',
    'prohibited_unless' => 'O campo ":attribute" é proibido a não ser que o campo ":other" esteja contido em ":values".',
    'same' => 'Os campos ":attribute" e ":other" precisam ser iguais.',
    'size' => [
        'numeric' => 'O valor ":attribute" precisa ser :size.',
        'file' => 'O arquivo ":attribute" precisa ter :size kilobytes.',
        'string' => 'A string ":attribute" precisa ter :size caracteres.',
        'array' => 'O vetor ":attribute" precisa ter :size itens.',
    ],
    'starts_with' => 'O campo ":attribute" precisa começar com um dos seguintes valores: :values.',
    'string' => 'O campo ":attribute" precisa ser uma string.',
    'timezone' => 'O campo ":attribute" precisa ser um fuso horário válido.',
    'unique' => 'O campo ":attribute" já foi usado.',
    'uploaded' => 'O envio do arquivo ":attribute" falhou.',
    'url' => 'O campo ":attribute" precisa ser uma URL válida.',
    'uuid' => 'O campo ":attribute" precisa ser um UUID válido.',

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

    'attributes' => [
        'email' => 'e-mail',
        'password' => 'senha',
        'name' => 'nome',
        'abbreviation' => 'sigla',
        'stateId' => 'id_estado',
    ],

];
