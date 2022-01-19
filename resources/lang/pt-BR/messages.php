<?php

return [
  'system_error' => 'Erro de sistema.',
  'error_detect' => 'Um erro foi detectado. Cheque o vetor de erros.',
  'implementation_error' => 'Erro de implementação.',
  'not_found_error' => 'Item não encontrado.',
  'delete_error' => 'Erro na exclusão.',
  'up_to_date_error' => 'Dados já estão atualizados.',
  'confirm_email' => 'Um e-mail de confirmação de cadastro foi enviado ao endereço :email_address! Consulte sua caixa de entrada e/ou spam.',
  'send_id' => 'Envie um identificador!',
  'send_valid_id' => 'Envie um identificador válido!',
  'search_value_validation_fail' => 'A validação de um dos valores de busca falhou.',
  'different_value_validation_fail' => 'A validação de um dos valores diferentes falhou.',
  'compare_value_validation_fail' => 'A validação de um dos valores de comparação falhou.',
  'mail_not_set' => 'Entregador de e-mails não definido.',
  'mail_error' => 'Erro de E-mail: :error.',
  'mail_confirmation_title' => 'Confirme seu e-mail.',
  'establishment_not_found' => 'Estabelecimento não encontrado.',
  'can_not' => "Você não tem permissão.",
  'email_confirmed' => 'Seu e-mail foi confirmado.',
  'deleted' => ":Entity excluído(a) com sucesso!",
  'entity_already_exists_error' => 'Esta relação já existe no banco.',

  'entities' => [
    'city' => 'cidade',
    'establishment' => 'estabelecimento',
    'neighborhood' => 'bairro',
    'state' => 'estado',
    'street' => 'rua',
  ],

  'auth' => [
    'no_authorization' => 'Envie uma autorização válida!',
    'not_authorized' => 'Você não está autorizado a prosseguir com esta ação!',
  ],

  'bearer' => [
    'bad_format' => 'Bearer Token mal formatado!',
  ],

  'money' => [
    'cents_lesser_than_zero' => 'Os centavos do objeto MoneyData são menores que zero.',
    'cents_greater_than_one_hundred' => 'Os centavos do objeto MoneyData são maiores que cem.',
  ],
];
