module.exports = {
    options: {
        banner: '/*! <%= package.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
    },
    app: {
        files: {
            '<%= paths.js.dist %>/charcoal.property-filter.min.js': [
                '<%= concat.property_filter.dest %>'
            ]
        }
    }
};
