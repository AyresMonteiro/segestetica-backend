<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/4.1.0/swagger-ui.min.css" integrity="sha512-5Qqi9k+ey4yJCfC/8NemvXAcL9PMUE2ROvmIZ1TXd2sO0KXXZyykItptL710pH2qURIhzeqeidjZIDVtt3sHDA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>SegEstética API Documentation</title>
  <style>
    html, body {
      margin: 0;
      padding: 0;
    }
  </style>
</head>
<body>
  <div id="swagger-ui"></div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/4.1.0/swagger-ui-bundle.min.js" integrity="sha512-TRbzJ6FblfPwqZuXrOdY6mDf8Megswrvpg6of8+iRTaSwyLHRHHSrNutJbfdE6iq/X9MTGElOsWwcQ1bcc7Mmg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/4.1.0/swagger-ui-standalone-preset.min.js" integrity="sha512-JytO705eAj4uJcRGLqebUE28M2lsGEG0Mk4C96sCyX1sjznjKQtX0R6NDkKacqG6dAXu5KfeGmcZIwcDtPi2TQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    window.onload = function () {
      const url = "{{asset('/swagger/openapi.yaml')}}";
      
      const ui = SwaggerUIBundle({
        url: url,
        dom_id: '#swagger-ui',
        deepLinking: true,
          presets: [
              SwaggerUIBundle.presets.apis,
              SwaggerUIStandalonePreset
          ],
          layout: "StandaloneLayout"
      })

      window.ui = ui
    };
  </script>
</body>
</html>