window.onload = function () {
    window.ui = SwaggerUIBundle({
        url: 'docs',
        dom_id: '#swagger-ui',
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset
        ]
    });
};
