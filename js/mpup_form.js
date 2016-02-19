jQuery(function() {

//------Model
    var PopUp_Model = Backbone.Model.extend({

        initialize: function(){
            this.set({"title":popup_settings.header_text}),
            this.set({"content":popup_settings.main_text}),

            //this.set({"width":'400px'}),
            //this.set({"height":'350px'}),

            //this.set({"top":this.calculation_top()}),
            //this.set({"left": this.calculation_left()}),

            this.set({"delay_before_popup":popup_settings.delay_before_popup * 1000}),
            this.set({"time_display_popup":popup_settings.time_display_popup * 1000}),

            this.set({"existence_close":popup_settings.existence_close}),
            this.set({"close_clicking_esc":popup_settings.close_clicking_esc}),
            this.set({"close_clicking_overlay":popup_settings.close_clicking_overlay})
        },

        //calculation_top: function(){
        //    return (jQuery(window).height() - parseInt(this.get("width"))) / 2 + 'px';
        //},
        //
        //calculation_left: function(){
        //    return (jQuery(window).width() - parseInt(this.get("height"))) / 2 + 'px';
        //},

    });


//-----View
    var PopUp_View = Backbone.View.extend({
     //   className: "overlay",
        events: {
             'click .popup-close': 'close',
             'click .popup': 'stopPropagation',
             'click .overlay': 'close_overlay',
        },

        initialize: function () {
            this.template = jQuery('#popup-template').html();

            this.context = {
                title : this.model.get('title'),
                content : this.model.get('content'),
            };

            jQuery(this.el).css({
                'width': '100%',
                'height': '100%',
                'top': '0',
                'left': '0',
                'background-color': 'rgba(0,0,0,0.5)',
                'position': 'absolute',
            });
            jQuery(this.el).draggable();
        },

        render: function(){
            jQuery(this.el).html(_.template(this.template,this.context));

            if(popup_model.get('existence_close')!=1){
                jQuery(this.el).find( ".popup-close" ).css( {'display': 'none' });
            }

            return this;
        },

        close : function (){
            jQuery(this.el).remove();
        },
        close_esc: function(){
            if(this.model.get('close_clicking_esc')==1)
                this.close();
        },

        stopPropagation: function(event){
            event.stopPropagation();
        },

        close_overlay: function(){
            if(popup_model.get('close_clicking_overlay')==1)
                this.close();
        },
    });

    window.popup_model= new PopUp_Model;
    window.popup_view = new PopUp_View({model:popup_model});

    //-----------Load----------------
    jQuery(window).load(function () {
        var timerId = setTimeout(show(), popup_model.get('delay_before_popup'));
    });

    function show(){
        jQuery('body').append(popup_view.render().el);
        timerId = setTimeout(
            function hide(){
                popup_view.close();
            }, popup_model.get('time_display_popup'));
    }

    //------------ESC------------------
   jQuery(document).keydown(function(e) {
        if(e.keyCode == 27)
            popup_view.close_esc();
   });

});