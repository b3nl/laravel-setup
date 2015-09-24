<?php
use b3nl\LSetup\Jobs\ChangeFile as FileJob;
use Illuminate\Support\Str;

return [
    'env' => [
        'files' => [
            function (FileJob $job) {
                $job
                    ->setFile(
                        base_path() . DIRECTORY_SEPARATOR . '.env',
                        base_path() . DIRECTORY_SEPARATOR . '.env.example'
                    )
                    ->setProperties([
                        [
                            'APP_KEY',
                            'required',
                            Str::random(32)
                        ],
                        [
                            'DB_CONNECTION',
                            'sometimes',
                            'mysql'
                        ],
                        [
                            'DB_HOST',
                            'required|database',
                            ''
                        ],
                        [
                            'DB_DATABASE',
                            'required',
                            ''
                        ],
                        [
                            'DB_USERNAME',
                            'required',
                            ''
                        ],
                        [
                            'DB_PASSWORD',
                            'required',
                            ''
                        ]
                    ]);
            }
        ]
    ]
];
