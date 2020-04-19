/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */
var DD_AddFromLibrary_Model = DD_ModelBase.extend({

    currentCategory: null,

    getWindowTitle: function () {
        return this._('add_from_library');
    },

    setWindowContent: function (parent) {
        this.loadLibrary(parent);
    },

    addOnCategoryEvent: function (categoryLink, parent, name) {
        var self = this;
        categoryLink.on('click', function () {
            self.currentCategory = name;
            self.loadLibrary(parent);
        });
    },

    addClearLink: function (parent, name) {
        var fa = $('<span />').addClass('fa fa-window-close');
        var link = $('<a />').addClass('dd-clear-category')
                .append(fa)
                .append(name)
        parent.append(link);
        this.addClearLinkEvent(link, parent);
    },

    addClearLinkEvent: function (link, parent) {
        var self = this;
        link.on('click', function () {
            self.currentCategory = null;
            self.loadLibrary(parent);
        });
    },

    loadLibrary: function (parent) {
        var self = this;
        parent.empty();
        parent.addClass('dd-window-loading');
        parent.html(this._('loading') + '...');

        var extraConfig = this.getExtraConfig();
        var categories = (extraConfig && extraConfig.lib_categories) ? extraConfig.lib_categories : null;
        $.ajax({
            url: this._s('libraryPath'),
            type: 'json',
            method: 'post',
            data: {
                'category': self.currentCategory
            }
        })
                .done(function (response) {
                    parent.removeClass('dd-window-loading');
                    parent.empty();
                    if (response.error) {
                        alert(response.errMessage);
                        return;
                    }
                    if (self.currentCategory) {
                        self.addClearLink(parent, self.currentCategory);
                    }
                    $.each(response.data, function (i, element) {
                        if (element.directory) {
                            if (!categories || categories.indexOf(element.name) != -1) {

                                new DD_Category({
                                    'parent': parent,
                                    'data': element,
                                    'model': self
                                });
                            }
                        }
                        if (element.file) {
                            new DD_ImageLinkAdd({
                                'parent': parent,
                                'src': element.src,
                                'width': element.width,
                                'height': element.height,
                                'class': 'size-small'
                            });
                        }
                    });

                });


    },

    getExtraConfig: function () {
        return this._s('extra_config');
    }
});
