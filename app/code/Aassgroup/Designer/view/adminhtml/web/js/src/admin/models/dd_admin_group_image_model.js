var DD_admin_group_image_model = DD_Admin_ImagesSelected_Model.extend({

    setImageSize: function (img, group_id, media_id) {//group_index, media_id, conf
        var sizes = this.getImgSizes(img);
        return this.updateImageConf(group_id, media_id, 'sizes', sizes);
    },

    getImgSizes: function (element) {
        if (element.naturalWidth != undefined && element.naturalWidth != '' && element.naturalWidth != 0) {
            this.width = element.naturalWidth;
            this.height = element.naturalHeight;
        } else if (element.width != undefined && element.width != '' && element.width != 0) {
            this.width = element.width;
            this.height = element.height;
        } else if (element.clientWidth != undefined && element.clientWidth != '' && element.clientWidth != 0) {
            this.width = element.clientWidth;
            this.height = element.clientHeight;
        } else if (element.offsetWidth != undefined && element.offsetWidth != '' && element.offsetWidth != 0) {
            this.width = element.offsetWidth;
            this.height = element.offsetHeight;
        }

        return {
            'width': parseInt(this.width),
            'height': parseInt(this.height)
        }
    },

    clickRemove: function (el) {
        var self = this;
        el.on('click', function () {
            var group = el.attr('data-group');
            var media_id = el.attr('data-remove');
            self.removeImage(group, media_id);

        });
    },

    onUpdate: function (fabricObj, group_uid, media_id, type) {
        if (type === 'extra_conf') {
            this.updateExtraConf(group_uid, media_id, fabricObj.key, fabricObj.value);
            return;
        }
        this.updateImgFabricConf(group_uid, media_id, fabricObj, type);
    },

    clickEdit: function (el, options) {
        var self = this;

        var urlUploadImages = this._s('urlUploadImages');
        var percentSizeImage = this._s('percentSizeImage');
        var defaultFontSize = this._s('defaultFontSize');
        var defaultFont = this._s('defaultFont');
        var defualtFontColor = this._s('defualtFontColor');
        var defaultLayerMaskWidth = this._s('defaultLayerMaskWidth');
        var percentSizeFromMask = this._s('percentSizeFromMask');
        var onUpdate = this.onUpdate.bind(this);
        var group_index = el.attr('data-group');
        var listFonts = this._s('listFonts');
        var myFilesPath = this._s('myFilesPath');
        var defaultImgEnabled = this._s('defaultImgEnabled');
        var defaultTextEnabled = this._s('defaultTextEnabled');
        var defaultLibraryEnabled = this._s('defaultLibraryEnabled');
        var defaultTextPrice = this._s('defaultTextPrice');
        var defaultImgPrice = this._s('defaultImgPrice');
        var libraryPath = this._s('libraryPath');
        var libraryCategories = this._s('libraryCategories');


        el.on('click', function () {
            
            self._evnt().doCall('before-designer-created');
            
            $('#dd_designer').html('');
            $('#dd_designer').empty();
            var designer = $('#dd_designer').dd_productdesigner({
                'src': options.src,
                'width': options.sizes.width,
                'height': options.sizes.height,
                'sku': options.sku,
                'product_id': options.product_id,
                'media_id': options.media_id,
                'group_index': group_index,
                'mask': options.mask,
                'conf': options.conf,
                'extra_config': (options.extra_config ? options.extra_config : {}),
                'settings': {
                    'urlUploadImages': urlUploadImages,
                    'percentSizeImage': percentSizeImage,
                    'defualtFontColor': defualtFontColor,
                    'defaultFont': defaultFont,
                    'defaultFontSize': defaultFontSize,
                    'defaultLayerMaskWidth': defaultLayerMaskWidth,
                    'percentSizeFromMask': percentSizeFromMask,
                    'listFonts': listFonts,
                    'myFilesPath': myFilesPath,

                    'defaultImgEnabled': defaultImgEnabled,
                    'defaultTextEnabled': defaultTextEnabled,
                    'defaultLibraryEnabled': defaultLibraryEnabled,
                    'defaultTextPrice': defaultTextPrice,
                    'defaultImgPrice': defaultImgPrice,
                    'libraryPath': libraryPath,
                    'libraryCategories': libraryCategories
                }

            });
            $('html, body').animate({
                scrollTop: $("#dd_designer").offset().top - 100
            }, 500);

            designer.onUpdate(onUpdate);
            self.getObject().designer = designer.init();

            self.getObject().designerMediaId = options.media_id;
            self.getObject().designerGroupId = group_index;
            
            self._evnt().doCall('designer-created', designer);
        });
    }

});
