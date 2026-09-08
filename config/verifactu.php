<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AEAT VERI*FACTU Configuration
    |--------------------------------------------------------------------------
    */

    'environment' => env('VERIFACTU_ENVIRONMENT', 'test'), // test, production

    'urls' => [
        'test' => [
            'wsdl' => storage_path('app/verifactu/wsdl/SistemaFacturacion.wsdl'),
            'endpoint' => env('VERIFACTU_TEST_ENDPOINT', 'https://prewww1.aeat.es/wlpl/TIKE-CONT/ws/SistemaFacturacion/VerifactuSOAP'),
        ],
        'production' => [
            'wsdl' => storage_path('app/verifactu/wsdl/SistemaFacturacion.wsdl'),
            'endpoint' => env('VERIFACTU_PROD_ENDPOINT', 'https://www1.aeat.es/wlpl/TIKE-CONT/ws/SistemaFacturacion/VerifactuSOAP'),
        ],
    ],

    'certificate' => [
        'path' => env('VERIFACTU_CERT_PATH', storage_path('app/verifactu/certs/certificate.p12')),
        'password' => env('VERIFACTU_CERT_PASSWORD', ''),
        'type' => env('VERIFACTU_CERT_TYPE', 'p12'), // p12, pem
    ],

    /*
    |--------------------------------------------------------------------------
    | Collaboration Model
    |--------------------------------------------------------------------------
    | Option A: Send with client's own certificate
    | Option B: Use "Colaboración Social" (recommended for SaaS)
    */
    'representation_model' => env('VERIFACTU_REPRESENTATION_MODEL', 'B'), // A or B

    /*
    |--------------------------------------------------------------------------
    | Collaboration Data (For Option B)
    |--------------------------------------------------------------------------
    */
    'collaborator' => [
        'nif' => env('VERIFACTU_COLLABORATOR_NIF', ''),
        'name' => env('VERIFACTU_COLLABORATOR_NAME', ''),
        'code' => env('VERIFACTU_COLLABORATOR_CODE', ''), // Your collaborator registration code
    ],

    'retry' => [
        'max_attempts' => 3,
        'delay_minutes' => [5, 15, 30],
        'backoff_strategy' => 'incremental',
    ],

    'timeout' => env('VERIFACTU_TIMEOUT', 60),

    /*
    |--------------------------------------------------------------------------
    | Billing Software Details
    |--------------------------------------------------------------------------
    */
    'system_info' => [
        'software_name' => env('VERIFACTU_SOFTWARE_NAME', 'UltimatePOS'),
        'software_id' => env('VERIFACTU_SOFTWARE_ID', 'UPOS-VERIFACTU-01'),
        'version' => env('VERIFACTU_SOFTWARE_VERSION', '1.0'),
        'install_id' => env('VERIFACTU_INSTALL_ID', '01'),
    ],
];
