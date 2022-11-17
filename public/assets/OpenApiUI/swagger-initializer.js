window.onload = function () {
    window.ui = SwaggerUIBundle({
        url: 'spec',
        dom_id: '#swagger-ui',
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset
        ]
    });
};
