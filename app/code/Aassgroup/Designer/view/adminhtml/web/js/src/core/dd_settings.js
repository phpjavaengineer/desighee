var DD_Settings = DD_object.extend({
    id: 'dd_settings',
    jBoxes: [],
    init: function(settings) {
        this._super(this.id);
        this.settings = settings;
        this.setGlobal();
    }
})
