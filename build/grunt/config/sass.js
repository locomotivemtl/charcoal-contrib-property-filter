module.exports = {
    options: {
        sourceMap:   false,
        outputStyle: 'expanded'
    },
    app: {
        files: {
            '<%= paths.css.dist %>/charcoal.property-filter.css': '<%= paths.css.src %>/**/charcoal.property-filter.scss'
        }
    },
    vendors: {
        files: {
            '<%= paths.css.dist %>/charcoal.property-filter.vendors.css': '<%= paths.css.src %>/**/charcoal.property-filter.vendors.scss'
        }
    }
};
