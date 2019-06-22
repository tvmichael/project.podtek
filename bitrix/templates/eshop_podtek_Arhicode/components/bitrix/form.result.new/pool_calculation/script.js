(function() {
    if (!!window.BXPoolCalculationPrise)
        return;

    window.BXPoolCalculationPrise = function(params) {
        this.papams = params;
        this.bindClick();

        console.log('BXPoolCalculationPrise::');
        console.log(params);
    };

    window.BXPoolCalculationPrise.prototype = {
        bindClick:function ()
        {

        },

    }
})();