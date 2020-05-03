/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */
var DD_Bottomcontrols = DD_panel.extend({
    object_id: 'dd-bottom-controls',
    class_name: 'dd-designer-bottomcontrols',

    init: function (parent, main, mainModel) {
        this.parent = parent;
        this.main = main;
        this.mainModel = mainModel;
        this._super({
            'id': this.object_id,
            'class': this.class_name,
            'parent': parent
        });
        this.add();
    },
    
    _addElements: function () {
        this.addPreviewButton();
        this.addDownloadButton();
        this.addPrintButton();
        this.addClearAllButton();
    },
    
    addDownloadButton: function() {
        if(!this._s('download')) {
            return;
        }
        new DD_downloadButton(this.self, this.mainModel);
    },
    
    addPrintButton: function() {
        if(!this._s('print')) {
            return;
        }
        new DD_printButton(this.self, this.mainModel);
    },
    
    addClearAllButton: function() {
        if(!this._s('clear_all')) {
            return;
        }
        new DD_clearAllButton(this.self, this.mainModel);
    },
    
    
    addPreviewButton: function() {
        if(!this._s('preview')) {
            return;
        }
        new DD_previewButton(this.self, this.mainModel);
        
    }
});
