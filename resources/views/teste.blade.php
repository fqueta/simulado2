{{App\Qlib\Qlib::qForm([
    'type'=>'tel',
    'campo'=>'config[telefone2]',
    'placeholder'=>'Segundo con',
    'label'=>'Telefone 2',
    'ac'=>@$familia['ac'],
    'value'=>@$familia['config']['telefone2'],
    'tam'=>'6',
    'event'=>'onblur=mask(this,clientes_mascaraTelefone); onkeypress=mask(this,clientes_mascaraTelefone);',
])}}
