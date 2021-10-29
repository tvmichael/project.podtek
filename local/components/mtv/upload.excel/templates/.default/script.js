(function(window) {

    if (!window.BX || BX.MTVExcel)
        return;

    BX.MTVExcel = {
        url: null,
        button: null,
        isLoad: true,

        init : function (params) {
            this.url = params.URL_AJAX;
            this.button = BX(params.BTN_ID);

            delete params.URL_AJAX;
            delete params.BTN_ID;

            this.params = params;

            BX.bind(this.button, 'click', BX.proxy(this.request, this));

            this.loaderImg = this.button.querySelector('.btn-excel-load');
        },

        request: function () {
            if(this.isLoad) {
                this.loaderImg.style.display = 'inline-block';
                this.isLoad = false;

                BX.ajax({
                    method: 'POST',
                    dataType: 'json',
                    url: this.url,
                    data: this.params,
                    onsuccess: BX.delegate(function(response) {
                        if(response.status) {
                            this.upload(response);
                        }
                        this.isLoad = true;
                    }, this),
                    onfailure: BX.delegate(function(e) {
                        this.isLoad = true;
                        console.log( "Request failed:", e);
                    }, this)
                });
            }
        },

        upload: function (data) {
            if(data.linkSource && data.xlsData)
            {
                let linkSource = data.linkSource + data.xlsData;
                let downloadLink = document.createElement("a");
                let fileName = 'price-list.xlsx';

                downloadLink.href = linkSource;
                downloadLink.download = fileName;
                downloadLink.click();

                delete downloadLink;
                this.loaderImg.style.display = 'none';
            }
        }
    };
})(window);

