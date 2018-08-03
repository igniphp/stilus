var yaml = require('read-yaml');

var config = yaml.sync('.stilus.yml');

module.exports = {
    outputDir: config.development.build.dir,
    devServer: {
        port: config.development.web_server.port || 8080,
        host: config.development.web_server.host || "0.0.0.0",
    },
    css: {
        sourceMap: config.development.build.css_source_map || true,
        extract: false
    },
    pages: config.development.build.pages,
};
