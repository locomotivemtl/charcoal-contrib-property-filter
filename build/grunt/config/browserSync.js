module.exports = {
    options: {
        open:      false,
        proxy:     'charcoal-contrib-property-filter.test',
        port:      3000,
        watchTask: true,
        notify:    false
    },
    dev: {
        bsFiles: {
            src: [
                '<%= paths.prod %>/**/*'
            ]
        }
    }
};
