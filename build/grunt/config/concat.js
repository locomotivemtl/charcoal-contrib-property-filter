module.exports = {
    options: {
        separator: ';'
    },
    property_filter: {
        src: [
            '<%= paths.js.src %>/**/*.js',
        ],
        dest: '<%= paths.js.dist %>/property-filter.js'
    }
};
