/**
 *  extend the RegionUpdater to manager mps-ui-select
 */
RegionUpdater = Class.create(RegionUpdater, {
    initialize: function ($super, countryEl, regionTextEl, regionSelectEl, regions, disableAction, zipEl) {
        $super(countryEl, regionTextEl, regionSelectEl, regions, disableAction, zipEl);
        Event.observe(window, 'load', this.update.bind(this));
    },
    update: function($super) {
        $super();
        try {
            if (!this.regions[this.countryEl.value]) {
                $('select_container_' + this.regionSelectEl.id).hide();
            } else {
                $('select_container_' + this.regionSelectEl.id).show();
            }
        } catch (ex) {
            
        }
    }    
});