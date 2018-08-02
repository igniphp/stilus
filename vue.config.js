var yaml = require('read-yaml');

var config = yaml.sync('.stilus.yml');

module.exports = {
    outputDir: config.development.build_dir,
    devServer: {
        port: config.development.web_server.port || 8080,
        host: config.development.web_server.host || "0.0.0.0",
    },
    assetsDir: 'assets',
    css: {
        sourceMap: config.development.css_source_map || true,
        extract: false
    }
};
