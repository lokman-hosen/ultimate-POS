<?php

/*
| Email texts (password reset, registration). Only English and Spanish are
| maintained; other locales fall back to English.
*/

return [
    'hello' => '¡Hola!',
    'whoops' => '¡Vaya!',
    'regards' => 'Saludos,',
    'all_rights_reserved' => 'Todos los derechos reservados.',
    'trouble_clicking' => 'Si tienes problemas para hacer clic en el botón ":actionText", copia y pega la siguiente URL en tu navegador:',
    'reset_password_subject' => ':app - Restablece tu contraseña',
    'reset_password_intro' => 'Has recibido este correo porque hemos recibido una solicitud para restablecer la contraseña de tu cuenta.',
    'reset_password_button' => 'Restablecer contraseña',
    'reset_password_expire' => 'Este enlace para restablecer la contraseña caducará en :count minutos.',
    'reset_password_ignore' => 'Si no has solicitado restablecer la contraseña, no tienes que hacer nada.',
    'welcome_subject' => 'Bienvenido a :app',
    'welcome_body' => '<p>Hola {owner_name}:</p><p>Gracias por registrar {business_name} en :app. Tu cuenta ya está lista y puedes iniciar sesión con tu usuario o tu correo electrónico.</p><p>Saludos,<br>El equipo de :app</p>',
    'new_business_subject' => 'Nuevo registro de empresa',
    'new_business_intro' => 'Se ha registrado una nueva empresa.',
    'new_business_details' => 'Empresa: :business, propietario: :owner, correo: :email, teléfono de contacto: :phone',
];
