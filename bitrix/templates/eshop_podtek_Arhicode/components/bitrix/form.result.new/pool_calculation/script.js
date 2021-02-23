BX.namespace('BX.PoolCalculationPrise');
(function() {

    BX.PoolCalculationPrise = {
        init: function(parameters)
        {
            this.params = parameters;
            this.arProductPrice = parameters.arProductPrice;
            this.dpId = parameters.dpId;
            this.workPrice = 0;
            this.productPrice = 0;
            this.arPrice = [];
            this.workCatalogID = parameters.workCatalogID;
            this.isOpenPdf = parameters.isOpenPdf;

            this.form = document.querySelector("form[name='POOL_CALCULATION']");

            this.pollSize = {
                width: 0,
                length: 0,
                deep: 0,
                validate: function(){
                    this.width = parseFloat(this.width);
                    if(typeof this.width != 'number') this.width = 0;

                    this.length = parseFloat(this.length);
                    if(typeof this.length != 'number') this.length = 0;

                    this.deep = parseFloat(this.deep);
                    if(typeof this.deep != 'number') this.deep = 0;
                },
                volume: function () {
                    this.validate();
                    return Math.ceil(this.width *this.length * this.deep);
                },
                area: function () {
                    this.validate();
                    return Math.ceil(this.width * this.length);
                },
                perimeter: function () {
                    this.validate();
                    return Math.ceil(2*(this.width + this.length));
                },
                poolArea: function () {
                    this.validate();
                    return Math.ceil(((this.width * this.length) + 2 * this.deep * (this.width  + this.length)) * 1.1 );
                },
            };

            this.obFormTtable = $('.form-table.data-table')[0];

            this.inputTopLevel = $('.question.block1-left input', this.obFormTtable);
            for (i = 0; i < this.inputTopLevel.length; i++)
                BX.bind(this.inputTopLevel[i], 'click', BX.proxy(this.clickInput, this));

            this.inputInnerLevel = $('.question.block1-right input',this.obFormTtable);
            for (i = 0; i < this.inputInnerLevel.length; i++)
                BX.bind(this.inputInnerLevel[i], 'click', BX.proxy(this.calculatePrice, this));

            this.obInputSize = $('.el-question.el-text input', this.obFormTtable);
            for (i = 0; i < this.obInputSize.length; i++)
                BX.bind(this.obInputSize[i], 'change', BX.proxy(this.calculatePrice, this));

            this.currentDependentBlock = false;
            this.dependentInput = [];

            this.formSubmit(this.form);

            this.initDependentBlock();
            this.calculatePrice();

            console.log(this);
        },

        openPdf: function(data)
        {
            if(typeof data == 'object')
            {
                if(data.RESULT_ID && data.WEB_FORM_ID && data.formresult)
                {
                    let url = "https://podtek.ru/pool_calculation_result_test/result.php?WEB_FORM_ID="
                        + data.WEB_FORM_ID
                        + "&RESULT_ID="
                        + data.RESULT_ID
                        + "&formresult="
                        + data.formresult;
                    let win = window.open(url, '_blank');
                    win.focus();
                }
            }
        },

        formSubmit: function(form)
        {
            let self = this;
            $(form).on('submit',function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'post',
                    url: form.action,
                    data: $(form).serialize() + '&web_form_submit=submit',
                    dataType: 'json',
                    success: function (response) {
                        self.openPdf(response);
                    }
                });
                return false;
            });
        },

        initDependentBlock: function()
        {
            var i, value, v;
            for (i = 0; i < this.inputTopLevel.length; i++)
            {
                value = $(this.inputTopLevel[i]).attr(this.dpId);
                if(value)
                {
                    v = value.split('_');
                    if(v.length > 1 && this.inputTopLevel[i].checked) {
                        this.currentDependentBlock = value;
                        $('#' + value).show();
                        this.dependentInput[value] = $('#' + value + ' input');
                        this.dependentInput[this.currentDependentBlock][0].checked = true;
                    } else if (v.length > 1) {
                        $('#' + value).hide();
                        this.dependentInput[value] = $('#' + value + ' input');
                    }
                }
            }
        },

        clickInput: function (e)
        {
            var i, value, v, input,
                e = e.target;

            value = $(e).attr(this.dpId);
            if (value)
            {
                v = value.split('_');
                if(v.length > 1 && e.checked)
                {
                    for(i=0; i < this.dependentInput[this.currentDependentBlock].length; i++)
                        this.dependentInput[this.currentDependentBlock][i].checked = false;

                    $('#' + this.currentDependentBlock).hide();
                    this.currentDependentBlock = value;
                    $('#' + value).show();
                    this.dependentInput[this.currentDependentBlock][0].checked = true;
                }

                this.calculatePrice();
            }

        },

        tryParseJSON: function (jsonString)
        {
            try {
                var o = JSON.parse(jsonString);
                if (o && typeof o === "object") {
                    return o;
                }
            }
            catch (e) { }
            return false;
        },

        calculatePrice: function ()
        {
            this.arPrice = [];
            var i, j,
                value = 0;
            var valueSize = 0;

            for (i = 0; i < this.inputInnerLevel.length; i++)
            {
                if(this.inputInnerLevel[i].checked)
                {
                    value = $(this.inputInnerLevel[i]).attr(this.dpId);
                    this.arPrice.push(parseInt(value));
                }
            }

            value = 0;
            for (i = 0; i < this.inputTopLevel.length; i++)
            {
                value = $(this.inputTopLevel[i]).attr(this.dpId);

                if(this.inputTopLevel[i].checked)
                {
                    if (parseInt(value) > 1) // якщо input з ІД товара
                    {
                        value = parseInt(value);
                        if (value > 0 )
                            this.arPrice.push(parseInt(value));
                    }
                    else
                    {
                        value = value.replace(/'/g, '"');
                        if (this.tryParseJSON(value))
                        {
                            value = this.tryParseJSON(value);
                            var arrValue = 0;

                            if(value && !value.off)
                            {
                                for (j in value) {
                                    if (this.pollSize.volume() <= parseInt(j)) {
                                        arrValue = value[j];
                                        break;
                                    }
                                }

                                if(arrValue)
                                {
                                    this.arPrice.push(arrValue);
                                }
                            }
                        }
                    }
                }
                else if(value == 'width' || value == 'length' || value == 'deep') // якщо input з розмірами для басайна
                {
                    valueSize = parseFloat(this.inputTopLevel[i].value); // беремо введені значення
                    if(valueSize > 0)
                    {
                        this.pollSize[value] = valueSize;
                        this.inputTopLevel[i].style.borderBottomColor = 'black';
                    }
                    else
                    {
                        this.pollSize[value] = 0;
                        this.inputTopLevel[i].style.borderBottomColor = 'red';
                    }
                }

            }
            this.getPrice();
        },

        getPrice:function ()
        {
            this.workPrice = 0;
            this.productPrice = 0;

            var i, j,
                price,
                strPrice,
                quantity,
                action,
                perimeterNew,
                resultPrice = 0;

            for(i = 0; i < this.arPrice.length; i++)
            {
                if(this.arProductPrice[this.arPrice[i]]) // шукаємо в списку продуктів наш продукт по ІД
                {
                    price = this.arProductPrice[this.arPrice[i]];
                    for (j in price)
                    {
                        strPrice = price[j].QUANTITY.toString(); // продукт що розраховується

                        resultPrice = price[j].BASE_PRICE;

                        if(price[j].DISCOUNT_PRICE && price[j].DISCOUNT_PRICE > 0)
                        {
                            resultPrice = price[j].DISCOUNT_PRICE;
                        }

                        if(strPrice.length > 5)
                        {
                            // товар для розрахунку
                            quantity = parseInt(strPrice.slice(0, strPrice.length-5));
                            action = strPrice.slice(strPrice.length-5, strPrice.length);

                            switch (action)
                            {
                                case '00000':
                                    if(price[j].BLOCK_ID == this.workCatalogID) this.workPrice += (parseFloat(resultPrice) * quantity);
                                        else this.productPrice += (parseFloat(resultPrice) * quantity);
                                    break;
                                case '55555':
                                    break;
                                case '66666':
                                    break;
                                case '77777':
                                    if(price[j].BLOCK_ID == this.workCatalogID) this.workPrice += Math.ceil( parseFloat(resultPrice) * quantity * this.pollSize.perimeter() );
                                        else this.productPrice += Math.ceil(parseFloat(resultPrice) * quantity * this.pollSize.perimeter());
                                    break;
                                case '88888':
                                    if( (this.pollSize.perimeter() % quantity) > 0) perimeterNew =  (this.pollSize.perimeter() / quantity) + 1;
                                    else perimeterNew = this.pollSize.perimeter() / quantity;

                                    if(price[j].BLOCK_ID == this.workCatalogID) this.workPrice += Math.ceil( parseFloat(resultPrice) *  perimeterNew );
                                        else this.productPrice += Math.ceil(parseFloat(resultPrice) * perimeterNew );
                                    break;
                                case '99999':
                                    if(price[j].BLOCK_ID == this.workCatalogID) this.workPrice += Math.ceil( parseFloat(resultPrice) * quantity * this.pollSize.poolArea() );
                                        else this.productPrice += Math.ceil(parseFloat(resultPrice) * quantity * this.pollSize.poolArea() );
                                    break;
                            }
                        }
                        else
                        {
                            if(price[j].BLOCK_ID == this.workCatalogID)
                            {
                                this.workPrice += (parseFloat(resultPrice) * parseInt(price[j].QUANTITY));
                            }
                            else
                            {
                                this.productPrice += (parseFloat(resultPrice) * parseInt(price[j].QUANTITY));
                            }
                        }
                    }
                }
            }

            $('#pool-price-material').html(this.bxFormatPrice(parseFloat(this.productPrice).toFixed(2)) + ' руб.');
            $('#pool-price-work').html(this.bxFormatPrice(parseFloat(this.workPrice).toFixed(2)) + ' руб.');
            $('#pool-price-all').html(this.bxFormatPrice(parseFloat(this.workPrice + this.productPrice).toFixed(2)) + ' руб.');
        },

        bxFormatPrice: function(price)
        {
            var result = '';
            if (typeof(price) != 'undefined')
            {
                if (typeof(price) == 'number')
                    price = price.toString();
                if (price.length > 0)
                {
                    var testPrice = /^([\d]+)|([\d]+\.|,[\d]+)$/;
                    if (testPrice.test(price))
                    {
                        var str, integral, decimal, delim, regex;

                        regex = /\.|,[\d]+$/ig;
                        delimPos = price.search(regex);
                        if (delimPos >= 0)
                        {
                            integral = price.substr(0, delimPos);
                            decimal = price.substr(delimPos+1);
                        }
                        else
                        {
                            integral = price;
                            decimal = '';
                        }

                        str = integral;
                        var blockSize = 3;
                        if (str.length > blockSize)
                        {
                            while (str.length > 0)
                            {
                                if (str.length > blockSize)
                                {
                                    result = ' ' + str.substr((blockSize*(-1)), blockSize) + result;
                                    str = str.substr(0, (str.length - blockSize));
                                }
                                else
                                {
                                    result = str + result;
                                    str = '';
                                }
                            }
                            result = decimal.length > 0 ? result+'.'+decimal : result;
                        }
                        else
                        {
                            result = str + (decimal.length > 0 ? '.'+decimal : '');
                        }
                    }
                    else
                    {
                        result = price;
                    }
                }
            }
            return result;
        }
    };

})();