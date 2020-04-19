/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */
var DD_Main_Model = DD_ModelBase.extend({
    eventBase: 'main-panel-created',
    eventClick: 'panel-click',
    eventObjectChanged: 'object-changed',
    eventObjectAdded: 'object-added',
    base: true,
    mainConfig: {},
    init: function (obj) {
        this.obj = obj;
        this._super();
        var self = this;
        //set global extran config
        this._s('extra_config', this.obj.options.extra_config);

        if (this._s('loadGoogleFonts')) {
            var fonts = self.prepareFonts();
            WebFont.load({
                google: {
                    families: fonts
                },
                active: function () {
                    self.initLayers();
                    if (obj.options.help) {
                        self.help = $('body').dd_help({
                            'data': obj.options.help
                        });
                        self.help.show();

                    }
                }
            });
            return;
        }

        this.initLayers();
        if (obj.options.help) {
            self.help = $('body').dd_help({
                'data': obj.options.help
            });
            self.help.show();
        }

    },

    registerEvents: function () {
        this._evnt().register(this.eventClick, this.obj);
        this._evnt().register(this.eventObjectChanged, this.obj);
        this._evnt().register(this.eventObjectAdded, this.obj);

        this.callBackObject();
    },

    initLayers: function () {
        var self = this;
        this.layersObj = new DD_Layer();
        this.idBgCanvas = 'canvas-' + this.createUUID();
        this.idCanvasHover = 'canvas-hover-' + this.createUUID();
        var bgCanvas = $('<canvas/>', {
            id: this.idBgCanvas
        });
        var hoverCanvas = $('<canvas/>', {
            id: this.idCanvasHover
        });
        var width = this.obj.options.width;
        var height = this.obj.options.height;

        bgCanvas.attr({
            'width': width,
            'height': height
        });
        hoverCanvas.attr({
            'width': width,
            'height': height
        });
        this.obj.self.append(bgCanvas);
        var div = $('<div />').addClass('canvas-absolute')
                .append(hoverCanvas);
        this.obj.self.append(div);

        var bgCanvas = new fabric.Canvas(this.idBgCanvas);
        var hoverCanvas = new fabric.Canvas(this.idCanvasHover);

        this.layersObj.setBgCanvas(bgCanvas);
        this.layersObj.setHoverCanvas(hoverCanvas);
        this.layersObj.setHeight(height);
        this.layersObj.setWidth(width);

        new DD_Layer_Main({
            width: width,
            height: height,
            src: this.obj.options.src
        });

        this._canvasEvents(hoverCanvas);

        this._addObjects(this.obj.options);

        this.resize(width, height);
        $(window).on('resize', function () {
            self.resize(width, height);
        });

        $('.dd-designer-loading').hide();
        return;
    },

    _canvasEvents: function (hoverCanvas) {
        var self = this;

        hoverCanvas.on('object:added', function (e) {

            new DD_control({
                parent: self.obj.self,
                fabricObject: e.target
            });
            if (e.target.uid) {
                return;
            }
            e.target.uid = self.createUUID();
            e.target.uid.toString();
            self._onUpdate(e.target, 'update');

        });
        hoverCanvas.on('object:moving', function (e) {
            if (e.target.controlModelCreated) {
                e.target.controlModelCreated.hide();
            }
        });
        hoverCanvas.on('object:scaling', function (e) {
            if (e.target.controlModelCreated) {
                e.target.controlModelCreated.hide();
            }
        });
        hoverCanvas.on('object:rotating', function (e) {
            if (e.target.controlModelCreated) {
                e.target.controlModelCreated.hide();
            }
        });
        hoverCanvas.on('object:skewing', function (e) {
            if (e.target.controlModelCreated) {
                e.target.controlModelCreated.hide();
            }
        });

        hoverCanvas.on('before:selection:cleared', function (e) {
            if (e.target.controlModelCreated) {
                e.target.controlModelCreated.hide();
            }
        });
        hoverCanvas.on('object:selected', function (e) {
            if (e.target.controlModelCreated) {
                e.target.controlModelCreated.initPosition();
            }
            self._onUpdate(e.target, 'update');
        })
        hoverCanvas.on('object:modified', function (e) {
            if (e.target.controlModelCreated) {
                e.target.controlModelCreated.initPosition();
            }
            self._onUpdate(e.target, 'update');
        });
        hoverCanvas.on('object:removed', function (e) {
            self._onUpdate(e.target, 'remove');
            if (e.target.controlModelCreated) {
                e.target.controlModelCreated.remove();
            }
        });
        hoverCanvas.on('object:clear_all', function (e) {
            self.obj.options.onClearAll.call(null, self.obj.options.media_id)
        });

        fabric.util.addListener(hoverCanvas.upperCanvasEl, 'dblclick', function (e) {

            if (hoverCanvas.findTarget(e)) {
                var objType = hoverCanvas.findTarget(e).type;
                if (objType === 'i-text') {
                    hoverCanvas.findTarget(e).controlModelCreated.handleActive();
                }
            }
        });

    },

    _addObjects: function (options) {
        if (options.mask) {
            var mask = new DD_Layer_Mask(options.mask, true);
            mask.save();
        }
        if (options.conf) {
            var self = this;
            var last = options.conf.length;
            $(options.conf).each(function (i, obj) {
                if (obj.type === 'image') {
                    new DD_Layer_Img(null, obj);
                }
                if (obj.type === 'text' || obj.type === 'i-text') {
                    new DD_Layer_Text(null, obj);
                }
                if (obj.isSvg == true) {
                    new DD_Layer_Svg(obj);
                }
            });
        }
        ;
    },

    _onUpdate: function (fabricObj, type) {
        var newObject = fabricObj.toJSON();
        newObject.uid = fabricObj.uid;
        if (fabricObj.layerMask) {
            newObject.layerMask = true;
        }
        if (fabricObj.controlModel) {
            newObject.controlModel = fabricObj.controlModel;
        }
        if (fabricObj.isSvg) {
            newObject.svgString = fabricObj.toSVG();
            newObject.src_orig = fabricObj.src_orig;
            newObject.isSvg = true;
        }

        if (this.obj.options.onUpdate) {
            this.obj.options.onUpdate.call(
                    null,
                    newObject,
                    this.obj.options.group_index,
                    this.obj.options.media_id,
                    type);
        }
    },

    resize: function (width, height) {
        var blockWidth = $(window).width(); //magento 2 modal
        var blockHeight = $(window).height() - 40; //magento 2 modal

        this.obj.get().width(blockWidth);
        this.obj.get().height(blockHeight);

        var newWidth, newHeight, scaleFactor;
        var bgCanvas = this.layersObj.getBgCanvas();
        var hoverCanvas = this.layersObj.getHoverCanvas();
        if (blockWidth < width) {
            newWidth = blockWidth;
            newHeight = (height / width) * newWidth;
            scaleFactor = blockWidth / this._l().getWidth();
        }

        if (blockHeight < (newHeight ? newHeight : height)) {
            var scaleFactorH = (blockHeight) / (newHeight ? newHeight : this._l().getHeight());
            if (scaleFactorH != 1) {
                newHeight = blockHeight;
                newWidth = (newHeight) * (width / height);
                scaleFactor = (scaleFactor ? scaleFactor : 1) * scaleFactorH;
            }
        }

        if (scaleFactor != 1 && newHeight && newWidth) {
            bgCanvas.setWidth(newWidth);
            bgCanvas.setHeight(newHeight);
            bgCanvas.setZoom(scaleFactor);
            bgCanvas.calcOffset();
            bgCanvas.renderAll();
            hoverCanvas.setWidth(newWidth);
            hoverCanvas.setHeight(newHeight);
            hoverCanvas.setZoom(scaleFactor);
            hoverCanvas.calcOffset();
            hoverCanvas.renderAll();

            this.obj.get().width(newWidth);
            this.obj.get().height(newHeight);

        }
        return;
    },

    destroy: function () {
        this._evnt().doCall('window-destroyed');
        this._evnt().unregisterAll();
        this.obj.self.parent().empty();
        this.obj.self.parent().remove();

        this._w().close();
        this._evnt().destroyJBoxes();
        if (this.help) {
            this.help.destroy();
        }
        delete this;
    },

    prepareFonts: function () {
        var listFonts = this._s('listFonts');
        var googleFonts = [];
        $.each(listFonts, function (i, font) {
            if (font.indexOf('"') != -1) { //custom named font
                var fontArr = font.split(',');
                googleFonts.push(fontArr[0].replace(/\"/g, ''));
            }
        });

        return googleFonts;
    },

    getDataImg: function () {
        return this._mergeCanvases();
    },

    getJsonImg: function () {
        return this._mergeCanvases('json');
    },

    getSvgText: function () {
        return this._mergeCanvases('svg');
    },

    unselectAll: function () {
        var _hoverCanvas = this.layersObj.getHoverCanvas();
        if (_hoverCanvas) {
            _hoverCanvas.discardActiveObject().renderAll();
        }
    },

    _getSvgLayer: function (obj) {
        var output = $('<canvas />')
                .attr({
                    'width': obj.width,
                    'height': obj.height
                })
                .get(0);

        var octx = output.getContext('2d');

    },

    _mergeCanvases: function (type) {

        var _bgCanvas = this.layersObj.getBgCanvas();
        var _hoverCanvas = this.layersObj.getHoverCanvas();

        switch(type){
            case 'json':
                var mergedObjs = _bgCanvas.toJSON();
                var hoverObjs = _hoverCanvas.toJSON();
                for(var object in hoverObjs.objects) {
                    mergedObjs['objects'].push(hoverObjs.objects[object]);
                }
                return mergedObjs;
                break;
            case 'svg':

                /*var tmpCanvas = _bgCanvas;
                 var canvasObjects = _hoverCanvas.toJSON();

                 for (var i = 0; i < canvasObjects.objects.length; i++) {
                 var klass = fabric.util.getKlass(objects[i].type);
                 if (klass.async) {
                 klass.fromObject(objects[i], function (img) {
                 tmpCanvas.add(img);
                 });
                 } else {
                 tmpCanvas.add(klass.fromObject(objects[i]));
                 }
                 }*/

                return _hoverCanvas.toSVG();

                break;
            default:

                var bgCanvas = $('#' + this.idBgCanvas).get(0);
                var hoverCanvas = $('#' + this.idCanvasHover).get(0);

                var sourceBgWidth = _bgCanvas.lowerCanvasEl.width;
                var sourceBgHeight = _bgCanvas.lowerCanvasEl.height;
                var sourceHoverWidth = _hoverCanvas.lowerCanvasEl.width;
                var sourceHoverHeight = _hoverCanvas.lowerCanvasEl.height;
                var output = $('<canvas />')
                    .attr({
                        'width': this._l().getWidth(),
                        'height': this._l().getHeight()
                    })
                    .get(0);

                var octx = output.getContext('2d');

                octx.drawImage(bgCanvas, 0, 0, sourceBgWidth, sourceBgHeight, 0, 0, output.width, output.height);
                octx.drawImage(hoverCanvas, 0, 0, sourceHoverWidth, sourceHoverHeight, 0, 0, output.width, output.height);

                return output.toDataURL('png');
        }

    }

});
