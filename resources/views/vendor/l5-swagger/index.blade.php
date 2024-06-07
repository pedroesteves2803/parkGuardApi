<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('l5-swagger.documentations.' . $documentation . '.api.title') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ l5_swagger_asset($documentation, 'swagger-ui.css') }}">
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-32x32.png') }}"
        sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-16x16.png') }}"
        sizes="16x16" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }

        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }

        body {
            margin: 0;
            background: #fafafa;
        }

        /* Estilizando o campo de entrada do token JWT */
        #jwt-token {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 9999;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            background-color: #fff;
        }
    </style>
</head>

<body>
    <div id="swagger-ui"></div>

    <!-- Adiciona o campo de entrada para o token JWT -->
    <input type="text" id="jwt-token" placeholder="Insira seu token JWT">

    <script src="{{ l5_swagger_asset($documentation, 'swagger-ui-bundle.js') }}"></script>
    <script src="{{ l5_swagger_asset($documentation, 'swagger-ui-standalone-preset.js') }}"></script>
    <script>
        window.onload = function() {
            // Build a system
            const ui = SwaggerUIBundle({
                dom_id: '#swagger-ui',
                url: "{!! $urlToDocs !!}",
                operationsSorter: {!! isset($operationsSorter) ? '"' . $operationsSorter . '"' : 'null' !!},
                configUrl: {!! isset($configUrl) ? '"' . $configUrl . '"' : 'null' !!},
                validatorUrl: {!! isset($validatorUrl) ? '"' . $validatorUrl . '"' : 'null' !!},
                oauth2RedirectUrl: "{{ route('l5-swagger.' . $documentation . '.oauth2_callback', [], $useAbsolutePath) }}",

                requestInterceptor: function(request) {
                    // Adicione o token JWT aos cabeçalhos da solicitação
                    var jwtToken = window.localStorage.getItem('jwtToken');
                    if (jwtToken) {
                        request.headers['Authorization'] = 'Bearer ' + jwtToken;
                    }
                    request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    return request;
                },

                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],

                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],

                layout: "StandaloneLayout",
                docExpansion: "{!! config('l5-swagger.defaults.ui.display.doc_expansion', 'none') !!}",
                deepLinking: true,
                filter: {!! config('l5-swagger.defaults.ui.display.filter') ? 'true' : 'false' !!},
                persistAuthorization: "{!! config('l5-swagger.defaults.ui.authorization.persist_authorization') ? 'true' : 'false' !!}",

            })

            window.ui = ui

            @if (in_array('oauth2', array_column(config('l5-swagger.defaults.securityDefinitions.securitySchemes'), 'type')))
                ui.initOAuth({
                    usePkceWithAuthorizationCodeGrant: "{!! (bool) config('l5-swagger.defaults.ui.authorization.oauth2.use_pkce_with_authorization_code_grant') !!}"
                })
            @endif
        }

        // Adiciona o evento de escuta para atualizar o token JWT no armazenamento local
        document.addEventListener("DOMContentLoaded", function() {
            var jwtTokenInput = document.getElementById('jwt-token');

            jwtTokenInput.addEventListener('input', function() {
                window.localStorage.setItem('jwtToken', jwtTokenInput.value);
            });
        });

        // Adiciona o evento de escuta para adicionar o token JWT aos cabeçalhos das solicitações
        window.addEventListener("fetch", function(event) {
            var jwtToken = window.localStorage.getItem('jwtToken');
            var request = event.request;

            // Adicione o token JWT aos cabeçalhos da solicitação
            if (jwtToken) {
                var headers = new Headers(request.headers);
                headers.set('Authorization', 'Bearer ' + jwtToken);

                event.respondWith(
                    fetch(request.url, {
                        method: request.method,
                        headers: headers,
                        body: request.body,
                        redirect: request.redirect
                    })
                );
            }
        });
    </script>
</body>

</html>
