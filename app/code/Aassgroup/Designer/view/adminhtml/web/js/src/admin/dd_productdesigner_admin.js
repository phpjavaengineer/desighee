$.fn.dd_productdesigner_admin = function (options) {
    
    this.options = $.extend({
        'urlImages': '',
        'translator': {
            'default_main_image': 'By default shows main product image',
            'configure_images': 'Configure Images',
            'add_group': 'Add Group',
            'clear_all': 'Clear All',
            'cancel': 'Cancel',
            'save': 'Save',
            'remove': 'Remove',
            'image': 'Image',
            'select_images': 'Select Images',
            'edit': 'Edit',
            'add_edit_image': 'Add/Edit Image',
            'configure_images': 'Configure Images',
            'select_all': 'Select/Unselect All'
        },
        
        'settings': {
            'psku': '',
            'urlLoadImages': '',
            'product_id': '',
            'urlUploadImages': '',
            
            'urlSaveData': '',
            'percentSizeImage': '',
            'percentSizeFromMask': 70,
            'defaultFont': 'Verdana,Geneva,sans-serif',
            
            'defualtFontColor': '#ffffff',
            'defaultFontSize': 20,
            'listFonts': [],
            'defaultLayerMaskWidth': 50
        },
        'onUpdate': null
    }, options);
    
    new DD_Translator(this.options.translator);
    new DD_Event();
    new DD_Settings(this.options.settings);
    
    new DD_admin_main(this, this.options);
    
    if(this.options.debug) {
        new DD_Debug(this);
    }
    
    
    return this;
    
};
