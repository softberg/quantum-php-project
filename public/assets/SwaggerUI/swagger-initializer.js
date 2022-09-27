window.onload = function () {
    window.ui = SwaggerUIBundle({
        url: "/assets/api-docs.json",
        dom_id: '#swagger-ui',
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset
        ]
    });
};
