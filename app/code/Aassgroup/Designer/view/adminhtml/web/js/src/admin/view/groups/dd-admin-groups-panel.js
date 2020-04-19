var DD_admin_groups_panel = DD_panel.extend({
    
    class_name: 'dd-admin-groups-panel',
    model: 'DD_Admin_ImagesSelected_Model',
    
    init: function (options) {
        this.options  = options;
        this._super({
            'class': this.class_name
        });
        this.add();
    },
    
    _addElements: function() {
        this.addEditImgButton();
        this.addClearButton();
        this.addCancelButton();
    },
   
    addEditImgButton: function(){
        new DD_admin_image_button(
            this.self
        );
    },
    
    addClearButton: function() {
        new DD_admin_clear_button(
            this.self
        );
    },
    
    addCancelButton: function() {
        new DD_admin_groupcancel_button(
            this.self
        );
    }

});
